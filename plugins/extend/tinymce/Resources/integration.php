<?php

use Sunlight\Core;
use Wysiwyg\Wysiwyg;

// load system core
require '../../../../system/bootstrap.php';
Core::init('../../../../', array(
    'env' => Core::ENV_SCRIPT,
    'session_enabled' => true,
    'content_type' => 'text/javascript; charset=UTF-8'
));
// get plugin instance
$pluginInstance = Core::$pluginManager->getExtend('tinymce');
$config = $pluginInstance->getConfig();

// mode by priv
$active_mode = $config->offsetGet('editor_mode');
if ($config->offsetGet('mode_by_priv')) {
    foreach (array('limited', 'basic', 'advanced') as $mode) {
        if (_priv_level >= $config->offsetGet('priv_min_' . $mode) && (_priv_level <= $config->offsetGet('priv_max_' . $mode))) {
            $active_mode = $mode;
        }
    }
}

// create setup
$wysiwyg = new Wysiwyg('textarea.editor:not([name=perex])');
// set dynamic editor mode
call_user_func(array($wysiwyg, 'set' . ucfirst($active_mode) . 'Mode'));
// get props
$setup = $wysiwyg->getProperties();

if (_priv_fileaccess && $config->offsetGet('filemanager')) {
    $setup = array_merge($setup, array(
        'relative_urls' => false,
        'remove_script_host' => true,
        'external_filemanager_path' => Core::$url . '/plugins/extend/tinymce/Resources/filemanager/',
        'filemanager_title' => 'Responsive Filemanager',
        'external_plugins' => array(
            'filemanager' => Core::$url . '/plugins/extend/tinymce/Resources/filemanager/plugin.min.js',
        ),
        'filemanager_access_key' => Core::$appId,
    ));
}

?>
$(document).ready(tinymce.init(<?php echo json_encode($setup); ?>));