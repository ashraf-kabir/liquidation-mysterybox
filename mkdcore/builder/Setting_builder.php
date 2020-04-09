<?php
include_once 'Builder.php';
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
class Setting_builder extends Builder
{
    protected $_file_path = '';
    protected $_render_list = [];
    protected $_models = [];
    protected $_routes = [];
    protected $_setting_model = [];

    public function __construct($config, $locale)
    {
        $this->_config = $config;

        $this->_template = '';
        $this->_locale = $locale;
    }

    public function set_model($model)
    {
        $this->_models = $model;
        foreach ($this->_models as $model)
        {
            if ($model['name'] == 'setting')
            {
                $this->_setting_model = $model;
                return $this->_setting_model;
            }
        }
    }

    public function get_route ()
    {
        $portal = 'admin';
        $ucportal = ucfirst('admin');
        $this->_routes["{$portal}/settings"] = "{$ucportal}/{$ucportal}_setting_controller/index";
        $this->_routes["v1/api/{$portal}/settings/edit/(:num)"] = "{$ucportal}/{$ucportal}_setting_controller/edit/$1";
        return $this->_routes;
    }

    public function build()
    {
        // Plan:
        // 4. Custom Edit Page
        // 5. JS Code to call Edit
        // 6. Build Setting Page
        // 7. Write in cache config after
        $portal = 'admin';
        $ucportal = ucfirst('admin');
        $template = file_get_contents('../mkdcore/source/setting/Setting_controller.php');
        $template = $this->inject_substitute($template, 'portal', $portal);
        $template = $this->inject_substitute($template, 'ucportal', $ucportal);
        $this->_render_list["../release/application/controllers/{$ucportal}/{$ucportal}_setting_controller.php"] = $template;
        $view_template = file_get_contents('../mkdcore/source/setting/Setting_view.php');
        $view_template = $this->inject_substitute($view_template, 'portal', $portal);
        $view_template = $this->inject_substitute($view_template, 'ucportal', $ucportal);
        $view_template = $this->inject_substitute($view_template, 'rows', $this->_generate_rows($this->_setting_model));
        $this->_render_list["../release/application/views/{$ucportal}/Setting.php"] = $view_template;
        $js_template = file_get_contents('../mkdcore/source/setting/setting.js');
        $this->_render_list["../release/assets/js/setting.js"] = $js_template;
        $cache_template = file_get_contents('../mkdcore/source/setting/Setting_cache.php');
        $cache_template = $this->inject_substitute($cache_template, 'row', $this->_generate_cache_rows($this->_setting_model));
        $this->_render_list["../release/application/config/setting.php"] = $cache_template;
    }

    public function inject_template ()
    {
        foreach ($this->_render_list as $key => $value)
        {
            file_put_contents($key, $value);
        }
    }

    public function destroy ()
    {
        $this->build();
        foreach ($this->_render_list as $key => $value)
        {
            if (file_exists($key))
            {
                unlink($key);
            }
        }
    }

    protected function _generate_cache_rows($setting)
    {
        $html = '';
        $mapping = $setting['mapping'];
        $seed = $setting['seed'];
        foreach ($seed as $count => $row)
        {
            $html .= "\t'{$row['key']}' => '{$row['value']}',\n";
        }
        return $html;
    }

    protected function _generate_rows ($setting)
    {
        $html = '';
        $mapping = $setting['mapping'];
        $seed = $setting['seed'];
        foreach ($seed as $count => $row)
        {
            $id = $count + 1;
            $word = $this->snakeCaseToWords($row['key']);
            switch ($row['type'])
            {
                case 1:
                    $row_mapping = $mapping[$row['key']];
                    $html .= "\t\t\t\t\t\t<div class=\"col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12\">\n";
                    $html .= "\t\t\t\t\t\t\t<div class=\"form-group\">\n";
                    $html .= "\t\t\t\t\t\t\t\t<label for=\"{$word}\">{$word} </label>\n";
                    $html .= "\t\t\t\t\t\t\t\t<select name=\"{$row['key']}\" class=\"form-control mkd-setting-select-change\" data-id=\"{$id}\">\n";
                    foreach ($row_mapping as $mapping_key => $mapping_value)
                    {
                        $html .= "\t\t\t\t\t\t\t\t\t\t<?php echo \"<option value='{$mapping_key}' \" . ((\$list['{$row['key']}']->key == '{$row['key']}' && \$list['{$row['key']}']->value == \"{$mapping_key}\") ? 'selected' : '') . \"> {$mapping_value} </option>\";?>\n";
                    }
                    $html .= "\t\t\t\t\t\t\t\t</select>\n";
                    $html .= "\t\t\t\t\t\t\t</div>\n";
                    $html .= "\t\t\t\t\t\t</div>\n";
                    break;
                case 0:
                    $html .= "\t\t\t\t\t\t<div class=\"col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12\">\n";
                    $html .= "\t\t\t\t\t\t\t<div class=\"form-group\">\n";
                    $html .= "\t\t\t\t\t\t\t\t<label for=\"{$word}\">{$word} </label>\n";
                    $html .= "\t\t\t\t\t\t\t\t<textarea id='{$row['key']}' name='{$row['key']}' data-id=\"{$id}\" class='form-control mkd-setting-change' rows='3'><?php echo \$list['{$row['key']}']->value;?></textarea>\n";
                    $html .= "\t\t\t\t\t\t\t</div>\n";
                    $html .= "\t\t\t\t\t\t</div>\n";
                    break;
                case 4:
                    $html .= "\t\t\t\t\t\t<div class=\"col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12\">\n";
                    $html .= "\t\t\t\t\t\t\t<div class=\"form-group\">\n";
                    $html .= "\t\t\t\t\t\t\t\t<label for=\"{$word}\">{$word} </label>\n";
                    $html .= "\t\t\t\t\t\t\t\t<textarea id='{$row['key']}' readonly class='form-control mkd-setting-change' rows='3'><?php echo \$list['{$row['key']}']->value;?></textarea>\n";
                    $html .= "\t\t\t\t\t\t\t</div>\n";
                    $html .= "\t\t\t\t\t\t</div>\n";
                    break;
                case 2:
                    $html .= "\t\t\t\t\t\t<div class=\"col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12\">\n";
                    $html .= "\t\t\t\t\t\t\t<div class=\"form-group\">\n";
                    $html .= "\t\t\t\t\t\t\t\t<label for=\"{$word}\">{$word} </label>\n";
                    $html .= "\t\t\t\t\t\t\t\t<input type=\"text\" class=\"form-control mkd-setting-change\" id=\"{$row['key']}\" data-id=\"{$id}\" name=\"{$row['key']}\" value=\"<?php echo \$list['{$row['key']}']->value;?>\" onkeypress=\"return event.charCode >= 48 && event.charCode <= 57\"/>\n";
                    $html .= "\t\t\t\t\t\t\t</div>\n";
                    $html .= "\t\t\t\t\t\t</div>\n";
                    break;
                default:
                    # code...
                    break;
            }
        }
        return $html;
    }

    private function snakeCaseToWords($string)
    {
        $str = ucwords(str_replace('_', ' ', $string));
        $str[0] = ucfirst($str[0]);
        return $str;
    }
}