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
    <ol class="breadcrumb pl-0 mb-4 bg-background d-flex justify-content-center justify-content-md-start">
        <!-- <li class="breadcrumb-item active" aria-current="page">
            <a href="/admin/dashboard" class="breadcrumb-link">Dashboard</a>
        </li> -->
        <li class="breadcrumb-item active" aria-current="page">
            <a href="/admin/stripe_invoices/0" class="breadcrumb-link"><?php echo $view_model->get_heading();?></a>
        </li>
        <li class="breadcrumb-item active" aria-current="page">
            xyzRefund
        </li>
    </ol>
</div>
<br/>
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
        <div class="card">
            <div class="card-body">
                <h5 class="primaryHeading2 mb-4 text-md-left ">
                   <?php echo $view_model->get_heading();?> xyzRefund
                </h5> <br>
                <a target='_blank' class='btn btn-link' href='<?php echo $view_model->get_invoice_url();?>'>Invoice</a><br>
					<h6>xyzAmount Due:&nbsp; <?php echo "$". number_format($view_model->get_amount_due(),2);?></h6>
					<h6>xyzAmount Paid:&nbsp; <?php echo "$" . number_format( $view_model->get_amount_paid(),2);?></h6>
                <hr></hr>

                <?= form_open_multipart() ?>
                    <div class="form-group">
                        <label for="amount">xyzAmount </label>
                        <input type="number"  class='form-control' step="0.01" name='amount'/>
                    </div>
                    <div class='form-group'>
                        <label for="reason">xyzReason </label>
                        <select name="reason" class='form-control'>
                            <option value="">xyzChoose</option>
                            <option value="duplicate">xyzDuplicate</option>
                            <option value="requested_by_customer">xyzRequested by customer</option>
                            <option value="fraudulent">xyzFraudulent</option>
                        </select>
                    </div>
                    <input type="submit" class='btn btn-primary'>
                </form>
            </div>
        </div>
    </div>
</div>
</div>