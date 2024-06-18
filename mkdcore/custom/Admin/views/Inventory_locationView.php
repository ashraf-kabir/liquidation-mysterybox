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
            <a href="/admin/inventory_location/0" class="breadcrumb-link"><?php echo $view_model->get_heading();?></a>
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
					<span class="btn btn-primary float-right" onclick="togglePrintModal()">Print Barcode</span>
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
						Barcode Image
					</div>
					<div class='col'>
					<img class="img-fluid d-block mb-3 mt-3 view-image" style='max-height: 100px;' src='<?php echo $view_model->get_barcode_image();?>' onerror= \"if (this.src != '/uploads/placeholder.jpg') this.src = '/uploads/placeholder.jpg';\" />
					</div>
				</div>


				<div class='row mb-4'>
					<div class='col'>
						Store
					</div>
					<div class='col'>
						<?php echo $store->name;?>
					</div>
				</div>

            </div>
        </div>
    </div>
</div>


<?php if ($this->input->get('print') == 1 || true): ?>
	<div class="modal print-modal" tabindex="-1" role="dialog">
	  	<div class="modal-dialog" role="document">
		    <div class="modal-content">
		      	<div class="modal-header">
			        <h5 class="modal-title">Please print the Inventory Location Code below</h5>
			        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
			          	<span aria-hidden="true">&times;</span>
			        </button>
		      	</div>
		      	<div class="modal-body  page_full_width  record_break_per printable" id="print-me-2">

		      		 
		      		<div class='row '  >
						<div class="col-sm-12" >
							<h1  style="text-overflow: hidden;" class="make_font_big" > <?php echo $view_model->get_name();?></h1> 
						</div> 
					</div>



					<div class='row ' >
						<div class="col-sm-12" >
							<h1  class="font-x-large" >Store: <?php echo $store->name;?></h1> 
						</div> 
					</div>

					<div class='row' style="text-align: center;">
						<div class="col-sm-12" >
							<img style="width: 100%;height: 150px;padding:3px;"   src='<?php echo $view_model->get_barcode_image(); ?>' alt="Barcode"  >
						</div> 
					</div>  
			         
		      	</div>
		      	<div class="modal-footer">
		        	<button type="button" class="btn btn-primary"  onClick="printdiv('print-me-2')" >Print</button>
		        	<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
		      	</div>
		    </div>
	  	</div>
	</div>
<?php endif ?>


<script>
	function printdiv(printpage) {

    	$('.print-modal').modal('hide');
    	
        var headstr = '<html><head><title></title></head><link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" crossorigin="anonymous"><style type="text/css">  body  { margin: 0px !important;  } @media print {  .make_font_big{font-size: 5rem !important;padding:0px !important; word-spacing: 30px !important; width : 100% !important;transform:translateY(-20px); } .col-sm-12{ text-align: justify !important;text-justify: inter-word !important;} @page{ width:256px !important; height: 170px !important; margin: 0px !important; padding: 0px !important; }   .printable{padding:0px!important;margin:0px!important;} .printable{width:100%!important;float:left!important;} .printable{padding:0px!important;margin:0px!important;}  } </style><body>';
       
        var footstr = "</body>";
        var newstr = document.all.item(printpage).innerHTML;
        var oldstr = document.body.innerHTML;
        document.body.innerHTML = headstr + newstr + footstr;
        window.print();
        document.body.innerHTML = oldstr;
        return false;
    }   

	function togglePrintModal() {
		$('.print-modal').modal('toggle')
	}


    <?php if ($this->input->get('print') == 1): ?>
    document.addEventListener('DOMContentLoaded', function(){
    	togglePrintModal()
    }, false);
    <?php endif ?>
</script>