 
<section>
    <div class="container-fluid my-5" id="cart">
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
                <div class="row cart__addedItem justify-content-between mb-4" data-id=${ item.id }>
                  <div class="col-xl-3 col-lg-4 col-md-4 col-12 my-3">
                      <?php if( empty($value->feature_image) ) :  ?>
                        <img style=" width: 100%;" src="<?php echo base_url(); ?>/assets/pos_images/default_product_image.jpg" alt="<?= $value->product_name; ?>"  />
                      <?php else:  ?>
                        <img  style=" width: 100%;"  src="<?php echo $value->feature_image; ?>" alt="<?= $value->product_name; ?>" />
                      <?php endif;  ?>
                    
                  </div>
                  <div class="col-xl-4 col-lg-4 col-md-4 col-12 my-3 d-flex align-content-between flex-wrap">
                    <h4 class="w-100 "><?= $value->product_name; ?></h4>
              
                    <h4 class="text-danger my-3">
                      <span>Price: </span>$<?= number_format($value->unit_price,2); ?> 
                    </h4>
      
                    <!-- <div class="w-100">
                      <label for="store" class="d-block">
                        <input
                          type="radio"
                          name="delivery"
                          id="store"
                          class="my-2"
                          value="store"
                          checked=${item.delivery === "store" ? true : false}
                        />
                        <i class="fas fa-warehouse"></i> In-store-pickup
                      </label>
                      <label for="home" class="d-block">
                        <input
                          type="radio"
                          name="delivery"
                          id="home"
                          class="my-2"
                          value="home"
                          checked=${item.delivery === "home" ? true : false}
                        />
                        <i class="fas fa-home"></i> Ship to Home
                      </label>
                    </div> -->
                  </div>
                  <div class="col-xl-3 col-lg-4 col-md-4 col-12 my-3 d-flex align-content-between flex-wrap">
                    <a href="<?php echo base_url(); ?>cart_remove/<?= $value->id; ?>" style="line-height: 2;" class="btn btn-danger remove-btn">Remove</a>
                    <label for="quantity">
                      Qty:
                      <input type="text" readonly name="quantity" class="form-control w-75" value="<?= $value->product_qty; ?>"  />
                    </label>
                    <h5>
                      Total: <span>$<?= number_format($value->total_price,2); ?> </span>
                    </h5>
                  </div>
                </div>
              <?php } ?>
            </div>
          </div>
          
          
          <div class="col-xl-3 col-lg-4 col-md-4 col-sm-12 col-10 mt-3" style=" background-color: white; border-radius: 6px;padding-top: 7px;">
            <a href="<?php echo base_url(); ?>categories" class="btn text-success">
              <i class="fas fa-cart-plus"></i> Continue Shopping
            </a>
            <div class="cart__address p-4">
              <h4 class="my-4"><i class="fas fa-truck"></i> Shipping</h4>
             
              <h5 class="my-3">  <?= $customer->billing_city ; ?> </h5>
              <p class="w-75 my-2">
              <?= $customer->billing_address ; ?>
              </p>
            </div>
            <div class="cart__summary px-4 py-4">
              <h5><i class="fas fa-cart-plus"></i> Order Summary</h5>
              <div class="d-flex justify-content-between my-4">
                <h6>Subtotal</h6>
                <h6>$<span class="cart-subtotal"><?= number_format($total,2); ?></span></h6>
              </div> 
              <div class="d-flex justify-content-between my-4">
                <?php 
                    $tax_amount  = 0;
                    if(isset($tax->tax) and $total != 0)
                    {
                      $tax_amount = $tax->tax/100*$total;
                    } 
                ?>

                <h6>Tax</h6>
                <h6>$<span class="cart-tax"><?php echo number_format($tax_amount,2); ?></span></h6>
              </div>
              <div class="d-flex justify-content-between my-4">
                <strong>Total</strong>
                <strong>$<span class="cart-total"><?= number_format($total + $tax_amount,2); ?></span></strong>
              </div>

              <a  href="<?php echo base_url(); ?>checkout" style="line-height:2;" class="btn btn-success w-100" >Checkout Out</a >
            </div>
          </div>
        </div>
    </div>
</section>
 