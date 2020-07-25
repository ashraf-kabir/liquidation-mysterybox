<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2020*/
if ($layout_clean_mode) {
    echo '<style>#content{padding:0px !important;}</style>';
}
?>
<br/>
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
<br/>
<div class="row mb-5">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="card">
            <div class="card-body">
                <h5 class="primaryHeading2 mb-4 text-md-left pl-3">
                    xyzChange Password
                </h5>
                <?= form_open() ?>
                    				<div class="form-group col-md-5 col-sm-12">
					<label for="Email">xyzEmail </label>
					<input type="text" class="form-control data-input" id="form_email" name="email" value="<?php echo set_value('email', $this->_data['email']);?>"/>
				</div>
				<div class="form-group col-md-5 col-sm-12">
					<label for="Password">Password </label>
					<input type="password" class="form-control data-input" id="form_password" name="password" value=""/>
				</div>

                <div class="form-group col-md-5 col-sm-12">
                    <input type="submit" class="btn btn-primary ext-white mr-4 my-4" value="xyzSubmit">
                </div>
                </form>
            </div>
        </div>
    </div>
</div>