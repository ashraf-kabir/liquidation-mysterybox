<?php defined('BASEPATH') OR exit('No direct script access allowed');
use Aws\S3\S3Client;

include_once __DIR__ . '/../middlewares/Auth_middleware.php';
include_once __DIR__ . '/../middlewares/Acl_middleware.php';
include_once __DIR__ . '/../middlewares/Maintenance_middleware.php';
include_once __DIR__ . '/../middlewares/Affilate_middleware.php';
include_once __DIR__ . '/../middlewares/Subscription_middleware.php';
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
/**
 * Abstract Controller
 *
 * @copyright 2019 Manaknightdigital Inc.
 * @link https://manaknightdigital.com
 * @license Proprietary Software licensing
 * @author Ryan Wong
 *
 */
class {{{subclass_prefix}}}Controller extends CI_Controller
{

    // If set, this language file will automatically be loaded.
    protected $_language_file = NULL;

    // If set, this model file will automatically be loaded.
    protected $_model_file = NULL;

    //testMode flag
    protected $_test_mode = FALSE;

    protected $_setting = FALSE;

    /**
     * View Model
     * @var array
     */
    public $_data = [
        'error' => '',
        'success' => ''
    ];

    /**
     * Flash Data
     * @var array
     */
    protected $_flash_error = [
        'error' => '',
        'success' => ''
    ];

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
 

        //--------------------------------------------------------------------
        // Language & Model Files
        //--------------------------------------------------------------------

        if (!is_null($this->_language_file))
        {
            $this->lang->load($this->_language_file);
        }

        if (!is_null($this->_model_file))
        {
            $this->load->model($this->_model_file);
        }

        //Flashdata setup
        if ($this->session->flashdata('error'))
        {
            $this->_flash_error['error'] = $this->session->flashdata('error');
            $this->session->set_flashdata('error', '');
            $this->_data['error'] = $this->_flash_error['error'];
        }

        if ($this->session->flashdata('success'))
        {
            $this->_flash_error['success'] = $this->session->flashdata('success');
            $this->session->set_flashdata('success', '');
            $this->_data['success'] = $this->_flash_error['success'];
        }

        $this->load->model('setting_model');
        $this->_setting = $this->setting_model->get_config_settings();
    }

    /**
     * Set Controller into test mode
     *
     * @return void
     */
    public function set_test_mode ()
    {
        $this->_test_mode = TRUE;
    }

    /**
     * Render view
     *
     * @param string $template
     * @param array $data
     */
    public function render($template, $data)
    {
        return (!$this->_test_mode) ? $this->_render($template, $data) : $this->_render_test($template, $data);
    }

    /**
     * Render Test Model
     *
     * @param string $template
     * @param mixed $data
     * @return mixed
     */
    protected function _render_test($template, $data)
    {
        return [
            'header' => $this->load->view('Layout/Header', $data, TRUE),
            'body' => $this->load->view($template, $data, TRUE),
            'footer' => $this->load->view('Layout/Footer', $data, TRUE),
            'data' => $data,
        ];
    }

    /**
     * Render Normal View
     *
     * @param string $template
     * @param mixed $data
     * @return void
     */
    protected function _render($template, $data)
    {
        $this->load->view('Layout/Header', $data);
        $this->load->view($template, $data);
        $this->load->view('Layout/Footer');
    }

    /**
     * Redirect to URL
     *
     * @param string $template
     * @param array $data
     */
    public function redirect($url, $option = [])
    {
        return ($option) ? redirect($url, $option) : redirect($url);
    }

    /**
     * Set the Flashdata
     *
     * @param string $message
     */
    public function success($message)
	{
      $this->session->set_flashdata('success', $message);
    }

    /**
     * Set the Flashdata
     *
     * @param string $message
     */
    public function error($message)
	{
      $this->session->set_flashdata('error', $message);
    }

    /**
     * Debug Controller to error_log and turn off in production
     *
     * @param mixed $data
     * @return void
     */
    public function dl($key, $data)
    {
        if (ENVIRONMENT == 'development')
        {
            error_log($key . ' CONTROLLER : <pre>' . print_r($data, TRUE) . '</pre>');
        }
    }

    /**
     * Debug json Controller to error_log and turn off in production
     *
     * @param mixed $data
     * @return void
     */
    public function dj($key, $data)
    {
        if (ENVIRONMENT == 'development')
        {
            error_log($key . ' CONTROLLER : ' . json_encode($data));
        }
    }

    public function get_session()
    {
        if (!$this->_test_mode)
        {
            return $_SESSION;
        }

        $session = $this->config->item('session_test');

        if (!$session)
        {
            $session = [];
        }

        return $session;
    }

    public function set_session($field, $value)
    {
        if (!$this->_test_mode)
        {
            $_SESSION[$field] = $value;
        }
        else
        {
            $session = $this->config->item('session_test');
            if (!$session)
            {
                $session = [];
            }
            $session[$field] = $value;
            $this->config->set_item('session_test', $session);
        }
    }

    public function destroy_session()
    {
        if (!$this->_test_mode)
        {
            unset($_SESSION);
            session_unset();
        }
        else
        {
            $this->config->set_item('session_test', []);
        }
    }

    /**
     * Function to send Emails given slug, payload and email
     *
     * @param string $slug
     * @param mixed $payload
     * @param string $email
     * @return void
     */
    protected function _send_email_notification($slug, $payload, $email)
    {
		$this->load->model('email_model');
		$this->load->library('mail_service');
        $this->mail_service->set_adapter('smtp');
        $email_template = $this->email_model->get_template($slug, $payload);

        if ($email_template)
        {
            $from = $this->config->item('from_email');
            return $this->mail_service->send($from, $email, $email_template->subject, $email_template->html);
        }

        return FALSE;
    }

    /**
     * Function to send Sms given slug, payload and phone #
     *
     * @param string $slug
     * @param mixed $payload
     * @param string $to
     * @return void
     */
	protected function _send_sms_notification($slug, $payload, $to)
    {
		$this->load->model('sms_model');
		$this->load->library('sms_service');
        $this->sms_service->set_adapter('sms');
        $sms_template = $this->sms_model->get_template($slug, $payload);

        if ($sms_template)
        {
            return $this->sms_service->send($to, $sms_template->content);
        }

        return FALSE;
    }

    /**
     * Function to send Push notification
     *
     * @param string $slug
     * @param mixed $payload
     * @param string $to
     * @return void
     */
	protected function _send_push_notification($device_type, $device_id, $title, $message, $image)
    {
        $this->load->library('push_notification_service');
        $this->push_notification_service->init();
        return $this->push_notification_service->send($device_type, $device_id, $title, $message, $image);
    }

    protected function _middleware()
    {
        return [];
    }

    protected function _run_middlewares ()
    {

        $middlewares = [
            'affilate' => new Affilate_middleware($this, $this->config),
            'auth' => new Auth_middleware($this, $this->config),
            'acl' => new Acl_middleware($this, $this->config),
            'maintenance' => new Maintenance_middleware($this, $this->config),
            'subscription' => new Subscription_middleware($this, $this->config)
        ];

        foreach ($this->_middleware() as $middleware_key)
        {
            if (isset($middlewares[$middleware_key]))
            {
                $result = $middlewares[$middleware_key]->run();

                if (!$result)
                {
                    return FALSE;
                }
            }
        }
    }

    public function get_setting()
    {
        return $this->_setting;
    }



    public function image_upload_using_s3($file_name)
    {

        if( $this->config->item('image_upload')  == 's3')
        { 
            $s3 = new S3Client([
                'version' => $this->config->item('aws_version'),
                'region'  => $this->config->item('aws_region'),
                'credentials' => [
                    'key'    => $this->config->item('aws_key'),
                    'secret' => $this->config->item('aws_secret'),
                ]
            ]);

            $this->load->model('image_model'); 
            
            $image_path = __DIR__ . '/../../' . $file_name;

            // $data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $data_uri));
            // $filename = md5(uniqid() . time()) . '.png';
            // file_put_contents($image_path . $filename, $data);


            list($width, $height) = getimagesize( $image_path );
            $session = $this->get_session();
            $user_id = isset($session['user_id']) ? $session['user_id'] : 0;

            $file_name = str_replace('/uploads/','',$file_name);

            try
            {
                $result = $s3->putObject([
                    'Bucket' => $this->config->item('aws_bucket'),
                    'Key'    => $file_name,
                    'Body'   => fopen($image_path, 'r'),
                    'ACL'    => 'public-read',
                ]);

                $image_id = $this->image_model->create([
                    'url'       => $result->get('ObjectURL'),
                    'type'      => 0,
                    'user_id'   => $user_id,
                    'width'     => $width,
                    'caption'   => '',
                    'height'    => $height
                ]);
                
                return $this->output->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode([
                    'id'     => $image_id,
                    'image'  => $result->get('ObjectURL'),
                    'width'  => $width,
                    'height' => $height
                ]));
            }
            catch (Aws\S3\Exception\S3Exception $e)
            {
                return $this->output->set_content_type('application/json')
                ->set_status_header(403)
                ->set_output(json_encode([
                    'message' => 'Upload To S3 Failed'
                ]));
            }
        }
    }


    

    public function upload_image_with_s3($file_name,$image_portal = false)
    { 
        $output = $this->image_upload_using_s3($file_name);
        $out_image = '';
        if( isset($output->final_output ) )
        {
            $result = json_decode( $output->final_output );
            if(isset($result->image))
            {
                if ($image_portal) 
                {
                    $out_image = array();
                    $out_image['image_url'] = $result->image;
                    $out_image['image_id']  = $result->id;
                }
                else
                {
                    $out_image = $result->image;
                }
                
            } 
            return $out_image;
        } else{
            return $out_image;
        }
    }


    public function send_order_to_shipper($order_id)
    { 
        $this->load->model('inventory_model');
        $this->load->model('pos_order_items_model');
        $this->load->model('pos_order_model');
        $this->load->model('store_model');
        $this->load->model('physical_location_model');

        $this->load->library('api_service');
        $this->api_service->set_inventory_model($this->inventory_model);
        $this->api_service->set_pos_order_items_model($this->pos_order_items_model);
        $this->api_service->set_pos_order_model($this->pos_order_model);
        $this->api_service->set_store_model($this->store_model);
        $this->api_service->set_config($this->config);
        $this->api_service->set_physical_location_model($this->physical_location_model);


        $order_data = $this->api_service->send_order_to_shipper($order_id);

        if(isset($order_data->id))
        { 
            $this->pos_order_model->edit(['pick_up_id' => $order_data->id], $order_id);
        }
 
        return $order_data;
    }




    public function send_order_to_accounting($order_id)
    { 
        $this->load->model('inventory_model');
        $this->load->model('pos_order_items_model');
        $this->load->model('pos_order_model');
        $this->load->model('customer_model');

        $this->load->library('accounting_api_service');
        $this->accounting_api_service->set_inventory_model($this->inventory_model);
        $this->accounting_api_service->set_pos_order_items_model($this->pos_order_items_model);
        $this->accounting_api_service->set_pos_order_model($this->pos_order_model);
        $this->accounting_api_service->set_customer_model($this->customer_model);
        $this->accounting_api_service->set_config($this->config);


        $order_data = $this->accounting_api_service->post_order_to_accounting($order_id);

        if(isset($order_data->id))
        { 
            
        }
 
        return $order_data;
    }


    

    public function send_transaction_to_accounting($transaction_id)
    { 
        $this->load->model('transactions_model');  

        $this->load->library('accounting_api_service'); 
        $this->accounting_api_service->set_transactions_model($this->transactions_model);
        $this->accounting_api_service->set_config($this->config);


        $order_data = $this->accounting_api_service->send_transaction_to_accounting($transaction_id);

        if(isset($order_data->id))
        { 
            
        }
 
        return $order_data;
    }

    


    public function send_email_on_order($order_id)
    {
        $this->load->model('pos_order_model'); 
        $this->load->model('pos_order_items_model'); 
        $this->load->model('email_model');
        $this->load->model('store_model');
        $this->load->model('inventory_model');
        $this->load->model('customer_model');


        $this->load->library('mail_service');
        $this->mail_service->set_adapter('smtp');


        $id    = $order_id;  
        $model = $this->pos_order_model->get($id);

        if ($model and isset($model->customer_email) and filter_var($model->customer_email, FILTER_VALIDATE_EMAIL))
        {   
            include_once __DIR__ . '/../view_models/Orders_admin_view_view_model.php';
            $this->_data['view_model'] = new Orders_admin_view_view_model($this->pos_order_model);
            $this->_data['view_model']->set_heading('Orders');
            $this->_data['view_model']->set_model($model);
            $this->load->model('pos_order_items_model'); 

            $order_details = $this->pos_order_items_model->get_all(['order_id' => $id]);
            foreach ($order_details as $order_detail)
            {
                $inventory = $this->inventory_model->get($order_detail->product_id);
                $order_detail->product_image = empty($inventory) ? '': $inventory->feature_image;

                if($order_detail->store_id == 0 /* if no store, hence its pickup */) {continue;}

                $order_detail->store = $this->store_model->get($order_detail->store_id);
            }
            $this->_data['orders_details'] = $order_details;
            $this->_data['customer'] = $this->customer_model->get($model->customer_id);
             

            ob_start();  
            $this->load->view('Admin/OrderEmailCopy', $this->_data); 
            $content = ob_get_contents(); 
            ob_end_clean();
            $from = $this->config->item('from_email');
            $this->mail_service->send($from, $model->customer_email, "Mystery Box Order Update", $content);
            
        }  
    }



    public function send_email_new_order_to_admin($order_id)
    {
        $this->load->model('pos_order_model'); 
        $this->load->model('pos_order_items_model'); 
        $this->load->model('email_model');
        $this->load->model('store_model');
        $this->load->model('inventory_model');
        $this->load->model('customer_model');


        $this->load->library('mail_service');
        $this->mail_service->set_adapter('smtp');


        $id    = $order_id;  
        $model = $this->pos_order_model->get($id);

        if ($model)
        {   
            include_once __DIR__ . '/../view_models/Orders_admin_view_view_model.php';
            $this->_data['view_model'] = new Orders_admin_view_view_model($this->pos_order_model);
            $this->_data['view_model']->set_heading('Orders');
            $this->_data['view_model']->set_model($model);
            $this->load->model('pos_order_items_model'); 

            $order_details = $this->pos_order_items_model->get_all(['order_id' => $id]);
            foreach ($order_details as $order_detail)
            {
                if($order_detail->store_id == 0 /* if no store, hence its pickup */) {continue;}

                $order_detail->store = $this->store_model->get($order_detail->store_id);
                $inventory = $this->inventory_model->get($order_detail->product_id);
                $order_detail->product_image = empty($inventory) ? '': $inventory->feature_image;
            }
            $this->_data['orders_details'] = $order_details;
            $this->_data['customer'] = $this->customer_model->get($model->customer_id);
             

            ob_start();  
            $this->load->view('Admin/OrderEmailCopy', $this->_data); 
            $content = ob_get_contents(); 
            ob_end_clean();
            $from = $this->config->item('from_email');
            $sale_email = $this->config->item('sale_email');
            $this->mail_service->send($from, $sale_email, "New Order Received on Mysterybox", $content);
            
        }  
    }

    public function send_email_on_refund($order_id)
    {
        $this->load->model('pos_order_model'); 
        $this->load->model('pos_order_items_model'); 
        $this->load->model('email_model');
        $this->load->model('store_model');
        $this->load->model('inventory_model');
        $this->load->model('customer_model');


        $this->load->library('mail_service');
        $this->mail_service->set_adapter('smtp');


        $id    = $order_id;  
        $model = $this->pos_order_model->get($id);

        if ($model and isset($model->customer_email) and filter_var($model->customer_email, FILTER_VALIDATE_EMAIL))
        {   
            include_once __DIR__ . '/../view_models/Orders_admin_view_view_model.php';
            $this->_data['view_model'] = new Orders_admin_view_view_model($this->pos_order_model);
            $this->_data['view_model']->set_heading('Orders');
            $this->_data['view_model']->set_model($model);
            $this->load->model('pos_order_items_model'); 

            $order_details = $this->pos_order_items_model->get_all(['order_id' => $id]);
            foreach ($order_details as $order_detail)
            {
                $inventory = $this->inventory_model->get($order_detail->product_id);
                $order_detail->product_image = empty($inventory) ? '': $inventory->feature_image;

                if($order_detail->store_id == 0 /* if no store, hence its pickup */) {continue;}

                $order_detail->store = $this->store_model->get($order_detail->store_id);
            }
            $this->_data['orders_details'] = $order_details;
            $this->_data['customer'] = $this->customer_model->get($model->customer_id);
             

            ob_start();  
            $this->load->view('Admin/OrderRefundEmailCopy', $this->_data); 
            $content = ob_get_contents(); 
            ob_end_clean();
            $from = $this->config->item('from_email');
            $this->mail_service->send($from, $model->customer_email, "Mystery Box Order Refund", $content);
            
        }  
    }

}