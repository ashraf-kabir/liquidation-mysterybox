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
						<li class="breadcrumb-item"><a href="/{{{portal}}}/dashboard" class="breadcrumb-link">xyzDashboard</a></li>
						<li class="breadcrumb-item"><a href="/{{{portal}}}{{{route}}}" class="breadcrumb-link"><?php echo $view_model->get_heading();?></a></li>
						<li class="breadcrumb-item active" aria-current="page">xyzView</li>
					</ol>
				</nav>
			</div>
		</div>
	</div>
</div>
<div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="card">
            <h5 class="card-header"><?php echo $view_model->get_heading();?> xyzDetails</h5>
                <div class="card-body">
{{{input}}}{{{custom_view_view}}}
                </div>
        </div>
    </div>
</div>