<?php
include_once 'Builder.php';
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
class Skeleton_builder extends Builder
{
    protected $_config;
    protected $_dynamic_config;
    protected $_template;
    protected $_file_path = '';
    protected $_render_list = [];
    protected $_routes = [];
    protected $_models = [];
    protected $_controllers = [];
    protected $_packages = [];
    protected $_migration = [];
    protected $_libraries = [];
    protected $_middlewares = [];
    protected $_bad_artifacts = [
        "src/application/core/Controller.php",
        "src/application/core/Form_validation.php",
        "src/application/core/Model.php",
        "src/application/core/Input.php",
    ];

    public function __construct($config, $locale)
    {
        $this->_config = $config;
        $this->_libraries = [
            'src/application/libraries/Mime_service.php',
            'src/application/libraries/Mail_service.php',
            'src/application/libraries/Sms_service.php',
            'src/application/libraries/Cache_service.php',
            'src/application/libraries/Push_notification_service.php',
            'src/application/libraries/View_helper.php'
        ];
        $this->_models = [
        ];

        $this->_middlewares = [
            'src/application/middlewares/Auth_middleware.php',
            'src/application/middlewares/Acl_middleware.php',
            'src/application/middlewares/Maintenance_middleware.php',
            'src/application/middlewares/Token_middleware.php',
            'src/application/middlewares/Token_acl_middleware.php',
            'src/application/middlewares/Affilate_middleware.php'
        ];

        $this->_controllers = [
            'src/application/controllers/Health_check_controller.php',
            'src/application/controllers/Guest/Guest_controller.php',
            'src/application/controllers/Welcome.php'
        ];
        $this->_template = '';
        $this->_locale = $locale;
    }

    public function get_route ()
    {
        return [
            'health_check' => 'Health_check_controller/index'
        ];
    }

    public function build()
    {
        return $this->_template;
    }

    public function inject_template ()
    {
        foreach ($this->_libraries as $key => $value)
        {
            $template = file_get_contents(str_replace('src/application/libraries/', 'templates/source/core/', $value));
            $template = $this->inject_substitute($template, 'subclass_prefix', $this->_config['subclass_prefix']);
            file_put_contents($value, $template);
        }

        foreach ($this->_middlewares as $key => $value)
        {
            $template = file_get_contents(str_replace('src/application/middlewares/', 'templates/source/middleware/', $value));
            $template = $this->inject_substitute($template, 'subclass_prefix', $this->_config['subclass_prefix']);
            file_put_contents($value, $template);
        }

        foreach ($this->_models as $key => $value)
        {
            $template = file_get_contents(str_replace('src/application/models/', 'templates/source/', $value));
            $template = $this->inject_substitute($template, 'subclass_prefix', $this->_config['subclass_prefix']);
            file_put_contents($value, $template);
        }

        foreach ($this->_controllers as $key => $value)
        {
            $template = file_get_contents(str_replace('src/application/controllers/', 'templates/source/core/', $value));
            $template = $this->inject_substitute($template, 'subclass_prefix', $this->_config['subclass_prefix']);
            file_put_contents($value, $template);
        }

        $template = file_get_contents('templates/source/core/Manaknight_Controller.php');
        $template = $this->inject_substitute($template, 'subclass_prefix', $this->_config['subclass_prefix']);

        file_put_contents("src/application/core/{$this->_config['subclass_prefix']}Controller.php", $template);

        $form_template = file_get_contents('templates/source/core/Manaknight_Form_validation.php');
        $form_template = $this->inject_substitute($form_template, 'subclass_prefix', $this->_config['subclass_prefix']);
        file_put_contents("src/application/core/{$this->_config['subclass_prefix']}Form_validation.php", $form_template);

        $model_template = file_get_contents('templates/source/model/Manaknight_Model.php');
        $model_template = $this->inject_substitute($model_template, 'subclass_prefix', $this->_config['subclass_prefix']);
        file_put_contents("src/application/core/{$this->_config['subclass_prefix']}Model.php", $model_template);

        $input_template = file_get_contents('templates/source/core/Manaknight_Input.php');
        $input_template = $this->inject_substitute($input_template, 'subclass_prefix', $this->_config['subclass_prefix']);
        file_put_contents("src/application/core/{$this->_config['subclass_prefix']}Input.php", $input_template);

        $migration_template = file_get_contents('templates/source/cronjob/Migration_cron_controller.php');
        file_put_contents('src/application/controllers/Cli/Migration_cron_controller.php', $migration_template);

        // $migration_config_template = file_get_contents('templates/source/core/migration.php');
        // $migration_config_template = $this->inject_substitute($migration_config_template, 'migration_number', $this->_config['migration_number']);
        // file_put_contents('src/application/config/migration.php', $migration_config_template);

        $mapping_template = file_get_contents('templates/source/core/Mapping.php');
        file_put_contents('src/application/core/Mapping.php', $mapping_template);
        $index_template = file_get_contents('templates/source/core/index.php');
        file_put_contents('index.php', $index_template);

        foreach ($this->_bad_artifacts as $key => $value)
        {
            if (file_exists($value))
            {
                unlink($value);
            }
        }
    }

    public function destroy ()
    {
        foreach ($this->_libraries as $key => $value)
        {
            if (file_exists($value))
            {
                unlink($value);
            }
        }

        foreach ($this->_models as $key => $value)
        {
            if (file_exists($value))
            {
                unlink($value);
            }
        }

        $do_not_remove_list = [
            'src/application/controllers/Guest/Guest_controller.php',
            'src/application/controllers/Welcome.php'
        ];
        foreach ($this->_controllers as $key => $value)
        {
            if (file_exists($value) && !in_array($value, $do_not_remove_list))
            {
                unlink($value);
            }
        }

        foreach ($this->_middlewares as $key => $value)
        {
            if (file_exists($value))
            {
                unlink($value);
            }
        }

        $files = [
            "src/application/core/{$this->_config['subclass_prefix']}Controller.php",
            "src/application/core/{$this->_config['subclass_prefix']}Form_validation.php",
            "src/application/core/{$this->_config['subclass_prefix']}Model.php",
            "src/application/core/{$this->_config['subclass_prefix']}Input.php",
            'src/application/core/Mapping.php',
            'src/system/core/Cache.php'
        ];

        foreach ($files as $key => $value)
        {
            if (file_exists($value))
            {
                unlink($value);
            }
        }


    }
}