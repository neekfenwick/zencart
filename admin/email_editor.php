<?php
/**
 * @copyright Copyright 2003-2022 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: DrByte 2020 Dec 25 Modified in v1.5.8-alpha $
 */
require('includes/application_top.php');

/**
 * Undocumented function
 *
 * @param string $dir_check
 * @return array
 */
function zen_list_email_templates(string $dir_check) : array {
  // Check the base folder, and any language specific subfolder.
  $found = [];
  foreach ([ $dir_check, $dir_check . $_SESSION['language'] ] as $each_dir) {
    $files = scandir($each_dir);
    if (!$files) continue;
    // We expect files to be called email_template_something.blade.php
    // or email_template_something.text.blade.php
    $files = array_filter($files, function ($filename) {
      return preg_match('/^[^\._].*\.php$/i', $filename) == 1;
    });
    foreach ($files as $file) {
      $matches = [];
      // $is_html = substr_compare($file, '.blade.php', -10);
      // $is_text = substr_compare($file, '_text.blade.php', -15);
      if (preg_match('/(^.*?)(_text\.blade\.php|\.blade\.php)$/', $file, $matches)) {
        // We found a template file, record it in found array
        $basename = $matches[1];
        if (!array_key_exists($basename, $found)) {
          $found[$basename] = [];
        }
        if ($matches[2] == '_text.blade.php') {
          $found[$basename]['text'] = is_writeable($file);
        } else if ($matches[2] == '.blade.php') {
          $found[$basename]['html'] = is_writeable($file);
        }
      }
    }
  }
  uksort($found, function ($a, $b) { if ($a == $b) return 0; return ($a < $b) ? -1 : 1; });
  // cmp);
  return $found;

  // $directory_array = [];
  // if ($dir = @dir($dir_check)) {
  //   while ($file = $dir->read()) {
  //     if (!is_dir($dir_check . $file)) {
  //       if (preg_match('~^[^\._].*\.php$~i', $file) > 0) {
  //         $directory_array[] = $file;
  //       }
  //     }
  //   }
  //     if (sizeof($directory_array)) {
  //     sort($directory_array);
  //   }
  //   $dir->close();
  // }
  // return $directory_array;
}

if (empty($_SESSION['language'])) {
  $_SESSION['language'] = $language;
}

$languages_array = array();
$languages = zen_get_languages();
$lng_exists = false;
for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
  if ($languages[$i]['directory'] == $_SESSION['language'])
    $lng_exists = true;

  $languages_array[] = array('id' => $languages[$i]['directory'],
    'text' => $languages[$i]['name']);
}
if (!$lng_exists) {
  $_SESSION['language'] = $language;
}

// Resolve action being requested
$action = (isset($_GET['action']) ? $_GET['action'] : '');
if (isset($_GET['filename'])) {
  $_GET['filename'] = str_replace('../', '!HA' . 'CK' . 'ER_A' . 'LERT!', $_GET['filename']);
}

$directory_files = zen_list_email_templates(DIR_FS_EMAIL_TEMPLATES);

$za_lookup = array();
$za_lookup[] = array('id' => -1, 'text' => TEXT_INFO_SELECT_FILE);

// for ($i = 0, $n = sizeof($directory_files); $i < $n; $i++) {
//   $za_lookup[] = array('id' => $i, 'text' => $directory_files[$i]);
// }
foreach ($directory_files as $basename => $info) {
  $za_lookup[] = array('id' => $basename, 'text' => $basename);
}

if ($action == 'new_page') {
  $_GET['filename'] = $_GET['edit_name'];
  // $page = $_GET['edit_name'];

  // $directory_files = zen_display_files(DIR_FS_EMAIL_TEMPLATES);

  // $za_lookup = array();
  // for ($i = 0, $n = sizeof($directory_files); $i < $n; $i++) {
  //   $za_lookup[] = array('id' => $i, 'text' => $directory_files[$i]);
  // }
  // for ($i = 0 ; $i < count($za_lookup) ; $i ++) {
  //   if ($za_lookup[$i]['id'] == $page) {
  //     $_GET['filename'] = $za_lookup[$i]['text'];
  //   }
  // }

}

// define template specific file name defines
$file = isset($_GET['filename']) ? $_GET['filename'] : '';
?>
<?php
if (empty($_GET['action'])) {
  $_GET['action'] = '';
}
switch ($_GET['action']) {
  case 'set_editor':
    // Reset will be done by init_html_editor.php. Now we simply redirect to refresh page properly.
    $action = '';
    zen_redirect(zen_href_link(FILENAME_EMAIL_EDITOR));
    break;
  case 'save':
    if (($_GET['lngdir']) && ($_GET['filename'])) {
      if (file_exists($file)) {
        if (file_exists('bak' . $file)) {
          @unlink('bak' . $file);
        }
        @rename($file, 'bak' . $file);
        $new_file = fopen($file, 'w');
        $file_contents = stripslashes($_POST['file_contents']);
        fwrite($new_file, $file_contents, strlen($file_contents));
        fclose($new_file);
      }
      zen_record_admin_activity('Email-Editor was used to save changes to file ' . $file, 'info');
      zen_redirect(zen_href_link(FILENAME_EMAIL_EDITOR));
    }
    break;
}

?>
<!doctype html>
<html <?php echo HTML_PARAMS; ?>>
  <head>
    <?php require DIR_WS_INCLUDES . 'admin_html_head.php'; ?>
    <?php require DIR_WS_INCLUDES . 'javascript/email_editor.php'; ?>
    <?php if ($editor_handler != '') include ($editor_handler); ?>
  </head>
  <body>
    <!-- header //-->
    <?php require(DIR_WS_INCLUDES . 'header.php'); ?>
    <!-- header_eof //-->

    <!-- body //-->
    <div class="container-fluid">
      <h1><?php echo HEADING_TITLE . '&nbsp;' . $_SESSION['language']; ?></h1>
      <div class="row">
        <div class="col-sm-4 col-md-4">
            <?php

            echo zen_draw_form('new_page', FILENAME_EMAIL_EDITOR, '', 'get');
            echo zen_draw_pull_down_menu('edit_name', $za_lookup, '-1', 'onChange="this.form.submit();" class="form-control"');
            echo zen_hide_session_id();
            echo zen_draw_hidden_field('action', 'new_page');
            echo '</form>';
            ?>
        </div>
        <div class="col-sm-5 col-md-6">&nbsp;</div>
        <div class="col-sm-3 col-md-2">
            <?php
// toggle switch for editor
            echo zen_draw_form('set_editor_form', FILENAME_EMAIL_EDITOR, '', 'get', 'class="form-horizontal"');
            echo zen_draw_label(TEXT_EDITOR_INFO, 'reset_editor', 'class="control-label"');
            echo zen_draw_pull_down_menu('reset_editor', $editors_pulldown, $current_editor_key, 'onChange="this.form.submit();" class="form-control"');
            echo zen_draw_hidden_field('action', 'set_editor');
            echo zen_hide_session_id();
            echo '</form>';
            ?>
        </div>
      </div>
      <?php
// show editor
      if (isset($_GET['filename'])) {

        if ($_SESSION['language'] && $_GET['filename']) {
          $fileInfo = $directory_files[$file];
          $html_writable = isset($fileInfo['html']) && $fileInfo['html'] === false;
          $text_writable = isset($fileInfo['text']) && $fileInfo['text'] === false;
          $neither_writable = !$html_writable && !$text_writable;
      ?>
          <div class="row"><strong><?php echo TEXT_INFO_EDITING . '<br>' . $file . '<br>'; ?></strong></div>
          <?php echo zen_draw_form('language', FILENAME_EMAIL_EDITOR, 'lngdir=' . $_SESSION['language'] . '&filename=' . $_GET['filename'] . '&action=save'); ?>
          <div class="row">
            <div class="col-sm-6">
              HTML Email Content
            <?php
            function quickMessageStack ($message) {
              // global $messageStack;
              // $messageStack->reset();
              $messageStack = new messageStack();
              $messageStack->add($message, 'error');
              return $messageStack->output();
            }
            $file_contents = '';
            if (!$html_writable) {
              // echo 'File is not writable, your edits will not be saved successfully!';
              echo quickMessageStack(sprintf(ERROR_FILE_NOT_WRITEABLE, $file));
            } else {
              $file_contents = file_get_contents(DIR_FS_EMAIL_TEMPLATES . $file . '.blade.php');
              echo zen_draw_textarea_field('file_contents_html', 'soft', '100%', '30', htmlspecialchars($file_contents, ENT_COMPAT, CHARSET, TRUE), (($file_writeable) ? '' : 'readonly') . ' class="editorHook form-control"');
            }
            ?>
            <div class="col-sm-6">
              Plain Text Email Content
            <?php
            $file_contents = '';
            if (!$text_writable) {
              // echo 'File is not writable, your edits will not be saved successfully!';
              echo quickMessageStack(sprintf(ERROR_FILE_NOT_WRITEABLE, $file));
            } else {
              $file_contents = file_get_contents(DIR_FS_EMAIL_TEMPLATES . $file . '.blade.php');
              echo zen_draw_textarea_field('file_contents_html', 'soft', '100%', '30', htmlspecialchars($file_contents, ENT_COMPAT, CHARSET, TRUE), (($file_writeable) ? '' : 'readonly') . ' class="editorHook form-control"');
            }
            ?>

            <div class="col-sm-6 text-right">
              <?php
              if (!$neither_writable) {
              ?>
              <button type="submit" class="btn btn-primary"><?php echo IMAGE_SAVE; ?></button>
              <a href="<?php echo zen_href_link(FILENAME_EMAIL_EDITOR, 'edit_name=' . $_GET['edit_name'] . '&action=new_page'); ?>" class="btn btn-primary" role="button"><?php echo IMAGE_RESET; ?></a>
              <a href="<?php echo zen_href_link(FILENAME_EMAIL_EDITOR . '.php'); ?>" class="btn btn-default"><?php echo IMAGE_CANCEL; ?></a>
              <?php
              } else {
              ?>
                <a href="<?php echo zen_href_link(FILENAME_EMAIL_EDITOR, 'lngdir=' . $_SESSION['language']); ?>" class="btn btn-default" role="button"><?php echo IMAGE_BACK; ?></a>
              <?php
              }
              ?>
            </div>
            <div class="col-sm-6">&nbsp;</div>
            <?php echo '</form>'; ?>
            </div>
            <?php
          }
        } else {
          // TODO is this whole branch obsolete?
          $filename = $_SESSION['language'] . '.php';
          ?>
          <div class="row">
            <table class="table">
              <tr>
                <td><a href="<?php echo zen_href_link($_GET['filename'], 'lngdir=' . $_SESSION['language'] . '&filename=' . $filename); ?>"><strong><?php echo $filename; ?></strong></a></td>
                      <?php
                      $dir = dir(DIR_FS_CATALOG_LANGUAGES . $_SESSION['language']);
                      $left = false;
                      if ($dir) {
                        while ($file = $dir->read()) {
                          if (preg_match('~^[^\._].*\.php$~i', $file) > 0) {
                            echo '                <td class="smallText"><a href="' . zen_href_link($_GET['filename'], 'lngdir=' . $_SESSION['language'] . '&filename=' . $file) . '">' . $file . '</a></td>' . "\n";
                            if (!$left) {
                              echo '              </tr>' . "\n" .
                              '              <tr>' . "\n";
                            }
                            $left = !$left;
                          }
                        }
                        $dir->close();
                      }
                      ?>
              </tr>
            </table>
          </div>
          <?php
        }
        ?>
      <!-- body_text_eof //-->
    </div>
    <!-- body_eof //-->

    <!-- footer //-->
    <?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
    <!-- footer_eof //-->
  </body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
