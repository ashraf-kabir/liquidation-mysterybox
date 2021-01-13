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
                <a href="/admin/inventory/0" class="breadcrumb-link"><?php echo $view_model->get_heading();?></a>  
            <?php }elseif($this->session->userdata('role') == 4) { ?>
                <a href="/manager/inventory/0" class="breadcrumb-link"><?php echo $view_model->get_heading();?></a>
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
						Product Name
					</div>
					<div class='col'>
						<?php echo $view_model->get_product_name();?>
					</div>
				</div>

				<div class='row mb-4'>
					<div class='col'>
						SKU
					</div>
					<div class='col'>
						<?php echo $view_model->get_sku();?>
					</div>
				</div>

				<div class='row mb-4'>
					<div class='col'>
						Category
					</div>
					<div class='col'>
						<?php echo $view_model->get_category_id();?>
					</div>
				</div>

				<div class='row mb-4'>
					<div class='col'>
						Manifest
					</div>
					<div class='col'>
						<?php echo $view_model->get_manifest_id();?>
					</div>
				</div>

				<div class='row mb-4'>
					<div class='col'>
						Physical Location
					</div>
					<div class='col'>
						<?php echo $view_model->get_physical_location();?>
					</div>
				</div>

				<div class='row mb-4'>
					<div class='col'>
						Location Description
					</div>
					<div class='col'>
						<?php echo $view_model->get_location_description();?>
					</div>
				</div>

				<div class='row mb-4'>
					<div class='col'>
						Weight
					</div>
					<div class='col'>
						<?php echo $view_model->get_weight();?>
					</div>
				</div>

				<div class='row mb-4'>
					<div class='col'>
						Length
					</div>
					<div class='col'>
						<?php echo $view_model->get_length();?>
					</div>
				</div>

				<div class='row mb-4'>
					<div class='col'>
						Height
					</div>
					<div class='col'>
						<?php echo $view_model->get_height();?>
					</div>
				</div>

				<div class='row mb-4'>
					<div class='col'>
						Width
					</div>
					<div class='col'>
						<?php echo $view_model->get_width();?>
					</div>
				</div>

				<div class='row mb-4'>
					<div class='col'>
						<span class='d-block'>Image</span>
						<img class="img-fluid d-block mb-3 mt-3 view-image" style='max-height: 100px;' src="<?php echo $view_model->get_feature_image();?>" onerror=\"if (this.src != '/uploads/placeholder.jpg') this.src = '/uploads/placeholder.jpg';\"/>
				</div>
				</div>

				<div class='row mb-4'>
					<div class='col'>
						Selling Price
					</div>
					<div class='col'>
						<?php echo $view_model->get_selling_price();?>
					</div>
				</div>

				<div class='row mb-4'>
					<div class='col'>
						Quantity
					</div>
					<div class='col'>
						<?php echo $view_model->get_quantity();?>
					</div>
				</div>

				<div class='row mb-4'>
					<div class='col'>
						Inventory Note
					</div>
					<div class='col'>
						<?php echo $view_model->get_inventory_note();?>
					</div>
				</div>

				<div class='row mb-4'>
					<div class='col'>
						Cost Price
					</div>
					<div class='col'>
						<?php echo $view_model->get_cost_price();?>
					</div>
				</div>

				<div class='row mb-4'>
					<div class='col'>
						Admin Inventory Note
					</div>
					<div class='col'>
						<?php echo $view_model->get_admin_inventory_note();?>
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
						Store Location
					</div>
					<div class='col'>
						<?php echo $view_model->get_store_location_id();?>
					</div>
				</div>

				<div class='row mb-4'>
					<div class='col'>
						Barcode Image
					</div>
					<div class='col'>
					<img class="img-fluid d-block mb-3 mt-3 view-image" style='max-height: 100px;' src="<?php echo $view_model->get_barcode_image();?>" onerror= \"if (this.src != '/uploads/placeholder.jpg') this.src = '/uploads/placeholder.jpg';\" />
					</div>
				</div>

				<div class='row mb-4'>
					<div class='col'>
						Can Ship
					</div>
				<div class='col'>
						<?php echo $view_model->can_ship_mapping()[$view_model->get_can_ship()];?>
					</div>
					</div>
				
				<div class='row mb-4'>
					<div class='col'>
						Product Type
					</div>
				<div class='col'>
						<?php echo $view_model->product_type_mapping()[$view_model->get_product_type()];?>
					</div>
					</div>
				
				<div class='row mb-4'>
					<div class='col'>
						Pin Item
					</div>
				<div class='col'>
						<?php echo $view_model->pin_item_top_mapping()[$view_model->get_pin_item_top()];?>
					</div>
					</div>
				
            </div>
        </div>
    </div>
</div>
