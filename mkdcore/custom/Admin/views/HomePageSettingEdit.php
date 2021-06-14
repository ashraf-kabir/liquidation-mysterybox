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
                        <label for="home_page_address">Address </label>
                        <input type="text" class="form-control data-input" id="home_page_address" name="home_page_address" value="<?php echo set_value('home_page_address', $home_page_address); ?>"/>
                    </div>
                    <div class="form-group col-md-5 col-sm-12 ">
                        <label for="home_page_phone_no">Phone# </label>
                        <input type="text" class="form-control data-input" id="home_page_phone_no" name="home_page_phone_no" value="<?php echo set_value('home_page_phone_no', $home_page_phone_no); ?>"/>
                    </div>

                    <div class="form-group col-md-5 col-sm-12">
                        <label for="home_page_time">Office Timing </label>
                        <input type="text" class="form-control data-input" id="home_page_time" name="home_page_time" value="<?php echo set_value('home_page_time', $home_page_time); ?>"/>
                    </div>



                    <div class="form-group col-md-5 col-sm-12 ">
                        <label for="home_page_support_email">Support Email </label>
                        <input type="email" class="form-control data-input" id="home_page_support_email" name="home_page_support_email" value="<?php echo set_value('home_page_support_email', $home_page_support_email); ?>"/>
                    </div>
                    <div class="form-group col-md-5 col-sm-12 ">
                        <label for="home_page_fb_link">Facebook Link </label>
                        <input type="text" class="form-control data-input" id="home_page_fb_link" name="home_page_fb_link" value="<?php echo set_value('home_page_fb_link', $home_page_fb_link); ?>"/>
                    </div>

                    <div class="form-group col-md-5 col-sm-12">
                        <label for="home_page_tiktok_link">Tiktok Link </label>
                        <input type="text" class="form-control data-input" id="home_page_tiktok_link" name="home_page_tiktok_link" value="<?php echo set_value('home_page_tiktok_link', $home_page_tiktok_link); ?>"/>
                    </div>


                    <div class="form-group col-md-5 col-sm-12">
                        <label for="home_page_insta_link">Instagram Link </label>
                        <input type="text" class="form-control data-input" id="home_page_insta_link" name="home_page_insta_link" value="<?php echo set_value('home_page_insta_link', $home_page_insta_link); ?>"/>
                    </div>


                    <div class="form-group col-md-5 col-sm-12">
                        <label for="home_page_twitter_link">Twitter Link </label>
                        <input type="text" class="form-control data-input" id="home_page_twitter_link" name="home_page_twitter_link" value="<?php echo set_value('home_page_twitter_link', $home_page_twitter_link); ?>"/>
                    </div>


                    <div class="form-group col-md-5 col-sm-12">
                        <label for="home_page_pintrest_link">Pintrest Link</label>
                        <input type="text" class="form-control data-input" id="home_page_pintrest_link" name="home_page_pintrest_link" value="<?php echo set_value('home_page_pintrest_link', $home_page_pintrest_link); ?>"/>
                    </div>


                    <!-- <div class="form-group col-md-5 col-sm-12">
                        <label for="product_text_note">Text about how to buy item</label> 
                        <input type="text" class="form-control data-input" id="product_text_note" name="product_text_note" value="<?php echo set_value('product_text_note', $product_text_note); ?>"/>
                    </div>
                      -->
                        
                    <div class="form-group  col-md-5 col-sm-12">
                        <input type="submit" class="btn btn-primary text-white mr-4 my-4" value="Submit">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>