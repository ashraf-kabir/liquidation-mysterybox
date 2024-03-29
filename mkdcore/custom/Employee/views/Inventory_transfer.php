<?php
defined('BASEPATH') or exit('No direct script access allowed');
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2020*/

?>
<div class="tab-content mx-4" id="nav-tabContent">
    <br>
    <div class="clear"></div>
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

    <section>
        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="card" id="inventory_transfer_filter_listing">
                    <div class="card-body">
                        <h5 class="primaryHeading2 text-md-left">
                            <?php echo $view_model->get_heading(); ?> Search
                        </h5>
                        <?= form_open('/admin/inventory_transfer/0', ['method' => 'get']) ?>
                        <div class="row">
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12">
                                <div class="form-group">
                                    <label for="SKU">SKU </label>
                                    <input type="text" class="form-control" id="sku" name="sku" value="<?php echo $this->_data['view_model']->get_sku(); ?>" />
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12">
                                <div class="form-group">
                                    <label for="Status">Status </label>
                                    <select name="status" class="form-control">
                                        <option value="">All</option>
                                        <?php foreach ($view_model->status_mapping() as $key => $value) {
                                            echo "<option value='{$key}' " . (($view_model->get_status() == $key && $view_model->get_status() != '') ? 'selected' : '') . "> {$value} </option>";
                                        } ?>
                                    </select>
                                </div>
                            </div>

                            <div style="width:100%;height:10px;display:block;float:none;"></div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12">
                                <div class="form-group">
                                    <input type="submit" name="submit" class="btn btn-primary" value="Search">
                                </div>
                            </div>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
    </section>

    <h5 class="primaryHeading2 d-flex justify-content-between mt-2 my-4">
        <?php echo $view_model->get_heading(); ?> (<?php echo $view_model->get_total_rows(); ?> results found)
        <span class="add-part d-flex justify-content-md-end"><a class="btn btn-primary btn-sm" target="_blank" href="/employee/transfer/transfer_inventory"><i class="fas fa-plus-circle"></i></a></span>
    </h5>

    <section class="table-placeholder bg-white mb-5 p-3 pl-4 pr-4 pt-4" style='height:auto;'>
        <div class="row">
            <div class="col p-2">
                <div class="float-right mr-4"></div>
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="table-responsive" id="table-wrapper" encoded-locations="<?php echo $encoded_physical_locations; ?>">
            <table class="table br w-100">
                <thead class='thead-light'>
                    <?php
                    $order_by = $view_model->get_order_by();
                    $direction = $view_model->get_sort();
                    $model_base_url = $view_model->get_sort_base_url();
                    $field_column = $view_model->get_field_column();
                    $clean_mode = $view_model->get_format_layout();
                    $format_mode = '';
                    if ($clean_mode) {
                        $format_mode = '&layout_clean_mode=1';
                    }
                    foreach ($view_model->get_column() as $key => $data) {
                        $data_field = $field_column[$key];
                        if (strlen($order_by) < 1 || $data_field == '') {
                            echo "<th scope='col' class='paragraphText text-left'>{$data}</th>";
                        } else {
                            if ($order_by === $data_field) {
                                if ($direction == 'ASC') {
                                    echo "<th scope='col' class='paragraphText text-left'><a href='{$model_base_url}?order_by={$data_field}{$format_mode}&direction=DESC'>{$data} <i class='fas fa-sort-up' style='vertical-align: -0.35em;'></i></a></th>";
                                } else {
                                    echo "<th scope='col' class='paragraphText text-left' ><a href='{$model_base_url}?order_by={$data_field}{$format_mode}&direction=ASC'>{$data} <i class='fas fa-sort-down' style='margin-bottom:3px;'></i></a></th>";
                                }
                            } else {
                                echo "<th  scope='col' class='paragraphText text-left'><a href='{$model_base_url}?order_by={$data_field}{$format_mode}&direction=ASC'>{$data} <i class='fas fa-sort-down'  style='margin-bottom:3px;color:#e2e2e2;'></i></a></th>";
                            }
                        }
                    } ?>
                </thead>
                <tbody class="tbody-light">
                    <?php foreach ($view_model->get_list() as $data) { ?>
                        <?php
                        $store = isset($store_map[$data->from_store]) ? $store_map[$data->from_store] : 'N/A';
                        $location = isset($location_map[$data->from_location]) ? $location_map[$data->from_location] : 'N/A';
                        echo '<tr>';
                        echo "<td>{$data->id}</td>";
                        echo "<td>{$data->product_name}</td>";
                        echo "<td>{$data->sku}</td>";
                        echo "<td>{$store}</td>";
                        echo "<td>{$location}</td>";
                        echo "<td>{$data->quantity}</td>";
                        echo "<td>{$store_map[$data->to_store]}</td>";
                        echo "<td>" . ucfirst($view_model->status_mapping()[$data->status]) . "</td>";
                        echo '<td>';
                        if ($data->status != 2  /* Completed */) { //Show when status is not completed
                            // echo '<button class="btn btn-link  link-underline text-underline btn-sm text-success" onclick="confirmAndAccept(' . $data->to_store . ')" target="" href="/admin/inventory_transfer/accept/' . $data->id . '">Accept Request</button>';
                        }
                        echo ' <a class="btn btn-link  link-underline text-underline btn-sm" target="_blank" href="/employee/inventory_transfer/view/' . $data->id . '">View</a>';
                        // echo ' <a class="btn btn-link  link-underline text-underline text-danger btn-sm" target="_blank" href="/admin/inventory_transfer/delete/' . $data->id . '">Remove</a>';
                        echo '</td>';
                        echo '</tr>';
                        ?>
                    <?php } ?>
                </tbody>
            </table>
            <p class="pagination_custom"><?php echo $view_model->get_links(); ?></p>
        </div>
    </section>
</div>


<!-- Modal -->
<div class="modal fade" id="acceptTransferModal" tabindex="-1" role="dialog" aria-labelledby="acceptTransferModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="acceptTransferModalLabel"> Scan or select item for confirmation </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="acceptTransferModalForm" action="" method="get">
                <div class="modal-body">
                    <div class="form-group">
                        <label for=""> Scan or select item</label>
                        <input required type="text" name="sku_c" id="sku_c" class="form-control">
                        <label for=""> Select Items</label>
                        <select name="" id="select-items" class="form-control" onchange="populateSku()">
                            <option value=""></option>
                            <?php foreach ($inventory_items_list as $item) : ?>
                                <option value="<?php echo $item->sku ?>"> <?php echo $item->sku . " - " . $item->product_name ?></option>
                            <?php endforeach; ?>
                        </select>
                        <label for=""> Store Physical Location (Destination)</label>
                        <select name="physical_location" id="to_location" class="form-control">
                            <option value=""></option>

                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Proceed</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php
if ($layout_clean_mode) {
    echo '<style>#content{padding:0px !important;}</style>';
    echo '<style>#tab-content{padding:0px !important; margin:0px !important;}</style>';
}
?>


<script>
    function confirmAndAccept(to_store_id) {
        let target = event.target.getAttribute('href');
        // console.log(target);
        let form = document.querySelector('#acceptTransferModalForm');
        form.setAttribute('action', target);

        let locations = JSON.parse(atob(document.querySelector('#table-wrapper').getAttribute('encoded-locations')));
        console.log(locations);
        console.log(to_store_id);
        let options_template = locations.filter((location) => location.store_id == to_store_id)
            .map((location) => {
                return `<option value="${location.id}"> ${location.name} </option>`
            });
        console.log(options_template);
        $('#to_location').html(options_template);
        $('#acceptTransferModal').modal('show')
    }

    function populateSku() {
        let itemSku = document.querySelector('#select-items').value;
        document.querySelector('#sku_c').value = itemSku;

    }
</script>