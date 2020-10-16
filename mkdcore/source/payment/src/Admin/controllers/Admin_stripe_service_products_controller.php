<?php defined('BASEPATH') OR exit('No direct script access allowed');
include_once 'Admin_controller.php';
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
/**
 * Stripe_service_products Controller
 * @copyright 2019 Manaknightdigital Inc.
 * @link https://manaknightdigital.com
 * @license Proprietary Software licensing
 * @author Ryan Wong
 *
 */
class Admin_stripe_service_products_controller extends Admin_controller
{
    protected $_model_file = 'stripe_products_model';
    public $_page_name = 'xyzService Products';

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
        include_once __DIR__ . '/../../view_models/Stripe_service_products_admin_list_paginate_view_model.php';
        $format = $this->input->get('format', TRUE) ?? 'view';
        $order_by = $this->input->get('order_by', TRUE) ?? '';
        $direction = $this->input->get('direction', TRUE) ?? 'ASC';
        $session = $this->get_session();
        $where = [];
        $this->_data['view_model'] = new Stripe_service_products_admin_list_paginate_view_model(
            $this->stripe_products_model,
            $this->pagination,
            '/admin/stripe_service_products/0');
        $this->_data['view_model']->set_heading('xyzService Products');
        $this->_data['view_model']->set_total_rows($this->stripe_products_model->count($where));

        $this->_data['view_model']->set_per_page(25);
        $this->_data['view_model']->set_page($page);
        $this->_data['view_model']->set_order_by($order_by);
        $this->_data['view_model']->set_sort($direction);
        $this->_data['view_model']->set_sort_base_url('/admin/stripe_service_products/0');
		$this->_data['view_model']->set_list($this->stripe_products_model->get_paginated(
            $this->_data['view_model']->get_page(),
            $this->_data['view_model']->get_per_page(),
            $where,
            $order_by,
            $direction));

        if ($format != 'view')
        {
            return $this->output->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode($this->_data['view_model']->to_json()));
        }

        return $this->render('Admin/Stripe_service_products', $this->_data);
	}

    public function add()
	{
        include_once __DIR__ . '/../../view_models/Stripe_service_products_admin_add_view_model.php';
        $this->form_validation = $this->stripe_products_model->set_form_validation(
        $this->form_validation, $this->stripe_products_model->get_all_validation_rule());
        $this->_data['view_model'] = new Stripe_service_products_admin_add_view_model($this->stripe_products_model);
        $this->_data['view_model']->set_heading('xyzService Products');
        
        
		if ($this->form_validation->run() === FALSE)
		{
			return $this->render('Admin/Stripe_service_productsAdd', $this->_data);
        }

        $name = $this->input->post('name');
        $type = $this->input->post('type');
        
        $stripe_product_params = [
            'name' => $name,
            'type' => $type,
        ];

        try
        {
            $stripe_product = $this->payment_service->create_product( $stripe_product_params);

            if(isset($stripe_product['id']))
            {
                $result = $this->stripe_products_model->create([
                    'name' => $name,
                    'type' => $this->stripe_products_model->get_mappings_key($type, 'type'),
                    'stripe_id' => $stripe_product['id']
                ]);
                
                if ($result)
                {   
                    $this->success('xyzProduct Added');
                    return $this->redirect('/admin/stripe_service_products/0', 'refresh');
                }
            
                $this->_data['error'] = 'xyzError';
                return $this->render('Admin/Stripe_service_productsAdd', $this->_data);
            }
        }
        catch(Exception $e)
        {
            $this->_data['error'] = $e->getMessage();
            return $this->render('Admin/Stripe_service_productsAdd', $this->_data);
        }
        
        $this->_data['error'] = 'xyzError';
        return $this->render('Admin/Stripe_service_productsAdd', $this->_data);
	}

    public function edit($id)
	{
        $model = $this->stripe_products_model->get($id);

		if (!$model)
		{
			$this->error('xyzError');
			return redirect('/admin/stripe_service_products/0');
        }

        include_once __DIR__ . '/../../view_models/Stripe_service_products_admin_edit_view_model.php';
        $this->form_validation = $this->stripe_products_model->set_form_validation(
        $this->form_validation, $this->stripe_products_model->get_all_edit_validation_rule());
        $this->_data['view_model'] = new Stripe_service_products_admin_edit_view_model($this->stripe_products_model);
        $this->_data['view_model']->set_model($model);
        $this->_data['view_model']->set_heading('xyzService Products');
        
        
		if ($this->form_validation->run() === FALSE)
		{
			return $this->render('Admin/Stripe_service_productsEdit', $this->_data);
        }

        $name = $this->input->post('name');
		$type = $this->input->post('type');
		
        $result = $this->stripe_products_model->edit([
            'name' => $name,
			'type' => $type,
			
        ], $id);

        if ($result)
        {
            
            
            return $this->redirect('/admin/stripe_service_products/0', 'refresh');
        }

        $this->_data['error'] = 'Error';
        return $this->render('Admin/Stripe_service_productsEdit', $this->_data);
	}

    public function view($id)
	{
        $model = $this->stripe_products_model->get($id);

		if (!$model)
		{
			$this->error('xyzError');
			return redirect('/admin/stripe_service_products/0');
		}


        include_once __DIR__ . '/../../view_models/Stripe_service_products_admin_view_view_model.php';
		$this->_data['view_model'] = new Stripe_service_products_admin_view_view_model($this->stripe_products_model);
		$this->_data['view_model']->set_heading('xyzService Products');
        $this->_data['view_model']->set_model($model);
        
		return $this->render('Admin/Stripe_service_productsView', $this->_data);
	}   
}