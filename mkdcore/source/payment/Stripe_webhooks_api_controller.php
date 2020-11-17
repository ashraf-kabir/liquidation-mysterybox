<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
/**
 * Voice Controller
 *
 * @copyright 2019 Manaknightdigital Inc.
 * @link https://manaknightdigital.com
 * @license Proprietary Software licensing
 *
 */
class Stripe_webhooks_api_controller extends CI_Controller{

    
    public function __construct()
    {
        parent::__construct();
        $stripe_config = [
            'stripe_api_version' => ($this->config->item('stripe_api_version') ?? ''),
            'stripe_publish_key' => ($this->config->item('stripe_publish_key') ?? ''),
            'stripe_secret_key' => ($this->config->item('stripe_secret_key') ?? '')
        ];
        $this->load->library('payment_service', $stripe_config);
        $this->load->database();
    }

    /**
     * handle stripe events
     * @see https://stripe.com/docs/api/events/types
     */
    public function index()
    {
        $payload = @file_get_contents('php://input');
        $event = null;

        try 
        {
            $event = $this->payment_service->get_stripe_event($payload);
        } 
        catch(Exception $e) 
        {
            http_response_code(400);
            exit(); 
        }

        $args = $event->data->object;

        switch ($event->type) 
        {
            case 'payment_intent.succeeded':
                $this-> handle_payment_intent_succeeded($args);
            break;
            
            case 'payment_method.attached':
                $this->handle_payment_method($args);
            break;

            case 'invoice.created':
                $this->handle_invoice_created($args);
            break;
            
            case 'invoice.finalized':
                $this->handle_invoice_finalized($args);
            break; 

            case 'invoice.upcoming':
                $this->handle_invoice_upcoming($args);
            break;

            case 'charge.succeeded':
                $this->handle_charge_succeeded($args);
            break;

            case 'charge.refunded':
                $this->handle_charge_refunded($args);
            break;
            
            case 'invoice.payment_succeeded':
                $this->handle_invoice_payment_succeeded($args);
            break;

            case 'invoice.payment_failed':
                $this->handle_invoice_payment_failed($args);
            break;

            case 'customer.source.expiring':
                http_response_code(200);
            break;

            case 'customer.subscription.deleted':
                http_response_code(200);
            break;

            case 'subscription_schedule.aborted':
                $this->handle_subscription_schedule_aborted($args);
            break;
            
            case 'subscription_schedule.canceled':
                $this->handle_subscription_schedule_canceled($args);
            break;

            case 'subscription_schedule.completed':
                $this->handle_subscription_schedule_completed($args);
            break;

            case 'subscription_schedule.expiring':
                $this->handle_subscription_schedule_expiring($args);
            break;

            default:
                http_response_code(200);
            exit();
        }
    }

    private function handle_payment_intent_succeeded($args)
    {
        echo json_encode($args);
        http_response_code(200);
        exit();
    }

    private function handle_payment_method($args)
    {
        echo json_encode($args);
        http_response_code(200);
        exit();
    } 
    
    /**
     * Occurs whenever a subscription schedule is canceled due to the underlying 
     * subscription being canceled because of delinquency.
     * @see https://stripe.com/docs/api/events/types
    */
    private function handle_subscription_schedule_aborted($args)
    {
        echo json_encode($args);
        http_response_code(200);
        exit();
    }

    /**
     * Occurs whenever a subscription schedule is canceled.
     * @see https://stripe.com/docs/api/events/types
     */
    private function handle_subscription_schedule_canceled($args)
    {
       echo json_encode($args);
       http_response_code(200);
       exit();

    } 

    /**
     * Occurs whenever a new subscription schedule is completed.
     * @see https://stripe.com/docs/api/events/types
     */
    private function handle_subscription_schedule_completed($args)
    {
        file_put_contents('log3.log', print_r($args,true));
        http_response_code(200);
        exit();
    }
    
    /**
     * Occurs 7 days before a subscription schedule will expire.
     * @see https://stripe.com/docs/api/events/types
     */
    private function handle_subscription_schedule_expiring($args)
    {
        echo json_encode($args);
        http_response_code(200);
        exit();
    }

    private function handle_invoice_created($args)
    {
        $this->load->model('stripe_subscriptions_invoices_model');
        $this->load->model('stripe_subscriptions_model');
        
        if(isset($args['id']))
        {
            $subscription_obj = $this->stripe_subscriptions_model->get_by_field('stripe_id', $args['subscription']);
            $invoice_params = [
                'subscriptions_id' => $subscription_obj->id ?? 0,
                'stripe_id' => $args['id'],
                'user_id' => $subscription_obj->user_id ?? 0,
                'role_id' =>  $subscription_obj->role_id ?? 0,
                'stripe_subscriptions_id' => $args['subscription'] ?? " ",
                'stripe_customer_id' => $args['customer'] ?? "",
                'stripe_charge_id' => ( $args['charge'] == NULL ? "" : $args['charge']),
                'collection_method' => $args['collection_method'] ?? " ",
                'currency' => $args['currency'] ?? " ",
                'invoice_url' => $args['hosted_invoice_url'] ?? " ",
                'invoice_pdf_url' => $args['invoice_pdf'] ?? " ",
                'payment_intent' =>( $args['payment_intent'] == NULL ? " " : $args['payment_intent']),
                'amount_due' => ($args['amount_due']/100), //convert from cents
                'amount_paid' => ($args['amount_paid'] / 100),
                'payment_attempted' => (int) $args['attempted'] ?? 0,
                'status' => $args['status']
            ];            
            $result = $this->stripe_subscriptions_invoices_model->create($invoice_params);

            if($result)
            {
                echo json_encode(["success" => true, "message" => "invoice captured"]);
                http_response_code(200);
                exit();       
            }

            echo json_encode(["success" => false, "message" => "invoice capture failed"]);
            http_response_code(400);
            exit();    
        }
        
        echo json_encode(["success" => false, "message" => "invalid invoice received"]);
        http_response_code(400);
        exit();    
    }

    private function handle_invoice_finalized($args)
    {
        $this->load->model('stripe_subscriptions_invoices_model');
        
        if(isset($args['id']))
        {
            $invoice_obj = $this->stripe_subscriptions_invoices_model->get_by_field('stripe_id', $args['id']);
            
            if(!empty($invoice_obj))
            {
                 $invoice_params = [
                    'stripe_charge_id' => ( $args['charge'] == NULL ? " " : $args['charge']),
                    'invoice_url' => $args['hosted_invoice_url'] ?? " ",
                    'invoice_pdf_url' => $args['invoice_pdf'] ?? " ",
                    'payment_intent' =>( $args['payment_intent'] == NULL ? " " : $args['payment_intent']),
                    'amount_due' => ($args['amount_due'] / 100),
                    'amount_paid' => ($args['amount_paid'] / 100),
                    'payment_attempted' => (int) $args['attempted'],
                    'status' =>  $this->stripe_subscriptions_invoices_model->get_mappings_key($args['status'],'status')
                 ];   

                 $result = $this->stripe_subscriptions_invoices_model->edit($invoice_params, $invoice_obj->id);
                 
                 if($result)
                 {
                    echo json_encode(["success" => true, "message" => "invoice captured"]);
                    http_response_code(200);
                    exit();    
                 }

                echo json_encode(["success" => false, "message" => "invoice finalized capture failed"]);
                http_response_code(400);
                exit();  
            }
        }

        echo json_encode(["success" => false, "message" => "invalid invoice received"]);
        http_response_code(400);
        exit();    
    }

    private function handle_charge_succeeded($args)
    {
     
        echo json_encode(["success" => false, "message" => "invalid invoice received"]);
        http_response_code(400);
        exit();      
    }

    private function handle_invoice_payment_succeeded($args)
    {
        $this->load->model('stripe_subscriptions_invoices_model');
        
        if(isset($args['id']))
        {
            $invoice_obj = $this->stripe_subscriptions_invoices_model->get_by_field('stripe_id', $args['id']);
            
            if(!empty($invoice_obj))
            {
                 $invoice_params = [
                    'stripe_charge_id' => ( $args['charge'] == NULL ? "" : $args['charge']),
                    'invoice_url' => $args['hosted_invoice_url'] ?? " ",
                    'invoice_pdf_url' => $args['invoice_pdf'] ?? " ",
                    'payment_intent' =>( $args['payment_intent'] == NULL ? "" : $args['payment_intent']) ?? " ",
                    'amount_due' => ($args['amount_due'] / 100 ) , // convert from cents
                    'amount_paid' => ($args['amount_paid'] / 100),
                    'payment_attempted' => (int) $args['attempted'],
                    'status' =>  $this->stripe_subscriptions_invoices_model->get_mappings_key($args['status'],'status')
                 ];   

                 $result = $this->stripe_subscriptions_invoices_model->edit($invoice_params, $invoice_obj->id);
                 
                 if($result)
                 {
                    echo json_encode(["success" => true, "message" => "invoice payment success captured"]);
                    http_response_code(200);
                    exit();    
                 }

                echo json_encode(["success" => false, "message" => "invoice finalized capture failed"]);
                http_response_code(400);
                exit();  
            }
        }
        
        echo json_encode(["success" => false, "message" => "invalid invoice received"]);
        http_response_code(400);
        exit();    
    }

    public function handle_charge_refunded($args)
    {
        
        $this->load->model('stripe_subscriptions_invoices_model');
        $this->load->model('stripe_refunds_model');
        $this->load->model('stripe_subscriptions_model');
        
        if(isset($args['id']))
        {
            $invoice_obj = $this->stripe_subscriptions_invoices_model->get_by_field('stripe_id', $args['invoice'] ?? "");
            $refund_params = [
                'stripe_id' => $args['id'],
                'stripe_invoice_id' =>  $args['invoice'] ?? "",
                'invoice_id' => $invoice_obj->id ?? 0,
                'receipt_url' =>  $args['receipt_url'] ?? "",
                'user_id' => $invoice_obj->user_id ?? 0,
                'amount' => $args['amount_refunded'] ?? 0,
                'reason' => $args['refunds']['data'][0]['reason'] ,
                'status' => $args['status'] ?? ""
            ];       

            $result = $this->stripe_refunds_model->create($refund_params);

            if($result)
            {
                $this->stripe_subscriptions_invoices_model->edit(['refunded' => 1],  $invoice_obj->id ?? 0 );
                $this->stripe_subscriptions_model->update_by_stripe_id($invoice_obj->stripe_subscriptions_id ?? "", ['status' => 7]);
                echo json_encode(["success" => true, "message" => "invoice refund captured"]);
                http_response_code(200);
                exit(); 
            }
            
        }
        echo json_encode(["success" => true, "message" => "charge refunded captured"]);
        http_response_code(400);
        exit();  
    }

    private function handle_invoice_payment_failed($args)
    {
        $this->load->model('stripe_subscriptions_invoices_model');
        
        if(isset($args['id']))
        {
            $invoice_obj = $this->stripe_subscriptions_invoices_model->get_by_field('stripe_id', $args['id']);
            
            if(!empty($invoice_obj))
            {
                 $invoice_params = [
                    'stripe_charge_id' => ( $args['charge'] == NULL ? "" : $args['charge']),
                    'invoice_url' => $args['hosted_invoice_url'] ?? " ",
                    'invoice_pdf_url' => $args['invoice_pdf'] ?? " ",
                    'payment_intent' =>( $args['payment_intent'] == NULL ? "" : $args['payment_intent']),
                    'amount_due' => ($args['amount_due'] / 100 ), // convert from cents
                    'amount_paid' => ($args['amount_paid'] / 100),
                    'payment_attempted' => (int) $args['attempted'],
                    'status' =>  $this->stripe_subscriptions_invoices_model->get_mappings_key($args['status'],'status')
                 ];   

                 $result = $this->stripe_subscriptions_invoices_model->edit($invoice_params, $invoice_obj->id);
                 
                 if($result)
                 {
                    echo json_encode(["success" => true, "message" => "invoice payment failed captured"]);
                    http_response_code(200);
                    exit();    
                 }

                echo json_encode(["success" => false, "message" => "invoice finalized capture failed"]);
                http_response_code(400);
                exit();  
            }
        }
        
        echo json_encode(["success" => false, "message" => "invalid invoice received"]);
        http_response_code(400);
        exit();    
    }

    public function handle_invoice_upcoming($args)
    {
        echo json_encode(["success" => false, "message" => "invalid invoice received"]);
        http_response_code(200);
        exit();    
    }



    
    /**
     * handle stripe ach events
     * @see https://stripe.com/docs/api/events/types
     */
    public function stripe_events()
    {
        $endpoint_secret = $this->config->item('stripe_endpoint_secret'); 

        $stripe_secret_key  = $this->config->item('stripe_secret_key');
        Stripe::setApiKey($stripe_secret_key);  

        $payload = @file_get_contents('php://input');
        $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
        $event = null;

        try {
            $event = Webhook::constructEvent(
                $payload, $sig_header, $endpoint_secret
            );
        } catch(\UnexpectedValueException $e) {
            // Invalid payload
            http_response_code(400);
            exit();
        } catch(\Stripe\Exception\SignatureVerificationException $e) {
            // Invalid signature
            http_response_code(400);
            exit();
        }

 
        // Handle the event
        switch ($event->type) 
        {
            case 'invoice.paid':
                $payment_intent = $event->data->object; 
                // write your stripe webhook code here
                // contains a StripePaymentIntent
                $this->handle_invoice_paid_method($payment_intent); 
                break;  
            default:
                echo 'Received unknown event type ' . $event->type;
        }

        http_response_code(200);
    }


    public function handle_invoice_paid_method($payment_intent)
    {
        $invoice_id     = $paymentIntent->id;
        // write your code here 
    }
}