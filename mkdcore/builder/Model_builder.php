<?php
include_once 'Builder.php';
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
class Model_builder extends Builder
{
    protected $_config;
    protected $_template;
    protected $_models;
    protected $_model_template;
    protected $_migration_template;
    protected $_seed_template;
    protected $_file_path = '';

    public function __construct($config, $locale)
    {
        $this->_config = $config;
        $this->_models = [];
        $this->_model_template = [];
        $this->_migration_template = [];
        $this->_seed_template = [];
        $this->_seed_template_window = [];
        $this->_template = '';
        $this->_locale = $locale;
    }

    public function set_model($models)
    {
        $this->_models = $models;
    }

    public function build()
    {
        $this->_template = file_get_contents('../mkdcore/source/model/Model.php');

        foreach ($this->_models as $model_key => $value)
        {
            $upper_case_model = ucfirst($value['name']) . '_model';

            $model_template = $this->_template;
            $model_template = $this->inject_substitute($model_template, 'subclass_prefix', $this->_config['subclass_prefix']);
            $model_template = $this->inject_substitute($model_template, 'name', $value['name']);

            $model_template = $this->inject_substitute($model_template, 'upper_case_model', $upper_case_model);
            if (isset($value['timestamp']) && $value['timestamp'] == TRUE)
            {
                $model_template = $this->inject_substitute($model_template, 'timestamp', 'TRUE');
            }
            else
            {
                $model_template = $this->inject_substitute($model_template, 'timestamp', 'FALSE');
            }

            if (isset($value['method']) && is_string($value['method']) && strlen($value['method']) > 0)
            {
                $model_template = $this->inject_substitute($model_template, 'method', $value['method']);
            }

            if (isset($value['method']) && is_array($value['method']))
            {
                $model_template = $this->inject_substitute($model_template, 'method', implode("\n",$value['method']));
            }
            else
            {
                $model_template = $this->inject_substitute($model_template, 'method', '');
            }

            if (isset($value['pre']) && is_string($value['pre']) && strlen($value['pre']) > 0)
            {
                $model_template = $this->inject_substitute($model_template, 'pre', $value['pre']);
            }
            else
            {
                if (isset($value['pre']) && is_array($value['pre']))
                {
                    $model_template = $this->inject_substitute($model_template, 'pre', implode("\n", $value['pre']));
                }
                else
                {
                    $model_template = $this->inject_substitute($model_template, 'pre', '');
                }
            }

            if (isset($value['post']) && is_string($value['post']) && strlen($value['post']) > 0)
            {
                $model_template = $this->inject_substitute($model_template, 'post', $value['post']);
            }
            else
            {
                if (isset($value['post']) && is_array($value['post']))
                {
                    $model_template = $this->inject_substitute($model_template, 'post', implode("\n", $value['post']));
                }
                else
                {
                    $model_template = $this->inject_substitute($model_template, 'post', '');
                }
            }

            if (isset($value['count']) && is_string($value['count']) && strlen($value['count']) > 0)
            {
                $model_template = $this->inject_substitute($model_template, 'count', $value['count']);
            }
            else
            {
                if (isset($value['count']) && is_array($value['count']))
                {
                    $model_template = $this->inject_substitute($model_template, 'count', implode("\n", $value['count']));
                }
                else
                {
                    $model_template = $this->inject_substitute($model_template, 'count', '');
                }
            }

            if (isset($value['join']) && count($value['join']) > 0)
            {
                $join_template = '';
                foreach ($value['join'] as $join_value)
                {
                    $join_template = $join_template . $this->_join_template($join_value);
                    $join_template = $join_template . $this->_join_paginate_template($join_value);
                }
                $model_template = $this->inject_substitute($model_template, 'join', $join_template);
            }
            else
            {
                $model_template = $this->inject_substitute($model_template, 'join', '');
            }

            if (isset($value['field']) && !empty($value['field']))
            {
                $fields = $value['field'];
                $name_list = '';
                $label_list = '';
                $validation_rule_list = '';
                $validation_edit_rule_list = '';
                $migration_list = [];

                foreach ($fields as $field)
                {
                    if (count(array_values($field)) == 6)
                    {
                        $name = $field[0];
                        $type = $field[1];

                        if ($type == 'password')
                        {
                            $type = 'string';
                        }

                        if ($type == 'time')
                        {
                            $type = 'integer';
                        }

                        if (strpos($type, 'image') !== FALSE)
                        {
                            $type = 'text';
                        }

                        if (strpos($type, 'file') !== FALSE)
                        {
                            $type = 'text';
                        }

                        $condition = (count($field[2]) > 0) ? $field[2][0] : [];
                        $label = $field[3];
                        $validation_rule = $field[4];
                        $validation_edit_rule = $field[5];

                        $name_list = $name_list . "'{$name}',\n\t\t";
                        $label_list = $label_list . "'{$label}',";
                        $validation_rule_list = $validation_rule_list . "['{$name}', '{$label}', '{$validation_rule}'],\n\t\t";
                        $validation_edit_rule_list = $validation_edit_rule_list . "['{$name}', '{$label}', '{$validation_edit_rule}'],\n\t\t";
                        $migration_list[] = [$name, $type, json_encode($condition)];
                    }
                    else
                    {
                        error_log('Field not valid: ' . print_r($field, TRUE));
                    }
                }
                $model_template = $this->inject_substitute($model_template, 'allowed_fields', $name_list);
                $model_template = $this->inject_substitute($model_template, 'labels', $label_list);
                $model_template = $this->inject_substitute($model_template, 'validation_rules', $validation_rule_list);
                $model_template = $this->inject_substitute($model_template, 'validation_edit_rules', $validation_edit_rule_list);
                //inject migration
                if (isset($value['migration']) && $value['migration'] == TRUE)
                {
                    $upper_case_migration_model = ucfirst($value['name']);
                    $model = $value['name'];
                    $migration = '$table';
                    $migration_windows = [];
                    foreach ($migration_list as $key => $migration_row) {
                        $constraint = 0;
                        if ($migration_row[0] == 'id')
                        {
                            $migration_windows[] = "  'id' => array('type' => 'INT','constraint' => 11,'unsigned' => TRUE,'auto_increment' => TRUE)";
                            continue;
                        }

                        if ($migration_row[2] != '[]')
                        {
                            $condition = str_replace(['{','}', ':'], ['[',']', ' => '], $migration_row[2]);
                            $constraint = (int) filter_var($condition, FILTER_SANITIZE_NUMBER_INT);
                            $migration = $migration . "->addColumn('{$migration_row[0]}','{$migration_row[1]}',{$condition})\n\t\t";
                            $migration_windows[] = "'{$migration_row[0]}' => array('type' => 'VARCHAR', 'constraint' => '{$constraint}')";
                        }
                        else
                        {
                            if ($migration_row[1] == 'date' || $migration_row[1] == 'datetime')
                            {
                                if ($migration_row[1] == 'date')
                                {
                                    $migration_windows[] = "'{$migration_row[0]}' => array('type' => 'DATE', 'null' => TRUE)";
                                }

                                if ($migration_row[1] == 'datetime')
                                {
                                    $migration_windows[] = "'{$migration_row[0]}' => array('type' => 'DATETIME', 'null' => TRUE)";
                                }

                                $migration = $migration . "->addColumn('{$migration_row[0]}','{$migration_row[1]}', ['null' => true])\n\t\t";
                            }
                            else
                            {
                                if ($migration_row[1] == 'integer')
                                {
                                    $migration_windows[] = "'{$migration_row[0]}' => array('type' => 'INT', 'constraint' => 11, 'null' => TRUE)";
                                }
                                if ($migration_row[1] == 'text')
                                {
                                    $migration_windows[] = "'{$migration_row[0]}' => array('type' => 'TEXT', 'null' => TRUE)";
                                }
                                if ($migration_row[1] == 'float')
                                {
                                    $migration_windows[] = "'{$migration_row[0]}' => array('type' => 'FLOAT', 'null' => TRUE)";
                                }
                                $migration = $migration . "->addColumn('{$migration_row[0]}','{$migration_row[1]}')\n\t\t";
                            }
                        }
                    }

                    if ($value['timestamp'])
                    {
                        $migration_windows[] = "'created_at' => array('type' => 'DATE', 'null' => TRUE)";
                        $migration_windows[] = "'updated_at' => array('type' => 'DATETIME', 'null' => TRUE)";
                        $migration = $migration . "->addColumn('created_at','date')\n\t\t";
                        $migration = $migration . "->addColumn('updated_at','datetime')\n\t\t";
                    }
                    if (isset($value['unique']) && !empty($value['unique']))
                    {
                        $unique_fields = implode(',', $this->process_unique($value['unique']));
                        $migration = $migration . "->addIndex([{$unique_fields}], ['unique' => true])\n\t\t";
                    }
                    $migration = $migration . '->create();';
                    $migration_template = file_get_contents('../mkdcore/source/model/Migration_file.php');
                    $migration_template = $this->inject_substitute($migration_template, 'upper_case_model', $this->to_camel_case($upper_case_migration_model));
                    $migration_template = $this->inject_substitute($migration_template, 'model', $model);
                    $migration_template = $this->inject_substitute($migration_template, 'migration', $migration);
                    $this->_migration_template[($this->_config['migration_number'] + $model_key) . '_' . $model . '.php'] = $migration_template;

                    $migration_template_window = file_get_contents('../mkdcore/source/model/Migration_file_window.php');
                    $migration_template_window = $this->inject_substitute($migration_template_window, 'upper_case_model', $this->to_camel_case($upper_case_migration_model));
                    $migration_template_window = $this->inject_substitute($migration_template_window, 'model', $model);
                    $migration_template_window = $this->inject_substitute($migration_template_window, 'migration', '[' . implode(",\n", $migration_windows) . ']');
                    $this->_migration_template_window[str_pad($model_key, 3, '0', STR_PAD_LEFT) . '_' . $value['name'] . '.php'] = $migration_template_window;
                    $seed_template_window = '';

                    if (!empty($value['seed']))
                    {
                        $seed = '';
                        $seed_template = file_get_contents('../mkdcore/source/model/Seed_file.php');
                        $seed_template_window = file_get_contents('../mkdcore/source/model/Seed_file_window.php');
                        $seed_template = $this->inject_substitute($seed_template, 'upper_case_model', $this->to_camel_case($upper_case_migration_model));
                        $seed_template = $this->inject_substitute($seed_template, 'model', $model);
                        $seed_template_window = $this->inject_substitute($seed_template_window, 'upper_case_model', $this->to_camel_case($upper_case_migration_model));
                        $seed_template_window = $this->inject_substitute($seed_template_window, 'model', $model);
                        foreach ($value['seed'] as $seed_row) {
                            $seed .= "\t\t[\n";
                            foreach ($seed_row as $seed_key => $seed_value) {
                                if (is_string($seed_value))
                                {
                                    if(strstr($seed_value, 'password_hash') !== false)
                                    {
                                        $seed .= "\t\t\t'{$seed_key}' => {$seed_value},\n";
                                    }
                                    else
                                    {
                                        $seed .= "\t\t\t'{$seed_key}' => '{$seed_value}',\n";
                                    }
                                }
                                else
                                {
                                    $seed .= "\t\t\t'{$seed_key}' => {$seed_value},\n";
                                }
                            }
                            if ($value['timestamp'])
                            {
                                $seed .= "\t\t\t'created_at' => date('Y-m-j'),\n";
                                $seed .= "\t\t\t'updated_at' => date('Y-m-j H:i:s'),\n";
                            }
                            $seed .= "\t\t],\n";
                        }
                        $seed_template = $this->inject_substitute($seed_template, 'seed', $seed);
                        $seed_template_window = $this->inject_substitute($seed_template_window, 'seed', $seed);
                        $this->_seed_template[$this->to_camel_case($model, TRUE) . 'Seeder.php'] = $seed_template;
                    }

                    if (strlen($seed_template_window) > 0)
                    {
                        $migration_template_window = $this->inject_substitute($migration_template_window, 'after_function', $seed_template_window);
                        $migration_template_window = $this->inject_substitute($migration_template_window, 'after', '$this->seed_data();');
                    }
                    else
                    {
                        $migration_template_window = $this->inject_substitute($migration_template_window, 'after_function', '');
                        $migration_template_window = $this->inject_substitute($migration_template_window, 'after', '');
                    }

                    $this->_migration_template_window[str_pad($model_key, 3, '0', STR_PAD_LEFT) . '_' . $value['name'] . '.php'] = $migration_template_window;
                }
            }
            else
            {
                $model_template = $this->inject_substitute($model_template, 'allowed_fields', '');
                $model_template = $this->inject_substitute($model_template, 'labels', '');
                $model_template = $this->inject_substitute($model_template, 'validation_rules', '');
                $model_template = $this->inject_substitute($model_template, 'validation_edit_rules', '');
            }

            if (isset($value['mapping']) && count(array_keys($value['mapping'])) > 0)
            {
                $model_template = $this->inject_substitute($model_template, 'mapping', $this->output_view_model_mapping($value['mapping']));
            }
            else
            {
                $model_template = $this->inject_substitute($model_template, 'mapping', '');
            }

            if(!empty($value['import_fields']))
            {
                $model_template = $this->inject_substitute($model_template, 'import_fields', $this->output_import_fields($value['import_fields']));

            }
            else
            {
                $model_template = $this->inject_substitute($model_template, 'import_fields', '');
            }

            $this->_model_template['../release/application/models/' . $upper_case_model . '.php'] = $model_template;
        }

        return $this->_template;
    }

    protected function process_unique($list)
    {
        $result = [];
        foreach ($list as $key => $value)
        {
            $result[] = "\"$value\"";
        }
        return $result;
    }

    protected function to_camel_case($str, $capitalise_first_char = FALSE)
    {
        preg_match('#^_*#', $str, $underscores);
        $underscores = current($underscores);
        $camel = str_replace(' ', '', ucwords(str_replace('_', ' ', $str)));
        $camel = strtolower(substr($camel, 0, 1)).substr($camel, 1);

        if($capitalise_first_char)
        {
            $camel[0] = strtoupper($camel[0]);
        }
        return $underscores . $camel;
    }

    protected function output_import_fields($fields)
    {
        $result = "\n\tpublic function get_import_fields()\n\t{\n\t\t";
        $result .= "return [\n\t\t\t";
        $last_index = count($fields) - 1;
        for($i = 0; $i < count($fields); $i++)
        {
            if($i == $last_index)
            {
                $result .= "'" . $fields[$i] . "'";
            }
            else
            {
                $result .= "'" . $fields[$i] . "'". ",\n\t\t\t";
            }
        }
        $result .= "\n\t\t];\n\t";
        $result .= "}\n";

        return $result;
    }


    protected function output_view_model_mapping ($mappings)
    {
        $result = '';
        foreach ($mappings as $mapping_key => $mapping_value)
        {
            $result .= "\n\tpublic function {$mapping_key}_mapping ()\n\t{";
            if (count(array_keys($mapping_value)) > 0)
            {
                $result .= "\n\t\treturn [\n";
                foreach ($mapping_value as $key => $value)
                {
                    if (is_numeric($key))
                    {
                        $result .= "\t\t\t{$key} => '{$value}',\n";
                    }
                    else
                    {
                        $result .= "\t\t\t'{$key}' => '{$value}',\n";
                    }
                }
                $result .= "\t\t];";
            }
            $result .= "\n\t}\n";
        }
        return $result;
    }

    public function inject_template ()
    {
        foreach ($this->_model_template as $key => $value)
        {
            file_put_contents($key, $value);
        }

        foreach ($this->_migration_template as $key => $value)
        {
            file_put_contents('../release/db/migrations/' . $key, $value);
        }
        foreach ($this->_migration_template_window as $key => $value)
        {
            file_put_contents('../release/db/windows/' . $key, $value);
        }
        foreach ($this->_seed_template as $key => $value)
        {
            file_put_contents('../release/db/seeds/' . $key, $value);
        }
        foreach ($this->_seed_template_window as $key => $value)
        {
            file_put_contents('../release/db/windows/' . $key, $value);
        }
    }

    public function destroy ()
    {
        foreach ($this->_models as $value)
        {
            $upper_case_model = ucfirst($value['name']) . '_model' . '.php';
            $file_name = '../release/application/models/' . $upper_case_model;
            if (file_exists($file_name))
            {
                unlink($file_name);
            }
        }

        $files = glob('../release/db/migrations/*');
        foreach ($files as $file) {
            (!is_dir($file) && $file != '../release/db/migrations/index.html') ? unlink($file) : null;
        }

        $files = glob('../release/db/seeds/*');
        foreach ($files as $file) {
            (!is_dir($file) && $file != '../release/db/seeds/index.html') ? unlink($file) : null;
        }
        $files = glob('../release/db/windows/*');
        foreach ($files as $file) {
            (!is_dir($file) && $file != '../release/db/windows/index.html') ? unlink($file) : null;
        }
    }

    private function _join_template($data)
    {
        return "\tpublic function get_{$data['name']} (\$where)\n\t{\n\t\treturn \$this->_join ('{$data['name']}', '{$data['field']}', \$where, []);\n\t}\n\n";
    }

    private function _join_paginate_template($data)
    {
        return "\tpublic function get_{$data['name']}_paginated (\$page, \$limit, \$where)\n\t{\n\t\treturn \$this->_join_paginate ('{$data['name']}', '{$data['field']}', \$where, \$page, \$limit, '', 'ASC', []);\n\t}\n\n";
    }
}