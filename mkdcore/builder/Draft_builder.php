<?php
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2020*/
include_once 'Builder.php';
class Draft_builder extends Builder
{
    protected $_config;
    protected $_render_list = [];
    protected $_translate = [];

    public function __construct($config)
    {
        $this->_config = json_decode($config, TRUE);
        $this->_translate = $this->_config['translations'];
    }

    public function init()
    {
        $blacklist = [
            '../release/vendor',
            '../release/system',
            '../release/uploads'
        ];
        $dir = '../release';
        foreach (glob("$dir/*") as $file)
        {
            if (!in_array($file, $blacklist) && !is_file($file))
            {
                $this->check_folder($file);
            }
        }
    }

    public function build()
    {
        $this->init();
        echo "Copy these lines into copy object please\n";
        $flipped_translation = array_flip($this->_translate);
        
        foreach ($this->_render_list as $path => $template)
        {
            $content = file_get_contents($template);
            foreach($flipped_translation as $key => $value){
                if($value !== 'xyz' && strpos($path, '_controllers_') === FALSE){
                    $content = str_replace('>'.$key, '>'.$value, $content);
                }
            }
            $content = str_replace('DRAFTMODE', '', $content);
            file_put_contents($path, $content);
            echo "\"$path\": \"$template\",\n";
        }
    }

    public function check_folder($path)
    {
        foreach (glob("$path/*") as $file) {
            if (!is_file($file))
            {
                $this->check_folder($file);
            }
            else
            {
                $content = file_get_contents($file);
                if (strpos($content, 'DRAFTMODE') !== FALSE)
                {
                    $this->_render_list['../mkdcore/custom/generated/' . str_replace(array('../', '/'), array('', '_'), $file)] = $file;
                }
            }
        }
    }

    public function destroy()
    {
        $this->init();

        foreach ($this->_render_list as $path => $template)
        {
            $this->destroy_file($path);
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