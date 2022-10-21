<?php
class Shipstation_api_service
{

    private $_config;


    public function set_config($config)
    {
        $this->_config = $config;
    }

    public function get_shipping_cost($orders_list, $postal_code, $city, $state, $country, $from_postal, $address_type)
    {

        if (!empty($orders_list)) {

            $free_ship = 0;

            $weight_object = 0;
            $dimensions_length = 0;
            $dimensions_width  = 0;
            $dimensions_height = 0;
            foreach ($orders_list as $key => $value) {
                $product        = $value->product_detail;
                $free_ship      = $product->free_ship;

                // if ($product->free_ship != 1) 
                // {
                $weight_object     += $product->weight;
                $dimensions_length += $product->length;
                $dimensions_width  += $product->width;
                $dimensions_height += $product->height;


                $weight_object = $value->product_qty * $weight_object;

                // } 

            }


            /**
             * ShipStation API
             * Shipping Cost 
             * https://www.shipstation.com/docs/api/shipments/get-rates/ 
             * 
             */


            $carrier_code       = "fedex";   //* 
            $from_postal_code   = $from_postal;


            // $from_postal_code   = 78703;  //*


            // $to_state           = "DC";
            // $to_city            = "Washington";
            // $to_country         = "US";  //*

            $to_state           = $state;
            $to_country         = $country;  //*
            $to_postal_code     = $postal_code;  //* 
            $to_city            = $city;
            $weight_value       = $weight_object * 16;     //*
            $weight_units       = "ounces";   //*

            $dimensions_units   = "inches";
            $dimensions_length  = $dimensions_length;
            $dimensions_width   = $dimensions_width;
            $dimensions_height  = $dimensions_height;

            $confirmation   = "none";
            $residential    = "false";



            $user_name_as_key     =  $this->_config->item('shipstation_user_name_as_key');
            $password_as_secret   =  $this->_config->item('shipstation_password_as_secret');

            // $user_name_as_key     = "ShipStation";
            // $password_as_secret   = "Rocks";


            $authorization   = "Basic " . base64_encode($user_name_as_key . ":" . $password_as_secret);


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
                CURLOPT_POSTFIELDS => "{\n  \"carrierCode\": '" . $carrier_code . "' ,\n  \"serviceCode\": null,\n  \"packageCode\": null,\n  \"fromPostalCode\": " . $from_postal_code . ",\n  \"toState\": '" . $to_state . "',\n  \"toCountry\": '" . $to_country . "',\n  \"toPostalCode\": '" . $to_postal_code . "',\n  \"toCity\": '" . $to_city . "',\n  \"weight\": {\n    \"value\": " . $weight_value . ",\n    \"units\": '" . $weight_units . "'  \n  },\n  \"dimensions\": {\n    \"units\": '" . $dimensions_units . "',\n    \"length\": " . $dimensions_length . ",\n    \"width\": " . $dimensions_width . ",\n    \"height\": " . $dimensions_height . "\n  },\n  \"confirmation\": '" . $confirmation . "',\n  \"residential\": '" . $residential . "'\n}",
                CURLOPT_HTTPHEADER => array(
                    "Host: ssapi.shipstation.com",
                    "Authorization: " . $authorization,
                    "Content-Type: application/json"
                ),
            ));

            $response = curl_exec($curl);
            curl_close($curl);
            $response = json_decode($response);


            if (!empty($response) and !isset($response->ExceptionMessage)) {
                foreach ($response as $key => &$value) {
                    $value = (object) $value;
                    $myDate = Date('Y-m-d');


                    $value->expected_date = "";
                    $value->expected_date_only = "";
                    $value->selected = "";

                    /*  Specification Requires Only three options 
                        Fedex Priority Overnight
                        Fedex 2Day
                        FedEx Ground free shipping (For business)/ Home Delivery (For Residential)
                    */

                    if (isset($value->serviceCode) && ($value->serviceCode != 'fedex_priority_overnight' &&
                        $value->serviceCode != 'fedex_2day' &&
                        $value->serviceCode != 'fedex_home_delivery' &&
                        $value->serviceCode != 'fedex_ground')) {

                        unset($response[$key]);
                    }
                    if (isset($value->serviceCode) && $value->serviceCode == 'fedex_express_saver') {
                        $expected_date_only = date('F d, Y', strtotime($myDate . ' +3 Weekday'));
                        $value->expected_date = "Expected Delivery Date <strong>" . $expected_date_only . "</strong>";
                        $value->expected_date_only =  $expected_date_only;
                    }


                    if (isset($value->serviceCode) && $value->serviceCode == 'fedex_standard_overnight') {
                        $expected_date_only = date('F d, Y', strtotime($myDate . ' +1 Weekday'));
                        $value->expected_date = "Expected Delivery Date <strong>" . $expected_date_only . "</strong>";
                        $value->expected_date_only =  $expected_date_only;
                    }

                    if (isset($value->serviceCode) && $value->serviceCode == 'fedex_ground' && ($free_ship == 1   || $address_type == 2)) {
                        $value->serviceName  = $value->serviceName . " Free Shipping";
                        $value->shipmentCost = 0;
                        $value->otherCost    = 0;

                        $value->selected = " checked ";
                    }



                    if (isset($value->serviceCode) && $value->serviceCode == 'fedex_2day') {
                        $expected_date_only = date('F d, Y', strtotime($myDate . ' +2 Weekday'));
                        $value->expected_date = "Expected Delivery Date <strong>" . $expected_date_only . "</strong>";
                        $value->expected_date_only =  $expected_date_only;
                    }


                    if (isset($value->serviceCode) && $value->serviceCode == 'fedex_2day_am') {
                        $expected_date_only = date('F d, Y', strtotime($myDate . ' +2 Weekday'));
                        $value->expected_date = "Expected Delivery Date <strong>" . $expected_date_only . "</strong>";
                        $value->expected_date_only =  $expected_date_only;
                    }


                    if (isset($value->serviceCode) && $value->serviceCode == 'fedex_priority_overnight') {
                        $expected_date_only = date('F d, Y', strtotime($myDate . ' +1 Weekday'));
                        $value->expected_date = "Expected Delivery Date <strong>" . $expected_date_only . "</strong>";
                        $value->expected_date_only =  $expected_date_only;
                    }

                    if (isset($value->serviceCode) && $value->serviceCode == 'fedex_first_overnight') {
                        $expected_date_only = date('F d, Y', strtotime($myDate . ' +1 Weekday'));
                        $value->expected_date = "Expected Delivery Date <strong>" . $expected_date_only . "</strong>";
                        $value->expected_date_only =  $expected_date_only;
                    }



                    if (isset($value->serviceCode) && $value->serviceCode == 'fedex_home_delivery') {
                        $expected_date_only = date('F d, Y', strtotime($myDate . ' +7 days '));
                        $value->expected_date = "Expected Delivery Date <strong>" . $expected_date_only . "</strong>";
                        $value->expected_date_only =  $expected_date_only;
                    }


                    if (isset($value->serviceCode) && $value->serviceCode == 'fedex_ground') {
                        $expected_date_only = date('F d, Y', strtotime($myDate . ' +7 days'));
                        $value->expected_date = "Expected Delivery Date <strong>" . $expected_date_only . "</strong>";
                        $value->expected_date_only =  $expected_date_only;
                    }

                    // 2 for business and 1 for home and 3 for others
                    //If business address remove home delivery and make ground shipping $0 free delivery
                    if (isset($value->serviceCode) && $value->serviceCode == 'fedex_home_delivery'  && $address_type == 2) {
                        unset($response[$key]);
                    }

                    // if (isset($value->serviceCode) && $value->serviceCode == 'fedex_ground'  && $address_type == 2 ) 
                    // {
                    //     $value->serviceName  = $value->serviceName . " Free Shipping";
                    //     $value->shipmentCost = 0;
                    //     $value->otherCost    = 0;
                    // }




                    //If address is home or house remove ground shipping and make home delivery $0 free shipping
                    if (isset($value->serviceCode) && $value->serviceCode == 'fedex_ground'  && $address_type == 1) {

                        unset($response[$key]);
                    }

                    if (isset($value->serviceCode) && $value->serviceCode == 'fedex_home_delivery'  && $address_type == 1) {
                        $value->serviceName  = $value->serviceName . " Free Shipping";
                        $value->shipmentCost = 0;
                        $value->otherCost    = 0;

                        $value->selected = " checked ";

                        $expected_date_only = date('F d, Y', strtotime($myDate . ' +7 days '));
                        $value->expected_date = "Expected Delivery Date <strong>" . $expected_date_only . "</strong>";
                        $value->expected_date_only =  $expected_date_only;
                    }
                }
                $response = array_values($response);
            }


            return  $response;
            exit();
        }
    }




    public function get_order($order_id, $order_no, $total, $tax, $ship_cost, $customer_note, $internal_note, $customer_email, $customer_name, $customer_company, $customer_phone)
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
        $xmlStr .= '<OrderNumber>' . $order_number . '</OrderNumber>';
        $xmlStr .= '<OrderID>' . $order_id . '</OrderID>';
        $xmlStr .= '<CustomerCode>' . $customer_code . '</CustomerCode>';
        $xmlStr .= '<CustomerNotes></CustomerNotes>';
        $xmlStr .= '<InternalNotes></InternalNotes>';
        $xmlStr .= '<NotesToCustomer></NotesToCustomer>';
        $xmlStr .= '<NotifyCustomer></NotifyCustomer>';
        $xmlStr .= '<LabelCreateDate>' . $label_create_date . '</LabelCreateDate>';
        $xmlStr .= '<ShipDate>' . $ship_date . '</ShipDate>';
        $xmlStr .= '<Carrier>' . $carrier . '</Carrier>';
        $xmlStr .= '<Service>Priority Mail</Service>';
        $xmlStr .= '<TrackingNumber>' . $tracking_number . '</TrackingNumber>';
        $xmlStr .= '<ShippingCost>' . $shipping_cost . '</ShippingCost>';
        $xmlStr .= '<CustomField1></CustomField1>';
        $xmlStr .= '<CustomField2></CustomField2>';
        $xmlStr .= '<CustomField3></CustomField3>';
        $xmlStr .= '<Recipient>';
        $xmlStr .= '<Name>' . $recipient_name . '</Name>';
        $xmlStr .= '<Company>' . $recipient_company . '</Company>';
        $xmlStr .= '<Address1>' . $recipient_address1 . '</Address1>';
        $xmlStr .= '<Address2>' . $recipient_address2 . '</Address2>';
        $xmlStr .= '<City>' . $recipient_city . '</City>';
        $xmlStr .= '<State>' . $recipient_state . '</State>';
        $xmlStr .= '<PostalCode>.' . $recipient_postal_code . '.</PostalCode>';
        $xmlStr .= '<Country>' . $recipient_country . '</Country>';
        $xmlStr .= '</Recipient>';
        $xmlStr .= '<Items>';
        $xmlStr .= '<Item>';
        $xmlStr .= '<SKU>' . $sku . '</SKU>';
        $xmlStr .= '<Name>' . $item_name . '</Name>';
        $xmlStr .= '<Quantity>' . $item_quantity . '</Quantity>';
        $xmlStr .= '<LineItemID>' . $line_item_id . '</LineItemID>';
        $xmlStr .= '</Item>';
        $xmlStr .= '</Items>';
        $xmlStr .= '</ShipNotice>';


        //The XML string that you want to send.

        // route
        $endpoint_url = "https://www.example.com";

        $route = "v1/api/admin/test_shipstation";

        $post_url = $endpoint_url . '/action=shipnotify&order_number=' . $order_number . '&carrier=' . $carrier . '&service=&tracking_number=' . $tracking_number;
        //The URL that you want to send your XML to.
        // $url = 'http://localhost/xml';

        //Initiate cURL
        $curl = curl_init($post_url);

        //Set the Content-Type to text/xml.
        curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-Type: text/xml"));

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
        if (curl_errno($curl)) {
            throw new Exception(curl_error($curl));
        }

        //Close the cURL handle.
        curl_close($curl);

        //Print out the response output.
        echo $result;
    }

    public function get_list_of_services()
    {

        /**
         * ShipStation API
         * List Services
         * https://www.shipstation.com/docs/api/carriers/list-services/
         * 
         */


        $user_name_as_key     =  $this->_config->item('shipstation_user_name_as_key');
        $password_as_secret   =  $this->_config->item('shipstation_password_as_secret');


        $carrier_code   =  "fedex";

        // $user_name_as_key     = "ShipStation";
        // $password_as_secret   = "Rocks";


        $authorization   = "Basic " . base64_encode($user_name_as_key . ":" . $password_as_secret);



        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://ssapi.shipstation.com/carriers/listservices?carrierCode=" . $carrier_code,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => FALSE,
            CURLOPT_SSL_VERIFYHOST => FALSE,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Host: ssapi.shipstation.com",
                "Authorization: " . $authorization
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        return json_decode($response);
    }


    /* Validate Address using Smarty Us Address Api 
        RDI response type: Residential, Commercial or [blank] (and empty response for address that couldn't be validated)
    */
    public function validate_address($street = "", $city = "", $state = "", $zip = "", $country = "US")
    {
        $smarty_street_api_base_url = $this->_config->item("smarty_street_url");
        $url = $smarty_street_api_base_url . "?";

        $smarty_auth_id = $this->_config->item("smarty_auth_id");
        $smarty_auth_token = $this->_config->item("smarty_auth_token");

        $url .= "auth-id=$smarty_auth_id";
        $url .= "&auth-token=$smarty_auth_token";
        $url .= "&street=" . urlencode($street);
        $url .= "&city=" . urlencode($city);
        $url .= "&state=" . urlencode($state);
        $url .= "&zip=" . urlencode($zip);

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        //for debug only!
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);


        $resp = curl_exec($curl);
        curl_close($curl);


        $resp = json_decode($resp);
        $output = false;

        // echo '<pre>';
        // var_dump($resp);
        // echo '</pre>';

        if (empty($resp)) {
            $output = false;
        } else {
            $output = isset($resp[0]->metadata->rdi) ?  $resp[0]->metadata->rdi : false;
        }

        // echo '<pre>';
        // var_dump($output);
        // echo '</pre>';

        return $output;
    }

    public function validate_address_upsp($street = "", $city = "", $state = "", $zip = "", $country = "US")
    {
        $user_id = $this->_config->item("usps_user_id");;
        //$xmlStr = "&XML=";
        $xmlStr = '<AddressValidateRequest USERID="' . $user_id . '">';
        $xmlStr .= '<Revision>1</Revision>';
        $xmlStr .= '<Address ID="0">';
        $xmlStr .= '<Address1>' . $street . '</Address1>';
        $xmlStr .= '<Address2>' . $street . '</Address2>';
        $xmlStr .= '<City/>';
        $xmlStr .= '<State>' . $state . '</State>';
        $xmlStr .= '<Zip5>' . $zip . '</Zip5>';
        $xmlStr .= '<Zip4/>';
        $xmlStr .= '</Address>';
        $xmlStr .= '</AddressValidateRequest>';

        //The XML string that you want to send.

        // route
        $endpoint_url = $this->_config->item("usps_url");;

        //The URL that you want to send your XML to.
        // $url = 'http://localhost/xml';

        $post_url  = $endpoint_url . urlencode($xmlStr);
        //Initiate cURL
        $curl = curl_init($post_url);

        //Set the Content-Type to text/xml.
        curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-Type: text/xml"));

        //Set CURLOPT_POST to true to send a GET request.
        curl_setopt($curl, CURLOPT_HTTPGET, true);

        curl_setopt($curl, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        //Tell cURL that we want the response to be returned as
        //a string instead of being dumped to the output.
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        //Execute the POST request and send our XML.
        $result = curl_exec($curl);

        // echo '<pre>';
        // var_dump($result);
        // echo '</pre>';

        //Do some basic error checking.
        if (curl_errno($curl)) {
            throw new Exception(curl_error($curl));
        }

        //Close the cURL handle.
        curl_close($curl);

        //Convert XML to array
        $result = simplexml_load_string($result);

        $output = false;

        if ($result->Address->Business == 'Y') {

            $output = "Commercial";
        } else if ($result->Address->Business == 'N') {

            $output = "Residential";
        } else {
            $output = false;
        }

        return $output;
    }

    public function create_order()
    {


        $order_id = null;
        $order_date = date('Y-m-d\TH:i:s.000000');
        $user_name_as_key     =  $this->_config->item('shipstation_user_name_as_key');
        $password_as_secret   =  $this->_config->item('shipstation_password_as_secret');


        $carrier_code   =  "fedex";
        $authorization   = "Basic " . base64_encode($user_name_as_key . ":" . $password_as_secret);

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://ssapi.shipstation.com/orders/createorder",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "{\n  \"orderNumber\": \"{$order_id}\",\n  
                                    \"orderDate\": \"{$order_date}\",\n 
                                    \"orderStatus\": \"awaiting_shipment\",\n  
                                    \"billTo\": {\n    
                                        \"name\": \"The President\",\n    
                                        \"company\": null,\n    
                                        \"street1\": null,\n   
                                         \"street2\": null,\n    
                                         \"street3\": null,\n    
                                         \"city\": null,\n    
                                         \"state\": null,\n    
                                         \"postalCode\": null,\n    
                                         \"country\": null,\n   
                                         \"phone\": null,\n    
                                         \"residential\": null\n  },\n  
                                    \"shipTo\": {\n    
                                        \"name\": \"The President\",\n    
                                        \"company\": \"US Govt\",\n    
                                        \"street1\": \"1600 Pennsylvania Ave\",\n    
                                        \"street2\": \"Oval Office\",\n    
                                        \"street3\": null,\n   
                                        \"city\": \"Washington\",\n    
                                        \"state\": \"DC\",\n    
                                        \"postalCode\": \"20500\",\n    
                                        \"country\": \"US\",\n    
                                        \"phone\": \"555-555-5555\",\n    
                                        \"residential\": true\n  },\n  
                                    \"items\": [\n    
                                        {\n    
                                            \"lineItemKey\": \"vd08-MSLbtx\",\n      
                                            \"sku\": \"ABC123\",\n      
                                            \"name\": \"Test item #1\",\n      
                                            \"imageUrl\": null,\n      
                                            \"weight\": {\n        
                                                \"value\": 24,\n       
                                                 \"units\": \"ounces\"\n      
                                                },\n      
                                            \"quantity\": 2,\n      
                                            \"unitPrice\": 99.99,\n      
                                            \"taxAmount\": 2.5,\n      
                                            \"shippingAmount\": 5,\n      
                                            \"warehouseLocation\": \"Aisle 1, Bin 7\",\n      
                                            \"options\": [\n        
                                                {\n          
                                                    \"name\": \"Size\",\n          
                                                    \"value\": \"Large\"\n        
                                                }\n      ],\n      
                                            \"productId\": 123456,\n      
                                            \"fulfillmentSku\": null,\n      
                                            \"adjustment\": false,\n      
                                            \"upc\": \"32-65-98\"\n    
                                        } 
                                        ],\n 
                                    \"amountPaid\": 218.73,\n  
                                    \"taxAmount\": 5,\n  
                                    \"shippingAmount\": 10,\n  
                                    \"customerNotes\": \"Please ship as soon as possible!\",\n 
                                    \"internalNotes\": \"Customer called and would like to upgrade shipping\",\n 
                                    \"gift\": true,\n  
                                    \"giftMessage\": \"Thank you!\",\n  
                                    \"paymentMethod\": \"Credit Card\",\n  
                                    \"requestedShippingService\": \"Priority Mail\",\n 
                                    \"carrierCode\": \"fedex\",\n  
                                    \"serviceCode\": \"fedex_2day\",\n 
                                    \"packageCode\": \"package\",\n  
                                    \"confirmation\": \"delivery\",\n  
                                    \"shipDate\": \"2015-07-02\",\n  
                                    \"weight\": {\n    
                                        \"value\": 25,\n    
                                        \"units\": \"ounces\"\n  
                                    },\n  
                                    \"dimensions\": {\n    
                                        \"units\": \"inches\",\n    
                                        \"length\": 7,\n    
                                        \"width\": 5,\n    
                                        \"height\": 6\n  
                                    },\n  
                                    \"insuranceOptions\": {\n    
                                        \"provider\": \"carrier\",\n    
                                        \"insureShipment\": true,\n    
                                        \"insuredValue\": 200\n  
                                    },\n  
                                    \"internationalOptions\": {\n    
                                        \"contents\": null,\n    
                                        \"customsItems\": null\n  
                                    },\n  
                                    \"advancedOptions\": {\n    
                                        \"warehouseId\": 98765,\n    
                                        \"nonMachinable\": false,\n    
                                        \"saturdayDelivery\": false,\n    
                                        \"containsAlcohol\": false,\n    
                                        \"mergedOrSplit\": false,\n    
                                        \"mergedIds\": [],\n    
                                        \"parentId\": null,\n    
                                        \"storeId\": 12345,\n    
                                        \"customField1\": \"Custom data that you can add to an order. See Custom Field #2 & #3 for more info!\",\n    
                                        \"customField2\": \"Per UI settings, this information can appear on some carrier's shipping labels. See link below\",\n    
                                        \"customField3\": \"https://help.shipstation.com/hc/en-us/articles/206639957\",\n    
                                        \"source\": \"Webstore\",\n    
                                        \"billToParty\": null,\n    
                                        \"billToAccount\": null,\n    
                                        \"billToPostalCode\": null,\n    
                                        \"billToCountryCode\": null\n  
                                    },\n  
                                    \"tagIds\": [\n    53974\n  ]\n}",

            CURLOPT_HTTPHEADER => array(
                "Host: ssapi.shipstation.com",
                "Authorization: " . $authorization,
                "Content-Type: application/json"
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        echo $response;
    }
}
