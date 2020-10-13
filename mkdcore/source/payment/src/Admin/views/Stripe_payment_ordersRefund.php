<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
?>
<div class="tab-content mx-4" id="nav-tabContent">
	<div aria-label="breadcrumb">
    	<ol class="breadcrumb pl-0 mb-4 bg-background d-flex justify-content-center justify-content-md-start">
    	    <!-- <li class="breadcrumb-item active" aria-current="page">
    	        <a href="/admin/dashboard" class="breadcrumb-link">Dashboard</a>
    	    </li> -->
    	    <li class="breadcrumb-item active" aria-current="page">
    	        <a href="/admin/stripe_payment_orders/0" class="breadcrumb-link"><?php echo $view_model->get_heading();?></a>
    	    </li>
    	    <li class="breadcrumb-item active" aria-current="page">
				xyzRefund
    	    </li>
    	</ol>
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

<div class="row mb-5">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="card pb-5" style='border-bottom:1px solid #ccc;'>
            <div class="card-body">
                <h5 class="primaryHeading2 text-md-left">
                    <?php echo $view_model->get_heading();?> Details
                </h5>      
				
				<div class='row mb-4'>
					<div class='col'>
						xyzStatus
					</div>
					<div class='col'>
						<?php echo $view_model->get_status();?>
					</div>
				</div>

				<div class='row mb-4'>
					<div class='col'>
						xyzStripe ID
					</div>
					<div class='col'>
						<?php echo $view_model->get_stripe_id();?>
					</div>
				</div>

				<div class='row mb-4'>
					<div class='col'>
						xyzTransaction Type
					</div>
					<div class='col'>
						<?php echo $view_model->get_object();?>
					</div>
				</div>


				<div class='row mb-4'>
					<div class='col'>
						xyzAmount
					</div>
					<div class='col'>
					 	<?php echo number_format($view_model->get_amount(),2);?>(<?php echo strtoupper($view_model->get_currency()); ?>)
					</div>
				</div>

				<div class='row mb-4'>
					<div class='col'>
						xyzRefunded
					</div>
					<div class='col'>
					 	<?php echo number_format($view_model->get_amount(),2);?>(<?php echo strtoupper($view_model->get_refunded()); ?>)
					</div>
				</div>	
			</div>
		</div>
	</div>
</div>

<div class="row mb-5">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="card">
            <div class="card-body">
                <h5 class="primaryHeading2 mb-4 text-md-left pl-3">
                 	<?php echo $view_model->get_heading();?>
                </h5>
                <?= form_open_multipart() ?>
				<div class="form-group">
					<input type="hidden" class="form-control" id="stripe_id" name="stripe_id" value="<?php echo set_value('stripe_id', $view_model->get_stripe_id()); ?>"/>
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
							<label for="Reverse Transfer">xyzReverse Transfer </label>
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
