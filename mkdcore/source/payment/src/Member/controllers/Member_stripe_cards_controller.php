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

        
		if ($this->form_validation->run() === FALSE)
		{
			return $this->render('Member/Stripe_cardsAdd', $this->_data);
        }

        $is_default = $this->input->post('is_default');
        $source = $this->input->post('stripeToken');
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
                'card_name' => $stripe_card['name'] ?? " ",
                'stripe_card_customer' => $stripe_card['customer'],
                'stripe_card_id' =>$stripe_card['id'],
                'is_default' => $is_default,
                'user_id' => $user_obj->id
            ];
            
            $result = $this->stripe_cards_model->create($card_params);

            if ($result)
            {
                return $this->redirect('/member/stripe_cards/0', 'refresh');
            }
        }

        $this->_data['error'] = 'Error';
        return $this->render('Member/Stripe_cardsAdd', $this->_data);
	}

	public function edit($id)
	{
        $model = $this->stripe_cards_model->get($id);

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

        $is_default = $this->input->post('is_default');
		
        $result = $this->stripe_cards_model->edit([
            'is_default' => $is_default,
			
        ], $id);

        if ($result)
        {   
            return $this->redirect('/member/stripe_cards/0', 'refresh');
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