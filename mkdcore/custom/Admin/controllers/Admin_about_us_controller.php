<?php defined('BASEPATH') OR exit('No direct script access allowed');
include_once 'Admin_controller.php';
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
/**
 * AboutUs Controller
 * @copyright 2019 Manaknightdigital Inc.
 * @link https://manaknightdigital.com
 * @license Proprietary Software licensing
 * @author Ryan Wong
 *
 */
class Admin_about_us_controller extends Admin_controller
{
    protected $_model_file = 'terms_and_conditions_model';
    public $_page_name = 'About Us';

    public function __construct()
    {
        parent::__construct();
        
        
        
    }

    

    

    

    	public function edit($id)
	{
        $id = 1;
        $model = $this->terms_and_conditions_model->get($id);
        $session = $this->get_session();
		if (!$model)
		{
			$this->error('Error');
			return redirect('/admin/dashboard');
        }

        include_once __DIR__ . '/../../view_models/AboutUs_admin_edit_view_model.php';
        $this->form_validation = $this->terms_and_conditions_model->set_form_validation(
        $this->form_validation, $this->terms_and_conditions_model->get_all_edit_validation_rule());
        $this->_data['view_model'] = new AboutUs_admin_edit_view_model($this->terms_and_conditions_model);
        $this->_data['view_model']->set_model($model);
        $this->_data['view_model']->set_heading('About Us');
        

        $this->form_validation->set_rules('about_us_page','About Us','required');
        
		if ($this->form_validation->run() === FALSE)
		{
			return $this->render('Admin/AboutUsEdit', $this->_data);
        }

        $about_us_page = $this->input->post('about_us_page', TRUE);
		
        $result = $this->terms_and_conditions_model->edit([
            'about_us_page' => $about_us_page,
			
        ], $id);

        if ($result)
        {
            
            
            return $this->redirect('/admin/about_us/edit', 'refresh');
        }

        $this->_data['error'] = 'Error';
        return $this->render('Admin/AboutUsEdit', $this->_data);
	}

    

    
    
    
    
    
    
    
    
}