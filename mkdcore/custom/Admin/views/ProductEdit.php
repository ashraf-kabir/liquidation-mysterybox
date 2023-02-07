<?php
defined('BASEPATH') or exit('No direct script access allowed');
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2020*/
if ($layout_clean_mode) {
    echo '<style>#content{padding:0px !important;}</style>';
}

$delete_link = "";
if ($this->session->userdata('role') == 1) {
    $delete_link = '/manager/';
} elseif ($this->session->userdata('role') == 2) {
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
                <a href="/admin/product/0" class="breadcrumb-link"><?php echo $view_model->get_heading(); ?></a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
                Edit
            </li>
        </ol>
    </div>
    <br />
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
                        Edit <?php echo $view_model->get_heading(); ?>
                    </h5>
                    <?= form_open() ?>
                    <div class="form-group col-md-5 col-sm-12">
                        <label for="Product Name">Product Name </label>
                        <input type="text" class="form-control data-input" id="form_product_name" name="product_name" value="<?php echo set_value('product_name', $this->_data['view_model']->get_product_name()); ?>" />
                    </div>

                    <div class="form-group col-md-5 col-sm-12">
                        <label for="Parent Category">Category </label>
                        <select required class="form-control data-input" id="form_category_id" name="category_id" onchange="updateSKU(this)">
                            <option value="">Select</option>
                            <?php foreach ($parent_categories as $key => $value) {
                                $child_category_tab = $value->parent_category_id == 0 || $value->parent_category_id == null ? '' : '&nbsp;&nbsp;&nbsp;&nbsp;';
                                echo "<option  " . (($view_model->get_category_id() == $value->id && $view_model->get_category_id() != '') ? 'selected' : '') . "   value='{$value->id}'> {$child_category_tab} {$value->name} </option>";
                            } ?>
                        </select>
                        <input type="hidden" name="sale_person_id" value="<?= $this->session->userdata('user_id') ?>">
                        <input type="hidden" name="locations" value="null">
                        <input type="hidden" name="is_product" value="1">
                        <input type="hidden" id="encoded_parent_categories" value="<?= $encoded_parent_categories ?>">
                    </div>

                    <div class="form-group col-md-5 col-sm-12">
                        <label for="SKU">SKU </label>
                        <input type="text" class="form-control data-input" id="form_sku" name="sku" value="<?php echo set_value('sku', $this->_data['view_model']->get_sku()); ?>" readonly />
                    </div>

                    <div class="form-group col-md-5 col-sm-12">
                        <label for="Manifest">Manifest </label>
                        <input type="text" class="form-control data-input" id="form_manifest_id" name="manifest_id" value="<?php echo set_value('manifest_id', $this->_data['view_model']->get_manifest_id()); ?>" onkeypress="return (event.charCode >= 48 && event.charCode <= 57) || (event.charCode == 45)" />
                    </div>




                    <div class="form-group col-md-5 col-sm-12">
                        <label for="Weight">Weight (lbs) <span class="text-danger">*</span></label>
                        <input type="text" class="form-control data-input" id="form_weight" name="weight" value="<?php echo set_value('weight', $this->_data['view_model']->get_weight()); ?>" onkeypressing="return mkd_is_number(event,this)" />
                    </div>


                    <div class="form-group col-md-5 col-sm-12">
                        <label for="Length">Length <span class="text-danger">*</span></label>
                        <input type="text" class="form-control data-input" id="form_length" name="length" value="<?php echo set_value('length', $this->_data['view_model']->get_length()); ?>" onkeypress="return mkd_is_number(event,this)" />
                    </div>


                    <div class="form-group col-md-5 col-sm-12">
                        <label for="Height">Height <span class="text-danger">*</span></label>
                        <input type="text" class="form-control data-input" id="form_height" name="height" value="<?php echo set_value('height', $this->_data['view_model']->get_height()); ?>" onkeypress="return mkd_is_number(event,this)" />
                    </div>


                    <div class="form-group col-md-5 col-sm-12">
                        <label for="Width">Width <span class="text-danger">*</span></label>
                        <input type="text" class="form-control data-input" id="form_width" name="width" value="<?php echo set_value('width', $this->_data['view_model']->get_width()); ?>" onkeypress="return mkd_is_number(event,this)" />
                    </div>



                    <div class="form-group col-md-5 col-sm-12">
                        <label for="Cost Price">Cost Price </label>
                        <input type="text" class="form-control data-input" id="form_cost_price" name="cost_price" value="<?php echo set_value('cost_price', $this->_data['view_model']->get_cost_price()); ?>" onkeypress="return mkd_is_number(event,this)" />
                    </div>


                    <div class="form-group col-md-5 col-sm-12">
                        <label for="Selling Price">Selling Price </label>
                        <input type="text" class="form-control data-input" id="form_selling_price" name="selling_price" value="<?php echo set_value('selling_price', $this->_data['view_model']->get_selling_price()); ?>" onkeypress="return mkd_is_number(event,this)" />
                    </div>



                    <div class="form-group col-md-5 col-sm-12">
                        <label for="Pin Item">Pin Item </label>
                        <select id="form_pin_item_top" name="pin_item_top" class="form-control data-input">
                            <?php foreach ($view_model->pin_item_top_mapping() as $key => $value) {
                                echo "<option value='{$key}' " . (($view_model->get_pin_item_top() == $key && $view_model->get_pin_item_top() != '') ? 'selected' : '') . "> {$value} </option>";
                            } ?>
                        </select>
                    </div>


                    <div class="form-group col-md-5 col-sm-12">
                        <label for="Can Ship">Can Ship </label>
                        <select id="form_can_ship" name="can_ship" class="form-control data-input">
                            <?php foreach ($view_model->can_ship_mapping() as $key => $value) {
                                echo "<option value='{$key}' " . (($view_model->get_can_ship() == $key && $view_model->get_can_ship() != '') ? 'selected' : '') . "> {$value} </option>";
                            } ?>
                        </select>
                    </div>

                    <div class="form-group col-md-5 col-sm-12">
                        <label for="Can Ship Approval">Can Ship Approval </label>
                        <select id="form_can_ship_approval" name="can_ship_approval" class="form-control data-input">
                            <?php foreach ($view_model->can_ship_approval_mapping() as $key => $value) {
                                echo "<option value='{$key}' " . (($view_model->get_can_ship_approval() == $key && $view_model->get_can_ship_approval() != '') ? 'selected' : '') . "> {$value} </option>";
                            } ?>
                        </select>
                    </div>



                    <div class="form-group col-md-5 col-sm-12">
                        <label for="Can Ship">Free Shipping </label>
                        <select id="form_can_ship" name="free_ship" class="form-control data-input">
                            <?php foreach ($view_model->free_ship_mapping() as $key => $value) {
                                echo "<option value='{$key}' " . (($view_model->get_free_ship() == $key && $view_model->get_free_ship() != '') ? 'selected' : '') . "> {$value} </option>";
                            } ?>
                        </select>
                    </div>


                    <div class="form-group col-md-5 col-sm-12 mb-4  img-wrapper-delete">

                        <label for="Image" style="display: block;">Feature Image </label>

                        <span class="img-delete-close " <?php if (empty($this->_data['view_model']->get_feature_image())) : ?> style="display: none;" <?php endif ?>><i class="fa fa-trash img-wrapper-delete-close"></i></span>
                        <img class='edit-preview-image ' style="max-height:100px" id="output_feature_image" src="<?php echo set_value('feature_image', $this->_data['view_model']->get_feature_image()); ?>" />


                        <div class="mkd-upload-form-btn-wrapper gallery_image_add_inputs">
                            <button class="mkd-upload-btn btn btn-primary d-block">Choose Image</button>
                            <input type="file" name="feature_image_upload" id="" class="" onchange="uploadFeatureImage(this)" accept=".jpg,.jpeg,.png">

                            <br />
                            <!-- <div class="btn btn-primary image_id_uppload_library btn-sm uppload-button  " data-image-url="feature_image" data-image-id="feature_image_id" data-image-preview="output_feature_image" data-view-width="250" data-view-height="250" data-boundary-width="500" data-boundary-height="500">Choose Image</div> -->
                            <input type="hidden" id="feature_image" name="feature_image" value="<?php echo set_value('feature_image', $this->_data['view_model']->get_feature_image()); ?>" class="check_change_event" data-srcid="output_feature_image" />
                            <input type="hidden" id="feature_image_id" name="feature_image_id" value="<?php echo set_value('feature_image_id', $this->_data['view_model']->get_feature_image_id()); ?>" />
                        </div>
                        <button type="button" data-preview="output_feature_image" data-url="feature_image" data-id="feature_image_id" class="btn btn-primary btn-sm add-image-form-portal create-image-portal-modal pt5" style="margin-bottom: 70px;">+</button>
                        <span id="feature_image_complete" style="display: block;"></span>
                    </div>



                    <div class="form-group col-md-12 col-sm-12 ">
                        <div class="mkd-upload-form-btn-wrapper ">
                            <label for="Barcode Image">Gallery Images</label>
                        </div>
                        <div class="row add_images_gallery">
                            <?php
                            foreach ($gallery_lists as $key => $value) {
                                echo '<div class="col-md-3 form-group img-wrapper-delete"> <a class="fa fa-trash img-wrapper-delete-close" href="' . $delete_link . 'inventory/delete_gallery_image/' . $value->id . '"></a> <img style="height: 150px;width: 70%;" src="' . $value->image_name . '"></div>';
                            }
                            ?>
                        </div>
                    </div>

                    <div class="form-group col-md-5 col-sm-12 ">
                        <div class="mkd-upload-form-btn-wrapper gallery_image_add_inputs">
                            <button class="mkd-upload-btn btn btn-primary d-block">Upload Image(s)</button>
                            <input type="file" name="barcode_image_upload" multiple id="barcode_image_upload" onchange="manaknightMultipleImageUploader(event, 'manaknight_multiple_image')" accept=".jpg,.jpeg,.png" />

                            <input type="hidden" id="manaknight_multiple_image_1" name="gallery_image[]" />
                            <input type="hidden" id="manaknight_multiple_image_id_1" name="gallery_image_id[]" />
                        </div>
                    </div>



                    <?php
                    $images_data = json_decode($this->_data['view_model']->get_youtube_thumbnail_1());
                    $videos_data = json_decode($this->_data['view_model']->get_video_url());
                    $counter = 2;
                    $index_i = 0;
                    ?>

                    <div class="form-group col-md-12 col-sm-12">
                        <div class="card" style="background-color: #fff; background-clip: border-box; border: 1px solid rgba(0,0,0,.125) !important; border-radius: .25rem;">
                            <div class="card-header" style="text-align: end;"> <button class="btn btn-primary add_more_link" type="button">Add More</button> </div>

                            <div class="card-body card-body-add-row">

                                <?php if (!empty($videos_data)) : ?>



                                    <?php foreach ($videos_data as $key => $value) : ?>

                                        <?php if (empty($videos_data[$key])) : continue;
                                        endif;
                                        $counter++;
                                        $index_i++; ?>

                                        <div class="row thumbnail_video_row">
                                            <div class="form-group col-md-12 col-sm-12">
                                                <label class="label-full"><span class="label-text-is">Video URL <?php echo $index_i; ?> </span>



                                                    <span class="delete-full " <?php if ($key == 0) : ?> style="display: none;" <?php endif ?>><i class="fa fa-trash img-wrapper-delete-close"></i></span>


                                                </label>
                                                <input type="text" class="form-control validate_url_field width-75 data-input" name="video_url[]" value="<?php echo $videos_data[$key] ?>" />
                                            </div>

                                            <div class="form-group col-md-12 col-sm-12">
                                                <label>Choose Thumbnail <?php echo $index_i; ?></label>
                                                <span class="youtube-image-delete-close"><i class="fa fa-trash img-wrapper-delete-close"></i></span>

                                                <img id="output_youtube_thumbnail_1" class="output_youtube_thumbnail_1  pb-2" style="max-height:100px" src="<?php echo $images_data[$key] ?>" />
                                                <div style="margin: 0px;" class="btn btn-primary btn-sm mkd-choose-image-thumbnail" data-image-url="youtube_thumbnail_1" data-image-id="youtube_thumbnail_1_id" data-image-preview="output_youtube_thumbnail_1" data-view-width="250" data-view-height="250" data-boundary-width="500" data-boundary-height="500">Browse</div>


                                                <input type="hidden" class="youtube_thumbnail_1 validate_img_field" id="youtube_thumbnail_1" name="youtube_thumbnail_1[]" value="<?php echo $images_data[$key] ?>" />

                                                <input type="hidden" class="youtube_thumbnail_1_id" id="youtube_thumbnail_1_id" name="youtube_thumbnail_1_id[]" value="" />


                                                <input type="file" style="display:none" class="file_to_upload_class" onchange="manaknightThumbnailImage(event, 'manaknight_multiple_image')" accept=".jpg,.jpeg,.png" />
                                            </div>
                                        </div>

                                    <?php endforeach ?>

                                <?php endif ?>

                                <?php if ($counter == 2) :  $counter++; ?>
                                    <div class="row thumbnail_video_row">
                                        <div class="form-group col-md-12 col-sm-12">
                                            <label class="label-full"> <span class="label-text-is">Video URL 1 </span>


                                                <span class="delete-full " style="display: none;"><i class="fa fa-trash img-wrapper-delete-close"></i></span>

                                            </label>
                                            <input type="text" class="form-control validate_url_field width-75 data-input" name="video_url[]" value="" />
                                        </div>

                                        <div class="form-group col-md-12 col-sm-12">
                                            <label>Choose Thumbnail 1</label>
                                            <img id="output_youtube_thumbnail_1" class="output_youtube_thumbnail_1  pb-2" style="max-height:100px" />
                                            <div style="margin: 0px;" class="btn btn-primary btn-sm mkd-choose-image-thumbnail" data-image-url="youtube_thumbnail_1" data-image-id="youtube_thumbnail_1_id" data-image-preview="output_youtube_thumbnail_1" data-view-width="250" data-view-height="250" data-boundary-width="500" data-boundary-height="500">Browse</div>


                                            <input type="hidden" class="youtube_thumbnail_1 validate_img_field" id="youtube_thumbnail_1" name="youtube_thumbnail_1[]" value="" />

                                            <input type="hidden" class="youtube_thumbnail_1_id" id="youtube_thumbnail_1_id" name="youtube_thumbnail_1_id[]" value="" />


                                            <input type="file" style="display:none" class="file_to_upload_class" onchange="manaknightThumbnailImage(event, 'manaknight_multiple_image')" accept=".jpg,.jpeg,.png" />
                                        </div>
                                    </div>
                                <?php endif ?>

                            </div>
                        </div>
                    </div>


                    <div class="form-group col-md-5 col-sm-12">
                        <label for="Inventory Note">Product Note </label>
                        <textarea id='subeditor_inventory_note' name='inventory_note' class='data-input form-control subeditor_inventory_note' rows='5'><?php echo set_value('inventory_note', $this->_data['view_model']->get_inventory_note()); ?></textarea>
                    </div>
                    <div class="form-group col-md-5 col-sm-12">
                        <label for="Admin Inventory Note">Admin Product Note </label>
                        <textarea id='form_admin_inventory_note' name='admin_inventory_note' class='data-input form-control' rows='5'><?php echo set_value('admin_inventory_note', $this->_data['view_model']->get_admin_inventory_note()); ?></textarea>
                    </div>

                    <div class="form-group col-md-5 col-sm-12">
                        <label for="Status">Status </label>
                        <select id="form_status" name="status" class="form-control data-input">
                            <?php foreach ($view_model->status_mapping() as $key => $value) {
                                echo "<option value='{$key}' " . (($view_model->get_status() == $key && $view_model->get_status() != '') ? 'selected' : '') . "> {$value} </option>";
                            } ?>
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

    <!-- New Physical location Modal -->

    <!-- Modal -->
    <div class="modal fade" id="newPhysicalLocationModal" tabindex="-1" role="dialog" aria-labelledby="newPhysicalLocationLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="newPhysicalLocationModalLabel"> Add new Physical Location</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <input type="hidden" id="location_trigger" value="">
                        <label for=""> Select Store</label>
                        <select name="new_physical_location_store" id="new_physical_location_store" class="form-control">
                            <option value=""></option>
                            <?php foreach ($stores as $store) : ?>
                                <option value="<?php echo $store->id ?>"><?php echo $store->name ?></option>
                            <?php endforeach; ?>
                        </select>
                        <label for=""> Store Inventory Location</label>
                        <input type="text" name="new_physical_location" id="new_physical_location" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" onclick="createInventoryLocation(this)" class="btn btn-primary">Add</button>
                </div>
            </div>
        </div>
    </div>

    <?php
    $this->load->view('Guest/ImagePortalModal.php');
    ?>

    <?php $this->load->view('Guest/ImageThumbnailModel'); ?>

    <script type="text/javascript" src="<?php echo base_url() ?>assets/js/image-portal.js"></script>
    <script type="text/javascript" src="<?php echo base_url() ?>assets/js/multiple-gallery-image-upload.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/video_image_script.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/suneditor@latest/dist/css/suneditor.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/suneditor@latest/dist/suneditor.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/suneditor@latest/src/lang/ko.js"></script>
    <script type="text/javascript">
        function updateSKU(el) {
            const categories = getCategories();
            console.log(categories);
            let category_value = el.value;
            let sku_value = categories.filter(category => category.id === category_value)
            console.log(sku_value);
            let skuElement = document.querySelector("#form_sku");
            skuElement.value = sku_value[0].sku_prefix;
        }

        function getCategories() {
            let categories = JSON.parse(atob(document.querySelector('#encoded_parent_categories').value));
            return categories;
        }

        const editor = SUNEDITOR.create(('subeditor_inventory_note'), {
            buttonList: [
                ['undo', 'redo'],
                ['font', 'fontSize', 'formatBlock'],
                ['paragraphStyle', 'blockquote'],
                ['bold', 'underline', 'italic', 'strike', 'subscript', 'superscript'],
                ['textStyle'],
                ['removeFormat'],
                '/', // Line break
                ['outdent', 'indent'],
                ['align', 'horizontalRule', 'list', 'lineHeight'],
                ['table', 'link', 'image', 'video', 'audio' /** ,'math' */ ], // You must add the 'katex' library at options to use the 'math' plugin.
                /** ['imageGallery'] */ // You must add the "imageGalleryUrl".
                ['fullScreen', 'showBlocks', 'codeView'],
                ['preview', 'print'],
                ['save']
            ],
            // All of the plugins are loaded in the "window.SUNEDITOR" object in dist/suneditor.min.js file
            // Insert options
            // Language global object (default: en)
            lang: SUNEDITOR_LANG['en']
        });

        editor.onChange = (contents, core) => {
            $('._se_command_save').trigger('click');
            $('[data-command="save"]').trigger('click');
        }

        function uploadFeatureImage(e) {

            console.log(e.files[0]);
            var file = e.files[0];

            var formData = new FormData();
            formData.append('file', file);

            $.ajax({
                type: "POST",
                url: "/v1/api/file/upload",
                enctype: 'multipart/form-data',
                timeout: 15000,
                processData: false,
                contentType: false,
                cache: false,
                data: formData,
                success: function(response) {
                    console.log(response);
                    document.getElementById("output_feature_image").setAttribute("src", response.file);
                    document.getElementById("feature_image").value = response.file;
                    document.getElementById("feature_image_id").value = response.id;
                },
                error: function(response) {
                    console.log(response);
                }
            });
        }
    </script>