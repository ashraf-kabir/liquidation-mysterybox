<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
if ($layout_clean_mode) {
	echo '<style>#content{padding:0px !important;}</style>';
}
?>
<div class="row">
	<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
		<div class="page-header" id="top">
			<h2 class="pageheader-title"><?php echo $view_model->get_heading();?> </h2>
			<div class="page-breadcrumb">
				<nav aria-label="breadcrumb">
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="/admin/dashboard" class="breadcrumb-link">Dashboard</a></li>
						<li class="breadcrumb-item"><a href="/admin/stripe_invoices/0" class="breadcrumb-link"><?php echo $view_model->get_heading();?></a></li>
						<li class="breadcrumb-item active" aria-current="page">View</li>
					</ol>
				</nav>
			</div>
		</div>
	</div>
</div>
<div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="card">
            <h5 class="card-header"><?php echo $view_model->get_heading();?> Details</h5>
                <div class="card-body">
					<h6>Collection Method:&nbsp; <?php echo $view_model->get_collection_method();?></h6>
					<h6>Currency:&nbsp; <?php echo $view_model->get_currency();?></h6>
					<h6>Invoice URL:&nbsp; <a target='_blank' class='btn btn-default' href='<?php echo $view_model->get_invoice_url();?>'>View Invoice</a></h6>
					<h6>Invoice PDF URL:&nbsp; <a target="_blank" class='btn btn-default' href=' <?php echo $view_model->get_invoice_pdf_url();?>'>Download Pdf Invoice</a></h6>
					<h6>Amount Due:&nbsp; <?php echo '$' . number_format($view_model->get_amount_due(), 2);?></h6>
					<h6>Amount Paid:&nbsp; <?php echo '$' , number_format($view_model->get_amount_paid(),2);?></h6>
					<h6>Payment Attempted:&nbsp; <?php echo $view_model->payment_attempted_mapping()[$view_model->get_payment_attempted()];?></h6>
					<h6>Status:&nbsp; <?php echo $view_model->status_mapping()[$view_model->get_status()];?></h6>
					<h6>User: </h6>
                </div>
        </div>
    </div><br>
	<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12"><br>
        <div class="card">
            <h5 class="card-header"><?php echo $view_model->get_heading();?> Details</h5>
                <div class="card-body">
					<table class='table'>
						<thead>
							<tr>
								<th>Amount</th>
								<th>Reason</th>
								<th>Status</th>
								<th>Receipt</th>
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