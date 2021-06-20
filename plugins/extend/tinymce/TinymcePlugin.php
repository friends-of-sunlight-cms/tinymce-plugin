<?php

namespace SunlightExtend\Tinymce;

use Sunlight\Core;
use Sunlight\Plugin\Action\ConfigAction;
use Sunlight\Plugin\ExtendPlugin;
use Sunlight\Util\Form;
use Sunlight\Plugin\Action\PluginAction;

/**
 * TinyMCE plugin
 *
 * @author Jirka Daněk <jdanek.eu>
 */
class TinymcePlugin extends ExtendPlugin
{
    private $wysiwygDetected = false;

    protected function getConfigDefaults(): array
    {
        return array(
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
        );
    }

    /**
     * @param array $args
     */
    public function onHead(array $args)
    {
        if (_logged_in && !$this->isDisabled() && !$this->wysiwygDetected && (bool)Core::$userData['wysiwyg'] === true) {
            $args['js'][] = $this->getWebPath() . '/Resources/tinymce/tinymce.min.js';
            $args['js'][] = $this->getWebPath() . '/Resources/integration.php';
        }
    }

    /**
     * @param $args
     */
    public function onWysiwyg($args)
    {
        if ($args['available']) {
            $this->wysiwygDetected = true;
        } elseif (_logged_in && !$this->isDisabled() && (bool)Core::$userData['wysiwyg']) {
            $args['available'] = true;
        }
    }

    /**
     * @param array $args
     */
    public function onCoreJavascript(array $args)
    {
        $args['variables']['pluginWysiwyg'] = array(
            'systemLang' => _language,
        );
    }

    public function getAction($name): ?PluginAction
    {
        if ($name == 'config') {
            return new CustomConfig($this);
        }
        return parent::getAction($name);
    }
}


class CustomConfig extends ConfigAction
{
    protected function getFields(): array
    {

        $modes = array(
            _lang('tinymce.limited') => 'limited',
            _lang('tinymce.basic') => 'basic',
            _lang('tinymce.advanced') => 'advanced'
        );

        $fields = array(
            'editor_mode' => array(
                'label' => _lang('tinymce.mode'),
                'input' => $this->createSelect('editor_mode', $modes, $this->plugin->getConfig()->offsetGet('editor_mode')),
                'type' => 'text'
            ),
            'filemanager' => array(
                'label' => _lang('tinymce.filemanager'),
                'input' => $this->createInput('checkbox', 'filemanager'),
                'type' => 'checkbox'
            ),
            'mode_by_priv' => array(
                'label' => _lang('tinymce.mode_by_priv'),
                'input' => $this->createInput('checkbox', 'mode_by_priv'),
                'type' => 'checkbox'
            ),
        );

        foreach (array('limited', 'basic', 'advanced') as $v) {
            foreach (array('min', 'max') as $v2) {
                $name = 'priv_' . $v2 . '_' . $v;
                $fields[$name] = array(
                    'label' => _lang('tinymce.' . $name),
                    'input' => $this->createInput('number', $name, array('min' => -1, 'max' => _priv_max_level)),
                    'type' => 'text'
                );
            }
        }

        return $fields;
    }

    private function createSelect($name, $options, $default)
    {
        $result = "<select name='config[" . $name . "]'>";
        foreach ($options as $k => $v) {
            $result .= "<option value='" . $v . "'" . ($default == $v ? " selected" : "") . ">" . $k . "</option>";
        }
        $result .= "</select>";
        return $result;
    }

    private function createInput($type, $name, $attributes = null)
    {
        $result = "";
        $attr = array();

        if (is_array($attributes)) {
            foreach ($attributes as $k => $v) {
                if (is_integer($k)) {
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
