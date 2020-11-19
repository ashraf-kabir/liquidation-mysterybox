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
            Edit
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
                    Edit <?php echo $view_model->get_heading();?>
                </h5>
                <?= form_open() ?>
                
                    <div class="form-group col-md-5 col-sm-12">
                        <label for="Name">Name </label>
                        <input type="text" class="form-control data-input" id="form_name" name="name" value="<?php echo set_value('name', $this->_data['view_model']->get_name());?>"/>
                    </div>
                    <div class="form-group col-md-5 col-sm-12">
                        <label for="Parent Category">Parent Category </label>
                        <select class="form-control data-input" id="form_parent_category_id" name="parent_category_id">
                            <option value="" >Select</option>
                            <?php foreach ($parent_categories as $key => $value) {
                                echo "<option  " . (($view_model->get_parent_category_id() == $value->id && $view_model->get_parent_category_id() != '') ? 'selected' : '') . "  value='{$value->id}'> {$value->name} </option>";
                            }?>
				        </select>  
                    </div>
                    
                    <div class="form-group col-md-5 col-sm-12">
                        <label for="Status">Status </label>
                        <select id="form_status" name="status" class="form-control data-input">
                            <?php foreach ($view_model->status_mapping() as $key => $value) {
                                echo "<option value='{$key}' " . (($view_model->get_status() == $key && $view_model->get_status() != '') ? 'selected' : '') . "> {$value} </option>";
                            }?>
                        </select>
                    </div>

                    
                    <div class="form-group col-md-5 col-sm-12">
                        <input type="submit" class="btn btn-primary ext-white mr-4 my-4" value="Submit">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>