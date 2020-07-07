<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
?>
<div class="tab-content" id="nav-tabContent">
              <!-- Bread Crumb -->
    <div aria-label="breadcrumb">
        <ol class="breadcrumb pl-0 mb-4 bg-background d-flex justify-content-center justify-content-md-start">
            <li class="breadcrumb-item active" aria-current="page">
                <a href="/admin/dashboard" class="breadcrumb-link">xyzDashboard</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
                <a href="/admin/stripe_products/0" class="breadcrumb-link"><?php echo $view_model->get_heading();?></a>
            </li>
           <li class="breadcrumb-item active" aria-current="page">
             xyzAdd
           </li>
        </ol>
    </div>
</div>
<h1 class="primaryHeading mb-4 text-center text-md-left">
    xyzAdd <?php echo $view_model->get_heading();?>
</h1>
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
            <div class="card-body">
                <?= form_open() ?>
				<div class="form-group">
					<label for="Product Name">Product Name </label>
					<input type="text" class="form-control" id="form_name" name="name" value="<?php echo set_value('name'); ?>"/>
				</div>
				<div class="form-group">
                    <label for="Product Type">Product Type </label>
                    <input type="text" name='type' value='service' readonly="true" class="form-control" >
				</div>
                <div class="form-group">
                    <input type="submit" class="btn btn-primary" value="Submit">
                </div>
                </form>
            </div>
        </div>
    </div>
</div>