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
	                 
	                
					<div class='row mb-4'  style="margin-bottom: 1.5rem!important;display: flex;-ms-flex-wrap: wrap; flex-wrap: wrap; margin-right: -15px; margin-left: -15px;"  >
						<div class='col'  style="flex-basis: 0; -ms-flex-positive: 1; flex-grow: 1; max-width: 100%;position: relative; width: 100%; min-height: 1px;padding-right: 15px; padding-left: 15px;">
							ID
						</div>
						<div class='col'  style="flex-basis: 0; -ms-flex-positive: 1; flex-grow: 1; max-width: 100%;position: relative; width: 100%; min-height: 1px;padding-right: 15px; padding-left: 15px;">
							<?php echo $view_model->get_id();?>
						</div>
					</div>

					<div class='row mb-4'  style="margin-bottom: 1.5rem!important;display: flex;-ms-flex-wrap: wrap; flex-wrap: wrap; margin-right: -15px; margin-left: -15px;"  >
						<div class='col'  style="flex-basis: 0; -ms-flex-positive: 1; flex-grow: 1; max-width: 100%;position: relative; width: 100%; min-height: 1px;padding-right: 15px; padding-left: 15px;">
							Billing Name
						</div>
						<div class='col'  style="flex-basis: 0; -ms-flex-positive: 1; flex-grow: 1; max-width: 100%;position: relative; width: 100%; min-height: 1px;padding-right: 15px; padding-left: 15px;">
							<?php echo $view_model->get_billing_name();?>
						</div>
					</div>

					<div class='row mb-4'  style="margin-bottom: 1.5rem!important;display: flex;-ms-flex-wrap: wrap; flex-wrap: wrap; margin-right: -15px; margin-left: -15px;"  >
						<div class='col'  style="flex-basis: 0; -ms-flex-positive: 1; flex-grow: 1; max-width: 100%;position: relative; width: 100%; min-height: 1px;padding-right: 15px; padding-left: 15px;">
							Billing Country
						</div>
						<div class='col'  style="flex-basis: 0; -ms-flex-positive: 1; flex-grow: 1; max-width: 100%;position: relative; width: 100%; min-height: 1px;padding-right: 15px; padding-left: 15px;">
							<?php echo $view_model->get_billing_country();?>
						</div>
					</div>

					<div class='row mb-4'  style="margin-bottom: 1.5rem!important;display: flex;-ms-flex-wrap: wrap; flex-wrap: wrap; margin-right: -15px; margin-left: -15px;"  >
						<div class='col'  style="flex-basis: 0; -ms-flex-positive: 1; flex-grow: 1; max-width: 100%;position: relative; width: 100%; min-height: 1px;padding-right: 15px; padding-left: 15px;">
							Billing City
						</div>
						<div class='col'  style="flex-basis: 0; -ms-flex-positive: 1; flex-grow: 1; max-width: 100%;position: relative; width: 100%; min-height: 1px;padding-right: 15px; padding-left: 15px;">
							<?php echo $view_model->get_billing_city();?>
						</div>
					</div>

					<div class='row mb-4'  style="margin-bottom: 1.5rem!important;display: flex;-ms-flex-wrap: wrap; flex-wrap: wrap; margin-right: -15px; margin-left: -15px;"  >
						<div class='col'  style="flex-basis: 0; -ms-flex-positive: 1; flex-grow: 1; max-width: 100%;position: relative; width: 100%; min-height: 1px;padding-right: 15px; padding-left: 15px;">
							Billing Zip
						</div>
						<div class='col'  style="flex-basis: 0; -ms-flex-positive: 1; flex-grow: 1; max-width: 100%;position: relative; width: 100%; min-height: 1px;padding-right: 15px; padding-left: 15px;">
							<?php echo $view_model->get_billing_zip();?>
						</div>
					</div>

					<div class='row mb-4'  style="margin-bottom: 1.5rem!important;display: flex;-ms-flex-wrap: wrap; flex-wrap: wrap; margin-right: -15px; margin-left: -15px;"  >
						<div class='col'  style="flex-basis: 0; -ms-flex-positive: 1; flex-grow: 1; max-width: 100%;position: relative; width: 100%; min-height: 1px;padding-right: 15px; padding-left: 15px;">
							Billing Address
						</div>
						<div class='col'  style="flex-basis: 0; -ms-flex-positive: 1; flex-grow: 1; max-width: 100%;position: relative; width: 100%; min-height: 1px;padding-right: 15px; padding-left: 15px;">
							<?php echo $view_model->get_billing_address();?>
						</div>
					</div>



					 

					<div class='row mb-4'  style="margin-bottom: 1.5rem!important;display: flex;-ms-flex-wrap: wrap; flex-wrap: wrap; margin-right: -15px; margin-left: -15px;"  >
						<div class='col'  style="flex-basis: 0; -ms-flex-positive: 1; flex-grow: 1; max-width: 100%;position: relative; width: 100%; min-height: 1px;padding-right: 15px; padding-left: 15px;">
							Shipping Country
						</div>
						<div class='col'  style="flex-basis: 0; -ms-flex-positive: 1; flex-grow: 1; max-width: 100%;position: relative; width: 100%; min-height: 1px;padding-right: 15px; padding-left: 15px;">
							<?php echo $view_model->get_shipping_country();?>
						</div>
					</div>

					<div class='row mb-4'  style="margin-bottom: 1.5rem!important;display: flex;-ms-flex-wrap: wrap; flex-wrap: wrap; margin-right: -15px; margin-left: -15px;"  >
						<div class='col'  style="flex-basis: 0; -ms-flex-positive: 1; flex-grow: 1; max-width: 100%;position: relative; width: 100%; min-height: 1px;padding-right: 15px; padding-left: 15px;">
							Shipping City
						</div>
						<div class='col'  style="flex-basis: 0; -ms-flex-positive: 1; flex-grow: 1; max-width: 100%;position: relative; width: 100%; min-height: 1px;padding-right: 15px; padding-left: 15px;">
							<?php echo $view_model->get_shipping_city();?>
						</div>
					</div>

					<div class='row mb-4'  style="margin-bottom: 1.5rem!important;display: flex;-ms-flex-wrap: wrap; flex-wrap: wrap; margin-right: -15px; margin-left: -15px;"  >
						<div class='col'  style="flex-basis: 0; -ms-flex-positive: 1; flex-grow: 1; max-width: 100%;position: relative; width: 100%; min-height: 1px;padding-right: 15px; padding-left: 15px;">
							Shipping Zip
						</div>
						<div class='col'  style="flex-basis: 0; -ms-flex-positive: 1; flex-grow: 1; max-width: 100%;position: relative; width: 100%; min-height: 1px;padding-right: 15px; padding-left: 15px;">
							<?php echo $view_model->get_shipping_zip();?>
						</div>
					</div>

					<div class='row mb-4'  style="margin-bottom: 1.5rem!important;display: flex;-ms-flex-wrap: wrap; flex-wrap: wrap; margin-right: -15px; margin-left: -15px;"  >
						<div class='col'  style="flex-basis: 0; -ms-flex-positive: 1; flex-grow: 1; max-width: 100%;position: relative; width: 100%; min-height: 1px;padding-right: 15px; padding-left: 15px;">
							Shipping Address
						</div>
						<div class='col'  style="flex-basis: 0; -ms-flex-positive: 1; flex-grow: 1; max-width: 100%;position: relative; width: 100%; min-height: 1px;padding-right: 15px; padding-left: 15px;">
							<?php echo $view_model->get_shipping_address();?>
						</div>
					</div>


					<div class='row mb-4'  style="margin-bottom: 1.5rem!important;display: flex;-ms-flex-wrap: wrap; flex-wrap: wrap; margin-right: -15px; margin-left: -15px;"  >
						<div class='col'  style="flex-basis: 0; -ms-flex-positive: 1; flex-grow: 1; max-width: 100%;position: relative; width: 100%; min-height: 1px;padding-right: 15px; padding-left: 15px;">
							Order Date
						</div>
						<div class='col'  style="flex-basis: 0; -ms-flex-positive: 1; flex-grow: 1; max-width: 100%;position: relative; width: 100%; min-height: 1px;padding-right: 15px; padding-left: 15px;">
							<?php echo date('m-d-Y', strtotime( $view_model->get_order_date_time() ));?>
						</div>
					</div>


					<div class='row mb-4'  style="margin-bottom: 1.5rem!important;display: flex;-ms-flex-wrap: wrap; flex-wrap: wrap; margin-right: -15px; margin-left: -15px;"  >
						<div class='col'  style="flex-basis: 0; -ms-flex-positive: 1; flex-grow: 1; max-width: 100%;position: relative; width: 100%; min-height: 1px;padding-right: 15px; padding-left: 15px;">
							Order Time
						</div>
						<div class='col'  style="flex-basis: 0; -ms-flex-positive: 1; flex-grow: 1; max-width: 100%;position: relative; width: 100%; min-height: 1px;padding-right: 15px; padding-left: 15px;">
							<?php echo date('H:i:s A', strtotime( $view_model->get_order_date_time() ));?>
						</div>
					</div>

					<div class='row mb-4'  style="margin-bottom: 1.5rem!important;display: flex;-ms-flex-wrap: wrap; flex-wrap: wrap; margin-right: -15px; margin-left: -15px;"  >
						<div class='col'  style="flex-basis: 0; -ms-flex-positive: 1; flex-grow: 1; max-width: 100%;position: relative; width: 100%; min-height: 1px;padding-right: 15px; padding-left: 15px;">
							Payment Method
						</div>
					<div class='col'  style="flex-basis: 0; -ms-flex-positive: 1; flex-grow: 1; max-width: 100%;position: relative; width: 100%; min-height: 1px;padding-right: 15px; padding-left: 15px;">
							<?php echo $view_model->payment_method_mapping()[$view_model->get_payment_method()];?>
						</div>
						</div>
					
					<div class='row mb-4'  style="margin-bottom: 1.5rem!important;display: flex;-ms-flex-wrap: wrap; flex-wrap: wrap; margin-right: -15px; margin-left: -15px;"  >
						<div class='col'  style="flex-basis: 0; -ms-flex-positive: 1; flex-grow: 1; max-width: 100%;position: relative; width: 100%; min-height: 1px;padding-right: 15px; padding-left: 15px;">
							Order Type
						</div>
						<div class='col'  style="flex-basis: 0; -ms-flex-positive: 1; flex-grow: 1; max-width: 100%;position: relative; width: 100%; min-height: 1px;padding-right: 15px; padding-left: 15px;">
							<?php echo $view_model->order_type_mapping()[$view_model->get_order_type()];?>
						</div>
					</div>
					
					<div class='row mb-4'  style="margin-bottom: 1.5rem!important;display: flex;-ms-flex-wrap: wrap; flex-wrap: wrap; margin-right: -15px; margin-left: -15px;"  >
						<div class='col'  style="flex-basis: 0; -ms-flex-positive: 1; flex-grow: 1; max-width: 100%;position: relative; width: 100%; min-height: 1px;padding-right: 15px; padding-left: 15px;">
							Status
						</div>
						<div class='col'  style="flex-basis: 0; -ms-flex-positive: 1; flex-grow: 1; max-width: 100%;position: relative; width: 100%; min-height: 1px;padding-right: 15px; padding-left: 15px;">
							<?php echo $view_model->status_mapping()[$view_model->get_status()];?>
						</div>
					</div>
					
					<!-- <div class='row mb-4'  style="margin-bottom: 1.5rem!important;display: flex;-ms-flex-wrap: wrap; flex-wrap: wrap; margin-right: -15px; margin-left: -15px;"  >
						<div class='col'  style="flex-basis: 0; -ms-flex-positive: 1; flex-grow: 1; max-width: 100%;position: relative; width: 100%; min-height: 1px;padding-right: 15px; padding-left: 15px;">
							Delivery Type
						</div>
						<div class='col'  style="flex-basis: 0; -ms-flex-positive: 1; flex-grow: 1; max-width: 100%;position: relative; width: 100%; min-height: 1px;padding-right: 15px; padding-left: 15px;">
							<?php echo $view_model->checkout_type_mapping()[$view_model->get_checkout_type()];?>
						</div>
					</div> -->
					
				 
					 
 

					<div class='row'  style="display: -ms-flexbox;display: flex;-ms-flex-wrap: wrap;flex-wrap: wrap;margin-right: -15px;margin-left: -15px;">
	                    <div class="col-md-12" style="width: 100%;">
							<div class="table-responsive" style="display: block;width: 100%;overflow-x: auto;-webkit-overflow-scrolling: touch;-ms-overflow-style: -ms-autohiding-scrollbar;">
	                        <table class="table table-bordered " style="width: 100%;max-width: 100%;margin-bottom: 1rem;background-color: transparent;">
	                            <thead class="thead-light">
	                                <tr>
	                                    <th style="width:20%;color: #495057; background-color: #e9ecef; border-color: #dee2e6;" >Item Name</th> 
	                                    <th style="width:10%;color: #495057; background-color: #e9ecef; border-color: #dee2e6;" >Quantity</th>
	                                    <th style="width:10%;color: #495057; background-color: #e9ecef; border-color: #dee2e6;" >Delivery Type</th>
	                                    <th style="width:10%;color: #495057; background-color: #e9ecef; border-color: #dee2e6;" ></th>
	                                    <th style="width:10%;color: #495057; background-color: #e9ecef; border-color: #dee2e6;" >Rate</th> 
	                                    <th style="width:10%;color: #495057; background-color: #e9ecef; border-color: #dee2e6;" >Amount</th>
	                                </tr> 
	                            </thead>
	                            <tbody>
	                            <?php foreach($orders_details as $key => $detail){  ?> 
	                                <tr>
	                                    <td style="border: 1px solid #dee2e6;"><?php echo  $detail->product_name; ?>  </td> 
	                                    <td style="border: 1px solid #dee2e6;"><?php echo  $detail->quantity; ?></td>
	                                    <td style="border: 1px solid #dee2e6;"><?php echo  $detail->is_pickup == 1 ? 'Store Pickup' : 'Delivery'; ?></td>
	                                    <td style="border: 1px solid #dee2e6;">
											<?php if (!empty($detail->store)) : ?>
											
												<p style="margin-top:1px; padding-top:1.5px"><?php echo $detail->store->name ?> </p>
												<p style="margin-top:1px; padding-top:1.5px"><?php echo $detail->store->address ?> </p>
												<p style="margin-top:1px; padding-top:1.5px"><?php echo $detail->store->city." ". $detail->store->state. " ".$detail->store->zip." " ?> </p>
												<p style="margin-top:1px; padding-top:1.5px"><a href='tel:<?php echo $detail->store->phone?>'> <?php echo $detail->store->phone?>  </a></p>
											
											<?php endif; ?>
										</td>
	                                    <td style="border: 1px solid #dee2e6;"><?php echo  number_format($detail->product_unit_price,2); ?></td> 
	                                    <td style="border: 1px solid #dee2e6;"><?php echo  number_format($detail->amount,2); ?></td>
	                                </tr>
	                            <?php } ?>
	                            </tbody>
	                            <tfoot>
								<tr>
									<td colspan="4"></td> 
									<td>Subtotal</td>
									<td><?php echo number_format($view_model->get_subtotal(),2);?></td>
								</tr> 
								<tr>
									<td colspan="4"></td> 
									<td>Shipping Cost</td>
									<td><?php echo number_format($view_model->get_shipping_cost(),2);?>
										<span><?php echo $view_model->get_shipping_cost_service_name() ; ?></span>
									</td>
								</tr>
								 

								<tr>
									<td colspan="2"></td> 
									<td>Tax</td>
									<td><?php echo number_format($view_model->get_tax(),2);?></td>
								</tr>


								<tr>
									<td colspan="2"></td> 
									<td>Discount</td>
									<td><?php echo number_format($view_model->get_discount(), 2);?></td>
								</tr>

								<tr>
									<td colspan="2"></td> 
									<td>Total</td>
									<td>
	                                    <?php  
	                                        echo number_format($view_model->get_total(), 2);
	                                    ?>
	                                    
	                                </td>
								</tr>
							    </tfoot>
	                        </table>
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


 