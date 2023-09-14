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
            'language_url' => Router::path('plugins/extend/tinymce/public/langs/' . Core::$lang . '.js'),
            'relative_urls' => false,
            'document_base_url' => Core::getBaseUrl()->build(),
            'menubar' => false,
            'theme' => 'silver',
            'plugins' => [
                'advlist', 'autolink', 'lists', 'link',  'charmap', 'preview', 'anchor',
                'searchreplace', 'visualblocks', 'code', 'fullscreen',
                'insertdatetime', 'media', 'table', 'code', 'help', 'wordcount'
            ],
            'toolbar' => 'undo redo | bold italic forecolor |  formatselect  | removeformat help',
            'entity_encoding' => 'raw',
            'image_advtab' => false,
            //'content_css' => array('//www.tinymce.com/css/codepen.min.css'),
            //'content_css' => [Router::path('plugins/extend/tinymce/public/tinymce/custom_css/custom_codepen.min.css')],
        ];

        if(User::hasPrivilege('fileaccess')) {
            $this->properties['plugins'][] = 'image';
        }

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
            'advlist', 'autolink', 'lists', 'link', 'charmap', 'preview', 'anchor',
            'searchreplace', 'visualblocks', 'code', 'fullscreen',
            'insertdatetime', 'media', 'table', 'code', 'help', 'wordcount'
        ];

        if(User::hasPrivilege('fileaccess')) {
            $this->properties['plugins'][] = 'image';
        }

        $this->properties['toolbar'] = 'insert | undo redo |  formatselect | bold italic forecolor backcolor  | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | code help';
        return $this;
    }

    public function setAdvancedMode(): Wysiwyg
    {
        $this->properties['plugins'] = [
            'preview', 'searchreplace', 'autolink', 'directionality', 'visualblocks',
            'visualchars', 'fullscreen' ,'link', 'codesample', 'table', 'charmap',
            'pagebreak', 'nonbreaking', 'anchor', 'insertdatetime', 'advlist', 'lists',  'wordcount',
            'code', 'help'
        ];

        if(User::hasPrivilege('fileaccess')) {
            $this->properties['plugins'][] = 'image';
        }

        if (User::hasPrivilege('fileaccess')) {
            $this->properties['plugins'][] = 'media';
        }

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