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
            xyzView
        </li>
    </ol>
</div>
<h1 class="primaryHeading mb-4 text-center text-md-left">
    xyzView <?php echo $view_model->get_heading();?>
</h1>
<div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="card">
            <div class="card-body">
                {{{input}}}{{{custom_view_view}}}
            </div>
        </div>
    </div>
</div>