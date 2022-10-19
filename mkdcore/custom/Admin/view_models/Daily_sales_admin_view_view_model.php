<?php defined('BASEPATH') or exit('No direct script access allowed');
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
/**
 * View Daily Sales View Model
 *
 * @copyright 2019 Manaknightdigital Inc.
 * @link https://manaknightdigital.com
 * @license Proprietary Software licensing
 * @author Ryan Wong
 */
class Daily_sales_admin_view_view_model
{
	protected $_entity;
	protected $_model;
	protected $_id;
	protected $_customer_phone;
	protected $_customer_email;
	protected $_billing_name;
	protected $_billing_country;
	protected $_billing_state;
	protected $_billing_city;
	protected $_billing_zip;
	protected $_billing_address;
	protected $_shipping_country;
	protected $_shipping_state;
	protected $_shipping_city;
	protected $_shipping_address;
	protected $_shipping_zip;
	protected $_order_date_time;
	protected $_payment_method;
	protected $_order_type;
	protected $_status;
	protected $_checkout_type;
	protected $_total;
	protected $_discount;
	protected $_shipping_cost;
	protected $_subtotal;
	protected $_shipping_cost_service_name;
	protected $_tax;
	protected $_ship_station_tracking_no;
	protected $_intent_data;
	protected $_refunded_amount;
	protected $_refund_response;


	public function __construct($entity)
	{
		$this->_entity = $entity;
	}

	public function get_entity()
	{
		return $this->_entity;
	}

	/**
	 * set_heading function
	 *
	 * @param string $heading
	 * @return void
	 */
	public function set_heading($heading)
	{
		$this->_heading = $heading;
	}

	/**
	 * get_heading function
	 *
	 * @return string
	 */
	public function get_heading()
	{
		return $this->_heading;
	}

	public function set_model($model)
	{
		$this->_model = $model;
		$this->_id = $model->id;
		$this->_customer_phone = $model->customer_phone;
		$this->_customer_email = $model->customer_email;
		$this->_billing_name = $model->billing_name;
		$this->_billing_country = $model->billing_country;
		$this->_billing_state = $model->billing_state;
		$this->_billing_city = $model->billing_city;
		$this->_billing_zip = $model->billing_zip;
		$this->_billing_address = $model->billing_address;
		$this->_shipping_country = $model->shipping_country;
		$this->_shipping_state = $model->shipping_state;
		$this->_shipping_city = $model->shipping_city;
		$this->_shipping_address = $model->shipping_address;
		$this->_shipping_zip = $model->shipping_zip;
		$this->_order_date_time = $model->order_date_time;
		$this->_payment_method = $model->payment_method;
		$this->_order_type = $model->order_type;
		$this->_status = $model->status;
		$this->_checkout_type = $model->checkout_type;
		$this->_total = $model->total;
		$this->_discount = $model->discount;
		$this->_shipping_cost = $model->shipping_cost;
		$this->_subtotal = $model->subtotal;
		$this->_shipping_cost_service_name = $model->shipping_cost_service_name;
		$this->_tax = $model->tax;
		$this->_ship_station_tracking_no = $model->ship_station_tracking_no;
		$this->_intent_data = $model->intent_data;
		$this->_refunded_amount = $model->refunded_amount;
		$this->_refund_response = $model->refund_response;
	}

	public function timeago($date)
	{
		$timestamp = strtotime($date);

		$strTime = array('second', 'minute', 'hour', 'day', 'month', 'year');
		$length = array('60', '60', '24', '30', '12', '10');

		$currentTime = time();
		if ($currentTime >= $timestamp) {
			$diff  = time() - $timestamp;

			for ($i = 0; $diff >= $length[$i] && $i < count($length) - 1; $i++) {
				$diff = $diff / $length[$i];
			}

			$diff = round($diff);
			return $diff . ' ' . $strTime[$i] . '(s) ago ';
		}
	}

	public function time_default_mapping()
	{
		$results = [];
		for ($i = 0; $i < 24; $i++) {
			for ($j = 0; $j < 60; $j++) {
				$hour = ($i < 10) ? '0' . $i : $i;
				$min = ($j < 10) ? '0' . $j : $j;
				$results[($i * 60) + $j] = "$hour:$min";
			}
		}
		return $results;
	}

	public function status_mapping()
	{
		return $this->_entity->status_mapping();
	}

	public function status_color_mapping()
	{
		return $this->_entity->status_color_mapping();
	}

	public function order_type_mapping()
	{
		return $this->_entity->order_type_mapping();
	}

	public function pos_pickup_status_mapping()
	{
		return $this->_entity->pos_pickup_status_mapping();
	}

	public function checkout_type_mapping()
	{
		return $this->_entity->checkout_type_mapping();
	}

	public function is_picked_mapping()
	{
		return $this->_entity->is_picked_mapping();
	}

	public function is_shipped_mapping()
	{
		return $this->_entity->is_shipped_mapping();
	}

	public function is_split_mapping()
	{
		return $this->_entity->is_split_mapping();
	}

	public function payment_method_mapping()
	{
		return $this->_entity->payment_method_mapping();
	}

	public function referrer_mapping()
	{
		return $this->_entity->referrer_mapping();
	}

	public function get_id()
	{
		return $this->_id;
	}

	public function set_id($id)
	{
		$this->_id = $id;
	}

	public function get_customer_phone()
	{
		return $this->_customer_phone;
	}

	public function set_customer_phone($customer_phone)
	{
		$this->_customer_phone = $customer_phone;
	}

	public function get_customer_email()
	{
		return $this->_customer_email;
	}

	public function set_customer_email($customer_email)
	{
		$this->_customer_email = $customer_email;
	}

	public function get_billing_name()
	{
		return $this->_billing_name;
	}

	public function set_billing_name($billing_name)
	{
		$this->_billing_name = $billing_name;
	}

	public function get_billing_country()
	{
		return $this->_billing_country;
	}

	public function set_billing_country($billing_country)
	{
		$this->_billing_country = $billing_country;
	}

	public function get_billing_state()
	{
		return $this->_billing_state;
	}

	public function set_billing_state($billing_state)
	{
		$this->_billing_state = $billing_state;
	}

	public function get_billing_city()
	{
		return $this->_billing_city;
	}

	public function set_billing_city($billing_city)
	{
		$this->_billing_city = $billing_city;
	}

	public function get_billing_zip()
	{
		return $this->_billing_zip;
	}

	public function set_billing_zip($billing_zip)
	{
		$this->_billing_zip = $billing_zip;
	}

	public function get_billing_address()
	{
		return $this->_billing_address;
	}

	public function set_billing_address($billing_address)
	{
		$this->_billing_address = $billing_address;
	}

	public function get_shipping_country()
	{
		return $this->_shipping_country;
	}

	public function set_shipping_country($shipping_country)
	{
		$this->_shipping_country = $shipping_country;
	}

	public function get_shipping_state()
	{
		return $this->_shipping_state;
	}

	public function set_shipping_state($shipping_state)
	{
		$this->_shipping_state = $shipping_state;
	}

	public function get_shipping_city()
	{
		return $this->_shipping_city;
	}

	public function set_shipping_city($shipping_city)
	{
		$this->_shipping_city = $shipping_city;
	}

	public function get_shipping_address()
	{
		return $this->_shipping_address;
	}

	public function set_shipping_address($shipping_address)
	{
		$this->_shipping_address = $shipping_address;
	}

	public function get_shipping_zip()
	{
		return $this->_shipping_zip;
	}

	public function set_shipping_zip($shipping_zip)
	{
		$this->_shipping_zip = $shipping_zip;
	}

	public function get_order_date_time()
	{
		return $this->_order_date_time;
	}

	public function set_order_date_time($order_date_time)
	{
		$this->_order_date_time = $order_date_time;
	}

	public function get_payment_method()
	{
		return $this->_payment_method;
	}

	public function set_payment_method($payment_method)
	{
		$this->_payment_method = $payment_method;
	}

	public function get_order_type()
	{
		return $this->_order_type;
	}

	public function set_order_type($order_type)
	{
		$this->_order_type = $order_type;
	}

	public function get_status()
	{
		return $this->_status;
	}

	public function set_status($status)
	{
		$this->_status = $status;
	}

	public function get_checkout_type()
	{
		return $this->_checkout_type;
	}

	public function set_checkout_type($checkout_type)
	{
		$this->_checkout_type = $checkout_type;
	}

	public function get_total()
	{
		return $this->_total;
	}

	public function set_total($total)
	{
		$this->_total = $total;
	}

	public function get_discount()
	{
		return $this->_discount;
	}

	public function set_discount($discount)
	{
		$this->_discount = $discount;
	}

	public function get_shipping_cost()
	{
		return $this->_shipping_cost;
	}

	public function set_shipping_cost($shipping_cost)
	{
		$this->_shipping_cost = $shipping_cost;
	}

	public function get_subtotal()
	{
		return $this->_subtotal;
	}

	public function set_subtotal($subtotal)
	{
		$this->_subtotal = $subtotal;
	}

	public function get_shipping_cost_service_name()
	{
		return $this->_shipping_cost_service_name;
	}

	public function set_shipping_cost_service_name($shipping_cost_service_name)
	{
		$this->_shipping_cost_service_name = $shipping_cost_service_name;
	}

	public function get_tax()
	{
		return $this->_tax;
	}

	public function set_tax($tax)
	{
		$this->_tax = $tax;
	}

	public function get_ship_station_tracking_no()
	{
		return $this->_ship_station_tracking_no;
	}

	public function set_ship_station_tracking_no($ship_station_tracking_no)
	{
		$this->_ship_station_tracking_no = $ship_station_tracking_no;
	}

	public function get_intent_data()
	{
		return $this->_intent_data;
	}

	public function set_intent_data($intent_data)
	{
		$this->_intent_data = $intent_data;
	}

	public function get_refunded_amount()
	{
		return $this->_refunded_amount;
	}

	public function set_refunded_amount($refunded_amount)
	{
		$this->_refunded_amount = $refunded_amount;
	}

	public function get_refund_response()
	{
		return $this->_refund_response;
	}

	public function set_refund_response($refund_response)
	{
		$this->_refund_response = $refund_response;
	}

	public function to_json()
	{
		return [
			'id' => $this->get_id(),
			'customer_phone' => $this->get_customer_phone(),
			'customer_email' => $this->get_customer_email(),
			'billing_name' => $this->get_billing_name(),
			'billing_country' => $this->get_billing_country(),
			'billing_state' => $this->get_billing_state(),
			'billing_city' => $this->get_billing_city(),
			'billing_zip' => $this->get_billing_zip(),
			'billing_address' => $this->get_billing_address(),
			'shipping_country' => $this->get_shipping_country(),
			'shipping_state' => $this->get_shipping_state(),
			'shipping_city' => $this->get_shipping_city(),
			'shipping_address' => $this->get_shipping_address(),
			'shipping_zip' => $this->get_shipping_zip(),
			'order_date_time' => $this->get_order_date_time(),
			'payment_method' => $this->get_payment_method(),
			'order_type' => $this->get_order_type(),
			'status' => $this->get_status(),
			'checkout_type' => $this->get_checkout_type(),
			'total' => $this->get_total(),
			'discount' => $this->get_discount(),
			'shipping_cost' => $this->get_shipping_cost(),
			'subtotal' => $this->get_subtotal(),
			'shipping_cost_service_name' => $this->get_shipping_cost_service_name(),
			'tax' => $this->get_tax(),
			'ship_station_tracking_no' => $this->get_ship_station_tracking_no(),
			'intent_data' => $this->get_intent_data(),
			'refunded_amount' => $this->get_refunded_amount(),
			'refund_response' => $this->get_refund_response(),
		];
	}
}
