<link rel="stylesheet" type="text/css" href="/assets/css/checkout.css">
<style type="text/css">
     .custom-pricing-div {
          margin-right: 2px;
          text-align: start;
          padding-left: 25px;
          display: grid;
     }

     .add_card_div {
          display: none;
     }

     @media screen and (min-width:1351px) {

          .hide_modal_button {
               display: none !important;
          }

          .save_modal_button {
               display: block !important;
          }
     }

     @media screen and (max-width:1350px) {

          .hide_modal_button {
               display: block !important;
          }

          .save_modal_button {
               display: none !important;
          }
     }


     .child {
          order: 1;
     }

     .btn2-place_order {
          display: none !important;
     }

     @media (max-width:992px) {

          .topper {
               order: 0;
               margin-bottom: 30px;
          }

          .topper .box {
               width: 100% !important;
          }

          /*.box .header{
               position: fixed;
              bottom: 0;
              left: 0;
              right: 0;
              top: unset !important;
              z-index: 1000;
              background: #101010;
          }
          .box .header label{
               color: white;
               margin-left: 15px;
          }*/

          .btn2-place_order {
               display: block !important;
          }

          .btn1-place_order {
               display: none !important;
          }
     }


     /*@media (max-width:993px) {
          

          .btn2-place_order{
               display: none !important;
          }

          .btn1-place_order{
               display: block !important;
          }
     }*/
     .custom-shipping-div {
          display: -webkit-box;
          display: -ms-flexbox;
          display: flex;
          -webkit-box-align: center;
          -ms-flex-align: center;
          align-items: center;
          -webkit-box-pack: justify;
          -ms-flex-pack: justify;
          justify-content: space-between;
          margin: 1rem 0;
     }

     .required-must {
          color: red;
     }


     @media only screen and (max-width:566px) {

          .shipping-cost p {
               font-size: 10px;
          }
     }

     @media only screen and (max-width:450px) {

          .inputs-container div {
               display: block !important;
          }

          .inputs-container div label {
               width: 100% !important;
               text-align: center !important;
          }

          .inputs-container div input {
               width: 100% !important;
          }
     }

     .checkout-section .checkout-left .checkout-row .first-box {
          font-weight: bold;
          margin-right: 40px;
          min-width: 180px;
     }





     /*
     

     @media only screen and (min-width:1091px) {
           
          .show-text-only{
               white-space: nowrap;
               width: 475px;
               overflow: hidden;
               text-overflow: ellipsis;
          }
     }

     @media  only screen and (max-width:1090px) {
           
          .show-text-only{
               white-space: nowrap;
               width: 475px;
               overflow: hidden;
               text-overflow: ellipsis;
          }
     }

     @media  only screen and (max-width:767px) {
           
          .show-text-only{
               white-space: nowrap;
               width: 276px;
               overflow: hidden;
               text-overflow: ellipsis;
          }
     }

     @media only screen and (max-width: 495px)
     {*/
     .show-text-only {
          word-wrap: break-word;
     }

     /*}*/

     .second-box {
          width: 26%;
     }
</style>
<?php echo form_open('', array('class' => 'send_checkout')); ?>

<section class="checkout-section" id="checkout-section">

     <div class="checkout-left child p-0">

          <div class=" first-box d-flex flex-row-reverse justify-content-between">
               <a class="btn btn-secondary" href="/checkout">Back to Shipping</a>
          </div>

          <?php foreach ($checkout_data as $key => $item) : ?>
               <div class="checkout-row">
                    <div class="first-box">
                         <?php if (!empty($item->product->feature_image)) : ?>
                              <img src="<?php echo $item->product->feature_image ?>" alt="<?php echo $item->product_name ?>" height="100" width="100">
                         <?php else : ?>
                              <img src="/assets/frontend_images/noun_pallet_box_1675914.png" alt="<?php echo $item->product_name ?>" height="100" width="100">
                         <?php endif; ?>
                    </div>
                    <div class="second-box">
                         <p><?php echo $item->product_name ?></p>
                         <p>Qty: <?php echo $item->product_qty ?></p>
                         <p>Price: $<?php echo $item->unit_price ?></p>
                    </div>
                    <div class="third-box" style="width:250px">
                         <?php if (!empty($item->store)) : ?>
                              <p id="msg_full_name" class="font-weight-bold">Store Pickup</p>
                              <p id="msg_full_name"><?php echo $item->store->name; ?></p>
                              <p class="show-text-only" id="msg_shipping_address"><?php echo $item->store->address; ?></p>

                              <p id="msg_shipping_zip"><?php echo $item->store->city . " " . $item->store->state . " " . $item->store->zip; ?></p>
                              <a href="tel:<?php echo $item->store->phone; ?>">
                                   <p id="msg_shipping_zip"><?php echo $item->store->phone; ?></p>
                              </a>
                         <?php else : ?>
                              <p id="msg_full_name" class="font-weight-bold">Delivery</p>
                              <p id="msg_full_name"><?php echo $customer->name; ?></p>
                              <p class="show-text-only" id="msg_shipping_address"><?php echo $customer->shipping_address; ?></p>

                              <p id="msg_shipping_zip"><?php echo $customer->shipping_city . " " . $customer->shipping_state . " " . $customer->shipping_zip; ?></p>
                         <?php endif; ?>
                    </div>

               </div>
          <?php endforeach; ?>



          <div class="checkout-row" <?php echo ($has_shipping == true) ? '' : 'style="display:none"' ?>>
               <div class="first-box">
                    <!-- <span>1</span> -->
                    <span>Shipping Address</span>
               </div>
               <div class="second-box">
                    <p id="msg_full_name"><?php echo $customer->name; ?></p>
                    <p class="show-text-only" id="msg_shipping_address"><?php echo $customer->shipping_address; ?></p>
                    <p class="show-text-only">
                         <span id="msg_shipping_city"><?php echo $customer->shipping_city; ?></span>
                         <span id="shipping_coma" <?php if (empty($customer->shipping_state)) : ?> style="display: none;" <?php endif ?>>,</span>
                         <span id="msg_shipping_state"><?php echo $customer->shipping_state; ?></span>
                    </p>
                    <p id="msg_shipping_zip"><?php echo $customer->shipping_zip; ?></p>
               </div>


               <div class="third-box">
                    <!-- <button type="button" class="dropdown-btn btn btn-secondary shipping-btn ">change/add</button> -->
                    <div class="dropdown-box if_click_check" style="overflow-y: auto;">
                         <div class="modal-container">
                              <div class="payments-details">
                                   <div class="account-details">
                                        <div class="heading"><strong>Basic Information</strong></div>
                                        <div class="inputs-container">

                                             <div>
                                                  <label for="name">Full Name <span class="required-must">*</span></label>
                                                  <input type="text" name="full_name" id="full_name" value="<?php echo set_value('full_name', $customer->name); ?>" placeholder="Enter your full name" />
                                             </div>


                                             <div>
                                                  <label for="name">Email Address</label>
                                                  <input readonly="" style="background: #e9ecef" type="email" name="email_address" id="email_address" value="<?php echo set_value('email_address', $customer->email); ?>" placeholder="abc@example.com" />
                                             </div>


                                             <div>
                                                  <label for="number">Phone Number</label>
                                                  <input type="text" name="phone_number" id="phone_number" value="<?php echo set_value('phone_number', $customer->phone); ?>" placeholder="+123-456-789" />
                                             </div>
                                        </div>
                                   </div>

                                   <div class="billing-address">
                                        <div class="heading"><strong>Shipping Address</strong></div>
                                        <div class="inputs-container">
                                             <div class="custom-shipping-div">
                                                  <label for="address">Address <span class="required-must">*</span></label>
                                                  <input name="shipping_address" id="shipping_address" value="<?php echo set_value('address_1', $customer->shipping_address); ?>" type="search" placeholder="your address" />
                                             </div>
                                             <div class="custom-shipping-div">
                                                  <label for="country">Country <span class="required-must">*</span></label>
                                                  <input readonly="" style="background: #e9ecef" name="shipping_country" id="shipping_country" value="<?php echo set_value('country', $customer->shipping_country); ?>" type="text" placeholder="your country" />
                                             </div>

                                             <div class="custom-shipping-div">
                                                  <label for="state">State <span class="required-must">*</span></label>
                                                  <input name="shipping_state" id="shipping_state" value="<?php echo set_value('state', $customer->shipping_state); ?>" type="text" placeholder="your state" />
                                             </div>

                                             <div class="custom-shipping-div">
                                                  <label for="city">City <span class="required-must">*</span></label>
                                                  <input name="shipping_city" id="shipping_city" value="<?php echo set_value('city', $customer->shipping_city); ?>" type="text" placeholder="your city" />
                                             </div>


                                             <div class="custom-shipping-div">
                                                  <label for="zip-code">Zip-Code <span class="required-must">*</span></label>
                                                  <input id="shipping_zip" name="shipping_zip" value="<?php echo set_value('postal_code', $customer->shipping_zip); ?>" type="text" placeholder="your zip-code" />
                                             </div>
                                             <input type="hidden" id="address_type" name="address_type" value="<?php echo set_value('address_type', $customer->address_type); ?>">
                                        </div>
                                   </div>
                              </div>
                              <div class="checkout-info-add-btn">
                                   <button type="button" class="close-btn btn btn-secondary on_click_shipping_modal">Close</button>
                                   <button type="button" class="btn btn-primary add-shipping-address">Save</button>
                              </div>
                         </div>
                    </div>
               </div>
          </div>

          <?php if ($has_pickup == true) : ?>
               <?php foreach ($stores as $key => $store_data) : ?>
                    <!-- <div class="checkout-row">
               <div class="first-box">
                    <span>Pickup Address <?php echo $key > 0 ? $key + 1 : '' ?></span>
               </div>
               <div class="second-box">
                    <p id="msg_full_name"><?php echo $store_data->name; ?></p>
                    <p class="show-text-only" id="msg_shipping_address"><?php echo $store_data->address; ?></p>
                   
                    <p id="msg_shipping_zip"><?php echo $store_data->zip; ?></p>
                    <p id="msg_shipping_zip"><?php echo $store_data->phone; ?></p>
               </div>
               <div class="third-box">&nbsp;</div>
              
          </div> -->
               <?php endforeach; ?>
          <?php endif; ?>
          <div class="checkout-row">
               <div class="first-box">
                    <!-- <span>3</span> -->
                    <span>payment method</span>
               </div>
               <div class="second-box">
                    <div id="customer_card">

                    </div>
                    <p>
                         <span>Billing Address:</span>
                    <p class="show-text-only" id="msg_billing_address"><?php echo $customer->billing_address; ?></p>
                    <p class="show-text-only">
                         <span id="msg_billing_city"><?php echo $customer->billing_city; ?></span>
                         <span id="billing_coma" <?php if (empty($customer->billing_state)) : ?> style="display: none;" <?php endif ?>>,</span>
                         <span id="msg_billing_state"><?php echo $customer->billing_state; ?></span>
                    </p>
                    <p><span id="msg_billing_zip"><?php echo $customer->billing_zip; ?></span></p>
                    </p>
               </div>
               <div class="third-box">
                    <button type="button" class="dropdown-btn btn btn-danger">change/add <br>Credit Card</br></button>
                    <div class="dropdown-box" style="overflow-y: auto;">
                         <div class="modal-container">
                              <form>
                                   <div class="payments-details">
                                        <div class="account-details">
                                             <div class="heading"><strong>Add Payment Details</strong></div>
                                             <div class="inputs-container">

                                                  <div class="add_card_div">
                                                       <label for="account-no">credit-card-no <span class="required-must">*</span></label>
                                                       <input name="number" id="account_no" type="text" placeholder="your account-no" />
                                                  </div>
                                                  <div class="add_card_div">
                                                       <label for="month">month <span class="required-must">*</span></label>
                                                       <select style="height: 60px;" name="exp_month" id="exp_month" class="form-control">
                                                            <option value="">Select Month</option>
                                                            <option value="01">01 - January</option>
                                                            <option value="02">02 - February</option>
                                                            <option value="03">03 - March</option>
                                                            <option value="04">04 - April</option>
                                                            <option value="05">05 - May</option>
                                                            <option value="06">06 - June</option>
                                                            <option value="07">07 - July</option>
                                                            <option value="08">08 - August</option>
                                                            <option value="09">09 - September</option>
                                                            <option value="10">10 - October</option>
                                                            <option value="11">11 - November</option>
                                                            <option value="12">12 - December</option>
                                                       </select>
                                                  </div>
                                                  <div class="add_card_div">
                                                       <label for="year">year <span class="required-must">*</span></label>
                                                       <?php
                                                       $year  = Date('Y');
                                                       $limit = $year + 25;
                                                       ?>
                                                       <select style="height: 60px;" name="exp_year" id="exp_year" class="form-control">
                                                            <option value="">Select Year</option>
                                                            <?php for ($i = $year; $i <= $limit; $i++) {
                                                                 echo "<option value='" . $i . "' > " . $i . " </option>";
                                                            } ?>
                                                       </select>
                                                  </div>
                                                  <div class="add_card_div">
                                                       <label for="CVC">CVC <span class="required-must">*</span></label>
                                                       <input pattern="/^-?\d+\.?\d*$/" onKeyPress="if(this.value.length==4) return false;" id="cvc_numb" name="cvc" min-length="3" type="text" placeholder="your CVC" />
                                                  </div>


                                                  <div class="add_card_div">
                                                       <label for="card_default">Default </label>

                                                       <select style="height: 60px;" name="card_default" id="card_default" class="form-control">
                                                            <option value="1">Yes</option>
                                                            <option value="0">No</option>
                                                       </select>
                                                  </div>

                                                  <!-- <div class="add_card_div" style="float: right;">
                                                       <button type="button" style="margin-right: 39.5%;" class="hide_modal_button add_new_card btn btn-primary ">Save</button>
                                                  </div> -->


                                             </div>
                                        </div>

                                        <div class="billing-address">
                                             <div class="heading"><strong>Billing Address</strong></div>
                                             <div class="inputs-container">
                                                  <div>
                                                       <label for="address">Address <span class="required-must">*</span></label>
                                                       <input id="billing_address" name="billing_address" value="<?php echo set_value('address_1', $customer->billing_address); ?>" type="search" placeholder="your address" />
                                                  </div>
                                                  <div>
                                                       <label for="country">Country <span class="required-must">*</span></label>
                                                       <input readonly="" style="background: #e9ecef" id="billing_country" name="billing_country" type="text" value="<?php echo set_value('country', "US"); ?>" placeholder="your country" />
                                                  </div>

                                                  <div>
                                                       <label for="state">State </label>
                                                       <input id="billing_state" name="billing_state" value="<?php echo set_value('state', $customer->billing_state); ?>" type="text" placeholder="your state" />
                                                  </div>

                                                  <div>
                                                       <label for="city">City </label>
                                                       <input id="billing_city" name="billing_city" value="<?php echo set_value('city', $customer->billing_city); ?>" type="text" placeholder="your city" />
                                                  </div>


                                                  <div>
                                                       <label for="zip-code">Zip-Code <span class="required-must">*</span></label>
                                                       <input name="billing_zip" id="billing_zip" value="<?php echo set_value('postal_code', $customer->billing_zip); ?>" type="text" placeholder="your zip-code" />
                                                  </div>
                                             </div>
                                        </div>
                                        <div class="checkout-info-add-btn">
                                             <!-- <div style="float: left;margin-left: 40.5%;">
                                        <button type="button" class="save_modal_button add_new_card btn btn-primary ">Save</button>
                                        
                                   </div> -->
                                             <button type="button" class="close-btn btn btn-secondary on_click_billing_modal">Close</button>
                                             <!-- <button type="button" class="close-btn btn billing_and_card">Save</button> -->
                                             <button type="button" class="btn btn-primary add_billing_and_card">Save</button>
                                             <!-- add-billing-address -->
                                        </div>
                                   </div>
                              </form>

                         </div>
                    </div>
               </div>
          </div>

     </div>
     <div class="checkout-right child topper mt-2">
          <div class="box mt-5">
               <?php
               $tax_amount  = 0;
               if (isset($tax->tax) and $total != 0) {
                    $tax_amount = $tax->tax / 100 * $total;
               }
               $sub_total = $total;
               $total = $total + $tax_amount;
               $grand_total = $total + $total_shipping;
               ?>
               <input type="hidden" value="<?php echo number_format($total, 2); ?>" class="total_of_all" />
               <input type="hidden" value="<?php echo number_format($tax_amount, 2); ?>" class="tax_amount_val" />
               <input type="hidden" value="<?php echo number_format($total - $tax_amount, 2); ?>" class="total_without_tax" />

               <div class="header btn2-place_order  mb-3">
                    <button style="    margin-top: 0px;" class="btn btn-warning place-order-btn btn2-place_order" type="button">Place your Order</button>
               </div>

               <div class="summary">
                    <p>Order summary</p>
                    <div class="details">
                         <div>
                              <p>Line Items(<?php echo count($checkout_data) ?>):</p>
                              <p>$<span class="sub_total_value"><?php echo number_format($sub_total, 2); ?></span></p>
                         </div>
                         <div>
                              <p>shipping & handling:</p>
                              <p>$ <span class="shiping_total_cost"><?php echo $total_shipping ?> </span></p>
                         </div>
                         <div>
                              <p>Total before tax:</p>
                              <p>$<span class="total_without_tax_value"><?php echo number_format($sub_total, 2); ?></span></p>
                         </div>
                         <div>
                              <p>tax:</p>
                              <p>$<span class="cart-tx"><?php echo number_format($tax_amount, 2); ?></span></p>
                         </div>
                         <div>
                              <p>Order total:</p>
                              <p>$<span class="total_of_all_"><?php echo number_format($grand_total, 2); ?></span></p>
                         </div>

                    </div>
               </div>
               <div class="header">
                    <label for="terms" style="margin-top: 13px;">
                         <input type="checkbox" required name="terms" id="terms1" class="mr-2" />
                         I’ve read and accept the <a target="_blank" href="<?php echo base_url(); ?>terms_and_conditions">Terms & Conditions</a>
                         <br>
                         <input type="checkbox" required name="sales_are_final" id="sales_are_final" class="mr-2" />
                         All sales are final. Product is sold As-Is.
                    </label>
                    <button class="btn btn-warning place-order-btn btn1-place_order" type="button">Place your Order</button>
               </div>
          </div>
     </div>
</section>
</form>

<script>
     const checkoutBtns = document.querySelectorAll('.dropdown-btn');
     const modalBox = document.querySelectorAll('.dropdown-box');
     const closeBtns = document.querySelectorAll('.close-btn');

     checkoutBtns.forEach((btn) => {
          btn.addEventListener('click', (e) => {
               e.target.nextElementSibling.classList.add('active');
          });
     });
     modalBox.forEach((modal) => {
          modal.addEventListener('click', (e) => {
               if (e.target.classList.contains('active') && e.target.classList.contains('dropdown-box')) {
                    e.target.classList.remove('active');
               }
          });
     });

     closeBtns.forEach((btn) => {
          btn.addEventListener('click', (e) => {
               modalBox.forEach((modal) => {
                    modal.classList.remove('active');
               });
          });
     });
</script>


<?php $google_api_key = $this->config->item('google_api_key'); ?>

<script src="https://maps.googleapis.com/maps/api/js?key=<?php echo $google_api_key; ?>&libraries=places&callback=initialize" async defer></script>


<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/address_form.js"></script>