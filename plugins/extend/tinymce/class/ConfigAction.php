<?php

namespace SunlightExtend\Tinymce;

use Fosc\Feature\Plugin\Config\FieldGenerator;
use Sunlight\Core;
use Sunlight\Plugin\Action\ConfigAction as BaseConfigAction;
use Sunlight\User;
use Sunlight\Util\ConfigurationFile;
use Sunlight\Util\Form;
use Sunlight\Util\Request;

class ConfigAction extends BaseConfigAction
{
    private const EDITOR_MODES = ['limited', 'basic', 'advanced'];

    protected function getFields(): array
    {
        $config = $this->plugin->getConfig();

        // filemanager plugin exists?
        $fm = Core::$pluginManager->getPlugins()->has('extend/wysiwyg-fm');

        return [
            'editor_mode' => [
                'label' => _lang('tinymce.config.editor_mode'),
                'input' => Form::select('config[editor_mode]', [
                    'limited' => _lang('tinymce.config.limited'),
                    'basic' => _lang('tinymce.config.basic'),
                    'advanced' => _lang('tinymce.config.advanced'),
                ], $config['editor_mode'], ['class' => 'inputsmall']),
            ],
            'filemanager' => [
                'label' => _lang('tinymce.config.filemanager'),
                'input' => Form::input('checkbox', 'config[filemanager]', '1', ['checked' => Form::loadCheckbox('config', $config['filemanager'], 'filemanager'), 'disabled' => !$fm]),
                'type' => 'checkbox'
            ],
            'editor_in_perex' => [
                'label' => _lang('tinymce.config.editor_in_perex'),
                'input' => Form::input('checkbox', 'config[editor_in_perex]', '1', ['checked' => Form::loadCheckbox('config', $config['editor_in_perex'], 'editor_in_perex')]),
                'type' => 'checkbox'
            ],
            'editor_in_boxes' => [
                'label' => _lang('tinymce.config.editor_in_boxes'),
                'input' => Form::input('checkbox', 'config[editor_in_boxes]', '1', ['checked' => Form::loadCheckbox('config', $config['editor_in_boxes'], 'editor_in_boxes')]),
                'type' => 'checkbox'
            ],
            'mode_by_priv' => [
                'label' => _lang('tinymce.config.mode_by_priv'),
                'input' => Form::input('checkbox', 'config[mode_by_priv]', '1', ['checked' => Form::loadCheckbox('config', $config['mode_by_priv'], 'mode_by_priv')]),
                'type' => 'checkbox'
            ],
            'priv_min_limited' => [
                'label' => _lang('tinymce.config.priv_min_limited'),
                'input' => Form::input('number', 'config[priv_min_limited]', Request::post('priv_min_limited', $config['priv_min_limited']), ['checked' => Form::loadCheckbox('config', $config['priv_min_limited'],'priv_min_limited'), 'min' => -1, 'max' => User::MAX_LEVEL, 'class' => 'inputsmall']),
                'type' => 'text',
            ],
            'priv_max_limited' => [
                'label' => _lang('tinymce.config.priv_max_limited'),
                'input' => Form::input('number', 'config[priv_max_limited]', Request::post('priv_max_limited', $config['priv_max_limited']), ['checked' => Form::loadCheckbox('config[priv_max_limited]', $config['priv_max_limited']), 'min' => -1, 'max' => User::MAX_LEVEL, 'class' => 'inputsmall']),
                'type' => 'text',
            ],
            'priv_min_basic' => [
                'label' => _lang('tinymce.config.priv_min_basic'),
                'input' => Form::input('number', 'config[priv_min_basic]', Request::post('priv_min_basic', $config['priv_min_basic']), ['checked' => Form::loadCheckbox('config[priv_min_basic]', $config['priv_min_basic']), 'min' => -1, 'max' => User::MAX_LEVEL, 'class' => 'inputsmall']),
                'type' => 'text',
            ],
            'priv_max_basic' => [
                'label' => _lang('tinymce.config.priv_max_basic'),
                'input' => Form::input('number', 'config[priv_max_basic]', Request::post('priv_max_basic', $config['priv_max_basic']), ['checked' => Form::loadCheckbox('config[priv_max_basic]', $config['priv_max_basic']), 'min' => -1, 'max' => User::MAX_LEVEL, 'class' => 'inputsmall']),
                'type' => 'text',
            ],
            'priv_min_advanced' => [
                'label' => _lang('tinymce.config.priv_min_advanced'),
                'input' => Form::input('number', 'config[priv_min_advanced]', Request::post('priv_min_advanced', $config['priv_min_advanced']), ['checked' => Form::loadCheckbox('config[priv_min_advanced]', $config['priv_min_advanced']), 'min' => -1, 'max' => User::MAX_LEVEL, 'class' => 'inputsmall']),
                'type' => 'text',
            ],
            'priv_max_advanced' => [
                'label' => _lang('tinymce.config.priv_max_advanced'),
                'input' => Form::input('number', 'config[priv_max_advanced]', Request::post('priv_max_advanced', $config['priv_max_advanced']), ['checked' => Form::loadCheckbox('config[priv_max_advanced]', $config['priv_max_advanced']), 'min' => -1, 'max' => User::MAX_LEVEL, 'class' => 'inputsmall']),
                'type' => 'text',
            ],
        ];
    }

    protected function mapSubmittedValue(ConfigurationFile $config, string $key, array $field, $value): ?string
    {
        if ($key == 'editor_mode') {
            $config[$key] = in_array($value, self::EDITOR_MODES) ? $value : 'basic';
            return null;
        }
        return parent::mapSubmittedValue($config, $key, $field, $value);
    }
}
