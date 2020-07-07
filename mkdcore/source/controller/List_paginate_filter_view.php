<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
if ($layout_clean_mode) {
    echo '<style>#content{padding:0px !important;}</style>';
}
?>
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
<div class="tab-content" id="nav-tabContent">
              <!-- Bread Crumb -->
    <div aria-label="breadcrumb">
        <ol class="breadcrumb pl-0 mb-4 bg-background d-flex justify-content-center justify-content-md-start">
            <li class="breadcrumb-item active" aria-current="page">
                <a href="/{{{portal}}}/dashboard" class="breadcrumb-link">xyzDashboard</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
                <?php echo $view_model->get_heading();?>
            </li>
        </ol>
    </div>
</div>
<h1 class="primaryHeading text-center text-md-left">
  <?php echo $view_model->get_heading();?>
</h1>
<section>
<div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="card" id="{{{name}}}_filter_listing">
            <div class="card-body">
              <h1 class="primaryHeading2 text-center text-md-left">
                    <?php echo $view_model->get_heading();?> xyzSearch
              </h1>
                <?= form_open('/{{{portal}}}{{{route}}}', ['method' => 'get']) ?>
                    <div class="row">
                    {{{filter}}}
                    <div style="width:100%;height:10px;display:block;float:none;"></div>
                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12">
                           <div class="form-group">
                                <input type="submit" name="submit" class="btn btn-outline-primary w-100 mt-4 form-button text-primary bg-white" value="xyzSearch">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<div class="add-part d-flex justify-content-md-end  my-4">
    {{{add}}}
</div>

  <section class="table-placeholder bg-white mb-5 p-1" style='height:auto;'> 
    <div class="row">
        <div class="col p-2">
            <div class="float-right mr-4">{{{import}}}</div>
        </div>
        <div class="clearfix"></div>
    </div>
    <table class="table table-mh br w-100">
        <thead>
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
                echo "<th scope='col' class='paragraphText text-center'>{$data}</th>";
            }
            else
            {
                if ($order_by === $data_field)
                {
                    if ($direction == 'ASC')
                    {
                        echo "<th scope='col' class='paragraphText text-center'><a href='{$model_base_url}?order_by={$data_field}{$format_mode}&direction=DESC'>{$data} <i class='fas fa-sort-up' style='vertical-align: -0.35em;'></i></a></th>";
                    }
                    else
                    {
                        echo "<th scope='col' class='paragraphText text-center' ><a href='{$model_base_url}?order_by={$data_field}{$format_mode}&direction=ASC'>{$data} <i class='fas fa-sort-down' style='margin-bottom:3px;'></i></a></th>";
                    }
                }
                else
                {
                    echo "<th  scope='col' class='paragraphText text-center'><a href='{$model_base_url}?order_by={$data_field}{$format_mode}&direction=ASC'>{$data} <i class='fas fa-sort-down'  style='margin-bottom:3px;color:#e2e2e2;'></i></a></th>";
                }
            }
        } ?>
        </thead>
        <tbody>
            <?php foreach ($view_model->get_list() as $data) { ?>
                <?php
                    echo '<tr>';
                        {{{row}}}
                    echo '</tr>';
                ?>
            <?php } ?>
        </tbody>
    </table>
     <p class="pagination_custom"><?php echo $view_model->get_links(); ?></p>
   </section>
