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
        <div class="card" id="Orders_filter_listing">
            <div class="card-body">
              <h5 class="primaryHeading2 text-md-left">
                    <?php echo $view_model->get_heading();?> Search
              </h5>
                <?= form_open('/admin/orders/0', ['method' => 'get']) ?>
                    <div class="row">
                    						<div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12">
							<div class="form-group">
								<label for="Billing Name">Billing Name </label>
								<input type="text" class="form-control" id="billing_name" name="billing_name" value="<?php echo $this->_data['view_model']->get_billing_name();?>"/>
							</div>
						</div>
						<div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12">
							<div class="form-group">
								<label for="Picked">Picked </label>
								<select name="is_picked" class="form-control">
									<option value="">All</option>
									<?php foreach ($view_model->is_picked_mapping() as $key => $value) {
										echo "<option value='{$key}' " . (($view_model->get_is_picked() == $key && $view_model->get_is_picked() != '') ? 'selected' : '') . "> {$value} </option>";
									}?>
								</select>
							</div>
						</div>
						<div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12">
							<div class="form-group">
								<label for="Shipped">Shipped </label>
								<select name="is_shipped" class="form-control">
									<option value="">All</option>
									<?php foreach ($view_model->is_shipped_mapping() as $key => $value) {
										echo "<option value='{$key}' " . (($view_model->get_is_shipped() == $key && $view_model->get_is_shipped() != '') ? 'selected' : '') . "> {$value} </option>";
									}?>
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
  <?php echo $view_model->get_heading();?>  (<?php echo $view_model->get_total_rows();?> results found)
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
            if (strlen($order_by) < 1 || $data_field == '')
            {
                echo "<th scope='col' class='paragraphText text-left'>{$data}</th>";
            }
            else
            {
                if ($order_by === $data_field)
                {
                    if ($direction == 'ASC')
                    {
                        echo "<th scope='col' class='paragraphText text-left'><a href='{$model_base_url}?order_by={$data_field}{$format_mode}&direction=DESC'>{$data} <i class='fas fa-sort-up' style='vertical-align: -0.35em;'></i></a></th>";
                    }
                    else
                    {
                        echo "<th scope='col' class='paragraphText text-left' ><a href='{$model_base_url}?order_by={$data_field}{$format_mode}&direction=ASC'>{$data} <i class='fas fa-sort-down' style='margin-bottom:3px;'></i></a></th>";
                    }
                }
                else
                {
                    echo "<th  scope='col' class='paragraphText text-left'><a href='{$model_base_url}?order_by={$data_field}{$format_mode}&direction=ASC'>{$data} <i class='fas fa-sort-down'  style='margin-bottom:3px;color:#e2e2e2;'></i></a></th>";
                }
            }
        } ?>
        </thead>
        <tbody class="tbody-light">
            <?php foreach ($view_model->get_list() as $data) { ?>
                <?php
                    echo '<tr>';
							echo "<td>{$data->id}</td>";
							echo "<td>{$data->billing_name}</td>";
							echo "<td>" . ucfirst($view_model->order_type_mapping()[$data->order_type]) ."</td>";
							echo "<td>" . ucfirst($view_model->payment_method_mapping()[$data->payment_method]) ."</td>";
							echo "<td><a class='text-primary' target='_blank' href='https://www.google.com/search?q={$data->ship_station_tracking_no}'>{$data->ship_station_tracking_no}</a></td>";
							echo "<td>" . ucfirst($view_model->is_picked_mapping()[$data->is_picked]) ."</td>";
							echo "<td>" . ucfirst($view_model->is_shipped_mapping()[$data->is_shipped]) ."</td>";
							echo "<td>$" . number_format($data->subtotal, 2) . "</td>";
							echo "<td>$" . number_format($data->discount, 2) . "</td>";
							echo "<td>$" . number_format($data->total, 2) . "</td>";
							echo '<td>';
							echo ' <a class="btn btn-link  link-underline text-underline btn-sm" target="_blank" href="/admin/orders/view/' . $data->id . '">View</a>';
                            $response = json_decode($data->intent_data);
	                        if(isset($response->transactionid) && $response->success == 1 && $data->status == 1 /*Purchased */ && $data->payment_method == 2 /* Card*/ ) {
                                echo ' <a class="btn btn-link text-danger  link-underline text-underline btn-sm" target="_blank" href="/admin/orders/refund/' . $data->id . '">Refund</a>';
                            }
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
<?php
if ($layout_clean_mode) {
    echo '<style>#content{padding:0px !important;}</style>';
    echo '<style>#tab-content{padding:0px !important; margin:0px !important;}</style>';
}
?>