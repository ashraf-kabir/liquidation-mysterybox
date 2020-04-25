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
						<li class="breadcrumb-item"><a href="/admin/dashboard" class="breadcrumb-link">Dashboard</a></li>
						<li class="breadcrumb-item"><a href="/admin/stripe_files/0" class="breadcrumb-link"><?php echo $view_model->get_heading();?></a></li>
						<li class="breadcrumb-item active" aria-current="page">Add</li>
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
            <h5 class="card-header">Add <?php echo $view_model->get_heading();?></h5>
                <div class="card-body">
                 <?= form_open_multipart() ?> 
				<div class="form-group">
					<label for="Purpose">Purpose </label>
					<select id="form_purpose" name="purpose" class="form-control">
						<?php foreach ($view_model->purpose_mapping() as $key => $value) {
							echo "<option value='{$key}'> {$value} </option>";
						}?>
					</select>
				</div>
				<div class="form-group">
					<div class="mkd-upload-form-btn-wrapper">
						<label for="xyzlocal_copy">xyzlocal_copy</label>
						<button class="mkd-upload-btn">Upload A File</button>
						<input type="file" name="local_file" id="local_file_upload" >
					    <input type="hidden" id="local_file" name="local_file"/>
					    <input type="hidden" id="local_file_id" name="local_file_id"/>
					</div>
				</div>


                <div class="form-group">
                    <input type="submit" class="btn btn-primary" value="Submit">
                </div>
                </form>
            </div>
        </div>
    </div>
</div>