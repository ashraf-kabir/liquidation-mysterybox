<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
if ($layout_clean_mode) {
    echo '<style>#content{padding:0px !important;}</style>';
}
?>
<div class="row">
	<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
		<div class="page-header">
			<h2 class="pageheader-title"><?php echo $view_model->get_heading();?> </h2>
			<div class="page-breadcrumb">
				<nav aria-label="breadcrumb">
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="/{{{portal}}}/dashboard" class="breadcrumb-link">xyzDashboard</a></li>
						<li class="breadcrumb-item"><a href="/{{{portal}}}{{{route}}}" class="breadcrumb-link"><?php echo $view_model->get_heading();?></a></li>
						<li class="breadcrumb-item active" aria-current="page">xyzAdd</li>
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
            <h5 class="card-header">xyzAdd <?php echo $view_model->get_heading();?></h5>
                <div class="card-body">
                <?= form_open() ?>
{{{input}}}
{{{custom_view_add}}}
                <div class="form-group">
                    <input type="submit" class="btn btn-primary" value="xyzSubmit">
                </div>
                </form>
            </div>
        </div>
    </div>
</div>