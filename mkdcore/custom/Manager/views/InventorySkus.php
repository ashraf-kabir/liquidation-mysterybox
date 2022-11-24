<?php
defined('BASEPATH') or exit('No direct script access allowed');
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

<div class="tab-content mx-4" id="nav-tabContent">

    <div class="row mb-5">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="card pb-5" style='border-bottom:1px solid #ccc;'>
                <div class="card-body">

                </div>
            </div>
        </div>
    </div>



    <?php if ($this->input->get('print') == 1) : ?>
        <div class="modal print-modal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Please print the SKU(s) below</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body  page_full_width  record_break_per printable" id="print-me-2">


                        <?php foreach ($inventories as $inventory) : ?>
                            <div class='row '>
                                <div class="col-sm-12">
                                    <h1 style="text-overflow: hidden;" class="make_font_big"> <?php echo $inventory->product_name; ?></h1>
                                </div>
                            </div>



                            <div class='row '>
                                <div class="col-sm-12">
                                    <h1 class="make_font_big"><?php echo $inventory->sku; ?></h1>
                                </div>
                            </div>


                            <div class='row '>
                                <div class="col-sm-12">
                                    <h1 class="make_font_big"> $<?php echo number_format($inventory->selling_price, 2); ?> </h1>
                                </div>
                            </div>


                            <div class='row' style="text-align: center;">
                                <div class="col-sm-12">
                                    <img style="width: 100%;height: 150px" src="<?php echo $inventory->barcode_image; ?>" alt="Barcode">
                                </div>
                            </div>
                            <hr>
                        <?php endforeach; ?>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" onClick="printdiv('print-me-2')">Print</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    <?php endif ?>


    <script>
        function printdiv(printpage) {

            $('.print-modal').modal('hide');

            var headstr = '<html><head><title></title></head><link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" crossorigin="anonymous"><style type="text/css">  body  { margin: 0px !important;  } @media print {  .make_font_big{font-size: 7rem !important;padding:0px !important; word-spacing: 50px !important; width : 100% !important;transform:translateY(-20px); } .col-sm-12{ text-align: justify !important;text-justify: inter-word !important;} @page{ width:256px !important; height: 170px !important; margin: 0px !important; padding: 0px !important; }   .printable{padding:0px!important;margin:0px!important;} .printable{width:100%!important;float:left!important;} .printable{padding:0px!important;margin:0px!important;}  } </style><body>';

            var footstr = "</body>";
            var newstr = document.all.item(printpage).innerHTML;
            var oldstr = document.body.innerHTML;
            document.body.innerHTML = headstr + newstr + footstr;
            window.print();
            document.body.innerHTML = oldstr;
            return false;
        }


        <?php if ($this->input->get('print') == 1) : ?>
            document.addEventListener('DOMContentLoaded', function() {
                $('.print-modal').modal('toggle')

                window.addEventListener("afterprint", function() {
                    location.href = "<?= base_url() . 'admin/inventory/0?order_by=id&direction=DESC'; ?>"
                });
            }, false);
        <?php endif ?>
    </script>