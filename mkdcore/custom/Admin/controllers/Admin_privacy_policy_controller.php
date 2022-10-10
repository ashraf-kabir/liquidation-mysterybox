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
class Admin_privacy_policy_controller extends Admin_controller
{
    protected $_model_file = 'terms_and_conditions_model';
    public $_page_name = 'About Us';

    public function __construct()
    {
        parent::__construct();
        
        
        
    }

    

    

    

    	public function edit()
	{
        $id = 1;
        $model = $this->terms_and_conditions_model->get($id);
        $session = $this->get_session();
		if (!$model)
		{
			$this->error('Error');
			return redirect('/admin/dashboard');
        }

        include_once __DIR__ . '/../../view_models/PrivacyPolicy_admin_edit_view_model.php';
        $this->form_validation = $this->terms_and_conditions_model->set_form_validation(
        $this->form_validation, $this->terms_and_conditions_model->get_all_edit_validation_rule());
        $this->_data['view_model'] = new PrivacyPolicy_admin_edit_view_model($this->terms_and_conditions_model);
        $this->_data['view_model']->set_model($model);
        $this->_data['view_model']->set_heading('Privacy Policy');
        $this->_data['page_name'] = 'Privacy Policy';
        $this->_data['privacy_policy'] = $model->privacy_policy;
        

        $this->form_validation->set_rules('privacy_policy','Privacy Policy','required');
        
		if ($this->form_validation->run() === FALSE)
		{
			return $this->render('Admin/PrivacyPolicyEdit', $this->_data);
        }

        $privacy_policy = $this->input->post('privacy_policy', TRUE);
		
        $result = $this->terms_and_conditions_model->edit([
            'privacy_policy' => $privacy_policy,
			
        ], $id);

        if ($result)
        {
            
            
            return $this->redirect('/admin/privacy_policy/edit', 'refresh');
        }

        $this->_data['error'] = 'Error';
        return $this->render('Admin/PrivacyPolicyEdit', $this->_data);
	}

    

    
    
    
    
    
    
    
    
}