<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
if ($layout_clean_mode) {
	echo '<style>#content{padding:0px !important;}</style>';
}
?>
<div class="tab-content mx-4" id="nav-tabContent">
              <!-- Bread Crumb -->
<div aria-label="breadcrumb">
    <ol class="breadcrumb pl-0 mb-4 bg-background d-flex justify-content-center justify-content-md-start" style="background-color: inherit;">
        <li class="breadcrumb-item active" aria-current="page">
            <a href="/admin/orders/0" class="breadcrumb-link"><?php echo $view_model->get_heading();?></a>
        </li>
        <li class="breadcrumb-item active" aria-current="page">
            View
        </li>
    </ol>
</div>
<div class="row mb-5">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="card pb-5" style='border-bottom:1px solid #ccc;'>
            <div class="card-body">
                <h5 class="primaryHeading2 text-md-left">
                    <?php echo $view_model->get_heading();?> Details
                </h5>
                
				<div class='row mb-4'>
					<div class='col'>
						ID
					</div>
					<div class='col'>
						<?php echo $view_model->get_id();?>
					</div>
				</div>

				<div class='row mb-4'>
					<div class='col'>
						Billing Name
					</div>
					<div class='col'>
						<?php echo $view_model->get_billing_name();?>
					</div>
				</div>

				<div class='row mb-4'>
					<div class='col'>
						Billing Country
					</div>
					<div class='col'>
						<?php echo $view_model->get_billing_country();?>
					</div>
				</div>

				<div class='row mb-4'>
					<div class='col'>
						Billing City
					</div>
					<div class='col'>
						<?php echo $view_model->get_billing_city();?>
					</div>
				</div>

				<div class='row mb-4'>
					<div class='col'>
						Billing Zip
					</div>
					<div class='col'>
						<?php echo $view_model->get_billing_zip();?>
					</div>
				</div>

				<div class='row mb-4'>
					<div class='col'>
						Billing Address
					</div>
					<div class='col'>
						<?php echo $view_model->get_billing_address();?>
					</div>
				</div>

				<div class='row mb-4'>
					<div class='col'>
						Order DateTime
					</div>
					<div class='col'>
						<?php echo $view_model->get_order_date_time();?>
					</div>
				</div>

				<div class='row mb-4'>
					<div class='col'>
						Payment Method
					</div>
				<div class='col'>
						<?php echo $view_model->payment_method_mapping()[$view_model->get_payment_method()];?>
					</div>
					</div>
				
				<div class='row mb-4'>
					<div class='col'>
						Order Type
					</div>
					<div class='col'>
						<?php echo $view_model->order_type_mapping()[$view_model->get_order_type()];?>
					</div>
				</div>
				
				<div class='row mb-4'>
					<div class='col'>
						Status
					</div>
					<div class='col'>
						<?php echo $view_model->status_mapping()[$view_model->get_status()];?>
					</div>
				</div>
				
				<div class='row mb-4'>
					<div class='col'>
						Delivery Type
					</div>
					<div class='col'>
						<?php echo $view_model->checkout_type_mapping()[$view_model->get_checkout_type()];?>
					</div>
				</div>
				
			 
				 

				 

				<div class='row'>
                    <div class="col-md-12">
						<div class="table-responsive" >
                        <table class="table table-bordered ">
                            <thead class="thead-light">
                                <tr>
                                    <th style="width:20%">Item Name</th> 
                                    <th style="width:10%">Quantity</th>
                                    <th style="width:10%">Rate</th> 
                                    <th style="width:10%">Amount</th>
                                </tr> 
                            </thead>
                            <tbody>
                            <?php foreach($orders_details as $key => $detail){  ?> 
                                <tr>
                                    <td><?php echo  $detail->product_name; ?>  </td> 
                                    <td><?php echo  $detail->quantity; ?></td>
                                    <td><?php echo  number_format($detail->product_unit_price,2); ?></td> 
                                    <td><?php echo  number_format($detail->amount,2); ?></td>
                                </tr>
                            <?php } ?>
                            </tbody>
                            <tfoot>
							<tr>
								<td colspan="2"></td> 
								<td>Subtotal</td>
								<td><?php echo number_format($view_model->get_subtotal(),2);?></td>
							</tr> 
							<tr>
								<td colspan="2"></td> 
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