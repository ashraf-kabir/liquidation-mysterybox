<?php
include_once 'Builder.php';
include_once 'Config_builder.php';
include_once 'Role_builder.php';
include_once 'Portal_builder.php';
include_once 'Skeleton_builder.php';
include_once 'Model_builder.php';
include_once 'Lang_builder.php';
include_once 'User_module_builder.php';
include_once 'Controller_builder.php';
include_once 'Route_builder.php';
include_once 'Database_builder.php';
include_once 'Phinx_builder.php';
include_once 'Powerby_builder.php';
include_once 'Copy_builder.php';
include_once 'Cronjob_builder.php';
include_once 'Setting_builder.php';
include_once 'License_builder.php';
include_once 'Killcode_builder.php';
include_once 'Remove_kill_code_builder.php';
include_once 'Report_builder.php';
include_once 'Image_builder.php';
include_once 'Marketing_builder.php';
include_once 'Package_builder.php';
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
class App_builder extends Builder
{
    protected $_config;
    protected $_render_list = [];
    protected $_roles = [];
    protected $_routes = [];
    protected $_menu = [];
    protected $_models = [];
    protected $_controllers = [];
    protected $_packages = [];
    protected $_migration = [];
    protected $_libraries = [];
    protected $_translations = [];
    protected $_modular_packages = [];

    public function __construct($config, $locale=false)
    {
        $this->_config = json_decode($config, TRUE);
        $this->_locale = $locale;
    }

    /**
     * @param void
     * function simply copies content of configuration.json in the package folder
     * we need to maintain a strong naming conversion is source directory must match name defined in configuration.json
     */
    private function init_modular_packages()
    {
        foreach($this->_modular_packages as $package)
        {
            $package_config = json_decode(file_get_contents("../mkdcore/source/{$package['name']}/configuration.json"), TRUE);
            $config_items = array_keys($package_config);

            for($i = 0; $i < count( $config_items); $i ++ )
            {
                if(isset( $this->_config[$config_items[$i]]))
                {
                    $this->_config[$config_items[$i]] = array_merge($this->_config[$config_items[$i]], $package_config[$config_items[$i]]);
                }
            }
            //build the menus
            for($i = 0; $i < count($this->_config['portals']); $i++)
            {
                if(isset($package_config['menus'][$this->_config['portals'][$i]['name']]))
                {
                    $menu_1 = array_slice($this->_config['portals'][$i]['menu'], 0, 1, true);
                    $menu_2 = array_slice($this->_config['portals'][$i]['menu'], 1, count($this->_config['portals'][$i]['menu']) - 1, true);
                    $this->_config['portals'][$i]['menu'] = array_merge($menu_1, $package_config['menus'][$this->_config['portals'][$i]['name']], $menu_2);
                }
            }
        }
    }

    public function init()
    {
        // if ($this->_config['locale'])
        // {
        //     $lang_builder = new Lang_builder($this->_config, $this->_config['locale']);
        //     $lang_builder->set_language($this->_config['language']);
        //     $lang_builder->set_translation($this->_config['translations']);
        //     $this->_locale = true;
        //     $this->_render_list[] = $lang_builder;
        // }

        foreach ($this->_config['packages'] as $package_type => $package)
        {
            if(!empty($package) && is_array($package) && $package['is_active'] == TRUE )
            {
                $this->_modular_packages[] = [
                    'name' => $package_type,
                    'settings' =>  $package
                ];
            }
        }

        if(file_exists("translations.json"))
        {
            $local_config = json_decode(file_get_contents("translations.json"), TRUE);
            $this->_config['translations'] = $local_config['translations'];
        }

        if(file_exists("env.json"))
        {
            $local_config = json_decode(file_get_contents("env.json"), TRUE);
            $this->_config['config'] = $local_config['config'];
            $this->_config['database'] =  $local_config['database'];
        }

        $this->init_modular_packages();


        $this->_routes = $this->_config['routes'];
        $this->_translations = $this->_config['translations'];
        sortKeysDesc($this->_translations);
        $config_builder = new Config_builder($this->_config['config'], $this->_config['locale']);
        $config_builder->set_translate_text($this->_translations);
        if ($this->_config['locale'])
        {
            $config_builder->set_language($this->_config['language']);
        }
        $this->_render_list[] = $config_builder;

        $role_builder = new Role_builder($this->_config['roles'], $this->_config['locale']);
        $role_builder->set_translate_text($this->_translations);
        $this->_roles = $role_builder->get_role();
        $this->_render_list[] = $role_builder;

        $database_builder = new Database_builder($this->_config['database'], $this->_config['locale']);
        $database_builder->set_translate_text($this->_translations);
        $this->_render_list[] = $database_builder;

        $phinx_builder = new Phinx_builder($this->_config['database'], $this->_config['locale']);
        $phinx_builder->set_translate_text($this->_translations);
        $this->_render_list[] = $phinx_builder;

        $package_builder = new Package_builder($this->_config['config'], $this->_config['locale']);
        $package_builder->set_translate_text($this->_translations);
        $package_builder->set_package($this->_config['packages']);
        $this->_routes = array_merge($this->_routes, $package_builder->get_routes());
        $this->_render_list[] = $package_builder;

        $skeleton_builder = new Skeleton_builder($this->_config['config'], $this->_config['locale']);
        $skeleton_builder->set_translate_text($this->_translations);
        $this->_routes = array_merge($this->_routes, $skeleton_builder->get_route());
        $this->_render_list[] = $skeleton_builder;

        $model_builder = new Model_builder($this->_config['config'], $this->_config['locale']);
        $model_builder->set_translate_text($this->_translations);
        $model_builder->set_model($this->_config['models']);
        $this->_render_list[] = $model_builder;

        $marketing_builder = new Marketing_builder($this->_config['config'], $this->_config['locale']);
        $marketing_builder->set_translate_text($this->_translations);
        $marketing_builder->set_marketing($this->_config['marketing']);
        $this->_routes = array_merge($this->_routes, $marketing_builder->get_route());
        $this->_render_list[] = $marketing_builder;

        $user_module_builder = new User_module_builder($this->_config['config'], $this->_config['locale']);
        $user_module_builder->set_translate_text($this->_translations);
        $this->_render_list[] = $user_module_builder;

        $setting_builder = new Setting_builder($this->_config['config'], $this->_config['locale']);
        $setting_builder->set_translate_text($this->_translations);
        $setting_builder->set_model($this->_config['models']);
        $this->_routes = array_merge($this->_routes, $setting_builder->get_route());
        $this->_render_list[] = $setting_builder;

        $report_builder = new Report_builder($this->_config['config'], $this->_config['locale']);
        $report_builder->set_translate_text($this->_translations);
        $report_builder->set_reporting($this->_config['reporting']);
        $this->_routes = array_merge($this->_routes, $report_builder->get_route());
        $this->_render_list[] = $report_builder;

        $image_builder = new Image_builder($this->_config, $this->_config['locale']);
        $image_builder->set_translate_text($this->_translations);
        $this->_routes = array_merge($this->_routes, $image_builder->get_route());
        $this->_render_list[] = $image_builder;

        $portal_builder = new Portal_builder($this->_config['config'], $this->_config['locale']);
        $portal_builder->set_translate_text($this->_translations);
        $portal_builder->set_portal($this->_config['portals']);
        $portal_builder->set_role($this->_roles);
        $this->_routes = array_merge($this->_routes, $portal_builder->get_route());
        $this->_render_list[] = $portal_builder;

        $controller_builder = new Controller_builder($this->_config['config'], $this->_config['locale']);
        $controller_builder->set_translate_text($this->_translations);
        $controller_builder->set_portal($this->_config['portals']);
        $controller_builder->set_controller($this->_config['controllers']);
        $controller_builder->set_role($this->_roles);
        $controller_builder->set_model($this->_config['models']);
        $this->_routes = array_merge($this->_routes, $controller_builder->get_route());
        $this->_render_list[] = $controller_builder;
        sortKeysDesc($this->_routes);
        $route_builder = new Route_builder($this->_config['config'], $this->_config['locale']);
        $route_builder->set_translate_text($this->_translations);
        $route_builder->set_route($this->_routes);
        $this->_render_list[] = $route_builder;

        $cron_builder = new Cronjob_builder($this->_config['config'], $this->_config['locale']);
        $cron_builder->set_translate_text($this->_translations);
        $cron_builder->set_cronjob($this->_config['cronjob']);
        $this->_render_list[] = $cron_builder;

        $copy_builder = new Copy_builder($this->_config['config'], $this->_config['locale']);
        $copy_builder->set_translate_text($this->_translations);
        $copy_builder->set_copy($this->_config['copy']);
        $this->_render_list[] = $copy_builder;


        $powerby_builder = new Powerby_builder($this->_config, $this->_config['locale']);
        $powerby_builder->set_translate_text($this->_translations);
        $this->_render_list[] = $powerby_builder;

        $license_builder = new License_builder($this->_config, $this->_config['locale']);
        $license_builder->set_translate_text($this->_translations);
        $this->_render_list[] = $license_builder;

        $killcode_builder = new Killcode_builder($this->_config, $this->_config['locale']);
        $killcode_builder->set_translate_text($this->_translations);
        $this->_render_list[] = $killcode_builder;

        $remove_kill_code_builder = new Remove_kill_code_builder($this->_config, $this->_config['locale']);
        $remove_kill_code_builder->set_translate_text($this->_translations);
        $this->_render_list[] = $remove_kill_code_builder;
    }

    public function build()
    {
        $this->init();

        foreach ($this->_render_list as $builder) {
            $builder->build();
            $builder->inject_template();
        }
    }

    public function destroy()
    {
        $this->init();

        foreach ($this->_render_list as $builder) {
            $builder->destroy();
        }
    }
}

function sortKeysDesc(&$arrNew)
{
    uksort($arrNew, function($a, $b)
    {
        $lenA = strlen($a); $lenB = strlen($b);
        if($lenA == $lenB)
        {
            // If equal length, sort again by descending
            $arrOrig = array($a, $b);
            $arrSort = $arrOrig;
            rsort($arrSort);
            if($arrOrig[0] !== $arrSort[0]) return 1;
        }
        else
        {
            // If not equal length, simple
            return $lenB - $lenA;
        }
    });
}