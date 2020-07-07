<?php defined('BASEPATH') OR exit('No direct script access allowed');
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
if ($layout_clean_mode) {
    echo '<style>#content{padding:0px !important;}</style>';
}
?>
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
</section>