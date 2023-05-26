<?php

namespace SunlightExtend\Tinymce;

use Sunlight\Core;
use Sunlight\Plugin\Action\ConfigAction as BaseConfigAction;
use Sunlight\User;
use Sunlight\Util\Form;

class ConfigAction extends BaseConfigAction
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
            'editor_in_perex' => [
                'label' => _lang('tinymce.editor_in_perex'),
                'input' => $this->createInput('checkbox', 'editor_in_perex'),
                'type' => 'checkbox'
            ],
            'editor_in_boxes' => [
                'label' => _lang('tinymce.editor_in_boxes'),
                'input' => $this->createInput('checkbox', 'editor_in_boxes'),
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
