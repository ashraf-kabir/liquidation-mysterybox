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
            <a href="/admin/dashboard" class="breadcrumb-link">Dashboard</a>
        </li>
        <li class="breadcrumb-item active" aria-current="page">
            <a href="/admin/users/0" class="breadcrumb-link"><?php echo $view_model->get_heading();?></a>
        </li>
        <li class="breadcrumb-item active" aria-current="page">
            Edit
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
                <h5 class="primaryHeading2 mb-4 text-md-left">
                    Edit <?php echo $view_model->get_heading();?>
                </h5>
                <?= form_open() ?>
                <div class="form-group col-md-6 col-sm-12">
					<label for="First Name">Email </label>
					<input type="email" class="form-control data-input" id="form_first_name" name="email" value="<?php echo set_value('email', $this->_data['view_model']->get_email());?>"/>
				</div>
                <div class="form-group col-md-6 col-sm-12">
					<label for="First Name">First Name </label>
					<input type="text" class="form-control data-input" id="form_first_name" name="first_name" value="<?php echo set_value('first_name', $this->_data['view_model']->get_first_name());?>"/>
				</div>
				<div class="form-group col-md-6 col-sm-12">
					<label for="Last Name">Last Name </label>
					<input type="text" class="form-control data-input" id="form_last_name" name="last_name" value="<?php echo set_value('last_name', $this->_data['view_model']->get_last_name());?>"/>
				</div>
				<div class="form-group col-md-6 col-sm-12">
					<label for="Phone #">Phone # </label>
					<input type="text" class="form-control data-input" id="form_phone" name="phone" value="<?php echo set_value('phone', $this->_data['view_model']->get_phone());?>"/>
				</div>
				<div class="form-group col-md-6 col-sm-12 mb-4">
					<label for="Image">Image </label>
					<img class='edit-preview-image d-block' id="output_image" src="<?php echo set_value('image', $this->_data['view_model']->get_image());?>" onerror=\"if (this.src != '/uploads/placeholder.jpg') this.src = '/uploads/placeholder.jpg';\"/>
					<br/><div class="btn btn-info btn-sm mkd-choose-image" data-image-url="image" data-image-id="image_id" data-image-preview="output_image" data-view-width="250" data-view-height="250" data-boundary-width="500" data-boundary-height="500">Choose Image</div>
					<input type="hidden" id="image" name="image" value="<?php echo set_value('image', $this->_data['view_model']->get_image());?>"/>
					<input type="hidden" id="image_id" name="image_id" value="<?php echo set_value('image_id', $this->_data['view_model']->get_image_id());?>"/>
				</div>
                <div class="form-group col-md-6 col-sm-12">
                    <label for="Image">xyzRole  </label>
                    <select id="form_mobile_support" name="role_id" class="form-control data-input">
						<?php foreach ($this->_data['view_data']['roles'] as $key => $value) {
							echo "<option value='{$key}' " . (($view_model->get_role_id() == $key && $view_model->get_role_id() != '') ? 'selected' : '') . "> {$value} </option>";
						}?>
					</select>
                </div>
                <div class="form-group col-md-6 col-sm-12">
                    <label for="Image">xyzStatus  </label>
                    <select id="form_mobile_support" name="status" class="form-control data-input">
						<?php foreach ($this->_data['view_data']['status'] as $key => $value) {
							echo "<option value='{$key}' " . (($view_model->get_status() == $key && $view_model->get_status() != '') ? 'selected' : '') . "> {$value} </option>";
						}?>
					</select>
                </div>

                <div class="form-group col-md-6 col-sm-12">
					<label for="Phone #">Password</label>
					<input type="password" class="form-control data-input" id="password" name="password" />
				</div>

                <div class="form-group col-md-6 col-sm-12">
                    <input type="submit" class="btn btn-primary ext-white mr-4 my-4" value="Submit">
                </div>
                </form>
            </div>
        </div>
    </div>
</div>