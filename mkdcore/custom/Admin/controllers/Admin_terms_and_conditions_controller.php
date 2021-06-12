<?php defined('BASEPATH') OR exit('No direct script access allowed');
include_once 'Admin_controller.php';
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
/**
 * TermsAndConditions Controller
 * @copyright 2019 Manaknightdigital Inc.
 * @link https://manaknightdigital.com
 * @license Proprietary Software licensing
 * @author Ryan Wong
 *
 */
class Admin_terms_and_conditions_controller extends Admin_controller
{
    protected $_model_file = 'terms_and_conditions_model';
    public $_page_name = 'Terms And Conditions';

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
			return redirect('/admin/terms_and_conditions');
        }

        include_once __DIR__ . '/../../view_models/TermsAndConditions_admin_edit_view_model.php';
        $this->form_validation = $this->terms_and_conditions_model->set_form_validation(
        $this->form_validation, $this->terms_and_conditions_model->get_all_edit_validation_rule());
        $this->_data['view_model'] = new TermsAndConditions_admin_edit_view_model($this->terms_and_conditions_model);
        $this->_data['view_model']->set_model($model);
        $this->_data['view_model']->set_heading('Terms And Conditions');
        
        
		if ($this->form_validation->run() === FALSE)
		{
			return $this->render('Admin/TermsAndConditionsEdit', $this->_data);
        }

        $terms_and_condition_text = $this->input->post('terms_and_condition_text', TRUE);
		
        $result = $this->terms_and_conditions_model->edit([
            'terms_and_condition_text' => $terms_and_condition_text,
			
        ], $id);

        if ($result)
        { 
            $this->success('Success! Terms and conditions has been updated.');
            return redirect($_SERVER['HTTP_REFERER']);
        }

        $this->_data['error'] = 'Error';
        return $this->render('Admin/TermsAndConditionsEdit', $this->_data);
	}

    

    
    
    
    
    
    
    
    
}