<?php defined('BASEPATH') OR exit('No direct script access allowed'); 

/**
 * Home Controller to Manage all Frontend pages
 * @copyright 2019 Manaknightdigital Inc.
 * @link https://manaknightdigital.com
 * @license Proprietary Software licensing
 * @author Ryan Wong
 *
 */
 


class Shipstation_controller extends Manaknight_Controller
{
     
     

    public function __construct()
    {
        parent::__construct();  
        $this->load->model('pos_order_model');
        $this->load->model('customer_model'); 
        $this->load->model('inventory_model'); 
        $this->load->model('pos_order_items_model'); 
    }

     
    public function ship_station_endpoint()
    { 
        $this->load->library('shipstation_api_service');
        
        if($this->input->get('action') == 'export')
        {
            $order_list = $this->pos_order_model->get_all();

            if(!empty($order_list))
            { 
                $order_xml   =  ' <?xml version="1.0" encoding="utf-8"?> ';
                    $order_xml   .=  ' <Orders pages="1">';

                    $internal_note = '';
                    $customer_note = '';
                    foreach($order_list as $key => $order):

                        $customer_data = $this->customer_model->get($order->customer_id);
                        $order_details = $this->pos_order_items_model->get_all(['order_id' => $order->id]);

                        $order_xml   .=  ' <Order>';
                            $order_xml   .=  ' <OrderID><![CDATA[' . $order->id . ']]></OrderID>';
                            $order_xml   .=  ' <OrderNumber><![CDATA[' . $order->id . ']]></OrderNumber>';
                            $order_xml   .=  ' <OrderDate>12/18/2020 12:01 PM</OrderDate>';
                            $order_xml   .=  ' <OrderStatus><![CDATA[paid]]></OrderStatus>';
                            $order_xml   .=  ' <LastModified>12/18/2020 12:56 PM</LastModified>';
                            $order_xml   .=  ' <ShippingMethod><![CDATA[USPSPriorityMail]]></ShippingMethod>';
                            $order_xml   .=  ' <PaymentMethod><![CDATA[Credit Card]]></PaymentMethod>';
                            $order_xml   .=  ' <OrderTotal>' . $order->total . '</OrderTotal>';
                            $order_xml   .=  ' <TaxAmount>' . $order->tax . '</TaxAmount>';
                            $order_xml   .=  ' <ShippingAmount>' . $order->shipping_cost . '</ShippingAmount>';
                            $order_xml   .=  ' <CustomerNotes><![CDATA[' . $customer_note . ']]></CustomerNotes>';
                            $order_xml   .=  ' <InternalNotes><![CDATA[' . $internal_note . ']]></InternalNotes>';
                            $order_xml   .=  ' <Gift>false</Gift>';
                            $order_xml   .=  ' <GiftMessage></GiftMessage>';
                            $order_xml   .=  ' <CustomField1></CustomField1>';
                            $order_xml   .=  ' <CustomField2></CustomField2>';
                            $order_xml   .=  ' <CustomField3></CustomField3>';
                            $order_xml   .=  ' <Customer>';
                                $order_xml   .=  ' <CustomerCode><![CDATA[' . $customer_data->id . ']]></CustomerCode>';
                                $order_xml   .=  ' <BillTo>';
                                    $order_xml   .=  ' <Name><![CDATA[' . $order->billing_name . ']]></Name>';
                                    $order_xml   .=  ' <Company><![CDATA[' . $customer_data->company_name . ']]></Company>';
                                    $order_xml   .=  ' <Phone><![CDATA[' . $customer_data->phone . ']]></Phone>';
                                    $order_xml   .=  ' <Email><![CDATA[' . $customer_data->email . ']]></Email>';
                                $order_xml   .=  ' </BillTo>';
                                $order_xml   .=  ' <ShipTo>';
                                $order_xml   .=  ' <Name><![CDATA[The President]]></Name>';
                                $order_xml   .=  ' <Company><![CDATA[US Govt]]></Company>';
                                $order_xml   .=  ' <Address1><![CDATA[1600 Pennsylvania Ave]]></Address1>';
                                $order_xml   .=  ' <Address2></Address2>';
                                $order_xml   .=  ' <City><![CDATA[Washington]]></City>';
                                $order_xml   .=  ' <State><![CDATA[DC]]></State>';
                                $order_xml   .=  ' <PostalCode><![CDATA[20500]]></PostalCode>';
                                $order_xml   .=  ' <Country><![CDATA[US]]></Country>';
                                $order_xml   .=  ' <Phone><![CDATA[512-555-5555]]></Phone>';
                                $order_xml   .=  ' </ShipTo>';
                            $order_xml   .=  ' </Customer>';


                            $order_xml   .=  ' <Items>';
                                
                                foreach($order_details as $key => $order_item_value):
                                    $inventory_item = $this->inventory_model->get($order_item_value->product_id);

                                    $order_xml   .=  ' <Item>';
                                        $order_xml   .=  ' <SKU><![CDATA[' . $inventory_item->sku .  ']]></SKU>';
                                        $order_xml   .=  ' <Name><![CDATA[' . $order_item_value->product_name .  ']]></Name>';
                                        $order_xml   .=  ' <ImageUrl><![CDATA[' . $inventory_item->feature_image .  ']]></ImageUrl>';
                                        $order_xml   .=  ' <Weight>' . $inventory_item->weight .  '</Weight>';
                                        $order_xml   .=  ' <WeightUnits>Ounces</WeightUnits>';
                                        $order_xml   .=  ' <Quantity>' . $order_item_value->quantity .  '</Quantity>';
                                        $order_xml   .=  ' <UnitPrice>' . $order_item_value->product_unit_price .  '</UnitPrice>';
                                    $order_xml   .=  ' </Item>';

                                endforeach;

                               
                            $order_xml   .=  ' </Items>';
                        $order_xml   .=  ' </Order>';
                    endforeach;

                    $order_xml   .=  ' </Orders>';
                echo     $order_xml;
                die();
            }
        } 
        exit;
    }
 
    public function get_extra()
    {

        // $order_xml   .=  ' <Item>';
        // $order_xml   .=  ' <SKU></SKU>';
        // $order_xml   .=  ' <Name><![CDATA[$10 OFF]]></Name>';
        // $order_xml   .=  ' <Quantity>1</Quantity>';
        // $order_xml   .=  ' <UnitPrice>-10.00</UnitPrice>';
        // $order_xml   .=  ' <Adjustment>true</Adjustment>';
        // $order_xml   .=  ' </Item>';

         // $order_xml   .=  ' <Option>';
                                        //     $order_xml   .=  ' <Name><![CDATA[Size]]></Name>';
                                        //     $order_xml   .=  ' <Value><![CDATA[Large]]></Value>';
                                        //     $order_xml   .=  ' <Weight>10</Weight>';
                                        // $order_xml   .=  ' </Option>';
                                        // $order_xml   .=  ' <Option>';
                                        //     $order_xml   .=  ' <Name><![CDATA[Color]]></Name>';
                                        //     $order_xml   .=  ' <Value><![CDATA[Green]]></Value>';
                                        //     $order_xml   .=  ' <Weight>5</Weight>';
                                        // $order_xml   .=  ' </Option>';
    }



    public function get_shipping_cost()
    {

        /**
         * ShipStation API
         * Shipping Cost 
         * https://www.shipstation.com/docs/api/shipments/get-rates/ 
         * 
        */
        $carrier_code   = "fedex";   //*

        $from_postal_code   = 78703;  //*
        $to_state           = "DC";
        $to_country         = "US";  //*
        $to_postal_code     = 20500;  //*
        $to_city            = "Washington";
        $weight_value       = 3;     //*
        $weight_units       = "ounces";   //*
          
        $dimensions_units   = "inches";
        $dimensions_length  = 7;
        $dimensions_width   = 5;
        $dimensions_height  = 6;

        $confirmation   = "delivery";
        $residential    = "false";
        


        $user_name_as_key     = "6c15dc6d7bea48ed9a490f2515bd7a8e";
        // $user_name_as_key     = "ShipStation";
        $password_as_secret   = "e583d48514674abfbd85bb9ecabb4f5f";
        // $password_as_secret   = "Rocks";

        $authorization   = "Basic ". base64_encode($user_name_as_key . ":" . $password_as_secret);
 

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://ssapi.shipstation.com/shipments/getrates",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => FALSE,
            CURLOPT_SSL_VERIFYHOST => FALSE,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 40,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS =>"{\n  \"carrierCode\": '". $carrier_code ."' ,\n  \"serviceCode\": null,\n  \"packageCode\": null,\n  \"fromPostalCode\": " . $from_postal_code . ",\n  \"toState\": '" . $to_state . "',\n  \"toCountry\": '" . $to_country . "',\n  \"toPostalCode\": '" . $to_postal_code . "',\n  \"toCity\": '" . $to_city . "',\n  \"weight\": {\n    \"value\": " . $weight_value . ",\n    \"units\": '" . $weight_units . "'  \n  },\n  \"dimensions\": {\n    \"units\": '" . $dimensions_units . "',\n    \"length\": " . $dimensions_length . ",\n    \"width\": " . $dimensions_width . ",\n    \"height\": " . $dimensions_height . "\n  },\n  \"confirmation\": '" . $confirmation . "',\n  \"residential\": '" . $residential . "'\n}",
            CURLOPT_HTTPHEADER => array(
                "Host: ssapi.shipstation.com",
                "Authorization: " . $authorization,
                "Content-Type: application/json"
            ),
        ));

        $response = curl_exec($curl); 
        curl_close($curl); 
        $response = json_decode( $response );
        return $response;
        exit();

    }
}