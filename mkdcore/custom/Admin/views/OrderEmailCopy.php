<!DOCTYPE html>
<html>
<head>
	<title>Order Invoice</title>

	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" crossorigin="anonymous">
</head>
<body>
 
<div class="container" style="width: 100%;
    padding-right: 15px;
    padding-left: 15px;
    margin-right: auto;
    margin-left: auto;">
	

	 
	<div class="row mb-5" style="display: -ms-flexbox;display: flex;
    -ms-flex-wrap: wrap;flex-wrap: wrap;margin-right: -15px;
    margin-left: -15px;margin-bottom: 3rem!important;width: 100% !important">

	    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12" style="width: 100%;">

	        <div class="card pb-5" style="border-bottom:1px solid #ccc;position: relative;display: -ms-flexbox;display: flex;-ms-flex-direction: column;flex-direction: column;min-width: 0;word-wrap: break-word;background-color: #fff;background-clip: border-box;border: 1px solid rgba(0,0,0,.125);border-radius: .25rem;padding-bottom: 3rem!important;width: 100% !important;">
	            <div class="card-body" style="flex: 1 1 auto;padding: 1.25rem;width: 100% !important;">
	                <div class="text-center" style="text-align:center">
						<img class="logo-img mx-auto" style="padding-bottom: 9px;height:100px" src="<?php echo base_url() ?>uploads/vegas-liquidation.png"></a> 
					</div> 
					<h2 class="" style="margin-top:1.5rem; margin-bottom:1.5rem; padding:1.5rem;text-align:center; background-color:#333;color:#eee">
						 Thank you for your order!
					</h2>

					<table style="width: 100%;max-width: 100%;margin-bottom: 1rem;background-color: transparent; border-collapse: collapse; border: none; padding:1rem">
						<tbody>
							<tr style="padding:1rem;background-color:#ddd;color:#444;height:180px">
								<td style="width:33%">
									<div>
										<div>
											<div style="margin-top:1rem;"><span style="font-weight:bold">Order Number: </span> <?php echo $view_model->get_id();?> </div>
											<div style="margin-top:1rem;"><span style="font-weight:bold">Billing Name: </span> <?php echo $view_model->get_billing_name();?> </div>
										</div>
										<div>
											<div style="margin-top:1rem;"><span style="font-weight:bold">Order Date: </span><?php echo date('m-d-Y', strtotime( $view_model->get_order_date_time() ));?></div>
											<div style="margin-top:1rem;"><span style="font-weight:bold">Email: </span> <?php echo $view_model->get_customer_email();?> </div>
										</div>
									</div>
								</td>
								<td style="width:33%">
									<!-- Billing -->
									<div>
										<div style="font-weight:bold">Billing Details</div>
										<div style="margin-top:1.5rem; margin-bottom:1.3rem; ">
											<div><?php echo $view_model->get_billing_name();?> </div>
											<div><?php echo $view_model->get_billing_address();?> </div>
											<div><?php echo $view_model->get_billing_city() == '' ? '': $view_model->get_billing_city().',' ;?> <?php echo $view_model->get_billing_zip();?>  </div>
											<div><a href='tel:<?php echo $customer->phone?>'> <?php echo $customer->phone?>  </a>  </div>
										</div>
									</div>
								</td>
								<td style="width:33%">
									<!-- Shipping -->
									<div>
										<div style="font-weight:bold">Shipping Details</div>
										<div style="margin-top:1.5rem; margin-bottom:1.3rem; ">
											<div><?php echo $view_model->get_billing_name();?> </div>
											<div><?php echo $view_model->get_shipping_address();?> </div>
											<div><?php echo $view_model->get_shipping_city() == '' ? '': $view_model->get_shipping_city().',' ;?><?php echo $view_model->get_shipping_zip();?>  </div>
											<div><a href='tel:<?php echo $customer->phone?>'> <?php echo $customer->phone?>  </a>  </div>
										</div>
									</div>
								</td>
							</tr>
						</tbody>
					</table>
					

					<div>
						<h5 style="margin-top:1.5rem; margin-bottom:1.25rem;">Order Details</h5>

						<div>
						<table class="  " style="width: 100%;max-width: 100%;margin-bottom: 1rem;background-color: transparent; border-collapse: collapse; border: none; font-size:small">
	                            <thead class="" style="padding-top:1rem; padding-bottom:1rem; height:50px">
	                                <tr>
	                                    <th style="width:10%;color: #495057; background-color: #e9ecef; border-color: #dee2e6;" ></th> 
	                                    <th style="width:15%;color: #495057; background-color: #e9ecef; border-color: #dee2e6;text-align:left;" >Item Name</th> 
	                                    <th style="width:10%;color: #495057; background-color: #e9ecef; border-color: #dee2e6;text-align:left;" >Delivery Type</th>
	                                    <th style="width:15%;color: #495057; background-color: #e9ecef; border-color: #dee2e6;text-align:left;" >Shipping Service/Store</th>
	                                    <th style="width:10%;color: #495057; background-color: #e9ecef; border-color: #dee2e6;text-align:left;" >Rate</th> 
	                                    <th style="width:10%;color: #495057; background-color: #e9ecef; border-color: #dee2e6;text-align:left;" >Amount</th>
	                                </tr> 
	                            </thead>
	                            <tbody style="border-bottom: 1px solid #444">
	                            <?php foreach($orders_details as $key => $detail){  ?> 
	                                <tr style="border-top:1px solid #444;">
	                                    <td style="border: 0px solid #dee2e6;"><?php echo  $detail->product_image != '' ? "<img src='{$detail->product_image}' style='height:100px;width:100px'  class='' >": ''; ?> </td> 
	                                    <td style="border: 0px solid #dee2e6;"><?php echo  $detail->product_name; ?>  </td> 
	                                    <td style="border: 0px solid #dee2e6;"><?php echo  $detail->is_pickup == 1 ? 'Store Pickup' : 'Delivery'; ?></td>
	                                    <td style="border: 0px solid #dee2e6;">
											<?php if (!empty($detail->store)) : ?>
											
												<p style="margin-top:0; padding-top:0"><?php echo $detail->store->name ?> </p>
												<p style="margin-top:0; padding-top:0"><?php echo $detail->store->address ?> </p>
												<p style="margin-top:0; padding-top:0"><?php echo $detail->store->city." ". $detail->store->state. " ".$detail->store->zip." " ?> </p>
												<p style="margin-top:0; padding-top:0"><a href='tel:<?php echo $detail->store->phone?>'> <?php echo $detail->store->phone?>  </a></p>
											
											<?php endif; ?>
										</td>
	                                    <td style="border: 0px solid #dee2e6;">$<?php echo  number_format($detail->product_unit_price,2); ?> &times; <?php echo  $detail->quantity; ?> </td> 
	                                    <td style="border: 0px solid #dee2e6;"$><?php echo  number_format($detail->amount,2); ?></td>
	                                </tr>
	                            <?php } ?>
	                            </tbody>
	                            <tfoot>
								<tr>
									<td colspan="4"></td> 
									<td>Subtotal</td>
									<td>$<?php echo number_format($view_model->get_subtotal(),2);?></td>
								</tr> 
								<tr>
									<td colspan="4"></td> 
									<td>Shipping Cost</td>
									<td>$<?php echo number_format($view_model->get_shipping_cost(),2);?>
										<span><?php echo $view_model->get_shipping_cost_service_name() ; ?></span>
									</td>
								</tr>
								 

								<tr>
									<td colspan="4"></td> 
									<td>Tax</td>
									<td>$<?php echo number_format($view_model->get_tax(),2);?></td>
								</tr>


								<tr>
									<td colspan="4"></td> 
									<td>Discount</td>
									<td>$<?php echo number_format($view_model->get_discount(), 2);?></td>
								</tr>

								<tr style="font-weight:bold; border-top:1px solid #444;">
									<td colspan="4"></td> 
									<td >Total</td>
									<td>$
	                                    <?php  
	                                        echo number_format($view_model->get_total(), 2);
	                                    ?>
	                                    
	                                </td>
								</tr>
							    </tfoot>
	                        </table>
						</div>
					</div>

					
				 
					 
 

					<div class='row'  style="display: -ms-flexbox;display: flex;-ms-flex-wrap: wrap;flex-wrap: wrap;margin-right: -15px;margin-left: -15px;">
	                    <div class="col-md-12" style="width: 100%;">
							<div class="table-responsive" style="display: block;width: 100%;overflow-x: auto;-webkit-overflow-scrolling: touch;-ms-overflow-style: -ms-autohiding-scrollbar;">
	                        
							</div>
	                    </div> 
	                </div> 

	            </div>
	        </div>
	    </div>
	</div>


</div>
</body>
</html>


 