<?php
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2020*/
include_once 'Builder.php';
class GraphQL_builder extends Builder
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
        /**
         * Steps:
         * 1.Loop through all model, make schema.graphql
         * 2.Make Model Files
         * 3.Make Resolvers files
         * 4.Generate index.js(resolver)
         * 5.Generate index.js(models)
         *
         */
        $ignore_model_list = [];
        $ignore_id_field_list = [];
        $role = '';
        $graphql = [];

        if (isset($this->_config['graphql']))
        {
            $graphql = $this->_config['graphql'];
        }

        if (isset($graphql['ignore_models']))
        {
            $ignore_model_list = $graphql['ignore_models'];
        }

        if (isset($graphql['ignore_id_field']))
        {
            $ignore_id_field_list = $graphql['ignore_id_field'];
        }

        $model_list = [];
        $schema_list = [];
        $query_list = [];
        $mutation_list = [];
        $import_files = [];
        $resolver_query = [];
        $resolver_mutation = [];
        $resolver_relation = [];

        $default_model_template = file_get_contents('../mkdcore/source/graphql/model.php');
        $default_resolve_all_template = file_get_contents('../mkdcore/source/graphql/resolver_all.php');
        $default_resolve_single_template = file_get_contents('../mkdcore/source/graphql/resolver_single.php');
        $default_resolve_delete_template = file_get_contents('../mkdcore/source/graphql/resolver_delete.php');
        $default_resolve_edit_template = file_get_contents('../mkdcore/source/graphql/resolver_edit.php');
        $default_resolve_add_template = file_get_contents('../mkdcore/source/graphql/resolver_add.php');
        $default_resolve_relation_template = file_get_contents('../mkdcore/source/graphql/resolver_relation.php');
        $default_model_index_template = file_get_contents('../mkdcore/source/graphql/model_index.php');

        $this->_render_list['../graphql/src/models/index.js'] = $default_model_index_template;

        $resolve_index_template = file_get_contents('../mkdcore/source/graphql/resolver_index.php');

        foreach ($this->_config['models'] as $model_key => $value)
        {
            $model_template = $default_model_template;
            $upper_case_model = ucfirst($value['name']);
            $model_name = $value['name'];
            $model_list[] = $value['name'];
            $model_template = $this->inject_substitute($model_template, 'subclass_prefix', $this->_config['config']['subclass_prefix']);
            $model_template = $this->inject_substitute($model_template, 'model', $model_name);
            $model_template = $this->inject_substitute($model_template, 'associations', '' . implode("\n", $this->generate_model_association($value['field'], $upper_case_model, $ignore_id_field_list)));
            $model_template = $this->inject_substitute($model_template, 'mapping', $this->generate_model_mapping($value['mapping'], $upper_case_model));
            $model_template = $this->inject_substitute($model_template, 'fields',  '      ' . implode(",\n      ", $this->generate_model_field($value['field'], $value['timestamp'])));
            $model_template = $this->inject_substitute($model_template, 'uppercase_model', $upper_case_model);

            $this->_render_list['../graphql/src/models/' . $model_name . '.js'] = $model_template;

            if (in_array($value['name'], $ignore_model_list))
            {
                continue;
            }

            $resolve_all_template = $default_resolve_all_template;
            $resolve_single_template = $default_resolve_single_template;
            $resolve_delete_template = $default_resolve_delete_template;
            $resolve_edit_template = $default_resolve_edit_template;
            $resolve_add_template = $default_resolve_add_template;

            $resolve_all_template = $this->inject_substitute($resolve_all_template, 'model_name', $model_name);
            $resolve_single_template = $this->inject_substitute($resolve_single_template, 'model_name', $model_name);
            $resolve_delete_template = $this->inject_substitute($resolve_delete_template, 'model_name', $model_name);
            $resolve_edit_template = $this->inject_substitute($resolve_edit_template, 'model_name', $model_name);
            $resolve_edit_template = $this->inject_substitute($resolve_edit_template, 'fields', '   ' . implode(",\n   ", $this->generate_update_field_list($value['field'])));
            $resolve_add_template = $this->inject_substitute($resolve_add_template, 'model_name', $model_name);
            $resolve_add_template = $this->inject_substitute($resolve_add_template, 'fields', '   ' . implode(",\n   ", $this->generate_update_field_list($value['field'])));

            $schema_list[] = $this->generate_schema($model_name, $value['field'], $value['timestamp'], $ignore_id_field_list);
            $query_list[] = $this->generate_schema_query($model_name);
            $import_files[] = $this->generate_import_files($model_name);
            $resolver_query = array_merge($resolver_query,  $this->generate_resolver_query($model_name));
            $resolver_mutation = array_merge($resolver_mutation,  $this->generate_resolver_mutation($model_name));
            // $resolver_relation = array_merge($resolver_relation,  $this->generate_resolver_relation($model_name, $value['field']));

            $mutation_list[] = $this->generate_schema_mutation($model_name, $value['field']);
            $this->_render_list['../graphql/src/resolvers/all/all' . ucFirst($this->to_camel_case($model_name)) . '.js'] = $resolve_all_template;
            $this->_render_list['../graphql/src/resolvers/single/single' . ucFirst($this->to_camel_case($model_name)) . '.js'] = $resolve_single_template;
            $this->_render_list['../graphql/src/resolvers/delete/delete' . ucFirst($this->to_camel_case($model_name)) . '.js'] = $resolve_delete_template;
            $this->_render_list['../graphql/src/resolvers/update/update' . ucFirst($this->to_camel_case($model_name)) . '.js'] = $resolve_edit_template;
            $this->_render_list['../graphql/src/resolvers/create/create' . ucFirst($this->to_camel_case($model_name)) . '.js'] = $resolve_add_template;

            $relations = $this->get_relation_list($model_name, $value['field']);

            foreach ($relations as $relation_key => $relation_value)
            {
                $resolve_relation_template = $default_resolve_relation_template;
                $resolve_relation_template = $this->inject_substitute($resolve_relation_template, 'model_name', $relation_value);
                $resolve_relation_template = $this->inject_substitute($resolve_relation_template, 'upper_model_name', ucFirst($this->to_camel_case($relation_value)));
                $this->_render_list['../graphql/src/resolvers/relation/relation' . ucFirst($this->to_camel_case($relation_value)) . '.js'] = $resolve_relation_template;
            }
        }

        $resolve_index_template = $this->inject_substitute($resolve_index_template, 'import_files', implode("\n", $import_files));
        $resolve_index_template = $this->inject_substitute($resolve_index_template, 'querys', '  ' . implode(",\n  ", $resolver_query));
        $resolve_index_template = $this->inject_substitute($resolve_index_template, 'mutations', '  ' . implode(",\n  ", $resolver_mutation));
        $resolve_index_template = $this->inject_substitute($resolve_index_template, 'relations', '');
        // $resolve_index_template = $this->inject_substitute($resolve_index_template, 'relations', implode(",\n", $resolver_relation));

        $this->_render_list['../graphql/src/types/schema.graphql'] = implode("\n", $schema_list) . $this->schema_query_all($query_list) . $this->schema_mutation_all($mutation_list);
        $this->_render_list['../graphql/src/resolvers/index.js'] = $resolve_index_template;
    }

    public function generate_model_association ($fields, $upper_case_model, $ignore_id_field_list)
    {
        $result = [];
        foreach ($fields as $key => $value)
        {
            $field_name = $value[0];
            if (strstr($field_name, '_id') !== FALSE && !in_array($field_name, $ignore_id_field_list))
            {
                $upper_field_name = ucfirst($field_name);
                $other_model = str_replace('_id', '', $field_name);
                $result[] =<<<DOC
  $upper_case_model.associate = models => {
      $upper_case_model.belongsTo(models.$other_model, {
        foreignKey: "$field_name",
        constraints: false
      });
  };
DOC;
            }
        }
        return $result;
    }

    public function generate_model_mapping ($mapping, $upper_case_model)
    {
        $result = [];
        foreach ($mapping as $key => $pair)
        {
            $pair_str = [];
            foreach ($pair as $pair_key => $pair_value)
            {
                $pair_str[] = "\"{$pair_key}\": \"{$pair_value}\"";
            }

            $pair_str_final = '        ' . implode(",\n        ", $pair_str);

            $result[] =<<<DOC
        $key: {
$pair_str_final
        }
DOC;

        }

        $result_str = implode(",\n", $result);
        $final = <<<DOC2
  $upper_case_model.getMapping = () => {
     return {
$result_str
     };
  };
DOC2;
        return $final;
    }

    public function generate_model_field ($fields, $timestamp)
    {
        $result = [];
        foreach ($fields as $key => $value)
        {
            $field_name = $value[0];
            $field_type = '';
            switch ($value[1])
            {
                case 'integer':
                    $field_type = 'INTEGER';
                    break;
                case 'float':
                    $field_type = 'FLOAT';
                    break;
                case 'string':
                case 'password':
                case 'image':
                    $field_type = 'STRING';
                    break;
                case 'text':
                case 'image':
                case 'file':
                    $field_type = 'TEXT';
                    break;
                case 'date':
                    $field_type = 'DATEONLY';
                    break;
                case 'datetime':
                    $field_type = 'DATE';
                    break;

                default:
                    if (strstr($value[1], 'image') !== FALSE)
                    {
                        $field_type = 'STRING';
                    }

                    break;
            }
            if ($field_name == 'id')
            {
                continue;
            }

            $result[] = "$field_name: DataTypes.$field_type";
        }

        if ($timestamp)
        {
            $result[] = "created_at: DataTypes.DATEONLY";
            $result[] = "updated_at: DataTypes.DATE";
        }

        return $result;
    }

    public function generate_update_field_list ($fields)
    {
        $result = [];
        foreach ($fields as $key => $value)
        {
            $field_name = $value[0];
            if ($field_name == 'id')
            {
                continue;
            }
            $result[] = "$field_name: args.$field_name";
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

    public function generate_schema ($model_name, $fields, $timestamp, $ignore_id_field_list)
    {
        $model_camel = ucFirst($this->to_camel_case($model_name));
        $result = [];
        foreach ($fields as $key => $value)
        {
            $field_name = $value[0];
            $field_type = '';
            switch ($value[1])
            {
                case 'integer':
                    $field_type = 'Int';
                    break;
                case 'float':
                    $field_type = 'Float';
                    break;
                case 'string':
                case 'password':
                case 'image':
                    $field_type = 'String';
                    break;
                case 'text':
                case 'image':
                case 'file':
                    $field_type = 'String';
                    break;
                case 'date':
                    $field_type = 'String';
                    break;
                case 'datetime':
                    $field_type = 'String';
                    break;

                default:
                    if (strstr($value[1], 'image') !== FALSE)
                    {
                        $field_type = 'String';
                    }

                    break;
            }
            if ($field_name == 'id')
            {
                continue;
            }

            $result[] = "$field_name: $field_type";

            $field_name = $value[0];

            if (strstr($field_name, '_id') !== FALSE)
            {
                if (strstr($field_name, '_file_id') === FALSE)
                {
                    if (strstr($field_name, '_image_id') === FALSE)
                    {
                        if (strstr($field_name, '_holder_id') === FALSE)
                        {
                            if (!in_array($field_name, $ignore_id_field_list))
                            {
                                $other_model = str_replace('_id', '', $field_name);
                                $other_model_camel = ucFirst($this->to_camel_case($other_model));
                                $result[] = "$other_model: $other_model_camel";
                            }
                        }
                    }
                }
            }
        }

        if ($timestamp)
        {
            $result[] = "created_at: String";
            $result[] = "updated_at: String";
        }
        $field_str = implode("\n    ", $result);


        $final = <<<DOC
type $model_camel {
    id: ID!
    $field_str
}
DOC;
        return $final;
    }

    public function generate_schema_mutation ($model_name, $fields)
    {
        $model_camel = ucFirst($this->to_camel_case($model_name));
        $result = [];
        foreach ($fields as $key => $value)
        {
            $field_name = $value[0];
            $field_type = '';
            switch ($value[1])
            {
                case 'integer':
                    $field_type = 'Int';
                    break;
                case 'float':
                    $field_type = 'Float';
                    break;
                case 'string':
                case 'password':
                case 'image':
                    $field_type = 'String';
                    break;
                case 'text':
                case 'image':
                case 'file':
                    $field_type = 'String';
                    break;
                case 'date':
                    $field_type = 'String';
                    break;
                case 'datetime':
                    $field_type = 'String';
                    break;

                default:
                    if (strstr($value[1], 'image') !== FALSE)
                    {
                        $field_type = 'String';
                    }

                    break;
            }
            if ($field_name == 'id')
            {
                continue;
            }

            if (strstr($field_name, '_id') !== FALSE)
            {
                $result[] = "$field_name: ID!";
            }
            else
            {
                $result[] = "$field_name: $field_type!";
            }
        }

        $parameters_str = implode(", ", $result);


        $final = <<<DOC
    create$model_camel($parameters_str): $model_camel!
    update$model_camel(id: ID!, $parameters_str): [Int!]!
    delete$model_camel(id: ID!): Int!
DOC;
        return $final;
    }

    public function generate_schema_query ($model_name)
    {
        $model_camel = ucFirst($this->to_camel_case($model_name));

        $final = <<<DOC
    {$model_name}s: [{$model_camel}!]!
    {$model_name}(id: ID!): {$model_camel}
DOC;
        return $final;
    }

    public function generate_import_files ($model_name)
    {
        $model_camel = ucFirst($this->to_camel_case($model_name));

        $final = <<<DOC
const all{$model_camel}Resolver = require("./all/all{$model_camel}");
const single{$model_camel}Resolver = require("./single/single{$model_camel}");
const create{$model_camel}Resolver = require("./create/create{$model_camel}");
const update{$model_camel}Resolver = require("./update/update{$model_camel}");
const delete{$model_camel}Resolver = require("./delete/delete{$model_camel}");

DOC;
        return $final;
    }

    public function generate_resolver_query ($model_name)
    {
        $model_camel = ucFirst($this->to_camel_case($model_name));

        return [
            " {$model_name}s: all{$model_camel}Resolver",
            " {$model_name}: single{$model_camel}Resolver",
        ];
    }

    public function generate_resolver_mutation ($model_name)
    {
        $model_camel = ucFirst($this->to_camel_case($model_name));

        return [
            " create{$model_camel}: create{$model_camel}Resolver",
            " update{$model_camel}: update{$model_camel}Resolver",
            " delete{$model_camel}: delete{$model_camel}Resolver",
        ];
    }

    public function generate_resolver_relation ($model_name, $fields)

    {
        $model_camel = ucFirst($this->to_camel_case($model_name));
        $result = [];

        foreach ($fields as $key => $value)
        {
            $field_name = $value[0];
            if (strstr($field_name, '_id') !== FALSE)
            {
                if (strstr($field_name, '_file_id') !== FALSE ||
                strstr($field_name, '_image_id') !== FALSE ||
                strstr($field_name, '_holder_id') !== FALSE )
                {
                    continue;
                }
                $other_model = str_replace('_id', '', $field_name);
                $upper_other_model = ucfirst($this->to_camel_case($other_model));
                $result[] = "{$other_model}: relation{$upper_other_model}Resolver";
            }
        }

        if (count($result) > 0)
        {
            $other_str = implode(",\n\t ", $result);
            $final =<<<DOC
    $model_camel: {
     {$other_str}
    }
DOC;
            return [$final];
        }
        return [];
    }

    public function get_relation_list ($model_name, $fields)
    {
        $result = [];

        foreach ($fields as $key => $value)
        {
            $field_name = $value[0];
            if (strstr($field_name, '_id') !== FALSE)
            {
                if (strstr($field_name, '_file_id') !== FALSE ||
                strstr($field_name, '_image_id') !== FALSE ||
                strstr($field_name, '_holder_id') !== FALSE )
                {
                    continue;
                }
                $other_model = str_replace('_id', '', $field_name);
                $result[] = $other_model;
            }
        }

        return $result;
    }

    public function schema_query_all ($query_list)
    {
        $query_str = implode("\n\n", $query_list);
        $final = <<<DOC

type Query {
$query_str
}
DOC;
        return $final;
    }

    public function schema_mutation_all ($query_list)
    {
        $query_str = implode("\n\n", $query_list);
        $final = <<<DOC

type Mutation {
$query_str
}
DOC;
        return $final;
    }

    public function build()
    {
        $this->init();

        foreach ($this->_render_list as $path => $template) {
            file_put_contents($path, $template);
        }
    }

    public function destroy()
    {
        $this->init();

        foreach ($this->_render_list as $path => $builder)
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
}