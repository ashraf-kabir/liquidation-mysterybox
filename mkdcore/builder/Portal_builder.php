<?php
include_once 'Builder.php';
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
class Portal_builder extends Builder
{
    protected $_config;
    protected $_portal = [];
    protected $_view_model_methods = [];
    protected $_view_model_fields = [];
    protected $_roles = [];
    protected $_routes = [];
    protected $_file_path = '';
    protected $_render_list = [];
    protected $_login_types = ['login', 'login_only', 'login_no_social', 'login_full_function'];
    protected $_register_types = ['register', 'register_no_social', 'login_full_function'];
    protected $_reset_types = ['reset'];
    protected $_forgot_types = ['forgot'];

    public function __construct($config, $locale)
    {
        $this->_config = $config;
        $this->_template = '';
        $this->_locale = $locale;
    }

    public function get_portal()
    {
        return $this->_portal;
    }


    public function set_portal($portal)
    {
        $this->_portal = $portal;
    }

    public function set_role($roles)
    {
        $this->_roles = $roles;
        foreach ($this->_portal as $key => $portal) {
            foreach ($this->_roles as $key2 => $role) {
                if ($portal['name'] == $role['name'])
                {
                    $this->_portal[$key]['role'] = $role['id'];
                }
            }
        }
    }

    public function get_route()
    {
        foreach ($this->_portal as $portal)
        {
            $ucname = ucfirst($portal['name']);
            $this->_routes["{$portal['name']}/dashboard"] = "{$ucname}/{$ucname}_dashboard_controller";
            $this->_routes["{$portal['name']}/profile"] = "{$ucname}/{$ucname}_profile_controller";
            $this->_routes["{$portal['name']}/credential"] = "{$ucname}/{$ucname}_profile_credential_controller";

            if (isset($portal['login_type']) && strlen($portal['login_type']) > 0 && in_array($portal['login_type'], $this->_login_types))
            {
                $this->_routes["{$portal['name']}/login"] = "{$ucname}/{$ucname}_login_controller";
                $this->_routes["{$portal['name']}/logout"] = "{$ucname}/{$ucname}_login_controller/logout";

                if ($portal['api_auth'])
                {
                    $this->_routes["v1/api/{$portal['name']}/login"] = "{$ucname}/{$ucname}_login_api_controller";
                    $this->_routes["v1/api/{$portal['name']}/token"] = "{$ucname}/{$ucname}_login_api_controller/token";
                }

                if ($portal['login_type'] == 'login')
                {
                    $this->_routes["google"] = "{$ucname}/{$ucname}_social_login_controller/google";
                    $this->_routes["facebook"] = "{$ucname}/{$ucname}_social_login_controller/facebook";

                    if ($portal['api_auth'])
                    {
                        $this->_routes["v1/api/google"] = "{$ucname}/{$ucname}_social_login_api_controller/google";
                        $this->_routes["v1/api/facebook"] = "{$ucname}/{$ucname}_social_login_api_controller/facebook";
                    }
                }
            }
            if (isset($portal['register']) && strlen($portal['register']) > 0 && in_array($portal['register'], $this->_register_types))
            {
                $this->_routes["{$portal['name']}/register"] = "{$ucname}/{$ucname}_register_controller";

                if ($portal['api_auth'])
                {
                    $this->_routes["v1/api/{$portal['name']}/register"] = "{$ucname}/{$ucname}_register_api_controller";
                }

                if ($portal['register'] == 'register')
                {
                    $this->_routes["google"] = "{$ucname}/{$ucname}_social_login_controller/google";
                    $this->_routes["facebook"] = "{$ucname}/{$ucname}_social_login_controller/facebook";

                    if ($portal['api_auth'])
                    {
                        $this->_routes["v1/api/google"] = "{$ucname}/{$ucname}_social_login_api_controller/google";
                        $this->_routes["v1/api/facebook"] = "{$ucname}/{$ucname}_social_login_api_controller/facebook";
                    }
                }
            }

            if (isset($portal['forgot']) && $portal['forgot'] == TRUE)
            {
                $this->_routes["{$portal['name']}/forgot"] = "{$ucname}/{$ucname}_forgot_controller";

                if ($portal['api_auth'])
                {
                    $this->_routes["v1/api/{$portal['name']}/forgot"] = "{$ucname}/{$ucname}_forgot_api_controller";
                }
            }

            if (isset($portal['reset']) && $portal['reset'] == TRUE)
            {
                $this->_routes["{$portal['name']}/reset/(:num)"] = "{$ucname}/{$ucname}_reset_controller/index/$1";

                if ($portal['api_auth'])
                {
                    $this->_routes["v1/api/{$portal['name']}/reset/(:num)"] = "{$ucname}/{$ucname}_reset_api_controller/index/$1";
                }
            }

        }
        return $this->_routes;
    }

    public function build()
    {
        foreach ($this->_portal as $portal)
        {
            $template = file_get_contents('../mkdcore/source/portal/Portal_Controller.php');
            $template = $this->inject_substitute($template, 'subclass_prefix', $this->_config['subclass_prefix']);
            $template = $this->inject_substitute($template, 'name', $portal['name']);
            $ucname = ucfirst($portal['name']);
            $template = $this->inject_substitute($template, 'ucname', $ucname);
            $template = $this->inject_substitute($template, 'user_model', $portal['model']);
            $template = $this->inject_substitute($template, 'valid_roles', $portal['role']);
            $template = $this->inject_substitute($template, 'middleware', $this->process_middleware($portal['middleware']));
            $this->_render_list["../release/application/controllers/{$ucname}/{$ucname}_controller.php"] = $template;

            $profile_template = file_get_contents('../mkdcore/source/portal/Profile.php');
            $profile_template = $this->inject_substitute($profile_template, 'portal', $portal['name']);
            $profile_template = $this->inject_substitute($profile_template, 'user_model', $portal['model']);
            $profile_template = $this->inject_substitute($profile_template, 'valid_roles', $portal['role']);
            $profile_template = $this->inject_substitute($profile_template, 'middleware', $this->process_middleware($portal['middleware']));
            $this->_render_list["../release/application/views/{$ucname}/Profile.php"] = $profile_template;

            $credential_controller_template = file_get_contents('../mkdcore/source/portal/Credential_controller.php');
            $credential_controller_template = $this->inject_substitute($credential_controller_template, 'portal', $portal['name']);
            $credential_controller_template = $this->inject_substitute($credential_controller_template, 'uc_portal', ucfirst($portal['name']));
            $this->_render_list["../release/application/controllers/{$ucname}/{$ucname}_profile_credential_controller.php"] = $credential_controller_template;

            $uc_portal = ucfirst($portal['name']);
            $credential_view_model_template = file_get_contents('../mkdcore/source/portal/Credential_view_model.php');
            $credential_view_model_template = $this->inject_substitute($credential_view_model_template, 'portal', $portal['name']);
            $credential_view_model_template = $this->inject_substitute($credential_view_model_template, 'uc_portal', ucfirst($portal['name']));
            $this->_render_list["../release/application/view_models/{$uc_portal}_profile_credential_view_model.php"] = $credential_view_model_template;

            $credential_template = file_get_contents('../mkdcore/source/portal/Credential.php');
            $this->_render_list["../release/application/views/{$ucname}/Credential.php"] = $credential_template;

            $profile_controller_template = file_get_contents('../mkdcore/source/portal/Profile_controller.php');
            $profile_controller_template = $this->inject_substitute($profile_controller_template, 'portal', $portal['name']);
            $profile_controller_template = $this->inject_substitute($profile_controller_template, 'uc_portal', ucfirst($portal['name']));
            $profile_controller_template = $this->inject_substitute($profile_controller_template, 'model', $portal['model']);
            $profile_controller_template = $this->inject_substitute($profile_controller_template, 'uc_no_model', ucfirst(str_replace('_model', '', $portal['model'])));
            $profile_controller_template = $this->inject_substitute($profile_controller_template, 'no_model', str_replace('_model', '', $portal['model']));
            $profile_controller_template = $this->inject_substitute($profile_controller_template, 'uc_model', ucfirst($portal['model']));
            $profile_controller_template = $this->inject_substitute($profile_controller_template, 'middleware', $this->process_middleware($portal['middleware']));
            $this->_render_list["../release/application/controllers/{$ucname}/{$ucname}_profile_controller.php"] = $profile_controller_template;

            $uc_portal = ucfirst($portal['name']);
            $profile_view_model_template = file_get_contents('../mkdcore/source/portal/Profile_view_model.php');
            $profile_view_model_template = $this->inject_substitute($profile_view_model_template, 'portal', $portal['name']);
            $profile_view_model_template = $this->inject_substitute($profile_view_model_template, 'uc_portal', ucfirst($portal['name']));
            $profile_view_model_template = $this->inject_substitute($profile_view_model_template, 'uc_name', ucfirst($portal['name']));
            $profile_view_model_template = $this->inject_substitute($profile_view_model_template, 'model', $portal['model']);
            $profile_view_model_template = $this->inject_substitute($profile_view_model_template, 'uc_no_model', ucfirst(str_replace('_model', '', $portal['model'])));
            $profile_view_model_template = $this->inject_substitute($profile_view_model_template, 'no_model', str_replace('_model', '', $portal['model']));
            $profile_view_model_template = $this->inject_substitute($profile_view_model_template, 'uc_model', ucfirst($portal['model']));
            $profile_view_model_template = $this->inject_substitute($profile_view_model_template, 'middleware', $this->process_middleware($portal['middleware']));
            $this->_render_list["../release/application/view_models/{$uc_portal}_profile_view_model.php"] = $profile_view_model_template;



            if(isset($portal['profile_page_fields']) && !empty($portal['profile_page_fields']))
            {
                // overwrite view model
                $profile_view_model_template = file_get_contents('../mkdcore/source/portal/Generic_portal_view_model.php');
                $profile_view_model_template = $this->inject_substitute($profile_view_model_template, 'uc_portal', ucfirst($portal['name']));
                $profile_view_model_template = $this->inject_substitute($profile_view_model_template, 'fields', $this->generate_view_model_fields($portal['profile_page_fields']));
                $profile_view_model_template = $this->inject_substitute($profile_view_model_template, 'methods', $this->generate_view_model_methods($portal['profile_page_fields']));
                $profile_view_model_template = $this->inject_substitute($profile_view_model_template, 'set_model', $this->set_model_method($portal['profile_page_fields']));
                $this->_render_list["../release/application/view_models/{$uc_portal}_profile_view_model.php"] = $profile_view_model_template;

                //overwrite profile
                $profile_template = file_get_contents('../mkdcore/source/portal/Generic_profile.php');
                $profile_template = $this->inject_substitute($profile_template, 'portal', $portal['name']);
                $profile_template = $this->inject_substitute($profile_template, 'form_controls', $this->generate_form_controls($portal['profile_page_fields']));
                $this->_render_list["../release/application/views/{$ucname}/Profile.php"] = $profile_template;

                // overwrite controller
                $profile_controller_template = file_get_contents('../mkdcore/source/portal/Generic_profile_controller.php');
                $profile_controller_template = $this->inject_substitute($profile_controller_template, 'portal', $portal['name']);
                $profile_controller_template = $this->inject_substitute($profile_controller_template, 'uc_portal', ucfirst($portal['name']));
                $profile_controller_template = $this->inject_substitute($profile_controller_template, 'model', $portal['model']);
                $profile_controller_template = $this->inject_substitute($profile_controller_template, 'uc_no_model', ucfirst(str_replace('_model', '', $portal['model'])));
                $profile_controller_template = $this->inject_substitute($profile_controller_template, 'no_model', str_replace('_model', '', $portal['model']));
                $profile_controller_template = $this->inject_substitute($profile_controller_template, 'uc_model', ucfirst($portal['model']));
                $profile_controller_template = $this->inject_substitute($profile_controller_template, 'edit_fields', $this->generate_edit_params($portal['profile_page_fields']));
                $profile_controller_template = $this->inject_substitute($profile_controller_template, 'input_fields', $this->generate_post_params($portal['profile_page_fields']));
                $profile_controller_template = $this->inject_substitute($profile_controller_template, 'middleware', $this->process_middleware($portal['middleware']));
                $this->_render_list["../release/application/controllers/{$ucname}/{$ucname}_profile_controller.php"] = $profile_controller_template;
            }

            if ($portal['api_auth'])
            {
                $api_template = file_get_contents('../mkdcore/source/api_auth/API_Controller.php');
                $ucname = ucfirst($portal['name']);
                $api_template = $this->inject_substitute($api_template, 'valid_roles', $portal['role']);
                $api_template = $this->inject_substitute($api_template, 'ucname', $ucname);
                $this->_render_list["../release/application/controllers/{$ucname}/{$ucname}_api_auth_controller.php"] = $api_template;

                $token_template = file_get_contents('../mkdcore/source/api_auth/Token_service.php');
                $this->_render_list["../release/application/services/Token_service.php"] = $token_template;

            }

            if ($portal['api'])
            {
                $portal_template = file_get_contents('../mkdcore/source/controller/portal_api_Controller.php');
                $portal_template = $this->inject_substitute($portal_template, 'ucname', $ucname);
                $portal_template = $this->inject_substitute($portal_template, 'user_model', $portal['model']);
                $portal_template = $this->inject_substitute($portal_template, 'valid_roles', $portal['role']);
                $this->_render_list['../release/application/controllers/' . $ucname . '/' . $ucname . '_api_controller.php'] = $portal_template;
            }

            if (isset($portal['login_type']) && strlen($portal['login_type']) > 0 && in_array($portal['login_type'], $this->_login_types))
            {
                $template = file_get_contents("../mkdcore/source/auth/{$portal['login_type']}_controller.php");
                $template = $this->inject_substitute($template, 'subclass_prefix', $this->_config['subclass_prefix']);
                $template = $this->inject_substitute($template, 'name', $portal['name']);
                $template = $this->inject_substitute($template, 'model', $portal['model']);
                $template = $this->inject_substitute($template, 'ucname', $ucname);
                $template = $this->inject_substitute($template, 'role', $portal['role']);
                $template = $this->inject_substitute($template, 'valid_roles', $portal['role']);
                $this->_render_list["../release/application/controllers/{$ucname}/{$ucname}_login_controller.php"] = $template;

                if ($portal['api_auth'])
                {
                    $api_template = file_get_contents("../mkdcore/source/api_auth/login_api_controller.php");
                    $api_template = $this->inject_substitute($api_template, 'subclass_prefix', $this->_config['subclass_prefix']);
                    $api_template = $this->inject_substitute($api_template, 'name', $portal['name']);
                    $api_template = $this->inject_substitute($api_template, 'model', $portal['model']);
                    $api_template = $this->inject_substitute($api_template, 'ucname', $ucname);
                    $api_template = $this->inject_substitute($api_template, 'role', $portal['role']);
                    $this->_render_list["../release/application/controllers/{$ucname}/{$ucname}_login_api_controller.php"] = $api_template;
                }

                $view_template = file_get_contents('../mkdcore/source/auth/' . $portal['login_type'] . '.php');
                $view_template = $this->inject_substitute($view_template, 'subclass_prefix', $this->_config['subclass_prefix']);
                $view_template = $this->inject_substitute($view_template, 'name', $portal['name']);
                $view_template = $this->inject_substitute($view_template, 'title', $this->_config['site_title']);
                $view_template = $this->inject_substitute($view_template, 'portal', $portal['name']);
                $view_template = $this->inject_substitute($view_template, 'ucname', $ucname);
                $template = $this->inject_substitute($template, 'valid_roles', $portal['role']);
                $view_template = $this->inject_substitute($view_template, 'model', $portal['model']);

                $this->_render_list["../release/application/views/{$ucname}/Login.php"] = $view_template;
                if ($portal['login_type'] == 'login')
                {
                    $this->_render_list["../release/application/libraries/Google_service.php"] = file_get_contents('../mkdcore/source/auth/Google_service.php');
                    $this->_render_list["../release/application/libraries/Facebook_service.php"] = file_get_contents('../mkdcore/source/auth/Facebook_service.php');
                }
            }
            if (isset($portal['register']) && strlen($portal['register']) > 0 && in_array($portal['register'], $this->_register_types))
            {
                if($portal['register']  === TRUE)
                {
                    $portal['register'] = 'register';
                }

                $template = file_get_contents("../mkdcore/source/auth/{$portal['register']}_controller.php");
                $template = $this->inject_substitute($template, 'subclass_prefix', $this->_config['subclass_prefix']);
                $template = $this->inject_substitute($template, 'name', $portal['name']);
                $template = $this->inject_substitute($template, 'model', $portal['model']);
                $template = $this->inject_substitute($template, 'ucname', $ucname);
                $template = $this->inject_substitute($template, 'valid_roles', $portal['role']);
                $template = $this->inject_substitute($template, 'role', $portal['role']);
                $this->_render_list["../release/application/controllers/{$ucname}/{$ucname}_register_controller.php"] = $template;

                if ($portal['api_auth'])
                {
                    $api_template = file_get_contents("../mkdcore/source/api_auth/register_api_controller.php");
                    $api_template = $this->inject_substitute($api_template, 'subclass_prefix', $this->_config['subclass_prefix']);
                    $api_template = $this->inject_substitute($api_template, 'name', $portal['name']);
                    $api_template = $this->inject_substitute($api_template, 'model', $portal['model']);
                    $api_template = $this->inject_substitute($api_template, 'ucname', $ucname);
                    $api_template = $this->inject_substitute($api_template, 'role', $portal['role']);
                    $this->_render_list["../release/application/controllers/{$ucname}/{$ucname}_register_api_controller.php"] = $api_template;
                }

                $view_template = file_get_contents('../mkdcore/source/auth/' . $portal['register'] . '.php');
                $view_template = $this->inject_substitute($view_template, 'subclass_prefix', $this->_config['subclass_prefix']);
                $view_template = $this->inject_substitute($view_template, 'name', $portal['name']);
                $view_template = $this->inject_substitute($view_template, 'ucname', $ucname);
                $view_template = $this->inject_substitute($view_template, 'title', $this->_config['site_title']);
                $view_template = $this->inject_substitute($view_template, 'portal', $portal['name']);
                $view_template = $this->inject_substitute($view_template, 'model', $portal['model']);

                $this->_render_list["../release/application/views/{$ucname}/Register.php"] = $view_template;
                if ($portal['register'] == 'register')
                {
                    $this->_render_list["../release/application/libraries/Google_service.php"] = file_get_contents('../mkdcore/source/auth/Google_service.php');
                    $this->_render_list["../release/application/libraries/Facebook_service.php"] = file_get_contents('../mkdcore/source/auth/Facebook_service.php');
                }
            }

            if (isset($portal['forgot']) && $portal['forgot'] == TRUE)
            {
                $template = file_get_contents('../mkdcore/source/auth/forgot_controller.php');
                $template = $this->inject_substitute($template, 'subclass_prefix', $this->_config['subclass_prefix']);
                $template = $this->inject_substitute($template, 'name', $portal['name']);
                $template = $this->inject_substitute($template, 'base_url', $this->_config['base_url']);
                $template = $this->inject_substitute($template, 'model', $portal['model']);
                $template = $this->inject_substitute($template, 'ucname', $ucname);
                $template = $this->inject_substitute($template, 'role', $portal['role']);
                $template = $this->inject_substitute($template, 'valid_roles', $portal['role']);
                $this->_render_list["../release/application/controllers/{$ucname}/{$ucname}_forgot_controller.php"] = $template;

                if ($portal['api_auth'])
                {
                    $api_template = file_get_contents("../mkdcore/source/api_auth/forgot_api_controller.php");
                    $api_template = $this->inject_substitute($api_template, 'subclass_prefix', $this->_config['subclass_prefix']);
                    $api_template = $this->inject_substitute($api_template, 'name', $portal['name']);
                    $api_template = $this->inject_substitute($api_template, 'base_url', $this->_config['base_url']);
                    $api_template = $this->inject_substitute($api_template, 'model', $portal['model']);
                    $api_template = $this->inject_substitute($api_template, 'ucname', $ucname);
                    $api_template = $this->inject_substitute($api_template, 'role', $portal['role']);
                    $this->_render_list["../release/application/controllers/{$ucname}/{$ucname}_forgot_api_controller.php"] = $api_template;
                }

                $view_template = file_get_contents('../mkdcore/source/auth/forgot.php');
                $view_template = $this->inject_substitute($view_template, 'subclass_prefix', $this->_config['subclass_prefix']);
                $view_template = $this->inject_substitute($view_template, 'name', $portal['name']);
                $view_template = $this->inject_substitute($view_template, 'portal', $portal['name']);
                $view_template = $this->inject_substitute($view_template, 'ucname', $ucname);
                $view_template = $this->inject_substitute($view_template, 'title', $this->_config['site_title']);
                $view_template = $this->inject_substitute($view_template, 'model', $portal['model']);

                $this->_render_list["../release/application/views/{$ucname}/Forgot.php"] = $view_template;
            }

            if (isset($portal['reset']) && $portal['reset'] == TRUE)
            {
                $template = file_get_contents('../mkdcore/source/auth/reset_controller.php');
                $template = $this->inject_substitute($template, 'subclass_prefix', $this->_config['subclass_prefix']);
                $template = $this->inject_substitute($template, 'name', $portal['name']);
                $template = $this->inject_substitute($template, 'model', $portal['model']);
                $template = $this->inject_substitute($template, 'ucname', $ucname);
                $template = $this->inject_substitute($template, 'valid_roles', $portal['role']);
                $template = $this->inject_substitute($template, 'role', $portal['role']);
                $this->_render_list["../release/application/controllers/{$ucname}/{$ucname}_reset_controller.php"] = $template;

                if ($portal['api_auth'])
                {
                    $api_template = file_get_contents("../mkdcore/source/api_auth/reset_api_controller.php");
                    $api_template = $this->inject_substitute($api_template, 'subclass_prefix', $this->_config['subclass_prefix']);
                    $api_template = $this->inject_substitute($api_template, 'name', $portal['name']);
                    $api_template = $this->inject_substitute($api_template, 'model', $portal['model']);
                    $api_template = $this->inject_substitute($api_template, 'ucname', $ucname);
                    $api_template = $this->inject_substitute($api_template, 'role', $portal['role']);
                    $this->_render_list["../release/application/controllers/{$ucname}/{$ucname}_reset_api_controller.php"] = $api_template;
                }

                $view_template = file_get_contents('../mkdcore/source/auth/reset.php');
                $view_template = $this->inject_substitute($view_template, 'subclass_prefix', $this->_config['subclass_prefix']);
                $view_template = $this->inject_substitute($view_template, 'name', $portal['name']);
                $view_template = $this->inject_substitute($view_template, 'portal', $portal['name']);
                $view_template = $this->inject_substitute($view_template, 'ucname', $ucname);
                $view_template = $this->inject_substitute($view_template, 'title', $this->_config['site_title']);
                $view_template = $this->inject_substitute($view_template, 'model', $portal['model']);

                $this->_render_list["../release/application/views/{$ucname}/Reset.php"] = $view_template;
            }
            //build layouts
            $layout_header_template = file_get_contents('../mkdcore/source/portal/LayoutHeader.php');
            $layout_header_template = $this->inject_substitute($layout_header_template, 'subclass_prefix', $this->_config['subclass_prefix']);
            $layout_header_template = $this->inject_substitute($layout_header_template, 'copyright', "<?php echo \$setting['copyright'];?>");
            $layout_header_template = $this->inject_substitute($layout_header_template, 'powered_by', $this->_config['powered_by']);
            $layout_header_template = $this->inject_substitute($layout_header_template, 'company', $this->_config['company']);
            $layout_header_template = $this->inject_substitute($layout_header_template, 'menu', $this->_generate_menu($portal));
            $layout_header_template = $this->inject_substitute($layout_header_template, 'name', $portal['name']);
            $layout_header_template = $this->inject_substitute($layout_header_template, 'portal', $portal['name']);
            $layout_header_template = $this->inject_substitute($layout_header_template, 'ucname', $ucname);
            $layout_header_template = $this->inject_substitute($layout_header_template, 'title', $this->_config['site_title']);
            $layout_header_template = $this->inject_substitute($layout_header_template, 'model', $portal['model']);
            $layout_header_template = $this->inject_substitute($layout_header_template, 'css', $this->_generate_css($portal['css'], $portal['name']));

            $this->_render_list["../release/application/views/Layout/{$ucname}Header.php"] = $layout_header_template;
            $layout_footer_template = file_get_contents('../mkdcore/source/portal/LayoutFooter.php');
            $layout_footer_template = $this->inject_substitute($layout_footer_template, 'js', $this->_generate_js($portal['js'], $portal['name']));
            $this->_render_list["../release/application/views/Layout/{$ucname}Footer.php"] = $layout_footer_template;

            //build dashboard
            $dashboard_template = file_get_contents('../mkdcore/source/portal/dashboard_controller.php');
            $dashboard_template = $this->inject_substitute($dashboard_template, 'subclass_prefix', $this->_config['subclass_prefix']);
            $dashboard_template = $this->inject_substitute($dashboard_template, 'name', $portal['name']);
            $dashboard_template = $this->inject_substitute($dashboard_template, 'portal', $portal['name']);
            $dashboard_template = $this->inject_substitute($dashboard_template, 'ucname', $ucname);

            $this->_render_list["../release/application/controllers/{$ucname}/{$ucname}_dashboard_controller.php"] = $dashboard_template;
            $this->_render_list["../release/application/views/{$ucname}/Dashboard.php"] = $this->inject_substitute(file_get_contents('../mkdcore/source/portal/dashboard.php'), 'qwertyui', '');
        }

        if ($this->_config['mode'] == 'production')
        {
            foreach ($this->_portal as $portal)
            {
                foreach ($portal['js'] as $file)
                {
                    if (file_exists($this->_generate_asset_file_name($file)))
                    {
                        unlink($this->_generate_asset_file_name($file));
                    }
                }
                foreach ($portal['css'] as $file)
                {
                    if (file_exists($this->_generate_asset_file_name($file)))
                    {
                        unlink($this->_generate_asset_file_name($file));
                    }
                }
            }
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

        foreach ($this->_portal as $portal)
        {
            $name = $portal['name'];
            if (file_exists($this->_generate_asset_file_name("/assets/css/{$name}.css")))
            {
                unlink($this->_generate_asset_file_name("/assets/css/{$name}.css"));
            }
            if (file_exists($this->_generate_asset_file_name("/assets/js/{$name}.js")))
            {
                unlink($this->_generate_asset_file_name("/assets/js/{$name}.js"));
            }
        }
    }

    private function _generate_menu($portal)
    {
        $menu_html = '';
        foreach ($portal['menu'] as $key => $value) {
            if (is_string($value))
            {
                $menu_html .= "\t\t\t<li><a href='/{$portal['name']}{$value}' class='<?php echo (\$page_name == '{$key}') ? 'list-group-item list-group-item-action d-flex align-items-center p-4 border-0 c-active': 'list-group-item list-group-item-action d-flex align-items-center p-4 border-0';?>'><p class='paragraphText mb-0 text-white d-none d-md-block'>{$key}</p></a></li>\n";
            }
            else
            {
                /**
                 * Steps:
                 * 1. Get all Keys
                 * 2. Get all values
                 * 3. Loop keys to make top level
                 * 4. Save top level
                 * 5. Loop values
                 * 6. Save values
                 * 7. Save last Step
                 */
                $menu_html .= "\t\t\t<li ";
                $value_keys = array_keys($value);
                $condition = [];
                foreach ($value_keys as $single_value_key)
                {
                    $condition[] = "\$page_name == '{$single_value_key}'";
                }
                $condition_str = implode(' || ', $condition);
                $id = md5(uniqid());
                $menu_html .= "class='<?php echo ($condition_str) ? \"active\" :\"\";?>' >\n";
                $menu_html .= "\t\t\t\t<a href='#{$id}' data-toggle='collapse' aria-expanded='false' class='dropdown-toggle list-group-item list-group-item-action d-flex align-items-center p-4 border-0 '><p class='paragraphText mb-0 text-white d-none d-md-block'>{$key}<p></a>\n";
                $menu_html .= "\t\t\t\t\t<ul class='collapse list-unstyled <?php echo ($condition_str) ? \"show\" :\"\";?>' id='{$id}'>\n";

                foreach ($value as $sub_level_key => $sub_level_value)
                {
                    $menu_html .= "\t\t\t\t\t\t<li><a href='/{$portal['name']}{$sub_level_value}' class='<?php echo (\$page_name == '{$sub_level_key}') ? 'active': '';?>'>{$sub_level_key}</a></li>\n";
                }
                $menu_html .= "\t\t\t\t\t</ul>\n";
                $menu_html .= "\t\t\t</li>\n";
            }
        }
        return $menu_html;
    }

    private function _generate_css ($css_list, $portal)
    {
        $css_html = '';
        $compiled_css = '';
        $compiled_css_file_name = "/assets/css/{$portal}.css";
        if ($this->_config['mode'] == 'production')
        {
            foreach ($css_list as $file)
            {
                $file = $this->_generate_asset_file_name($file);
                $compiled_css .= file_get_contents($file);
            }
            file_put_contents($this->_generate_asset_file_name($compiled_css_file_name), $compiled_css);
            return "\t<link rel=\"stylesheet\" href=\"{$compiled_css_file_name}\"/>\n";
        }

        foreach ($css_list as $file)
        {
            $css_html .= "\t<link rel=\"stylesheet\" href=\"{$file}\"/>\n";
        }

        return $css_html;
    }

    private function _generate_js ($js_list, $portal)
    {
        $js_html = '';
        $compiled_js = '';
        $compiled_js_file_name = "/assets/js/{$portal}.js";
        if ($this->_config['mode'] == 'production')
        {
            foreach ($js_list as $file)
            {
                $file = $this->_generate_asset_file_name($file);
                $compiled_js .= file_get_contents($file);
            }
            file_put_contents($this->_generate_asset_file_name($compiled_js_file_name), $compiled_js);
            return "\t<script src=\"{$compiled_js_file_name}\"></script>\n";
        }

        foreach ($js_list as $file)
        {
            $js_html .= "\t<script src=\"{$file}\"></script>\n";
        }

        return $js_html;
    }

    private function generate_view_model_fields($profile_fields)
    {
        $output = "";

        for($i = 0; $i < count($profile_fields); $i++)
        {
            $output .=  'protected $_' . $profile_fields[$i] . ";\n\t";
        }
        return $output;
    }

    private function set_model_method($profile_fields)
    {
        $output = "";
        for($i = 0; $i < count($profile_fields); $i++)
        {
            $output .=  ' $this->_'. $profile_fields[$i] .' = $model->'. $profile_fields[$i] .';'. "\n\t\t";
        }
        return $output;
    }

    private function generate_edit_params($profile_fields)
    {
        $output = "";
        for($i = 0; $i < count($profile_fields); $i++)
        {
            $output.=  "'{$profile_fields[$i]}' => $". $profile_fields[$i] ." , \n\t\t\t";
        }
        return $output;
    }

    private function generate_post_params($profile_fields)
    {
        $output = "";
        for($i = 0; $i < count($profile_fields); $i++)
        {
            $output.=  '$'. $profile_fields[$i]  . ' = $this->input->post("'. $profile_fields[$i] .'");' . "\n\t\t";
        }
        return $output;
    }

    private function generate_view_model_methods($profile_fields)
    {
        $output = "";
        for($i = 0; $i < count($profile_fields); $i++)
        {
            $output .= "\n\tpublic function get_{$profile_fields[$i]} ()\n\t{\n\t\treturn ".'$this->_' ."$profile_fields[$i];\n\t}\n";
        }

        for($i=0;$i < count($profile_fields); $i++ )
        {
            $output .= "\n\tpublic function set_{$profile_fields[$i]} (". '$'.$profile_fields[$i] .")\n\t{\n\t\t " . '$this->_'. $profile_fields[$i] .' = $'.  $profile_fields[$i] . ";\n\t}\n";
        }

        return $output;
    }


    private function generate_form_controls($profile_fields)
    {
        $output = "";

        for($i = 0; $i < count($profile_fields); $i++)
        {
            $heading = ucwords(str_replace('_', ' ', $profile_fields[$i]));
            $input_type = ($profile_fields[$i] === 'password' ? 'password' :'text');

            if($input_type === 'password')
            {
                $output .= "<div class='form-group'>\n\t
                <label for='{$profile_fields[$i]}'>xyz{$heading}</label>\n\t
                <input type='{$input_type}' class='form-control' id='form_{$profile_fields[$i]}' name='{$profile_fields[$i]}' />\n\t
                </div>";
            }
            else
            {
                $output .= "<div class='form-group'>\n\t
                <label for='{$profile_fields[$i]}'>xyz{$heading}</label>\n\t
                <input type='{$input_type}' class='form-control' id='form_{$profile_fields[$i]}' name='{$profile_fields[$i]}' value='<?php echo set_value('{$profile_fields[$i]}',". ' $this->_data' ."['view_model']->get_{$profile_fields[$i]}());?>'/>\n\t
                </div>";
            }

        }
        return $output;

    }

    private function _generate_asset_file_name ($file)
    {
        if (substr($file,0,1) == '/')
        {
            return dirname(__FILE__) . '/../..' . $file;
        }

        return dirname(__FILE__) . '/../../' . $file;
    }

    private function process_middleware ($middleware)
    {
        $result = [];
        foreach ($middleware as $key => $value) {
            $result[] = "'{$value}'";
        }
        return implode(', ', $result);
    }

    private function _remove_dir($path)
    {
        $files = glob($path . '/*');
        foreach ($files as $file)
        {
            is_dir($file) ? removeDirectory($file) : unlink($file);
        }
        rmdir($path);

        return;
    }

}