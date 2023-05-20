<?php

namespace SunlightExtend\Tinymce;

use Sunlight\Admin\AdminState;
use Sunlight\Core;
use Sunlight\Plugin\Action\PluginAction;
use Sunlight\Plugin\ExtendPlugin;
use Sunlight\Plugin\Plugin;
use Sunlight\User;

class TinymcePlugin extends ExtendPlugin
{
    /** @var bool */
    private $wysiwygDetected = false;

    public function onHead(array $args): void
    {
        /** @var AdminState $_admin */
        global $_admin;
        if (
            User::isLoggedIn()
            && !$this->hasStatus(Plugin::STATUS_DISABLED)
            && !$this->wysiwygDetected
            && (bool)User::$data['wysiwyg'] === true
        ) {
            // disable display of editor in boxes (optional)
            if (
                $_admin->currentModule === 'content-boxes-edit'
                && $this->getConfig()->offsetGet('editor_in_boxes') === false
            ) {
                return;
            }

            // register assets
            $args['js'][] = $this->getWebPath() . '/public/tinymce/tinymce.min.js';
            $args['js'][] = $this->getWebPath() . '/public/integration.php';
        }
    }

    public function onWysiwyg(array $args): void
    {
        if ($args['available']) {
            $this->wysiwygDetected = true;
        } elseif (User::isLoggedIn() && !$this->hasStatus(Plugin::STATUS_DISABLED) && (bool)User::$data['wysiwyg'] === true) {
            $args['available'] = true;
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