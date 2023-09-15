<?php

namespace SunlightExtend\Tinymce;

use Fosc\Feature\Plugin\Config\FieldGenerator;
use Sunlight\Core;
use Sunlight\Plugin\Action\ConfigAction as BaseConfigAction;
use Sunlight\User;
use Sunlight\Util\ConfigurationFile;
use Sunlight\Util\Form;

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
                'input' => _buffer(function () use ($config) { ?>
                    <select name="config[editor_mode]" class="inputsmall">
                        <?php foreach (self::EDITOR_MODES as $mode): ?>
                            <option value="<?= _e($mode) ?>" <?= Form::selectOption($config['editor_mode'] === $mode) ?>><?= _lang('tinymce.config.' . _e($mode)) ?></option>
                        <?php endforeach; ?>
                    </select>
                <?php }),
            ],
            'filemanager' => [
                'label' => _lang('tinymce.config.filemanager'),
                'input' => '<input type="checkbox" name="config[filemanager]" value="1"' . Form::activateCheckbox($config['filemanager']) . (!$fm ? ' disabled' : '') . '>',
                'type' => 'checkbox'
            ],
            'editor_in_perex' => [
                'label' => _lang('tinymce.config.editor_in_perex'),
                'input' => '<input type="checkbox" name="config[editor_in_perex]" value="1"' . Form::activateCheckbox($config['editor_in_perex']) . '>',
                'type' => 'checkbox'
            ],
            'editor_in_boxes' => [
                'label' => _lang('tinymce.config.editor_in_boxes'),
                'input' => '<input type="checkbox" name="config[editor_in_boxes]" value="1"' . Form::activateCheckbox($config['editor_in_boxes']) . '>',
                'type' => 'checkbox'
            ],
            'mode_by_priv' => [
                'label' => _lang('tinymce.config.mode_by_priv'),
                'input' => '<input type="checkbox" name="config[mode_by_priv]" value="1"' . Form::activateCheckbox($config['mode_by_priv']) . '>',
                'type' => 'checkbox'
            ],
            'priv_min_limited' => [
                'label' => _lang('tinymce.config.priv_min_limited'),
                'input' => '<input type="number" name="config[priv_min_limited]" min="-1" max="' . User::MAX_LEVEL . '" value="' . Form::restorePostValue('priv_min_limited', $config['priv_min_limited'], false) . '" class="inputsmall">',
                'type' => 'text',
            ],
            'priv_max_limited' => [
                'label' => _lang('tinymce.config.priv_max_limited'),
                'input' => '<input type="number" name="config[priv_max_limited]" min="-1" max="' . User::MAX_LEVEL . '" value="' . Form::restorePostValue('priv_max_limited', $config['priv_max_limited'], false) . '" class="inputsmall">',
                'type' => 'text',
            ],
            'priv_min_basic' => [
                'label' => _lang('tinymce.config.priv_min_basic'),
                'input' => '<input type="number" name="config[priv_min_basic]" min="-1" max="' . User::MAX_LEVEL . '" value="' . Form::restorePostValue('priv_min_basic', $config['priv_min_basic'], false) . '" class="inputsmall">',
                'type' => 'text',
            ],
            'priv_max_basic' => [
                'label' => _lang('tinymce.config.priv_max_basic'),
                'input' => '<input type="number" name="config[priv_max_basic]" min="-1" max="' . User::MAX_LEVEL . '" value="' . Form::restorePostValue('priv_max_basic', $config['priv_max_basic'], false) . '" class="inputsmall">',
                'type' => 'text',
            ],
            'priv_min_advanced' => [
                'label' => _lang('tinymce.config.priv_min_advanced'),
                'input' => '<input type="number" name="config[priv_min_advanced]" min="-1" max="' . User::MAX_LEVEL . '" value="' . Form::restorePostValue('priv_min_advanced', $config['priv_min_advanced'], false) . '" class="inputsmall">',
                'type' => 'text',
            ],
            'priv_max_advanced' => [
                'label' => _lang('tinymce.config.priv_max_advanced'),
                'input' => '<input type="number" name="config[priv_max_advanced]" min="-1" max="' . User::MAX_LEVEL . '" value="' . Form::restorePostValue('priv_max_advanced', $config['priv_max_advanced'], false) . '" class="inputsmall">',
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
