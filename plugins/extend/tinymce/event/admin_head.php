<?php

return function (array $args) {
    // register assets
    $args['js'][] = $this->getAssetPath('vendor/tinymce/tinymce/tinymce.min.js');
    $args['js'][] = $this->getAssetPath('public/integration.php');
};
