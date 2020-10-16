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
            <a href="/admin/payment_custom_plans/0" class="breadcrumb-link"><?php echo $view_model->get_heading();?></a>
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
					<label for="Interval">xyzInterval </label>
					<select id="form_subscription_interval" name="subscription_interval" class="form-control data-input">
						<?php foreach ($view_model->subscription_interval_mapping() as $key => $value) {
							echo "<option " . ( $key != 4 ? 'disabled ' : ''   )  .  " value='{$key}'> {$value} </option>";
						}?>
					</select>
				</div>
				<div class="form-group  col-md-5 col-sm-12">
					<label for="Amount">xyzAmount </label>
					<input type="text" class="form-control data-input" id="form_amount" name="amount" value="<?php echo set_value('amount'); ?>" onkeypress="return mkd_is_number(event,this)"/>
				</div>
				<div class="form-group col-md-5 col-sm-12 ">
					<label for="Type">xyzType </label>
					<select id="form_type" name="type" class="form-control data-input">
						<?php foreach ($view_model->type_mapping() as $key => $value) {
							echo "<option   ".   ( $key == 0 ? 'disabled ' : ''   )   ." value='{$key}'> {$value} </option>";
						}?>
					</select>
				</div>
				<div class="form-group col-md-5 col-sm-12 ">
					<label for="Status">xyzStatus </label>
					<select id="form_status" name="status" class="form-control data-input">
						<?php foreach ($view_model->status_mapping() as $key => $value) {
							echo "<option value='{$key}'> {$value} </option>";
						}?>
					</select>
				</div>
				<div class="form-group col-md-5 col-sm-12">
					<label for="Product ID">xyzProduct ID </label>
                    <select name="product_id"  class="form-control"  value="<?php echo set_value('product_id'); ?>">
                        <?php foreach($products as $product):?>
                            <option value='<?php echo $product->id?>'><?php echo $product->name;?></option>
                        <?php endforeach;?>
                    </select>
				</div>
				<div class="form-group col-md-5 col-sm-12 ">
					<label for="Display Name">xyzDisplay Name </label>
					<input type="text" class="form-control data-input" id="form_display_name" name="display_name" value="<?php echo set_value('display_name'); ?>"/>
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