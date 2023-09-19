<?php

use Sunlight\User;

return function (array $args) {
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
        isset($this->getExtraOption('supported_formats')[$args['options']['format']])

        && $args['options']['mode'] === 'default'
        && $_admin->wysiwygAvailable
        && User::isLoggedIn()
        && User::$data['wysiwyg']
    ) {
        $this->enableEventGroup('tinymce');
    }
};
