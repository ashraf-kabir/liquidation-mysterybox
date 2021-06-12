<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2020*/
if ($layout_clean_mode) {
    echo '<style>#content{padding:0px !important;}</style>';
}
$delete_link = ""; 
if($this->session->userdata('role') == 1) 
{ 
    $delete_link = '/manager/'; 
}elseif($this->session->userdata('role') == 2) 
{ 
    $delete_link = '/admin/';  
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
                <a href="/admin/inventory/0" class="breadcrumb-link"><?php echo $view_model->get_heading();?></a>  
            <?php }elseif($this->session->userdata('role') == 4) { ?>
                <a href="/manager/inventory/0" class="breadcrumb-link"><?php echo $view_model->get_heading();?></a>
            <?php } ?>
            
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
                    <label for="Product Name">Product Name </label>
                    <input type="text" class="form-control data-input" id="form_product_name" name="product_name" value="<?php echo set_value('product_name', $this->_data['view_model']->get_product_name());?>"/>
                </div>

                <div class="form-group col-md-5 col-sm-12">
                    <label for="SKU">SKU </label>
                    <input type="text" class="form-control data-input" name="sku" id="form_sku" readonly value="<?php echo set_value('sku', $this->_data['view_model']->get_sku());?>"/>
                </div>
                <div class="form-group col-md-5 col-sm-12">
                    <label for="Product Type">Product Type </label>
                    <select id="form_product_type" name="product_type" class="form-control data-input">
                        <?php foreach ($view_model->product_type_mapping() as $key => $value) {
                            echo "<option value='{$key}' " . (($view_model->get_product_type() == $key && $view_model->get_product_type() != '') ? 'selected' : '') . "> {$value} </option>";
                        }?>
                    </select>
                </div>
                
                <div class="form-group col-md-5 col-sm-12">
                    <label for="Category">Category </label>
                    <select  class="form-control data-input" id="form_category_id" name="category_id">
                        <option value="" >Select</option>
                        <?php foreach ($parent_categories as $key => $value) {
                            echo "<option  " . (($view_model->get_category_id() == $value->id && $view_model->get_category_id() != '') ? 'selected' : '') . "   value='{$value->id}'> {$value->name} </option>";
                        }?>
                    </select>  
                </div>


                <div class="form-group col-md-5 col-sm-12 ">
                    <label for="sale_person_id"> Sale Person </label> 
                    <select  class="form-control data-input" id="sale_person_id" name="sale_person_id">
                        <option value="" >Select</option>
                        <?php foreach ($sale_persons as $key => $value) {
                            echo "<option " . (($view_model->get_sale_person_id() == $value->id && $view_model->get_sale_person_id() != '') ? 'selected' : '') . " value='{$value->id}'> {$value->first_name}  {$value->last_name} </option>";
                        }?>
                    </select> 
                </div>
                <div class="form-group col-md-5 col-sm-12">
                    <label for="Store Location">Store </label>
                    <select   class="form-control data-input" id="form_store_location_id" name="store_location_id">
                        <option value="" >Select</option>
                        <?php foreach ($stores as $key => $value) {
                            echo "<option  " . (($view_model->get_store_location_id() == $value->id && $view_model->get_store_location_id() != '') ? 'selected' : '') . "  value='{$value->id}'> {$value->name} </option>";
                        }?>
                    </select>   
                </div>

 

                <div class="form-group col-md-5 col-sm-12">
                    <label for="Manifest">Manifest </label>
                    <input type="text" class="form-control data-input" id="form_manifest_id" name="manifest_id" value="<?php echo set_value('manifest_id', $this->_data['view_model']->get_manifest_id());?>" onkeypress="return (event.charCode >= 48 && event.charCode <= 57) || (event.charCode == 45)"/>
                </div>
                
                <div class="form-group col-md-5 col-sm-12">
                    <label for="Inventory Location">Inventory Location </label>
                    <select class="form-control data-input" id="form_physical_location" name="physical_location">
                        <option value="" >Select</option>
                        <?php foreach ($physical_locations as $key => $value) {
                            echo "<option " . (($view_model->get_physical_location() == $value->id && $view_model->get_physical_location() != '') ? 'selected' : '') . "  value='{$value->id}'> {$value->name} </option>";
                        }?>
                    </select>   
                </div>

                <div class="form-group col-md-5 col-sm-12">
                    <label for="Inventory Location Description">Inventory Location Description </label>
                    <input type="text" class="form-control data-input" id="form_location_description" name="location_description" value="<?php echo set_value('location_description', $this->_data['view_model']->get_location_description());?>"/>
                </div>


                <div class="form-group col-md-5 col-sm-12">
                    <label for="Weight">Weight </label>
                    <input type="text" class="form-control data-input" id="form_weight" name="weight" value="<?php echo set_value('weight', $this->_data['view_model']->get_weight());?>" onkeypress="return mkd_is_number(event,this)"/>
                </div>


                <div class="form-group col-md-5 col-sm-12">
                    <label for="Length">Length </label>
                    <input type="text" class="form-control data-input" id="form_length" name="length" value="<?php echo set_value('length', $this->_data['view_model']->get_length());?>" onkeypress="return mkd_is_number(event,this)"/>
                </div>


                <div class="form-group col-md-5 col-sm-12">
                    <label for="Height">Height </label>
                    <input type="text" class="form-control data-input" id="form_height" name="height" value="<?php echo set_value('height', $this->_data['view_model']->get_height());?>" onkeypress="return mkd_is_number(event,this)"/>
                </div>


                <div class="form-group col-md-5 col-sm-12">
                    <label for="Width">Width </label>
                    <input type="text" class="form-control data-input" id="form_width" name="width" value="<?php echo set_value('width', $this->_data['view_model']->get_width());?>" onkeypress="return mkd_is_number(event,this)"/>
                </div>

                <div class="form-group col-md-5 col-sm-12">
                    <label for="Quantity">Quantity </label>
                    <input type="text" class="form-control data-input" id="form_quantity" name="quantity" value="<?php echo set_value('quantity', $this->_data['view_model']->get_quantity());?>" onkeypress="return (event.charCode >= 48 && event.charCode <= 57) || (event.charCode == 45)"/>
                </div>

                <div class="form-group col-md-5 col-sm-12">
                    <label for="Cost Price">Cost Price </label>
                    <input type="text" class="form-control data-input" id="form_cost_price" name="cost_price" value="<?php echo set_value('cost_price', $this->_data['view_model']->get_cost_price());?>" onkeypress="return mkd_is_number(event,this)"/>
                </div>


                <div class="form-group col-md-5 col-sm-12">
                    <label for="Selling Price">Selling Price </label>
                    <input type="text" class="form-control data-input" id="form_selling_price" name="selling_price" value="<?php echo set_value('selling_price', $this->_data['view_model']->get_selling_price());?>" onkeypress="return mkd_is_number(event,this)"/>
                </div>
                
                
                
                <div class="form-group col-md-5 col-sm-12">
                    <label for="Pin Item">Pin Item </label>
                    <select id="form_pin_item_top" name="pin_item_top" class="form-control data-input">
                        <?php foreach ($view_model->pin_item_top_mapping() as $key => $value) {
                            echo "<option value='{$key}' " . (($view_model->get_pin_item_top() == $key && $view_model->get_pin_item_top() != '') ? 'selected' : '') . "> {$value} </option>";
                        }?>
                    </select>
                </div>

                
                <div class="form-group col-md-5 col-sm-12">
                    <label for="Can Ship">Can Ship </label>
                    <select id="form_can_ship" name="can_ship" class="form-control data-input">
                        <?php foreach ($view_model->can_ship_mapping() as $key => $value) {
                            echo "<option value='{$key}' " . (($view_model->get_can_ship() == $key && $view_model->get_can_ship() != '') ? 'selected' : '') . "> {$value} </option>";
                        }?>
                    </select>
                </div>


                
                <div class="form-group col-md-5 col-sm-12">
                    <label for="Can Ship">Free Shipping </label>
                    <select id="form_can_ship" name="free_ship" class="form-control data-input">
                        <?php foreach ($view_model->free_ship_mapping() as $key => $value) {
                            echo "<option value='{$key}' " . (($view_model->get_free_ship() == $key && $view_model->get_free_ship() != '') ? 'selected' : '') . "> {$value} </option>";
                        }?>
                    </select>
                </div>


                <div class="form-group col-md-5 col-sm-12 mb-4">
                    <label for="Image">Feature Image </label>
                    <img class='edit-preview-image ' style="max-height:100px" id="output_feature_image" src="<?php echo set_value('feature_image', $this->_data['view_model']->get_feature_image());?>" onerror=\"if (this.src != '/uploads/placeholder.jpg') this.src = '/uploads/placeholder.jpg';\"/>
                    <br/><div class="btn btn-primary image_id_uppload_library btn-sm uppload-button  " data-image-url="feature_image" data-image-id="feature_image_id" data-image-preview="output_feature_image" data-view-width="250" data-view-height="250" data-boundary-width="500" data-boundary-height="500">Choose Image</div>
                    <input type="hidden" id="feature_image" name="feature_image" value="<?php echo set_value('feature_image', $this->_data['view_model']->get_feature_image());?>"
                        class="check_change_event"  data-srcid="output_feature_image" 

                    />
                    <input type="hidden" id="feature_image_id" name="feature_image_id" value="<?php echo set_value('feature_image_id', $this->_data['view_model']->get_feature_image_id());?>"/> 
                    <button type="button" data-preview="output_feature_image"  data-url="feature_image" data-id="feature_image_id"  class="btn btn-primary btn-sm add-image-form-portal create-image-portal-modal">+</button>
                    <span id="feature_image_complete" style="display: block;"></span>
                </div>
                
 

                <div class="form-group col-md-12 col-sm-12 ">
                    <div class="mkd-upload-form-btn-wrapper ">
                        <label for="Barcode Image">Gallery Images</label>
                    </div>
                    <div class="row add_images_gallery">
                        <?php 
                            foreach ($gallery_lists as $key => $value) 
                            {
                                echo '<div class="col-md-3 form-group img-wrapper-delete"> <a class="fa fa-trash img-wrapper-delete-close" href="' . $delete_link . 'inventory/delete_gallery_image/' . $value->id .'"></a> <img style="height: 150px;width: 70%;" src="' . $value->image_name . '"></div>';
                            }
                        ?>
                    </div> 
                </div> 

                <div class="form-group col-md-5 col-sm-12 ">
                    <div class="mkd-upload-form-btn-wrapper gallery_image_add_inputs">
                        <button class="mkd-upload-btn btn btn-primary d-block">Upload Image(s)</button>
                        <input type="file" name="barcode_image_upload" multiple id="barcode_image_upload" onchange="manaknightMultipleImageUploader(event, 'manaknight_multiple_image')" accept=".jpg,.jpeg,.png"/>

                        <input type="hidden" id="manaknight_multiple_image_1" name="gallery_image[]"/>
                        <input type="hidden" id="manaknight_multiple_image_id_1" name="gallery_image_id[]"/> 
                    </div>
                </div>


                
                <?php 
                $video_url = json_decode($this->_data['view_model']->get_video_url());
                ?>

                <div class="form-group  col-md-5 col-sm-12">
                    <label for="video_url" >Youtube URL 1 </label>
                    <input type="url"  class="form-control data-input"  name="video_url[]" value="<?php echo isset($video_url[0]) ? $video_url[0] : ''; ?>"  /> 
                </div>

                <div class="form-group col-md-5 col-sm-12 mb-4">
                    <label for="Image">Youtube Thumbnail 1</label>
                    <img class='edit-preview-image d-block' style="max-height:100px" id="output_youtube_thumbnail_1" src="<?php echo set_value('youtube_thumbnail_1', $this->_data['view_model']->get_youtube_thumbnail_1());?>" />

                    <br/>
                    <div class="btn btn-primary image_id_uppload_library btn-sm uppload-button  " data-image-url="youtube_thumbnail_1" data-image-id="youtube_thumbnail_1_id" data-image-preview="output_youtube_thumbnail_1" data-view-width="250" data-view-height="250" data-boundary-width="500" data-boundary-height="500">Choose Image</div>
                    <input type="hidden" id="youtube_thumbnail_1" name="youtube_thumbnail_1" value="<?php echo set_value('youtube_thumbnail_1', $this->_data['view_model']->get_youtube_thumbnail_1());?>"    class="check_change_event"  data-srcid="output_youtube_thumbnail_1" /> 
                    <span id="youtube_thumbnail_1_complete" style="display: block;"></span>
                </div>



                <div class="form-group  col-md-5 col-sm-12">
                    <label for="video_url" >Youtube URL 2 </label>
                    <input type="url"  class="form-control data-input"  name="video_url[]" value="<?php echo isset($video_url[1]) ? $video_url[1] : ''; ?>"   /> 
                </div>


                <div class="form-group col-md-5 col-sm-12 mb-4">
                    <label for="Image">Youtube Thumbnail 2</label>
                    <img class='edit-preview-image d-block' style="max-height:100px" id="output_youtube_thumbnail_2" src="<?php echo set_value('youtube_thumbnail_2', $this->_data['view_model']->get_youtube_thumbnail_2());?>" />

                    <br/>
                    <div class="btn btn-primary image_id_uppload_library btn-sm uppload-button  " data-image-url="youtube_thumbnail_2" data-image-id="youtube_thumbnail_2_id" data-image-preview="output_youtube_thumbnail_2" data-view-width="250" data-view-height="250" data-boundary-width="500" data-boundary-height="500">Choose Image</div>
                    <input type="hidden" id="youtube_thumbnail_2" name="youtube_thumbnail_2" value="<?php echo set_value('youtube_thumbnail_2', $this->_data['view_model']->get_youtube_thumbnail_2());?>"   class="check_change_event"  data-srcid="output_youtube_thumbnail_2" />
                    
                    <span id="youtube_thumbnail_2_complete" style="display: block;"></span>
                </div>


                <div class="form-group  col-md-5 col-sm-12">
                    <label for="video_url" >Youtube URL 3</label>
                    <input type="url"  class="form-control data-input"  name="video_url[]" value="<?php echo isset($video_url[2]) ? $video_url[2] : ''; ?>"   /> 
                </div>


                <div class="form-group col-md-5 col-sm-12 mb-4">
                    <label for="Image">Youtube Thumbnail 3</label>
                    <img class='edit-preview-image d-block' style="max-height:100px" id="output_youtube_thumbnail_3" src="<?php echo set_value('youtube_thumbnail_3', $this->_data['view_model']->get_youtube_thumbnail_3());?>" />

                    <br/>
                    <div class="btn btn-primary image_id_uppload_library btn-sm uppload-button  " data-image-url="youtube_thumbnail_3" data-image-id="youtube_thumbnail_3_id" data-image-preview="output_youtube_thumbnail_3" data-view-width="250" data-view-height="250" data-boundary-width="500" data-boundary-height="500">Choose Image</div>
                    <input type="hidden" id="youtube_thumbnail_3" name="youtube_thumbnail_3" value="<?php echo set_value('youtube_thumbnail_3', $this->_data['view_model']->get_youtube_thumbnail_3());?>"  
                     class="check_change_event"  data-srcid="output_youtube_thumbnail_3"
                     /> 
                    
                    <span id="youtube_thumbnail_3_complete" style="display: block;"></span>
                </div>



                <div class="form-group  col-md-5 col-sm-12">
                    <label for="video_url" >Youtube URL 4 </label>
                    <input type="url"  class="form-control data-input"  name="video_url[]" value="<?php echo isset($video_url[3]) ? $video_url[3] : ''; ?>"   /> 
                </div>
 
                <div class="form-group col-md-5 col-sm-12 mb-4">
                    <label for="Image">Youtube Thumbnail 4</label>
                    <img class='edit-preview-image d-block' style="max-height:100px" id="output_youtube_thumbnail_4" src="<?php echo set_value('youtube_thumbnail_4', $this->_data['view_model']->get_youtube_thumbnail_4());?>" />

                    <br/>
                    <div class="btn btn-primary image_id_uppload_library btn-sm uppload-button  " data-image-url="youtube_thumbnail_4" data-image-id="youtube_thumbnail_4_id" data-image-preview="output_youtube_thumbnail_4" data-view-width="250" data-view-height="250" data-boundary-width="500" data-boundary-height="500">Choose Image</div>
                    <input type="hidden" id="youtube_thumbnail_4" name="youtube_thumbnail_4" value="<?php echo set_value('youtube_thumbnail_4', $this->_data['view_model']->get_youtube_thumbnail_4());?>"   class="check_change_event"  data-srcid="output_youtube_thumbnail_4" /> 
                    <span id="youtube_thumbnail_4_complete" style="display: block;"></span>
                </div>



                <div class="form-group col-md-5 col-sm-12">
                    <label for="Inventory Note">Description </label>
                    <textarea id='form_inventory_note' name='inventory_note' class='data-input form-control' rows='5'><?php echo set_value('inventory_note', $this->_data['view_model']->get_inventory_note());?></textarea>
                </div>
                
                <div class="form-group col-md-5 col-sm-12">
                    <label for="Admin Inventory Note">Admin Inventory Note </label>
                    <textarea id='form_admin_inventory_note' name='admin_inventory_note' class='data-input form-control' rows='5'><?php echo set_value('admin_inventory_note', $this->_data['view_model']->get_admin_inventory_note());?></textarea>
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

<?php 
    $this->load->view('Guest/ImagePortalModal.php');
?>



 

<script type="text/javascript" src="<?php echo base_url() ?>assets/js/image-portal.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>assets/js/multiple-gallery-image-upload.js"></script>



<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function(){  
        $(function(){ 
            $("body").on("change",".check_change_event",function(){
                var current_src_id = $(this).attr('data-srcid'); 
                $('#' + current_src_id).attr('src', $(this).val());
            });
        });
    }, false)
</script>