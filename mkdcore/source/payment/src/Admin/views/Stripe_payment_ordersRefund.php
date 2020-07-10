<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
?>
<div class="row">
	<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
		<div class="page-header" id="top">
			<h2 class="pageheader-title"><?php echo $view_model->get_heading();?> </h2>
			<div class="page-breadcrumb">
				<nav aria-label="breadcrumb">
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="/admin/dashboard" class="breadcrumb-link">xyzDashboard</a></li>
						<li class="breadcrumb-item"><a href="/admin/stripe_payment_orders/0" class="breadcrumb-link"><?php echo $view_model->get_heading();?></a></li>
						<li class="breadcrumb-item active" aria-current="page">xyzRefund</li>
					</ol>
				</nav>
			</div>
		</div>
	</div>
</div>
<div class="row">
    <?php if (validation_errors()) : ?>
        <div class="col-md-12">
            <div class="alert alert-danger" role="alert">
                <?= validation_errors() ?>
            </div>
        </div>
    <?php endif; ?>
    <?php if (strlen($error) > 0) : ?>
        <div class="col-md-12">
            <div class="alert alert-danger" role="alert">
                <?php echo $error; ?>
            </div>
        </div>
    <?php endif; ?>
    <?php if (strlen($success) > 0) : ?>
        <div class="col-md-12">
            <div class="alert alert-success" role="success">
                <?php echo $success; ?>
            </div>
        </div>
    <?php endif; ?>
</div>
<div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="card">
            <h5 class="card-header"><?php echo $view_model->get_heading();?></h5>
                <div class="card-body">
					<table>
						<tr>
							<td><h6>xyzStatus:&nbsp; <?php echo $view_model->get_status();?></h6></td>
						</tr>
						<tr>
							<td><h6>xyzStripe ID:&nbsp; <?php echo $view_model->get_stripe_id();?></h6></td>
						</tr>
						<tr>
							<td><h6>xyzTransaction Type:&nbsp; <?php echo $view_model->get_object();?></h6></td>
						</tr>
						<tr>
							<td><h6>xyzAmount:&nbsp; <?php echo number_format($view_model->get_amount(),2);?>(<?php echo strtoupper($view_model->get_currency()); ?>)</h6></td>
						</tr>
						<tr>
							<td><h6>xyzRefunded:&nbsp; <?php echo number_format($view_model->get_refunded(),2);?>(<?php echo strtoupper($view_model->get_currency()); ?>)</h6></td>
						</tr>
						<tr>
							<td><h6>xyzCustomer:&nbsp; <?php echo $view_model->get_customer();?></h6></td>
						</tr>
						
					</table>				
					
					<?= form_open() ?>
						<div class="form-group">
							<input type="hidden" class="form-control" id="stripe_id" name="stripe_id" value="<?php echo set_value('stripe_id', $view_model->get_stripe_id()); ?>"/>
						</div>
						<div class="form-group">
							<label for="Amount">xyzAmount </label>
							<input type="text" class="form-control" id="form_amount" name="amount" value="<?php echo set_value('amount'); ?>" onkeypress="return mkd_is_number(event,this)"/>
						</div>
						<div class="form-group">
							<label for="Reverse Transafare">xyzReason </label>
							<select class='form-control' name='reason'>
								<option selected value=''>xyzChoose</option>
								<option value='requested_by_customer'>xyzRequested By Customer</option>
								<option value='duplicate'>xyzDuplicate</option>
								<option value='fraudulent'>xyzFraudulent</option>
							</select>
						</div>
						<div class="form-group">
							<label for="Refund Application Fee">xyzApply Refund Application Fee </label>
							 <select  class="form-control" name="refund_application_fee">
							 	 <option value='1'>xyzYes</option>
								 <option selected value='0'>xyzNo</option>
							 </select>
						</div>
						<div class="form-group">
							<label for="Reverse Transfer">Reverse Transfer </label>
							<select class='form-control' name='reverse_transfer'>
								<option value='1'>xyzYes</option>
								<option selected value='0'>xyzNo</option>
							</select>
						</div>
						<div class="form-group">
							<input type="submit" class="btn btn-primary" value="Submit">
							</div>
                    </form>
                </div>
        </div>
    </div>
</div>