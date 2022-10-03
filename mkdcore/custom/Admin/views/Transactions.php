<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2020*/
$QUERY_STRING = isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : '';
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
        <div class="card" id="transactions_filter_listing">
            <div class="card-body">
              <h5 class="primaryHeading2 text-md-left">
                    <?php echo $view_model->get_heading();?> Search
              </h5>
                <?= form_open('', ['method' => 'get']) ?>
                    <div class="row">
                    	<div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12">
							<div class="form-group">
								<label for="Transaction Type">Transaction Type </label>
                                <select name="transaction_type" id="transaction_type" class="form-control">
                                    <option value=""></option>
                                    <?php foreach ($view_model->transaction_type_mapping() as $key => $value): ?>
                                        <?php $selected = $transaction_type == $key ? 'selected' : '' ?>
                                    <option <?php echo $selected; ?> value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                    <?php endforeach; ?>
                                </select>
							</div>
						</div>
                    	<div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12">
							<div class="form-group">
								<label for="From Date">From </label>
								<input type="date" class="form-control" id="from_date" name="from_date" value="<?php echo $from_date;?>"/>
							</div>
						</div>
                    	<div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12">
							<div class="form-group">
								<label for="To Date">To </label>
								<input type="date" class="form-control" id="to_date" name="to_date" value="<?php echo $to_date;?>"/>
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

  <span class="add-part d-flex justify-content-md-end  "><a class="btn btn-info btn-sm ml-2" href="<?php echo base_url().'admin/transactions/to_csv?' . $QUERY_STRING; ?>"><i class="fas fa-file-download" style="color:white;"></i></a></span>
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
							echo "<td>{$data->pos_order_id}</td>";
							echo "<td class='table-td-with-date'>" . date('F d Y', strtotime($data->transaction_date)) . "</td>";
							echo "<td>{$data->customer_id}</td>";
							echo "<td>" . ucfirst($view_model->payment_type_mapping()[$data->payment_type]) ."</td>";
							echo "<td>$" . number_format($data->tax,2) . "</td>";
                            echo "<td>$" . number_format($data->discount,2) . "</td>";
                            echo "<td>$" . number_format($data->subtotal,2) . "</td>";
                            echo "<td>$" . number_format($data->total,2) . "</td>"; 
							echo '<td>';
                            if($this->session->userdata('role') == 2) {
                                echo ' <a class="btn btn-link  link-underline text-underline btn-sm" target="_blank" href="/admin/transactions/view/' . $data->id . '">View</a>';
                            }elseif($this->session->userdata('role') == 4) { 
                                echo ' <a class="btn btn-link  link-underline text-underline btn-sm" target="_blank" href="/manager/transactions/view/' . $data->id . '">View</a>';
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