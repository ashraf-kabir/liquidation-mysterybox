<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
if ($layout_clean_mode) {
	echo '<style>#content{padding:0px !important;}</style>';
}
?>
<div class="tab-content mx-4" id="nav-tabContent">
	<div aria-label="breadcrumb">
    	<ol class="breadcrumb pl-0 mb-4 bg-background d-flex justify-content-center justify-content-md-start">
    	    <!-- <li class="breadcrumb-item active" aria-current="page">
    	        <a href="/admin/dashboard" class="breadcrumb-link">Dashboard</a>
    	    </li> -->
    	    <li class="breadcrumb-item active" aria-current="page">
    	        <a href="/admin/stripe_invoices/0" class="breadcrumb-link"><?php echo $view_model->get_heading();?></a>
    	    </li>
    	    <li class="breadcrumb-item active" aria-current="page">
    	        xyzView
    	    </li>
    	</ol>
	</div>
<br/>
<div class="row mb-5">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="card pb-5" style='border-bottom:1px solid #ccc;'>
            <div class="card-body">
                <h5 class="primaryHeading2 text-md-left">
                    <?php echo $view_model->get_heading();?> Details
                </h5>      
				<div class='row mb-4'>
					<div class='col'>
						xyzCollection Method
					</div>
					<div class='col'>
						<?php echo $view_model->get_collection_method();?>
					</div>
				</div>

				<div class='row mb-4'>
					<div class='col'>
						xyzCurrency
					</div>
					<div class='col'>
						<?php echo $view_model->get_currency();?>
					</div>
				</div>

				<div class='row mb-4'>
					<div class='col'>
						xyzInvoice URL
					</div>
					<div class='col'>
						<a target='_blank' class='btn btn-default' href='<?php echo $view_model->get_invoice_url();?>'>xyzView Invoice</a>
					</div>
				</div>
				
				<div class='row mb-4'>
					<div class='col'>
						xyzInvoice PDF URL
					</div>
					<div class='col'>
						<a target="_blank" class='btn btn-default' href=' <?php echo $view_model->get_invoice_pdf_url();?>'>xyzDownload Pdf Invoice</a>
					</div>
				</div>

				<div class='row mb-4'>
					<div class='col'>
						xyzAmount Due
					</div>
					<div class='col'>
						<?php echo '$' . number_format($view_model->get_amount_due(), 2);?>
					</div>
				</div>

				<div class='row mb-4'>
					<div class='col'>
						xyzAmount Paid
					</div>
					<div class='col'>
						<?php echo '$' , number_format($view_model->get_amount_paid(),2);?>
					</div>
				</div>


				<div class='row mb-4'>
					<div class='col'>
						xyzPayment Attempted
					</div>
					<div class='col'>
						<?php echo $view_model->payment_attempted_mapping()[$view_model->get_payment_attempted()];?>
					</div>
				</div>

				<div class='row mb-4'>
					<div class='col'>
						xyzStatus
					</div>
					<div class='col'>
						<?php echo $view_model->status_mapping()[$view_model->get_status()];?>
					</div>
				</div>

            </div>
        </div>
    </div>
</div><br>
<div class="row mb-5">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="card pb-5" style='border-bottom:1px solid #ccc;'>
            <div class="card-body">
                <h5 class="primaryHeading2 text-md-left">
                   xyzRefund Details
                </h5>
				<table class='table'>
						<thead>
							<tr>
								<th>xyzAmount</th>
								<th>xyzReason</th>
								<th>xyzStatus</th>
								<th>xyzReceipt</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach($this->_data['view_data']['refunds'] as $refund):?>
								<tr>
									<td><?php echo '$' . number_format(($refund->amount/100) , 2); ?></td>
									<td><?php echo $refund->reason ?? "" ;?></td>
									<td><?php echo $refund->status; ?></td>
									<td><a  target='_blank' class='btn btn-primary' href='<?php echo $refund->receipt_url ?>'>Receipt</a></td>
								</tr>
							<?php endforeach;?>
						</tbody>
					</table>
			</div>
		</div>
	</div>
</div>
