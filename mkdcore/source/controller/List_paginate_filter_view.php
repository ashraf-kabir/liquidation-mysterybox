<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
if ($layout_clean_mode) {
    echo '<style>#content{padding:0px !important;}</style>';
}
?>
<h2><?php echo $view_model->get_heading();?></h2>
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
<div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="card" id="{{{name}}}_filter_listing">
            <h5 class="card-header"><?php echo $view_model->get_heading();?> xyzSearch</h5>
            <div class="card-body">
                <?= form_open('/{{{portal}}}{{{route}}}', ['method' => 'get']) ?>
                    <div class="row">
                    {{{filter}}}
                    <div style="width:100%;height:10px;display:block;float:none;"></div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                           <div class="form-group">
                                <input type="submit" name="submit" class="btn btn-primary" value="xyzSearch">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 mt-3">
        <div class="card"  id="{{{name}}}_listing">
            <h5 class="card-header">
                <div class="float-left"><?php echo $view_model->get_heading();?></div>
                <div class="float-right">{{{add}}}</div>
                <div class="float-right">{{{import}}}</div>
                <div class="clearfix"></div>
            </h5>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-condensed table-striped">
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
                                echo "<th>{$data}</th>";
                            }
                            else
                            {
                                if ($order_by === $data_field)
                                {
                                    if ($direction == 'ASC')
                                    {
                                        echo "<th><a href='{$model_base_url}?order_by={$data_field}{$format_mode}&direction=DESC'>{$data} <i class='fas fa-sort-up' style='vertical-align: -0.35em;'></i></a></th>";
                                    }
                                    else
                                    {
                                        echo "<th><a href='{$model_base_url}?order_by={$data_field}{$format_mode}&direction=ASC'>{$data} <i class='fas fa-sort-down' style='margin-bottom:3px;'></i></a></th>";
                                    }
                                }
                                else
                                {
                                    echo "<th><a href='{$model_base_url}?order_by={$data_field}{$format_mode}&direction=ASC'>{$data} <i class='fas fa-sort-down'  style='margin-bottom:3px;color:#e2e2e2;'></i></a></th>";
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
                </div>
            </div>
        </div>
    </div>
</div>