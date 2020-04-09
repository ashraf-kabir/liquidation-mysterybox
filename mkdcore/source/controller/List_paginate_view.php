<?php defined('BASEPATH') OR exit('No direct script access allowed');
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
        <div class="card"  id="{{{name}}}_listing">
            <h5 class="card-header">
                <div class="float-left"><?php echo $view_model->get_heading();?></div>
                <div class="float-right">{{{add}}}</div>
                <div class="float-right">{{{import}}}</div>
            </h5>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-condensed table-striped">
                        <thead>
                        <?php foreach ($view_model->get_column() as $data) {
                            echo "<th>{$data}</th>";
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