<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2020*/
if ($layout_clean_mode) {
    echo '<style>#content{padding:0px !important;}</style>';
}
?>

<div class="tab-content mx-4" id="nav-tabContent">
              <!-- Bread Crumb -->
<div aria-label="breadcrumb">
    <ol class="breadcrumb pl-0 mb-4 bg-background d-flex justify-content-center justify-content-md-start">
        <!-- <li class="breadcrumb-item active" aria-current="page">
            <a href="/admin/dashboard" class="breadcrumb-link">Dashboard</a>
        </li> -->
        <li class="breadcrumb-item active" aria-current="page">
            <?php if($this->session->userdata('role') == 2) { ?>
                <a href="/admin/customer/0" class="breadcrumb-link"><?php echo $view_model->get_heading();?></a>  
            <?php }elseif($this->session->userdata('role') == 4) { ?>
                <a href="/manager/customer/0" class="breadcrumb-link"><?php echo $view_model->get_heading();?></a>
            <?php } ?>
        </li>
        <li class="breadcrumb-item active" aria-current="page">
            Add
        </li>
    </ol>
</div>
<br/>
<?php if (validation_errors()) : ?>
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-danger" role="alert">
                <?= validation_errors() ?>
            </div>
        </div>
        </div>
    <?php endif; ?>
    <?php if (strlen($error) > 0) : ?>
        <div class="row">
        <div class="col-md-12">
            <div class="alert alert-danger" role="alert">
                <?php echo $error; ?>
            </div>
        </div>
        </div>
    <?php endif; ?>
    <?php if (strlen($success) > 0) : ?>
        <div class="row">
        <div class="col-md-12">
            <div class="alert alert-success" role="success">
                <?php echo $success; ?>
            </div>
        </div>
        </div>
    <?php endif; ?>

<div class="row mb-5">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="card">
            <div class="card-body">
                <h5 class="primaryHeading2 mb-4 text-md-left pl-3">
                    Add <?php echo $view_model->get_heading();?>
                </h5>
                <?= form_open() ?>
                <div class="form-group col-md-5 col-sm-12 ">
					<label for="Name">Name </label>
					<input type="text" class="form-control data-input" id="form_name" name="name" value="<?php echo set_value('name'); ?>"/>
				</div>
				<div class="form-group col-md-5 col-sm-12 ">
					<label for="Email">Email </label>
					<input type="text" class="form-control data-input" id="form_email" name="email" value="<?php echo set_value('email'); ?>"/>
				</div>

                <div class="form-group col-md-5 col-sm-12">
					<label for="password">Password </label>
					<input type="password" class="form-control data-input" id="password" name="password" value="<?php echo set_value('password'); ?>"/>
				</div>

				<div class="form-group col-md-5 col-sm-12 ">
					<label for="Phone">Phone </label>
					<input type="text" class="form-control data-input" id="form_phone" name="phone" value="<?php echo set_value('phone'); ?>"/>
				</div>
				<div class="form-group col-md-5 col-sm-12 ">
					<label for="Company Name">Company Name </label>
					<input type="text" class="form-control data-input" id="form_company_name" name="company_name" value="<?php echo set_value('company_name'); ?>"/>
				</div>
				<div class="form-group col-md-5 col-sm-12 ">
					<label for="Billing Zip">Billing Zip </label>
					<input type="text" class="form-control data-input" id="form_billing_zip" name="billing_zip" value="<?php echo set_value('billing_zip'); ?>"/>
				</div>
				<div class="form-group col-md-5 col-sm-12">
					<label for="Billing Address">Billing Address </label>
					<textarea id='form_billing_address' name='billing_address' class='form-control data-input' rows='5'><?php echo set_value('billing_address'); ?></textarea>
				</div>
				<div class="form-group col-md-5 col-sm-12 ">
					<label for="Billing Country">Billing Country </label>
					<input type="text" class="form-control data-input" id="form_billing_country" name="billing_country" value="<?php echo set_value('billing_country'); ?>"/>
				</div>
				<div class="form-group col-md-5 col-sm-12 ">
					<label for="Billing State">Billing State </label>
					<input type="text" class="form-control data-input" id="form_billing_state" name="billing_state" value="<?php echo set_value('billing_state'); ?>"/>
				</div>
				<div class="form-group col-md-5 col-sm-12 ">
					<label for="Billing City">Billing City </label>
					<input type="text" class="form-control data-input" id="form_billing_city" name="billing_city" value="<?php echo set_value('billing_city'); ?>"/>
				</div>
				<div class="form-group col-md-5 col-sm-12 ">
					<label for="Shipping Zip">Shipping Zip </label>
					<input type="text" class="form-control data-input" id="form_shipping_zip" name="shipping_zip" value="<?php echo set_value('shipping_zip'); ?>"/>
				</div>
				<div class="form-group col-md-5 col-sm-12">
					<label for="Shipping Address">Shipping Address </label>
					<textarea id='form_shipping_address' name='shipping_address' class='form-control data-input' rows='5'><?php echo set_value('shipping_address'); ?></textarea>
				</div>
				<div class="form-group col-md-5 col-sm-12 ">
					<label for="Shipping Country">Shipping Country </label>
					<input type="text" class="form-control data-input" id="form_shipping_country" name="shipping_country" value="<?php echo set_value('shipping_country'); ?>"/>
				</div>
				<div class="form-group col-md-5 col-sm-12 ">
					<label for="Shipping State">Shipping State </label>
					<input type="text" class="form-control data-input" id="form_shipping_state" name="shipping_state" value="<?php echo set_value('shipping_state'); ?>"/>
				</div>
				<div class="form-group col-md-5 col-sm-12 ">
					<label for="Shipping City">Shipping City </label>
					<input type="text" class="form-control data-input" id="form_shipping_city" name="shipping_city" value="<?php echo set_value('shipping_city'); ?>"/>
				</div>
				<div class="form-group col-md-5 col-sm-12">
					<label for="Customer Since">Customer Since </label>
					<input type="date" class="form-control data-input" id="form_customer_since" name="customer_since" value="<?php echo set_value('customer_since'); ?>"/>
				</div>


				<div class="form-group col-md-5 col-sm-12 ">
					<label for="Status">Status </label>
					<select id="form_status" name="status" class="form-control data-input">
						<?php foreach ($view_model->status_mapping() as $key => $value) {
							echo "<option value='{$key}'> {$value} </option>";
						}?>
					</select>
				</div>

                    
                <div class="form-group  col-md-5 col-sm-12">
                    <input type="submit" class="btn btn-primary text-white mr-4 my-4" value="Submit">
                </div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>