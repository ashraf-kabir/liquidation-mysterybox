<?php
include_once 'Builder.php';
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
class Preschema_builder extends Builder{

    protected $_config =[];
    private $_roles = [];
    private $_portals = [];
    private $_models = [];
    private $_mappings = [];
    private $_controllers = [];
    private $_translations=[];
    private $_menus = [];
    private $_csrf_exclude_uris = [];
    private $_csrf_exclude_routes = [
        "login",
        "forgot",
        "reset/.*",
        "register"
    ];
    private $_entities = [
        'roles' => [],
        'portals'=> [],
        'models' => [],
        'mappings' => [],
        'controllers'=> [],
        'translations' => [],
        'menus'=> [],
        'filter_fields'=> [],
        'listing_fields'=> [],
        'listing_headers' => [],
        'edit_fields' => [],
        'add_fields' => [],
        'rows' => [],
        'view_fields' => []
    ];

    public function __construct($config)
    {
        $this->_config = explode(';',$config);
    }

    private function _get_field_details($type, $label)
    {
       $return_str =  '"string", [{"limit" : 255}], "' .  $label . '", "required|max_length[255]", "required|max_length[255]" ';

       if($label === 'xyzid')
       {
            return '"integer", [], "' .  $label . '", "", "" ';
       }

       switch ($type)
       {
            case  'STR':
                $return_str =  '"string", [{"limit" : 255}], "' .  $label . '", "required|max_length[255]", "required|max_length[255]" ';
            break;
            case  'EMAIL':
                $return_str =  '"string", [{"limit" : 255}], "' .  $label . '", "trim|required|valid_email","trim|required|valid_email" ';
            break;
            case  'INT':
                $return_str =  '"integer", [], "' .  $label . '", "required|integer", "required|integer" ';
            break;

            case  'DATE':
                $return_str = '"date" ,[], "' .  $label . '", "required|date", "required|date" ';
            break;

            case  'DATETIME':
                $return_str = '"datetime" ,[], "' .  $label . '", "required", "required" ';
            break;

            case  'TEXT':
                $return_str = '"text" ,[], "' .  $label . '", "required", "required" ';
            break;

            case  'IMAGE':
                $return_str =  '"image|250|250|500|500",[], "' .  $label . '", "required", "" ';
            break;

            case  'PASSWORD':
                $return_str =   '"password", [{"limit" : 255}], "' .  $label . '", "required|max_length[255]", "" ';
            break;

            case  'FILE':
                $return_str =  '"file" ,[], "' .  $label . '", "required", "" ';;
            break;

            case  'FLOAT':
                $return_str = '"float", [], "' .  $label . '", "required|float", "required|float" ';
            break;
       }

       return $return_str;
    }

    private function _translate_string($text)
    {
        $first_chars = substr($text,0, 3 );
        $translation_item = "\n\t\t". '"xyz'. $text . '" : ' . '"'. ucwords(str_replace("_", " ", $text)) .'"' ;

        if($first_chars === 'xyz')
        {
            $temp = substr($text, 3, strlen($text) - 1 );
            $translation_item =  "\n\t\t". '"xyz'.  $temp . '" : ' . '"'. ucwords(str_replace("_", " ",  $temp)) .'"' ;
            $text = $temp;
        }

        if(!in_array( $translation_item, $this->_translations ))
        {
            $this->_translations[] =   $translation_item ;
        }
        return 'xyz' . $text;

    }

    private function _array_to_string($input_array, $formate_string = '')
    {
       return  implode(', ',$input_array) . $formate_string;
    }

    private function _build_models()
    {
        // get models array
        for($i = 0; $i < count($this->_entities['models']); $i ++)
        {
            $model_array = explode('|', $this->_entities['models'][$i]);
            $model_mapping = [];
            $model_fields = [];
            // build the fields
            for($a = 1; $a < count( $model_array); $a ++)
            {
                $field =  explode(':', $model_array[$a]);
                $template = file_get_contents('templates/source/preschema/model_field.php');
                $template = $this->inject_substitute($template, 'name', $field[0]);
                $template = $this->inject_substitute($template, 'field_details', $this->_get_field_details(trim($field[1]),  $this->_translate_string($field[0]) ));
                $model_fields[] = "\n\t\t" . $template ;
            }

            // get model mapping
            for($x = 0; $x < count($this->_entities['mappings']); $x++)
            {
                $mapping_array = explode('|', $this->_entities['mappings'][$x]);
                if($mapping_array[0] ===  $model_array[0] )
                {
                    $map_array = [];
                    $map_code = 0;
                    for($z = 2; $z < count($mapping_array); $z++)
                    {
                        $map_array[] =  "\n\t\t\t" .  '"'.$map_code.'" : '. '"'. $this->_translate_string( $mapping_array[$z]) .'"';
                        $map_code ++;
                    }
                    // inject to the template file
                    $template = file_get_contents('templates/source/preschema/model_mapping.php');
                    $template = $this->inject_substitute($template, 'field', $mapping_array[1]);
                    $template = $this->inject_substitute($template, 'mapping', $this->_array_to_string($map_array, "\n\t\t"));
                    $model_mapping[] =  "\n\t\t" . $template;
                }
            }
            $template = file_get_contents('templates/source/preschema/model.php');
            $template = $this->inject_substitute($template, 'name', $model_array[0]);
            $template = $this->inject_substitute($template, 'fields', $this->_array_to_string($model_fields));
            $template = $this->inject_substitute($template, 'mapping',$this->_array_to_string($model_mapping ));
            $this->_models[] = $template;
        }

    }

    private function _build_portals()
    {
        for($i = 0; $i < count($this->_entities['portals']); $i++ )
        {
            $portal_menu = [];
            $portal_array = explode('|', $this->_entities['portals'][$i]);
            // portal menus
            for($x = 0; $x < count($this->_entities['menus']); $x++)
            {
                $menu_item_array = explode('|', $this->_entities['menus'][$x]);

                if($portal_array[0] ===  $menu_item_array[0])
                {
                    $field =  explode('::', $menu_item_array[1]);
                    if(count($field) === 1) // no sub_menu
                    {
                        $template = file_get_contents('templates/source/preschema/menu_item.php');
                        $template = $this->inject_substitute($template, 'menu_label', $this->_translate_string($menu_item_array[1]) );
                        $template = $this->inject_substitute($template, 'menu_link',$menu_item_array[2] );
                        $portal_menu[] =   "\n\t\t\t" .  $template;
                    }
                    else // menu has sub_menu items
                    {
                        $parent_nav = substr($menu_item_array[1], 0, strpos($menu_item_array[1], "("));
                        $parent_nav = $this->_translate_string($parent_nav);
                        $all_sub_menu_items = [];
                        preg_match('#\((.*?)\)#', $menu_item_array[1], $match);
                        $sub_menus =  $match[1];
                        $sub_menu_array = explode(',',$sub_menus);
                        for($y = 0; $y < count($sub_menu_array); $y ++ )
                        {
                            $nav_label = explode('::', str_replace(",", '', $sub_menu_array[$y]))[0];
                            $nav_link = explode('::', str_replace(",", '', $sub_menu_array[$y]))[1];
                            $menu_template = file_get_contents('templates/source/preschema/menu_item.php');
                            $menu_template = $this->inject_substitute($menu_template, 'menu_label',  $this->_translate_string($nav_label) );
                            $menu_template = $this->inject_substitute($menu_template, 'menu_link', $nav_link  );
                            $all_sub_menu_items[] = "\n\t\t\t\t" . $menu_template;
                        }
                        //now add the menu to
                        $template = file_get_contents('templates/source/preschema/sub_menu.php');
                        $template = $this->inject_substitute($template, 'menu_label', $this->_translate_string($nav_label) );
                        $template = $this->inject_substitute($template, 'menu_links', $this->_array_to_string( $all_sub_menu_items, "\n\t\t\t") );
                        //die( $template);
                        $portal_menu[] =   "\n\t\t\t" .  $template;
                    }
                }
            }
            //now build the portal
            $template = file_get_contents('templates/source/preschema/portal.php');
            $template = $this->inject_substitute($template, 'name', $portal_array[0] );
            $template = $this->inject_substitute($template, 'model', $portal_array[1] );
            $template = $this->inject_substitute($template, 'login', $portal_array[2] );
            $template = $this->inject_substitute($template, 'role',  $portal_array[0] );
            $template = $this->inject_substitute($template, 'menu', implode(', ', $portal_menu) );

            for($z = 0; $z < count($this->_csrf_exclude_routes); $z++)
            {
                $this->_csrf_exclude_uris[] =  "\n\t\t\t" . '"'. $portal_array[0] .'/'. $this->_csrf_exclude_routes[$z] .'"';
            }

            $this->_portals[] = $template;
        }
    }

    private function _build_portal_operation()
    {
        for($i = 0; $i < count($this->_entities['portals']); $i ++ )
        {
            $portal_array = explode('|', $this->_entities['portals'][$i]);
            $template = file_get_contents('templates/source/preschema/portal_operation.php');
            $template = $this->inject_substitute($template, 'model',str_replace("_model", "", $portal_array[1] ));
            $template = $this->inject_substitute($template, 'portal_name', strtolower(trim($portal_array[0]). '_operation') );
            $this->_models[] = $template;
        }
    }

    private function _build_roles()
    {
        $roles_array = explode('|', ($this->_entities['roles'][0] ?? '') );
        $role_id = 0;
        for($i = 0; $i < count($roles_array); $i ++)
        {
            $role_id = $i + 1;
            $template = file_get_contents('templates/source/preschema/roles.php');
            $template = $this->inject_substitute($template, 'name', $roles_array[$i] );
            $template = $this->inject_substitute($template, 'id',  $role_id);
            $this->_roles[] =  "\n\t\t\t" . $template;
        }
    }

    /**
     *  controller
     *  index 0  model,
     *  index 1 page name
     *  index 2 route third
     *  index 3 portal
     */
    private function _build_controllers()
    {

        for($i =0; $i < count($this->_entities['controllers']); $i ++  )
        {
           $controller_array = explode('|', $this->_entities['controllers'][$i]);
           $model =  $controller_array[0];
           $page_name =  $controller_array[1];
           $route =  $controller_array[2];
           $portal =  $controller_array[3];
           $add_fields = '';
           $edit_fields = '';
           $filter_fields = '';
           $listing_fields = '';
           $header_fields = '';
           $row_fields  = '';
           $view_fields = '';

           for($a = 0; $a < count($this->_entities['add_fields']); $a++)
           {
                $field_array = explode('|', $this->_entities['add_fields'][$i]);
                if($field_array[0] === $model && $field_array[1] === $portal)
                {

                     $fields = $field_array[2] ?? '';

                     if(!empty($fields))
                     {
                        $fields = '"' . str_replace(",", '","', $fields) . '"';
                        $template =  file_get_contents('templates/source/preschema/controller_fields.php');
                        $template = $this->inject_substitute($template, 'field_name', "add_fields" );
                        $template = $this->inject_substitute($template, 'fields', $fields );
                        $add_fields =  $template;
                     }
                     else
                     {
                        $add_fields = '"add_fields" : [],';
                     }

                }
           }

           for($a = 0; $a < count($this->_entities['listing_fields']); $a++)
           {
                $field_array = explode('|', $this->_entities['listing_fields'][$i]);
                if($field_array[0] === $model && $field_array[1] === $portal)
                {
                     $fields = $field_array[2]  ?? '';

                     if(!empty($fields))
                     {
                        $fields = '"' . str_replace(",", '","', $fields) . '"';
                        $template =  file_get_contents('templates/source/preschema/controller_fields.php');
                        $template = $this->inject_substitute($template, 'field_name', "listing_fields_api" );
                        $template = $this->inject_substitute($template, 'fields',$fields);
                        $listing_fields =  $template;
                     }
                     else
                     {
                        $listing_fields = '"listing_fields_api" : [],';
                     }

                }
           }

           for($a = 0; $a < count($this->_entities['edit_fields']); $a++)
           {
                $field_array = explode('|', $this->_entities['edit_fields'][$i]);
                if($field_array[0] === $model && $field_array[1] === $portal)
                {
                     $fields = $field_array[2]  ?? '';

                     if(!empty($fields))
                     {
                        $fields = '"' . str_replace(",", '","', $fields) . '"';
                        $template =  file_get_contents('templates/source/preschema/controller_fields.php');
                        $template = $this->inject_substitute($template, 'field_name', "edit_fields" );
                        $template = $this->inject_substitute($template, 'fields',$fields);
                        $edit_fields =  $template;
                     }
                     else
                     {
                        $edit_fields = '"edit_fields" : [],';
                     }

                }
           }

           for($a = 0; $a < count($this->_entities['listing_headers']); $a++)
           {
                $field_array = explode('|', $this->_entities['listing_headers'][$i]);
                if($field_array[0] === $model && $field_array[1] === $portal)
                {
                     $fields = $field_array[2] ?? '';
                     if(!empty($fields))
                     {
                        $fields = '"' . str_replace(",", '","', $fields) . '"';
                        $template =  file_get_contents('templates/source/preschema/controller_fields.php');
                        $template = $this->inject_substitute($template, 'field_name', "listing_headers" );
                        $template = $this->inject_substitute($template, 'fields', $fields);
                        $header_fields =  $template;
                     }
                     else
                     {
                        $header_fields = '"listing_headers" : [],';
                     }

                }
           }

           for($a = 0; $a < count($this->_entities['filter_fields']); $a++)
           {
                $field_array = explode('|', $this->_entities['filter_fields'][$i]);
                if($field_array[0] === $model && $field_array[1] === $portal)
                {
                     $fields = $field_array[2] ?? '';

                     if(!empty($fields))
                     {
                        $fields = '"' . str_replace(",", '","', $fields) . '"';
                        $template =  file_get_contents('templates/source/preschema/controller_fields.php');
                        $template = $this->inject_substitute($template, 'field_name', "filter_fields" );
                        $template = $this->inject_substitute($template, 'fields',$fields);
                        $filter_fields =  $template;
                     }
                     else
                     {
                        $filter_fields = '"filter_fields" : [],';
                     }

                }
           }

           for($a = 0; $a < count($this->_entities['view_fields']); $a++)
           {
                $field_array = explode('|', $this->_entities['view_fields'][$i]);
                if($field_array[0] === $model && $field_array[1] === $portal)
                {

                     $fields = $field_array[2] ?? '';

                     if(!empty($fields))
                     {
                        $fields = '"' . str_replace(",", '","', $fields) . '"';
                        $template =  file_get_contents('templates/source/preschema/controller_fields.php');
                        $template = $this->inject_substitute($template, 'field_name', "view_fields" );
                        $template = $this->inject_substitute($template, 'fields',$fields);
                        $view_fields =  $template;
                     }
                     else
                     {
                        $view_fields = '"view_fields" : [],';
                     }

                }
           }

           for($a = 0; $a < count($this->_entities['rows']); $a++)
           {
                $field_array = explode('|', $this->_entities['rows'][$i]);
                if($field_array[0] === $model && $field_array[1] === $portal)
                {

                     $fields = $field_array[2] ?? '';

                     if(!empty($fields))
                     {
                        $fields = '"' . str_replace(",", '","', $fields) . '"';
                        $fields = str_replace("=", '|', $fields);
                        $template =  file_get_contents('templates/source/preschema/controller_fields.php');
                        $template = $this->inject_substitute($template, 'field_name', "listing_rows" );
                        $template = $this->inject_substitute($template, 'fields',$fields);
                        $row_fields =  $template;
                     }
                     else
                     {
                        $row_fields = '"listing_rows" : [],';
                     }      
                }
           }

           //now build the controller
           $template =  file_get_contents('templates/source/preschema/controller.php');
           $template = $this->inject_substitute($template, 'route', $route );
           $template = $this->inject_substitute($template, 'name', strtolower(str_replace([' ','xyz'],['_', ''], $page_name)));
           $template = $this->inject_substitute($template, 'page_name', $page_name );
           $template = $this->inject_substitute($template, 'model', $model );
           $template = $this->inject_substitute($template, 'controller_name',  ucwords($portal) . '_'.  strtolower(str_replace([' ', 'xyz'], ['_', ''], $page_name)) . '_controller.php' );
           $template = $this->inject_substitute($template, 'controller_api_name', ucwords($portal) . '_api_'.  strtolower(str_replace([' ', 'xyz'], ['_', ''],$page_name)) . '_controller.php' );
           $template = $this->inject_substitute($template, 'listing_fields', $listing_fields );
           $template = $this->inject_substitute($template, 'listing_headers', $header_fields );
           $template = $this->inject_substitute($template, 'listing_rows', $row_fields );
           $template = $this->inject_substitute($template, 'filter_fields', $filter_fields );
           $template = $this->inject_substitute($template, 'add_fields',  $add_fields );
           $template = $this->inject_substitute($template, 'edit_fields', $edit_fields );
           $template = $this->inject_substitute($template, 'view_fields', $view_fields );
           $template = $this->inject_substitute($template, 'portal', $portal );
           $this->_controllers[] = $template;
        }
    }

    public function _build_configuration_file()
    {
        $template =  file_get_contents('templates/source/preschema/configuration.php');
        $template = $this->inject_substitute($template, 'models', implode(', ',$this->_models) );
        $template = $this->inject_substitute($template, 'portals', implode(', ',$this->_portals) );
        $template = $this->inject_substitute($template, 'controllers', implode(', ',$this->_controllers) );
        $template = $this->inject_substitute($template, 'translations', implode(',',$this->_translations));
        $template = $this->inject_substitute($template, 'roles', implode(',',$this->_roles));
        $template = $this->inject_substitute($template, 'csrf_exclude', implode(',',$this->_csrf_exclude_uris));
        file_put_contents(getcwd() .'/preschema_output/configuration_'. time() .'_.json', $template);
    }

    public function init()
    {
        if(!empty($this->_config))
        {
            for($i = 0; $i < count($this->_config); $i++)
            {
                $this->transform_list($this->_config[$i], $i);
            }
        }
        $default_translations = file_get_contents('templates/source/preschema/default_translations.json');

        $translations_values  = (array) json_decode( $default_translations);

        foreach($translations_values as $value)
        {
           $this->_translate_string($value);
        }
       $this->build();
    }

    public function transform_list($item, $index)
    {
        $config_obj = explode('~', $item);
        $item_type = trim($config_obj[0]);
        switch ($item_type)
        {
            case 'portal':
              $this->_entities['portals'][] =  $config_obj[1];
            break;

            case 'model':
                $this->_entities['models'][] =  $config_obj[1];
            break;

            case 'mapping':
                $this->_entities['mappings'][] =  $config_obj[1];
              break;

            case 'controller':
                $this->_entities['controllers'][] = $config_obj[1];
            break;

            case 'menu':
                $this->_entities['menus'][] =  $config_obj[1];
            break;

            case 'filter':
                $this->_entities['filter_fields'][] =  $config_obj[1];
            break;

            case 'header':
                $this->_entities['listing_headers'][] =  $config_obj[1];
            break;

            case 'edit':
                $this->_entities['edit_fields'][] =  $config_obj[1];
            break;

            case 'add':
                $this->_entities['add_fields'][] =  $config_obj[1];
            break;

            case 'view':
                $this->_entities['view_fields'][] =  $config_obj[1];
            break;

            case 'list':
            $this->_entities['listing_fields'][] =  $config_obj[1];
            break;

            case 'row':
                $this->_entities['rows'][] =  $config_obj[1];
            break;

            case 'roles':
                $this->_entities['roles'][] =  $config_obj[1];
            break;
        }
    }

    public function build()
    {
        $this->_build_portal_operation();
        $this->_build_models();
        $this->_build_roles();
        $this->_build_portals();
        $this->_build_controllers();
        $this->_build_configuration_file();
    }
}
