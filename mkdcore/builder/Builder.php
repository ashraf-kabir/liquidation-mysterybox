<?php
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
abstract class Builder
{
    protected $_config;
    protected $_template;
    protected $_file_path;
    protected $_locale;
    protected $_translate = [];

    public function build ()
    {

    }

    public function destroy ()
    {
        if (file_exists($this->_file_path))
        {
            unlink($this->_file_path);
        }
    }

    public function set_locale ($locale)
    {
        $this->_locale = $locale;
    }

    public function set_translate_text ($translate_text)
    {
        $this->_translate = $translate_text;
    }

    public function get_locale ()
    {
        return $this->_locale;
    }

    public function get_config ()
    {
        return $this->_config;
    }

    public function get_template ()
    {
        return $this->_template;
    }

    public function inject_template ()
    {
        if (strlen($this->_file_path) > 0)
        {
            file_put_contents($this->_file_path, $this->_template);
        }
    }

    public function inject_substitute($text, $key, $value)
    {
        $text = str_replace('{{{'.$key.'}}}', $value, $text);
        foreach ($this->_translate as $t_key => $t_value) {

            $text = str_replace($t_key, $t_value, $text);
        }
        return $text;
    }

    public function inject_array($text, $key, $value)
    {
        foreach ($value as $k => $new_value)
        {
            if (is_string($new_value))
            {
                $value[$k] = "'$new_value'";
            }
        }

        $process_value = implode(',', $value);
        $value = str_replace('[', '', $process_value);
        $value = str_replace(']', '', $value);
        $text = str_replace('{{{'.$key.'}}}', 'array('.$value.')', $text);

        return $text;
    }

    public function printr($data)
    {
        echo '<pre>'.print_r($data, true).'</pre>';
    }

    public function is_object_or_array($data)
    {
        $is_array = false;
        foreach ($data as $key => $value)
        {
            if (is_int($key))
            {
                $is_array = true;
            }
        }

        return $is_array;
    }
}