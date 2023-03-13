<?php

namespace SunlightExtend\Tinymce;

use Sunlight\Core;
use Sunlight\Plugin\Action\ConfigAction;
use Sunlight\Plugin\Action\PluginAction;
use Sunlight\Plugin\ExtendPlugin;
use Sunlight\User;
use Sunlight\Util\Form;

class TinymcePlugin extends ExtendPlugin
{
    /** @var bool */
    private $wysiwygDetected = false;

    public function onHead(array $args): void
    {
        if (User::isLoggedIn() && !$this->isDisabled() && !$this->wysiwygDetected && (bool)User::$data['wysiwyg'] === true) {
            $args['js'][] = $this->getWebPath() . '/resources/tinymce/tinymce.min.js';
            $args['js'][] = $this->getWebPath() . '/resources/integration.php';
        }
    }

    public function onWysiwyg(array $args): void
    {
        if ($args['available']) {
            $this->wysiwygDetected = true;
        } elseif (User::isLoggedIn() && !$this->isDisabled() && (bool)User::$data['wysiwyg'] === true) {
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
        ];
    }

    public function getAction(string $name): ?PluginAction
    {
        if ($name === 'config') {
            return new CustomConfig($this);
        }
        return parent::getAction($name);
    }
}


class CustomConfig extends ConfigAction
{
    protected function getFields(): array
    {

        $modes = [
            _lang('tinymce.limited') => 'limited',
            _lang('tinymce.basic') => 'basic',
            _lang('tinymce.advanced') => 'advanced'
        ];

        // filemanager plugin exists?
        $fmAttr = [];
        if (!Core::$pluginManager->getPlugins()->has('extend/wysiwyg-fm')) {
            $fmAttr[] = 'disabled';
        }

        $fields = [
            'editor_mode' => [
                'label' => _lang('tinymce.mode'),
                'input' => $this->createSelect('editor_mode', $modes, $this->plugin->getConfig()->offsetGet('editor_mode')),
                'type' => 'text'
            ],
            'filemanager' => [
                'label' => _lang('tinymce.filemanager'),
                'input' => $this->createInput('checkbox', 'filemanager', $fmAttr),
                'type' => 'checkbox'
            ],
            'mode_by_priv' => [
                'label' => _lang('tinymce.mode_by_priv'),
                'input' => $this->createInput('checkbox', 'mode_by_priv'),
                'type' => 'checkbox'
            ],
        ];

        foreach (['limited', 'basic', 'advanced'] as $v) {
            foreach (['min', 'max'] as $v2) {
                $name = 'priv_' . $v2 . '_' . $v;
                $fields[$name] = [
                    'label' => _lang('tinymce.' . $name),
                    'input' => $this->createInput('number', $name, ['min' => -1, 'max' => User::MAX_LEVEL]),
                    'type' => 'text'
                ];
            }
        }

        return $fields;
    }

    private function createSelect($name, $options, $default): string
    {
        $result = "<select name='config[" . $name . "]'>";
        foreach ($options as $k => $v) {
            $result .= "<option value='" . $v . "'" . ($default == $v ? " selected" : "") . ">" . $k . "</option>";
        }
        $result .= "</select>";
        return $result;
    }

    private function createInput($type, $name, $attributes = null): string
    {
        $result = "";
        $attr = [];

        if (is_array($attributes)) {
            foreach ($attributes as $k => $v) {
                if (is_int($k)) {
                    $attr[] = $v . '=' . $v;
                } else {
                    $attr[] = $k . '=' . $v;
                }
            }
        }

        if ($type === 'checkbox') {
            $result = '<input type="checkbox" name="config[' . $name . ']" value="1"' . implode(' ', $attr) . Form::activateCheckbox($this->plugin->getConfig()->offsetGet($name)) . '>';
        } else {
            $result = '<input type="' . $type . '" name="config[' . $name . ']" value="' . $this->plugin->getConfig()->offsetGet($name) . '"' . implode(' ', $attr) . '>';
        }

        return $result;
    }
}
