<?php
/**
 * @copyright Copyright 2003-2022 Zen Cart Development Team
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: lat9 2022 May 05 New in v1.5.8-alpha $
 */

namespace Zencart\Traits;

use  Zencart\Events\EventDto;

trait NotifierManager
{
    /**
     * @var array of aliases
     */
    private $observerAliases = ['NOTIFY_ORDER_CART_SUBTOTAL_CALCULATE' => 'NOTIFIY_ORDER_CART_SUBTOTAL_CALCULATE'];

    public function getRegisteredObservers()
    {
        return EventDto::getInstance()->getObservers();
    }

    /**
     * method to notify observers that an event has occurred in the notifier object
     * Can optionally pass parameters and variables to the observer, useful for passing stuff which is outside of the 'scope' of the observed class.
     * Any of params 2-9 can be passed by reference, and will be updated in the calling location if the observer "update" function also receives them by reference
     *
     * @param string $eventID The event ID to notify.
     * @param mixed $param1 passed as value only.
     * @param mixed $param2 passed by reference.
     * @param mixed $param3 passed by reference.
     * @param mixed $param4 passed by reference.
     * @param mixed $param5 passed by reference.
     * @param mixed $param6 passed by reference.
     * @param mixed $param7 passed by reference.
     * @param mixed $param8 passed by reference.
     * @param mixed $param9 passed by reference.
     *
     * NOTE: The $param1 is not received-by-reference, but params 2-9 are.
     * NOTE: The $param1 value CAN be an array, and is sometimes typecast to be an array, but can also safely be a string or int etc if the notifier sends such and the observer class expects same.
     */
    function notify($eventID, $param1 = array(), &$param2 = null, &$param3 = null, &$param4 = null, &$param5 = null, &$param6 = null, &$param7 = null, &$param8 = null, &$param9 = null)
    {
        $this->logNotifier($eventID, $param1, $param2, $param3, $param4, $param5, $param6, $param7, $param8, $param9);

        $observers = $this->getRegisteredObservers();
        if (empty($observers)) {
            return;
        }

        // identify the event
        $actualEventId = $eventID;
        $matchMap = [$eventID, '*'];

        // Adjust for aliases
        // if the event fired by the notifier is old and has an alias registered
        $hasAlias = $this->eventIdHasAlias($eventID);
        if ($hasAlias) {
            // then lookup the correct new event name
            $eventAlias = $this->substituteAlias($eventID);
            // use the substituted event name in the list of matches
            $matchMap[] = $eventAlias;
            // and set the Actual event to the name that was originally attached to in the observer class
            // $actualEventId = $obs['eventID'];
        }

        // foreach ($observers as $key => $obs) {
        foreach ($matchMap as $eventToMatch) {
            if (!array_key_exists($eventToMatch, $observers)) {
                continue; // Nothing registered for this eventID
            }

            // Notify the listening observers that this event has been triggered
            $observersToTrigger = $observers[$eventToMatch];

            foreach ($observersToTrigger as $obs) {

                $methodsToCheck = [];
                // Check for a snake_cased method name of the notifier Event, ONLY IF it begins with "NOTIFY_" or "NOTIFIER_"
                $snake_case_method = strtolower($eventToMatch);
                if (preg_match('/^notif(y|ier)_/', $snake_case_method) && method_exists($obs, $snake_case_method)) {
                    $methodsToCheck[] = $snake_case_method;
                }
                // alternates are a camelCased version starting with "update" ie: updateNotifierNameCamelCased(), or just "update()"
                $methodsToCheck[] = 'update' . \base::camelize(strtolower($eventToMatch), true);
                $methodsToCheck[] = 'update';

                foreach ($methodsToCheck as $method) {
                    if (method_exists($obs, $method)) {
                        $obs->{$method}($this, $eventToMatch, $param1, $param2, $param3, $param4, $param5, $param6, $param7, $param8, $param9);
                        continue 2;
                    }
                }
                // If no update handler method exists then trigger an error so the problem is logged
                $className = (is_object($obs)) ? get_class($obs) : $obs;
                trigger_error('WARNING: No update() method (or matching alternative) found in the ' . $className . ' class for event ' . $actualEventId, E_USER_WARNING);
            }
        }
    }

    /**
     * Call observers listening for CONTENT_* events, so they can produce their output.
     *
     * @param string $eventID
     * @param array $params
     * @return void
     */
    // public function insertContent(string $eventID, $param1 = array(), &$param2 = null, &$param3 = null, &$param4 = null, &$param5 = null, &$param6 = null, &$param7 = null, &$param8 = null, &$param9 = null)
    public function insertContent(string $eventID, &...$params)
    {
        global $l;

        // If the eventID looks like 'define_', just output a define page by that name.
        if (str_starts_with($eventID, 'define_')) {
            $this->outputDefinePage($eventID);
            return;
        }

        // We may have a registered observer for this eventID...
        $observers = $this->getRegisteredObservers();
        if (empty($observers) || !array_key_exists($eventID, $observers)) {
            return;
        }

        // Act on each observer registered for this eventID.
        foreach ($observers[$eventID] as $obs) {

            // If there is a mapping provided from event name to define page, use it and continue.
            if (!empty($obs->contentDefinePageMap) && array_key_exists($eventID, $obs->contentDefinePageMap)) {
                $this->outputDefinePage($obs->contentDefinePageMap[$eventID]);
                continue;
            }

            // Call the observer's handler function.
            $snake_case_method = strtolower($eventID);
            if (method_exists($obs, $snake_case_method)) {
                $obs->{$snake_case_method}($this, $eventID, $params);
            } else {
                // If no update handler method exists then trigger an error so the problem is logged
                $className = (is_object($obs)) ? get_class($obs) : $obs;
                trigger_error('WARNING: No handler method found in the ' . $className . ' class for event ' . $eventID, E_USER_WARNING);
            }
        }
    }

    /**
     * Write out a named define page, wrapping it in a <div class="define-page"> with extra
     * classes and params defined by arguments.
     *
     * @param string $define_page_name The name of the define page, e.g. define_foo_bar.php
     * @param array|null $classList Any classes to be added to the wrapper e.g. [ 'warning' ]
     * @param string|null $params Any other params to be inserted into the <div> e.g. "id='foobar'"
     * @return void
     */
    protected function outputDefinePage (string $define_page_name, ?array $classList = [], ?string $params = null) {
        $define_page = zen_get_file_directory(DIR_WS_LANGUAGES . $_SESSION['language'] . '/html_includes/', $define_page_name, false);
        if (!file_exists($define_page)) {
            trigger_error("NotifierManager::outputDefinePage: define page '$define_page' does not exist.");
            return;
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

    protected function logNotifier($eventID, $param1, $param2, $param3, $param4, $param5, $param6, $param7, $param8, $param9)
    {
        if (!defined('NOTIFIER_TRACE') || empty(NOTIFIER_TRACE) || NOTIFIER_TRACE === 'false' || NOTIFIER_TRACE === 'Off') {
            return;
        }
        global $zcDate;

        $file = DIR_FS_LOGS . '/notifier_trace.log';
        $paramArray = (is_array($param1) && count($param1) == 0) ? array() : array('param1' => $param1);
        for ($i = 2; $i < 10; $i++) {
            $param_n = "param$i";
            if ($$param_n !== null) {
                $paramArray[$param_n] = $$param_n;
            }
        }
        global $this_is_home_page, $PHP_SELF;
        $main_page = (isset($this_is_home_page) && $this_is_home_page)
            ? 'index-home'
            : ((IS_ADMIN_FLAG) ? basename($PHP_SELF)
                : (isset($_GET['main_page']) ? $_GET['main_page'] : ''));
        $output = '';
        if (count($paramArray)) {
            $output = ', ';
            if (NOTIFIER_TRACE === 'var_export' || NOTIFIER_TRACE === 'var_dump' || NOTIFIER_TRACE === 'true') {
                $output .= var_export($paramArray, true);
            } elseif (NOTIFIER_TRACE === 'print_r' || NOTIFIER_TRACE === 'On' || NOTIFIER_TRACE === true) {
                $output .= print_r($paramArray, true);
            }
        }
        error_log($zcDate->output("%Y-%m-%d %H:%M:%S") . ' [main_page=' . $main_page . '] ' . $eventID . $output . "\n", 3, $file);
    }

    private function eventIdHasAlias($eventId)
    {
        if (array_key_exists($eventId, $this->observerAliases)) {
            return true;
        }
        return false;
    }

    private function substituteAlias($eventId)
    {
        if (array_key_exists($eventId, $this->observerAliases)) {
            return $eventId;
        }
        return $this->observerAliases[$eventId];
    }
}
