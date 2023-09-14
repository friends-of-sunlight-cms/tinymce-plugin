<?php

use Sunlight\Core;
use Sunlight\User;
use SunlightExtend\Tinymce\Wysiwyg;

// load system core
require __DIR__ . '/../../../../system/bootstrap.php';
Core::init([
    'env' => Core::ENV_SCRIPT,
    'session_enabled' => true,
    'content_type' => 'text/javascript; charset=UTF-8'
]);

// get plugin instance
$pluginInstance = Core::$pluginManager->getPlugins()->get('extend/tinymce');
$config = $pluginInstance->getConfig();

// mode by priv
$active_mode = $config['editor_mode'];
if ($config['mode_by_priv']) {
    foreach (['limited', 'basic', 'advanced'] as $mode) {
        if (
            User::getLevel() >= $config['priv_min_' . $mode]
            && User::getLevel() <= $config['priv_max_' . $mode]
        ) {
            $active_mode = $mode;
        }
    }
}

// create default setup
$defaultWysiwyg = new Wysiwyg('.editor[data-editor-mode=default]');
call_user_func([$defaultWysiwyg, 'set' . ucfirst($active_mode) . 'Mode']);
$defaultSetup = $defaultWysiwyg->getProperties();

// file manager
/*
if (
    User::hasPrivilege('fileaccess')
    && Core::$pluginManager->getPlugins()->has('extend/wysiwyg-fm')
    && $config['filemanager']
) {
*/
    $fmAsset = Core::getBaseUrl()->getPath() . '/plugins/extend/wysiwyg-fm/public';
    $defaultSetup = array_merge($defaultSetup, [
            'relative_urls' => false,
            'remove_script_host' => true,
            'file_picker_types' => 'file image media',
            'external_filemanager_path' => $fmAsset . '/filemanager/',
            'filemanager_title' => 'Responsive Filemanager',
            'external_plugins' => [
                'filemanager' => $fmAsset . '/filemanager/plugin.min.js',
            ],
            'filemanager_access_key' => Core::$secret,
        ]
    );
//}

// create lite setup
$liteWysiwyg = (new Wysiwyg('.editor[data-editor-mode=lite]'))->setLimitedMode();
$liteSetup = $liteWysiwyg->getProperties();

?>
$(document).ready(tinymce.init(<?php echo json_encode($defaultSetup); ?>));

$(document).ready(tinymce.init(<?php echo json_encode($liteSetup); ?>));
