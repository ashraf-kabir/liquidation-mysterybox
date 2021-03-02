<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet" />
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.14.0/css/all.css"
        integrity="sha384-HzLeBuhoNPvSl5KYnjx0BT+WB0QEEqLprO+NBkkk5gbc67FTaL7XIGa2w1L0Xbgc" crossorigin="anonymous" />
    <!-- Hover Css -->
    <link rel="stylesheet" href="https://printjs-4de6.kxcdn.com/print.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/hover.css/2.3.1/css/hover-min.css"
        integrity="sha512-csw0Ma4oXCAgd/d4nTcpoEoz4nYvvnk21a8VA2h2dzhPAvjbUIK6V3si7/g/HehwdunqqW18RwCJKpD7rL67Xg=="
        crossorigin="anonymous" />
    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"
        integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous" />

    <!-- Native CSS -->
    <link href="//cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@4/dark.css" rel="stylesheet">

    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/pos_css/styles.css" />
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />

    <link href="<?php echo base_url() ?>assets/js/select2.css" />

    <div id="logs" style="display:none;"></div>
    <script src="https://js.stripe.com/terminal/v1/"></script>
    <script src="<?php echo base_url(); ?>assets/js/stripe_terminal.js"></script>
    <title>POS</title>
</head>
<style type="text/css">
     
    .cart-items::-webkit-scrollbar-track {
        /* border: 1px solid #000; */
        padding: 2px 0;
        background-color: white;
    }

    .cart-items::-webkit-scrollbar {
      width: 10px;
    }

    .cart-items::-webkit-scrollbar-thumb {
        border-radius: 10px;
        box-shadow: inset 0 0 6px rgba(0,0,0,.3);
        background-color: #343a40;
        /* border: 1px solid #000; */
    }


    /*  */
    .cart-list::-webkit-scrollbar-track {
        /* border: 1px solid #000; */
        padding: 2px 0;
        background-color: white;
    }

    .cart-list::-webkit-scrollbar {
      width: 10px;
    }

    .cart-list::-webkit-scrollbar-thumb {
        border-radius: 10px;
        box-shadow: inset 0 0 6px rgba(0,0,0,.3);
        background-color: #343a40;
        /* border: 1px solid #000; */
    }


    /*  */
    .cart-col::-webkit-scrollbar-track {
        /* border: 1px solid #000; */
        padding: 2px 0;
        background-color: white;
    }

    .cart-col::-webkit-scrollbar {
      width: 10px;
    }

    .cart-col::-webkit-scrollbar-thumb {
        border-radius: 10px;
        box-shadow: inset 0 0 6px rgba(0,0,0,.3);
        background-color: #343a40;  
        /* border: 1px solid #000; */
    }
    .select2-drop-active{
        margin-top: -25px !important;
    }

    .fix-css-quagga-container2  video, canvas {
        width: 100% !important;
        height: auto;
    }

    .fix-css-quagga-container2 video.drawingBuffer, canvas.drawingBuffer {
        display: none;
    }
</style>
<body>

    <div class="wrapper container-fluid pl-0">
        <!-- Sidebar  -->
        <nav id="sidebar" class="active">
            <div class="row">
                <ul class="list-unstyled col-12 mt-4">
                    <li class="page-toggler pl-4 active-nav" id="pos-toggler">POS <span class="counter">0</span> 
                        <i class="fas fa-chevron-right"></i></li>
                    <li class="page-toggler pl-4" id="pickup-toggler">Customer Pickup <i class="fas fa-chevron-right"></i></li>
                    <li class="page-toggler pl-4" id="past-order-toggler">Past Order <i class="fas fa-chevron-right"></i></li>
                    <li class="page-toggler pl-4" id="report-toggler">Report <i class="fas fa-chevron-right"></i></li>
                </ul>
            </div>
        </nav>

        <!-- Page Content  -->
        <div id="content" class="container-fluid px-0">
            <header id="pos-header" class="px-0 w-100 bg-white">
                <div class="row py-1 px-2 px-md-4 justify-content-between align-items-center">
                    <div class="col-4 d-flex align-items-center">
                        <div id="sidebarCollapse" class="p-3 mr-4">
                            <i class="fas fa-bars" style="position: relative;"> </i>
                            <span class="counter" style="top: 10px; right: -4px;">0</span>

                        </div>
                        <img src="<?php echo base_url(); ?>assets/pos_images/logo.svg" alt="" style="height: 50px;" />
                    </div>
                    <div class="col-4 d-flex">
                        <input type="text" placeholder="Search" class="form-control ml-2 search-pos-items-sku"> <i class="fas fa-search"></i>
                        </input>
                    </div>
                    <div class="col-4 d-flex justify-content-end">
                        <a href="<?php echo base_url(); ?>pos/logout" style="line-height:0px !important">
                        <span class="text-center mx-2 nav-btn">
                            <i class="fas fa-sign-out-alt d-block"></i>
                            <span>Logout</span>
                        </span>
                        </a>
                    </div>
                </div>
            </header>
            <section id="main" class="container-fluid">
                <div class="row pages active-page" id="pos">
                    <div class="col-xl-4 col-lg-4 col-md-4 cart-items" style="position: relative;">
                        <div class="pos-products-list d-flex flex-wrap justify-content-between py-3 px-md-3">  

                        </div>
                        <div class="row py-3 px-md-3 justify-content-center "> 
                            <button  class="btn btn-primary load_more" >Load More</button>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 cart-list px-0">
                        <div class="cart-header bg-dark text-white pt-2 pb-1 text-center mb-2">
                            <h4>Cart</h4>
                        </div>
                        <ul class="list-unstyled my-3" id="cart-ul"></ul>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 cart-col">
                        <div class="cart-header bg-dark text-white pt-2 pb-1 text-center mb-2">
                            <h4>Check out</h4>
                        </div>
                        <div class="cart-summary p-3  mt-3 ">
                            <div class="row my-1">
                                <div class="col-12 my-1" style="font-size: 14px;">
                                    <span class="counter">0</span> item(s)
                                </div>
                                <div class="col-6" style="font-size: 12px;">Subtotal</div>
                                <div class="col-6 text-right" style="font-size: 12px;">$ <span class="item-total"> 0.00</span></div>
                            </div>
                            <div class="row my-1">
                                <div class="col-6" style="font-size: 12px;">Tax</div>
                                <div class="col-6 text-right" style="font-size: 12px;">$ 0.00</div>
                            </div>
                            <div class="row my-1">
                                <div class="col-6" style="font-size: 12px;">Discount</div>
                                <div class="col-6 text-right" style="font-size: 12px;">$ <span class="item-discount-value"> 0.00</span></div>
                            </div>
                            <div class="row text-danger mt-4">
                                <div class="col-6">
                                    <h5 style="font-size: 14px;">TOTAL</h5>
                                </div>
                                <div class="col-6 text-right">
                                    <h5 style="font-size: 14px;">$ <span class="item-total discounted-total"> 0.00</span></h5>
                                </div>
                            </div>
                        </div>

                        <div class="row justify-content-center my-3 px-2">
                            <div class="col-12">
                                <button class="btn btn-success w-100 "
                                    data-toggle="modal" data-target="#checkout-modal" id="checkout-btn">
                                    <!-- <span class=" mx-auto mt-3 text-light d-flex align-items-center -- p-1"
                                        style="background: #138D36; border-radius: 4px; margin-right: 18%;">Total $<span
                                            class="item-total discounted-total">00.00</span></span> -->
                                    <strong>Pay Now</strong>

                                </button>
                            </div>
                        </div>
                        <hr>
                        <div class="cart-actions mt-1 px-2 row justify-content-between  ">
                            <div class="col-12 my-2">
                                <button class="btn btn-warning w-100" id="emty-carts"
                                    style=" background: #DD6928;">Empty Cart</button>
                            </div>

                            <div class="col-12 my-2">
                                <button class="btn btn-primary w-100 customer-btn" data-target="#customer-modal"
                                    data-toggle="modal">Customer</button>
                            </div>

                            <div class="col-12 my-2">
                                <button class="btn btn-success w-100 discount-btn" data-toggle="modal"
                                    data-target="#discount-modal"
                                    style="background: rgba(14, 135, 113, 0.83) !important;">Discount</button>
                            </div> 

                            <div class="col-12 my-2">
                                <button class="btn btn-success w-100" id="btn-scanner-camera2"  style="background: #1E59B1;">Scan Product</button>
                            </div> 
                            
                        </div>
                    </div>
                </div>
                <div class="row pages d-none w-100 justify-content-center py-5" id="pickup">
                    <div class="col-12 px-1 px-md-5  ">
                        <div class="table-responsive" >
                            <table class="table bg-white">
                                <thead class="thead-dark text-center">
                                    <tr>
                                        <th scope="col">Customer Name</th>
                                        <th scope="col">Order Number</th>
                                        <th scope="col">Pickup ID</th>
                                        <th scope="col">Items</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Order Placed Date</th>
                                        <th scope="col">Last Pick Date</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody class=" text-center"> 
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="col-12 px-1 px-md-5 pagination-for-pickup "> 
                    </div>
                </div> 

                <div class="row pages d-none justify-content-center my-5 " id="past-order">
                    <div class="col-12  px-1 my-3 px-md-5">
                        <div class="row bg-white" style="padding: 18px 3px;margin: 0px !important;">
                            <div class="col-3">
                                <label>From</label>
                                <input type='date' class="form-control search_past_orders_from" />
                            </div>

                            <div class="col-3">
                                <label>To</label>
                                <input type='date' class="form-control search_past_orders_to" />
                            </div>

                            <div class="col-3">
                                <label>Customer</label>
                                <input type='text' class="form-control search_past_orders_customer" />
                            </div>
                            <div class="col-3" style="margin-top: 29px;"> 
                                <button class="btn btn-info search_past_orders">Search</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 px-1 my-3 px-md-5  ">
                        <div class="table-responsive" >
                            <table class="table bg-white">
                                <thead class="thead-dark text-center">
                                    <tr>
                                        <th scope="col">Customer Name</th>
                                        <th scope="col">Order Number</th>
                                        <th scope="col">Pickup ID</th>
                                        <th scope="col">Items</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Sub Total</th>
                                        <th scope="col">Tax</th>
                                        <th scope="col">Shipping Cost</th>
                                        <th scope="col">Total</th>
                                        <th scope="col">Picked</th>
                                        <th scope="col">Shipped</th>
                                    </tr>
                                </thead>
                                <tbody class=" text-center">
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="col-12 px-1 px-md-5 pagination-for-past-orders "> 
                    </div>
                </div>

                <div class="row pages d-none" id="report"> 

                    <div class="col-12  px-1 my-3 px-md-5">
                        <div class="row bg-white" style="padding: 18px 3px;margin: 0px !important;">
                            <div class="col-3">
                                <label for="summary-date" class="">Select Date</label>
                                <input type="date" name="date" id="summary-date" class="form-control" value="<?php echo Date('Y-m-d'); ?>" >
                            </div>

                            <div class="col-3" style="margin-top: 29px;"> 
                                <button class="btn btn-info search_summary_report">Search</button>
                            </div>
                        </div>
                    </div> 

                    <div class="col-12 px-1 my-3 px-md-5  ">
                        <div class="table-responsive">
                            <table class="table bg-white">
                                <thead class="thead-dark text-center">
                                    <tr>
                                        <th scope="col">Order No</th>
                                        <th scope="col">Customer</th> 
                                        <th scope="col">Transaction on</th> 
                                        <th scope="col">Type</th>
                                        <th scope="col">Tax</th> 
                                        <th scope="col">Discount</th> 
                                        <th scope="col">Sub Total</th> 
                                        <th scope="col">Total</th> 
                                    </tr>
                                </thead>
                                <tbody class=" text-center report_summary">
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="col-12 px-1 my-3 px-md-5 ">
                        <div class="table-responsive">
                            <table class="table bg-white">
                                <thead class="thead-dark text-center">
                                    <tr>
                                        <th scope="col">Day Cash</th>
                                        <th scope="col">Credit Card</th>
                                        <th scope="col">Discount</th> 
                                        <th scope="col">Total</th> 
                                    </tr>
                                </thead>
                                <tbody class=" text-center report_summary_total">
                                    <tr>  
                                    </tr> 
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="row pages d-none p-2 justify-content-center" id="receipt">
                    <div class="col-12 text-center my-3">
                        <a href="" onclick="location.reload()">Back to home</a> 
                        <br> 
                        <a class="ml-auto" href="#" onclick="$.print('#ticket');">Print Receipt</a>
                    </div>
                    <div class="col-xl-8 col-lg-10 col-md-11 col-12 ticket bg-white text-center p-3 mx-auto" id="ticket">
                        <h4>Vegas Liquidation</h4>
                        <h6 id="receipt-address"></h6> 
                        <h6 id="receipt-order-id"></h6> 

                        <div class="row w-100 px-1 px-md-3 mt-4">
                            <h6 class="receipt-customer-name" id="receipt-customer-name" style="width: 100%; text-align: -webkit-left;"></h6>
                            <h6 class="receipt-date"></h6>  
                            <h6 class="mx-2 receipt-time"></h6>
                           
                        </div>


                        <div class="row"  >
                            <div class="col-12" style="margin-bottom: 70px;">
                                <div class="table-responsive">
                                    <table class="table table-borderless">
                                        <thead class="thead text-center" style="border-bottom: 0.5px solid #0000002f;">
                                            <tr>
                                                <th scope="col">QTY</th>
                                                <th scope="col">Product</th>
                                                <th scope="col">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-center" id="receipt-table-body">

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="row receipt-summary justify-content-end" style="position: absolute; bottom: 0; width: 100%;">
                            <div class="col-12 text-right ">
                                <div class="row justify-content-end">
                                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-5 text-right">
                                    <h6 class="my-2">DISCOUNT</h6>
                                    <h6 class="my-2">TAX</h6>
                                    <h6 class="my-2">TOTAL</h6>
                                    </div>
                                    <div class="col-xl-3 col-lg-3 col-md-3 col-sm-4 col-5">
                                        <h6 class="my-2">$ <span class="item-discount-value  item-discount-value-db">0.00</span></h6>
                                        <h6 class="my-2 ">$ <span class="  item-tax-value-db">0.00</span></h6>
                                        <h6 class="my-2">$ <span class=" item-total discounted-total  item-total-value-db">0.00</span></h6>
                                    </div>
                                </div>
                             
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <!-- Amount in drawer Modal -->
    <div class="modal fade" id="drawer-modal" tabindex="-1" role="dialog" aria-labelledby="drawerLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-md p-3 modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="drawerLabel">Drawer Cash</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="" id="drawer-form" data-id="">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col my-3">
                                <label for="amount">Enter Amount below: </label>
                                <input type="number" name="amount" placeholder="$00.00" min="1" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
                        <input type="submit" class="btn btn-secondary bg-dark px-4" data-dismiss="modal" value="Submit">
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add to Cart Modal -->
    <div class="modal fade" id="addCart-modal" tabindex="-1" role="dialog" aria-labelledby="addCartLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-md p-3 modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCartLabel">Add Item to Cart</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="" id="addToCart-form" data-id="">
                    <div class="modal-body">
                        <div class="row align-items-center justify-content-between">
                            <div class="col-6">
                                <h4 class="modal-sub-title" id="item-title"></h4>
                            </div>
                            <div class="col-6 text-right">
                                <h4 class="text-danger">$ <span id="item-price"></span></h4>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col my-3">
                                <label for="quantity">QTY: </label>
                                <input type="number" name="quantity" value="1" min="1"  class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
                        <input type="submit" class="btn btn-success px-4" id="addToCartBtn" value="Add to Cart">
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add Custom Cart Modal -->
    <div class="modal fade" id="customCart-modal" tabindex="-1" role="dialog" aria-labelledby="customCartLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-md p-3 modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="customCartLabel">Add Custom Product</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="" id="customCart-form" data-id="">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12 my-3">
                                <label for="title">Title <span class="text-danger">*</span></label>
                                <input type="text" name="title" id="title" class="form-control px-2"
                                    placeholder="Enter Product Title" required>
                            </div>

                            <div class="col-12 my-3">
                                <label for="price">Price <span class="text-danger">*</span></label>
                                <input type="number" id="price" name="price" class="form-control" placeholder="00.00"><i
                                    class="fas fa-dollar-sign"></i></input>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col my-3">
                                <label for="quantity">QTY: </label>
                                <input type="number" name="quantity" value="1" min="1"  class="form-control" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <label for="note">Note</label>
                                <textarea name="note" id="note" cols="30" rows="3" placeholder="text here..."
                                    class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
                        <input type="submit" class="btn btn-primary px-4" style="background: #1E59B1;"
                            value="Add Product">
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Barcode Modal -->
    <div class="modal fade" id="barcode-modal" tabindex="-1" role="dialog" aria-labelledby="barcodeLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-md p-3 modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="barcodeLabel">Barcode / SKU</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="" id="barcode-form" data-id="">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12 my-3">
                                <label for="barcode">Barcode/SKU Code <span class="text-danger">*</span></label>
                                <input type="text" name="barcode" id="barcode" class="form-control px-2"
                                    placeholder="Enter Code" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
                        <input type="submit" class="btn btn-secondary px-4" value="Submit">
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Discount Modal -->
    <div class="modal fade" id="discount-modal" tabindex="-1" role="dialog" aria-labelledby="discountLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-md p-3 modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="discountLabel">Discount</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="" id="discount-form" data-id="">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <label for="type">Type</label>
                                <select name="type" id="type" class="form-control">
                                    <option value="fixed">Fixed</option>
                                    <option value="percentage">Percentage</option> 
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 my-3">
                                <label for="discount">Discount</label>
                                <input type="number" name="discount" min="1" placeholder="0" class="form-control px-2"
                                    required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
                        <input type="submit" class="btn btn-success px-4"
                            style="background: rgba(14, 135, 113, 0.83) !important;">
                    </div>
                </form>
            </div>
        </div>
    </div>



    <!-- Edit Quantity Modal -->
    <div class="modal fade" id="edit-quantity-modal" tabindex="-1" role="dialog" aria-labelledby="edit-quantityLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-md p-3 modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="edit-quantityLabel">Change Quantity</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="" id="edit-quantity-form" data-id="">
                    <div class="modal-body">
                        <div class="rw">
                            <div class="col-12 my-3 px-0">
                                <h5 class="edit-title"></h5>
                            </div>
                            <div class="col-12 d-flex px-0 justify-content-between">
                                <h5>Price</h5>
                                <h5 class="text-danger">$<span class="edit-price"></span> </h5>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 my-3">
                                <label for="quantity">Quantity<span class="text-danger">*</span></label>
                                <input type="number" name="quantity" min="1" class="form-control px-2" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
                        <input type="submit" class="btn btn-success px-4" id="updateQuantity" value="Update">
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Checkout Modal -->
    <div class="modal fade " id="checkout-modal" tabindex="-1" role="dialog" aria-labelledby="checkoutLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg p-3 modal-dialog-centered  " role="document">
            <div class="modal-content p-sm-3">
                <div class="modal-header ">
                    <h5 class="modal-title" id="checkoutLabel">Checkout</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="text-white">&times;</span>
                    </button>
                </div>
                <form action="" id="checkout-form" data-id="">
                    <div class="modal-body">
                        <div class="row bg-white" style="margin-bottom: 10px;">
                            <div class="col-7 my-3">
                                <i class="fas fa-user mr-2 "></i>
                                <h5 class="d-inline-block customer-name"></h5>
                            </div>
                            <div class="col-5 " style="position: relative">
                                <label for="checkout-address">Customer</label> 
                                <select class="form-control customer-list-api" name="customer_id" required >
                                    <option value="">Select</option>
                                </select>
                            </div>
                        </div>       

                        <div class="row bg-white" > 
                            <div class="col-6 " style="position: relative">
                                <label for="checkout-name">Name</label><i class="fas fa-edit edit-address-btn"
                                    style="position: absolute; right: 10px;"></i>
                                <input type="text" name="name" class="checkout-address form-control"
                                    id="checkout-name" readonly="true" value="">
                            </div> 

                            <div class="col-6 " style="position: relative">
                                <label for="checkout-city">City</label>
                                <input type="text" name="city" class="checkout-address form-control"
                                    id="checkout-city" readonly="true" value="">
                            </div> 

                            <div class="col-6 " style="position: relative">
                                <label for="checkout-address"> Postal Code</label>
                                <input type="text" name="postal_code" class="checkout-address form-control"
                                    id="checkout-postal_code" readonly="true" value="">
                            </div>

                            <div class="col-6 " style="position: relative">
                                <label for="checkout-address">Country</label>
                                <input type="text" name="country" class="checkout-address form-control"
                                    id="checkout-country" readonly="true" value="">
                            </div> 
                            <div class="col-6 " style="position: relative">
                                <label for="checkout-address">State</label>
                                <input type="text" name="state" class="checkout-address form-control"
                                    id="checkout-state" readonly="true" value="">
                            </div> 

                            <div class="col-6 " style="position: relative">
                                <label for="checkout-type">Type</label> 
                                <Select class="checkout_type customer_pickup_type form-control" name="checkout_type">
                                    <option value='1' >Pickup</option>
                                    <option value='2' selected>Delivery</option>
                                </Select>
                            </div>
                        </div>
                        <div class="row bg-white" > 
                            <div class="col-12 " style="position: relative">
                                <label for="checkout-address">Address</label>
                                <input type="text" name="address" class="checkout-address form-control"
                                    id="checkout-address" readonly="true" value="">
                            </div> 
                        </div>



                        <div class="row mt-3 align-items-center py-4 bg-white">
                            <div class="col-xl-8 col-lg-8 col-md-10 col-12">
                                <label for="message">Message</label>
                                <textarea name="message" id="message" class="form-control mr-2" cols="30" rows="4"
                                    placeholder="text here..."></textarea>
                            </div>
                        </div>

                        <div class="row mt-3 align-items-center py-4 bg-white" id="card-details">
                            <div class="col-xl-10 col-lg-10 col-md-10 col-12">
                                <label class="d-block" for="payment">Select Payment Method </label>
                                <label class="d-block" for="cash">
                                    <input type="radio" name="payment" id="cash" value="1" checked> Cash
                                </label>
                                <!-- <label class="d-block" for="credit-card">
                                    <input type="radio" name="payment" id="credit-card" value="2"> Credit/Debit Card
                                </label> -->

                                <label class="d-block" for="card">
                                    <input type="radio" name="payment" id="terminal" value="2"> Credit
                                </label>


                                <div class="row d-none" id="card-input-area">
                                    <!-- <div class="col-6">
                                        <input type="number" id="card-number-for-stripe-is" name="card-number" class="form-control card-number-for-stripe-is"
                                            placeholder="Card number" maxlength="16"
                                            oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);">
                                    </div> -->
                                    <!-- <div class="col-6">
                                        <input type="number" name="card-month" class="form-control w-25 d-inline"
                                            maxlength="2"
                                            oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                            placeholder="MM">
                                        <input type="number" name="card-year" class="form-control w-25 d-inline"
                                            maxlength="2"
                                            oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                            placeholder="YY">
                                    </div>
                                    <div class="col-12">
                                        <input type="number" name="cvc" class="form-control w-25 d-inline my-2"
                                            maxlength="3"
                                            oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                            placeholder="CVC">
                                    </div> -->
                                </div>



                            </div>

                            <hr class="w-100">

                            <div class="col-12">
                                <label for="split-payment">
                                    <input type="checkbox" name="split-payment" id="split-payment"> Split Payment
                                </label>
                            </div>
                            
                            <div class="col-12 shipping-postal-option mb-2">
                                <label for="">Shipping Postal Code</label>
                                <input type="text" class="form-control shipping-postal-code" name="shipping_postal_cost" value="">
                            </div> 

                            <div class="col-12 button-calculate-shipping">
                                <label for="split-payment">
                                    <button class="btn btn-primary calculate-shipping-cost" type="button"> Calculate Shipping Cost </button>
                                </label>
                                
                            </div>
                            <div  class="col-12 shipping-cost-options">
                            </div>
                        </div>

                        <div class="row bg-white mt-3 py-4">
                            <div class="col-12">
                                <h4>Payable</h4>
                            </div>
                            <div class="col-12 d-flex justify-content-between">
                                <h6 class="text-danger">Total</h6>
                                <h6 class="text-danger">$ <span class="item-total discounted-total">0.00</span></h6>
                            </div>
 
                            <div class="col-12 d-flex justify-content-between">
                                <h6 class="">Change</h6>
                                <p class="text-danger">$ <span class="change">0.00</span></p>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cancel</button>
                        <input type="submit" class="btn btn-success px-4" id="payBtn" value="Pay">
                        <!-- <button type="button" class="btn btn-success px-4" id="collect-button"  >Collect</button> -->
                        <button type="button" class="btn btn-success px-4" id="capture-button"  >Capture</button>
                        <div class="show-loader">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <input type="hidden" id="pageno" value="1">

    
    <!-- Customer Modal -->
    <div class="modal fade " id="customer-modal" tabindex="-1" role="dialog" aria-labelledby="customerLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered  " role="document">
            <div class="modal-content">
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title" id="customerLabel">Add New Customer</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="text-white">&times;</span>
                    </button>
                </div>
                <form action="" id="customer-form" class="p-3">
                    <div class="form-row justify-content-between ">
                        <div class="col-xl-6 col-lg-6 col-md-6 col-12 my-3">
                            <label for="first-name">First Name<span class="text-danger">*</span></label>
                            <input type="text" name="firstname" class="form-control" id="first-name"
                                placeholder="Your First Name" required>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-12 my-3">
                            <label for="last-name">Last Name</label>
                            <input type="text" name="lastname" class="form-control" id="last-name"
                                placeholder="Your Last Name"  >
                        </div>
                    </div>

                    <div class="form-row justify-content-between ">
                        <div class="col-xl-6 col-lg-6 col-md-6 col-12 my-3">
                            <label for="email">Email</label>
                            <input type="text" name="email" class="form-control" id="email"
                                placeholder="dhoe@gmail.com">
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-12 my-3">
                            <label for="phone">Telephone</label>
                            <input type="tel" name="phone" class="form-control" id="phone" placeholder="+12345678">
                        </div>
                    </div>

                    <div class="form-row justify-content-between ">
                        <div class="col-xl-6 col-lg-6 col-md-6 col-12 my-3">
                            <label for="address-1">Street </label>
                            <input type="text" name="street" class="form-control" id="address-1">
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-12 my-3">
                            <label for="address-2">Street Address 2</label>
                            <input type="text" name="streetTwo" class="form-control" id="address-2">
                        </div>
                    </div>

                    <div class="form-row justify-content-between ">
                        <div class="col-xl-6 col-lg-6 col-md-6 col-12 my-3">
                            <label for="city">City</label>
                            <input name="city" type="text" class="form-control" id="city">
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-12 my-3">
                            <label for="postal-code">Postal Code</label>
                            <input name="postal-code" type="text" class="form-control" id="postal-code">
                        </div>
                    </div>


                    <div class="form-row justify-content-between ">
                        <div class="col-xl-6 col-lg-6 col-md-6 col-12 my-3">
                            <label for="country">Country</label>
                            <input name="country" type="text" class="form-control" id="country"> 
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-12 my-3">
                            <label for="state">State</label>
                            <input name="state" type="text" class="form-control" id="state">  
                        </div>
                    </div>

                    <div class="row justify-content-end my-3">
                        <div class="col-12 text-right">
                            <button class="btn btn-outline-secondary mr-2 " data-target="#customer-modal"
                                data-dismiss="modal">Close</button>
                            <input type="submit" class="btn btn-primary" value="Continue">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>



    <!-- POS Login Modal -->
    <div class="modal fade " id="on-pos-login-modal" tabindex="-1" role="dialog" aria-labelledby="customerLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered  " role="document">
            <div class="modal-content">
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title" id="customerLabel">Add New Customer</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="text-white">&times;</span>
                    </button>
                </div>
                <form action="" id="customer-form" class="p-3">
                    <div class="form-row justify-content-between ">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-12 my-3">
                            <label for="first-name">First Name<span class="text-danger">*</span></label>
                            <input type="text" name="firstname" class="form-control" id="first-name"
                                placeholder="Your First Name" required>
                        </div> 
                    </div>

                    <div class="row justify-content-end my-3">
                        <div class="col-12 text-right">
                            <button class="btn btn-outline-secondary mr-2 " data-target="#customer-modal"
                                data-dismiss="modal">Close</button>
                            <input type="submit" class="btn btn-primary" value="Continue">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    

    <!-- Scan Product Modal -->
    <div class="modal fade" id="scan-product-modal" tabindex="-1" role="dialog" aria-labelledby="scan-productLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-md p-3 modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="barcodeLabel">Scan Product</h5>
                    <button type="button" class="close close-scanner-camera2"  aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                 
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-md-12 col-sm-12 fix-css-quagga-container2"  style="text-align: center;">
                            <div id="scanner-container2"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary close-scanner-camera2"  >Close</button> 
                </div>
                 
            </div>
        </div>
    </div>

    <!-- Optional JavaScript -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js" ></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" ></script>

<script>
toastr.options = {
  "closeButton": false,
  "debug": false,
  "newestOnTop": false,
  "progressBar": false,
  "positionClass": "toast-top-right",
  "preventDuplicates": false,
  "onclick": null,
  "showDuration": "300",
  "hideDuration": "1000",
  "timeOut": "5000",
  "extendedTimeOut": "1000",
  "showEasing": "swing",
  "hideEasing": "linear",
  "showMethod": "fadeIn",
  "hideMethod": "fadeOut"
}
 
</script>

    <!-- <script src="https://code.jquery.com/jquery-3.5.1.js"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"
        integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN"
        crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"
        integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV"
        crossorigin="anonymous"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jQuery.print/1.6.0/jQuery.print.min.js"
        integrity="sha512-i8ERcP8p05PTFQr/s0AZJEtUwLBl18SKlTOZTH0yK5jVU0qL8AIQYbbG5LU+68bdmEqJ6ltBRtCxnmybTbIYpw=="
        crossorigin="anonymous"></script>
    <!-- Main JS -->

    <script src="//cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/select2.js"></script>
    <script src="<?php echo base_url(); ?>assets/pos_js/script.js"></script>
    <script type="text/javascript"> 
        $(document).ready(function () { 
            $("#sidebarCollapse").on("click", function () {
                $("#sidebar").toggleClass("active");
            });
        });
    </script>

    <script src="/assets/js/quagga.min.js"></script> 
</body>
</html>