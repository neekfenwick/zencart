<?php
/** 
 * File contains just the notifier class
 *
 * @copyright Copyright 2003-2022 Zen Cart Development Team
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: DrByte 2020 Jul 10 Modified in v1.5.8-alpha $
 */
/**
 * class notifier is a concrete implemetation of the abstract base class
 *
 * it can be used in procedural (non OOP) code to set up an observer
 * see the observer/notifier tutorial for more details.
 *
 */
if (!defined('IS_ADMIN_FLAG')) {
  die('Illegal Access');
}
class ContentNotifier extends base {

    /**
     * Requires a define page, and calls regisistered Observers for content output.
     *
     * @param [type] $eventID
     * @param array $param1
     * @param [type] ...$params
     * @return void
     */
    function insertContent($eventID, $param1 = array(), &...$params)
    {
        global $l;

        // Output any 'define_' page, e.g. CONTENT_FOO will output define_foo.php
        $define_page_name = strtolower(str_replace('CONTENT_', 'DEFINE_', $eventID));
        $this->outputDefinePage($define_page_name);

        $observers = $this->getRegisteredObservers();
        if (empty($observers)) {
            return;
        }
        foreach ($observers as $key => $obs) {
            // Skip observer if it's not registered for a CONTENT_ event.
            if (!str_starts_with($obs['eventID'], 'CONTENT_')) {
                continue;
            }

            // If there is a mapping provided on the observer from event name to define page, use it and continue.
            if (!empty($obs['obs']->contentDefinePageMap) && array_key_exists($eventID, $obs['obs']->contentDefinePageMap)) {
                $this->outputDefinePage($obs['obs']->contentDefinePageMap[$eventID]);
                continue;
            }

            // Notify the listening observer that this event has been triggered
            $snake_case_method = strtolower($eventID);
            if (method_exists($obs['obs'], $snake_case_method)) {
                $obs['obs']->{$snake_case_method}($this, $eventID, $params);
            } else {
                // If no update handler method exists then trigger an error so the problem is logged
                $className = (is_object($obs['obs'])) ? get_class($obs['obs']) : $obs['obs'];
                trigger_error('WARNING: No update() method (or matching alternative) found in the ' . $className . ' class for event ' . $actualEventId, E_USER_WARNING);
            }
        }
    }

    /**
     * Write out a named define page, wrapping it in a <div class="define-page"> with extra
     * classes and params defined by arguments.
     *
     * @param string $define_page_name The name of the define page, e.g. define_foo_bar.php
     * @param array $classList Any classes to be added to the wrapper e.g. [ 'warning' ]
     * @param string $params Any other params to be inserted into the <div> e.g. "id='foobar'"
     * @return void
     */
    protected function outputDefinePage (string $define_page_name, array $classList = [], string $params = null) {
        $define_page = zen_get_file_directory(DIR_WS_LANGUAGES . $_SESSION['language'] . '/html_includes/', $define_page_name, false);
        if (!file_exists($define_page)) {
            return; // no action
        }
        if (empty($classList)) {
            $classList = [];
        }
        if (!empty($params)) {
            $params = ' ' . $params;
        }
        $classList[] = 'define-page';
        ob_start();
        echo '<div class="' . implode(' ', $classList) . '"' . $params . '>';
        require($define_page);
        echo '</div>';
        ob_end_flush();
    }
}
?>