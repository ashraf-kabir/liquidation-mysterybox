<?php defined('BASEPATH') OR exit('No direct script access allowed');
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2020*/
?>
<div class="tab-content mx-4" id="nav-tabContent">
<div class="clear"></div>
<?php if (strlen($error) > 0) : ?>
    <div class="row">
        <div class="col-md-12 mt-4">
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

<h5 class="primaryHeading2 text-md-left">
  <?php echo $view_model->get_heading();?>
</h5>
<div class="row mb-5">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="card"   id="{{{name}}}_listing">

            <div class="card-body">

                <div class="table-responsive">
                    <table class="table table-hover table-condensed table-striped">
                        <thead class='thead-light'>
                        <?php foreach ($view_model->get_column() as $data) {
                            echo "<th text-left>{$data}</th>";
                        } ?>
                        </thead>
                        <tbody class="tbody-light">
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
</div>