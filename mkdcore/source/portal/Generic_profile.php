<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="row">
	<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
		<div class="page-header">
			<h2 class="pageheader-title"><?php echo $view_model->get_heading();?> </h2>
			<div class="page-breadcrumb">
				<nav aria-label="breadcrumb">
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="/{{{portal}}}/dashboard" class="breadcrumb-link">xyzDashboard</a></li>
						<li class="breadcrumb-item"><a href="/{{{portal}}}/profile" class="breadcrumb-link">xyzProfile</a></li>
						<li class="breadcrumb-item active" aria-current="page">xyzEdit</li>
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
            <h5 class="card-header">xyzEdit Profile</h5>
                <div class="card-body">
                <?= form_open() ?>
				    {{{form_controls}}}
                    <div class="form-group">
                        <input type="submit" class="btn btn-primary" value="Submit">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div><br>
<div class="row">
	<div class="col col-md-12 ">
		<div class="card">
			<h5 class="card-header">xyzCredentials</h5>
			<div class="card-body">
				<iframe style='width:100%; height:400px; overflow-x:scroll;'src="<?php echo "/{{{portal}}}/credential?layout_clean_mode=1"; ?>" frameborder="0" class='d-iframe'>
				</iframe>
			</div>
		</div>
	</div>
</div>

