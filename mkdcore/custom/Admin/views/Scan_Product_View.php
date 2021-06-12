<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
if ($layout_clean_mode) {
    echo '<style>#content{padding:0px !important;}</style>';
}
 
?>

<style>
.drawingBuffer{
    display:none !important;
}
</style>
<div class="tab-content mx-4" id="nav-tabContent">
              <!-- Bread Crumb -->
<div aria-label="breadcrumb">
    <ol class="breadcrumb pl-0 mb-4 bg-background d-flex justify-content-center justify-content-md-start" style="background-color: inherit;">
        <li class="breadcrumb-item active" aria-current="page">
            <a href="/admin/scan/scan_product_view" class="breadcrumb-link">Scan Inventory</a>
        </li>
        <li class="breadcrumb-item active" aria-current="page">
            View
        </li>
    </ol>
</div>
<div class="row mb-5">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="card pb-5" style='border-bottom:1px solid #ccc;'>
            <div class="card-body">
                <h5 class="primaryHeading2 text-md-left">
                    Scan Inventory Barcode
                </h5>
                
                <div class="row mb-5">
                    <div class="form-group col-md-12 col-sm-12 "  style="text-align: center;">
                        <div id="scanner-container2"></div>
                    </div>
                </div> 
                

                <div class="form-group col-md-12 col-sm-12 " style="text-align: center;">
                    <button  id="btn-scanner-camera2" type="button" class="btn btn-primary btn-scanner-camera2">Scan</button>
                </div> 
            </div>
        </div>
    </div>
</div>


<script src="https://cdn.rawgit.com/serratus/quaggaJS/0420d5e0/dist/quagga.min.js"></script>
<script src="/assets/js/scan_product.js" defer></script>
