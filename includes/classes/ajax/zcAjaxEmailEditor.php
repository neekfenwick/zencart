<?php
/**
 * zcAjaxEmailEditor
 *
 * @copyright Copyright 2003-2023 Zen Cart Development Team
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: Scott C Wilson 2023 Mar 14 Modified in v1.5.8a $
 */
require_once(DIR_FS_CATALOG . DIR_WS_CLASSES . 'PhiloBlade.php');
use Zencart\LanguageLoader\LanguageLoaderFactory;
use Philo\Blade\Blade;

class zcAjaxEmailEditor extends base
{
    protected function setCommonFields() {
        header('Content-Type', 'application/json');
    }

    public function getDefaultTemplateData()
    {
        $module = $_POST['module'];
        $templateData = $this->generateTemplateData($module);
        return $templateData;
    }

    public function generatePreview()
    {
        global $languageLoaderFactory, $template_dir;
        $data = file_get_contents('php://input');
        $payload = json_decode($data);
        if ($payload == null) {
            return [ 'error' => 'Invalid payload.' ];
        }

        $module = $payload->name;
        if (empty($module)) {
            return [ 'error' => 'No email module name provided.' ];
        }
        if (empty($payload->template_data)) {
            return [ 'error' => 'No templateData provided.' ];
        }

        // Load language strings that might be used by the email template
        // NB assume $module matches the page name you want to load strings for,
        // e.g. when createing an account $module='create_account' to load lang.create_account.php
        $languageLoaderFactory = new LanguageLoaderFactory();
        $languageLoader = $languageLoaderFactory->make('catalog', null, $module, $template_dir);
        $languageLoader->loadInitialLanguageDefines();
        $languageLoader->loadLanguageForView();
        $languageLoader->finalizeLanguageDefines();

        try {
            // Create 'preview' email template files on disk for the email system to use for this time only.
            // Alternatively, change the .._from_blade_template function to take template body overrides.
            // $templateData = $this->generateTemplateData($module);
            // $templateData = $this->sanitiseTemplateData($payload->template_data);
            $templateData = [];
            $this->object_to_array($payload->template_data, $templateData);
            // $email = zen_build_html_email_from_blade_template($module, $templateData);

            $blade = new Blade(DIR_FS_EMAIL_TEMPLATES, DIR_FS_SQL_CACHE);
            file_put_contents(DIR_FS_EMAIL_TEMPLATES . '/email_template_preview.blade.php', $payload->template_html);
            file_put_contents(DIR_FS_EMAIL_TEMPLATES . '/email_template_preview_text.blade.php', $payload->template_text);

            // filenames need to have the path and suffix stripped so the Blade engine can find them
            // $html_template_filename = str_replace(DIR_FS_EMAIL_TEMPLATES, '', $html_template_filename);
            // try {
            $html_output = $blade->view()->make('email_template_preview', $templateData)->render();
            $text_output = $blade->view()->make('email_template_preview_text', $templateData)->render();

            return [
                'html' => $html_output,
                'text' => $text_output
            ];
        } catch (Illuminate\View\ViewException $ex) {
            // Try to quote context of the error, around $ex->line
            $file_contents = file_get_contents($ex->getFile());
            $line = $ex->getLine();
            $file_lines = explode("\n", $file_contents);
            $sliced = array_slice($file_lines, $line - 2, 5);
            // $sliced[3] = 'APPROX ERROR LOCATION ==> ' . $sliced[3];
            foreach ($sliced as $idx => &$eachLine) {
                $realLine = $idx + $line - 2;
                $eachLine = "Line $realLine: " . $eachLine;
            }
            return [
                'view_error' => $ex->getMessage(),
                'lines' => $sliced
             ];
        } catch (Throwable $ex) {
            return [ 'error' => $ex->getMessage() ];
        }
    }

    protected function sanitiseTemplateData ($templateData) {
        $data = (array)$templateData;
        foreach ($data as $key => &$value) {
            $dbg = gettype($value);
            if (gettype($value) == 'array') {
                $value = $this->sanitiseTemplateData($value);
            } else if (gettype($value) == 'object') {
                    $value = (array)$value;
            } else {
                // nothing?
            }
        }
        return $data;
    }
    function object_to_array($obj, &$arr){

        if(!is_object($obj) && !is_array($obj)){
            $arr = $obj;
            return $arr;
        }

        foreach ($obj as $key => $value) {
            if (!empty($value)) {
                $arr[$key] = array();
                $this->object_to_array($value, $arr[$key]);
            } else {
                $arr[$key] = $value;
            }
        }

        return $arr;
    }

    /**
    * Build a default block of template data for the given module, similar to
    * how the calling code would prepare the template in a real world situation.
    *
    * @param string $module
    * @return void
    */
    protected function generateTemplateData(string $module)
    {
        $block = [];

        //define some additional html message blocks available to templates, then build the html portion.
        $block['EMAIL_TO_NAME'] = 'dummy value';
        $block['EMAIL_TO_ADDRESS'] = 'dummy value';
        $block['EMAIL_SUBJECT'] = 'dummy value';
        $block['EMAIL_FROM_NAME'] = 'dummy value';
        $block['EMAIL_FROM_ADDRESS'] = 'dummy value';

        email_add_common_data($block, 'en');

        // TODO: Consider loading these from small datafiles, to reduce code change when templates change.
        $extra_datafiles_dir = DIR_WS_INCLUDES . 'extra_datafiles/email_editor/';
        $module_datafile = $extra_datafiles_dir . $module . '.json';
        if (file_exists($module_datafile)) {
            $data = file_get_contents($module_datafile);
            $obj = json_decode($data);
            foreach ($obj as $key => $value) {
                $block[$key] = $value;
            }
        }

        // $mixin = [];
        // switch ($module) {
        //     $block['EMAIL_MESSAGE']
        // }

        return $block;
    }

    public function saveTemplates()
    {
        $this->setCommonFields();
        $lngdir = $_SESSION['language'];
        $data = file_get_contents('php://input');
        $payload = json_decode($data);
        if ($payload == null) {
            return [ 'error' => 'Invalid payload.' ];
        }

        if (!empty($lngdir) && !empty($_GET['module'])) {
            $file = $_GET['module'];
            $html_filename = DIR_FS_EMAIL_TEMPLATES . 'email_template_' . $file . '.blade.php';
            $text_filename = DIR_FS_EMAIL_TEMPLATES . 'email_template_' . $file . '_text.blade.php';

            $this->saveTemplate($payload->template_html, $file, '.blade.php');
            $this->saveTemplate($payload->template_text, $file, '_text.blade.php');

            zen_record_admin_activity('Email-Editor was used to save changes to file ' . $file, 'info');
        }
    }

    protected function saveTemplate($contents, $file, $filename_part)
    {
        $filename = DIR_FS_EMAIL_TEMPLATES . 'email_template_' . $file . $filename_part;
        $bak_filename = DIR_FS_EMAIL_TEMPLATES . 'bakemail_template_' . $file . $filename_part;
        if (file_exists($filename)) {
            if (file_exists('bak' . $filename)) {
                @unlink('bak' . $filename);
            }
            @rename($filename, $bak_filename);
            file_put_contents($filename, $contents);
        }
    }
}
