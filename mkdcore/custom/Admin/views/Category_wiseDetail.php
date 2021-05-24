<?php
defined('BASEPATH') OR exit('No direct script access allowed');
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
        <div class="card" id="category_wise_filter_listing">
            <div class="card-body">
              <h5 class="primaryHeading2 text-md-left">
                <?php echo $heading; ?>  Sale Report Search
              </h5>
                <?= form_open('/admin/category_wise/view/'. $category_id, ['method' => 'get']) ?>
                    <div class="row">
                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12">
							<div class="form-group">
								<label for="search_name">Name </label>
								<input type="text" class="form-control" id="search_name" name="search_name" value="<?php echo $search_name;?>"/>
							</div>
						</div>

                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12">
							<div class="form-group">
								<label for="sku">SKU </label>
								<input type="text" class="form-control" id="search_sku" name="search_sku" value="<?php echo $search_sku;?>"/>
							</div>
						</div>


                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12">
							<div class="form-group">
								<label for="order_date">Date </label>
								<input type="date" class="form-control" id="order_date" name="order_date" value="<?php echo $order_date;?>"/>
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
    <?php echo $heading; ?> Sale Report  (<?php echo $view_model->get_total_rows();?> results found)
  <span class="d-none"></span>
</h5>

  <section class="table-placeholder bg-white mb-5 p-3 pl-4 pr-4 pt-4" style='height:auto;'>
    <div class="row">
        <div class="col p-2">
            <div class="float-right mr-4"></div>
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="table-responsive">
    <table class="table br w-100">
        <thead class='thead-light'>
            <tr>
                <th>ID</th>
                <th>Item Name</th>
                <th>SKU</th>
                <th>Quantity</th>
                <th>Total Sale</th>
                <th>Credit</th>
                <th>Cash</th> 
            </tr>
        </thead>
        <tbody class="tbody-light">
            <?php foreach ($products_list as $data) { ?>
                <?php
                    echo '<tr>';
                        echo "<td>{$data->id}</td>";
                        echo "<td>{$data->product_name}</td>";  
                        echo "<td>{$data->sku}</td>";  
                        echo "<td>{$data->total_qty}</td>";
                        echo "<td>" . number_format($data->total_sale,2) . "</td>";
                        echo "<td>" . number_format($data->total_credit,2) . "</td>";
                        echo "<td>" . number_format($data->total_cash,2) . "</td>"; 
                    echo '</tr>';
                ?>
            <?php } ?>
        </tbody>
    </table>
     <p class="pagination_custom"><?php // echo $view_model->get_links(); ?></p>
    </div>
   </section>
</div>
<?php
if ($layout_clean_mode) {
    echo '<style>#content{padding:0px !important;}</style>';
    echo '<style>#tab-content{padding:0px !important; margin:0px !important;}</style>';
}
?>