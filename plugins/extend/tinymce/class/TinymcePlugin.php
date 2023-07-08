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
            || (
                ($args['context'] === 'page-perex' || $args['context'] === 'article-perex')
                && $config['editor_in_perex'] === false
            )
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
}