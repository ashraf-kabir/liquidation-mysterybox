<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
if ($layout_clean_mode) {
    echo '<style>#content{padding:0px !important;}</style>';
}
?>
<div class="tab-content" id="nav-tabContent">
              <!-- Bread Crumb -->
<div aria-label="breadcrumb">
    <ol class="breadcrumb pl-0 mb-4 bg-background d-flex justify-content-center justify-content-md-start">
        <li class="breadcrumb-item active" aria-current="page">
            <a href="/{{{portal}}}/dashboard" class="breadcrumb-link">xyzDashboard</a>
        </li>
        <li class="breadcrumb-item active" aria-current="page">
            <a href="/{{{portal}}}{{{route}}}" class="breadcrumb-link"><?php echo $view_model->get_heading();?></a>
        </li>
        <li class="breadcrumb-item active" aria-current="page">
            xyzEdit
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
        <div class="card">
            <div class="card-body">
                <h1 class="primaryHeading mb-4 text-center text-md-left">
                    xyzEdit <?php echo $view_model->get_heading();?>
                </h1>
                <?= form_open() ?>
                    {{{input}}}
                    {{{custom_view_edit}}}
                <div class="form-group">
                    <input type="submit" class="btn btn-primary ext-white mr-4 my-4" value="xyzSubmit">
                </div>
                </form>
            </div>
        </div>
    </div>
</div>