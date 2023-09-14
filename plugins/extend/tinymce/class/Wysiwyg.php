<?php

namespace SunlightExtend\Tinymce;

use Sunlight\Core;
use Sunlight\Router;
use Sunlight\User;

class Wysiwyg
{
    /** @var array */
    public $properties = [];

    public function __construct($selector = '')
    {
        $this->properties = [
            'selector' => '.editor',
            'language' => Core::$lang,
            'relative_urls' => false,
            'document_base_url' => Core::getBaseUrl()->build(),
            'menubar' => false,
            'theme' => 'modern',
            'plugins' => [
                'advlist autolink lists link ' . (User::hasPrivilege('fileaccess') ? 'image' : '') . ' charmap print preview anchor textcolor',
                'searchreplace visualblocks code fullscreen',
                'insertdatetime media table contextmenu paste code help wordcount'
            ],
            'toolbar' => 'undo redo | bold italic forecolor |  formatselect  | removeformat help',
            'entity_encoding' => 'raw',
            'image_advtab' => false,
            //'content_css' => array('//www.tinymce.com/css/codepen.min.css'),
            'content_css' => [Router::path('plugins/extend/tinymce/public/tinymce/custom_css/custom_codepen.min.css')],
        ];

        if ($selector != '') {
            $this->properties['selector'] = $selector;
        }
    }

    public function setLimitedMode(): Wysiwyg
    {
        // limited is default
        return $this;
    }

    public function setBasicMode(): Wysiwyg
    {
        $this->properties['plugins'] = [
            'advlist autolink lists link ' . (User::hasPrivilege('fileaccess') ? 'image' : '') . ' charmap print preview anchor textcolor',
            'searchreplace visualblocks code fullscreen',
            'insertdatetime media table contextmenu paste code help wordcount'
        ];
        $this->properties['toolbar'] = 'insert | undo redo |  formatselect | bold italic forecolor backcolor  | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | code help';
        return $this;
    }

    public function setAdvancedMode(): Wysiwyg
    {
        $this->properties['plugins'] = [
            'print preview searchreplace autolink directionality visualblocks',
            'visualchars fullscreen ' . (User::hasPrivilege('fileaccess') ? 'image' : '') . ' link ' . (User::hasPrivilege('fileaccess') ? 'media' : '') . ' template codesample table charmap hr',
            'pagebreak nonbreaking anchor toc insertdatetime advlist lists textcolor wordcount',
            'imagetools contextmenu colorpicker textpattern code help'
        ];
        $this->properties['toolbar'] = 'formatselect | bold italic strikethrough forecolor backcolor | link | alignleft aligncenter alignright alignjustify  | numlist bullist outdent indent  | removeformat';
        $this->properties['menubar'] = true;
        $this->properties['image_advtab'] = true;

        return $this;
    }

    public function getProperties(): array
    {
        return $this->properties;
    }

}

