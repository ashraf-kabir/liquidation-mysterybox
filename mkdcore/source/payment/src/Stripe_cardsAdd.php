<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
?>
<div class="row">
	<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
		<div class="page-header">
			<h2 class="pageheader-title"><?php echo $view_model->get_heading();?> </h2>
			<div class="page-breadcrumb">
				<nav aria-label="breadcrumb">
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="/member/dashboard" class="breadcrumb-link">Dashboard</a></li>
						<li class="breadcrumb-item"><a href="/member/stripe_cards/0" class="breadcrumb-link"><?php echo $view_model->get_heading();?></a></li>
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
                <?= form_open( "", array('id' => 'payment-form', 'class'=>'billable-class' )) ?>
				<div class="form-group">
					<label for="Is Default Card">Is Default Card </label>
					<select id="form_is_default" name="is_default" class="form-control">
						<?php foreach ($view_model->is_default_mapping() as $key => $value) {
							echo "<option value='{$key}'> {$value} </option>";
						}?>
					</select>
				</div>
                <div class="form-group" style='width:100%;' >
                    <label for="card-element">
                        Credit or debit card
                    </label>
                    <div id="card-element" class="form-control"></div>
                    <div id="card-errors" role="alert"></div>              
                </div>

                <div class="form-group">
                    <input type="submit" class="btn btn-primary" value="Submit">
                </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://js.stripe.com/v3/"></script>
<script src="/assets/js/stripe_client.js"></script>