<?php
use \Stripe\Stripe;
use \Stripe\Customer;
use \Stripe\Charge;
use \Stripe\Refund;
use \Stripe\Plan;
use \Stripe\Coupon;
use \Stripe\Product;
use \Stripe\Subscription;
use \Stripe\Invoice;
use \Stripe\Error;
use \Stripe\Webhook;
use \Stripe\Source;
use \Stripe\Dispute;
use \Stripe\File;
use \Stripe\Exception;
use \Stripe\Event;
use \Stripe\InvoiceItem;
use \Stripe\PaymentMethod;

class Stripe_ach_invoice_service {  
    
    protected $_config;
      

    public function set_config($config)
    {
        $this->_config = $config;
    }
      


    public function send_ach_invoice_sale_order($customer_real_name, $customer_email, $customer_phone, $total, $days_until_due)
    {
        $stripe_secret_key  = $this->_config->item('stripe_secret_key');
        Stripe::setApiKey( $stripe_secret_key );
        try 
        {  
            $customer = Customer::create([
                "name"        => $customer_real_name,
                "email"       => $customer_email, 
                "description" => $customer_phone,
            ]);
             
            $amount = $total * 100; 
            
            try 
            { 
                $invoice_item = InvoiceItem::create([
                    "amount"      => $amount,
                    "currency"    => "usd",
                    "customer"    => $customer->id,
                    "description" => "Sale Order Invoice"
                ]);
    
                if ($invoice_item) 
                { 
                    try 
                    {   
                        // pulls in invoice items
                        $invoice_detail =  Invoice::create(array(
                            "customer"          => $customer->id,
                            "collection_method" => 'send_invoice',
                            "days_until_due"    => $days_until_due,
                        ));
                            
                        try 
                        {
                            
                            $output['invoice_id'] = $invoice_detail->id;
                            return $output;
                            
                            // Invoice::sendInvoice(array($invoice_detail->id));
                        } catch (Exception $e) {
                            $output['error'] = $e;
                            return $output; 
                        } 
                        // # status (error: 0, success: 1)
                        // echo json_encode(['status' => 1]);
                        // exit();
                    } catch (Exception $e) {
                        $output['error'] = $e;
                        return $output; 
                    } 
                }
                else
                {
                    $output['error'] = $e;
                    return $output; 
                } 
            } catch (Exception $e) {
                $output['error'] = $e;
                return $output; 
            } 

            // echo json_encode(['status' => 0]);
            // exit();
        } catch (Exception $e) {
            $output['error'] = $e;
            return $output; 
        } 
    }
 

}    

?>