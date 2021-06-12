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
                <a href="/admin/customer/0" class="breadcrumb-link"><?php echo $heading;?></a>  
            <?php }elseif($this->session->userdata('role') == 4) { ?>
                <a href="/manager/customer/0" class="breadcrumb-link"><?php echo $heading;?></a>
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
                    Edit <?php echo $heading;?>
                </h5>
                <?= form_open() ?>
                    <div class="form-group col-md-5 col-sm-12 ">
    					<label for="home_page_top_text">Top Text </label>
    					<input type="text" class="form-control data-input" id="home_page_top_text" name="home_page_top_text" value="<?php echo set_value('home_page_top_text', $home_page_top_text); ?>"/>
    				</div>
    				<div class="form-group col-md-5 col-sm-12 ">
    					<label for="Email">Color </label>
    					<input type="text" class="form-control data-input" id="home_page_top_color" name="home_page_top_color" value="<?php echo set_value('home_page_top_color', $home_page_top_color); ?>"/>
    				</div>

                    <div class="form-group col-md-5 col-sm-12">
    					<label for="home_page_top_bg">Background Color </label>
    					<input type="text" class="form-control data-input" id="home_page_top_bg" name="home_page_top_bg" value="<?php echo set_value('home_page_top_bg', $home_page_top_bg); ?>"/>
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