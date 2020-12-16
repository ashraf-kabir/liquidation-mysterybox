<?php 
class Shipstation_api_service { 
 
    

    public function post_shipment_info()
    {

        // $order_number = $this->input->post('order_number',TRUE);
        // $order_id = $this->input->post('order_id',TRUE);

        // // email: customer@mystore.com
        // $customer_code = $this->input->post('customer_code',TRUE);
        
        // // 10/19/2019 12:56
        // $label_create_date = $this->input->post('label_create_date',TRUE);
        
        // // 10/19/2019
        // $ship_date = $this->input->post('ship_date',TRUE);
        
        // $carrier = $this->input->post('carrier',TRUE);
        // $tracking_number = $this->input->post('tracking_number',TRUE);
        // $shipping_cost = $this->input->post('shipping_cost',TRUE);
        // $recipient_name = $this->input->post('recipient_name',TRUE);
        // $recipient_company = $this->input->post('recipient_company',TRUE);
        // $recipient_address1 = $this->input->post('recipient_address1',TRUE);
        // $recipient_address2 = $this->input->post('recipient_address2',TRUE);
        // $recipient_city = $this->input->post('recipient_city',TRUE);
        // $recipient_state = $this->input->post('recipient_state',TRUE);
        // $recipient_postal_code = $this->input->post('recipient_postal_code',TRUE);
        // $recipient_country = $this->input->post('recipient_country',TRUE);
        // $sku = $this->input->post('sku',TRUE);
        // $item_name = $this->input->post('item_name',TRUE);
        // $item_quantity = $this->input->post('item_quantity',TRUE);
        // $line_item_id = $this->input->post('line_item_id',TRUE);


        // ########################################### hard coded ################################################
        $order_number = "456789";
        $order_id = "abcdef12";

        
        // email: customer@mystore.com
        $customer_code = "customer@mystore.com";
        
        // 10/19/2019 12:56
        $label_create_date = "10/19/2019 12:56";
        
        // 10/19/2019
        $ship_date = "10/19/2019";
        
        $carrier = "USPS";
        $tracking_number = "trn_19726378123jabdkjakjsadg";
        $shipping_cost = "10";
        $recipient_name = "Luka Modric";
        $recipient_company = "Modric Inc.";
        $recipient_address1 = "123 Street";
        $recipient_address2 = "";
        $recipient_city = "Madrid";
        $recipient_state = "Central Madrid";
        $recipient_postal_code = "3456";
        $recipient_country = "Spain";
        $sku = "1927381731231234";
        $item_name = "Random Item";
        $item_quantity = "5";
        $line_item_id = "item_qwerty1234";

        // basic authentication header

        $xmlStr = '<?xml version="1.0" encoding="utf-8"?>';
        $xmlStr .= '<ShipNotice>';
        $xmlStr .= '<OrderNumber>'.$order_number.'</OrderNumber>';
        $xmlStr .= '<OrderID>'.$order_id.'</OrderID>';
        $xmlStr .= '<CustomerCode>'.$customer_code.'</CustomerCode>';
        $xmlStr .= '<CustomerNotes></CustomerNotes>';
        $xmlStr .= '<InternalNotes></InternalNotes>';
        $xmlStr .= '<NotesToCustomer></NotesToCustomer>';
        $xmlStr .= '<NotifyCustomer></NotifyCustomer>';
        $xmlStr .= '<LabelCreateDate>'.$label_create_date.'</LabelCreateDate>';
        $xmlStr .= '<ShipDate>'.$ship_date.'</ShipDate>';
        $xmlStr .= '<Carrier>'.$carrier.'</Carrier>';
        $xmlStr .= '<Service>Priority Mail</Service>';
        $xmlStr .= '<TrackingNumber>'.$tracking_number.'</TrackingNumber>';
        $xmlStr .= '<ShippingCost>'.$shipping_cost.'</ShippingCost>';
        $xmlStr .= '<CustomField1></CustomField1>';
        $xmlStr .= '<CustomField2></CustomField2>';
        $xmlStr .= '<CustomField3></CustomField3>';
        $xmlStr .= '<Recipient>';
        $xmlStr .= '<Name>'.$recipient_name.'</Name>';
        $xmlStr .= '<Company>'.$recipient_company.'</Company>';
        $xmlStr .= '<Address1>'.$recipient_address1.'</Address1>';
        $xmlStr .= '<Address2>'.$recipient_address2.'</Address2>';
        $xmlStr .= '<City>'.$recipient_city.'</City>';
        $xmlStr .= '<State>'.$recipient_state.'</State>';
        $xmlStr .= '<PostalCode>.'.$recipient_postal_code.'.</PostalCode>';
        $xmlStr .= '<Country>'.$recipient_country.'</Country>';
        $xmlStr .= '</Recipient>';
        $xmlStr .= '<Items>';
        $xmlStr .= '<Item>';
        $xmlStr .= '<SKU>'.$sku.'</SKU>';
        $xmlStr .= '<Name>'.$item_name.'</Name>';
        $xmlStr .= '<Quantity>'.$item_quantity.'</Quantity>';
        $xmlStr .= '<LineItemID>'.$line_item_id.'</LineItemID>';
        $xmlStr .= '</Item>';
        $xmlStr .= '</Items>';
        $xmlStr .= '</ShipNotice>';


        //The XML string that you want to send.

        // route
        $endpoint_url = "https://www.example.com";

        $route = "v1/api/admin/test_shipstation";

        $post_url = $endpoint_url. '/action=shipnotify&order_number='.$order_number.'&carrier='.$carrier.'&service=&tracking_number='.$tracking_number;
        //The URL that you want to send your XML to.
        // $url = 'http://localhost/xml';

        //Initiate cURL
        $curl = curl_init($post_url);

        //Set the Content-Type to text/xml.
        curl_setopt ($curl, CURLOPT_HTTPHEADER, array("Content-Type: text/xml"));

        //Set CURLOPT_POST to true to send a POST request.
        curl_setopt($curl, CURLOPT_POST, true);

        //Attach the XML string to the body of our request.
        curl_setopt($curl, CURLOPT_POSTFIELDS, $xmlStr);

        //Tell cURL that we want the response to be returned as
        //a string instead of being dumped to the output.
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        //Execute the POST request and send our XML.
        $result = curl_exec($curl);

        //Do some basic error checking.
        if(curl_errno($curl)){
            throw new Exception(curl_error($curl));
        }

        //Close the cURL handle.
        curl_close($curl);

        //Print out the response output.
        echo $result;
    }
}

?>