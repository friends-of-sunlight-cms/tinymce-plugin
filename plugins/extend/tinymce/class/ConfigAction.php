<?php

namespace SunlightExtend\Tinymce;

use Fosc\Feature\Plugin\Config\FieldGenerator;
use Sunlight\Core;
use Sunlight\Plugin\Action\ConfigAction as BaseConfigAction;
use Sunlight\User;

class ConfigAction extends BaseConfigAction
{
    protected function getFields(): array
    {
        $modes = [
            'limited' => _lang('tinymce.config.limited'),
            'basic' => _lang('tinymce.config.basic'),
            'advanced' => _lang('tinymce.config.advanced')
        ];

        // filemanager plugin exists?
        $fmAttr = [];
        if (!Core::$pluginManager->getPlugins()->has('extend/wysiwyg-fm')) {
            $fmAttr[] = 'disabled';
        }

        $privNames = [];
        foreach (['limited', 'basic', 'advanced'] as $k => $v) {
            foreach (['min', 'max'] as $v2) {
                $privNames[] = 'priv_' . $v2 . '_' . $v;
            }
        }

        $langPrefix = "%p:tinymce.config";

        $gen = new FieldGenerator($this->plugin);
        $gen->generateField('editor_mode', $langPrefix, '%select', [
            'class' => 'inputsmall',
            'select_options' => $modes,
        ], 'text')
            ->generateField('filemanager', $langPrefix, '%checkbox', $fmAttr)
            ->generateFields([
                'editor_in_perex',
                'editor_in_boxes',
                'mode_by_priv'
            ], $langPrefix, '%checkbox')
            ->generateFields($privNames, $langPrefix, '%number', ['min' => -1, 'max' => User::MAX_LEVEL]);

        return $gen->getFields();
    }
}
