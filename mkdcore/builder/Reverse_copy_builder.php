<?php
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2020*/
include_once 'Builder.php';
class Reverse_copy_builder extends Builder
{
    protected $_config;
    protected $_render_list = [];
    protected $_translate = [];

    public function __construct($config)
    {
        $this->_config = json_decode($config, TRUE);
        $this->_translate = $this->_config['translations'];
    }

    public function build()
    {
        $reverse_copy = [];

        if (isset($this->_config['reverse_copy']))
        {
            $reverse_copy = $this->_config['reverse_copy'];
        }

        foreach ($reverse_copy as $key => $value) {
            file_put_contents($value, file_get_contents($key));
        }
    }

    public function destroy()
    {
        $reverse_copy = [];

        if (isset($this->_config['reverse_copy']))
        {
            $reverse_copy = $this->_config['reverse_copy'];
        }

        foreach ($reverse_copy as $key => $value)
        {
            $this->destroy_file($value);
        }
    }

    public function destroy_file ($path)
    {
        if (file_exists($path))
        {
            unlink($path);
        }
    }
}