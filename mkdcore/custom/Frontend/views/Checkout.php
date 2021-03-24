<?php 
  $total      = 0;
  $sub_total  = 0;
  foreach($cart_items as $key => $value) 
  {  
    $total = $total + $value->total_price;  
  }

  $sub_total = $total;
?>
<style>
  .margin_top_label{
    margin-top: 6px;
  }
</style>

<?php echo form_open('',array('class' => 'send_checkout' )); ?>
  <section>
      <div class="container-fluid px-5 py-5">
        <div class="row justify-content-around">

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


          <div class="col-xl-8 col-lg-9 col-md-8 col-12 my-3">
            <!-- <div class="bg-white w-100 p-2 p-md-4">
              <h5>Sign in to Checkout</h5>
              <button class="btn btn-secondary bg-dark my-3">SIGN IN</button>

              <p class="text-muted my-3">or checkout as guest</p>
            </div> -->
            <div class="bg-white w-100 p-2 p-md-4">
              <h5>Personal Information</h5>
              <hr />
              <form action="" id="personal-information">
                <div class="form-row justify-content-between">
                  <div class="col-xl-5 col-lg-5 col-md-5 col-12 my-3">
                    <label for="name">Full Name</label>
                    <input
                      type="text"
                      name="full_name"
                      id="name1"
                      value="<?php echo set_value('full_name', $customer->name); ?>"
                      placeholder="Enter your full name"
                      class="form-control"
                    />
                  </div>
                  <div class="col-xl-5 col-lg-5 col-md-5 col-12 my-3">
                    <label for="email">Email Address</label>
                    <input
                      type="text"
                      name="email_address"
                      id="email1"
                      value="<?php echo set_value('email_address', $customer->email); ?>"
                      placeholder="abc@example.com"
                      class="form-control"
                    />
                  </div>
                  <div class="col-xl-5 col-lg-5 col-md-5 col-12 my-3">
                    <label for="number">Phone Number</label>
                    <input
                      type="text"
                      name="phone_number"
                      id="number"
                      placeholder="+123-456-789"
                      value="<?php echo set_value('phone_number', $customer->phone); ?>"
                      class="form-control"
                    />
                  </div>
                  <div class="col-xl-5 col-lg-5 col-md-5 col-12 my-3">
                    <label for="city">City</label>
                    <input
                      type="text"
                      name="city"
                      id="checkout-city"
                      value="<?php echo set_value('city', $customer->billing_city); ?>"
                      placeholder="Enter your city"
                      class="form-control"
                    />
                  </div>
                  <div class="col-xl-5 col-lg-5 col-md-5 col-12 my-3">
                    <label for="city">Postal Code</label>
                    <input
                      type="text"
                      name="postal_code"
                      id="checkout-postal_code"
                      value="<?php echo set_value('postal_code', $customer->billing_zip); ?>"
                      placeholder="Enter your postal code"
                      class="form-control"
                    />
                  </div>

                  <div class="col-xl-5 col-lg-5 col-md-5 col-12 my-3">
                    <label for="state">State</label>
                    <input
                      type="text"
                      name="state"
                      id="checkout-state"
                      value="<?php echo set_value('state', $customer->billing_state); ?>"
                      placeholder="Enter your State"
                      class="form-control"
                    />
                  </div>
                  <div class="col-xl-5 col-lg-5 col-md-5 col-12 my-3">
                    <label for="country">Country</label>
                    <input
                      type="text"
                      name="country"
                      id="checkout-country"
                      value="<?php echo set_value('country', "US"); ?>"
                      placeholder="Enter your Country"
                      class="form-control"
                    />
                  </div>
                  <div class="col-12 my-3">
                    <label for="address-1">Address 1</label>
                    <input
                      type="text"
                      name="address_1"
                      id="address-1"
                      value="<?php echo set_value('address_1', $customer->billing_address); ?>"
                      placeholder="Address here..."
                      class="form-control"
                    />
                  </div>
                  <div class="col-12 my-3">
                    <label for="address-2">Address 2 (Optional)</label>
                    <input
                      type="text"
                      name="address_2"
                      id="address-2"
                      value="<?php echo set_value('address_2'); ?>"
                      placeholder="Address here..."
                      class="form-control"
                    />
                  </div>
                </div>
              </form>
            </div>

            <div class="bg-white p-2 p-md-4 my-4">
              <h5>Choose Payment Method</h5>
              <div
                class="col-xl-6 col-lg-6 col-md-8 col-sm-8 col-12 d-flex justify-content-between"
              >
                <!-- <label for="cash">
                  <input
                    type="radio"
                    name="payment"
                    id="cash"
                    value="1"
                    class="mr-1 select_card"
                    required
                  />
                  Cash
                </label> -->

                <label for="cash">
                  <input
                    type="radio"
                    name="payment"
                    id="card"
                    value="2"
                    class="mr-1 select_card"
                    required
                  />
                  Credit Card 
                </label> 
              </div>
            </div>

          <div class="bg-white p-2 p-md-4 my-4 card_div" style="display:none;">
              <h5>Payment Details</h5>
              <hr /> 

               <div class="col-xl-12 col-lg-12 col-md-12 col-12 my-3">
                    <label for="number" class="margin_top_label">Account Number</label>
                    <input type="text" value="<?php echo set_value('number'); ?>"  name="number" id="number" placeholder="Enter Number" class="form-control" />
               </div>


               <div class="col-xl-12 col-lg-12 col-md-12 col-12 my-3">
                    <label for="exp_month" class="margin_top_label">Expiry Month</label> 
                    <select name="exp_month" id="exp_month"  class="form-control">
                         <option value="">Select Month</option> 
                         <option value="01">January</option> 
                         <option value="02">February</option> 
                         <option value="03">March</option> 
                         <option value="04">April</option> 
                         <option value="05">May</option> 
                         <option value="06">June</option> 
                         <option value="07">July</option> 
                         <option value="08">August</option> 
                         <option value="09">September</option> 
                         <option value="10">October</option> 
                         <option value="11">November</option> 
                         <option value="12">December</option> 
                    </select>
               </div>


               
               <div class="col-xl-12 col-lg-12 col-md-12 col-12 my-3">
                    <label for="exp_year" class="margin_top_label">Expiry Year</label>

                    <?php  
                    $year  = Date('Y');
                    $limit = $year + 25
                    ?>
                    <select name="exp_year" id="exp_year"  class="form-control">
                         <option value="">Select Year</option>
                         <?php for($i = $year; $i <= $limit ; $i++) {
                              echo "<option value='" . $i . "' > " . $i . " </option>";
                         } ?>
                    </select> 
               </div>


               <div class="col-xl-12 col-lg-12 col-md-12 col-12 my-3">
                    <label for="cvc" class="margin_top_label">CVC</label>
                    <input type="number" value="<?php echo set_value('cvc'); ?>"  pattern="/^-?\d+\.?\d*$/" onKeyPress="if(this.value.length==4) return false;" name="cvc" id="cvc" placeholder="Enter CVC" class="form-control" max-length="4" min-length="3" />
               </div> 
          </div>

            <div class="bg-white w-100 p-2 p-md-4">
              <h5>Your Order</h5>
              <hr />

              <div class="row">
                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-5 col-6 my-2">
                  <h5>Subtotal</h5>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-5 col-6 my-2">
                  <h5>$<?php echo number_format($sub_total,2); ?></h5>
                </div>
              </div>

              <div class="row">
                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-5 col-6 my-2">
                  <h5>Coupon</h5>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-5 col-6 my-2">
                  <input type="text" class="form-control coupon_code" name="coupon_code" /> 
                  <p class="coupon_success_coupon_error"></p>
                  <button class="btn btn-primary apply_coupon mb-2" type="button"> Apply Coupon </button>
                  <h5>$<span class="coupon_amount">0.00</span></h5>
                  <input type="hidden"  id="coupon_amount_now" value="0">
                </div>
              </div>


              <div class="row">
                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-5 col-6 my-2">
                  <h5>Shipping Cost</h5>
                </div>
                <div class="col-xl-8 col-lg-4 col-md-4 col-sm-5 col-6 my-2">
                  
                  <div class="shipping-cost-options">
                  </div>
                  <button class="btn btn-primary calculate-shipping-cost mb-2" type="button"> Calculate Shipping Cost </button>
                  <h5>$<span class="shipping_cost_selected">0.00</span></h5>
                </div>
              </div>
              <div class="row">
                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-5 col-6 my-2">
                  <h5>Tax</h5>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-5 col-6 my-2">
                <?php 
                    $tax_amount  = 0;
                    if(isset($tax->tax) and $total != 0)
                    {
                      $tax_amount = $tax->tax/100*$total;
                    }
                    $total = $total + $tax_amount;
                ?>
                  <h5 class="cart-tax">$<?php echo number_format($tax_amount,2); ?></h5>
                </div>
              </div>
              <div class="row">
                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-5 col-6 my-2">
                  <h4>Total</h4>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-5 col-6 my-2">  
                    <input type="hidden" value="<?php echo number_format($total,2); ?>" class="total_of_all" />
                    <input type="hidden" value="<?php echo number_format($tax_amount,2); ?>" class="tax_amount_val" />
                    <input type="hidden" value="<?php echo number_format($total-$tax_amount,2); ?>" class="total_without_tax" />
                  <h5>$<span class="total_of_all_text"><?php echo number_format($total,2); ?></span></h5>
                </div>
              </div>

              <div class="row mt-4 justify-content-between px-4">
                <label for="terms">
                  <input type="checkbox" required name="terms" id="terms1" class="mr-2" />
                  I’ve read and accept the terms & conditions
                </label>
                <button type="submit" class="btn btn-success">Place Order</button>
              </div>
            </div>
          </div>
          <div class="col-xl-3 col-lg-3 col-md-4 col-12 my-3">
            <div class="bg-white">
              <div class="cart__address p-4">
                <h4 class="my-4"><i class="fas fa-truck"></i> Shipping</h4>
                <h5 class="my-3"><?php echo $customer->shipping_state; ?></h5>
                <p class="w-75 my-2">
                  <?php echo $customer->shipping_address; ?>, <?php echo $customer->shipping_country; ?>
                </p>
              </div>
              <hr />
              <div class="cart__summary px-4 py-4">
                <h5><i class="fas fa-cart-plus"></i> Order Summary</h5>
                <div class="d-flex justify-content-between my-4">
                  <h6>Subtotal</h6>
                  <h6>$<span class="cart-subtotal"><?php echo number_format($sub_total,2); ?></span></h6>
                </div>
                <div class="d-flex justify-content-between my-4">
                  <h6>Shipping</h6>
                  <h6>$<span class="cart-shipping shipping_cost_selected">0.00</span></h6>
                </div>
                <div class="d-flex justify-content-between my-4">
                  <h6>Tax</h6>
                  <h6>$<span class="cart-tax"><?php echo number_format($tax_amount,2); ?></span></h6>
                </div>
                <div class="d-flex justify-content-between my-4">
                  <strong>Total</strong>
                  <strong>$<span class="cart-total total_of_all_text"><?php echo number_format($total,2); ?></span></strong>
                </div>
  
                 
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>


</form>