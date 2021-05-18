<?php defined('BASEPATH') OR exit('No direct script access allowed');
include_once 'Admin_controller.php';
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
/**
 * TrafficReferrer Controller
 * @copyright 2019 Manaknightdigital Inc.
 * @link https://manaknightdigital.com
 * @license Proprietary Software licensing
 * @author Ryan Wong
 *
 */
class Admin_traffic_referrer_controller extends Admin_controller
{
    protected $_model_file = 'traffic_referrer_model';
    public $_page_name = 'Traffic Referrer';

    public function __construct()
    {
        parent::__construct();
        
        
        
    }

    

    	public function index($page)
	{
        $this->load->library('pagination');
        include_once __DIR__ . '/../../view_models/TrafficReferrer_admin_list_paginate_view_model.php';
        $session = $this->get_session();
        $format = $this->input->get('format', TRUE) ?? 'view';
        $order_by = $this->input->get('order_by', TRUE) ?? '';
        $direction = $this->input->get('direction', TRUE) ?? 'ASC';

        $this->_data['view_model'] = new TrafficReferrer_admin_list_paginate_view_model(
            $this->traffic_referrer_model,
            $this->pagination,
            '/admin/traffic_referrer/0');
        $this->_data['view_model']->set_heading('Traffic Referrer');
        $this->_data['view_model']->set_referrer_name(($this->input->get('referrer_name', TRUE) != NULL) ? $this->input->get('referrer_name', TRUE) : NULL);
		$this->_data['view_model']->set_referrer_key(($this->input->get('referrer_key', TRUE) != NULL) ? $this->input->get('referrer_key', TRUE) : NULL);
		
        $where = [
            'referrer_name' => $this->_data['view_model']->get_referrer_name(),
			'referrer_key' => $this->_data['view_model']->get_referrer_key(),
			
            
        ];

        $this->_data['view_model']->set_total_rows($this->traffic_referrer_model->count($where));

        $this->_data['view_model']->set_format_layout($this->_data['layout_clean_mode']);
        $this->_data['view_model']->set_per_page(25);
        $this->_data['view_model']->set_order_by($order_by);
        $this->_data['view_model']->set_sort($direction);
        $this->_data['view_model']->set_sort_base_url('/admin/traffic_referrer/0');
        $this->_data['view_model']->set_page($page);
		$this->_data['view_model']->set_list($this->traffic_referrer_model->get_paginated(
            $this->_data['view_model']->get_page(),
            $this->_data['view_model']->get_per_page(),
            $where,
            $order_by,
            $direction));

        if ($format == 'csv')
        {
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="export.csv"');

            echo $this->_data['view_model']->to_csv();
            exit();
        }

        if ($format != 'view')
        {
            return $this->output->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode($this->_data['view_model']->to_json()));
        }

        return $this->render('Admin/TrafficReferrer', $this->_data);
	}

    	public function add()
	{
        include_once __DIR__ . '/../../view_models/TrafficReferrer_admin_add_view_model.php';
        $session = $this->get_session();
        $this->form_validation = $this->traffic_referrer_model->set_form_validation(
        $this->form_validation, $this->traffic_referrer_model->get_all_validation_rule());
        $this->_data['view_model'] = new TrafficReferrer_admin_add_view_model($this->traffic_referrer_model);
        $this->_data['view_model']->set_heading('Traffic Referrer');
        

		if ($this->form_validation->run() === FALSE)
		{
			return $this->render('Admin/TrafficReferrerAdd', $this->_data);
        }

        $referrer_name = $this->input->post('referrer_name', TRUE);
		$referrer_key  = $this->input->post('referrer_key', TRUE);
        $referrer_key  = strtolower($referrer_key);
		
        $result = $this->traffic_referrer_model->create([
            'referrer_name' => $referrer_name,
			'referrer_key' => $referrer_key,
			
        ]);

        if ($result)
        {
            
            
            return $this->redirect('/admin/traffic_referrer/0', 'refresh');
        }

        $this->_data['error'] = 'Error';
        return $this->render('Admin/TrafficReferrerAdd', $this->_data);
	}

    	public function edit($id)
	{
        $model = $this->traffic_referrer_model->get($id);
        $session = $this->get_session();
		if (!$model)
		{
			$this->error('Error');
			return redirect('/admin/traffic_referrer/0');
        }

        include_once __DIR__ . '/../../view_models/TrafficReferrer_admin_edit_view_model.php';
        $this->form_validation = $this->traffic_referrer_model->set_form_validation(
        $this->form_validation, $this->traffic_referrer_model->get_all_edit_validation_rule());
        $this->_data['view_model'] = new TrafficReferrer_admin_edit_view_model($this->traffic_referrer_model);
        $this->_data['view_model']->set_model($model);
        $this->_data['view_model']->set_heading('Traffic Referrer');
        
        
		if ($this->form_validation->run() === FALSE)
		{
			return $this->render('Admin/TrafficReferrerEdit', $this->_data);
        }

        $referrer_name = $this->input->post('referrer_name', TRUE);
		$referrer_key  = $this->input->post('referrer_key', TRUE);
		$referrer_key  = strtolower($referrer_key);


        $result = $this->traffic_referrer_model->edit([
            'referrer_name' => $referrer_name,
			'referrer_key'  => $referrer_key, 
        ], $id);

        if ($result)
        {
            
            
            return $this->redirect('/admin/traffic_referrer/0', 'refresh');
        }

        $this->_data['error'] = 'Error';
        return $this->render('Admin/TrafficReferrerEdit', $this->_data);
	}

    	public function view($id)
	{
        $model = $this->traffic_referrer_model->get($id);

		if (!$model)
		{
			$this->error('Error');
			return redirect('/admin/traffic_referrer/0');
		}


        include_once __DIR__ . '/../../view_models/TrafficReferrer_admin_view_view_model.php';
		$this->_data['view_model'] = new TrafficReferrer_admin_view_view_model($this->traffic_referrer_model);
		$this->_data['view_model']->set_heading('Traffic Referrer');
        $this->_data['view_model']->set_model($model);
        
		return $this->render('Admin/TrafficReferrerView', $this->_data);
	}

    
    
    
    
    
    
    
    
}