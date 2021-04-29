<link rel="stylesheet" type="text/css" href="./assets/css/checkout.css">
<style type="text/css">
     .custom-pricing-div{
          margin-right: 5px;
          text-align: start;
          padding-left: 113px;
          display: grid;
     }
     .add_card_div{
          display: none;
     }
</style>
<?php echo form_open('',array('class' => 'send_checkout' )); ?>
<section class="checkout-section" id="checkout-section">
     <div class="checkout-left">
          <div class="checkout-row">
               <div class="first-box">
                    <span>1</span>
                    <span>Shipping Address</span>
               </div>
               <div class="second-box">
                    <p id="msg_full_name"><?php echo $customer->name; ?></p>
                    <p id="msg_shipping_address"><?php echo $customer->shipping_address; ?></p>
                    <p> <span id="msg_shipping_city"><?php echo $customer->shipping_city; ?></span>, <span id="msg_shipping_state"><?php echo $customer->shipping_state; ?></span></p>
                    <p id="msg_shipping_zip"><?php echo $customer->shipping_zip; ?></p>
               </div>
               <div class="third-box">
                    <button  type="button"  class="dropdown-btn btn btn-secondary">change/add</button>
                    <div class="dropdown-box" style="overflow-y: auto;">
                         <div class="modal-container">
                              <div class="shipping-address">
                                   <div class="heading">Add shipping Address</div>
                                   <div class="inputs-container">
  
                                        <div>
                                             <label for="name">Full Name</label>
                                             <input  type="text" name="full_name" id="full_name"  value="<?php echo set_value('full_name', $customer->name); ?>" placeholder="Enter your full name"  />
                                        </div>


                                        <div>
                                             <label for="name">Email Address</label>
                                             <input  type="email" name="email_address" id="email_address"  value="<?php echo set_value('email_address', $customer->email); ?>" placeholder="abc@example.com"   />
                                        </div>
                                        

                                        <div>
                                             <label for="number">Phone Number</label>
                                             <input  type="text" name="phone_number" id="phone_number"  value="<?php echo set_value('phone_number', $customer->phone); ?>" placeholder="+123-456-789" />
                                        </div>


                                        <div>
                                             <label for="address">Address:</label>
                                             <input name="shipping_address" id="shipping_address"  value="<?php echo set_value('address_1', $customer->shipping_address); ?>" type="text" placeholder="your address" />
                                        </div>
                                        <div>
                                             <label for="country">Country:</label>
                                             <input name="shipping_country" id="shipping_country"  value="<?php echo set_value('country', $customer->shipping_country); ?>" type="text" placeholder="your country" />
                                        </div>
                                        
                                        <div>
                                             <label for="state">State:</label>
                                             <input name="shipping_state" id="shipping_state"  value="<?php echo set_value('state', $customer->shipping_state); ?>" type="text" placeholder="your state" />
                                        </div>

                                        <div>
                                             <label for="city">City:</label>
                                             <input name="shipping_city" id="shipping_city" value="<?php echo set_value('city', $customer->shipping_city); ?>" type="text" placeholder="your city" />
                                        </div>


                                        <div>
                                             <label for="zip-code">Zip-Code:</label>
                                             <input id="shipping_zip" name="shipping_zip" value="<?php echo set_value('postal_code', $customer->shipping_zip); ?>" type="text" placeholder="your zip-code" />
                                        </div>
                                   </div>
                              </div>

                              <div class="checkout-info-add-btn">
                                   <button type="button"  class="close-btn btn btn-secondary on_click_shipping_modal">Close</button>
                                   <button type="button" class="btn btn-primary add-shipping-address">Save</button>
                              </div>
                         </div>
                    </div>
               </div>
          </div>
          <div class="checkout-row">
               <div class="first-box">
                    <span>2</span>
                    <span>payment method</span>
               </div>
               <div class="second-box"> 
                    <div id="customer_card">
                         
                    </div>
                    <p>
                         <span>Billing Address:</span> 
                         <p id="msg_billing_address"><?php echo $customer->billing_address; ?></p>
                         <p> <span id="msg_billing_city"><?php echo $customer->billing_city; ?></span>, <span id="msg_billing_state"><?php echo $customer->billing_state; ?></span> </p>
                         <p><span id="msg_billing_zip"><?php echo $customer->billing_zip; ?></span></p> 
                    </p> 
               </div>
               <div class="third-box">
                    <button type="button" class="dropdown-btn btn btn-secondary">change/add</button>
                    <div class="dropdown-box" style="overflow-y: auto;">
                         <div class="modal-container">
                              <div class="payments-details">
                                   <div class="account-details">
                                        <div class="heading">Add Payment Details</div>
                                        <div class="inputs-container">  
                                             
                                             <div class="add_card_div" >
                                                  <label for="account-no">credit-card-no:</label>
                                                  <input name="number" id="account_no" type="text" placeholder="your account-no" />
                                             </div>
                                             <div class="add_card_div"  >
                                                  <label for="month">month:</label>
                                                  <select style="height: 60px;" name="exp_month" id="exp_month"  class="form-control">
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
                                             <div class="add_card_div"  >
                                                  <label for="year">year:</label>
                                                  <?php  
                                                  $year  = Date('Y');
                                                  $limit = $year + 25;
                                                  ?>
                                                  <select style="height: 60px;" name="exp_year" id="exp_year"  class="form-control">
                                                       <option value="">Select Year</option>
                                                       <?php for($i = $year; $i <= $limit ; $i++) {
                                                            echo "<option value='" . $i . "' > " . $i . " </option>";
                                                       } ?>
                                                  </select>
                                             </div>
                                             <div class="add_card_div"  >
                                                  <label for="CVC">CVC:</label>
                                                  <input pattern="/^-?\d+\.?\d*$/" onKeyPress="if(this.value.length==4) return false;" id="cvc_numb" name="cvc"  min-length="3" type="text" placeholder="your CVC" />
                                             </div> 
 
                                        </div>
                                   </div>

                                   <div class="billing-address">
                                        <div class="heading">Add Billing Address</div>
                                        <div class="inputs-container">
                                             <div>
                                                  <label for="address">Address:</label>
                                                  <input id="billing_address" name="billing_address"  value="<?php echo set_value('address_1', $customer->billing_address); ?>" type="text" placeholder="your address" />
                                             </div>
                                             <div>
                                                  <label for="country">Country:</label>
                                                  <input   id="billing_country" name="billing_country"  type="text" value="<?php echo set_value('country', "US"); ?>" placeholder="your country" />
                                             </div>
                                             
                                             <div>
                                                  <label for="state">State:</label>
                                                  <input  id="billing_state" name="billing_state"   value="<?php echo set_value('state', $customer->billing_state); ?>" type="text" placeholder="your state" />
                                             </div>

                                             <div>
                                                  <label for="city">City:</label>
                                                  <input  id="billing_city" name="billing_city"  value="<?php echo set_value('city', $customer->billing_city); ?>" type="text" placeholder="your city" />
                                             </div>


                                             <div>
                                                  <label for="zip-code">Zip-Code:</label>
                                                  <input name="billing_zip"  id="billing_zip"  value="<?php echo set_value('postal_code', $customer->billing_zip); ?>" type="text" placeholder="your zip-code" />
                                             </div>
                                        </div>
                                   </div>
                              </div>
                              <div class="checkout-info-add-btn">
                                   <button   type="button" style="margin-right: 39.5%;" class="add_new_card btn btn-primary ">Save</button>
 
                                   <button type="button"  class="close-btn btn btn-secondary on_click_billing_modal">Close</button>
                                   <button type="button"  class="btn btn-primary  add-billing-address">Save</button>
                              </div>
                         </div>
                    </div>
               </div>
          </div>
          <div class="checkout-row" id="review-and-shipping">
               <div class="first-box">
                    <span>3</span>
                    <p>review items & shipping</p>
               </div>
               <div class="box">
                    <div class="heading">
                         
                    </div>
                    <?php 
                    $total = 0;
                    foreach($cart_items as $key => $value)  { 
                         // print_r($value);
                         // die;
                         $total = $total + $value->total_price;    ?>

                         <div class="product">
                              <div class="image">

                                   <?php if(!empty($value->feature_image)){   ?>

                                        <img src="<?php echo $value->feature_image; ?>" alt="" height="100" width="100" alt="<?php echo $value->product_name; ?>" />
                                   <?php }else{ ?>
                                        <img src="/assets/frontend_images/noun_pallet_box_1675914.png" alt="" height="100" width="100" alt="<?php echo $value->product_name; ?>" />

                                   <?php } ?>

                              </div>
                              <div class="details">
                                   <h4><?php echo $value->product_name; ?></h4>
                                   <p>Details: <?php echo $value->description; ?></p>
                                   <p>Price: $<?php echo $value->total_price; ?></p>
                                   <div class="product-quantity">
                                        <p>Quantity:</p>
                                        <button data-id="<?php echo $value->product_id; ?>"  data-product_qty="<?php echo $value->product_qty; ?>"  type="button" class="btn btn-secondary add_to_cart_button">+</button>
                                        <span style="margin-left: 11px;"><?php echo $value->product_qty; ?></span>
                                        <button data-id="<?php echo $value->product_id; ?>" data-product_qty="<?php echo $value->product_qty; ?>" type="button"  class="btn btn-secondary minus_to_cart_button">-</button>
                                   </div>

                                    


                                   <div class="shipping-cost">
                                        <?php if ($value->can_ship == 2): ?>
                                             <div class="shipping-cost-options custom-pricing-div" style="margin-right: 5px;">

                                                  <input type="hidden" class="shipping-cost-name" name="shipping_cost_name_<?php echo $value->id; ?>" value="">

                                                  <input type="hidden" class="shipping-cost-price-value" name="shipping_cost_value_<?php echo $value->id; ?>" value="0">

                                                  <label><input name="shipping_service_id_<?php echo $value->id; ?>" class="mr-3 shipping-cost-change" type="radio" value="Local Pickup" data-other-cost="0" data-price="0" data-service-code="Local Pickup" data-service-name="Local Pickup">Local Pickup </label>

                                                  <label><input name="shipping_service_id_<?php echo $value->id; ?>" class="mr-3 shipping-cost-change" type="radio" value="Local Pickup No Shipping" data-other-cost="0" data-price="0" data-service-code="Local Pickup No Shipping" data-service-name="Local Pickup No Shipping">Local Pickup No Shipping </label>

                                                   
                                             </div>
                                        <?php else: ?>  

                                             <?php if ($value->free_ship == 1): ?>
                                                  <p>Free Shipping</p>

                                                  <p>$<span class="selected_item_shipping_cost">0.00</span></p>

                                                 
                                                  <button style="display: none;" type="button" data-id="<?php echo $value->id; ?>" class="btn btn-secondary calculate-shipping-cost">calculate</button>
                                             <?php else: ?>
                                                  <p>Shipping Cost:</p>
                                                  <p>$<span class="selected_item_shipping_cost">0.00</span></p>

                                                 
                                                  <button style="display: none;" type="button" data-id="<?php echo $value->id; ?>" class="btn btn-secondary calculate-shipping-cost">calculate</button>
                                             <?php endif; ?> 
                                        <?php endif; ?> 
                                   </div>


                                   <div class="shipping-cost-options custom-pricing-div"  > </div>
                              </div>
                         </div>

                    <?php  }  $sub_total = $total; ?> 
               </div>
          </div>
     </div>
     <div class="checkout-right">
          <div class="box">
               <?php 
               $tax_amount  = 0;
               if(isset($tax->tax) and $total != 0)
               {
                    $tax_amount = $tax->tax/100*$total;
               }

               $total = $total + $tax_amount;
               ?>
               <input type="hidden" value="<?php echo number_format($total,2); ?>" class="total_of_all" />
               <input type="hidden" value="<?php echo number_format($tax_amount,2); ?>" class="tax_amount_val" />
               <input type="hidden" value="<?php echo number_format($total-$tax_amount,2); ?>" class="total_without_tax" />

               <div class="summary">
                    <p>Order summary</p>
                    <div class="details">
                         <div>
                              <p>Items(<?php echo count($cart_items) ?>):</p>
                              <p>$<?php echo number_format($sub_total,2); ?></p>
                         </div>
                         <div>
                              <p>shipping & handling:</p>
                              <p>$ <span class="shipping_total_cost">0.00</span></p>
                         </div>
                         <div>
                              <p>Total before tax:</p>
                              <p>$<?php echo number_format($sub_total,2); ?></p>
                         </div>
                         <div>
                              <p>tax:</p>
                              <p >$<span class="cart-tax">0.00</span></p>
                         </div>
                         <div>
                              <p>order total:</p>
                              <p>$<span class="total_of_all_text"><?php echo number_format($total,2); ?></span></p>
                         </div>
                    </div>
               </div>
               <div class="header">
                    <label for="terms" style="margin-top: 13px;">
                        <input type="checkbox" required name="terms" id="terms1" class="mr-2" />
                        I’ve read and accept the <a href="">Terms & Conditions</a>
                    </label> 
                    <button class="btn btn-warning place-order-btn" type="button">Place your Order</button> 
               </div>
          </div>
     </div>
</section>
<form />

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

<script src="https://maps.googleapis.com/maps/api/js?key=<?php echo $google_api_key; ?>&libraries=places&callback=initialize"  async defer></script>


<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/address_form.js"></script>
