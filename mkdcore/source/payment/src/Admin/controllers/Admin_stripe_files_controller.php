<?php defined('BASEPATH') OR exit('No direct script access allowed');
include_once 'Admin_controller.php';
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
/**
 * Stripe_files Controller
 * @copyright 2019 Manaknightdigital Inc.
 * @link https://manaknightdigital.com
 * @license Proprietary Software licensing
 * @author Ryan Wong
 *
 */
class Admin_stripe_files_controller extends Admin_controller
{
    protected $_model_file = 'stripe_files_model';
    public $_page_name = 'xyzStripe Files';

    public function __construct()
    {
        parent::__construct();
        $stripe_config = [
            'stripe_api_version' => ($this->config->item('stripe_api_version') ?? ''),
            'stripe_publish_key' => ($this->config->item('stripe_publish_key') ?? ''),
            'stripe_secret_key' => ($this->config->item('stripe_secret_key') ?? '')
        ];
        $this->load->library('payment_service', $stripe_config);
    }

	public function index($page)
	{
        $this->load->library('pagination');
        include_once __DIR__ . '/../../view_models/Stripe_files_admin_list_paginate_view_model.php';
        $session = $this->get_session();
        $format = $this->input->get('format', TRUE) ?? 'view';
        $order_by = $this->input->get('order_by', TRUE) ?? '';
        $direction = $this->input->get('direction', TRUE) ?? 'ASC';

        $this->_data['view_model'] = new Stripe_files_admin_list_paginate_view_model($this->stripe_files_model,$this->pagination,'/admin/stripe_files/0');
        $this->_data['view_model']->set_heading('xyzStripe Files');
        $this->_data['view_model']->set_created_at(($this->input->get('created_at', TRUE) != NULL) ? $this->input->get('created_at', TRUE) : NULL);
		$this->_data['view_model']->set_stripe_id(($this->input->get('stripe_id', TRUE) != NULL) ? $this->input->get('stripe_id', TRUE) : NULL);
		
        $where = [
            'created_at' => $this->_data['view_model']->get_created_at(),
			'stripe_id' => $this->_data['view_model']->get_stripe_id(),
			
        ];

        $this->_data['view_model']->set_total_rows($this->stripe_files_model->count($where));

        $this->_data['view_model']->set_format_layout($this->_data['layout_clean_mode']);
        $this->_data['view_model']->set_per_page(25);
        $this->_data['view_model']->set_order_by($order_by);
        $this->_data['view_model']->set_sort($direction);
        $this->_data['view_model']->set_sort_base_url('/admin/stripe_files/0');
        $this->_data['view_model']->set_page($page);
		$this->_data['view_model']->set_list($this->stripe_files_model->get_paginated($this->_data['view_model']->get_page(),$this->_data['view_model']->get_per_page(),$where,$order_by,$direction));

        if ($format != 'view')
        {
            return $this->output->set_content_type('application/json')->set_status_header(200)->set_output(json_encode($this->_data['view_model']->to_json()));
        }

        return $this->render('Admin/Stripe_files', $this->_data);
	}

    public function add()
	{
        include_once __DIR__ . '/../../view_models/Stripe_files_admin_add_view_model.php';
        $this->form_validation = $this->stripe_files_model->set_form_validation(
        $this->form_validation, $this->stripe_files_model->get_all_validation_rule());
        $this->_data['view_model'] = new Stripe_files_admin_add_view_model($this->stripe_files_model);
        $this->_data['view_model']->set_heading('xyzStripe Files');
        
        
		if ($this->form_validation->run() === FALSE)
		{
			return $this->render('Admin/Stripe_filesAdd', $this->_data);
        }

        $purpose = $this->input->post('purpose');
		$local_file = $this->input->post('local_file');
        
        $file = $this->_upload();

        if(empty($file))
        {
            $this->_data['error'] = 'xyzError Uploading File';
            return $this->render('Admin/Stripe_filesAdd', $this->_data);
        }

        if($file['error'] == TRUE)
        {
            $this->_data['error'] = $file['data'];
            return $this->render('Admin/Stripe_filesAdd', $this->_data);
        }

        try
        {
            $file_params = [
                'path' => $file['data']['full_path'],
                'purpose' => $this->_data['view_model']->purpose_mapping()[$purpose]
            ];   
            $stripe_file = $this->payment_service->create_file($file_params);
        }
        catch(Exception $e)
        {
            $this->_data['error'] = $e->getMessage();
            return $this->render('Admin/Stripe_filesAdd', $this->_data);
        }

        if(isset($stripe_file['id']))
        {
            $params = [
                'purpose' => $purpose,
                'local_file' => './uploads/' . $file['data']['file_name'],
                'stripe_id' => $stripe_file['id'],
                'url' => $stripe_file['url']
            ];
            
            $result = $this->stripe_files_model->create($params);
            $this->success('File saved');

            if ($result)
            { 
                return $this->redirect('/admin/stripe_files/0', 'refresh');
            }
    
        }

        $this->_data['error'] = 'xyzError';
        return $this->render('Admin/Stripe_filesAdd', $this->_data);
	}

	public function edit($id)
	{
        $model = $this->stripe_files_model->get($id);

		if (!$model)
		{
			$this->error('Error');
			return redirect('/admin/stripe_files/0');
        }

        include_once __DIR__ . '/../../view_models/Stripe_files_admin_edit_view_model.php';
        $this->form_validation = $this->stripe_files_model->set_form_validation(
        $this->form_validation, $this->stripe_files_model->get_all_edit_validation_rule());
        $this->_data['view_model'] = new Stripe_files_admin_edit_view_model($this->stripe_files_model);
        $this->_data['view_model']->set_model($model);
        $this->_data['view_model']->set_heading('xyzStripe Files');
        
        
		if ($this->form_validation->run() === FALSE)
		{
			return $this->render('Admin/Stripe_filesEdit', $this->_data);
        }

        $purpose = $this->input->post('purpose');
		$local_file = $this->input->post('local_file');
		$local_file_id = $this->input->post('local_file_id');
		
        $result = $this->stripe_files_model->edit([
            'purpose' => $purpose,
			'local_file' => $local_file,
			'local_file_id' => $local_file_id,
			
        ], $id);

        if ($result)
        {  
            return $this->redirect('/admin/stripe_files/0', 'refresh');
        }

        $this->_data['error'] = 'xyzError';
        return $this->render('Admin/Stripe_filesEdit', $this->_data);
	}

	public function view($id)
	{
        $model = $this->stripe_files_model->get($id);

		if (!$model)
		{
			$this->error('Error');
			return redirect('/admin/stripe_files/0');
		}


        include_once __DIR__ . '/../../view_models/Stripe_files_admin_view_view_model.php';
		$this->_data['view_model'] = new Stripe_files_admin_view_view_model($this->stripe_files_model);
		$this->_data['view_model']->set_heading('xyzStripe Files');
        $this->_data['view_model']->set_model($model);
        
		return $this->render('Admin/Stripe_filesView', $this->_data);
    }
    


    public function _upload()
    {
        $config['upload_path']   = './uploads/';
        $config['allowed_types'] = 'gif|png|jpg|csv|ppt|xls|xlsm|xlsx|docx|doc|pdf|txt';
        $config['max_size']      = 1000000;
        $config['max_width']     = 5024;
        $config['max_height']    = 5024;
        $this->load->library('upload', $config);
        
        if ( ! $this->upload->do_upload('local_file'))
        {
            return [
                'error' => TRUE,
                'data' => $this->upload->display_errors()
            ];
        }
        else
        {
            return [
                'error' => FALSE,
                'data' => $this->upload->data()
            ];   
        }

        return [];
    }
}