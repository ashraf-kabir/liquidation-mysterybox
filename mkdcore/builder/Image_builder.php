<?php
include_once 'Builder.php';
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
class Image_builder extends Builder
{
    protected $_config;
    protected $_dynamic_config;
    protected $_template;
    protected $_file_path = '';
    protected $_render_list = [
        '../release/assets/js/core.js' => '../mkdcore/source/core/core.js',
        '../release/assets/js/media.js' => '../mkdcore/source/image/media.js',
        '../release/assets/js/mkd-image-gallery.js' => '../mkdcore/source/image/mkd-image-gallery.js',
        '../release/application/controllers/Guest/Image_controller.php' => '../mkdcore/source/image/Image_controller.php',
        '../release/application/view_models/Image_asset_paginate_view_model.php' => '../mkdcore/source/image/Image_asset_paginate_view_model.php',
        '../release/application/libraries/Csv_import_service.php' => '../mkdcore/source/image/Csv_import_service.php'
    ];

    public function __construct($config, $locale)
    {
        $this->_config = $config;
        $this->_template = '';
        $this->_locale = $locale;
    }

    public function get_route ()
    {
        return [
            'v1/api/image/upload' => 'Guest/Image_controller',
            'v1/api/file/upload' => 'Guest/Image_controller/file_upload',
            'v1/api/file/import/(:any)' => 'Guest/Image_controller/file_import/$1',
            'v1/api/assets' => 'Guest/Image_controller/paginate/0',
            'v1/api/assets/(:num)' => 'Guest/Image_controller/paginate/$1'
        ];
    }

    public function build()
    {
        return $this->_template;
    }

    public function inject_template ()
    {
        foreach ($this->_render_list as $key => $value)
        {
            $template = file_get_contents($value);
            $template = $this->inject_substitute($template, '!@#$%^&', '');
            file_put_contents($key, $template);
        }
    }

    public function destroy ()
    {
        foreach ($this->_render_list as $key => $value)
        {
            if (file_exists($key))
            {
                unlink($key);
            }
        }
    }
}