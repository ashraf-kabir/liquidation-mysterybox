
<style type="text/css">
.cart-main-container{
    min-height: calc(100vh - 320px + 50px + 130px);
}
    .delete_btn_c {
        z-index: 2;
        position: absolute;
        right: -47px;
        top: -24px;
        color: #dc3545;
        text-decoration: none !important;
    }

    .delete_btn_c:hover { 
        color: #dc3545 !important; 
    }

    .image-fit-in-div{
        width: 100%;
        min-height: 200px;
        max-height: 200px;
        object-fit: contain;
    }

    .show-text-only{
        word-wrap: break-word;
    }

    @media only screen and (max-width:1199px) {

        .delete_btn_c { 
            right: 4px;
            top: -22px;
        }
        .margin-left-0{
            margin-left: 0px !important;
        }
    } 

    @media only screen and (max-width:767px) { 

        .delete_btn_c { 
            right: 0px;
            top: -381px;
        }
        .margin-left-0{
            padding: 0px !important;
        }
    } 

    @media only screen and (min-width:1400px) { 

        .delete_btn_c { 
            right: -49px; 
        }
        .margin-left-0{
            padding: 0px !important;
        }
    }

    @media only screen and (min-width:1500px) { 

        .delete_btn_c { 
            right: -56px; 
        }
         
    }

    @media only screen and (min-width:1710px) { 

        .delete_btn_c { 
            right: -66px; 
        }
         
    } 

    @media only screen and (min-width:2012px) { 

        .delete_btn_c { 
            right: -71px;
        } 
    } 

    @media only screen and (min-width:2370px) { 

        .delete_btn_c { 
            right: -84px;
        } 
    } 



</style>
<section>
    <div class="container-fluid cart-main-container my-5" id="cart">
        <div class="row justify-content-center">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                <?php if ($this->session->flashdata('success1')): ?> 
                    <div class="alert alert-success alert-dismissible " role="alert">
                        <?php echo $this->session->flashdata('success1'); ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div> 
                <?php endif ?>

                <?php if ($this->session->flashdata('error1')): ?> 
                    <div class="alert alert-danger alert-dismissible " role="alert">
                        <?php echo $this->session->flashdata('error1'); ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div> 
                <?php endif ?>
            </div>
            <div class="col-xl-8 col-lg-8 col-md-8 col-sm-12 col-10">
                <h5>Shopping Cart: <span class="cart-count"><?php echo count($cart_items);  ?></span> item(s)</h5>
                <div id="carts"> 
                    <?php 
                    $total      = 0;
                    $sub_total  = 0;
                    foreach($cart_items as $key => $value) {  $total = $total + $value->total_price;  ?> 
                        <div class="row cart__addedItem justify-content-between mb-4"  style="background-color: white;">
                            <div class="col-xl-3 col-lg-4 col-md-4 col-12 my-3">
                                <?php if( empty($value->feature_image) ) :  ?>
                                    <img  class="image-fit-in-div img-thumbnail " src="<?php echo base_url(); ?>/assets/frontend_images/noun_pallet_box_1675914.png" alt="<?= $value->product_name; ?>"  />
                                    <?php else:  ?>
                                        <img  class="image-fit-in-div img-thumbnail "  src="<?php echo $value->feature_image; ?>" alt="<?= $value->product_name; ?>" />
                                    <?php endif;  ?>

                                </div>
                                <div class="col-xl-4 col-lg-4 col-md-4 col-12 my-3 d-flex align-content-between flex-wrap">
                                    <h4 class="w-100 "><?= $value->product_name; ?></h4>
                                    <?php if (!empty($value->store_id)) : ?>
                                    <h4 class="w-100 "><i class="fa fa-store fa-2x"></i></h4>
                                    <input type="hidden" name="store_id" id="<?php echo $value->id ?>-store-input" value="<?php echo $value->store_id ?>">
                                    <?php endif ; ?>
                                    <h4 class="text-danger my-3">
                                        <span>Price: </span>$<?= number_format($value->unit_price,2); ?> 
                                    </h4> 
                                </div>
                                <div class="col-xl-3 col-lg-4 col-md-4 col-12 my-3 d-flex align-content-between flex-wrap">
                                    <a href="<?php echo base_url(); ?>cart_remove/<?= $value->id; ?>" style="line-height: 2;" class=" delete_btn_c  remove-btn"><strong>X</strong></a>
                                    <label for="quantity">
                                        Qty:
                                        <input type="text"  name="quantity" class="quantity_value form-control w-75" value="<?= $value->product_qty; ?>"  />
                                    </label>
                                    <h5>
                                        Total: <span>$<?= number_format($value->total_price,2); ?> </span>
                                    </h5>
                                    <br>
                                    <p style="width: 100%;"><a data-id="<?php echo $value->product_id; ?>" data-cart-id="<?php echo $value->id; ?>" href="" class=" edit_to_cart_button btn btn-sm btn-info">Update</a></p>

                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>


                <div class="col-xl-3 col-lg-4 col-md-4 col-sm-12 col-10 mt-3 margin-left-0" style="margin-left: 10px;    margin-top: 31px !important;" >
                    <div style=" background-color: white;  padding-top: 7px;">
                        
                    
                    <a href="<?php echo base_url(); ?>categories" class="btn text-success">
                        <i class="fas fa-cart-plus"></i> Continue Shopping
                    </a>
                    <div class="cart__address p-4">
                        <h4 class="my-4"><i class="fas fa-truck"></i> Shipping</h4>

                        <h5 class="my-3 show-text-only">  <?php echo  isset($customer->shipping_address) ? $customer->shipping_address : '' ; ?>  </h5>
                        <p class="w-75 my-2 show-text-only">
                            <?php echo isset($customer->shipping_city) ? $customer->shipping_city . ", " : '' ; ?><?php echo isset($customer->shipping_state) ? $customer->shipping_state : '' ; ?>
                            <?php echo isset($customer->shipping_zip) ? $customer->shipping_zip : '' ; ?>
                        </p>
                    </div>
                    <div class="cart__summary px-4 py-4">
                        <h5><i class="fas fa-cart-plus"></i> Order Summary</h5>
                        <div class="d-flex justify-content-between my-4">
                            <h6>Subtotal</h6>
                            <h6>$<span class="cart-subtotal"><?= number_format($total,2); ?></span></h6>
                        </div> 


                        <?php if(count($cart_items) >  0) : ?>
                        <a  

                        <?php if ($this->session->userdata('customer_login')): ?>
                            href="<?php echo base_url(); ?>checkout"   
                            <?php else: ?>  
                                data-target="#loginModal" data-toggle="modal" 
                            <?php endif; ?> 

                            style="line-height:2;" class="btn btn-success w-100  mb-4" >Checkout</a >
                            <?php endif ;?>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
        </section>
