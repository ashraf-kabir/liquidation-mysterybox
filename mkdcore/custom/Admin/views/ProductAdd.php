<?php
defined('BASEPATH') or exit('No direct script access allowed');
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
                <a href="/admin/product/0" class="breadcrumb-link"><?php echo $view_model->get_heading(); ?></a>
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
                        <label for="Product Name">Product Name </label>
                        <input type="text" class="form-control data-input" id="form_product_name" name="product_name" value="<?php echo set_value('product_name'); ?>" />
                    </div>

                    <div class="form-group col-md-5 col-sm-12 ">
                        <label for="Parent Category"> Category <span class="text-danger">*</span> </label>
                        <select required class="form-control data-input" id="form_category_id" name="category_id" onchange="updateSKU(this)">
                            <option value="">Select</option>
                            <?php foreach ($parent_categories as $key => $value) {
                                $child_category_tab = $value->parent_category_id == 0 || $value->parent_category_id == null ? '' : '&nbsp;&nbsp;&nbsp;&nbsp;';
                                echo "<option value='{$value->id}'> {$child_category_tab} {$value->name} </option>";
                            } ?>
                        </select>
                        <input type="hidden" name="sale_person_id" value="<?= $this->session->userdata('user_id') ?>">
                        <input type="hidden" name="locations" value="null">
                        <input type="hidden" name="is_product" value="1">
                        <input type="hidden" id="encoded_parent_categories" value="<?= $encoded_parent_categories ?>">
                    </div>
                    <?php
                    // echo '<pre>';
                    // var_dump($parent_categories);
                    // echo '</pre>';
                    ?>
                    <div class="form-group col-md-5 col-sm-12 ">
                        <label for="SKU">SKU </label>
                        <input type="text" class="form-control data-input" id="form_sku" name="sku" value="<?php echo set_value('sku'); ?>" readonly />
                    </div>

                    <!-- <div class="form-group col-md-5 col-sm-12">
                        <label for="Image">Image </label>
                        <img id="output_feature_image" style="max-height:100px" onerror=\"if (this.src !='/uploads/placeholder.jpg' ) this.src='/uploads/placeholder.jpg' ;\" />
                        <div class="btn uppload-button image_id_uppload_library btn-primary btn-sm  " data-image-url="feature_image" data-image-id="feature_image_id" data-image-preview="output_feature_image" data-view-width="250" data-view-height="250" data-boundary-width="500" data-boundary-height="500">Choose Image</div>
                        <input type="hidden" id="feature_image" name="feature_image" value="" />
                        <input type="hidden" id="feature_image_id" name="feature_image_id" value="" />
                        <span id="feature_image_complete" class="image_complete_uppload"></span>
                    </div> -->
                    <div class="form-group col-md-5 col-sm-12">
                        <label for="Inventory Note">Inventory Note </label>
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
                        <input type="submit" class="btn btn-primary text-white mr-4 my-4" value="Submit">
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

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
</script>