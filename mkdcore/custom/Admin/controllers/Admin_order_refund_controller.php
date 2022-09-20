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
class Admin_order_refund_controller extends Admin_controller
{
    protected $_model_file = 'pos_order_model';
    public $_page_name = 'About Us';

    public function __construct()
    {
        parent::__construct();
        
        
        
    }

    public function refund ($id)
    {
        $order_model = $this->pos_order_model->get($id);
        $session = $this->get_session();
		if (!$order_model)
		{
			$this->error('Error');
			return redirect('/admin/orders/0?order_by=id&direction=DESC');
        }

        $this->_data['heading'] = "Order";
        $this->_data['page_name'] = "Orders";
        $this->_data['order'] = $order_model;

        if(isset($_POST['refund-btn']))
        {
            // handle refund
            $payment_data = json_decode($order_model->intent_data);
            $transaction_id = $payment_data->transactionid;

            $amount = $this->input->post('amount');

            $response = $this->_make_nmi_refund($amount, $transaction_id);

            if(isset($response['success']) && $response['success']) {
                // Update model refunded
                $this->pos_order_model->edit([
                    'intent_data' => json_encode($response),
                    'status' => 2 /* Refunded */
                ], $id);
                $this->success('Refunded Successfully');
                return redirect('/admin/orders/0?order_by=id&direction=DESC');
            }
            $this->error('Refunded failed, refund amount may not exceed transaction balance');
            return $this->render('Admin/OrdersRefund', $this->_data);
        }
        
        return $this->render('Admin/OrdersRefund', $this->_data);
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

    
    private function _make_nmi_refund($amount, $transaction_id, $payment = "creditcard")
    {
        $APPROVED = 1;
        $url = $this->config->item('nmi_url');
        $nmi_secret_key = $this->config->item('nmi_security_key');
        $type = 'refund';
        // $test_mode = 'enabled';

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);

        // $headers = array(
        // "Content-Type: application/json",
        // );
        // curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        $query  = "";
        // Login Information
        $query .= "security_key=" . urlencode($nmi_secret_key) . "&";
        // refund Information
        $query .= "transactionid=" . urlencode($transaction_id) . "&";
        $query .= "amount=" . urlencode(number_format($amount,2,".","")) . "&";
        $query .= "type=" . urlencode($type) . "&";
        // $query .= "test_mode=" . urlencode($test_mode) . "&";


        curl_setopt($curl, CURLOPT_POSTFIELDS, $query);

        //for debug only!
        // curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        // curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $resp = curl_exec($curl);
        curl_close($curl);
        
        $data = explode("&",$resp);

        for($i=0;$i<count($data);$i++) {
                $rdata = explode("=",$data[$i]);
                $response[$rdata[0]] = isset($rdata[1]) ? $rdata[1] : 0;
        }

        if(isset($response['response']) && $response['response'] == $APPROVED){ 
            $response['success'] = true;
        }else{
            $response['error_msg'] = 'Refund Failed';
        }

        return $response;

    }

    
    
    
    
    
    
}