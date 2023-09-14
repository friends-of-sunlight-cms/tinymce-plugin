<?php

use Sunlight\Core;

return function (array $args) {
    $args['variables']['pluginWysiwyg'] = [
        'systemLang' => Core::$lang,
    ];
};
