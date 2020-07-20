<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
?>
<div class="tab-content" id="nav-tabContent">
              <!-- Bread Crumb -->
    <div aria-label="breadcrumb">
        <ol class="breadcrumb pl-0 mb-4 bg-background d-flex justify-content-center justify-content-md-start">
            <li class="breadcrumb-item active" aria-current="page">
                <a href="/member/dashboard" class="breadcrumb-link">xyzDashboard</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
                <a href="/member/stripe_cards/0" class="breadcrumb-link"><?php echo $view_model->get_heading();?></a>
            </li>
           <li class="breadcrumb-item active" aria-current="page">
             xyzAdd
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
<h5 class="primaryHeading2 mb-4 text-md-left">
    Add <?php echo $view_model->get_heading();?>
</h5>
<div class="row mb-5">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="card">
            <div class="card-body">
                <?= form_open( "", array('id' => 'payment-form', 'class'=>'billable-class' )) ?>
				<div class="form-group col-md-6 col-sm-12">
					<label for="Is Default Card">xyzIs Default Card </label>
					<select id="form_is_default" name="is_default" class="form-control">
						<?php foreach ($view_model->is_default_mapping() as $key => $value) {
							echo "<option value='{$key}'> {$value} </option>";
						}?>
					</select>
				</div>
                <div class="form-group col-md-6 col-sm-12">
                    <label for="card name">xyzCard Name</label>
                    <input type="text" name='card_name' class='form-control'>
                </div>
                <div class="form-group col-md-6 col-sm-12" style='width:100%;' >
                    <label for="card-element">
                        xyzCredit or debit card
                    </label>
                    <div id="card-element" class="form-control"></div>
                    <div id="card-errors" role="alert"></div>
                </div>

                <div class="form-group col-md-6 col-sm-12">
                    <input type="submit" class="btn btn-primary" value="Submit">
                </div>
                </form>
            </div>
        </div>
    </div>
</div>


