<?php 
class Shipstation_api_service { 
    


    public function get_shipping_cost($orders_list, $postal_code, $city, $state, $country)
    {
        if( !empty($orders_list) )
        {

            $weight_object = 0;
            foreach($orders_list as $key => $value)
            {
                $product = $value->product_detail;    

                $weight_object += $product->weight;
            }
        
            /**
             * ShipStation API
             * Shipping Cost 
             * https://www.shipstation.com/docs/api/shipments/get-rates/ 
             * 
            */
            

            $carrier_code   = "fedex";   //*

            $from_postal_code   = 78703;  //*


            // $to_state           = "DC";
            $to_state           = $state;
            // $to_country         = "US";  //*
            $to_country         = $country;  //*
            $to_postal_code     = $postal_code;  //*
            // $to_city            = "Washington";
            $to_city            = $city;
            $weight_value       = $weight_object;     //*
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




    public function get_order($order_id,$order_no,$total,$tax,$ship_cost,$customer_note,$internal_note,$customer_email, $customer_name, $customer_company, $customer_phone )
    {
        $order_xml = "";
        

        $order_id       = $order_id;
        $order_no       = $order_no;
        $total          = $total;
        $tax            = $tax;
        $ship_cost      = $ship_cost;

        $customer_note   = $customer_note;
        $internal_note   = $internal_note;
        $customer_email  = $customer_email;
        $customer_name   = $customer_name;

        $customer_company   = $customer_company;
        $customer_phone     = $customer_phone;
        

        $order_xml   .=  ' <?xml version="1.0" encoding="utf-8"?> ';
        $order_xml   .=  ' <Orders pages="1">';
            $order_xml   .=  ' <Order>';
                $order_xml   .=  ' <OrderID><![CDATA[' . $order_id . ']]></OrderID>';
                $order_xml   .=  ' <OrderNumber><![CDATA[' . $order_no . ']]></OrderNumber>';
                $order_xml   .=  ' <OrderDate>10/18/2019 21:56 PM</OrderDate>';
                $order_xml   .=  ' <OrderStatus><![CDATA[paid]]></OrderStatus>';
                $order_xml   .=  ' <LastModified>12/8/2011 12:56 PM</LastModified>';
                $order_xml   .=  ' <ShippingMethod><![CDATA[USPSPriorityMail]]></ShippingMethod>';
                $order_xml   .=  ' <PaymentMethod><![CDATA[Credit Card]]></PaymentMethod>';
                $order_xml   .=  ' <OrderTotal>' . $total . '</OrderTotal>';
                $order_xml   .=  ' <TaxAmount>' . $tax . '</TaxAmount>';
                $order_xml   .=  ' <ShippingAmount>' . $ship_cost . '</ShippingAmount>';
                $order_xml   .=  ' <CustomerNotes><![CDATA[' . $customer_note . ']]></CustomerNotes>';
                $order_xml   .=  ' <InternalNotes><![CDATA[' . $internal_note . ']]></InternalNotes>';
                $order_xml   .=  ' <Gift>false</Gift>';
                $order_xml   .=  ' <GiftMessage></GiftMessage>';
                $order_xml   .=  ' <CustomField1></CustomField1>';
                $order_xml   .=  ' <CustomField2></CustomField2>';
                $order_xml   .=  ' <CustomField3></CustomField3>';
                $order_xml   .=  ' <Customer>';
                    $order_xml   .=  ' <CustomerCode><![CDATA[' . $customer_email . ']]></CustomerCode>';
                    $order_xml   .=  ' <BillTo>';
                        $order_xml   .=  ' <Name><![CDATA[' . $customer_name . ']]></Name>';
                        $order_xml   .=  ' <Company><![CDATA[' . $customer_company . ']]></Company>';
                        $order_xml   .=  ' <Phone><![CDATA[' . $customer_phone . ']]></Phone>';
                        $order_xml   .=  ' <Email><![CDATA[' . $customer_email . ']]></Email>';
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
                    $order_xml   .=  ' <Item>';
                        $order_xml   .=  ' <SKU><![CDATA[FD88821]]></SKU>';
                        $order_xml   .=  ' <Name><![CDATA[My Product Name]]></Name>';
                        $order_xml   .=  ' <ImageUrl><![CDATA[http://www.mystore.com/products/12345.jpg]]></ImageUrl>';
                        $order_xml   .=  ' <Weight>8</Weight>';
                        $order_xml   .=  ' <WeightUnits>Ounces</WeightUnits>';
                        $order_xml   .=  ' <Quantity>2</Quantity>';
                        $order_xml   .=  ' <UnitPrice>13.99</UnitPrice>';
                        $order_xml   .=  ' <Location><![CDATA[A1-B2]]></Location>';
                        $order_xml   .=  ' <Options>';
                            $order_xml   .=  ' <Option>';
                                $order_xml   .=  ' <Name><![CDATA[Size]]></Name>';
                                $order_xml   .=  ' <Value><![CDATA[Large]]></Value>';
                                $order_xml   .=  ' <Weight>10</Weight>';
                            $order_xml   .=  ' </Option>';
                            $order_xml   .=  ' <Option>';
                                $order_xml   .=  ' <Name><![CDATA[Color]]></Name>';
                                $order_xml   .=  ' <Value><![CDATA[Green]]></Value>';
                                $order_xml   .=  ' <Weight>5</Weight>';
                            $order_xml   .=  ' </Option>';
                        $order_xml   .=  ' </Options>';
                    $order_xml   .=  ' </Item>';
                    $order_xml   .=  ' <Item>';
                        $order_xml   .=  ' <SKU></SKU>';
                        $order_xml   .=  ' <Name><![CDATA[$10 OFF]]></Name>';
                        $order_xml   .=  ' <Quantity>1</Quantity>';
                        $order_xml   .=  ' <UnitPrice>-10.00</UnitPrice>';
                        $order_xml   .=  ' <Adjustment>true</Adjustment>';
                    $order_xml   .=  ' </Item>';
                $order_xml   .=  ' </Items>';
            $order_xml   .=  ' </Order>';
        $order_xml   .=  ' </Orders>';


        return $order_xml;

    }

    

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