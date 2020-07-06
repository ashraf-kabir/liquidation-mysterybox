<?php defined('BASEPATH') OR exit('No direct script access allowed');
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
?>
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
            xyzDashboard
          </li>
        </ol>
    </div>
</div>
<h1 class="primaryHeading text-center text-md-left">
  <?php echo $view_model->get_heading();?>
</h1>
<div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="card"   id="{{{name}}}_listing">
           
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
                </div>
            </div>
        </div>
    </div>
</div>