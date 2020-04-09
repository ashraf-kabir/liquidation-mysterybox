<?php
include_once 'Builder.php';
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
class Report_builder extends Builder
{
    protected $_file_path = '';
    protected $_render_list = [];
    protected $_reporting = [];
    protected $_routes = [];
    protected $_setting_model = [];

    public function __construct($config, $locale)
    {
        $this->_config = $config;

        $this->_template = '';
        $this->_locale = $locale;
    }

    public function set_reporting($reporting)
    {
        $this->_reporting = $reporting;
    }

    public function get_route ()
    {
        foreach ($this->_reporting as $key => $value)
        {
            $portal = $value['portal'];
            $ucportal = ucfirst($value['portal']);
            $route = $value['route'];
            $name = strtolower($value['name']);
            $this->_routes["{$portal}{$route}"] = "{$ucportal}/{$ucportal}_{$name}_report_controller/index";
        }

        return $this->_routes;
    }

    public function build()
    {
        /**
         * Steps:
         * 1.Copy over reporting service
         * 2.Routes is copied above
         * 3.Form is made
         * 4.display option is set
         * 5.header and fields are specified
         */
        foreach ($this->_reporting as $key => $value)
        {
            $portal = $value['portal'];
            $ucportal = ucfirst($value['portal']);
            $ucname = ucfirst($value['name']);
            $name = strtolower($value['name']);
            $filename = $value['filename'];
            $route = $value['route'];
            $template = file_get_contents('../mkdcore/source/report/Report_service.php');
            $template = $this->inject_substitute($template, 'ucname', $ucname);
            $template = $this->inject_substitute($template, 'query', $value['query']);
            $template = $this->inject_substitute($template, 'result', $value['result']);
            $template = $this->inject_substitute($template, 'post', $value['post']);
            $this->_render_list["../release/application/services/{$ucname}_report_service.php"] = $template;

            $controller_template = file_get_contents('../mkdcore/source/report/Report_controller.php');
            $controller_template = $this->inject_substitute($controller_template, 'uc_portal', $ucportal);
            $controller_template = $this->inject_substitute($controller_template, 'portal', $portal);
            $controller_template = $this->inject_substitute($controller_template, 'name', $name);
            $controller_template = $this->inject_substitute($controller_template, 'ucname', $ucname);
            $controller_template = $this->inject_substitute($controller_template, 'model', $value['model']);
            $controller_template = $this->inject_substitute($controller_template, 'page_name', $value['page_name']);
            $controller_template = $this->inject_substitute($controller_template, 'pre_controller', $value['pre_controller']);
            $controller_template = $this->inject_substitute($controller_template, 'filter_fields', $this->output_filter_fields($value['filter_field'], $value['display']));
            $controller_template = $this->inject_substitute($controller_template, 'post_fields', $this->output_post_fields($value['filter_field']));
            $controller_template = $this->inject_substitute($controller_template, 'post', $value['post']);
            $controller_template = $this->inject_substitute($controller_template, 'process', $value['parameter']);

            if ($value['display'] == 'csv') {
                $view_template = file_get_contents('../mkdcore/source/report/Report_form.php');
                $view_template = $this->inject_substitute($view_template, 'page_name', $value['page_name']);
                $view_template = $this->inject_substitute($view_template, 'filter', $this->output_filter($value['filter_field']));
                $this->_render_list["../release/application/views/{$ucportal}/{$ucname}Report.php"] = $view_template;
                $controller_template = $this->inject_substitute($controller_template, 'display', $this->render_display($value, $filename));

            }
            if ($value['display'] == 'table') {
                $table_view_template = file_get_contents('../mkdcore/source/report/Filter_report_form.php');
                $table_view_template = $this->inject_substitute($table_view_template, 'page_name', $value['page_name']);
                $table_view_template = $this->inject_substitute($table_view_template, 'filter', $this->output_filter($value['filter_field']));
                $table_view_template = $this->inject_substitute($table_view_template, 'table_header', $this->output_table_header($value['header']));
                $table_view_template = $this->inject_substitute($table_view_template, 'row_data', $this->output_table_row($value['field']));
                $filter_field_url = $this->output_filter_url($value['filter_field']);
                $table_view_template = $this->inject_substitute($table_view_template, 'add', "<a class=\"btn btn-primary btn-sm\" href=\"/{$portal}{$route}?export=csv&$filter_field_url\"><i class=\"fas fa-cloud-download-alt\"></i></a>");
                $this->_render_list["../release/application/views/{$ucportal}/{$ucname}Report.php"] = $table_view_template;

                $controller_template = $this->inject_substitute($controller_template, 'display', $this->render_display($value, $filename));
            }

            $this->_render_list["../release/application/controllers/{$ucportal}/{$ucportal}_{$name}_report_controller.php"] = $controller_template;
        }
    }

    public function render_display($value, $filename)
    {
        $display_row = $this->output_display_rows($value['header']);
        $csv_str = "\t\t\$service->generate_csv([{$display_row}], \$result, '{$filename}_' . \$start_date . '_' . \$end_date . '.csv');\n";
        if ($value['display'] == 'csv')
        {
            return $csv_str;
        }
        else
        {
            $ucportal = ucfirst($value['portal']);
            $ucname = ucfirst($value['name']);
            return "\t\tif (\$export)\n\t\t{\n\t{$csv_str}\n\t\t}\n\t\telse\n\t\t{\n\t\t\t\$this->_data['list'] = \$result;\n\t\t\treturn \$this->render('{$ucportal}/{$ucname}Report', \$this->_data);\n\t\t}";
        }
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

    protected function output_filter_fields($fields, $display)
    {
        $result = '';
        $inner_result = '';
        foreach ($fields as $field)
        {
            $result .= "\t\t\$this->set_fields('{$field[0]}');\n";
        }

        if ($display == 'table')
        {
            $result .= "\t\t\$this->_data['list'] = [];\n";
        }

        return $result . $inner_result;
    }

    protected function output_post_fields($fields)
    {
        $result = '';
        foreach ($fields as $field)
        {
            $result .= "\t\t\${$field[0]} = \$this->input->get('{$field[0]}', TRUE);\n";
        }
        return $result;
    }

    protected function output_table_header ($table_header)
    {
        $result = "";
        foreach ($table_header as $field)
        {
            $result .= "\t\t\t\t\t\t\t\t<td>{$field}</td>\n";
        }
        return $result;
    }

    protected function output_table_row ($table_row)
    {

        $result = "<?php foreach (\$list as \$value) {\n";
        $result .= "\t\t\t\t\t\t\t\techo '<tr>';\n";
        foreach ($table_row as $field)
        {
            $result .= "\t\t\t\t\t\t\t\techo \"<td>\" . \$value['{$field}'] . \"</td>\";\n";
        }
        $result .= "\t\t\t\t\t\t\t\techo '<tr>';\n";
        $result .= "\t\t\t\t\t\t\t}?>";
        return $result;
    }

    protected function output_display_rows ($table_row)
    {
        $result = [];
        foreach ($table_row as $key => $value) {
            $result[] = "'$value'";
        }
        return implode(', ', $result);
    }

    protected function output_filter_url ($fields)
    {
        $list = [];
        foreach ($fields as $field)
        {
            $list[] = "{$field[0]}=<?php echo \$this->_data['{$field[0]}'];?>";
        }
        return implode('&', $list);
    }

    protected function output_filter ($fields)
    {
        $result = '';
        foreach ($fields as $field)
        {
            switch ($field[1]) {
                case 'string':
                    $result .= "\t\t\t\t\t\t<div class=\"col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12\">\n";
                    $result .= "\t\t\t\t\t\t\t<div class=\"form-group\">\n";
                    $result .= "\t\t\t\t\t\t\t\t<label for=\"{$field[3]}\">{$field[3]} </label>\n";
                    $result .= "\t\t\t\t\t\t\t\t<input type=\"text\" class=\"form-control\" id=\"{$field[0]}\" name=\"{$field[0]}\" value=\"<?php echo (\$this->_data['{$field[0]}'] != NULL) ? \$this->_data['{$field[0]}'] : '';?>\"/>\n";
                    $result .= "\t\t\t\t\t\t\t</div>\n";
                    $result .= "\t\t\t\t\t\t</div>\n";
                    break;
                case 'boolean':
                    $result .= "\t\t\t\t\t\t<div class=\"col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12\">\n";
                    $result .= "\t\t\t\t\t\t\t<div class=\"form-group\">\n";
                    $result .= "\t\t\t\t\t\t\t\t<label class=\"custom-control custom-checkbox\">\n";
                    $result .= "\t\t\t\t\t\t\t\t\t<input type=\"checkbox\" checked=\"<?php echo (\$this->_data['{$field[0]}'] != NULL) ? \$this->_data['{$field[0]}'] : '';?>\" class=\"custom-control-input\" id=\"form_{$field[0]}\" name=\"{$field[0]}\" value=\"1\"/><span class=\"custom-control-label\">{$field[3]}</span>\n";
                    $result .= "\t\t\t\t\t\t\t\t</label>\n";
                    $result .= "\t\t\t\t\t\t\t</div>\n";
                    $result .= "\t\t\t\t\t\t</div>\n";
                    break;
                case 'date':
                    $result .= "\t\t\t\t\t\t<div class=\"col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12\">\n";
                    $result .= "\t\t\t\t\t\t\t<div class=\"form-group\">\n";
                    $result .= "\t\t\t\t\t\t\t\t<label for=\"{$field[3]}\">{$field[3]} </label>\n";
                    $result .= "\t\t\t\t\t\t\t\t<input type=\"date\" class=\"form-control\" id=\"{$field[0]}\" name=\"{$field[0]}\" value=\"<?php echo (\$this->_data['{$field[0]}'] != NULL) ? \$this->_data['{$field[0]}'] : '';?>\"/>\n";
                    $result .= "\t\t\t\t\t\t\t</div>\n";
                    $result .= "\t\t\t\t\t\t</div>\n";
                    break;
                case 'datetime':
                    $result .= "\t\t\t\t\t\t<div class=\"col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12\">\n";
                    $result .= "\t\t\t\t\t\t\t<div class=\"form-group\">\n";
                    $result .= "\t\t\t\t\t\t\t\t<label for=\"{$field[3]}\">{$field[3]} </label>\n";
                    $result .= "\t\t\t\t\t\t\t\t<input type=\"datetime\" class=\"form-control\" id=\"{$field[0]}\" name=\"{$field[0]}\" value=\"<?php echo (\$this->_data['{$field[0]}'] != NULL) ? \$this->_data['{$field[0]}'] : '';?>\"/>\n";
                    $result .= "\t\t\t\t\t\t\t</div>\n";
                    $result .= "\t\t\t\t\t\t</div>\n";
                    break;
                case 'text':
                    $result .= "\t\t\t\t\t\t<div class=\"col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12\">\n";
                    $result .= "\t\t\t\t\t\t\t<div class=\"form-group\">\n";
                    $result .= "\t\t\t\t\t\t\t\t<label for=\"{$field[3]}\">{$field[3]} </label>\n";
                    $result .= "\t\t\t\t\t\t\t\t<textarea id='{$field[0]}' name='{$field[0]}' class='form-control' rows='5'><?php echo (\$this->_data['{$field[0]}'] != NULL) ? \$this->_data['{$field[0]}'] : '';?></textarea>\n";
                    $result .= "\t\t\t\t\t\t\t</div>\n";
                    $result .= "\t\t\t\t\t\t</div>\n";
                    break;
                case 'email':
                    $result .= "\t\t\t\t\t\t<div class=\"col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12\">\n";
                    $result .= "\t\t\t\t\t\t\t<div class=\"form-group\">\n";
                    $result .= "\t\t\t\t\t\t\t\t<label for=\"{$field[3]}\">{$field[3]} </label>\n";
                    $result .= "\t\t\t\t\t\t\t\t<input type=\"email\" class=\"form-control\" id=\"{$field[0]}\" name=\"{$field[0]}\" value=\"<?php echo (\$this->_data['{$field[0]}'] != NULL) ? \$this->_data['{$field[0]}'] : '';?>\"/>\n";
                    $result .= "\t\t\t\t\t\t\t</div>\n";
                    $result .= "\t\t\t\t\t\t</div>\n";
                    break;
                case 'integer':
                    $result .= "\t\t\t\t\t\t<div class=\"col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12\">\n";
                    $result .= "\t\t\t\t\t\t\t<div class=\"form-group\">\n";
                    $result .= "\t\t\t\t\t\t\t\t<label for=\"{$field[3]}\">{$field[3]} </label>\n";
                    $result .= "\t\t\t\t\t\t\t\t<input type=\"text\" class=\"form-control\" id=\"{$field[0]}\" name=\"{$field[0]}\" value=\"<?php echo (\$this->_data['{$field[0]}'] != NULL) ? \$this->_data['{$field[0]}'] : '';?>\" onkeypress=\"return event.charCode >= 48 && event.charCode <= 57\"/>\n";
                    $result .= "\t\t\t\t\t\t\t</div>\n";
                    $result .= "\t\t\t\t\t\t</div>\n";
                    break;
                case 'float':
                    $result .= "\t\t\t\t\t\t<div class=\"col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12\">\n";
                    $result .= "\t\t\t\t\t\t\t<div class=\"form-group\">\n";
                    $result .= "\t\t\t\t\t\t\t\t<label for=\"{$field[3]}\">{$field[3]} </label>\n";
                    $result .= "\t\t\t\t\t\t\t\t<input type=\"text\" class=\"form-control\" id=\"{$field[0]}\" name=\"{$field[0]}\" value=\"<?php echo (\$this->_data['{$field[0]}'] != NULL) ? \$this->_data['{$field[0]}'] : '';?>\" onkeypress=\"return ((event.charCode >= 48 && event.charCode <= 57) || (event.charCode == 110))\"/>\n";
                    $result .= "\t\t\t\t\t\t\t</div>\n";
                    $result .= "</div>\n";
                    break;

                default:
                    # code...
                    break;
            }
        }
        return $result;
    }
}