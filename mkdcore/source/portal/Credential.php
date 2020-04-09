<?php defined('BASEPATH') OR exit('No direct script access allowed');

if ($layout_clean_mode) {
    echo '<style>#content{padding:0px !important;}</style>';
}
?>

<div class="row">
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
            <h5 class="card-header">Edit Credentials</h5>
                <div class="card-body">
                <?= form_open() ?>
				<div class="form-group">
					<label for="First Name">Email</label>
					<input type="email" class="form-control" id="form_first_name" name="email" value="<?php echo set_value('email', $this->_data['view_model']->get_email());?>"/>
				</div>
                <div class="form-group">
					<label for="Password">Password</label>
					<input type="password" class="form-control" id="form_password" name="password" />
				</div>
                <div class="form-group">
                    <input type="submit" class="btn btn-primary" value="Submit">
                </div>
                </form>
            </div>
        </div>
    </div>
</div>
