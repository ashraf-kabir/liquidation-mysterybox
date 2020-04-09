<?php
include_once 'Builder.php';
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
class Marketing_builder extends Builder
{
    protected $_config;
    protected $_dynamic_config;
    protected $_template;
    protected $_file_path = '';
    protected $_render_list = [];
    protected $_routes = [];
    protected $_marketing;

    public function __construct($config, $locale)
    {
        $this->_config = $config;
        $this->_template = '';
        $this->_locale = $locale;
    }

    public function set_marketing($marketing)
    {
        $this->_marketing = $marketing;
    }

    public function build()
    {
        return $this->_template;
    }

    public function get_route()
    {
        foreach ($this->_marketing['routes'] as $key => $value)
        {
            $this->_routes[$key] = $value;

        }
        return $this->_routes;
    }

    public function inject_template ()
    {
        if (strlen($this->_marketing['header']) > 0)
        {
            $header_template = file_get_contents($this->_marketing['header']);
            $header_template = $this->inject_substitute($header_template, 'js', $this->_generate_js($this->_marketing['js']));
            $header_template = $this->inject_substitute($header_template, 'css', $this->_generate_css($this->_marketing['css']));
            foreach ($this->_config as $key => $value)
            {
                if (!is_array($value))
                {
                    $header_template = $this->inject_substitute($header_template, $key, $value);
                }
                else
                {
                    if ($key == 'dynamic_config')
                    {
                        $dynamic_config_text = '';
                        foreach ($value as $dynamic_config_key => $dynamic_config_value)
                        {
                            if (!is_array($value))
                            {
                                $header_template = $this->inject_substitute($header_template, $dynamic_config_key, $dynamic_config_value);
                            }
                        }
                    }
                    else if ($this->is_object_or_array($value))
                    {
                        $header_template = $this->inject_array($header_template, $key, $value);
                    }
                }
            }
            file_put_contents('src/application/views/Layout/GuestHeader.php', $header_template);
            $this->_render_list['src/application/views/Layout/GuestHeader.php'] = '';
        }

        if (strlen($this->_marketing['footer']) > 0)
        {
            $footer_template = file_get_contents($this->_marketing['footer']);
            $footer_template = $this->inject_substitute($footer_template, 'js', $this->_generate_js($this->_marketing['js']));
            $footer_template = $this->inject_substitute($footer_template, 'css', $this->_generate_css($this->_marketing['css']));
            foreach ($this->_config as $key => $value)
            {
                if (!is_array($value))
                {
                    $footer_template = $this->inject_substitute($footer_template, $key, $value);
                }
                else
                {
                    if ($key == 'dynamic_config')
                    {
                        $dynamic_config_text = '';
                        foreach ($value as $dynamic_config_key => $dynamic_config_value)
                        {
                            if (!is_array($value))
                            {
                                $footer_template = $this->inject_substitute($footer_template, $dynamic_config_key, $dynamic_config_value);
                            }
                        }
                    }
                    else if ($this->is_object_or_array($value))
                    {
                        $footer_template = $this->inject_array($footer_template, $key, $value);
                    }
                }
            }
            file_put_contents('src/application/views/Layout/GuestFooter.php', $footer_template);
            $this->_render_list['src/application/views/Layout/GuestFooter.php'] = '';
        }

        foreach ($this->_marketing['pages'] as $key => $value)
        {
            $template = file_get_contents($key);

            foreach ($this->_config as $config_key => $config_value)
            {
                if (!is_array($config_value))
                {
                    $template = $this->inject_substitute($template, $config_key, $config_value);
                }
            }

            file_put_contents($value, $template);
            $this->_render_list[$value] = '';
        }

        foreach ($this->_marketing['views'] as $key => $value)
        {
            $template = file_get_contents($key);

            foreach ($this->_config as $config_key => $config_value)
            {
                if (!is_array($config_value))
                {
                    $template = $this->inject_substitute($template, $config_key, $config_value);
                }
            }

            file_put_contents($value, $template);
            $this->_render_list[$value] = '';
        }

        if ($this->_config['mode'] == 'production')
        {
            foreach ($this->_marketing['js'] as $file)
            {
                if (file_exists($this->_generate_asset_file_name($file)))
                {
                    unlink($this->_generate_asset_file_name($file));
                }
            }
            foreach ($this->_marketing['css'] as $file)
            {
                if (file_exists($this->_generate_asset_file_name($file)))
                {
                    unlink($this->_generate_asset_file_name($file));
                }
            }
        }
    }

    public function destroy ()
    {
        $this->inject_template();
        foreach ($this->_render_list as $key => $value)
        {
            $key = str_replace('/assets', 'assets', $key);
            if (file_exists($key))
            {
                unlink($key);
            }
        }
    }

    private function _generate_js ($js_list)
    {
        $js_html = '';
        $compiled_js = '';
        $compiled_js_file_name = "/assets/js/bundle.js";
        if ($this->_config['mode'] == 'production')
        {
            foreach ($js_list as $file)
            {
                $file = str_replace('/assets/js/', 'templates/custom/', $file);
                $compiled_js .= file_get_contents($file);
            }
            file_put_contents($this->_generate_asset_file_name($compiled_js_file_name), $compiled_js);
            return "\t<script src=\"{$compiled_js_file_name}\"></script>\n";
        }

        foreach ($js_list as $file)
        {
            $js_html .= "\t<script src=\"{$file}\"></script>\n";
            $template = file_get_contents(str_replace('/assets/js/', 'templates/custom/', $file));
            file_put_contents(str_replace('/assets', 'assets', $file), $template);
            $this->_render_list[$file] = '';
        }

        return $js_html;
    }

    private function _generate_asset_file_name ($file)
    {
        if (substr($file,0,1) == '/')
        {
            return dirname(__FILE__) . '/../..' . $file;
        }

        return dirname(__FILE__) . '/../../' . $file;
    }

    private function _generate_css ($css_list)
    {
        $css_html = '';
        $compiled_css = '';
        $compiled_css_file_name = "/assets/css/bundle.css";
        if ($this->_config['mode'] == 'production')
        {
            foreach ($css_list as $file)
            {
                $file = str_replace('/assets/css/', 'templates/custom/', $file);
                $compiled_css .= file_get_contents($file);
            }
            file_put_contents($this->_generate_asset_file_name($compiled_css_file_name), $compiled_css);
            return "\t<link rel=\"stylesheet\" href=\"{$compiled_css_file_name}\"/>\n";
        }

        foreach ($css_list as $file)
        {
            $css_html .= "\t<link rel=\"stylesheet\" href=\"{$file}\"/>\n";
            $template = file_get_contents(str_replace('/assets/css/', 'templates/custom/', $file));
            file_put_contents(str_replace('/assets', 'assets', $file), $template);
            $this->_render_list[$file] = '';
        }

        return $css_html;
    }
}