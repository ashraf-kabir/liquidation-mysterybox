<?php defined('BASEPATH') OR exit('No direct script access allowed');
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
if ($layout_clean_mode) {
    echo '<style>#content{padding:0px !important;}</style>';
}
?>

<div class="tab-content mx-4" id="nav-tabContent">
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

<h5 class="primaryHeading2 d-flex justify-content-between mt-2 my-4">
  <?php echo $view_model->get_heading();?>
</h5>

<section class='p-3'>
    <div class="row">
        <?php foreach( $plans as $plan):?>
            <div class="col col-4 p-0">
                <div class="card m-1 <?php if ( !empty($current_subscription->plan_id) && $plan->id == $current_subscription->plan_id ?? "" ){ echo "border  border-secondary"; } ?>">
                    <div class="card-body" style='min-height:100px;'>
                        <h4><?php echo $plan->display_name; ?></h4>
                        <p>
                            $<?php echo number_format($plan->amount, 2)?> xyzPer <?php echo ucfirst( $interval_mapping[$plan->subscription_interval])?>
                        </p>
                        <?php if(!empty( $current_subscription) && $plan->id == $current_subscription->plan_id ):?>
                           <?php if(!empty( $current_stripe_subscription) && $current_stripe_subscription->cancel_at_period_end == 1):?>
                                <a href="/member/reactivate_subscription" class='btn-link text-success pb-'>xyzReactivate Subscription</a>
                           <?php else:?>
                                <a href="/member/cancel_subscription" class='btn-link text-danger pb-'>xyzCancel</a>
                           <?php endif;?>
                        <?php else:?>
                            <?php if(!empty($current_plan)):?>
                                <?php if($current_plan->type == 2):?>
                                  <?php echo ( $current_plan->id == $plan->id ?  '<a class="btn-link text-muted"href="#">Active</a>' : '<a class="btn-link text-muted"href="#">Downgrade</a>');  ?>
                                <?php else:?>
                                    <a href="/member/change_plan/<?php echo $plan->id;?>" class='btn btn-primary change-plan  <?php echo (in_array($plan->type, [1,2]) ? 'd-none' : '' ) ?> '><?php echo ($current_plan->amount > $plan->amount ? 'xyzDowngrade' : 'xyzUpgrade' )?></a>
                                <?php endif?>
                            <?php else:?>
                                <a href="/member/change_plan/<?php echo $plan->id;?>" class='btn btn-primary change-plan'>xyzSubscribe</a>
                            <?php endif;?>
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
                            echo "<td>" . date('F d Y', strtotime($data->current_period_end)) . "</td>";
                            echo "<td>" . date('F d Y', strtotime($data->current_period_start)) . "</td>";
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
<!-- Modal -->
<div class="modal fade" id="chooseCardModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">xyzChoose Card</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <table class='table'>
             <thead class='thead-light'>
                <tr>
                    <th>xyzLast 4 Digits</th>
                    <th>xyzBrand</th>
                    <th>xyzCard Name</th>
                    <th>xyzActive Card</th>
                    <th>xyzSelect Card</th>
                </tr>
             </thead>
             <tbody>
                <?php foreach($this->_data['view_data']['cards'] as $card):?>
                    <tr>
                        <td><?php echo $card->card_last;?></td>
                        <td><?php echo $card->card_brand;?></td>
                        <td><?php echo $card->card_name;?></td>
                        <td><?php echo ($card->is_default == 1 ? 'xyzYes' : 'xyzNo');?></td>
                        <td><a href='#' data-id='<?php echo $card->id;?>' class='btn btn-primary btn-sm btn-select-card'>xyzChoose</a></td>
                    </tr>
                <?php endforeach;?>
             </tbody>
          </table>
          <a href='#' id='btn-change-plan' class="btn btn-primary btn-block" >
             xyzProcced
         </a>
      </div>
    </div>
  </div>
</div>
