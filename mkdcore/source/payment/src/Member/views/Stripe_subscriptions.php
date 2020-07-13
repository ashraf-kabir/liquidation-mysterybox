<?php defined('BASEPATH') OR exit('No direct script access allowed');
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
if ($layout_clean_mode) {
    echo '<style>#content{padding:0px !important;}</style>';
}
?>

<div class="tab-content" id="nav-tabContent">
    <div aria-label="breadcrumb">
        <ol class="breadcrumb pl-0 mb-4 bg-background d-flex justify-content-center justify-content-md-start">
        <li class="breadcrumb-item active" aria-current="page">
            <a href="/member/dashboard" class="breadcrumb-link">xyzDashboard</a>
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
<section class='p-3'>
    <div class="row">
        <?php foreach( $this->_data['view_data']['plans'] as $plan):?>
            <div class="col p-0">
                <div class="card m-1 <?php if (in_array($plan->id, $this->_data['view_data']['user_plans'])){ echo "border border-warning"; } ?>">
                    <div class="card-body">
                        <h4><?php echo $plan->display_name; ?></h4>
                        <p>
                            $<?php echo number_format($plan->amount, 2)?> xyzPer <?php echo ucfirst( $this->_data['view_data']['interval_mapping'][$plan->subscription_interval])?>
                        </p>
                        <?php if(in_array($plan->id,$this->_data['view_data']['user_plans'])):?>
                           <?php if(!empty($this->_data['view_data']['current_subscription']) && $this->_data['view_data']['current_subscription']->cancel_at_period_end == 1):?>
                                <a href="/member/cancel_subscription" class='btn btn-link text-success'>xyzUndo Cancel</a>
                           <?php else:?>
                                <a href="/member/cancel_subscription" class='btn btn-link text-danger'>xyzCancel</a>
                           <?php endif;?>
                        <?php else:?>
                            <a href="/member/change_plan/<?php echo $plan->id;?>" class='btn btn-primary'>xyzSubscribe</a>
                        <?php endif;?>
                    </div>
                </div>
            </div>
        <?php endforeach;?>
    </div>
</section>

<section class="table-placeholder bg-white mb-5 p-1" style='height:auto;'> 
    <div class="row">
        <div class="col p-2">
            <div class="float-right mr-4"></div>
        </div>
        <div class="clearfix"></div>
    </div>
    <table class="table table-mh br w-100">
        <thead class='thead-light'>
            <?php foreach ($view_model->get_column() as $data) {
                echo "<th class='text-left'>{$data}</th>";
            } ?>
        </thead>
        <tbody>
            <?php foreach ($view_model->get_list() as $data) { ?>
                <?php
                    echo '<tr>';
                        	echo "<td>{$data->id}</td>";
							echo'<td>' . $data->plan_name . "<br/>" . $data->plan_interval . "<br/>" . '</td>';
							echo "<td>" . ucfirst($view_model->cancel_at_period_end_mapping()[$data->cancel_at_period_end]) ."</td>";
							echo "<td>" . date('F d Y', strtotime($data->current_period_start)) . "</td>";
							echo "<td>" . date('F d Y', strtotime($data->current_period_end)) . "</td>";
							echo "<td>" . ucfirst($view_model->status_mapping()[$data->status]) ."</td>";
							echo '<td>';
							echo '</td>';
                    echo '</tr>';
                ?>
            <?php } ?>
        </tbody>
    </table>
    <p class="pagination_custom"><?php echo $view_model->get_links(); ?></p>
</section>