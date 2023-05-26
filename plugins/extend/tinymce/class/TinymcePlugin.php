<?php

namespace SunlightExtend\Tinymce;

use Sunlight\Core;
use Sunlight\Plugin\Action\PluginAction;
use Sunlight\Plugin\ExtendPlugin;
use Sunlight\User;

class TinymcePlugin extends ExtendPlugin
{
    private const SUPPORTED_FORMATS = [
        'xml' => false,
        'css' => false,
        'js' => false,
        'json' => false,
        'php' => false,
        'php-raw' => false,
        'html' => true,
    ];

    /** @var bool */
    private $wysiwygDetected = false;

    public function onAdminInit(array $args): void
    {
        global $_admin;
        $_admin->wysiwygAvailable = true;
    }

    public function onAdminHead(array $args): void
    {
        $basePath = $this->getWebPath() . '/public';

        // register assets
        $args['js'][] = $basePath . '/tinymce/tinymce.min.js';
        $args['js'][] = $basePath . '/integration.php';
    }

    function onAdminEditor(array $args): void
    {
        global $_admin;

        $config = $this->getConfig();

        if (
            ($args['context'] === 'box-content' && $config['editor_in_boxes'] === false)
            || ($args['context'] === 'page-perex' && $config['editor_in_perex'] === false)
        ) {
            $args['options']['mode'] = 'code';
        }

        if (
            isset(self::SUPPORTED_FORMATS[$args['options']['format']])
            && $args['options']['mode'] === 'default'
            && $_admin->wysiwygAvailable
            && User::isLoggedIn()
            && User::$data['wysiwyg']
        ) {
            $this->enableEventGroup('tinymce');
        }
    }

    public function onCoreJavascript(array $args): void
    {
        $args['variables']['pluginWysiwyg'] = [
            'systemLang' => Core::$lang,
        ];
    }

    /**
     * ============================================================================
     *  EXTEND CONFIGURATION
     * ============================================================================
     */

    protected function getConfigDefaults(): array
    {
        return [
            'editor_mode' => 'basic',
            'mode_by_priv' => false,
            // privileges
            'priv_min_limited' => 1,
            'priv_max_limited' => 500,
            'priv_min_basic' => 600,
            'priv_max_basic' => 1000,
            'priv_min_advanced' => 10000,
            'priv_max_advanced' => 10001,
            // filemanager
            'filemanager' => false,
            'editor_in_boxes' => false,
            'editor_in_perex' => true,
        ];
    }

    public function getAction(string $name): ?PluginAction
    {
        if ($name === 'config') {
            return new ConfigAction($this);
        }
        return parent::getAction($name);
    }
}