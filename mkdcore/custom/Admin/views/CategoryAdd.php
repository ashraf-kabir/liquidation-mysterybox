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
            <a href="/admin/category/0" class="breadcrumb-link"><?php echo $view_model->get_heading();?></a>
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



                <div class="form-group col-md-5 col-sm-12">
                    <label for="Image" style="display: block;">Feature Image </label>
                    <span class="img-delete-close " style="display:none" ><i class="fa fa-trash img-wrapper-delete-close"></i></span>
                    <img class="edit-preview-image"  id="output_feature_image" style="max-height:100px"  />
                    <div class="btn uppload-button image_id_uppload_library btn-primary btn-sm  " data-image-url="feature_image" data-image-id="feature_image_id" data-image-preview="output_feature_image" data-view-width="250" data-view-height="250" data-boundary-width="500" data-boundary-height="500">Choose Image</div>
                    <input type="hidden" id="feature_image" data-srcid="output_feature_image" class="check_change_event" name="feature_image" value=""/>
                    <input type="hidden" id="feature_image_id" name="feature_image_id" value=""/>

                    <button type="button" data-preview="output_feature_image"  data-url="feature_image" data-id="feature_image_id"  class="btn btn-primary btn-sm add-image-form-portal create-image-portal-modal">+</button>
                    <span id="feature_image_complete" style="display: block;"></span>
 
                </div>  
 
				 

                <div class="form-group col-md-5 col-sm-12 ">
                    <label for="Name">SKU Prefix </label>
                    <input type="text" class="form-control data-input" id="sku_prefix" name="sku_prefix" value="<?php echo set_value('sku_prefix'); ?>"/>
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



<?php 
    $this->load->view('Guest/ImagePortalModal.php');
?>



 

<script type="text/javascript" src="<?php echo base_url() ?>assets/js/image-portal.js"></script> 
<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function(){  
        $(function(){ 
            $("body").on("change",".check_change_event",function(){
                var current_src_id = $(this).attr('data-srcid'); 
                $('#' + current_src_id).attr('src', $(this).val());

                $(this).parent().find(".img-delete-close").show(); 
            });
        });
    }, false)
</script>