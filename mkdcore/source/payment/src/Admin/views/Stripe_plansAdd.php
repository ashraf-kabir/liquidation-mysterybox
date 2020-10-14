<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
?>
<div class="tab-content mx-4" id="nav-tabContent">
              <!-- Bread Crumb -->
    <div aria-label="breadcrumb">
        <ol class="breadcrumb pl-0 mb-4 bg-background d-flex justify-content-center justify-content-md-start">
            <li class="breadcrumb-item active" aria-current="page">
                <a href="/admin/dashboard" class="breadcrumb-link">xyzDashboard</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
                <a href="/admin/stripe_plans/0" class="breadcrumb-link"><?php echo $view_model->get_heading();?></a>
            </li>
           <li class="breadcrumb-item active" aria-current="page">
             xyzAdd
           </li>
        </ol>
    </div>

<div class="row mb-5">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="card">
            <div class="card-body">
                <h5 class="primaryHeading2 mb-4 text-md-left">
                    xyzAdd <?php echo $view_model->get_heading();?>
                </h5>
                <?= form_open() ?>
				    <div class="form-group">
				    	<label for="Interval">xyzInterval </label>
				    	<select id="form_subscription_interval" name="subscription_interval" class="form-control">
				    		<?php foreach ($view_model->subscription_interval_mapping() as $key => $value) {
				    			echo "<option " . ( $key == 4 ? 'disabled' : ''   )    .    " value='{$key}'> {$value} </option>";
				    		}?>
				    	</select>
				    </div>
				    <div class="form-group">
				    	<label for="Amount">xyzAmount </label>
				    	<input type="text" class="form-control" id="form_amount" name="amount" value="<?php echo set_value('amount'); ?>" onkeypress="return mkd_is_number(event,this)"/>
				    </div>
				    <div class="form-group">
				    	<label for="Product ID">xyzProduct ID </label>
				    	<select class="form-control" name='product_id'>
				    		<?php foreach($this->_data['products'] as $product): ?>
				    			<option value="<?php echo $product->id;  ?>"><?php echo $product->name; ?></option>
				    		<?php endforeach;?>
				    	</select>
				    </div>
				    <div class="form-group">
				    	<label for="Display Name">xyzDisplay Name </label>
				    	<input type="text" class="form-control" id="form_display_name" name="display_name" value="<?php echo set_value('display_name'); ?>"/>
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