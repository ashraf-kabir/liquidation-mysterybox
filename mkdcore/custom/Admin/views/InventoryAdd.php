<?php
defined('BASEPATH') or exit('No direct script access allowed');
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2020*/
if ($layout_clean_mode) {
    echo '<style>#content{padding:0px !important;}</style>';
}
?>
<style type="text/css">
    .img-fluid {
        max-width: 100%;
        height: auto;
        width: 100%;
        max-height: 150px;
        min-height: 150px;
    }

    .label-full {
        width: 100%;

    }

    .delete-full {
        float: right;
    }

    .youtube-image-delete-close {
        display: block;
    }
</style>
<div class="tab-content mx-4" id="nav-tabContent">
    <!-- Bread Crumb -->
    <div aria-label="breadcrumb">
        <ol class="breadcrumb pl-0 mb-4 bg-background d-flex justify-content-center justify-content-md-start">
            <!-- <li class="breadcrumb-item active" aria-current="page">
            <a href="/admin/dashboard" class="breadcrumb-link">Dashboard</a>
        </li> -->
            <li class="breadcrumb-item active" aria-current="page">
                <?php if ($this->session->userdata('role') == 2) { ?>
                    <a href="/admin/inventory/0" class="breadcrumb-link"><?php echo $view_model->get_heading(); ?></a>
                <?php } elseif ($this->session->userdata('role') == 4) { ?>
                    <a href="/manager/inventory/0" class="breadcrumb-link"><?php echo $view_model->get_heading(); ?></a>
                <?php } ?>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
                Add
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
                        Add <?php echo $view_model->get_heading(); ?>
                    </h5>
                    <?= form_open() ?>
                    <div class="form-group col-md-5 col-sm-12 ">
                        <label for="Product Name">Product Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control data-input" id="form_product_name" name="product_name" value="<?php echo set_value('product_name'); ?>" />
                    </div>



                    <div class="form-group col-md-5 col-sm-12 ">
                        <label for="Product Type">Product Type </label>
                        <select id="form_product_type" name="product_type" class="form-control data-input">
                            <?php foreach ($view_model->product_type_mapping() as $key => $value) {

                                echo "<option value='{$key}'> {$value} </option>";
                            } ?>
                        </select>
                    </div>



                    <div class="form-group col-md-5 col-sm-12 ">
                        <label for="Parent Category"> Category <span class="text-danger">*</span> </label>
                        <select required class="form-control data-input" id="form_category_id" name="category_id">
                            <option value="">Select</option>
                            <?php foreach ($parent_categories as $key => $value) {
                                $child_category_tab = $value->parent_category_id == 0 || $value->parent_category_id == null ? '' : '&nbsp;&nbsp;&nbsp;&nbsp;';
                                echo "<option value='{$value->id}'> {$child_category_tab} {$value->name} </option>";
                            } ?>
                        </select>
                    </div>


                    <div class="form-group col-md-5 col-sm-12 ">
                        <label for="sale_person_id"> Sale Person <span class="text-danger">*</span></label>
                        <select required class="form-control data-input" id="sale_person_id" name="sale_person_id">
                            <option value="">Select</option>
                            <?php foreach ($sale_persons as $key => $value) {

                                $selected = "";
                                if ($this->session->userdata('role') != 2 && $value->id == $this->session->userdata('user_id')) {
                                    $selected = " selected ";
                                }
                                echo "<option  " . $selected . " value='{$value->id}'> {$value->first_name}  {$value->last_name} </option>";
                            } ?>
                        </select>
                    </div>
                    <fieldset class="col-md-5 ml-3 form-group border-bottom">
                        <hr>
                        <legend>Item Store Management</legend>
                        <div id="store-inventories" encoded-locations="<?php echo $encoded_physical_locations; ?> ">
                            <div class="store shadow-sm my-2 p-2">
                                <div class="form-group">
                                    <label for="">Store <span class="text-danger">*</span></label>
                                    <select required name="stores[]" id="" role='store' class="form-control" onchange="listStoreLocations(this)">
                                        <option value=""></option>
                                        <?php foreach ($stores as $store) : ?>
                                            <option value="<?php echo $store->id ?>"> <?php echo $store->name; ?></option>
                                        <?php endforeach; ?>
                                    </select>

                                </div>
                                <div class="form-group">
                                    <label for="">Physical Location <span class="text-danger">*</span></label>
                                    <div class="d-flex">
                                        <select required name="locations[]" class="form-control location-dropdown" role="physical-location" onchange="updateLocationState(this)">
                                            <option value=""></option>
                                        </select>
                                        <!-- Only admin can add new location -->
                                        <?php if ($this->session->userdata('role') == 2) : ?>
                                            <button type="button" class="btn btn-primary" onclick="newPhysicalLocationToggle(this)">
                                                <i class="fas fa-plus-circle"></i>
                                            </button>
                                        <?php endif ?>

                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="">Quantity <span class="text-danger">*</span></label>
                                    <input required class="form-control" type="number" name="quantity[]" id="">
                                </div>
                            </div>


                        </div>
                        <div class="d-inline-flex flex-row-reverse mb-3 ">
                            <span role="button" class="rounded-sm btn btn-primary  shadow p-1 text-sm" title="Add new location" onclick="addStoreLocation()"><i class="fas fa-plus-circle"></i> Add Inventory Location</span>
                            <span role="button" class="rounded-sm btn btn-danger mx-1  shadow p-1 text-sm" id="remove-store-btn" style="display:none" onclick="removeLastStoreLocation()"><i class="fas fa-times-circle"></i> Remove Inventory Location</span>
                        </div>
                        <!-- <div id = "stores">
                    <?php foreach ($stores as $key => $store) : ?>
                        <div>
                            <input class="" type="checkbox" name="stores_inventory[]" value="<?php echo $store->id; ?>" 
                                id="store_<?php echo $store->id; ?>" onchange="toggleStoreLocationsVisibility(this, <?php echo $store->id; ?>)">
                            <label id="store_<?php echo $store->id; ?>"><?php echo $store->name; ?></label>
                            <div id='<?php echo "store_{$store->id}_locations"; ?>' style="display:none">
                                <?php foreach ($store->locations as $location) : ?>
                                    <div class="form-group"  >
                                        <label id="location_<?php echo $location->id; ?>"><?php echo $location->name; ?></label>
                                        <input class="form-control" type="number" placeholder="Quantity" name='<?php echo "store_{$store->id}_location[$location->id]"; ?>' value="0"  id="">
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    </div> -->
                    </fieldset>




                    <div class="form-group col-md-5 col-sm-12 ">
                        <label for="Manifest">Manifest </label>
                        <input type="text" class="form-control data-input" id="form_manifest_id" name="manifest_id" value="<?php echo set_value('manifest_id'); ?>" onkeypress="return (event.charCode >= 48 && event.charCode <= 57) || (event.charCode == 45)" />
                    </div>

                    <div class="form-group  col-md-5 col-sm-12">
                        <label for="Weight">Weight (lbs) <span class="text-danger">*</span></label>
                        <input type="text" class="form-control data-input" id="form_weight" name="weight" value="<?php echo set_value('weight'); ?>" onkeypress="return mkd_is_number(event,this)" />
                    </div>
                    <div class="form-group  col-md-5 col-sm-12">
                        <label for="Length">Length <span class="text-danger">*</span></label>
                        <input type="text" class="form-control data-input" id="form_length" name="length" value="<?php echo set_value('length'); ?>" onkeypress="return mkd_is_number(event,this)" />
                    </div>
                    <div class="form-group  col-md-5 col-sm-12">
                        <label for="Height">Height <span class="text-danger">*</span></label>
                        <input type="text" class="form-control data-input" id="form_height" name="height" value="<?php echo set_value('height'); ?>" onkeypress="return mkd_is_number(event,this)" />
                    </div>
                    <div class="form-group  col-md-5 col-sm-12">
                        <label for="Width">Width <span class="text-danger">*</span></label>
                        <input type="text" class="form-control data-input" id="form_width" name="width" value="<?php echo set_value('width'); ?>" onkeypress="return mkd_is_number(event,this)" />
                    </div>



                    <div class="form-group  col-md-5 col-sm-12">
                        <label for="Cost Price">Cost Price </label>
                        <input type="text" class="form-control data-input" id="form_cost_price" name="cost_price" value="<?php echo set_value('cost_price'); ?>" onkeypress="return mkd_is_number(event,this)" />
                    </div>



                    <div class="form-group  col-md-5 col-sm-12">
                        <label for="Selling Price">Selling Price </label>
                        <input type="text" class="form-control data-input" id="form_selling_price" name="selling_price" value="<?php echo set_value('selling_price'); ?>" onkeypress="return mkd_is_number(event,this)" />
                    </div>





                    <div class="form-group col-md-5 col-sm-12 ">
                        <label for="Pin Item">Pin Item </label>
                        <select id="form_pin_item_top" name="pin_item_top" class="form-control data-input">
                            <?php foreach ($view_model->pin_item_top_mapping() as $key => $value) {
                                echo "<option value='{$key}'> {$value} </option>";
                            } ?>
                        </select>
                    </div>


                    <div class="form-group col-md-5 col-sm-12 ">
                        <label for="Can Ship">Can Ship </label>
                        <select id="form_can_ship" name="can_ship" class="form-control data-input">
                            <?php foreach ($view_model->can_ship_mapping() as $key => $value) {
                                echo "<option value='{$key}'> {$value} </option>";
                            } ?>
                        </select>
                    </div>


                    <div class="form-group col-md-5 col-sm-12 ">
                        <label for="form_free_ship">Free Shipping </label>
                        <select id="form_free_ship" name="free_ship" class="form-control data-input">
                            <?php foreach ($view_model->free_ship_mapping() as $key => $value) {
                                echo "<option value='{$key}'> {$value} </option>";
                            } ?>
                        </select>
                    </div>


                    <div class="form-group col-md-5 col-sm-12">
                        <label for="Image" style="display: block;">Feature Image </label>

                        <span class="img-delete-close " style="display:none"><i class="fa fa-trash img-wrapper-delete-close"></i></span>
                        <img class="edit-preview-image" id="output_feature_image" style="max-height:100px" />
                        <div class="btn uppload-button image_id_uppload_library btn-primary btn-sm  " data-image-url="feature_image" data-image-id="feature_image_id" data-image-preview="output_feature_image" data-view-width="250" data-view-height="250" data-boundary-width="500" data-boundary-height="500">Choose Image</div>
                        <input type="hidden" id="feature_image" data-srcid="output_feature_image" class="check_change_event" name="feature_image" value="" />
                        <input type="hidden" id="feature_image_id" name="feature_image_id" value="" />

                        <button type="button" data-preview="output_feature_image" data-url="feature_image" data-id="feature_image_id" class="btn btn-primary btn-sm add-image-form-portal create-image-portal-modal">+</button>
                        <span id="feature_image_complete" style="display: block;"></span>

                    </div>


                    <div class="form-group col-md-12 col-sm-12 ">
                        <div class="mkd-upload-form-btn-wrapper ">
                            <label for="Barcode Image">Gallery Images</label>
                        </div>
                        <div class="row add_images_gallery"></div>
                    </div>

                    <div class="form-group col-md-5 col-sm-12 ">
                        <div class="mkd-upload-form-btn-wrapper gallery_image_add_inputs">
                            <button class="mkd-upload-btn btn btn-primary d-block">Upload Image(s)</button>
                            <input type="file" name="barcode_image_upload" multiple id="barcode_image_upload" onchange="manaknightMultipleImageUploader(event, 'manaknight_multiple_image')" accept=".jpg,.jpeg,.png" />

                            <input type="hidden" id="manaknight_multiple_image_1" name="gallery_image[]" />
                            <input type="hidden" id="manaknight_multiple_image_id_1" name="gallery_image_id[]" />
                        </div>
                    </div>



                    <div class="form-group col-md-12 col-sm-12">
                        <div class="card" style="background-color: #fff; background-clip: border-box; border: 1px solid rgba(0,0,0,.125) !important; border-radius: .25rem;">
                            <div class="card-header" style="text-align: end;"> <button class="btn btn-primary add_more_link" type="button">Add More</button> </div>

                            <div class="card-body card-body-add-row">

                                <div class="row thumbnail_video_row">
                                    <div class="form-group col-md-12 col-sm-12">
                                        <label class="label-full" for="Video URL 1">
                                            <span class="label-text-is">Video URL 1 </span>


                                            <span class="delete-full " style="display: none;"><i class="fa fa-trash img-wrapper-delete-close"></i></span>
                                        </label>
                                        <input type="text" class="form-control validate_url_field width-75 data-input" name="video_url[]" value="" />
                                    </div>



                                    <div class="form-group col-md-12 col-sm-12">
                                        <label for="Youtube Thumbnail 1">Choose Thumbnail 1</label>


                                        <img id="output_youtube_thumbnail_1 edit-preview-image " class="output_youtube_thumbnail_1  pb-2" style="max-height:100px" />


                                        <div style="margin: 0px;" class="btn btn-primary btn-sm mkd-choose-image-thumbnail" data-image-url="youtube_thumbnail_1" data-image-id="youtube_thumbnail_1_id" data-image-preview="output_youtube_thumbnail_1" data-view-width="250" data-view-height="250" data-boundary-width="500" data-boundary-height="500">Browse</div>


                                        <input type="hidden" class="youtube_thumbnail_1 validate_img_field" id="youtube_thumbnail_1" name="youtube_thumbnail_1[]" value="" />

                                        <input type="hidden" class="youtube_thumbnail_1_id" id="youtube_thumbnail_1_id" name="youtube_thumbnail_1_id[]" value="" />


                                        <input type="file" style="display:none" class="file_to_upload_class" onchange="manaknightThumbnailImage(event, 'manaknight_multiple_image')" accept=".jpg,.jpeg,.png" />
                                    </div>
                                </div>


                            </div>
                        </div>
                    </div>



                    <div class="form-group col-md-12 col-sm-12">
                        <label for="Inventory Note">Description </label>
                        <textarea id='subeditor_inventory_note' name='inventory_note' class='form-control subeditor_inventory_note data-input' rows='5'><?php echo set_value('inventory_note'); ?></textarea>
                    </div>

                    <div class="form-group col-md-5 col-sm-12">
                        <label for="Admin Inventory Note">Admin Inventory Note </label>
                        <textarea id='form_admin_inventory_note' name='admin_inventory_note' class='form-control data-input' rows='5'><?php echo set_value('admin_inventory_note'); ?></textarea>
                    </div>

                    <div class="form-group col-md-5 col-sm-12 ">
                        <label for="Status">Status </label>
                        <select id="form_status" name="status" class="form-control data-input">
                            <?php foreach ($view_model->status_mapping() as $key => $value) {
                                echo "<option value='{$key}'> {$value} </option>";
                            } ?>
                        </select>
                    </div>


                    <div class="form-group  col-md-5 col-sm-12">
                        <input type="submit" class="btn btn-primary text-white mr-4 my-4 validate-videos" value="Submit">
                    </div>
                    </form>
                </div>
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
$this->load->view('Guest/ImagePortalModal');
?>


<link href="https://cdn.jsdelivr.net/npm/suneditor@latest/dist/css/suneditor.min.css" rel="stylesheet">
<!-- <link href="https://cdn.jsdelivr.net/npm/suneditor@latest/assets/css/suneditor.css" rel="stylesheet"> -->
<!-- <link href="https://cdn.jsdelivr.net/npm/suneditor@latest/assets/css/suneditor-contents.css" rel="stylesheet"> -->
<script src="https://cdn.jsdelivr.net/npm/suneditor@latest/dist/suneditor.min.js"></script>
<!-- languages (Basic Language: English/en) -->
<script src="https://cdn.jsdelivr.net/npm/suneditor@latest/src/lang/ko.js"></script>


<script type="text/javascript" src="<?php echo base_url() ?>assets/js/image-portal.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>assets/js/multiple-gallery-image-upload.js"></script>



<?php $this->load->view('Guest/ImageThumbnailModel'); ?>

<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/video_image_script.js"></script>
<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function() {
        number_counter = 2;
        $(document).on('click', '.add_more_link', function() {
            var row_th = $('.thumbnail_video_row').eq(0).clone();

            row_th.find('.label-text-is').eq(0).text('Video URL ' + number_counter);
            row_th.find('label').eq(1).text('Choose Thumbnail ' + number_counter);
            row_th.find('.delete-full').show();
            row_th.find('.validate_url_field').val('');
            row_th.find('.validate_img_field').val("");
            row_th.find('.output_youtube_thumbnail_1').attr('src', "");

            number_counter++;
            $('.card-body-add-row').append(row_th);

        });

        $(document).on('click', '.delete-full', function() {
            $(this).parent().parent().parent().remove();

            number_counter = 1
            $('.thumbnail_video_row').each(function(index, obj) {
                var row_th = $(this);
                row_th.find('.label-text-is').eq(0).text('Video URL ' + number_counter);
                row_th.find('label').eq(1).text('Choose Thumbnail ' + number_counter);
                number_counter++;
            });
        });

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

        // Shipping Status /////////////////////////////////////////////////////////////////////
        // can_ship_mapping => 1 : Delivery or pickup, 2: pickup only
        // can_ship_approval_mapping => 1 : Yes, 2: No
        updateCanShip();
        let weight_input = document.querySelector("#form_weight");
        weight_input.addEventListener('input', updateCanShip);

        // ///////////////////////////////////////////////////////////////////////////////////

    }, false)

    document.addEventListener('DOMContentLoaded', function() {
        $(function() {
            $("body").on("change", ".check_change_event", function() {
                var current_src_id = $(this).attr('data-srcid');
                $('#' + current_src_id).attr('src', $(this).val());

                $(this).parent().find(".img-delete-close").show();
            });
        });
    }, false)

    function updateCanShip() {
        let weight = document.querySelector("#form_weight").value;
        let can_ship = document.querySelector("#form_can_ship");
        let can_ship_approval = document.querySelector("#form_can_ship_approval");

        if (weight > 75) {
            can_ship.value = 2;
            can_ship.disabled = true;
            can_ship_approval.disabled = false
        } else {
            can_ship_approval.value = 2
            can_ship_approval.disabled = true
            can_ship.disabled = false;
        }
    }

    let storeState = {
        locations: []
    };

    function newPhysicalLocationToggle(el) {
        const id = makeid(8);
        el.parentElement.parentElement.parentElement.querySelector("[role='store']").id = id;
        document.querySelector('#location_trigger').value = id;
        document.querySelector("#new_physical_location_store").value = '';
        document.querySelector("#new_physical_location").value = '';
        $('#newPhysicalLocationModal').modal('show');
    }

    function createInventoryLocation(el) {
        const store = document.querySelector("#new_physical_location_store").value;
        const physical_location = document.querySelector("#new_physical_location").value;
        const new_physical_location_trigger = document.querySelector("#location_trigger").value;
        el.innerHTML = 'Adding Location...';

        $.ajax({
            type: "POST",
            url: "/v1/api/physical_location/create",
            timeout: 15000,
            dataType: "JSON",
            data: {
                store: store,
                physical_location: physical_location
            },
            success: function(response) {
                document.querySelector('#store-inventories').setAttribute('encoded-locations', response.encoded_locations);
                $(`#${new_physical_location_trigger}`).trigger('change');
                el.innerHTML = 'Add';
                $('#newPhysicalLocationModal').modal('hide');
            },
            error: function(response) {
                document.querySelector('#store-inventories').setAttribute('encoded-locations', response.responseJSON.encoded_locations);
                $(`#${new_physical_location_trigger}`).trigger('change');
                el.innerHTML = 'Add';
                $('#newPhysicalLocationModal').modal('hide');
            }
        });
    }

    function makeid(length) {
        let result = '';
        let characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        let charactersLength = characters.length;
        for (let i = 0; i < length; i++) {
            result += characters.charAt(Math.floor(Math.random() *
                charactersLength));
        }
        return result;
    }

    function listStoreLocations(el) {
        const locations = getStoreLocations(el.value);
        let options_template = '';
        locations.forEach(location => options_template += `<option value="${location.id}">${location.name}</option>`)
        let locationElement = el.parentElement.parentElement.querySelector("select[role='physical-location']");
        locationElement.innerHTML = options_template;
    }

    function getStoreLocations(store_id) {
        console.log(storeState.locations);
        let physicalLocations = JSON.parse(atob(document.querySelector('#store-inventories').getAttribute('encoded-locations')));
        return physicalLocations = physicalLocations.filter(location => location.store_id === store_id && storeState.locations.indexOf(location.id) === -1);
    }

    function updateLocationState(el) {
        // console.log(el);
    }

    function addStoreLocation() {
        updateLocations();

        $(`#store-inventories`).append(`
        <div class="store shadow-sm my-2 p-2" style="">
        ${Store()}
        ${PhysicalLocation()}
        ${Quantity()}
        </div>
        `);

        $(".location-dropdown").select2();
        updateRemoveStoreBtn();
    }

    function removeLastStoreLocation() {
        document.querySelectorAll('.store')[document.querySelectorAll('.store').length - 1].remove();
        updateRemoveStoreBtn();
        updateLocations();
    }

    function updateLocations() {
        let locations = document.querySelectorAll("[name='locations[]']");
        storeState.locations = [];
        for (let i = 0; i < locations.length; i++) {
            storeState.locations.push(locations[i].value);
        }
    }

    function updateRemoveStoreBtn() {
        if (document.querySelectorAll('.store').length > 1) {
            return showRemoveStore();
        }
        hideRemoveStore();
    }

    function showRemoveStore() {
        document.querySelector('#remove-store-btn').style.display = 'inline';
    }

    function hideRemoveStore() {
        document.querySelector('#remove-store-btn').style.display = 'none';
    }


    function Store() {
        return `
            <div class="form-group">
                <label for="">Store <span class="text-danger">*</span></label>
                <select required name="stores[]" id="" role='store' class="form-control" onchange="listStoreLocations(this)">
                <option value=""></option>
                    <?php foreach ($stores as $store) : ?>
                    <option value="<?php echo $store->id ?>"> <?php echo $store->name; ?></option>
                    <?php endforeach; ?>
                </select>
            
            </div>
        `;
    }

    function PhysicalLocation() {
        return `
            <div class="form-group">
                <label for="">Physical Location <span class="text-danger">*</span></label>
                <div class="d-flex">
                    <select required name="locations[]" class="form-control location-dropdown" role="physical-location">
                        <option value=""></option>
                    </select>

                    <?php if ($this->session->userdata('role') == 2) : ?> 
                        <button type="button" class="btn btn-primary"  onclick="newPhysicalLocationToggle(this)" >
                        <i class="fas fa-plus-circle"></i>
                        </button>
                    <?php endif ?>
                </div>
            </div>
        `;
    }

    function Quantity() {
        return `<div class="form-group">
                    <label for="">Quantity <span class="text-danger">*</span></label>
                    <input required class="form-control" type="number" name="quantity[]" id="">
                </div>`;
    }
</script>