<?php
defined('BASEPATH') or exit('No direct script access allowed');
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
				<?php if ($this->session->userdata('role') == 2) { ?>
					<a href="/admin/inventory/0" class="breadcrumb-link"><?php echo $view_model->get_heading(); ?></a>
				<?php } elseif ($this->session->userdata('role') == 4) { ?>
					<a href="/manager/inventory/0" class="breadcrumb-link"><?php echo $view_model->get_heading(); ?></a>
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
						<?php echo $view_model->get_heading(); ?> Details
					</h5>
					<button onClick="printdiv('print-me-1')" class="btn btn-info " style="float: right;" type="button"> Print</button>

					<div class='row mb-4' style=" clear: both;">
						<div class='col'>
							ID
						</div>
						<div class='col'>
							<?php echo $view_model->get_id(); ?>
						</div>
					</div>

					<div class='row mb-4'>
						<div class='col'>
							Product Name
						</div>
						<div class='col'>
							<?php echo $view_model->get_product_name(); ?>
						</div>
					</div>

					<div class='row mb-4'>
						<div class='col'>
							SKU
						</div>
						<div class='col'>
							<?php echo $view_model->get_sku(); ?>
						</div>
					</div>

					<div class='row mb-4'>
						<div class='col'>
							Category
						</div>
						<div class='col'>
							<?php echo $view_model->get_category_id(); ?>
						</div>
					</div>

					<div class='row mb-4'>
						<div class='col'>
							Manifest
						</div>
						<div class='col'>
							<?php echo $view_model->get_manifest_id(); ?>
						</div>
					</div>

					<div class='row mb-4'>
						<div class='col'>
							Inventory Location
						</div>
						<div class='col'>
							<?php echo $view_model->get_physical_location(); ?>
						</div>
					</div>

					<div class='row mb-4'>
						<div class='col'>
							Store
						</div>
						<div class='col'>
							<?php echo $view_model->get_store_location_id(); ?>
						</div>
					</div>

					<!-- <div class='row mb-4'>
					<div class='col'>
						Inventory Location Description
					</div>
					<div class='col'>
						<?php echo $view_model->get_location_description(); ?>
					</div>
				</div>  -->

					<div class='row mb-4'>
						<div class='col'>
							Weight
						</div>
						<div class='col'>
							<?php echo $view_model->get_weight(); ?>
						</div>
					</div>

					<div class='row mb-4'>
						<div class='col'>
							Length
						</div>
						<div class='col'>
							<?php echo $view_model->get_length(); ?>
						</div>
					</div>

					<div class='row mb-4'>
						<div class='col'>
							Height
						</div>
						<div class='col'>
							<?php echo $view_model->get_height(); ?>
						</div>
					</div>

					<div class='row mb-4'>
						<div class='col'>
							Width
						</div>
						<div class='col'>
							<?php echo $view_model->get_width(); ?>
						</div>
					</div>

					<?php if (!empty($view_model->get_feature_image())) : ?>
						<div class='row mb-4'>
							<div class='col'>
								<span class='d-block'>Feature Image</span>
								<img class="img-fluid d-block mb-3 mt-3 view-image" style='height: 120px; width: 300px; object-fit: cover;' src="<?php echo $view_model->get_feature_image(); ?>" />
							</div>
						</div>
					<?php endif ?>



					<div class='row mb-4'>
						<div class='col'>
							Selling Price
						</div>
						<div class='col'>
							<?php echo $view_model->get_selling_price(); ?>
						</div>
					</div>

					<div class='row mb-4'>
						<div class='col'>
							Quantity
						</div>
						<div class='col'>
							<?php echo $view_model->get_quantity(); ?>
						</div>
					</div>

					<div class='row mb-4'>
						<div class='col'>
							Available in shelf
						</div>
						<div class='col'>
							<?php
							if (!empty($view_model->get_available_in_shelf())) {
								echo $view_model->available_in_shelf_mapping()[$view_model->get_available_in_shelf()];
							}

							?>
						</div>
					</div>

					<div class='row mb-4'>
						<div class='col'>
							Description
						</div>
						<div class='col'>
							<?php echo $view_model->get_inventory_note(); ?>
						</div>
					</div>

					<div class='row mb-4'>
						<div class='col'>
							Cost Price
						</div>
						<div class='col'>
							<?php echo $view_model->get_cost_price(); ?>
						</div>
					</div>

					<div class='row mb-4'>
						<div class='col'>
							Admin Inventory Note
						</div>
						<div class='col'>
							<?php echo $view_model->get_admin_inventory_note(); ?>
						</div>
					</div>

					<div class='row mb-4'>
						<div class='col'>
							Status
						</div>
						<div class='col'>
							<?php echo $view_model->status_mapping()[$view_model->get_status()]; ?>
						</div>
					</div>

					<!-- <div class='row mb-4'>
					<div class='col'>
						Store Location
					</div>
					<div class='col'>
						<?php echo $view_model->get_store_location_id(); ?>
					</div>
				</div> -->
					<!-- <div class='row mb-4'>
						<div class='col'>
							Store Data
						</div>
						<div class='col'>
							<?php foreach ($store_inventory as $key => $store_data) : ?>

								<?php echo $key + 1; ?>. </br>
								<?php echo "Store: {$store_data->store_name}"; ?> </br>
								<?php echo "Store Quantity: {$store_data->quantity}"; ?> </br> </br>
								<?php if (isset($store_data->location_data)) : ?>
									<?php foreach ($store_data->location_data as $key2 => $location) : ?>
										<?php echo "Physical Location: " . $location['name'] ?> <br>
										<?php echo "Quantity: " . $location['quantity'] ?> <br><br>
									<?php endforeach; ?>
								<?php endif; ?>
								<?php //echo "Physical Location: {$store_data->physical_location_name}";
								?> </br>

							<?php endforeach; ?>
						</div>
					</div> -->

					<div class='row mb-4'>
						<div class='col'>
							Barcode Image
						</div>
						<div class='col'>
							<img class="img-fluid d-block mb-3 mt-3 view-image" style='max-height: 100px;' src="<?php echo $view_model->get_barcode_image(); ?>" onerror=\"if (this.src !='/uploads/placeholder.jpg' ) this.src='/uploads/placeholder.jpg' ;\" />
						</div>
					</div>




					<div class='row mb-4'>
						<div class='col'>
							Can Ship
						</div>
						<div class='col'>
							<?php echo !empty($view_model->can_ship_mapping()[$view_model->get_can_ship()]) ? $view_model->can_ship_mapping()[$view_model->get_can_ship()] : ''; ?>
						</div>
					</div>

					<div class='row mb-4'>
						<div class='col'>
							Can Ship Approval
						</div>
						<div class='col'>
							<?php echo !empty($view_model->can_ship_approval_mapping()[$view_model->get_can_ship_approval()]) ?
								$view_model->can_ship_approval_mapping()[$view_model->get_can_ship_approval()] : ''; ?>
						</div>
					</div>

					<div class='row mb-4'>
						<div class='col'>
							Free Shipping
						</div>
						<div class='col'>
							<?php echo $view_model->free_ship_mapping()[$view_model->get_free_ship()]; ?>
						</div>
					</div>

					<div class='row mb-4'>
						<div class='col'>
							Product Type
						</div>
						<div class='col'>
							<?php echo $view_model->product_type_mapping()[$view_model->get_product_type()]; ?>
						</div>
					</div>

					<div class='row mb-4'>
						<div class='col'>
							Pin Item
						</div>
						<div class='col'>
							<?php echo !empty($view_model->pin_item_top_mapping()[$view_model->get_pin_item_top()]) ?  $view_model->pin_item_top_mapping()[$view_model->get_pin_item_top()] : ''; ?>
						</div>
					</div>


					<div class='row mb-4'>

						<?php
						$check_heading  = false;
						$video_url = json_decode($this->_data['view_model']->get_video_url());
						$image_url = json_decode($this->_data['view_model']->get_youtube_thumbnail_1());
						foreach ($video_url as $key => $video) :
							if (!empty($video)) {
						?>
								<?php if (!$check_heading) : $check_heading = true; ?>
									<div class='col-lg-12'>
										Videos
									</div>
								<?php endif ?>

								<div class=" col-lg-3 col-md-4 col-sm-12" style="position: relative;">
									<a href="<?php echo $video ?>" target='_blank'>
										<div class="video-container-wrapper" style="position: absolute; height: 100%; width: 100%; top: 0; left: 0; z-index: 1; display: flex; justify-content: center; align-items: center;">
											<img src="<?php echo base_url() ?>assets/image/play_circle.png" alt="play" style="height: 30px;" />
										</div>

										<img class="img-thumbnail inventory-view-videos" style="height: 120px; width: 300px; object-fit: cover;" src="<?php echo $image_url[$key] ?>">
									</a>
								</div>

						<?php }
						endforeach; ?>
					</div>



					<div class="page_full_width record_break_per printable" style="text-align: center;display: none;" id="print-me-1">

						<div class='row '>
							<div class="col-sm-12">
								<h1 class="make_font_big" style="text-overflow: hidden;clear: both;display: inline-block;overflow: hidden;white-space: nowrap;"><?php echo $view_model->get_product_name(); ?></h1>
							</div>
						</div>



						<div class='row '>
							<div class="col-sm-12">
								<h1 class="make_font_big"><?php echo $view_model->get_sku(); ?></h1>
							</div>
						</div>


						<div class='row '>
							<div class="col-sm-12">
								<h1 class="make_font_big">$<?php echo number_format($view_model->get_selling_price(), 2); ?> </h1>
							</div>
						</div>


						<div class='row' style="text-align: center;">
							<div class="col-sm-12">
								<img style="width: 1200px;height: 200px" src="<?php echo $view_model->get_barcode_image(); ?>" alt="Barcode">
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>


	<?php if ($this->input->get('print') == 1) : ?>
		<div class="modal print-modal" tabindex="-1" role="dialog">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">Please print the SKU below</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body  page_full_width  record_break_per printable" id="print-me-2">


						<div class='row '>
							<div class="col-sm-12">
								<h1 style="text-overflow: hidden;" class="make_font_big"> <?php echo $view_model->get_product_name(); ?></h1>
							</div>
						</div>



						<div class='row '>
							<div class="col-sm-12">
								<h1 class="make_font_big"><?php echo $view_model->get_sku(); ?></h1>
							</div>
						</div>


						<div class='row '>
							<div class="col-sm-12">
								<h1 class="make_font_big"> $<?php echo number_format($view_model->get_selling_price(), 2); ?> </h1>
							</div>
						</div>


						<div class='row' style="text-align: center;">
							<div class="col-sm-12">
								<img style="width: 1200px;height: 200px" src="<?php echo $view_model->get_barcode_image(); ?>" alt="Barcode">
							</div>
						</div>

					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-primary" onClick="printdiv('print-me-2')">Print</button>
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>
	<?php endif ?>


	<script>
		function printdiv(printpage) {

			$('.print-modal').modal('hide');

			var headstr = "<html><head><title></title><style type='text/css'>@media print {.make_font_big{font-size: 135px !important;padding:0px !important;word-spacing: 50px !important;width : 100% !important;}@page { size: 28in 5in;}}</style></head><body>";
			//var headstr = '<html><head><title></title></head><link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" crossorigin="anonymous"><style type="text/css">  body  { margin: 0px !important;  } @media print {  .make_font_big{font-size: 7rem !important;padding:0px !important; word-spacing: 50px !important; width : 100% !important;transform:translateY(-20px); } .col-sm-12{ text-align: justify !important;text-justify: inter-word !important;} @page{ width:256px !important; height: 170px !important; margin: 0px !important; padding: 0px !important; }   .printable{padding:0px!important;margin:0px!important;} .printable{width:100%!important;float:left!important;} .printable{padding:0px!important;margin:0px!important;}  } </style><body>';

			var footstr = "</body>";
			var newstr = document.all.item(printpage).innerHTML;
			var oldstr = document.body.innerHTML;
			document.body.innerHTML = headstr + newstr + footstr;
			window.print();
			document.body.innerHTML = oldstr;
			return false;
		}


		<?php if ($this->input->get('print') == 1) : ?>
			document.addEventListener('DOMContentLoaded', function() {
				$('.print-modal').modal('toggle')
			}, false);
		<?php endif ?>
	</script>