<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
if ($layout_clean_mode) {
	echo '<style>#content{padding:0px !important;}</style>';
}
?>
<?php if (strlen($error) > 0) : ?>
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-danger" role="alert">
                <?php echo $error; ?>
            </div>
        </div>
    </div>
<?php endif; ?>
<?php if (strlen($success) > 0) : ?>
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-success" role="success">
                <?php echo $success; ?>
            </div>
        </div>
    </div>
<?php endif; ?>

<div class="tab-content mx-4" id="nav-tabContent">
              <!-- Bread Crumb -->
<div aria-label="breadcrumb">
    <ol class="breadcrumb pl-0 mb-4 bg-background d-flex justify-content-center justify-content-md-start" style="background-color: inherit;">
        <li class="breadcrumb-item active" aria-current="page">
            <a href="/admin/inventory_transfer/0" class="breadcrumb-link"><?php echo $view_model->get_heading();?></a>
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
						From Store
					</div>
					<div class='col'>
						<?php echo $store_map[$view_model->get_from_store()];?>
					</div>
				</div>

				<div class='row mb-4'>
					<div class='col'>
						To Store
					</div>
					<div class='col'>
						<?php echo $store_map[$view_model->get_to_store()];?>
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
						Status
					</div>
					<div class='col'>
						<?php echo $view_model->status_mapping()[$view_model->get_status()];?>
					</div>
				</div>
				<?php if ( $view_model->get_status() != 2 /* 2 - Completed---> only show button when status is not completed */ ): ?>
				<!-- <div class='row mb-4'>
					<div class='col'>
						<a href="<?php echo '/admin/inventory_transfer/accept/'.$view_model->get_id() ?>" class="btn btn-primary">Accept Request</a>
					</div>
					<div class='col'>
					</div>
				</div> -->
				<?php endif ; ?>
            </div>
        </div>
    </div>
</div>
