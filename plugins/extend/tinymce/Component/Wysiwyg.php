<?php

namespace Wysiwyg;

use Sunlight\Router;

class Wysiwyg
{
    /** @var array */
    public $properties = array();

    function __construct($selector = '')
    {
        $this->properties = array(
            'selector' => 'textarea.editor',
            'language' => _language,
            'menubar' => false,
            'theme' => 'modern',
            'plugins' => array(
                'advlist autolink lists link ' . (_priv_fileaccess ? 'image' : '') . ' charmap print preview anchor textcolor',
                'searchreplace visualblocks code fullscreen',
                'insertdatetime media table contextmenu paste code help wordcount'
            ),
            'toolbar' => 'undo redo | bold italic forecolor |  formatselect  | removeformat help',
            'entity_encoding' => 'raw',
            'image_advtab' => false,
            //'content_css' => array('//www.tinymce.com/css/codepen.min.css'),
            'content_css' => array(Router::generate('plugins/extend/tinymce/Resources/tinymce/custom_css/custom_codepen.min.css')),
        );

        if ($selector != '') {
            $this->properties['selector'] = $selector;
        }
    }

    function setLimitedMode()
    {
        // limited is default
        return $this;
    }

    function setBasicMode()
    {
        $this->properties['plugins'] = array(
            'advlist autolink lists link ' . (_priv_fileaccess ? 'image' : '') . ' charmap print preview anchor textcolor',
            'searchreplace visualblocks code fullscreen',
            'insertdatetime media table contextmenu paste code help wordcount'
        );
        $this->properties['toolbar'] = 'insert | undo redo |  formatselect | bold italic forecolor backcolor  | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | code help';
        return $this;
    }

    function setAdvancedMode()
    {
        $this->properties['plugins'] = array(
            'print preview searchreplace autolink directionality visualblocks',
            'visualchars fullscreen ' . (_priv_fileaccess ? 'image' : '') . ' link ' . (_priv_fileaccess ? 'media' : '') . ' template codesample table charmap hr',
            'pagebreak nonbreaking anchor toc insertdatetime advlist lists textcolor wordcount',
            'imagetools contextmenu colorpicker textpattern code help'
        );
        $this->properties['toolbar'] = 'formatselect | bold italic strikethrough forecolor backcolor | link | alignleft aligncenter alignright alignjustify  | numlist bullist outdent indent  | removeformat';
        $this->properties['menubar'] = true;
        $this->properties['image_advtab'] = true;

        return $this;
    }

    function getProperties()
    {
        return $this->properties;
    }

}
