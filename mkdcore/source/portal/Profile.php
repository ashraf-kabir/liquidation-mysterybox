<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="tab-content" id="nav-tabContent">
              <!-- Bread Crumb -->
    <div aria-label="breadcrumb">
        <ol class="breadcrumb pl-0 mb-4 bg-background d-flex justify-content-center justify-content-md-start">
            <li class="breadcrumb-item active" aria-current="page">
                <a href="/{{{portal}}}/dashboard" class="breadcrumb-link">xyzDashboard</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
                xyzProfile
            </li>
        </ol>
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
            <div class="card-body">
                 <h1 class="primaryHeading mb-4 text-center text-md-left">
                    xyzEdit Profile
                </h1>
                <?= form_open() ?>
				<div class="form-group">
					<label for="First Name">xyzFirst Name </label>
					<input type="text" class="form-control" id="form_first_name" name="first_name" value="<?php echo set_value('first_name', $this->_data['view_model']->get_first_name());?>"/>
				</div>
				<div class="form-group">
					<label for="Last Name">xyzLast Name </label>
					<input type="text" class="form-control" id="form_last_name" name="last_name" value="<?php echo set_value('last_name', $this->_data['view_model']->get_last_name());?>"/>
				</div>
                <div class="form-group">
                    <input type="submit" class="btn btn-primary" value="Submit">
                </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row mt-5 mb-5">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="card">
            <div class="card-body">
                 <h1 class="primaryHeading mb-4 text-center text-md-left">
                    xyzEdit Credentials
                </h1>
                <?= form_open('/{{{portal}}}/update_credentials') ?>
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
</div>