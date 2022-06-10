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
			<?php if($this->session->userdata('role') == 2) { ?>
                <a href="/admin/customer/0" class="breadcrumb-link"><?php echo $view_model->get_heading();?></a>  
            <?php }elseif($this->session->userdata('role') == 4) { ?>
                <a href="/manager/customer/0" class="breadcrumb-link"><?php echo $view_model->get_heading();?></a>
            <?php } ?>
            
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
						Name
					</div>
					<div class='col'>
						<?php echo $view_model->get_name();?>
					</div>
				</div>

				<div class='row mb-4'>
					<div class='col'>
						Email
					</div>
					<div class='col'>
						<?php echo $view_model->get_email();?>
					</div>
				</div>

				<div class='row mb-4'>
					<div class='col'>
						Phone
					</div>
					<div class='col'>
						<?php echo $view_model->get_phone();?>
					</div>
				</div>

				<div class='row mb-4'>
					<div class='col'>
						Company Name
					</div>
					<div class='col'>
						<?php echo $view_model->get_company_name();?>
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
						Billing Country
					</div>
					<div class='col'>
						<?php echo $view_model->get_billing_country();?>
					</div>
				</div>

				<div class='row mb-4'>
					<div class='col'>
						Billing State
					</div>
					<div class='col'>
						<?php echo $view_model->get_billing_state();?>
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
						Shipping Zip
					</div>
					<div class='col'>
						<?php echo $view_model->get_shipping_zip();?>
					</div>
				</div>

				<div class='row mb-4'>
					<div class='col'>
						Shipping Address
					</div>
					<div class='col'>
						<?php echo $view_model->get_shipping_address();?>
					</div>
				</div>

				<div class='row mb-4'>
					<div class='col'>
						Shipping Country
					</div>
					<div class='col'>
						<?php echo $view_model->get_shipping_country();?>
					</div>
				</div>

				<div class='row mb-4'>
					<div class='col'>
						Shipping State
					</div>
					<div class='col'>
						<?php echo $view_model->get_shipping_state();?>
					</div>
				</div>

				<div class='row mb-4'>
					<div class='col'>
						Shipping City
					</div>
					<div class='col'>
						<?php echo $view_model->get_shipping_city();?>
					</div>
				</div>

				<div class='row mb-4'>
					<div class='col'>
						Customer Since
					</div>
					<div class='col'>
						<?php
						$customer_since = "";
	                    if (!empty($data->customer_since)) 
	                    {
	                        $customer_since = date('F d Y', strtotime($view_model->get_customer_since()));
	                    }
	                	?>
						<?php echo $customer_since;?>
					</div>
				</div>

				<div class='row mb-4'>
					<div class='col'>
						Last Order
					</div>
					<div class='col'>
						<?php echo $view_model->get_last_order();?>
					</div>
				</div>

				<div class='row mb-4'>
					<div class='col'>
						Num Orders
					</div>
					<div class='col'>
						<?php echo $view_model->get_num_orders();?>
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
				
            </div>
        </div>
    </div>
</div>
