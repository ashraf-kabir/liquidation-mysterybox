<?php
include_once 'Builder.php';
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
class Controller_builder extends Builder
{
    protected $_config;
    protected $_portal = [];
    protected $_roles = [];
    protected $_controller = [];
    protected $_models = [];
    protected $_file_path = '';
    protected $_routes = [];
    protected $_menu = [];
    protected $_render_list = [];
    protected $_autocomplete_fields = [];

    public function __construct($config, $locale)
    {
        $this->_config = $config;
        $this->_template = '';
        $this->_locale = $locale;
        if(file_exists("assets/js/autocomplete.js"))
        {
            unlink("assets/js/autocomplete.js");
        }
    }

    public function get_portal()
    {
        return $this->_portal;
    }

    public function set_portal($portal)
    {
        $this->_portal = $portal;
    }

    public function get_route()
    {
        $routes = [];
        foreach ($this->_controller as $controller)
        {
            if ($controller['is_list'] && $controller['paginate'])
            {
                $routes[$controller['portal'] . $controller['route'] . '/(:num)'] = ucfirst($controller['portal']) . '/' . str_replace('.php', '', $controller['controller']) . '/index/$1';
                $routes[$controller['portal'] . $controller['route']] = ucfirst($controller['portal']) . '/' . str_replace('.php', '', $controller['controller']) . '/index/0';
            }
            if (isset($controller['autocomplete']) && strlen($controller['autocomplete']) > 0)
            {
                $routes[$controller['portal'] . $controller['route'] . '/autocomplete/(:any)'] = ucfirst($controller['portal']) . '/' . str_replace('.php', '', $controller['controller']) . '/autocomplete/$1';
            }

            if ($controller['is_list'] && !$controller['paginate'])
            {
                $routes[$controller['portal'] . $controller['route']] = ucfirst($controller['portal']) . '/' . str_replace('.php', '', $controller['controller']);
            }

            if ($controller['is_add'])
            {
                $routes[$controller['portal'] . $controller['route'] . '/add'] = ucfirst($controller['portal']) . '/' . str_replace('.php', '', $controller['controller']) . '/' . 'add';
            }

            if ($controller['is_edit'])
            {
                $routes[$controller['portal'] . $controller['route'] . '/edit/(:num)'] = ucfirst($controller['portal']) . '/' . str_replace('.php', '', $controller['controller']) . '/' . 'edit/$1';
            }

            if ($controller['is_view'])
            {
                $routes[$controller['portal'] . $controller['route'] . '/view/(:num)'] = ucfirst($controller['portal']) . '/' . str_replace('.php', '', $controller['controller']) . '/' . 'view/$1';
            }

            if ($controller['is_delete'] || $controller['is_real_delete'])
            {
                $routes[$controller['portal'] . $controller['route'] . '/delete/(:num)'] = ucfirst($controller['portal']) . '/' . str_replace('.php', '', $controller['controller']) . '/' . 'delete/$1';
            }

            if ($controller['api'] && $controller['is_list'] && $controller['paginate'])
            {
                $routes['v1/api/' . $controller['portal'] . $controller['route'] . '/(:num)'] = ucfirst($controller['portal']) . '/' . str_replace(['.php', '_controller'], ['', '_api_controller'], $controller['controller']) . '/index/$1';
                $routes['v1/api/' . $controller['portal'] . $controller['route']] = ucfirst($controller['portal']) . '/' . str_replace(['.php', '_controller'], ['', '_api_controller'], $controller['controller']) . '/index/0';
            }
            if ($controller['api'] && $controller['is_list'] && !$controller['paginate'])
            {
                $routes['v1/api/' . $controller['portal'] . $controller['route']] = ucfirst($controller['portal']) . '/' . str_replace(['.php', '_controller'], ['', '_api_controller'], $controller['controller']);
            }

            if ($controller['api'] && $controller['is_add'])
            {
                $routes['v1/api/' . $controller['portal'] . $controller['route'] . '/add'] = ucfirst($controller['portal']) . '/' . str_replace(['.php', '_controller'], ['', '_api_controller'], $controller['controller']) . '/' . 'add';
            }

            if ($controller['api'] && $controller['is_edit'])
            {
                $routes['v1/api/' . $controller['portal'] . $controller['route'] . '/edit/(:num)'] = ucfirst($controller['portal']) . '/' . str_replace(['.php', '_controller'], ['', '_api_controller'], $controller['controller']) . '/' . 'edit/$1';
            }

            if ($controller['api'] && $controller['is_view'])
            {
                $routes['v1/api/' . $controller['portal'] . $controller['route'] . '/view/(:num)'] = ucfirst($controller['portal']) . '/' . str_replace(['.php', '_controller'], ['', '_api_controller'], $controller['controller']) . '/' . 'view/$1';
            }

            if ($controller['api'] && ($controller['is_delete'] || $controller['is_real_delete']))
            {
                $routes['v1/api/' . $controller['portal'] . $controller['route'] . '/delete/(:num)'] = ucfirst($controller['portal']) . '/' . str_replace(['.php', '_controller'], ['', '_api_controller'], $controller['controller']) . '/' . 'delete/$1';
            }
            //autocomplete routes

            if(!empty($this->get_autocomplete_fields($controller, 'filter_fields')))
            {
                $fields = $this->get_autocomplete_fields($controller, 'filter_fields');
                for($i = 0; $i < count($fields); $i ++)
                {
                    $routes[$controller['portal'] .'/' .  $controller['name'] . '/search_' . $fields[$i]['field_name'] . '_' . $fields[$i]['method_type'] . '_autocomplete' ] = ucfirst($controller['portal']) . '/' . str_replace('.php', '', $controller['controller']) .'/search_'. $fields[$i]["field_name"] . '_' . $fields[$i]["method_type"] . '_auto_complete';
                }
            }


            if(!empty($this->get_autocomplete_fields($controller, 'add_fields')))
            {
                $fields = $this->get_autocomplete_fields($controller, 'add_fields');
                for($i = 0; $i < count($fields); $i ++)
                {
                    $routes[ $controller['portal'] .'/' .  $controller['name'] . '/search_' . $fields[$i]['field_name'] . '_' . $fields[$i]['method_type'] . '_autocomplete' ] = ucfirst($controller['portal']) . '/' . str_replace('.php', '', $controller['controller']) .'/search_'. $fields[$i]["field_name"] . '_' . $fields[$i]["method_type"] . '_auto_complete';
                }

            }

            if(!empty($this->get_autocomplete_fields($controller, 'edit_fields')))
            {
                $fields = $this->get_autocomplete_fields($controller, 'edit_fields');
                for($i = 0; $i < count($fields); $i ++)
                {
                    $routes[ $controller['portal'] .'/' .  $controller['name'] . '/search_' . $fields[$i]['field_name'] . '_' . $fields[$i]['method_type'] . '_autocomplete' ] = ucfirst($controller['portal']) . '/' . str_replace('.php', '', $controller['controller']) .'/search_'. $fields[$i]["field_name"] . '_' . $fields[$i]["method_type"] . '_auto_complete';
                }
            }

        }
        return $routes;
    }

    public function set_menu($menu)
    {
        $this->_menu = $menu;
    }

    public function set_controller($controllers)
    {
        $this->_controller = $controllers;
    }

    public function get_controller()
    {
        return $this->_controller;
    }

    public function set_model($models)
    {
        $this->_models = $models;
    }

    public function get_model()
    {
        return $this->_models;
    }

    public function get_role()
    {
        return $this->_roles;
    }

    public function set_role($roles)
    {
        $this->_roles = $roles;
        foreach ($this->_portal as $key => $portal)
        {
            $this->_menu[$portal['name']] = [];

            foreach ($this->_roles as $key2 => $role)
            {
                if ($portal['name'] == $role['name'])
                {
                    $this->_portal[$key]['role'] = $role['id'];
                }
            }
        }
    }

    public function build()
    {


        foreach ($this->_controller as $controller)
        {
            /**
             * 1.Is it crud? Yes load Crud Template, else Other
             * 2.Is it listing? Load listing template into listing
             * 2b.Is it paginate? Load paginate template into listing
             * 2c.Is it filter? Load filter template into listing
             * 3.Is it add? Load add template into add
             * 4.Is it edit? Load edit template into edit
             * 5.Is it delete? Load delete template into delete
             * 6.Is it view? Load view template into view
             * 7.Is it menu? Load menu template into menu
             * 8.Add to routes
             * 9.If Override, put in override
             */
            if ($controller['is_crud'])
            {
                $this->setup_crud($controller);
            }

            if ($controller['is_crud'] && $controller['api'])
            {
                $this->setup_crud_api($controller);
            }

            if (strlen($controller['override_add']) > 0)
            {
                $this->setup_override_layout($controller, 'add');
            }

            if (strlen($controller['override_edit']) > 0)
            {
                $this->setup_override_layout($controller, 'edit');
            }

            if (strlen($controller['override_view']) > 0)
            {
                 $this->setup_override_layout($controller, 'view');
            }

            if (strlen($controller['override_list']) > 0)
            {
                 $this->setup_override_list($controller);
            }
        }
    }

    private function setup_override($controller)
    {
        $uc_portal = ucfirst($controller['portal']);
        $portal = $controller['portal'];
        $uc_name = ucfirst($controller['name']);
        $template = file_get_contents($controller['override']);
        $template = $this->inject_substitute($template, 'uc_portal', $uc_portal);
        $template = $this->inject_substitute($template, 'name', $controller['name']);
        $template = $this->inject_substitute($template, 'page_name', $controller['page_name']);
        $template = $this->inject_substitute($template, 'model', $controller['model']);
        $template = $this->inject_substitute($template, 'portal', $portal);
        $template = $this->inject_substitute($template, 'uc_name', $uc_name);
        $this->_render_list['src/application/controllers/' . $uc_portal . '/' . ((strpos($controller['controller'], '.php') !== FALSE) ? $controller['controller'] : ($controller['controller'] . '.php'))] = $template;
    }

    private function setup_override_layout($controller, $type)
    {
        $uc_portal = ucfirst($controller['portal']);
        $portal = $controller['portal'];
        $uc_name = ucfirst($controller['name']);
        $template = file_get_contents($controller['override_' . $type]);
        $template = $this->inject_substitute($template, 'uc_portal', $uc_portal);
        $template = $this->inject_substitute($template, 'page_name', $controller['page_name']);
        $template = $this->inject_substitute($template, 'name', $controller['name']);
        $template = $this->inject_substitute($template, 'model', $controller['model']);
        $template = $this->inject_substitute($template, 'portal', $portal);
        $template = $this->inject_substitute($template, 'uc_name', $uc_name);
        $this->_render_list['src/application/views/' . $uc_portal . '/' . ucfirst($controller['model']) . ucfirst($type) . '.php'] = $template;
    }

    private function setup_override_layout_view_model($controller, $type)
    {
        $uc_portal = ucfirst($controller['portal']);
        $portal = $controller['portal'];
        $uc_name = ucfirst($controller['name']);
        $template = file_get_contents($controller['override_' . $type . '_view_model']);
        $template = $this->inject_substitute($template, 'uc_portal', $uc_portal);
        $template = $this->inject_substitute($template, 'page_name', $controller['page_name']);
        $template = $this->inject_substitute($template, 'name', $controller['name']);
        $template = $this->inject_substitute($template, 'model', $controller['model']);
        $template = $this->inject_substitute($template, 'portal', $portal);
        $template = $this->inject_substitute($template, 'uc_name', $uc_name);
        error_log('src/application/views/' . $uc_portal . '/' . ucfirst($controller['model']) . '_' . $controller['portal']  . '_' . $type . '_view_model.php');
        $this->_render_list['src/application/view_models/' . ucfirst($controller['model']) . '_' . $controller['portal']  . '_' . $type . '_view_model.php'] = $template;
    }

    private function setup_override_list_view_model($controller, $type)
    {
        $uc_portal = ucfirst($controller['portal']);
        $portal = $controller['portal'];
        $uc_name = ucfirst($controller['name']);
        $template = file_get_contents($controller['override_' . $type . '_view_model']);
        $template = $this->inject_substitute($template, 'uc_portal', $uc_portal);
        $template = $this->inject_substitute($template, 'page_name', $controller['page_name']);
        $template = $this->inject_substitute($template, 'name', $controller['name']);
        $template = $this->inject_substitute($template, 'model', $controller['model']);
        $template = $this->inject_substitute($template, 'portal', $portal);
        $template = $this->inject_substitute($template, 'uc_name', $uc_name);
        if ($controller['paginate'])
        {
            $path =  $controller['portal']  . '_paginate_view_model.php';
        }
        else
        {
            $path =  $controller['portal']  . '_list_view_model.php';
        }
        $this->_render_list['src/application/view_models/' . ucfirst($controller['model'])  . $path] = $template;
    }

    private function setup_override_list($controller)
    {
        $uc_portal = ucfirst($controller['portal']);
        $portal = $controller['portal'];
        $uc_name = ucfirst($controller['name']);
        $template = file_get_contents($controller['override_list']);
        $template = $this->inject_substitute($template, 'uc_portal', $uc_portal);
        $template = $this->inject_substitute($template, 'name', $controller['name']);
        $template = $this->inject_substitute($template, 'page_name', $controller['page_name']);
        $template = $this->inject_substitute($template, 'model', $controller['model']);
        $template = $this->inject_substitute($template, 'portal', $portal);
        $template = $this->inject_substitute($template, 'uc_name', $uc_name);
        $this->_render_list['src/application/views/' . $uc_portal . '/' . ucfirst($controller['model']) . '.php'] = $template;
    }

    private function setup_crud($controller)
    {
        $uc_portal = ucfirst($controller['portal']);
        $portal = $controller['portal'];
        $uc_name = ucfirst($controller['name']);

        $template = file_get_contents('templates/source/controller/controller.php');
        $template = $this->inject_substitute($template, 'controller_name', str_replace('.php', '', $controller['controller']));
        $template = $this->inject_substitute($template, 'uc_portal', $uc_portal);
        $template = $this->inject_substitute($template, 'page_name', $controller['page_name']);
        $template = $this->inject_substitute($template, 'name', $controller['name']);
        $template = $this->inject_substitute($template, 'model', $controller['model']);
        $template = $this->inject_substitute($template, 'portal', $portal);
        $template = $this->inject_substitute($template, 'uc_name', $uc_name);

        if ($controller['paginate'])
        {
            $route = $controller['route'] . '/0';
        }
        else
        {
            $route = $controller['route'];
        }

        if (isset($controller['dynamic_mapping']) && count(array_keys($controller['dynamic_mapping'])) > 0 )
        {
            $template = $this->inject_substitute($template, 'dynamic_mapping', $this->output_dynamic_mapping_controller($controller['dynamic_mapping']));
            $template = $this->inject_substitute($template, 'dynamic_mapping_load', $this->output_dynamic_mapping_controller_section($controller['dynamic_mapping']));
        }
        else
        {
            $template = $this->inject_substitute($template, 'dynamic_mapping', '');
            $template = $this->inject_substitute($template, 'dynamic_mapping_load', '');
        }


        if (isset($controller['load_libraries']) && count($controller['load_libraries']) > 0 )
        {
            $template = $this->inject_substitute($template, 'load_libraries', implode("\n", $controller['load_libraries']));
        }
        else
        {
            $template = $this->inject_substitute($template, 'load_libraries', '');
        }


        if (isset($controller['autocomplete']) && strlen($controller['autocomplete']) > 0)
        {
            $autocomplete_str = file_get_contents('templates/source/controller/controller_autocomplete.php');
            $autocomplete_str = $this->inject_substitute($autocomplete_str, 'model', $controller['model']);
            $autocomplete_str = $this->inject_substitute($autocomplete_str, 'uc_portal', $uc_portal);
            $autocomplete_str = $this->inject_substitute($autocomplete_str, 'portal', $portal);
            $autocomplete_str = $this->inject_substitute($autocomplete_str, 'page_name', $controller['page_name']);
            $autocomplete_str = $this->inject_substitute($autocomplete_str, 'uc_name', $uc_name);
            $autocomplete_str = $this->inject_substitute($autocomplete_str, 'uc_name_no_underscore', str_replace('_', ' ', $uc_name));
            $autocomplete_str = $this->inject_substitute($autocomplete_str, 'autocomplete_statement', $this->output_autocomplete_controller($controller['autocomplete'], $controller['model']));
            $autocomplete_str = $this->inject_substitute($autocomplete_str, 'name', $controller['name']);
            $template = $this->inject_substitute($template, 'autocomplete', $autocomplete_str);
        }
        else
        {
            $template = $this->inject_substitute($template, 'autocomplete', '');
        }

        if ($controller['is_add'])
        {
            $add_str = file_get_contents('templates/source/controller/controller_add.php');
            $add_str = $this->inject_substitute($add_str, 'model', $controller['model']);
            $add_str = $this->inject_substitute($add_str, 'uc_portal', $uc_portal);
            $add_str = $this->inject_substitute($add_str, 'portal', $portal);
            $add_str = $this->inject_substitute($add_str, 'page_name', $controller['page_name']);
            $add_str = $this->inject_substitute($add_str, 'uc_name', $uc_name);
            $add_str = $this->inject_substitute($add_str, 'uc_name_no_underscore', str_replace('_', ' ', $uc_name));
            $add_str = $this->inject_substitute($add_str, 'input_post_add', $this->output_post_fields($controller['add_fields']));
            $add_str = $this->inject_substitute($add_str, 'model_array_value', $this->output_model_array_value($controller['add_fields']));
            $add_str = $this->inject_substitute($add_str, 'name', $controller['name']);

            if (strlen($controller['method_add']) > 0)
            {
                $add_str = $this->inject_substitute($add_str, 'method_add', $controller['method_add']);
            }
            else
            {
                $add_str = $this->inject_substitute($add_str, 'method_add', '');
            }
            if (strlen($controller['method_add_success']) > 0)
            {
                $add_str = $this->inject_substitute($add_str, 'method_add_success', $controller['method_add_success']);
            }
            else
            {
                $add_str = $this->inject_substitute($add_str, 'method_add_success', '');
            }

            if (isset($controller['activity_log']) && $controller['activity_log'] === TRUE )
            {
                $add_str = $this->inject_substitute( $add_str, 'activity_log', $this->output_activity_log($controller, $portal, 'Add'));
            }
            else
            {
                $add_str = $this->inject_substitute( $add_str, 'activity_log', '');
            }

            if (isset($controller['dynamic_mapping_add']) && count(array_keys($controller['dynamic_mapping_add'])) > 0 )
            {
                //add field to method
                $add_str = $this->inject_substitute($add_str, 'dynamic_mapping_add', $this->output_dynamic_mapping_controller_section($controller['dynamic_mapping_add']));
                $template = $this->inject_substitute($template, 'dynamic_mapping_add', $this->output_dynamic_mapping_controller($controller['dynamic_mapping_add']));
            }
            else
            {
                $add_str = $this->inject_substitute($add_str, 'dynamic_mapping_add', '');
                $template = $this->inject_substitute($template, 'dynamic_mapping_add', '');
            }

            $template = $this->inject_substitute($template, 'add', $add_str);
            $add_view_model_str = file_get_contents('templates/source/controller/Add_view_model.php');
            $add_view_model_str = $this->inject_substitute($add_view_model_str, 'uc_name', $uc_name);
            $add_view_model_str = $this->inject_substitute($add_view_model_str, 'portal', $portal);
            $add_view_model_str = $this->inject_substitute($add_view_model_str, 'mapping', $this->output_view_model_mapping($this->make_mapping_fields($controller)));
            $this->_render_list['src/application/view_models/' . $uc_name . '_' . $portal . '_add_view_model.php'] = $add_view_model_str;
            $add_view_str = file_get_contents('templates/source/controller/Add_view.php');
            $add_view_str = $this->inject_substitute($add_view_str, 'input', $this->output_list_input_add($controller));
            $add_view_str = $this->inject_substitute($add_view_str, 'portal', $portal);
            $add_view_str = $this->inject_substitute($add_view_str, 'route', $route);
            if (strlen($controller['custom_view_add']) > 0)
            {
                $add_view_str = $this->inject_substitute($add_view_str, 'custom_view_add', $controller['custom_view_add']);
            }
            else
            {
                $add_view_str = $this->inject_substitute($add_view_str, 'custom_view_add', '');
            }
            //check autocomplete fields
            if(!empty($this->get_autocomplete_fields($controller, 'add_fields')))
            {
                $autocomplete_fields = $this->get_autocomplete_fields($controller, 'add_fields');

                for($i = 0; $i < count($autocomplete_fields); $i ++)
                {
                    $this->_autocomplete_fields[] = $autocomplete_fields[$i];
                }

            }
            $this->_render_list['src/application/views/' . $uc_portal . '/' . $uc_name . 'Add.php'] = $add_view_str;

        }
        else
        {
            $template = $this->inject_substitute($template, 'add', '');
            $template = $this->inject_substitute($template, 'dynamic_mapping_add', '');
        }

        if ($controller['is_edit'])
        {
            $edit_str = file_get_contents('templates/source/controller/controller_edit.php');
            $edit_str = $this->inject_substitute($edit_str, 'model', $controller['model']);
            $edit_str = $this->inject_substitute($edit_str, 'uc_portal', $uc_portal);
            $edit_str = $this->inject_substitute($edit_str, 'portal', $portal);
            $edit_str = $this->inject_substitute($edit_str, 'page_name', $controller['page_name']);
            $edit_str = $this->inject_substitute($edit_str, 'uc_name', $uc_name);
            $edit_str = $this->inject_substitute($edit_str, 'uc_name_no_underscore', str_replace('_', ' ', $uc_name));
            $edit_str = $this->inject_substitute($edit_str, 'name', $controller['name']);
            $edit_str = $this->inject_substitute($edit_str, 'input_post_edit', $this->output_post_fields($controller['edit_fields']));
            $edit_str = $this->inject_substitute($edit_str, 'model_array_value', $this->output_model_array_value($controller['edit_fields']));

            if (strlen($controller['method_edit']) > 0)
            {
                $edit_str = $this->inject_substitute($edit_str, 'method_edit', $controller['method_edit']);
            }
            else
            {
                $edit_str = $this->inject_substitute($edit_str, 'method_edit', '');
            }

            if (strlen($controller['method_edit_pre']) > 0)
            {
                $edit_str = $this->inject_substitute($edit_str, 'method_edit_pre', $controller['method_edit_pre']);
            }
            else
            {
                $edit_str = $this->inject_substitute($edit_str, 'method_edit_pre', '');
            }

            if (strlen($controller['method_edit_success']) > 0)
            {
                $edit_str = $this->inject_substitute($edit_str, 'method_edit_success', $controller['method_edit_success']);
            }
            else
            {
                $edit_str = $this->inject_substitute($edit_str, 'method_edit_success', '');
            }

            if (isset($controller['activity_log']) && $controller['activity_log'] === TRUE )
            {
                $edit_str = $this->inject_substitute( $edit_str, 'activity_log', $this->output_activity_log($controller,$portal, 'Edit'));
            }
            else
            {
                $edit_str = $this->inject_substitute( $edit_str, 'activity_log', '');
            }

            if (isset($controller['dynamic_mapping_edit']) && count(array_keys($controller['dynamic_mapping_edit'])) > 0 )
            {
                //add field to method
                $edit_str = $this->inject_substitute($edit_str, 'dynamic_mapping_edit', $this->output_dynamic_mapping_controller_section($controller['dynamic_mapping_edit']));
                $template = $this->inject_substitute($template, 'dynamic_mapping_edit', $this->output_dynamic_mapping_controller($controller['dynamic_mapping_edit']));
            }
            else
            {
                $edit_str = $this->inject_substitute($edit_str, 'dynamic_mapping_edit', '');
                $template = $this->inject_substitute($template, 'dynamic_mapping_edit', '');
            }
            if(!empty($this->get_autocomplete_fields($controller, 'edit_fields')))
            {
                $autocomplete_fields = $this->get_autocomplete_fields($controller, 'edit_fields');

                for($i = 0; $i < count($autocomplete_fields); $i ++)
                {
                    $this->_autocomplete_fields[] = $autocomplete_fields[$i];
                }

            }


            $template = $this->inject_substitute($template, 'edit', $edit_str);
            $edit_view_model_str = file_get_contents('templates/source/controller/Edit_view_model.php');
            $edit_view_model_str = $this->inject_substitute($edit_view_model_str, 'uc_name', $uc_name);
            $edit_view_model_str = $this->inject_substitute($edit_view_model_str, 'portal', $portal);
            $edit_view_model_str = $this->inject_substitute($edit_view_model_str, 'setter_getter_edit', $this->output_setter_getter($controller['edit_fields']));
            $edit_view_model_str = $this->inject_substitute($edit_view_model_str, 'set_model', $this->output_set_model($controller['edit_fields']));
            $edit_view_model_str = $this->inject_substitute($edit_view_model_str, 'define_field', $this->output_define_field_edit($controller['edit_fields']));
            $edit_view_model_str = $this->inject_substitute($edit_view_model_str, 'mapping', $this->output_view_model_mapping($this->make_mapping_fields($controller)));
            $this->_render_list['src/application/view_models/' . $uc_name . '_' . $portal . '_edit_view_model.php'] = $edit_view_model_str;
            $edit_view_str = file_get_contents('templates/source/controller/Edit_view.php');
            $edit_view_str = $this->inject_substitute($edit_view_str, 'input', $this->output_list_input_edit($controller));
            $edit_view_str = $this->inject_substitute($edit_view_str, 'portal', $portal);
            $edit_view_str = $this->inject_substitute($edit_view_str, 'route', $route);

            if (strlen($controller['custom_view_edit']) > 0)
            {
                $edit_view_str = $this->inject_substitute($edit_view_str, 'custom_view_edit', $controller['custom_view_edit']);
            }
            else
            {
                $edit_view_str = $this->inject_substitute($edit_view_str, 'custom_view_edit', '');
            }
            $this->_render_list['src/application/views/' . $uc_portal . '/' . $uc_name . 'Edit.php'] = $edit_view_str;
        }
        else
        {
            $template = $this->inject_substitute($template, 'edit', '');
            $template = $this->inject_substitute($template, 'dynamic_mapping_edit', '');
        }

        if ($controller['is_view'])
        {
            $view_str = file_get_contents('templates/source/controller/controller_view.php');
            $view_str = $this->inject_substitute($view_str, 'model', $controller['model']);
            $view_str = $this->inject_substitute($view_str, 'uc_name_no_underscore', str_replace('_', ' ', $uc_name));
            $view_str = $this->inject_substitute($view_str, 'all_records', $this->view_resource($controller['all_records'], $controller['active_only']));
            $view_str = $this->inject_substitute($view_str, 'uc_portal', $uc_portal);
            $view_str = $this->inject_substitute($view_str, 'page_name', $controller['page_name']);
            $view_str = $this->inject_substitute($view_str, 'portal', $portal);
            $view_str = $this->inject_substitute($view_str, 'uc_name', $uc_name);
            $view_str = $this->inject_substitute($view_str, 'name', $controller['name']);
            if (strlen($controller['method_view']) > 0)
            {
                $view_str = $this->inject_substitute($view_str, 'method_view', $controller['method_view']);
            }
            else
            {
                $view_str = $this->inject_substitute($view_str, 'method_view', '');
            }
            if (strlen($controller['method_view_success']) > 0)
            {
                $view_str = $this->inject_substitute($view_str, 'method_view_success', $controller['method_view_success']);
            }
            else
            {
                $view_str = $this->inject_substitute($view_str, 'method_view_success', '');
            }
            if (isset($controller['dynamic_mapping_view']) && count(array_keys($controller['dynamic_mapping_view'])) > 0 )
            {
                $view_str = $this->inject_substitute( $view_str, 'dynamic_mapping_view', $this->output_dynamic_mapping_controller_section($controller['dynamic_mapping_view']));
                $template = $this->inject_substitute($template, 'dynamic_mapping_view', $this->output_dynamic_mapping_controller($controller['dynamic_mapping_view']));
            }
            else
            {
                $view_str = $this->inject_substitute($view_str, 'dynamic_mapping_view', '');
                $template = $this->inject_substitute($template, 'dynamic_mapping_view', '');
            }

            $template = $this->inject_substitute($template, 'view', $view_str);
            $view_view_model_str = file_get_contents('templates/source/controller/View_view_model.php');
            $view_view_model_str = $this->inject_substitute($view_view_model_str, 'uc_name', $uc_name);
            $view_view_model_str = $this->inject_substitute($view_view_model_str, 'portal', $portal);
            $view_view_model_str = $this->inject_substitute($view_view_model_str, 'to_json', $this->output_to_json($controller['view_fields']));
            $view_view_model_str = $this->inject_substitute($view_view_model_str, 'setter_getter_edit', $this->output_setter_getter($controller['view_fields']));
            $view_view_model_str = $this->inject_substitute($view_view_model_str, 'set_model', $this->output_set_model($controller['view_fields']));
            $view_view_model_str = $this->inject_substitute($view_view_model_str, 'define_field', $this->output_define_field_edit($controller['view_fields']));
            $view_view_model_str = $this->inject_substitute($view_view_model_str, 'mapping', $this->output_view_model_mapping($this->make_mapping_fields($controller)));
            $this->_render_list['src/application/view_models/' . $uc_name . '_' . $portal . '_view_view_model.php'] = $view_view_model_str;
            $view_view_str = file_get_contents('templates/source/controller/View_view.php');
            $view_view_str = $this->inject_substitute($view_view_str, 'input', $this->output_list_input_view($controller));
            $view_view_str = $this->inject_substitute($view_view_str, 'portal', $portal);
            $view_view_str = $this->inject_substitute($view_view_str, 'route', $route);
            if (strlen($controller['custom_view_view']) > 0)
            {
                $view_view_str = $this->inject_substitute($view_view_str, 'custom_view_view', $controller['custom_view_view']);
            }
            else
            {
                $view_view_str = $this->inject_substitute($view_view_str, 'custom_view_view', '');
            }
            $this->_render_list['src/application/views/' . $uc_portal . '/' . $uc_name . 'View.php'] = $view_view_str;
        }
        else
        {
            $template = $this->inject_substitute($template, 'view', '');
            $template = $this->inject_substitute($template, 'dynamic_mapping_view', '');
        }

        if ($controller['is_real_delete'])
        {
            $delete_str = file_get_contents('templates/source/controller/controller_real_delete.php');
            $delete_str = $this->inject_substitute($delete_str, 'model', $controller['model']);
            $delete_str = $this->inject_substitute($delete_str, 'uc_portal', $uc_portal);
            $delete_str = $this->inject_substitute($delete_str, 'page_name', $controller['page_name']);
            $delete_str = $this->inject_substitute($delete_str, 'portal', $portal);
            $delete_str = $this->inject_substitute($delete_str, 'uc_name', $uc_name);
            $delete_str = $this->inject_substitute($delete_str, 'name', $controller['name']);
            if (strlen($controller['method_delete_success']) > 0)
            {
                $delete_str = $this->inject_substitute($delete_str, 'method_delete_success', $controller['method_delete_success']);
            }
            else
            {
                $delete_str = $this->inject_substitute($delete_str, 'method_delete_success', '');
            }
            $template = $this->inject_substitute($template, 'delete', $delete_str);
        }
        else
        {
            $template = $this->inject_substitute($template, 'delete', '');
        }

        if ($controller['is_delete'])
        {
            $delete_str = file_get_contents('templates/source/controller/controller_delete.php');
            $delete_str = $this->inject_substitute($delete_str, 'model', $controller['model']);
            $delete_str = $this->inject_substitute($delete_str, 'uc_portal', $uc_portal);
            $delete_str = $this->inject_substitute($delete_str, 'page_name', $controller['page_name']);
            $delete_str = $this->inject_substitute($delete_str, 'portal', $portal);
            $delete_str = $this->inject_substitute($delete_str, 'uc_name', $uc_name);
            $delete_str = $this->inject_substitute($delete_str, 'name', $controller['name']);
            if (strlen($controller['method_delete_success']) > 0)
            {
                $delete_str = $this->inject_substitute($delete_str, 'method_delete_success', $controller['method_delete_success']);
            }
            else
            {
                $delete_str = $this->inject_substitute($delete_str, 'method_delete_success', '');
            }
            $template = $this->inject_substitute($template, 'delete', $delete_str);
        }
        else
        {
            $template = $this->inject_substitute($template, 'delete', '');
        }

        if ($controller['is_list'])
        {
            if ($controller['paginate'])
            {
                if ($controller['is_filter'])
                {
                    $list_str = file_get_contents('templates/source/controller/controller_list_paginate_filter.php');
                    $list_str = $this->inject_substitute($list_str, 'model', $controller['model']);
                    $list_str = $this->inject_substitute($list_str, 'uc_portal', $uc_portal);
                    $list_str = $this->inject_substitute($list_str, 'portal', $portal);
                    $list_str = $this->inject_substitute($list_str, 'uc_name', $uc_name);
                    $list_str = $this->inject_substitute($list_str, 'page_name', $controller['page_name']);
                    $list_str = $this->inject_substitute($list_str, 'name', $controller['name']);
                    $list_str = $this->inject_substitute($list_str, 'list_paginate_filter_post', $this->output_paginate_filter_post($controller['filter_fields']));
                    $list_str = $this->inject_substitute($list_str, 'list_paginate_filter_where', $this->output_paginate_filter_where($controller['filter_fields'], $controller['all_records'], $controller['active_only']));

                    if ($controller['is_add'])
                    {
                        $list_str = $this->inject_substitute($list_str, 'add', $this->output_add_button($controller));
                    }
                    else
                    {
                        $list_str = $this->inject_substitute($list_str, 'add', '');
                    }

                    if (strlen($controller['method_list']) > 0)
                    {
                        $list_str = $this->inject_substitute($list_str, 'method_list', $controller['method_list']);
                    }
                    else
                    {
                        $list_str = $this->inject_substitute($list_str, 'method_list', '');
                    }

                    if (strlen($controller['paginate_join']) > 0)
                    {
                        $list_str = $this->inject_substitute($list_str, 'paginate', $controller['paginate_join']);
                    }
                    else
                    {
                        $list_str = $this->inject_substitute($list_str, 'paginate', 'get_paginated');
                    }

                    $template = $this->inject_substitute($template, 'listing', $list_str);
                    $list_view_str = file_get_contents('templates/source/controller/List_paginate_filter_view.php');
                    if (strlen($controller['custom_view_list']) > 0)
                    {
                        $list_view_str = $this->inject_substitute($list_view_str, 'row', $controller['custom_view_list']);
                    }
                    else
                    {
                        $list_view_str = $this->inject_substitute($list_view_str, 'row', $this->output_list_rows_raw($controller['listing_rows'], $controller, $this->make_mapping_fields($controller)));
                    }

                    if ($controller['is_add'])
                    {
                        $list_view_str = $this->inject_substitute($list_view_str, 'add', $this->output_add_button($controller));
                    }
                    else
                    {
                        $list_view_str = $this->inject_substitute($list_view_str, 'add', '');
                    }

                    if ($controller['import'])
                    {
                        $list_view_str = $this->inject_substitute($list_view_str, 'import', $this->import($controller['model']));
                    }
                    else
                    {
                        $list_view_str = $this->inject_substitute($list_view_str, 'import', '');
                    }
                     //check autocomplete fields
                    if(!empty($this->get_autocomplete_fields($controller, 'filter_fields')))
                    {
                        $autocomplete_fields = $this->get_autocomplete_fields($controller, 'filter_fields');

                        for($i = 0; $i < count($autocomplete_fields); $i ++)
                        {
                            $this->_autocomplete_fields[] = $autocomplete_fields[$i];
                        }

                    }
                    $list_view_str = $this->inject_substitute($list_view_str, 'portal', $portal);
                    $list_view_str = $this->inject_substitute($list_view_str, 'name', str_replace(' ', '', $controller['name']));
                    $list_view_str = $this->inject_substitute($list_view_str, 'route', $route);
                    $list_view_str = $this->inject_substitute($list_view_str, 'url', 'a');
                    $list_view_str = $this->inject_substitute($list_view_str, 'filter', $this->output_list_filter($controller));
                    $this->_render_list['src/application/views/' . $uc_portal . '/' . $uc_name . '.php'] = $list_view_str;
                }
                else
                {
                    $list_str = file_get_contents('templates/source/controller/controller_list_paginate.php');
                    $list_str = $this->inject_substitute($list_str, 'model', $controller['model']);
                    $list_str = $this->inject_substitute($list_str, 'uc_portal', $uc_portal);
                    $list_str = $this->inject_substitute($list_str, 'portal', $portal);
                    $list_str = $this->inject_substitute($list_str, 'page_name', $controller['page_name']);
                    $list_str = $this->inject_substitute($list_str, 'uc_name', $uc_name);
                    $list_str = $this->inject_substitute($list_str, 'name', $controller['name']);

                    if (!$controller['all_records'])
                    {
                        if ($controller['active_only'])
                        {
                            $list_str = $this->inject_substitute($list_str, 'all_records', "'user_id' => \$session['user_id'], 'status' => 1");
                        }
                        else
                        {
                            $list_str = $this->inject_substitute($list_str, 'all_records', "'user_id' => \$session['user_id']");
                        }
                    }
                    else
                    {
                        if ($controller['active_only'])
                        {
                            $list_str = $this->inject_substitute($list_str, 'all_records', "'status' => 1");
                        }
                        else
                        {
                            $list_str = $this->inject_substitute($list_str, 'all_records', '');
                        }
                    }

                    if (strlen($controller['paginate_join']) > 0)
                    {
                        $list_str = $this->inject_substitute($list_str, 'paginate', $controller['paginate_join']);
                    }
                    else
                    {
                        $list_str = $this->inject_substitute($list_str, 'paginate', 'get_paginated');
                    }

                    if (strlen($controller['method_list']) > 0)
                    {
                        $list_str = $this->inject_substitute($list_str, 'method_list', $controller['method_list']);
                    }
                    else
                    {
                        $list_str = $this->inject_substitute($list_str, 'method_list', '');
                    }

                    $template = $this->inject_substitute($template, 'listing', $list_str);
                    $list_view_str = file_get_contents('templates/source/controller/List_paginate_view.php');
                    $list_view_str = $this->inject_substitute($list_view_str, 'name', str_replace(' ', '', $controller['name']));
                    $list_view_str = $this->inject_substitute($list_view_str, 'row', $this->output_list_rows_raw($controller['listing_rows'], $controller, $this->make_mapping_fields($controller)));
                    if ($controller['is_add'])
                    {
                        $list_view_str = $this->inject_substitute($list_view_str, 'add', $this->output_add_button($controller));
                    }
                    else
                    {
                        $list_view_str = $this->inject_substitute($list_view_str, 'add', '');
                    }

                    if ($controller['import'])
                    {
                        $list_view_str = $this->inject_substitute($list_view_str, 'import', $this->import($controller['model']));
                    }
                    else
                    {
                        $list_view_str = $this->inject_substitute($list_view_str, 'import', '');
                    }

                    $this->_render_list['src/application/views/' . $uc_portal . '/' . $uc_name . '.php'] = $list_view_str;
                }
                $list_view_model_str = file_get_contents('templates/source/controller/List_paginate_view_model.php');
                $list_view_model_str = $this->inject_substitute($list_view_model_str, 'uc_name', $uc_name);
                $list_view_model_str = $this->inject_substitute($list_view_model_str, 'portal', $portal);
                $list_view_model_str = $this->inject_substitute($list_view_model_str, 'to_json', $this->output_list_to_json($controller['listing_fields_api'], $this->make_mapping_fields($controller)));
                $list_view_model_str = $this->inject_substitute($list_view_model_str, 'column', $this->output_view_model_column_raw($controller['listing_headers']));
                $list_view_model_str = $this->inject_substitute($list_view_model_str, 'field_column', $this->output_view_model_field_column($controller['listing_rows']));
                $list_view_model_str = $this->inject_substitute($list_view_model_str, 'mapping', $this->output_view_model_mapping($this->make_mapping_fields($controller)));
                $list_view_model_str = $this->inject_substitute($list_view_model_str, 'filter_fields', $this->output_filter_field($controller['filter_fields']));
                $this->_render_list['src/application/view_models/' . $uc_name . '_' . $portal . '_list_paginate_view_model.php'] = $list_view_model_str;
            }
            else
            {
                $list_str = file_get_contents('templates/source/controller/controller_list.php');
                $list_str = $this->inject_substitute($list_str, 'model', $controller['model']);
                $list_str = $this->inject_substitute($list_str, 'uc_portal', $uc_portal);
                $list_str = $this->inject_substitute($list_str, 'portal', $portal);
                $list_str = $this->inject_substitute($list_str, 'page_name', $controller['page_name']);
                $list_str = $this->inject_substitute($list_str, 'uc_name', $uc_name);
                $list_str = $this->inject_substitute($list_str, 'name', $controller['name']);
                if (strlen($controller['method_list']) > 0)
                {
                    $list_str = $this->inject_substitute($list_str, 'method_list', $controller['method_list']);
                }
                else
                {
                    $list_str = $this->inject_substitute($list_str, 'method_list', '');
                }

                if (!$controller['all_records'])
                {
                    if ($controller['active_only'])
                    {
                        $list_str = $this->inject_substitute($list_str, 'all_records', "['user_id' => \$session['user_id'], 'status' => 1]");
                    }
                    else
                    {
                        $list_str = $this->inject_substitute($list_str, 'all_records', "['user_id' => \$session['user_id']]");
                    }
                }
                else
                {
                    if ($controller['active_only'])
                    {
                        $list_str = $this->inject_substitute($list_str, 'all_records', "['status' => 1]");
                    }
                    else
                    {
                        $list_str = $this->inject_substitute($list_str, 'all_records', '');
                    }
                }
                $template = $this->inject_substitute($template, 'listing', $list_str);
                $list_view_model_str = file_get_contents('templates/source/controller/List_view_model.php');
                $list_view_model_str = $this->inject_substitute($list_view_model_str, 'uc_name', $uc_name);
                $list_view_model_str = $this->inject_substitute($list_view_model_str, 'portal', $portal);
                $list_view_model_str = $this->inject_substitute($list_view_model_str, 'to_json', $this->output_list_to_json_single($controller['listing_fields_api'], $this->make_mapping_fields($controller)));
                $list_view_model_str = $this->inject_substitute($list_view_model_str, 'mapping', $this->output_view_model_mapping($this->make_mapping_fields($controller)));
                $list_view_model_str = $this->inject_substitute($list_view_model_str, 'column', $this->output_view_model_column_raw($controller['listing_headers']));
                $this->_render_list['src/application/view_models/' . $uc_name . '_' . $portal . '_list_view_model.php'] = $list_view_model_str;
                $list_view_str = file_get_contents('templates/source/controller/List_view.php');
                $list_view_str = $this->inject_substitute($list_view_str, 'name', str_replace(' ', '', $controller['name']));
                $list_view_str = $this->inject_substitute($list_view_str, 'row', $this->output_list_rows_raw($controller['listing_rows'], $controller, $this->make_mapping_fields($controller)));

                if ($controller['is_add'])
                {
                    $list_view_str = $this->inject_substitute($list_view_str, 'add', $this->output_add_button($controller));
                }
                else
                {
                    $list_view_str = $this->inject_substitute($list_view_str, 'add', '');
                }

                if ($controller['import'])
                {
                    $list_view_str = $this->inject_substitute($list_view_str, 'import', $this->import($controller['model']));
                }
                else
                {
                    $list_view_str = $this->inject_substitute($list_view_str, 'import', '');
                }

                $this->_render_list['src/application/views/' . $uc_portal . '/' . $uc_name . '.php'] = $list_view_str;
            }
        }
        else
        {
            $template = $this->inject_substitute($template, 'listing', '');
        }

        if ($controller['paginate'])
        {
            $template = $this->inject_substitute($template, 'route', $controller['route'] . '/0');
        }
        else
        {
            $template = $this->inject_substitute($template, 'route', $controller['route']);
        }
        if (strlen($controller['method']) > 0)
        {
            $template = $this->inject_substitute($template, 'method', $controller['method']);
        }
        else
        {
            $template = $this->inject_substitute($template, 'method', '');
        }
        //generate autocomplete
        if(!empty($this->_autocomplete_fields))
        {
            $template = $this->inject_substitute($template, 'autocomplete_methods', $this->get_autocomplete_controller_methods($this->_autocomplete_fields));
            $js_template = $this->generate_autocomplete_js($controller, $this->_autocomplete_fields);
            file_put_contents('assets/js/autocomplete.js', $js_template, FILE_APPEND);
            $this->_autocomplete_fields = [];
        }
        else
        {
            $template = $this->inject_substitute($template, 'autocomplete_methods', '');
        }
        $this->_render_list['src/application/controllers/' . $uc_portal . '/' . ((strpos($controller['controller'], '.php') !== FALSE) ? $controller['controller'] : ($controller['controller'] . '.php'))] = $template;
    }

    private function setup_crud_api($controller)
    {
        $uc_portal = ucfirst($controller['portal']);
        $portal = $controller['portal'];
        $uc_name = ucfirst($controller['name']);

        $api_controller_name = str_replace('_controller', '_api_controller', $controller['controller']);

        $template = file_get_contents('templates/source/controller/api_controller.php');
        $template = $this->inject_substitute($template, 'uc_portal', $uc_portal);
        $template = $this->inject_substitute($template, 'api_controller', str_replace('.php', '', $api_controller_name));
        $template = $this->inject_substitute($template, 'page_name', $controller['page_name']);
        $template = $this->inject_substitute($template, 'name', $controller['name']);
        $template = $this->inject_substitute($template, 'model', $controller['model']);
        $template = $this->inject_substitute($template, 'portal', $portal);
        $template = $this->inject_substitute($template, 'uc_name', $uc_name);

        if ($controller['paginate'])
        {
            $route = $controller['route'] . '/0';
        }
        else
        {
            $route = $controller['route'];
        }

        if ($controller['is_add'])
        {
            $add_str = file_get_contents('templates/source/controller/controller_api_add.php');
            $add_str = $this->inject_substitute($add_str, 'model', $controller['model']);
            $add_str = $this->inject_substitute($add_str, 'uc_portal', $uc_portal);
            $add_str = $this->inject_substitute($add_str, 'portal', $portal);
            $add_str = $this->inject_substitute($add_str, 'uc_name', $uc_name);
            $add_str = $this->inject_substitute($add_str, 'input_post_add', $this->output_post_fields($controller['add_fields']));
            $add_str = $this->inject_substitute($add_str, 'model_array_value', $this->output_model_array_value($controller['add_fields']));
            $add_str = $this->inject_substitute($add_str, 'name', $controller['name']);
            if (strlen($controller['method_add']) > 0)
            {
                $add_str = $this->inject_substitute($add_str, 'method_add', $controller['method_add']);
            }
            else
            {
                $add_str = $this->inject_substitute($add_str, 'method_add', '');
            }
            if (strlen($controller['method_add_success']) > 0)
            {
                $add_str = $this->inject_substitute($add_str, 'method_add_success', $controller['method_add_success']);
            }
            else
            {
                $add_str = $this->inject_substitute($add_str, 'method_add_success', '');
            }
            if (isset($controller['activity_log']) && $controller['activity_log'] === TRUE )
            {
                $add_str = $this->inject_substitute( $add_str, 'activity_log', $this->output_activity_log($controller,$portal, 'Add'));
            }
            else
            {
                $add_str = $this->inject_substitute( $add_str, 'activity_log', '');
            }

            if (isset($controller['dynamic_mapping_add']) && count(array_keys($controller['dynamic_mapping_add'])) > 0 )
            {
                $add_str = $this->inject_substitute( $add_str, 'dynamic_mapping_add', $this->output_dynamic_mapping_controller_section($controller['dynamic_mapping_add']));
                $template = $this->inject_substitute($template, 'dynamic_mapping_add', $this->output_dynamic_mapping_controller($controller['dynamic_mapping_add']));
            }
            else
            {
                $add_str = $this->inject_substitute($add_str, 'dynamic_mapping_add', '');
                $template = $this->inject_substitute($template, 'dynamic_mapping_add', '');
            }

            $template = $this->inject_substitute($template, 'add', $add_str);
        }
        else
        {
            $template = $this->inject_substitute($template, 'add', '');
        }

        if ($controller['is_edit'])
        {
            $edit_str = file_get_contents('templates/source/controller/controller_api_edit.php');
            $edit_str = $this->inject_substitute($edit_str, 'model', $controller['model']);
            $edit_str = $this->inject_substitute($edit_str, 'uc_portal', $uc_portal);
            $edit_str = $this->inject_substitute($edit_str, 'portal', $portal);
            $edit_str = $this->inject_substitute($edit_str, 'uc_name', $uc_name);
            $edit_str = $this->inject_substitute($edit_str, 'name', $controller['name']);
            $edit_str = $this->inject_substitute($edit_str, 'input_post_edit', $this->output_post_fields($controller['edit_fields']));
            $edit_str = $this->inject_substitute($edit_str, 'model_array_value', $this->output_model_array_value($controller['edit_fields']));
            if (strlen($controller['method_edit']) > 0)
            {
                $edit_str = $this->inject_substitute($edit_str, 'method_edit', $controller['method_edit']);
            }
            else
            {
                $edit_str = $this->inject_substitute($edit_str, 'method_edit', '');
            }
            if (strlen($controller['method_edit_success']) > 0)
            {
                $edit_str = $this->inject_substitute($edit_str, 'method_edit_success', $controller['method_edit_success']);
            }
            else
            {
                $edit_str = $this->inject_substitute($edit_str, 'method_edit_success', '');
            }

            if (isset($controller['activity_log']) && $controller['activity_log'] === TRUE )
            {
                $edit_str = $this->inject_substitute( $edit_str, 'activity_log', $this->output_activity_log($controller,$portal, 'Edit'));
            }
            else
            {
                $edit_str = $this->inject_substitute( $edit_str, 'activity_log', '');
            }

            if (isset($controller['dynamic_mapping_edit']) && count(array_keys($controller['dynamic_mapping_edit'])) > 0 )
            {
                $edit_str= $this->inject_substitute( $edit_str, 'dynamic_mapping_edit', $this->output_dynamic_mapping_controller_section($controller['dynamic_mapping_edit']));
                $template = $this->inject_substitute($template, 'dynamic_mapping_edit', $this->output_dynamic_mapping_controller($controller['dynamic_mapping_edit']));
            }
            else
            {
                $edit_str = $this->inject_substitute($edit_str, 'dynamic_mapping_edit', '');
                $template = $this->inject_substitute($template, 'dynamic_mapping_edit', '');
            }

            $template = $this->inject_substitute($template, 'edit', $edit_str);
        }
        else
        {
            $template = $this->inject_substitute($template, 'edit', '');
        }

        if ($controller['is_view'])
        {
            $view_str = file_get_contents('templates/source/controller/controller_api_view.php');
            $view_str = $this->inject_substitute($view_str, 'model', $controller['model']);
            $view_str = $this->inject_substitute($view_str, 'all_records', $this->view_resource($controller['all_records'], $controller['active_only']));
            $view_str = $this->inject_substitute($view_str, 'uc_portal', $uc_portal);
            $view_str = $this->inject_substitute($view_str, 'portal', $portal);
            $view_str = $this->inject_substitute($view_str, 'uc_name', $uc_name);
            $view_str = $this->inject_substitute($view_str, 'name', $controller['name']);
            if (strlen($controller['method_view']) > 0)
            {
                $view_str = $this->inject_substitute($view_str, 'method_view', $controller['method_view']);
            }
            else
            {
                $view_str = $this->inject_substitute($view_str, 'method_view', '');
            }
            if (strlen($controller['method_view_success']) > 0)
            {
                $view_str = $this->inject_substitute($view_str, 'method_view_success', $controller['method_view_success']);
            }
            else
            {
                $view_str = $this->inject_substitute($view_str, 'method_view_success', '');
            }

            if (isset($controller['dynamic_mapping_view']) && count(array_keys($controller['dynamic_mapping_view'])) > 0 )
            {
                $view_str = $this->inject_substitute( $view_str , 'dynamic_mapping_view', $this->output_dynamic_mapping_controller_section($controller['dynamic_mapping_view']));
                $template = $this->inject_substitute($template, 'dynamic_mapping_view', $this->output_dynamic_mapping_controller($controller['dynamic_mapping_view']));
            }
            else
            {
                $view_str = $this->inject_substitute($view_str, 'dynamic_mapping_view', '');
                $template = $this->inject_substitute($template, 'dynamic_mapping_view', '');
            }

            $template = $this->inject_substitute($template, 'view', $view_str);
        }
        else
        {
            $template = $this->inject_substitute($template, 'view', '');
        }

        if ($controller['is_real_delete'])
        {
            $delete_str = file_get_contents('templates/source/controller/controller_api_real_delete.php');
            $delete_str = $this->inject_substitute($delete_str, 'model', $controller['model']);
            $delete_str = $this->inject_substitute($delete_str, 'uc_portal', $uc_portal);
            $delete_str = $this->inject_substitute($delete_str, 'portal', $portal);
            $delete_str = $this->inject_substitute($delete_str, 'uc_name', $uc_name);
            $delete_str = $this->inject_substitute($delete_str, 'name', $controller['name']);
            if (strlen($controller['method_delete_success']) > 0)
            {
                $delete_str = $this->inject_substitute($delete_str, 'method_delete_success', $controller['method_delete_success']);
            }
            else
            {
                $delete_str = $this->inject_substitute($delete_str, 'method_delete_success', '');
            }
            $template = $this->inject_substitute($template, 'delete', $delete_str);
        }
        else
        {
            $template = $this->inject_substitute($template, 'delete', '');
        }

        if ($controller['is_delete'])
        {
            $delete_str = file_get_contents('templates/source/controller/controller_api_delete.php');
            $delete_str = $this->inject_substitute($delete_str, 'model', $controller['model']);
            $delete_str = $this->inject_substitute($delete_str, 'uc_portal', $uc_portal);
            $delete_str = $this->inject_substitute($delete_str, 'portal', $portal);
            $delete_str = $this->inject_substitute($delete_str, 'uc_name', $uc_name);
            $delete_str = $this->inject_substitute($delete_str, 'name', $controller['name']);
            if (strlen($controller['method_delete_success']) > 0)
            {
                $delete_str = $this->inject_substitute($delete_str, 'method_delete_success', $controller['method_delete_success']);
            }
            else
            {
                $delete_str = $this->inject_substitute($delete_str, 'method_delete_success', '');
            }
            $template = $this->inject_substitute($template, 'delete', $delete_str);
        }
        else
        {
            $template = $this->inject_substitute($template, 'delete', '');
        }

        if ($controller['is_list'])
        {
            if ($controller['paginate'])
            {
                if ($controller['is_filter'])
                {
                    $list_str = file_get_contents('templates/source/controller/controller_api_list_paginate_filter.php');
                    $list_str = $this->inject_substitute($list_str, 'model', $controller['model']);
                    $list_str = $this->inject_substitute($list_str, 'uc_portal', $uc_portal);
                    $list_str = $this->inject_substitute($list_str, 'portal', $portal);
                    $list_str = $this->inject_substitute($list_str, 'uc_name', $uc_name);
                    $list_str = $this->inject_substitute($list_str, 'name', $controller['name']);
                    $list_str = $this->inject_substitute($list_str, 'list_paginate_filter_post', $this->output_paginate_filter_post($controller['filter_fields']));
                    $list_str = $this->inject_substitute($list_str, 'list_paginate_filter_where', $this->output_paginate_filter_where($controller['filter_fields'], $controller['all_records'], $controller['active_only']));
                    if (strlen($controller['method_list']) > 0)
                    {
                        $list_str = $this->inject_substitute($list_str, 'method_list', $controller['method_list']);
                    }
                    else
                    {
                        $list_str = $this->inject_substitute($list_str, 'method_list', '');
                    }
                    if (strlen($controller['paginate_join']) > 0)
                    {
                        $list_str = $this->inject_substitute($list_str, 'paginate', $controller['paginate_join']);
                    }
                    else
                    {
                        $list_str = $this->inject_substitute($list_str, 'paginate', 'get_paginated');
                    }
                    $template = $this->inject_substitute($template, 'listing', $list_str);
                }
                else
                {
                    $list_str = file_get_contents('templates/source/controller/controller_api_list_paginate.php');
                    $list_str = $this->inject_substitute($list_str, 'model', $controller['model']);
                    $list_str = $this->inject_substitute($list_str, 'uc_portal', $uc_portal);
                    $list_str = $this->inject_substitute($list_str, 'portal', $portal);
                    $list_str = $this->inject_substitute($list_str, 'uc_name', $uc_name);
                    $list_str = $this->inject_substitute($list_str, 'name', $controller['name']);
                    if (strlen($controller['method_list']) > 0)
                    {
                        $list_str = $this->inject_substitute($list_str, 'method_list', $controller['method_list']);
                    }
                    else
                    {
                        $list_str = $this->inject_substitute($list_str, 'method_list', '');
                    }

                    if (strlen($controller['paginate_join']) > 0)
                    {
                        $list_str = $this->inject_substitute($list_str, 'paginate', $controller['paginate_join']);
                    }
                    else
                    {
                        $list_str = $this->inject_substitute($list_str, 'paginate', 'get_paginated');
                    }

                    if (!$controller['all_records'])
                    {
                        $list_str = $this->inject_substitute($list_str, 'all_records', "'user_id' => \$session['user_id']");
                    }
                    else
                    {
                        $list_str = $this->inject_substitute($list_str, 'all_records', '');
                    }

                    $template = $this->inject_substitute($template, 'listing', $list_str);
                }
            }
            else
            {
                $list_str = file_get_contents('templates/source/controller/controller_api_list.php');
                $list_str = $this->inject_substitute($list_str, 'model', $controller['model']);
                $list_str = $this->inject_substitute($list_str, 'uc_portal', $uc_portal);
                $list_str = $this->inject_substitute($list_str, 'portal', $portal);
                $list_str = $this->inject_substitute($list_str, 'uc_name', $uc_name);
                $list_str = $this->inject_substitute($list_str, 'name', $controller['name']);
                if (strlen($controller['method_list']) > 0)
                {
                    $list_str = $this->inject_substitute($list_str, 'method_list', $controller['method_list']);
                }
                else
                {
                    $list_str = $this->inject_substitute($list_str, 'method_list', '');
                }

                if (!$controller['all_records'])
                {

                    $list_str = $this->inject_substitute($list_str, 'all_records', "['user_id' => \$session['user_id']]");
                }
                else
                {
                    $list_str = $this->inject_substitute($list_str, 'all_records', '');
                }
                $template = $this->inject_substitute($template, 'listing', $list_str);
            }
        }
        else
        {
            $template = $this->inject_substitute($template, 'listing', '');
        }

        if ($controller['paginate'])
        {
            $template = $this->inject_substitute($template, 'route', $controller['route'] . '/0');
        }
        else
        {
            $template = $this->inject_substitute($template, 'route', $controller['route']);
        }
        $api_controller_name = str_replace('.php', '', $api_controller_name);
        $this->_render_list['src/application/controllers/' . $uc_portal . '/' . $api_controller_name . '.php'] = $template;
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

    /**
     * Steps:
     * 1.Field are taken
     * 2.Check model fields and pull them into a list
     * 3.Return this list
     *
     * @param [type] $controller
     * @param [type] $field_type
     * @return void
     */
    protected function make_input_fields($controller, $field_type)
    {
        $fields = $controller[$field_type];
        $clean_list = [];
        $model_fields = [];
        foreach ($this->_models as $single_model)
        {
            if ($single_model['name'] == $controller['model'])
            {
                $model_fields = $single_model['field'];
            }
        }
        foreach ($fields as $field)
        {
            foreach ($model_fields as $model_field)
            {
                if ($model_field[0] == $field)
                {
                    $clean_list[] = $model_field;
                }

            }

            if ($field === 'created_at')
            {
                $clean_list[] = ['created_at', 'date', [], 'xyzCreated At', '', ''];
            }
            if ($field === 'updated_at')
            {
                $clean_list[] = ['updated_at', 'datetime', [], 'xyzUpdated At', '', ''];
            }
        }

        return $clean_list;
    }

    protected function make_mapping_fields($controller)
    {
        $mapping = [];
        $model = NULL;
        foreach ($this->_models as $single_model)
        {
            if ($single_model['name'] == $controller['model'])
            {
                $model = $single_model;
            }
        }

        if ($model['mapping'])
        {
            $mapping = $model['mapping'];
        }

        return $mapping;
    }

    protected function output_post_fields ($fields)
    {
        $result = '';

        foreach ($fields as $field)
        {
            $field = explode("|", $field);
            $field= $field[0];
            $result .= "\${$field} = \$this->input->post('{$field}');\n\t\t";

            if (strpos($field, 'image') !== FALSE)
            {
                $result .= "\${$field}_id = \$this->input->post('{$field}_id');\n\t\t";
            }
            if (strpos($field, 'file') !== FALSE)
            {
                $result .= "\${$field}_id = \$this->input->post('{$field}_id');\n\t\t";
            }
        }
        return $result;
    }

    protected function output_setter_getter ($fields)
    {
        $result = '';

        if (!in_array('id', $fields))
        {
            $fields[] = 'id';
        }

        foreach ($fields as $field)
        {
            $result .= "\n\tpublic function get_{$field} ()\n\t{\n\t\treturn \$this->_{$field};\n\t}\n\n";
            $result .= "\tpublic function set_{$field} (\${$field})\n\t{\n\t\t\$this->_{$field} = \${$field};\n\t}\n";

            if (strpos($field, 'image') !== FALSE)
            {
                $result .= "\n\tpublic function get_{$field}_id ()\n\t{\n\t\treturn \$this->_{$field}_id;\n\t}\n\n";
                $result .= "\tpublic function set_{$field}_id (\${$field})\n\t{\n\t\t\$this->_{$field}_id = \${$field};\n\t}\n";
            }

            if (strpos($field, 'file') !== FALSE)
            {
                $result .= "\n\tpublic function get_{$field}_id ()\n\t{\n\t\treturn \$this->_{$field}_id;\n\t}\n\n";
                $result .= "\tpublic function set_{$field}_id (\${$field})\n\t{\n\t\t\$this->_{$field}_id = \${$field};\n\t}\n";
            }
        }
        return $result;
    }

    protected function output_filter_field ($fields)
    {
        $result = '';

        if (!in_array('id', $fields))
        {
            $fields[] = 'id';
        }

        foreach ($fields as $field)
        {
            $field = explode("|", $field);
            $field= $field[0];
            $result .= "\n\tpublic function get_{$field} ()\n\t{\n\t\treturn \$this->_{$field};\n\t}\n\n";
            $result .= "\tpublic function set_{$field} (\${$field})\n\t{\n\t\t\$this->_{$field} = \${$field};\n\t}\n";
        }
        return $result;
    }

    protected function output_to_json ($fields)
    {
        $result = "\n\tpublic function to_json ()\n\t{\n";
        $result .= "\t\treturn [\n";
        foreach ($fields as $field)
        {
            $result .= "\t\t'{$field}' => \$this->get_{$field}(),\n";
        }
        $result .= "\t\t];\n\t";
        $result .= "}\n";
        return $result;
    }

    protected function output_list_to_json ($listing_fields, $mapping)
    {
        $result = "\n\tpublic function to_json ()\n\t{\n";
        $result .= "\t\t\$list = \$this->get_list();\n\n";
        $result .= "\t\t\$clean_list = [];\n\n";
        $result .= "\t\tforeach (\$list as \$key => \$value)\n";
        $result .= "\t\t{\n";

        foreach ($mapping as $mapping_key => $mapping_value)
        {
            $result .= "\t\t\t\$list[\$key]->{$mapping_key} = \$this->{$mapping_key}_mapping()[\$value->{$mapping_key}];\n";
        }
        $result .= "\t\t\t\$clean_list_entry = [];\n";

        foreach ($listing_fields as $list_key)
        {
            $result .= "\t\t\t\$clean_list_entry['{$list_key}'] = \$list[\$key]->{$list_key};\n";
        }

        $result .= "\t\t\t\$clean_list[] = \$clean_list_entry;\n";

        $result .= "\t\t}\n\n";

        $result .= "\t\treturn [\n";
        $result .= "\t\t\t'page' => \$this->get_page(),\n";
        $result .= "\t\t\t'num_page' => \$this->get_num_page(),\n";
        $result .= "\t\t\t'num_item' => \$this->get_total_rows(),\n";
        $result .= "\t\t\t'item' => \$clean_list\n";
        $result .= "\t\t];\n\t";
        $result .= "}\n";

        return $result;
    }

    protected function output_list_to_json_single ($listing_fields, $mapping)
    {
        $result = "\n\tpublic function to_json ()\n\t{\n";
        $result .= "\t\t\$list = \$this->get_list();\n\n";
        $result .= "\t\t\$clean_list = [];\n\n";
        $result .= "\t\tforeach (\$list as \$key => \$value)\n";
        $result .= "\t\t{\n";

        foreach ($mapping as $mapping_key => $mapping_value)
        {
            $result .= "\t\t\t\$list[\$key]->{$mapping_key} = \$this->{$mapping_key}_mapping()[\$value->{$mapping_key}];\n";
        }
        $result .= "\t\t\t\$clean_list_entry = [];\n";

        foreach ($listing_fields as $list_key)
        {
            $result .= "\t\t\t\$clean_list_entry['{$list_key}'] = \$list[\$key]->{$list_key};\n";
        }

        $result .= "\t\t\t\$clean_list[] = \$clean_list_entry;\n";

        $result .= "\t\t}\n\n";

        $result .= "\t\treturn [\n";
        $result .= "\t\t\t'page' => 1,\n";
        $result .= "\t\t\t'num_page' => 1,\n";
        $result .= "\t\t\t'num_item' => count(\$clean_list),\n";
        $result .= "\t\t\t'item' => \$clean_list\n";
        $result .= "\t\t];\n\t";
        $result .= "}\n";

        return $result;
    }

    protected function output_set_model ($fields)
    {
        $result = '';

        if (!in_array('id', $fields))
        {
            $result = "\t\t\$this->_id = \$model->id;\n";
        }
        foreach ($fields as $field)
        {
            $result .= "\t\t\$this->_{$field} = \$model->{$field};\n";
        }
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
                $result .= "\n\t\treturn \$this->_entity->{$mapping_key}_mapping();\n";
            }
            $result .= "\n\t}\n";
        }
        return $result;
    }

    protected function output_define_field_edit ($fields)
    {
        $result = '';

        if (!in_array('id', $fields))
        {
            $result = "\tprotected \$_id;\n";
        }

        foreach ($fields as $field)
        {
            $result .= "\tprotected \$_{$field};\n";

            if (strpos($field, 'image') !== FALSE)
            {
                $result .= "\tprotected \$_{$field}_id;\n";
            }

            if (strpos($field, 'file') !== FALSE)
            {
                $result .= "\tprotected \$_{$field}_id;\n";
            }
        }
        return $result;
    }

    protected function output_paginate_filter_post ($fields)
    {
        $result = '';

        foreach ($fields as $field)
        {
            $field = explode("|", $field);
            $field= $field[0];
            $result .= "\$this->_data['view_model']->set_{$field}((\$this->input->get('{$field}', TRUE) != NULL) ? \$this->input->get('{$field}', TRUE) : NULL);\n\t\t";
        }

        return $result;
    }

    protected function output_model_array_value ($fields)
    {
        $result = '';
        foreach ($fields as $field)
        {
            $field = explode("|",$field);
            $field = $field[0];

            $result .= "'{$field}' => \${$field},\n\t\t\t";

            if (strpos($field, 'image') !== FALSE)
            {
                $result .= "'{$field}_id' => \${$field}_id,\n\t\t\t";
            }

            if (strpos($field, 'file') !== FALSE)
            {
                $result .= "'{$field}_id' => \${$field}_id,\n\t\t\t";
            }
        }
        return $result;
    }

    protected function list_key_only ($fields)
    {
        $result = [];
        foreach ($fields as $field)
        {
            $result[] = $field[0];
        }
        return $result;
    }

    protected function output_list_filter ($controller)
    {
        $fields = $this->make_input_fields([
            'model' => $controller['model'],
            'filter_fields' => $this->strip_pipes_controller($controller['filter_fields'])
        ], 'filter_fields');
        $mappings = $this->make_mapping_fields($controller);
        $autocomplete_fields = $this->get_autocomplete_fields($controller,'filter_fields');
        $result = '';

        foreach ($fields as $key => $field)
        {
            $has_mapping = FALSE;
            $mapping_function = '';

            foreach ($mappings as $mapping_key => $mapping_value)
            {
                if ($mapping_key == $field[0])
                {
                    $has_mapping = TRUE;
                    $mapping_function = "{$mapping_key}_mapping";
                }
            }

            if ($has_mapping)
            {
                $result .= "\t\t\t\t\t\t<div class=\"col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12\">\n";
                $result .= "\t\t\t\t\t\t\t<div class=\"form-group\">\n";
                $result .= "\t\t\t\t\t\t\t\t<label for=\"{$field[3]}\">{$field[3]} </label>\n";
                $result .= "\t\t\t\t\t\t\t\t<select name=\"{$field[0]}\" class=\"form-control\">\n";
                $result .= "\t\t\t\t\t\t\t\t\t<option value=\"\">xyzAll</option>\n";
                $result .= "\t\t\t\t\t\t\t\t\t<?php foreach (\$view_model->{$mapping_function}() as \$key => \$value) {\n";
                $result .= "\t\t\t\t\t\t\t\t\t\techo \"<option value='{\$key}' \" . ((\$view_model->get_{$field[0]}() == \$key && \$view_model->get_{$field[0]}() != '') ? 'selected' : '') . \"> {\$value} </option>\";\n";
                $result .= "\t\t\t\t\t\t\t\t\t}?>\n";
                $result .= "\t\t\t\t\t\t\t\t</select>\n";
                $result .= "\t\t\t\t\t\t\t</div>\n";
                $result .= "\t\t\t\t\t\t</div>\n";
            }
            elseif (!empty(array_column($autocomplete_fields, 'field_name')) && in_array($field[0], array_column($autocomplete_fields,'field_name')))
            {
                $result .= "\t\t\t\t\t\t<div class=\"col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12\">\n";
                $result .= "\t\t\t\t<div class=\"form-group\">\n";
                $result .= "\t\t\t\t\t<label for=\"{$field[3]}\">{$field[3]} </label>\n";
                $result .= "\t\t\t\t\t<input type=\"hidden\" class=\"form-control\" id=\"{$controller['portal']}_{$controller['name']}_filter_{$field[0]}_autocomplete_value_field\" name=\"{$field[0]}\" value=\"<?php echo set_value('{$field[0]}'); ?>\"/>\n";
                $result .= "\t\t\t\t\t<input type=\"text\" class=\"form-control\" id=\"{$controller['portal']}_{$controller['name']}_filter_{$field[0]}_autocomplete\"  />\n";
                $result .= "\t\t\t\t</div>\n";
                $result .= "\t\t\t\t\t\t</div>\n";
            }
            else
            {
                switch ($field[1]) {
                    case 'string':
                        $result .= "\t\t\t\t\t\t<div class=\"col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12\">\n";
                        $result .= "\t\t\t\t\t\t\t<div class=\"form-group\">\n";
                        $result .= "\t\t\t\t\t\t\t\t<label for=\"{$field[3]}\">{$field[3]} </label>\n";
                        $result .= "\t\t\t\t\t\t\t\t<input type=\"text\" class=\"form-control\" id=\"{$field[0]}\" name=\"{$field[0]}\" value=\"<?php echo \$this->_data['view_model']->get_{$field[0]}();?>\"/>\n";
                        $result .= "\t\t\t\t\t\t\t</div>\n";
                        $result .= "\t\t\t\t\t\t</div>\n";
                        break;
                    case 'boolean':
                        $result .= "\t\t\t\t\t\t<div class=\"col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12\">\n";
                        $result .= "\t\t\t\t\t\t\t<div class=\"form-group\">\n";
                        $result .= "\t\t\t\t\t\t\t\t<label class=\"custom-control custom-checkbox\">\n";
                        $result .= "\t\t\t\t\t\t\t\t\t<input type=\"checkbox\" checked=\"<?php echo \$this->_data['view_model']->get_{$field[0]}();?>\" class=\"custom-control-input\" id=\"form_{$field[0]}\" name=\"{$field[0]}\" value=\"1\"/><span class=\"custom-control-label\">{$field[3]}</span>\n";
                        $result .= "\t\t\t\t\t\t\t\t</label>\n";
                        $result .= "\t\t\t\t\t\t\t</div>\n";
                        $result .= "\t\t\t\t\t\t</div>\n";
                        break;
                    case 'date':
                        $result .= "\t\t\t\t\t\t<div class=\"col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12\">\n";
                        $result .= "\t\t\t\t\t\t\t<div class=\"form-group\">\n";
                        $result .= "\t\t\t\t\t\t\t\t<label for=\"{$field[3]}\">{$field[3]} </label>\n";
                        $result .= "\t\t\t\t\t\t\t\t<input type=\"date\" class=\"form-control\" id=\"{$field[0]}\" name=\"{$field[0]}\" value=\"<?php echo \$this->_data['view_model']->get_{$field[0]}();?>\"/>\n";
                        $result .= "\t\t\t\t\t\t\t</div>\n";
                        $result .= "\t\t\t\t\t\t</div>\n";
                        break;
                    case 'datetime':
                        $result .= "\t\t\t\t\t\t<div class=\"col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12\">\n";
                        $result .= "\t\t\t\t\t\t\t<div class=\"form-group\">\n";
                        $result .= "\t\t\t\t\t\t\t\t<label for=\"{$field[3]}\">{$field[3]} </label>\n";
                        $result .= "\t\t\t\t\t\t\t\t<input type=\"datetime\" class=\"form-control\" id=\"{$field[0]}\" name=\"{$field[0]}\" value=\"<?php echo \$this->_data['view_model']->get_{$field[0]}();?>\"/>\n";
                        $result .= "\t\t\t\t\t\t\t</div>\n";
                        $result .= "\t\t\t\t\t\t</div>\n";
                        break;
                    case 'text':
                        $result .= "\t\t\t\t\t\t<div class=\"col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12\">\n";
                        $result .= "\t\t\t\t\t\t\t<div class=\"form-group\">\n";
                        $result .= "\t\t\t\t\t\t\t\t<label for=\"{$field[3]}\">{$field[3]} </label>\n";
                        $result .= "\t\t\t\t\t\t\t\t<textarea id='{$field[0]}' name='{$field[0]}' class='form-control' rows='5'><?php echo \$this->_data['view_model']->get_{$field[0]}();?></textarea>\n";
                        $result .= "\t\t\t\t\t\t\t</div>\n";
                        $result .= "\t\t\t\t\t\t</div>\n";
                        break;
                    case 'email':
                        $result .= "\t\t\t\t\t\t<div class=\"col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12\">\n";
                        $result .= "\t\t\t\t\t\t\t<div class=\"form-group\">\n";
                        $result .= "\t\t\t\t\t\t\t\t<label for=\"{$field[3]}\">{$field[3]} </label>\n";
                        $result .= "\t\t\t\t\t\t\t\t<input type=\"email\" class=\"form-control\" id=\"{$field[0]}\" name=\"{$field[0]}\" value=\"<?php echo \$this->_data['view_model']->get_{$field[0]}();?>\"/>\n";
                        $result .= "\t\t\t\t\t\t\t</div>\n";
                        $result .= "\t\t\t\t\t\t</div>\n";
                        break;
                    case 'integer':
                        $result .= "\t\t\t\t\t\t<div class=\"col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12\">\n";
                        $result .= "\t\t\t\t\t\t\t<div class=\"form-group\">\n";
                        $result .= "\t\t\t\t\t\t\t\t<label for=\"{$field[3]}\">{$field[3]} </label>\n";
                        $result .= "\t\t\t\t\t\t\t\t<input type=\"text\" class=\"form-control\" id=\"{$field[0]}\" name=\"{$field[0]}\" value=\"<?php echo \$this->_data['view_model']->get_{$field[0]}();?>\" onkeypress=\"return event.charCode >= 48 && event.charCode <= 57\"/>\n";
                        $result .= "\t\t\t\t\t\t\t</div>\n";
                        $result .= "\t\t\t\t\t\t</div>\n";
                        break;
                    case 'float':
                        $result .= "\t\t\t\t\t\t<div class=\"col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12\">\n";
                        $result .= "\t\t\t\t\t\t\t<div class=\"form-group\">\n";
                        $result .= "\t\t\t\t\t\t\t\t<label for=\"{$field[3]}\">{$field[3]} </label>\n";
                        $result .= "\t\t\t\t\t\t\t\t<input type=\"text\" class=\"form-control\" id=\"{$field[0]}\" name=\"{$field[0]}\" value=\"<?php echo \$this->_data['view_model']->get_{$field[0]}();?>\" onkeypress=\"return mkd_is_number(event,this)\"/>\n";
                        $result .= "\t\t\t\t\t\t\t</div>\n";
                        $result .= "</div>\n";
                        break;
                    case 'autocomplete':

                        break;

                    default:
                        # code...
                        break;
                }
            }
        }

        return $result;
    }

    protected function output_list_input_add ($controller)
    {
        $result = '';
        $fields = $this->make_input_fields([
            'model' => $controller['model'],
            'add_fields' => $this->strip_pipes_controller($controller['add_fields'])
        ], 'add_fields');

        $mappings =  $this->make_mapping_fields($controller);
        $original_fields = $controller['add_fields'];
        $dynamic_mapping = $controller['dynamic_mapping'];
        $autocomplete_fields = $this->get_autocomplete_fields($controller,'add_fields');
        //$drop_down_fields =  $this->get_dropdown_fields($controller, 'add_fields');

        foreach ($fields as $key => $field)
        {
            $has_mapping = FALSE;
            $has_dynamic_mapping = FALSE;
            $mapping_function = '';
            $dynamic_mapping_function = '';

            foreach ($mappings as $mapping_key => $mapping_value)
            {
                if ($mapping_key == $field[0])
                {
                    $has_mapping = TRUE;
                    $mapping_function = "{$mapping_key}_mapping";
                }
            }

            foreach ($dynamic_mapping as $d_mapping_key => $d_mapping_value)
            {
                if ($d_mapping_key == $field[0])
                {
                    $has_dynamic_mapping = TRUE;
                    $dynamic_mapping_function = $d_mapping_key;
                }
            }

            // check if field has autocomplete
            if (strpos($field[1], 'image') !== FALSE)
            {
                $parts = explode('|', $field[1]);
                $width = $parts[1];
                $height = $parts[2];
                $boundary_width = $parts[3];
                $boundary_height = $parts[4];

                $result .= "\t\t\t\t<div class=\"form-group\">\n";
                $result .= "\t\t\t\t\t<label for=\"{$field[3]}\">{$field[3]} </label>\n";
                $result .= "\t\t\t\t\t<img id=\"output_{$field[0]}\" onerror=\\\"if (this.src != '/uploads/placeholder.jpg') this.src = '/uploads/placeholder.jpg';\\\"/>\n";
                $result .= "\t\t\t\t\t<div class=\"btn btn-info btn-sm mkd-choose-image\" data-image-url=\"{$field[0]}\" data-image-id=\"{$field[0]}_id\" data-image-preview=\"output_{$field[0]}\" data-view-width=\"{$width}\" data-view-height=\"{$height}\" data-boundary-width=\"{$boundary_width}\" data-boundary-height=\"{$boundary_height}\">xyzChoose Image</div>\n";
                $result .= "\t\t\t\t\t<input type=\"hidden\" id=\"{$field[0]}\" name=\"{$field[0]}\" value=\"\"/>\n";
                $result .= "\t\t\t\t\t<input type=\"hidden\" id=\"{$field[0]}_id\" name=\"{$field[0]}_id\" value=\"\"/>\n";
                $result .= "\t\t\t\t</div>";
            }

            if (strpos($field[1], 'file') !== FALSE)
            {
                $result .= "\t\t\t\t<div class=\"form-group\">\n";
                $result .= "\t\t\t\t\t<div class=\"mkd-upload-form-btn-wrapper\">\n";
                $result .= "\t\t\t\t\t\t<label for=\"{$field[3]}\">{$field[3]}</label>\n";
                $result .= "\t\t\t\t\t\t<button class=\"mkd-upload-btn\">xyzUpload a file</button>\n";
                $result .= "\t\t\t\t\t\t<input type=\"file\" name=\"{$field[0]}_upload\" id=\"{$field[0]}_upload\" onchange=\"onFileUploaded(event, '{$field[0]}')\" accept=\".gif,.jpg,.jpeg,.png,.doc,.docx,.pdf,.md,.txt,.rtf,.xls,.xlsx,.xml,.json,.html,.mp3,.mp4,.csv,.bmp,.mpeg,.ppt,.pptx,.svg,.wav,.webm,.weba,.woff,.tiff\"/>\n";
                $result .= "\t\t\t\t\t<input type=\"hidden\" id=\"{$field[0]}\" name=\"{$field[0]}\"/>\n";
                $result .= "\t\t\t\t\t<input type=\"hidden\" id=\"{$field[0]}_id\" name=\"{$field[0]}_id\"/>\n";
                $result .= "\t\t\t\t\t<span id=\"{$field[0]}_text\" class=\"mkd-upload-filename\"></span>\n";
                $result .= "\t\t\t\t\t</div>\n";
                $result .= "\t\t\t\t</div>\n";
            }

            if ($has_mapping)
            {
                $result .= "\t\t\t\t<div class=\"form-group\">\n";
                $result .= "\t\t\t\t\t<label for=\"{$field[3]}\">{$field[3]} </label>\n";
                $result .= "\t\t\t\t\t<select id=\"form_{$field[0]}\" name=\"{$field[0]}\" class=\"form-control\">\n";
                $result .= "\t\t\t\t\t\t<?php foreach (\$view_model->{$mapping_function}() as \$key => \$value) {\n";
                $result .= "\t\t\t\t\t\t\techo \"<option value='{\$key}'> {\$value} </option>\";\n";
                $result .= "\t\t\t\t\t\t}?>\n";
                $result .= "\t\t\t\t\t</select>\n";
                $result .= "\t\t\t\t</div>\n";
            }
            else if ($has_dynamic_mapping)
            {
                $result .= "\t\t\t\t<div class=\"form-group\">\n";
                $result .= "\t\t\t\t\t<label for=\"{$field[3]}\">{$field[3]} </label>\n";
                $result .= "\t\t\t\t\t<select id=\"form_{$field[0]}\" name=\"{$field[0]}\" class=\"form-control\">\n";
                $result .= "\t\t\t\t\t\t<?php foreach (\${$dynamic_mapping_function} as \$key => \$value) {\n";
                $result .= "\t\t\t\t\t\t\techo \"<option value='{\$key}'> {\$value} </option>\";\n";
                $result .= "\t\t\t\t\t\t}?>\n";
                $result .= "\t\t\t\t\t</select>\n";
                $result .= "\t\t\t\t</div>\n";
            }
            elseif (!empty(array_column($autocomplete_fields, 'field_name')) && in_array($field[0], array_column($autocomplete_fields,'field_name')))
            {
                $result .= "\t\t\t\t<div class=\"form-group\">\n";
                $result .= "\t\t\t\t\t<label for=\"{$field[3]}\">{$field[3]} </label>\n";
                $result .= "\t\t\t\t\t<input type=\"hidden\" class=\"form-control\" id=\"{$controller['portal']}_{$controller['name']}_add_{$field[0]}_autocomplete_value_field\" name=\"{$field[0]}\" value=\"<?php echo set_value('{$field[0]}'); ?>\"/>\n";
                $result .= "\t\t\t\t\t<input style='width:100%;' type=\"text\" class=\"form-control\" id=\"{$controller['portal']}_{$controller['name']}_add_{$field[0]}_autocomplete\"  />\n";
                $result .= "\t\t\t\t</div>\n";
            }
            /*elseif (!empty(array_column($drop_down_fields, 'field_name')) && in_array($field[0], array_column($drop_down_fields,'field_name')))
            {

                $field_index = $this->search_array($drop_down_fields, 'field_name', $field[0]);

                $result .= "\t\t\t\t<div class=\"form-group\">\n";
                $result .= "\t\t\t\t\t<label for=\"{$field[3]}\">{$field[3]} </label>\n";
                $result .= "\t\t\t\t\t<select class=\"form-control\" id=\"form_{$field[0]}\" name=\"{$field[0]}\" value=\"<?php echo set_value('{$field[0]}'); ?>\">\n";
                $result .= "\t\t\t\t\t\t<option value=''>xyzChoose</option>\n";
                $result .= "\t\t\t\t\t\t<?php foreach(".'$this'."->_data['view_data']['{$field[0]}'s] as {$field[0]}) ?>\n";
                $result .= "\t\t\t\t\t\t\t<option value=''></option\n";
                $result .= "\t\t\t\t\t\t<?endforeach;?>\n";
                $result .= "\t\t\t\t\t</select>\n";
                $result .= "\t\t\t\t</div>\n";
            }*/
            else
            {
                switch ($field[1]) {
                    case 'string':
                        $result .= "\t\t\t\t<div class=\"form-group\">\n";
                        $result .= "\t\t\t\t\t<label for=\"{$field[3]}\">{$field[3]} </label>\n";
                        $result .= "\t\t\t\t\t<input type=\"text\" class=\"form-control\" id=\"form_{$field[0]}\" name=\"{$field[0]}\" value=\"<?php echo set_value('{$field[0]}'); ?>\"/>\n";
                        $result .= "\t\t\t\t</div>\n";
                        break;
                    case 'password':
                        $result .= "\t\t\t\t<div class=\"form-group\">\n";
                        $result .= "\t\t\t\t\t<label for=\"{$field[3]}\">{$field[3]} </label>\n";
                        $result .= "\t\t\t\t\t<input type=\"password\" class=\"form-control\" id=\"form_{$field[0]}\" name=\"{$field[0]}\" value=\"\"/>\n";
                        $result .= "\t\t\t\t</div>\n";
                        break;
                    case 'boolean':
                        $result .= "\t\t\t\t\t<div class=\"form-group\">\n";
                        $result .= "\t\t\t\t\t\t<label class=\"custom-control custom-checkbox\">\n";
                        $result .= "\t\t\t\t\t\t\t<input type=\"checkbox\" checked=\"\" class=\"custom-control-input\" id=\"form_{$field[0]}\" name=\"{$field[0]}\" value=\"1\"/><span class=\"custom-control-label\">{$field[3]}</span>\n";
                        $result .= "\t\t\t\t\t\t</label>\n";
                        $result .= "\t\t\t\t\t</div>\n";
                        break;
                    case 'date':
                        $result .= "\t\t\t\t<div class=\"form-group\">\n";
                        $result .= "\t\t\t\t\t<label for=\"{$field[3]}\">{$field[3]} </label>\n";
                        $result .= "\t\t\t\t\t<input type=\"date\" class=\"form-control\" id=\"form_{$field[0]}\" name=\"{$field[0]}\" value=\"<?php echo set_value('{$field[0]}'); ?>\"/>\n";
                        $result .= "\t\t\t\t</div>\n";
                        break;
                    case 'datetime':
                        $result .= "\t\t\t\t<div class=\"form-group\">\n";
                        $result .= "\t\t\t\t\t<label for=\"{$field[3]}\">{$field[3]} </label>\n";
                        $result .= "\t\t\t\t\t<input type=\"datetime\" class=\"form-control\" id=\"form_{$field[0]}\" name=\"{$field[0]}\" value=\"<?php echo set_value('{$field[0]}'); ?>\"/>\n";
                        $result .= "\t\t\t\t</div>\n";
                        break;
                    case 'text':
                        $result .= "\t\t\t\t<div class=\"form-group\">\n";
                        $result .= "\t\t\t\t\t<label for=\"{$field[3]}\">{$field[3]} </label>\n";
                        $result .= "\t\t\t\t\t<textarea id='form_{$field[0]}' name='{$field[0]}' class='form-control' rows='5'><?php echo set_value('{$field[0]}'); ?></textarea>\n";
                        $result .= "\t\t\t\t</div>\n";
                        break;
                    case 'email':
                        $result .= "\t\t\t\t<div class=\"form-group\">\n";
                        $result .= "\t\t\t\t\t<label for=\"{$field[3]}\">{$field[3]} </label>\n";
                        $result .= "\t\t\t\t\t<input type=\"email\" class=\"form-control\" id=\"form_{$field[0]}\" name=\"{$field[0]}\" value=\"<?php echo set_value('{$field[0]}'); ?>\"/>\n";
                        $result .= "\t\t\t\t</div>\n";
                        break;
                    case 'integer':
                        $result .= "\t\t\t\t<div class=\"form-group\">\n";
                        $result .= "\t\t\t\t\t<label for=\"{$field[3]}\">{$field[3]} </label>\n";
                        $result .= "\t\t\t\t\t<input type=\"text\" class=\"form-control\" id=\"form_{$field[0]}\" name=\"{$field[0]}\" value=\"<?php echo set_value('{$field[0]}'); ?>\" onkeypress=\"return (event.charCode >= 48 && event.charCode <= 57) || (event.charCode == 45)\"/>\n";
                        $result .= "\t\t\t\t</div>\n";
                        break;
                    case 'float':
                        $result .= "\t\t\t\t<div class=\"form-group\">\n";
                        $result .= "\t\t\t\t\t<label for=\"{$field[3]}\">{$field[3]} </label>\n";
                        $result .= "\t\t\t\t\t<input type=\"text\" class=\"form-control\" id=\"form_{$field[0]}\" name=\"{$field[0]}\" value=\"<?php echo set_value('{$field[0]}'); ?>\" onkeypress=\"return mkd_is_number(event,this)\"/>\n";
                        $result .= "\t\t\t\t</div>\n";
                        break;

                    default:
                        # code...
                        break;
                }
            }
        }
        return $result;
    }

    protected function output_list_input_edit ($controller)
    {
        $result = '';
        $fields = $this->make_input_fields([
            'model' => $controller['model'],
            'edit_fields' => $this->strip_pipes_controller($controller['edit_fields'])
        ], 'edit_fields');
        $mappings = $this->make_mapping_fields($controller);
        $dynamic_mapping = $controller['dynamic_mapping'] ?? [];
        $autocomplete_fields = $this->get_autocomplete_fields($controller,'edit_fields');

        foreach ($fields as $key => $field)
        {
            $has_mapping = FALSE;
            $has_dynamic_mapping = FALSE;
            $mapping_function = '';
            $dynamic_mapping_function = '';

            foreach ($mappings as $mapping_key => $mapping_value)
            {
                if ($mapping_key == $field[0])
                {
                    $has_mapping = TRUE;
                    $mapping_function = "{$mapping_key}_mapping";
                }
            }

            foreach ($dynamic_mapping as $d_mapping_key => $d_mapping_value)
            {
                if ($d_mapping_key == $field[0])
                {
                    $has_dynamic_mapping = TRUE;
                    $dynamic_mapping_function = $d_mapping_key;
                }
            }

            if (strpos($field[1], 'image') !== FALSE)
            {
                $parts = explode('|', $field[1]);
                $width = $parts[1];
                $height = $parts[2];
                $boundary_width = $parts[3];
                $boundary_height = $parts[4];

                $result .= "\t\t\t\t<div class=\"form-group\">\n";
                $result .= "\t\t\t\t\t<label for=\"{$field[3]}\">{$field[3]} </label>\n";
                $result .= "\t\t\t\t\t<img id=\"output_{$field[0]}\" src=\"<?php echo set_value('{$field[0]}', \$this->_data['view_model']->get_{$field[0]}());?>\" onerror=\\\"if (this.src != '/uploads/placeholder.jpg') this.src = '/uploads/placeholder.jpg';\\\"/>\n";
                $result .= "\t\t\t\t\t<br/><div class=\"btn btn-info btn-sm mkd-choose-image\" data-image-url=\"{$field[0]}\" data-image-id=\"{$field[0]}_id\" data-image-preview=\"output_{$field[0]}\" data-view-width=\"{$width}\" data-view-height=\"{$height}\" data-boundary-width=\"{$boundary_width}\" data-boundary-height=\"{$boundary_height}\">xyzChoose Image</div>\n";
                $result .= "\t\t\t\t\t<input type=\"hidden\" id=\"{$field[0]}\" name=\"{$field[0]}\" value=\"<?php echo set_value('{$field[0]}', \$this->_data['view_model']->get_{$field[0]}());?>\"/>\n";
                $result .= "\t\t\t\t\t<input type=\"hidden\" id=\"{$field[0]}_id\" name=\"{$field[0]}_id\" value=\"<?php echo set_value('{$field[0]}_id', \$this->_data['view_model']->get_{$field[0]}_id());?>\"/>\n";
                $result .= "\t\t\t\t</div>";
            }

            if (strpos($field[1], 'file') !== FALSE)
            {

                $result .= "\t\t\t\t<div class=\"form-group\">\n";
                $result .= "\t\t\t\t\t<div class=\"mkd-upload-form-btn-wrapper\">\n";
                $result .= "\t\t\t\t\t\t<label for=\"{$field[3]}\">{$field[3]} </label>\n";
                $result .= "\t\t\t\t\t\t<button class=\"mkd-upload-btn\">xyzUpload a file</button>\n";
                $result .= "\t\t\t\t\t\t<input type=\"file\" name=\"{$field[0]}_upload\" id=\"{$field[0]}_upload\" onchange=\"onFileUploaded(event, '{$field[0]}')\" accept=\".gif,.jpg,.jpeg,.png,.doc,.docx,.pdf,.md,.txt,.rtf,.xls,.xlsx,.xml,.json,.html,.mp3,.mp4,.csv,.bmp,.mpeg,.ppt,.pptx,.svg,.wav,.webm,.weba,.woff,.tiff\"/>\n";
                $result .= "\t\t\t\t\t<input type=\"hidden\" id=\"{$field[0]}\" name=\"{$field[0]}\" value=\"<?php echo set_value('{$field[0]}', \$this->_data['view_model']->get_{$field[0]}());?>\"/>\n";
                $result .= "\t\t\t\t\t<input type=\"hidden\" id=\"{$field[0]}_id\" name=\"{$field[0]}_id\" value=\"<?php echo set_value('{$field[0]}_id', \$this->_data['view_model']->get_{$field[0]}_id());?>\"/>\n";
                $result .= "\t\t\t\t\t<span id=\"{$field[0]}_text\" class=\"mkd-upload-filename\"><?php echo set_value('{$field[0]}', \$this->_data['view_model']->get_{$field[0]}());?></span>\n";
                $result .= "\t\t\t\t\t</div>\n";
                $result .= "\t\t\t\t</div>\n";
            }

            if ($has_mapping)
            {
                $result .= "\t\t\t\t<div class=\"form-group\">\n";
                $result .= "\t\t\t\t\t<label for=\"{$field[3]}\">{$field[3]} </label>\n";
                $result .= "\t\t\t\t\t<select id=\"form_{$field[0]}\" name=\"{$field[0]}\" class=\"form-control\">\n";
                $result .= "\t\t\t\t\t\t<?php foreach (\$view_model->{$mapping_function}() as \$key => \$value) {\n";
                $result .= "\t\t\t\t\t\t\techo \"<option value='{\$key}' \" . ((\$view_model->get_{$field[0]}() == \$key && \$view_model->get_{$field[0]}() != '') ? 'selected' : '') . \"> {\$value} </option>\";\n";
                $result .= "\t\t\t\t\t\t}?>\n";
                $result .= "\t\t\t\t\t</select>\n";
                $result .= "\t\t\t\t</div>\n";
            }
            else if ($has_dynamic_mapping)
            {
                $result .= "\t\t\t\t<div class=\"form-group\">\n";
                $result .= "\t\t\t\t\t<label for=\"{$field[3]}\">{$field[3]} </label>\n";
                $result .= "\t\t\t\t\t<select id=\"form_{$field[0]}\" name=\"{$field[0]}\" class=\"form-control\">\n";
                $result .= "\t\t\t\t\t\t<?php foreach (\${$dynamic_mapping_function} as \$key => \$value) {\n";
                $result .= "\t\t\t\t\t\t\techo \"<option value='{\$key}' \" . ((\$view_model->get_{$field[0]}() == \$key && \$view_model->get_{$field[0]}() != '') ? 'selected' : '') . \"> {\$value} </option>\";\n";
                $result .= "\t\t\t\t\t\t}?>\n";
                $result .= "\t\t\t\t\t</select>\n";
                $result .= "\t\t\t\t</div>\n";
            }
            elseif (!empty(array_column($autocomplete_fields, 'field_name')) && in_array($field[0], array_column($autocomplete_fields,'field_name')))
            {
                $result .= "\t\t\t\t<div class=\"form-group\">\n";
                $result .= "\t\t\t\t\t<label for=\"{$field[3]}\">{$field[3]} </label>\n";
                $result .= "\t\t\t\t\t<input hidden=\"text\" class=\"form-control\" id=\"{$controller['portal']}_{$controller['name']}_edit_{$field[0]}_autocomplete_value_field\" name=\"{$field[0]}\" value=\"<?php echo set_value('{$field[0]}', \$this->_data['view_model']->get_{$field[0]}()); ?>\"/>\n";
                $result .= "\t\t\t\t\t<input style='width:100%;' type=\"text\" class=\"form-control\" id=\"{$controller['portal']}_{$controller['name']}_edit_{$field[0]}_autocomplete\"  />\n";
                $result .= "\t\t\t\t</div>\n";
            }
            else
            {
                switch ($field[1]) {
                    case 'string':
                        $result .= "\t\t\t\t<div class=\"form-group\">\n";
                        $result .= "\t\t\t\t\t<label for=\"{$field[3]}\">{$field[3]} </label>\n";
                        $result .= "\t\t\t\t\t<input type=\"text\" class=\"form-control\" id=\"form_{$field[0]}\" name=\"{$field[0]}\" value=\"<?php echo set_value('{$field[0]}', \$this->_data['view_model']->get_{$field[0]}());?>\"/>\n";
                        $result .= "\t\t\t\t</div>\n";
                        break;
                    case 'password':
                        $result .= "\t\t\t\t<div class=\"form-group\">\n";
                        $result .= "\t\t\t\t\t<label for=\"{$field[3]}\">{$field[3]} </label>\n";
                        $result .= "\t\t\t\t\t<input type=\"password\" class=\"form-control\" id=\"form_{$field[0]}\" name=\"{$field[0]}\" value=\"\"/>\n";
                        $result .= "\t\t\t\t</div>\n";
                        break;
                    case 'boolean':
                        $result .= "\t\t\t\t\t<div class=\"form-group\">\n";
                        $result .= "\t\t\t\t\t\t<label class=\"custom-control custom-checkbox\">\n";
                        $result .= "\t\t\t\t\t\t\t<input type=\"checkbox\" checked=\"<?php echo \$this->_data['view_model']->get_{$field[0]}();?>\" class=\"custom-control-input\" id=\"form_{$field[0]}\" name=\"{$field[0]}\" value=\"1\"/><span class=\"custom-control-label\">{$field[3]}</span>\n";
                        $result .= "\t\t\t\t\t\t</label>\n";
                        $result .= "\t\t\t\t\t</div>\n";
                        break;
                    case 'date':
                        $result .= "\t\t\t\t<div class=\"form-group\">\n";
                        $result .= "\t\t\t\t\t<label for=\"{$field[3]}\">{$field[3]} </label>\n";
                        $result .= "\t\t\t\t\t<input type=\"date\" class=\"form-control\" id=\"form_{$field[0]}\" name=\"{$field[0]}\" value=\"<?php echo set_value('{$field[0]}', \$this->_data['view_model']->get_{$field[0]}());?>\"/>\n";
                        $result .= "\t\t\t\t</div>\n";
                        break;
                    case 'datetime':
                        $result .= "\t\t\t\t<div class=\"form-group\">\n";
                        $result .= "\t\t\t\t\t<label for=\"{$field[3]}\">{$field[3]} </label>\n";
                        $result .= "\t\t\t\t\t<input type=\"datetime\" class=\"form-control\" id=\"form_{$field[0]}\" name=\"{$field[0]}\" value=\"<?php echo set_value('{$field[0]}', \$this->_data['view_model']->get_{$field[0]}());?>\"/>\n";
                        $result .= "\t\t\t\t</div>\n";
                        break;
                    case 'text':
                        $result .= "\t\t\t\t<div class=\"form-group\">\n";
                        $result .= "\t\t\t\t\t<label for=\"{$field[3]}\">{$field[3]} </label>\n";
                        $result .= "\t\t\t\t\t<textarea id='form_{$field[0]}' name='{$field[0]}' class='form-control' rows='5'><?php echo set_value('{$field[0]}', \$this->_data['view_model']->get_{$field[0]}());?></textarea>\n";
                        $result .= "\t\t\t\t</div>\n";
                        break;
                    case 'email':
                        $result .= "\t\t\t\t<div class=\"form-group\">\n";
                        $result .= "\t\t\t\t\t<label for=\"{$field[3]}\">{$field[3]} </label>\n";
                        $result .= "\t\t\t\t\t<input type=\"email\" class=\"form-control\" id=\"form_{$field[0]}\" name=\"{$field[0]}\" value=\"<?php echo set_value('{$field[0]}', \$this->_data['view_model']->get_{$field[0]}());?>\"/>\n";
                        $result .= "\t\t\t\t</div>\n";
                        break;
                    case 'integer':
                        $result .= "\t\t\t\t<div class=\"form-group\">\n";
                        $result .= "\t\t\t\t\t<label for=\"{$field[3]}\">{$field[3]} </label>\n";
                        $result .= "\t\t\t\t\t<input type=\"text\" class=\"form-control\" id=\"form_{$field[0]}\" name=\"{$field[0]}\" value=\"<?php echo set_value('{$field[0]}', \$this->_data['view_model']->get_{$field[0]}());?>\" onkeypress=\"return (event.charCode >= 48 && event.charCode <= 57) || (event.charCode == 45)\"/>\n";
                        $result .= "\t\t\t\t</div>\n";
                        break;
                    case 'float':
                        $result .= "\t\t\t\t<div class=\"form-group\">\n";
                        $result .= "\t\t\t\t\t<label for=\"{$field[3]}\">{$field[3]} </label>\n";
                        $result .= "\t\t\t\t\t<input type=\"text\" class=\"form-control\" id=\"form_{$field[0]}\" name=\"{$field[0]}\" value=\"<?php echo set_value('{$field[0]}', \$this->_data['view_model']->get_{$field[0]}());?>\" onkeypress=\"return mkd_is_number(event,this)\"/>\n";
                        $result .= "\t\t\t\t</div>\n";
                        break;

                    default:
                        # code...
                        break;
                }
            }
        }

        return $result;
    }

    protected function output_list_input_view ($controller)
    {
        $fields = $this->make_input_fields([
            'model' => $controller['model'],
            'view_fields' => $this->strip_pipes_controller($controller['view_fields'])
        ], 'view_fields');

        $mappings =  $this->make_mapping_fields($controller);
        $original_fields = $controller['view_fields'];
        $result = '';

        foreach ($fields as $key => $field)
        {
            $has_mapping = FALSE;
            $mapping_function = '';

            foreach ($mappings as $mapping_key => $mapping_value)
            {
                if ($mapping_key == $field[0])
                {
                    $has_mapping = TRUE;
                    $mapping_function = "{$mapping_key}_mapping";
                }
            }

            if ($has_mapping)
            {
                $result .= "\t\t\t\t\t\t<h6>{$field[3]}:&nbsp; <?php echo \$view_model->{$mapping_function}()[\$view_model->get_{$field[0]}()];?></h6>\n";
            }
            else
            {
                if (strpos($field[1], 'image') !== FALSE)
                {
                    $result .= "\t\t\t\t\t\t<h6>{$field[3]}:&nbsp; <img class=\"img-fluid\" src=\"<?php echo \$view_model->get_{$field[0]}();?>\" onerror=\\\"if (this.src != '/uploads/placeholder.jpg') this.src = '/uploads/placeholder.jpg';\\\"/></h6>\n";
                }
                else
                {
                    //TODO
                    $field_type = $original_fields[$key];
                    if (strstr($field_type, '|'))
                    {
                        $result .= $this->field_type_display($field_type, "\t\t\t\t\t\t<h6>{$field[3]}:&nbsp; <?php echo ", ";?></h6>\n" , "\$view_model->get_{$field[0]}()");
                    }
                    else
                    {
                        $result .= "\t\t\t\t\t\t<h6>{$field[3]}:&nbsp; <?php echo \$view_model->get_{$field[0]}();?></h6>\n";
                    }
                }
            }
        }

        return $result;
    }

    protected function output_list_rows ($fields, $controller, $mappings)
    {
        $result = '';
        foreach ($fields as $field)
        {
            $has_mapping = FALSE;
            $mapping_function = '';

            foreach ($mappings as $mapping_key => $mapping_value)
            {
                if ($mapping_key == $field)
                {
                    $has_mapping = TRUE;
                    $mapping_function = "{$mapping_key}_mapping";
                }
            }

            if ($has_mapping)
            {
                $result .= "\t\t\t\t\t\t\techo \"<td>{\$view_model->{$mapping_function}()[\$data->{$field}]}</td>\";\n";
            }
            else
            {
                $result .= "\t\t\t\t\t\t\techo \"<td>{\$data->{$field}}</td>\";\n";
            }
        }
        $has_action = $controller['is_edit'] || $controller['is_real_delete'] || $controller['is_delete'] || $controller['is_view'];

        if ($has_action)
        {
            $result .= "\t\t\t\t\t\t\techo '<td>';\n";

            if ($controller['is_edit'])
            {
                $result .= "\t\t\t\t\t\t\techo '<a class=\"btn btn-primary btn-sm\" target=\"__blank\" href=\"/{$controller['portal']}{$controller['route']}/edit/' . \$data->id . '\">xyzEdit</a>';\n";
            }

            if ($controller['is_view'])
            {
                $result .= "\t\t\t\t\t\t\techo ' <a class=\"btn btn-warning btn-sm\" target=\"__blank\" href=\"/{$controller['portal']}{$controller['route']}/view/' . \$data->id . '\">xyzView</a>';\n";
            }

            if ($controller['is_delete'] || $controller['is_real_delete'])
            {
                $result .= "\t\t\t\t\t\t\techo ' <a class=\"btn btn-danger btn-sm\" target=\"__blank\" href=\"/{$controller['portal']}{$controller['route']}/delete/' . \$data->id . '\">xyzRemove</a>';\n";
            }

            $result .= "\t\t\t\t\t\t\techo '</td>';";
        }
        return $result;
    }

    private function startsWith ($string, $startString)
    {
        $len = strlen($startString);
        return (substr($string, 0, $len) === $startString);
    }

    private function search_array($array, $column, $key)
    {
       return (array_search($key, array_column($array, $column)));
    }

    protected function output_list_rows_raw ($fields, $controller, $mappings)
    {
        $result = '';
        foreach ($fields as $field)
        {
            $has_mapping = FALSE;
            $mapping_function = '';
            $parts = explode('|', $field);
            $field_name = $parts[0];
            $field_type = $parts[1];

            foreach ($mappings as $mapping_key => $mapping_value)
            {
                if ($mapping_key == $field_name)
                {
                    $has_mapping = TRUE;
                    $mapping_function = "{$mapping_key}_mapping";
                }
            }

            /**
             * Steps:
             * 1.Is it complex || field
             * 2.If field, get field type
             * 3.field type switch by type
             * 4.If complex split fields
             * 5.complex show fields
             */
            if ($field_name == 'complex')
            {
                $field_list = explode(':', $field_type);
                $complex_list = [];
                $complex_list[] = "'<td>'";

                foreach ($field_list as $key => $value)
                {
                    if (strpos($value, '~') !== FALSE)
                    {
                        $field_parts = explode('~', $value);
                        $complex_field_name = $field_parts[0];
                        $complex_field_type = $field_parts[1];

                        $has_mapping = FALSE;
                        foreach ($mappings as $mapping_key => $mapping_value)
                        {
                            if ($mapping_key == $complex_field_name)
                            {
                                $has_mapping = TRUE;
                                $mapping_function = "{$mapping_key}_mapping";
                            }
                        }

                        switch ($complex_field_type)
                        {
                            case 'image':
                                $result .= "\t\t\t\t\t\t\techo \"<td><div class='mkd-image-container'><img class='img-fluid' src='{\$data->{$complex_field_name}}' onerror=\\\"if (this.src != '/uploads/placeholder.jpg') this.src = '/uploads/placeholder.jpg';\\\" /></div></td>\";\n";
                                break;
                            case 'imagefile':
                                $result .= "\t\t\t\t\t\t\techo \"<td>\" . \$view_model->image_or_file(\$data->{$field_name}) . \"</td>\";\n";
                                break;
                            case 'boolean':
                                $result .= "\t\t\t\t\t\t\techo \"<td>\" . ((\$data->{$complex_field_name} == 1) ? \"xyzYes\" : \"xyzNo\") . \"</td>\";\n";
                                break;
                            case 'integer':
                            case 'string':
                            case 'text':
                            case 'datetime':
                            case 'date':
                            case 'file':
                                if ($has_mapping)
                                {
                                    $result .= "\t\t\t\t\t\t\techo \"<td>{\$view_model->{$mapping_function}()[\$data->{$complex_field_name}]}</td>\";\n";
                                }
                                else
                                {
                                    $result .= "\t\t\t\t\t\t\techo \"<td>{\$data->{$complex_field_name}}</td>\";\n";
                                }
                                break;

                            default:
                                # code...
                                break;
                        }
                    }
                    else
                    {
                        $has_mapping = FALSE;
                        foreach ($mappings as $mapping_key => $mapping_value)
                        {
                            if ($mapping_key == $value)
                            {
                                $has_mapping = TRUE;
                                $mapping_function = "{$mapping_key}_mapping";
                            }
                        }
                        if ($has_mapping)
                        {
                            $complex_list[] = "\$view_model->{$mapping_function}()[\$data->{$value}] . \"<br/>\"";
                        }
                        else
                        {
                            $complex_list[] = "\$data->{$value} . \"<br/>\"";
                        }
                    }
                }
                $complex_list[] = "'</td>'";
                $result .= "\t\t\t\t\t\t\techo" . implode(' . ', $complex_list) . ";\n";
            }
            else if ($this->startsWith($field_type, 'link'))
            {
                $field_list = explode(':', $field_type);
                error_log(print_r($field_list, true));
                if (count($field_list) != 2) {
                    $result .= "\t\t\t\t\t\t\techo \"<td>ERROR</td>\";\n";
                } else {
                    $link = $field_list[1];
                    $result .= "\t\t\t\t\t\t\techo \"<td><a href='{$link}'>{\$data->{$field_name}}</a></td>\";\n";
                }
            }
            else
            {
                switch ($field_type)
                {
                    case 'date':
                    $result .= "\t\t\t\t\t\t\techo \"<td>\" . date('F d Y', strtotime(\$data->{$field_name})) . \"</td>\";\n";
                    break;
                    case 'datetime':
                    $result .= "\t\t\t\t\t\t\techo \"<td>\" . date('F d Y h:i A', strtotime({\$data->{$field_name}})) . \"</td>\";\n";
                    break;
                    case 'timeago':
                        $result .= "\t\t\t\t\t\t\techo \"<td>\" . \$view_model->timeago({\$data->{$field_name}}) . \"</td>\";\n";
                        break;
                    case 'currency':
                        $result .= "\t\t\t\t\t\t\techo \"<td>$\" . number_format(\$data->{$field_name}, 2) . \"</td>\";\n";
                        break;
                    case 'image':
                        $result .= "\t\t\t\t\t\t\techo \"<td><div class='mkd-image-container'><img class='img-fluid' src='{\$data->{$field_name}}' onerror=\\\"if (this.src != '/uploads/placeholder.jpg') this.src = '/uploads/placeholder.jpg';\\\"/></div></td>\";\n";
                        break;
                    case 'imagefile':
                        $result .= "\t\t\t\t\t\t\techo \"<td>\" . \$view_model->image_or_file(\$data->{$field_name}) . \"</td>\";\n";
                        break;
                    case 'boolean':
                        $result .= "\t\t\t\t\t\t\techo \"<td>\" . ((\$data->{$field_name} == 1) ? \"xyzYes\" : \"xyzNo\") . \"</td>\";\n";
                        break;
                    case 'uppercase':
                        $result .= "\t\t\t\t\t\t\techo \"<td>\" . strtoupper(\$data->{$field_name}) . \"</td>\";\n";
                        break;
                    case 'lowercase':
                        $result .= "\t\t\t\t\t\t\techo \"<td>\" . strtolower(\$data->{$field_name}) . \"</td>\";\n";
                        break;
                    case 'uppercasefirst':
                            $result .= "\t\t\t\t\t\t\techo \"<td>\" . ucfirst(\$data->{$field_name}) . \"</td>\";\n";
                        break;
                    case 'url':
                            $result .= "\t\t\t\t\t\t\techo \"<td><a  class='btn-link' target='_blank' href='\$data->{$field_name}'>xyzView</a></td>\";\n";
                        break;
                    case 'json':
                        $result .= "\t\t\t\t\t\t\techo \"<td>$\" . json_encode(json_decode(\$data->{$field_name}, TRUE), JSON_PRETTY_PRINT) . \"</td>\";\n";
                        break;
                    case 'integer':
                    case 'float':
                    case 'string':
                    case 'datetime':
                    case 'text':
                    case 'file':
                        if ($has_mapping)
                        {
                            $result .= "\t\t\t\t\t\t\techo \"<td>\" . ucfirst(\$view_model->{$mapping_function}()[\$data->{$field_name}]) .\"</td>\";\n";
                        }
                        else
                        {
                            $result .= "\t\t\t\t\t\t\techo \"<td>{\$data->{$field_name}}</td>\";\n";
                        }
                        break;

                    default:
                        # code...
                        break;
                }
            }
        }

        $has_action = $controller['is_edit'] || $controller['is_real_delete'] || $controller['is_delete'] || $controller['is_view'];
        $has_join = strlen($controller['paginate_join']) > 0;
        $result .= "\t\t\t\t\t\t\techo '<td>';\n";
        if ($has_action)
        {

            if ($controller['is_edit'])
            {
                if ($has_join)
                {
                    $result .= "\t\t\t\t\t\t\techo '<a class=\"btn btn-primary btn-sm\" target=\"__blank\" href=\"/{$controller['portal']}{$controller['route']}/edit/' . \$data->a_id . '\">xyzEdit</a>';\n";
                }
                else
                {
                    $result .= "\t\t\t\t\t\t\techo '<a class=\"btn btn-primary btn-sm\" target=\"__blank\" href=\"/{$controller['portal']}{$controller['route']}/edit/' . \$data->id . '\">xyzEdit</a>';\n";
                }
            }

            if ($controller['is_view'])
            {
                if ($has_join)
                {
                $result .= "\t\t\t\t\t\t\techo ' <a class=\"btn btn-warning btn-sm\" target=\"__blank\" href=\"/{$controller['portal']}{$controller['route']}/view/' . \$data->a_id . '\">xyzView</a>';\n";
                }
                else
                {
                    $result .= "\t\t\t\t\t\t\techo ' <a class=\"btn btn-warning btn-sm\" target=\"__blank\" href=\"/{$controller['portal']}{$controller['route']}/view/' . \$data->id . '\">xyzView</a>';\n";
                }
            }

            if ($controller['is_delete'] || $controller['is_real_delete'])
            {
                if ($has_join)
                {
                $result .= "\t\t\t\t\t\t\techo ' <a class=\"btn btn-danger btn-sm\" target=\"__blank\" href=\"/{$controller['portal']}{$controller['route']}/delete/' . \$data->a_id . '\">xyzRemove</a>';\n";
                }
                else
                {
                    $result .= "\t\t\t\t\t\t\techo ' <a class=\"btn btn-danger btn-sm\" target=\"__blank\" href=\"/{$controller['portal']}{$controller['route']}/delete/' . \$data->id . '\">xyzRemove</a>';\n";
                }
            }

        }
        $nbsp = '&nbsp;';

        if (count($controller['listing_actions']) < 2)
        {
            $nbsp = '';
        }

        foreach ($controller['listing_actions'] as $key => $value)
        {
            $parts = explode('|', $value);
            $label = $parts[0];
            $route = $parts[1];
            $condition = $parts[2];
            $result .= "\t\t\t\t\t\t\t\$condition = {$condition};\n";
            $result .= "\t\t\t\t\t\t\techo (\$condition) ? \"{$nbsp} <a class='btn btn-info btn-sm' target='_blank' href='{$route}'>{$label}</a>\" : '';\n";
        }
        $result .= "\t\t\t\t\t\t\techo '</td>';";
        return $result;
    }

    protected function output_view_model_column ($fields)
    {
        $result = [];
        foreach ($fields as $field)
        {
            $result[]= "'{$field[3]}'";
        }

        $result[] = "'xyzAction'";

        return implode(',', $result);
    }

    protected function output_view_model_column_raw ($fields)
    {
        $result = [];
        foreach ($fields as $field)
        {
            $result[]= "'{$field}'";
        }

        $result[] = "'xyzAction'";

        return implode(',', $result);
    }

    protected function output_view_model_field_column ($fields)
    {
        $result = [];
        foreach ($fields as $field)
        {
            if (strpos($field, 'complex|') !== false)
            {
                $result[] = "''";
            }
            else
            {
                $parts = explode('|', $field);
                $result[]= "'{$parts[0]}'";
            }
        }

        $result[] = "''";

        return implode(',', $result);
    }

    protected function output_paginate_filter_where ($fields, $all_records, $active_only)
    {
        $result = '';
        foreach ($fields as $field)
        {
            $field = explode("|", $field);
            $field= $field[0];
            $result .= "'{$field}' => \$this->_data['view_model']->get_{$field}(),\n\t\t\t";
        }

        if (!$all_records && !in_array('user_id', $fields))
        {
            $result .= "'user_id' => \$session['user_id'],";
        }

        if ($active_only)
        {
            $result .= " 'status' => 1";
        }

        return $result;
    }

    protected function output_add_button ($controller)
    {
        return "<a class=\"btn btn-primary btn-sm\" target=\"__blank\" href=\"/{$controller['portal']}{$controller['route']}/add\"><i class=\"fas fa-plus-circle\"></i></a>";
    }

    protected function view_resource($all_records, $active_only)
    {
        if ($all_records)
        {
            return '';
        }
        return "\t\t\$session = \$this->get_session();\n\t\tif (\$model->user_id != \$session['user_id'])\n\t\t{\n\t\t\t\$this->error('xyzError');\n\t\t\treturn redirect('/{{{portal}}}{{{route}}}');\n\t\t}\n\t\tif (\$model->status != 1)\n\t\t{\n\t\t\t\$this->error('xyzError');\n\t\t\treturn redirect('/{{{portal}}}{{{route}}}');\n\t\t}";
    }

    protected function import($model)
    {
        return '&nbsp;<div class="mkd-upload-form-btn-wrapper">' .
        "\t" .
        '    <button class="mkd-upload-btn">xyzImport</button>' .
        "\t" .
        '    <input type="file" name="file_import" id="file_import" onchange="onFileImport(event, \'' . $model . '\')" accept=".csv"/>' .
        '</div>&nbsp;';
    }

    protected function export($controller)
    {
        return "<a class=\"btn btn-primary btn-sm\" href=\"/{$controller['portal']}{$controller['route']}/export\"><i class=\"fas fa-cloud-download-alt\"></i></a>";
    }

    protected function output_dynamic_mapping_controller_section($mapping)
    {
        $result = '';
        foreach ($mapping as $key => $model_row)
        {
            $result .= "\t\t\t\t\$this->_data['{$key}'] = \$this->{$model_row['function']}();\n";
        }
        return $result;
    }

    protected function output_activity_log($controller, $portal, $action)
    {
        $model=  $controller['model'].'_model';
        $log_code = '$this->'. $portal .'_operation_model->log_activity("xyz'. $action .' '. $controller['name'] .'",$this->'. $model.'->get('. ($action === 'Add' ? '$result' : '$id')  .'),  $this->get_session()["user_id"] );';
        return  $log_code;
    }

    protected function output_autocomplete_controller($autocomplete_text, $model)
    {
        $result = '';
        $controller_model = NULL;
        foreach ($this->_models as $single_model)
        {
            if ($single_model['name'] == $model)
            {
                $controller_model = $single_model['field'];
            }
        }

        foreach ($controller_model as $key => $field)
        {
            if ($autocomplete_text == $field[0])
            {
                if ($field[1] == 'integer' || $field[1] == 'float')
                {
                    $result .= "'{$field[0]}' => \$text";
                }
                else if ($field[1] == 'date' || $field[1] == 'datetime')
                {
                    $result .= "'{$field[0]}' => '\$text'";
                }
                else
                {
                    $result .= "'{$field[0]} LIKE \"%' . \$text . '%\"' => NULL";
                }
            }

        }

        return $result;
    }

    protected function output_dynamic_mapping_controller ($mappings)
    {
        $result = '';
        foreach ($mappings as $mapping_key => $mapping_value)
        {
            if (isset($mapping_value['code']))
            {
                $result .= "\n{$mapping_value['code']}";
            }
        }
        return $result;
    }

    protected function get_dropdown_fields($controller, $type)
    {
        $drop_down_fields = [];
        $fields = $controller[$type];
        $result = explode('_',$type);
        $method_type = $result[0];

        for($i = 1; $i < count($fields); $i ++)
        {

            $str_pipe = explode('|',$fields[$i] ?? '' );
            $pipe =  $str_pipe[1] ?? '';
            if( substr($pipe, 0, 9) === 'drop_down')
            {
                $fields_array = explode('|', $fields[$i] ?? '');

                if(count($fields_array) > 1 )
                {
                    $drop_down_fields = explode(':', $fields[$i] ?? '');
                    preg_match('#\((.*?)\)#',  $drop_down_fields[2], $match);
                    $str_field = explode('|',$fields[$i] ?? '' );
                    $field =  $str_field[0] ?? '';
                    $display_fields =  $match[1];
                    $temp = [
                        'field_name' => $field ,
                        'table_name' =>  $drop_down_fields[1],
                        'display_fields' => explode(',', $display_fields),
                        'field_value_field' => $drop_down_fields[3],
                        'method_type' => $method_type
                    ];

                    $drop_down_fields[] =  $temp;
                }
            }
        }
        return  $drop_down_fields;
    }

    protected function get_autocomplete_fields($controller, $type)
    {
        $autocomplete_array = [];
        $fields = $controller[$type];
        $result = explode('_',$type);
        $method_type = $result[0];
        for($i = 1; $i < count($fields); $i ++)
        {

            $str_pipe = explode('|',$fields[$i] ?? '' );
            $pipe =  $str_pipe[1] ?? '';
            if( substr($pipe, 0, 12) === 'autocomplete'  )
            {
                $fields_array = explode('|', $fields[$i] ?? '');
                $autocomplete_fields = explode(':', $fields[$i] ?? '');
                if(count($fields_array) > 1 )
                {
                    $temp = [
                        'field_name' => $fields_array[0],
                        'table_name' => $autocomplete_fields[1],
                        'field_search' => $autocomplete_fields[2],
                        'field_label_field' => $autocomplete_fields[3],
                        'field_value_field' =>  $autocomplete_fields[4],
                        'method_type' => $method_type
                    ];

                    $autocomplete_array[] =  $temp;
                }
            }
        }
        return $autocomplete_array;
    }

    protected function get_autocomplete_controller_methods($auto_complete_array)
    {
        $output = '';
        $search_text = "'. ". '" \'%" . $search_text . "%\'"';
        for($i = 0; $i < count($auto_complete_array); $i ++)
        {
            $output .= "\n\tpublic function search_{$auto_complete_array[$i]["field_name"]}_{$auto_complete_array[$i]["method_type"]}_auto_complete()\n\t{\n\t\t".'$this->load->model'."('{$auto_complete_array[$i]['table_name']}_model');\n\t\t".'$search_text'." = ".'$this->input->get("search_text")'.";\n\t\t".'$sql'." =  ' SELECT {$auto_complete_array[$i]['field_label_field']}, {$auto_complete_array[$i]['field_value_field']} FROM {$auto_complete_array[$i]['table_name']} WHERE {$auto_complete_array[$i]["field_search"]} LIKE {$search_text} ;\n\t\t".'$result = $this->'."{$auto_complete_array[$i]['table_name']}_model->raw_query(".'$sql'.")->result(); \n\t\techo json_encode(".'$result'.");\n\t\texit();\n\t}\n";
        }
        return $output;
    }

    public function search_first_name_filter_auto_complete()
	{
		$this->load->model('user_model');
		$search_text = $this->input->get("search_text");
		$sql =  ' SELECT first_name, first_name FROM user WHERE first_name LIKE \'% $search_text%\' ';
		$result = $this->user_model->raw_query($sql)->result();
		echo json_encode($result);
		exit();
	}
    protected function generate_autocomplete_js($controller, $fields)
    {
        $output = '';
        for($i = 0; $i < count($fields); $i ++)
        {
            //admin_add_content_autocomplete display_field
            $url = '/'. $controller['portal'].'/'.  $controller['name'] . '/search_' . $fields[$i]['field_name'] . '_' . $fields[$i]['method_type'] . '_autocomplete?search_text=';
            $template = file_get_contents('templates/source/autocomplete/autocomplete_js.php');
            $template = $this->inject_substitute( $template, 'search_field', $controller['portal'].'_'.$controller['name'] .'_' .  $fields[$i]['method_type'] .'_'. $fields[$i]['field_name']);
            $template = $this->inject_substitute( $template, 'url',  $url);
            $template = $this->inject_substitute( $template, 'field_label_field', $fields[$i]['field_label_field'] );
            $template = $this->inject_substitute( $template, 'display_field', $fields[$i]['field_label_field'] );
            $template = $this->inject_substitute( $template, 'value_field', $fields[$i]['field_value_field'] );
            $output .=  $template . "\n\n";
        }
        return  $output;
    }

    protected function strip_pipes_controller($fields)
    {
        foreach ($fields as $key => $value)
        {
            $fields[$key] = str_replace(strstr($value, '|'), '', $value);
        }
        return $fields;
    }

    protected function field_type_display($field, $surroundingLeft, $surroundingRight, $surround_middle)
    {
        $pipe = strstr($field, '|');
        $field_type = $field;
        error_log($field . ' ' . $field_type);
        $result = '';
        if ($pipe)
        {
            $field_type = str_replace('|', '', $pipe);
        }
        switch ($field_type)
        {
            case 'date':
                $result = $surroundingLeft . "date('F d Y', strtotime($surround_middle))" . $surroundingRight;
                break;
            case 'timeago':
                $result = $surroundingLeft . "\$view_model->timeago($surround_middle) " . $surroundingRight;
                break;
            case 'datetime':
                $result = $surroundingLeft . "date('F d Y h:i A', strtotime($surround_middle)) " . $surroundingRight;
                break;
            case 'currency':
                $result = $surroundingLeft . "'$' . number_format($surround_middle, 2) " . $surroundingRight;
                break;
            case 'image':
                $result =  $surroundingLeft . "\"<div class='mkd-image-container'><img class='img-fluid' src='$surround_middle' onerror=\\\"if (this.src != '/uploads/placeholder.jpg') this.src = '/uploads/placeholder.jpg';\\\"/></div>\"" . $surroundingRight;
                break;
            case 'imagefile':
                $result = $surroundingLeft . "\$view_model->image_or_file($surround_middle)" . $surroundingRight;
                break;
            case 'boolean':
                $result = $surroundingLeft . "(($surround_middle == 1) ? \"xyzYes\" : \"xyzNo\")" . $surroundingRight;
                break;
            case 'uppercase':
                $result =  $surroundingLeft . " strtoupper($surround_middle) " . $surroundingRight;
                break;
            case 'lowercase':
                $result =  $surroundingLeft . " strtolower($surround_middle) " . $surroundingRight;
                break;
            case 'json':
                $result = $surroundingLeft . "  json_encode(json_decode($surround_middle, TRUE), JSON_PRETTY_PRINT) " . $surroundingRight;
                break;
            case 'integer':
            case 'float':
            case 'string':
            case 'datetime':
            case 'text':
            case 'file':
                    $result = $surroundingLeft . "$surround_middle" . $surroundingRight;
                break;

            default:
                # code...
                break;
        }
        return $result;
    }
}
