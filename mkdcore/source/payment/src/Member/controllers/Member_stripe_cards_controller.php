<?php defined('BASEPATH') OR exit('No direct script access allowed');
include_once 'Member_controller.php';
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
/**
 * Stripe_cards Controller
 * @copyright 2019 Manaknightdigital Inc.
 * @link https://manaknightdigital.com
 * @license Proprietary Software licensing
 * @author Ryan Wong
 *
 */
class Member_stripe_cards_controller extends Member_controller
{
    protected $_model_file = 'stripe_cards_model';
    public $_page_name = 'Cards';

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
        include_once __DIR__ . '/../../view_models/Stripe_cards_member_list_paginate_view_model.php';
        $session = $this->get_session();
        $format = $this->input->get('format', TRUE) ?? 'view';
        $order_by = $this->input->get('order_by', TRUE) ?? '';
        $direction = $this->input->get('direction', TRUE) ?? 'ASC';

        $this->_data['view_model'] = new Stripe_cards_member_list_paginate_view_model($this->stripe_cards_model,$this->pagination,'/member/stripe_cards/0');
        $this->_data['view_model']->set_heading('xyzCards');
        $this->_data['view_model']->set_card_last(($this->input->get('card_last', TRUE) != NULL) ? $this->input->get('card_last', TRUE) : NULL);
		$this->_data['view_model']->set_card_brand(($this->input->get('card_brand', TRUE) != NULL) ? $this->input->get('card_brand', TRUE) : NULL);
		$this->_data['view_model']->set_card_name(($this->input->get('card_name', TRUE) != NULL) ? $this->input->get('card_name', TRUE) : NULL);
		$this->_data['view_model']->set_is_default(($this->input->get('is_default', TRUE) != NULL) ? $this->input->get('is_default', TRUE) : NULL);
		
        $where = [
            'card_last' => $this->_data['view_model']->get_card_last(),
			'card_brand' => $this->_data['view_model']->get_card_brand(),
			'card_name' => $this->_data['view_model']->get_card_name(),
			'is_default' => $this->_data['view_model']->get_is_default(),
            'user_id' => $session['user_id'],
            'role_id' => $session['role']
        ];

        $this->_data['view_model']->set_total_rows($this->stripe_cards_model->count($where));

        $this->_data['view_model']->set_format_layout($this->_data['layout_clean_mode']);
        $this->_data['view_model']->set_per_page(25);
        $this->_data['view_model']->set_order_by($order_by);
        $this->_data['view_model']->set_sort($direction);
        $this->_data['view_model']->set_sort_base_url('/member/stripe_cards/0');
        $this->_data['view_model']->set_page($page);
		$this->_data['view_model']->set_list($this->stripe_cards_model->get_paginated($this->_data['view_model']->get_page(),$this->_data['view_model']->get_per_page(),$where,$order_by,$direction));

        if ($format != 'view')
        {
            return $this->output->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode($this->_data['view_model']->to_json()));
        }

        return $this->render('Member/Stripe_cards', $this->_data);
	}

	public function add()
	{
        include_once __DIR__ . '/../../view_models/Stripe_cards_member_add_view_model.php';
        $this->load->model('user_model');
        $this->form_validation = $this->stripe_cards_model->set_form_validation($this->form_validation, $this->stripe_cards_model->get_all_validation_rule());
        $this->_data['view_model'] = new Stripe_cards_member_add_view_model($this->stripe_cards_model);
        $this->_data['view_model']->set_heading('Cards');
        $user_obj = $this->user_model->get($this->session->userdata('user_id'));
        $session = $this->get_session();
        $user_id = $session['user_id'];
        $role_id = $session['role'];
        
		if ($this->form_validation->run() === FALSE)
		{
			return $this->render('Member/Stripe_cardsAdd', $this->_data);
        }

        $is_default = $this->input->post('is_default');
        $source = $this->input->post('stripeToken');
        $card_name = $this->input->post('card_name');
        /**
         * if stipe customer does not exist create it heres
         */
        if(empty($user_obj->stripe_id))
        {
            try
            {
                $stripe_client = $this->payment_service->create_customer(['email' => $user_obj->email , 'source' => $source ]);
                
                if(isset($stripe_client['id']))
                {
                    $this->user_model->edit(['stripe_id' => $stripe_client['id']], $user_id);
                    $card = $stripe_client['sources']['data'][0] ?? [];
                    if(!empty($card))
                    {
                        $card_params = [
                            'card_last' => $card['last4'] ?? " ",
                            'card_brand' =>  $card['brand'] ?? " ",
                            'card_exp_month' =>  $card['exp_month'] ?? " ",
                            'exp_year' => $card['exp_year'] ?? " ",
                            'card_name' =>  $card_name  ?? ( $card['brand'] ?? " "),
                            'stripe_card_customer' =>  $card['customer'],
                            'stripe_card_id' => $card['id'],
                            'is_default' => 1,
                            'user_id' => $user_obj->id,
                            'role_id' => $role_id
                        ];
                            
                        $this->stripe_cards_model->create($card_params);
                    }
                    $this->success('xyzCard created');
                    return $this->redirect('/member/stripe_cards/0', 'refresh'); 
                }
            }
            catch(Exception $e)
            {
                $this->_data['error'] = 'Error creating card';
                return $this->render('Member/Stripe_cardsAdd', $this->_data);
            }
        }
        
        try
        {
            $card_params = [
                'source' =>  $source,
                'customer_stripe_id' => $user_obj->stripe_id 
            ];
            $stripe_card = $this->payment_service->create_card($card_params);

        }
        catch(Exception $e)
        {
            $this->_data['error'] = $e->getMessage();
            return $this->render('Member/Stripe_cardsAdd', $this->_data);    
        }
        
        if(isset($stripe_card['id']))
        {
            //reset default card is current is default  
            if((int) $is_default === 1)
            {
                $sql = "UPDATE stripe_cards SET is_default = 0 WHERE user_id = {$user_obj->id}";
                $this->stripe_cards_model->raw_query($sql);
            }

            $card_params = [
                'card_last' => $stripe_card['last4'] ?? " ",
                'card_brand' => $stripe_card['brand'] ?? " ",
                'card_exp_month' => $stripe_card['exp_month'] ?? " ",
                'exp_year' =>$stripe_card['exp_year'] ?? " ",
                'card_name' =>  $card_name  ?? ( $card['brand'] ?? " "),
                'stripe_card_customer' => $stripe_card['customer'],
                'stripe_card_id' =>$stripe_card['id'],
                'is_default' => $is_default,
                'user_id' => $user_obj->id,
                'role_id' => $role_id
            ];
            
            $result = $this->stripe_cards_model->create($card_params);

            if ($result)
            {
                if($is_default == 1)
                {
                    try
                    {
                        $customer = $this->payment_service->update_customer_payment_method( $stripe_card['id'] ,$stripe_card['customer']);
                    }
                    catch(Exception $e)
                    {
                        $this->error('xyzError updating default payment method ' . $e->getMessage() );
                    }

                    if(isset($customer['id']))
                    {
                        $this->stripe_cards_model->update_default_card( $user_obj->id, $role_id, $model->id);
                    }

                }
                $this->success('xyzCard created');
                return $this->redirect('/member/stripe_cards/0', 'refresh');
            }
        }

        $this->_data['error'] = 'Error';
        return $this->render('Member/Stripe_cardsAdd', $this->_data);
	}

	public function edit($id)
	{
        $model = $this->stripe_cards_model->get($id);
        $this->load->model('user_model');

		if (!$model)
		{
			$this->error('Error');
			return redirect('/member/stripe_cards/0');
        }

        include_once __DIR__ . '/../../view_models/Stripe_cards_member_edit_view_model.php';
        $this->form_validation = $this->stripe_cards_model->set_form_validation(
        $this->form_validation, $this->stripe_cards_model->get_all_edit_validation_rule());
        $this->_data['view_model'] = new Stripe_cards_member_edit_view_model($this->stripe_cards_model);
        $this->_data['view_model']->set_model($model);
        $this->_data['view_model']->set_heading('Cards');
        
        
		if ($this->form_validation->run() === FALSE)
		{
			return $this->render('Member/Stripe_cardsEdit', $this->_data);
        }
        
        $session = $this->get_session();
        $user_id = $session['user_id'];
        $role_id = $session['role'];
        $is_default = $this->input->post('is_default');
        
        if($is_default == 1 && $model->is_default != $is_default)
        {
            try
            {
                $customer = $this->payment_service->update_customer_payment_method( $model->stripe_card_customer, $model->stripe_card_id);
            }
            catch(Exception $e)
            {
                $this->_data['error'] = 'xyzError updating payment method ' . $e->getMessage();
                return $this->render('Member/Stripe_cardsEdit', $this->_data);
            }

            if(isset($customer['id']))
            {
                $result =  $this->stripe_cards_model->update_default_card($user_id, $role_id, $model->id);
                
                if ($result)
                {   
                    $this->success('xyzCard updated');
                }

                return $this->redirect('/member/stripe_cards/0', 'refresh');
            }
        }
        else
        {
            $result = $this->stripe_cards_model->edit(['is_default' => $is_default], $model->id);
            if($result)
            {
                return $this->redirect('/member/stripe_cards/0', 'refresh');
            }
        }

        $this->_data['error'] = 'Error';
        return $this->render('Member/Stripe_cardsEdit', $this->_data);
	}

	public function view($id)
	{
        $model = $this->stripe_cards_model->get($id);

		if (!$model)
		{
			$this->error('Error');
			return redirect('/member/stripe_cards/0');
		}

		$session = $this->get_session();
		if ($model->user_id != $session['user_id'])
		{
			$this->error('Error');
			return redirect('/member/stripe_cards/0');
		}
		
        include_once __DIR__ . '/../../view_models/Stripe_cards_member_view_view_model.php';
		$this->_data['view_model'] = new Stripe_cards_member_view_view_model($this->stripe_cards_model);
		$this->_data['view_model']->set_heading('Cards');
        $this->_data['view_model']->set_model($model);
        
		return $this->render('Member/Stripe_cardsView', $this->_data);
	}









}