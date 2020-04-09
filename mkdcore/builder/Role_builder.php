<?php
include_once 'Builder.php';
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
class Role_builder extends Builder
{
    protected $_roles;
    protected $_file_path = '';
    protected $_render_list = [];
    protected $_routes = [];
    protected $_models = [];
    protected $_controllers = [];
    protected $_packages = [];
    protected $_migration = [];
    protected $_libraries = [];

    public function __construct($roles, $locale)
    {
        $this->_roles = $roles;

        $this->_template = '';
        $this->_locale = $locale;
    }

    public function get_role()
    {
        return $this->_roles;
    }

    public function build()
    {
        foreach ($this->_roles as $role)
        {
            if (!file_exists('../release/application/controllers/' . ucfirst($role['name'])))
            {
                mkdir('../release/application/controllers/' . ucfirst($role['name']));
            }

            if (!file_exists('../release/application/views/' . ucfirst($role['name'])))
            {
                mkdir('../release/application/views/' . ucfirst($role['name']));
            }
        }
    }

    public function destroy ()
    {
        foreach ($this->_roles as $role)
        {
            if (file_exists('../release/application/controllers/' . ucfirst($role['name'])))
            {
                $this->_remove_dir('../release/application/controllers/' . ucfirst($role['name']));
            }

            if (file_exists('../release/application/views/' . ucfirst($role['name'])))
            {
                $this->_remove_dir('../release/application/views/' . ucfirst($role['name']));
            }
        }
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