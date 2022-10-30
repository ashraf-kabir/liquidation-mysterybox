<?php
defined('BASEPATH') or exit('No direct script access allowed');
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
if ($layout_clean_mode) {
    echo '<style>#content{padding:0px !important;}</style>';
}
?>
<div class="tab-content mx-4" id="nav-tabContent">
    <!-- Bread Crumb -->
    <div aria-label="breadcrumb">
        <ol class="breadcrumb pl-0 mb-4 bg-background d-flex justify-content-center justify-content-md-start" style="background-color: inherit;">
            <li class="breadcrumb-item active" aria-current="page">
                <a href="/admin/orders/0" class="breadcrumb-link"><?php echo $view_model->get_heading(); ?></a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
                View
            </li>
        </ol>
    </div>
    <?php
    // echo '<pre>';
    // var_dump($orders_details);
    // echo '</pre>';
    ?>
    <div class="row mb-5">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="card pb-5" style='border-bottom:1px solid #ccc;'>
                <div class="card-body">
                    <h5 class="primaryHeading2 text-md-left">
                        <?php echo $view_model->get_heading(); ?> Details
                    </h5>

                    <div class='row mb-4' style=" clear: both;">
                        <div class='col'>
                            ID
                        </div>
                        <div class='col'>
                            <?php echo $inventory_details->id; ?>
                        </div>
                    </div>

                    <div class='row mb-4'>
                        <div class='col'>
                            Product Name
                        </div>
                        <div class='col'>
                            <?php echo $inventory_details->product_name; ?>
                        </div>
                    </div>

                    <div class='row mb-4'>
                        <div class='col'>
                            SKU
                        </div>
                        <div class='col'>
                            <?php echo $inventory_details->sku; ?>
                        </div>
                    </div>

                    <div class='row mb-4'>
                        <div class='col'>
                            Category
                        </div>
                        <div class='col'>
                            <?php echo $inventory_details->category_id; ?>
                        </div>
                    </div>

                    <div class='row mb-4'>
                        <div class='col'>
                            Manifest
                        </div>
                        <div class='col'>
                            <?php echo $inventory_details->manifest_id; ?>
                        </div>
                    </div>

                    <div class='row mb-4'>
                        <div class='col'>
                            Inventory Location
                        </div>
                        <div class='col'>
                            <?php echo $inventory_details->physical_location; ?>
                        </div>
                    </div>

                    <div class='row mb-4'>
                        <div class='col'>
                            Inventory Location Description
                        </div>
                        <div class='col'>
                            <?php echo $inventory_details->location_description; ?>
                        </div>
                    </div>

                    <div class='row mb-4'>
                        <div class='col'>
                            Weight
                        </div>
                        <div class='col'>
                            <?php echo $inventory_details->weight; ?>
                        </div>
                    </div>

                    <div class='row mb-4'>
                        <div class='col'>
                            Length
                        </div>
                        <div class='col'>
                            <?php echo $inventory_details->length; ?>
                        </div>
                    </div>

                    <div class='row mb-4'>
                        <div class='col'>
                            Height
                        </div>
                        <div class='col'>
                            <?php echo $inventory_details->height; ?>
                        </div>
                    </div>

                    <div class='row mb-4'>
                        <div class='col'>
                            Width
                        </div>
                        <div class='col'>
                            <?php echo $inventory_details->width; ?>
                        </div>
                    </div>

                    <?php if (!empty($inventory_details->feature_image)) : ?>
                        <div class='row mb-4'>
                            <div class='col'>
                                <span class='d-block'>Feature Image</span>
                                <img class="img-fluid d-block mb-3 mt-3 view-image" style='height: 120px; width: 300px; object-fit: cover;' src="<?php echo $inventory_details->feature_image; ?>" />
                            </div>
                        </div>
                    <?php endif ?>



                    <div class='row mb-4'>
                        <div class='col'>
                            Selling Price
                        </div>
                        <div class='col'>
                            <?php echo $inventory_details->selling_price; ?>
                        </div>
                    </div>

                    <div class='row mb-4'>
                        <div class='col'>
                            Quantity
                        </div>
                        <div class='col'>
                            <?php echo $inventory_details->quantity; ?>
                        </div>
                    </div>

                    <div class='row mb-4'>
                        <div class='col'>
                            Description
                        </div>
                        <div class='col'>
                            <?php echo $inventory_details->inventory_note; ?>
                        </div>
                    </div>

                    <div class='row mb-4'>
                        <div class='col'>
                            Cost Price
                        </div>
                        <div class='col'>
                            <?php echo $inventory_details->cost_price; ?>
                        </div>
                    </div>

                    <div class='row mb-4'>
                        <div class='col'>
                            Admin Inventory Note
                        </div>
                        <div class='col'>
                            <?php echo $inventory_details->admin_inventory_note; ?>
                        </div>
                    </div>

                    <div class='row mb-4'>
                        <div class='col'>
                            Status
                        </div>
                        <div class='col'> <?php echo $status_mapping[$inventory_details->status]; ?> </div>
                    </div>

                    <div class='row mb-4'>
                        <div class='col'>
                            <!-- Delivery Type -->
                        </div>
                        <!-- <div class='col'>
						<?php //echo $view_model->checkout_type_mapping()[$view_model->get_checkout_type()];
                        ?>
					</div> -->
                    </div>

                    <div class='row'>
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-bordered ">
                                    <thead class="thead-light">
                                        <tr>
                                            <th style="width:15%">Item Name</th>
                                            <th style="width:10%">Quantity</th>
                                            <th style="width:10%">Rate</th>
                                            <th style="width:10%">Amount</th>
                                            <th style="width:10%">Tax</th>
                                            <th style="width:10%">Shipping Cost</th>
                                            <th style="width:10%">Total</th>
                                            <th style="width:10%">Delivery Type</th>
                                            <th style="width:10%">Shipping Service/Store</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php foreach ($orders_details as $key => $detail) {
                                            $total = $detail->amount + $detail->tax + $detail->shipping_cost;
                                        ?>
                                            <tr>
                                                <td><?php echo  $detail->product_name; ?> </td>
                                                <td><?php echo  $detail->quantity; ?></td>
                                                <td>$<?php echo  number_format($detail->product_unit_price, 2); ?></td>
                                                <td>$<?php echo  number_format($detail->amount, 2); ?></td>
                                                <td>$<?php echo  number_format($detail->tax, 2); ?></td>
                                                <td>$<?php echo  number_format($detail->shipping_cost, 2); ?></td>
                                                <td>$<?php echo  number_format($total, 2); ?></td>
                                                <td><?php echo $detail->is_pickup == "0" ? 'Delivery' : 'Pick up'; ?></td>
                                                <?php if (empty($detail->store_id)) : ?>
                                                    <td><?php echo $detail->shipping_cost_name; ?></td>
                                                <?php else : ?>
                                                    <?php foreach ($stores as $key => $store) {
                                                        if ($store->id == $detail->store_id) {
                                                            echo "<td> {$store->name} </td>";
                                                        }
                                                    } ?>

                                                <?php endif; ?>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>