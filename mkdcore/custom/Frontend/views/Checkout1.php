<link rel="stylesheet" type="text/css" href="./assets/css/checkout.css">
<style type="text/css">
     .custom-pricing-div{
          margin-right: 2px;
          text-align: start;
          padding-left: 25px;
          display: grid;
     }
     .add_card_div{
          display: none;
     }

     @media  screen and (min-width:1351px){

          .hide_modal_button{
               display: none !important;
          }
          .save_modal_button{
               display: block !important;
          }
     }

     @media  screen and (max-width:1350px){

          .hide_modal_button{ 
               display: block !important;
          }
          .save_modal_button{
               display: none !important;
          }
     }


     .child {  
          order:1;
     }

     .btn2-place_order{
           display: none !important;
          }

     @media (max-width:992px) {
           
          .topper { 
               order:0; 
               margin-bottom: 30px;
          }
          .topper .box{
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

          .btn2-place_order{
               display: block !important;
          }

          .btn1-place_order{
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
     .custom-shipping-div{
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
     .required-must{
          color: red;
     }


     @media only screen and (max-width:566px) {
           
          .shipping-cost p{
               font-size: 10px;
          }
     } 

     @media only screen and (max-width:450px) {
           
          .inputs-container div{
               display: block !important;
          }

          .inputs-container div label{
               width: 100% !important;
               text-align: center !important;
          }
          .inputs-container div input{
               width: 100% !important;
          }
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

     .second-box{
          width: 26%;
     }
     
</style> 
<?php echo form_open('/checkout/step_2',array('class' => 'send_checkout_1', 'onsubmit' => 'validateForm()', 'id' => 'checkout_form_1', 'data-items' => count($cart_items) )); ?>
<section class="checkout-section" id="checkout-section">
     <div class="checkout-left child ">
         
         <?php

//print_r($cart_items);
          ?>

          <div class="checkout-row p-0" id="review-and-shipping">
               <div class="first-box justify-content-between">
                    <p>Review items & shipping</p>
                    <a class="btn btn-secondary right" href="/cart"> Back to Cart </a>
               </div>
               <div class="box">
                    <div class="heading">
                         
                    </div>
                    <?php 
                    $total = 0;
                    foreach($cart_items as $key => $value)  { 
                         // print_r($value);
                         // die;
                         // $total = $total + $value->total_price ;   
                         $total = $total + ($value->unit_price * $value->product_quantity) ;    ?>

                         <input type="hidden" name="product_id[]" value ="<?php echo $value->product_id; ?>">
                         <input type="hidden" name="product_name[]" value ="<?php echo $value->product_name; ?>">
                         <input type="hidden" name="product_quantity[]" value ="<?php echo $value->product_quantity; ?>">
                         <input type="hidden" name="unit_price[]" value ="<?php echo $value->unit_price; ?>">

                          <input type="hidden" name="is_pickup[]" id="pickup_<?php echo $key; ?>" value = "<?php echo $value->can_ship == 3 ? 'false' : 'true'; ?>">
                         <div class="product border shadow p-2">
                              
                              <div class="image">
                                   <p>Item: <?php echo $key+1; ?></p>

                                   <?php if(!empty($value->feature_image)){   ?>

                                        <img src="<?php echo $value->feature_image; ?>" alt="" height="100" width="100" alt="<?php echo $value->product_name; ?>" />
                                   <?php }else{ ?>
                                        <img src="/assets/frontend_images/noun_pallet_box_1675914.png" alt="" height="100" width="100" alt="<?php echo $value->product_name; ?>" />

                                   <?php } ?>

                              </div>
                              <div class="details ">
                                   <h4><?php echo $value->product_name; ?></h4>
                                   <p>Details: <?php echo $value->description; ?></p>
                                   <p>Price: $<span class="current_item_total_price"><?php echo $value->unit_price * $value->product_quantity; ?></span></p>
                                   <p>Weight: <span class=""><?php echo $value->item_data->weight * $value->product_quantity; ?></span> lbs</p>
                                   <div class="product-quantity">
                                        <p>Quantity:</p>
                                        <!-- <button data-id="<?php echo $value->product_id; ?>"  data-product_qty="<?php echo $value->product_quantity; ?>"  type="button" class="btn btn-secondary add_to_cart_button_checkout">+</button> -->
                                        <span class="quantity_for_item" style="margin-left: 11px;"><?php echo $value->product_quantity; ?></span>
                                        <!-- <button data-id="<?php echo $value->product_id; ?>" data-product_qty="<?php echo $value->product_quantity; ?>" type="button"  class="btn btn-secondary minus_to_cart_button">-</button> -->
                                   </div>

                                   <div class="d-flex flex-column flex-xl-row  ">
                                        <?php if ($value->can_ship != 3 /* Shipping only */): ?>
                                        <div class=" mr-2 p-2 pt-0 position-relative mt-2 " role="button" style="border-style:solid; border-width:5px; width:300px; min-height:150px" onclick="toggleToPickUp('<?php echo $key ?>')">
                                             <span style="border-style:solid; border-width:5px; position:absolute; top:0; right:0;" class=" p-0 m-0 text-white bg-dark border-dark" id="pickup_tick_<?php echo $key; ?>">&#10004;</span>
                                             <h6>PICKUP AT </h6>
                                             <p><?php echo $store_data->address ?></p>
                                             <p><?php echo $store_data->state." ".$store_data->zip. " ".$store_data->phone ?></p>
                                        </div>
                                        <?php endif ; ?>

                                        <?php if ($value->can_ship != 2 || $value->can_ship_approval == 1): ?>
                                        <div class="  position-relative p-2 mt-2" role="button" style="border-style:solid; border-width:5px; width:300px; min-height:150px " onclick="toggleToShipTo('<?php echo $key ?>')">
                                        <span class="text-white bg-dark border-dark" style="display:none; border-style:solid; border-width:5px; position:absolute; top:0; right:0;" id="ship_to_tick_<?php echo $key; ?>">&#10004;</span>
                                             <h6>SHIP TO </h6>
                                             <p id="msg_full_name" class="msg-full-name"><?php echo $customer->name; ?></p>
                                             <p class="show-text-only" id="msg_shipping_address" class="msg-shipping-address"><?php echo $customer->shipping_address; ?></p>
                                             <p class="show-text-only" > 
                                                  <span id="msg_shipping_city" class="msg-shipping-city"><?php echo $customer->shipping_city; ?></span>
                                                  <span id="shipping_coma"  
                                                       <?php if (empty($customer->shipping_state)): ?> 
                                                            style="display: none;" 
                                                       <?php endif ?> >,</span>   
                                                  <span id="msg_shipping_state" class="msg-shipping-state"><?php echo $customer->shipping_state; ?></span>
                                             </p>
                                             <p id="msg_shipping_zip" class="msg-shipping-zip"><?php echo $customer->shipping_zip; ?></p>
                                        </div>
                                        <?php endif ; ?>
                                   </div>

                                  
                                    

                                   <div class="shipping-cost"  style="display:none" data-shipping-box="<?php echo $key ?>">
                                        <?php if ($value->can_ship == 2 && $value->can_ship_approval == 2): ?>
                                             <div class="shipping-cost-options custom-pricing-div" style="margin-right: 5px;">

                                                  <input type="hidden" class="shipping-cost-name" name="shipping_cost_name_<?php echo $value->id; ?>" value="">

                                                  <input type="hidden" class="shipping-cost-price-value" name="shipping_cost_value_<?php echo $value->id; ?>" value="0">

                                                  <label><input name="shipping_service_id_<?php echo $value->id; ?>" class="mr-3 shipping-cost-change" type="radio" value="Local Pickup" data-other-cost="0" data-price="0" data-service-code="Local Pickup" data-service-name="Local Pickup">Local Pickup </label>

                                                  <label><input name="shipping_service_id_<?php echo $value->id; ?>" class="mr-3 shipping-cost-change" type="radio" value="Local Pickup No Shipping" data-other-cost="0" data-price="0" data-service-code="Local Pickup No Shipping" data-service-name="Local Pickup No Shipping">Local Pickup No Shipping </label>

                                                   
                                             </div>
                                        <?php else: ?>  

                                             <?php if ($value->free_ship == 1): ?>
                                                  <p>Shipping:</p>

                                                  <p>$<span class="selected_item_shipping_cost">0.00</span></p>

                                                  
                                                  <p><strong>Expected Delivery Date : <span class="selected_item_expected_shipping_date">N/A</span></strong></p>
                                                   
                                                  <button style="display: none;" type="button" data-key="<?php echo $key; ?>" data-quantity="<?php echo $value->product_quantity; ?>" data-id="<?php echo $value->id; ?>" class="btn btn-secondary calculate-shipping-cost">calculate</button>
                                             <?php else: ?>
                                                  <p>Shipping Cost:</p> 
                                                  <p>$<span class="selected_item_shipping_cost">0.00</span></p>

                                                  <p><strong>Expected Delivery Date : <span class="selected_item_expected_shipping_date">N/A</span></strong></p>

                                                  <button style="display: none;" type="button" data-id="<?php echo $value->id; ?>" class="btn btn-secondary calculate-shipping-cost">calculate</button>
                                             <?php endif; ?> 
                                        <?php endif; ?> 
                                   </div>


                                   <div class="shipping-cost-options custom-pricing-div" style="display:none" data-shipping-options="<?php echo $key ?>" > </div>
                              </div>
                         </div>

                    <?php  }  $sub_total = $total; ?> 
               </div>
          </div> 
          <div class="checkout-row border-0" >
               <!-- <div class="first-box">
                    <span>1</span>
                    <span>Shipping Address</span>
               </div>
               <div class="second-box">
                    <p id="msg_full_name"><?php echo $customer->name; ?></p>
                    <p class="show-text-only" id="msg_shipping_address"><?php echo $customer->shipping_address; ?></p>
                    <p class="show-text-only" > 
                         <span id="msg_shipping_city"><?php echo $customer->shipping_city; ?></span>
                         <span id="shipping_coma"  
                              <?php if (empty($customer->shipping_state)): ?> 
                                   style="display: none;" 
                              <?php endif ?> >,</span>   
                         <span id="msg_shipping_state"><?php echo $customer->shipping_state; ?></span>
                    </p>
                    <p id="msg_shipping_zip"><?php echo $customer->shipping_zip; ?></p>
               </div> -->
              

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
                                                  <input  type="text" name="full_name" id="full_name"  value="<?php echo set_value('full_name', $customer->name); ?>" placeholder="Enter your full name"  />
                                             </div>


                                             <div>
                                                  <label for="name">Email Address</label>
                                                  <input  readonly=""  style="background: #e9ecef"   type="email" name="email_address" id="email_address"  value="<?php echo set_value('email_address', $customer->email); ?>" placeholder="abc@example.com"   />
                                             </div>
                                             

                                             <div>
                                                  <label for="number">Phone Number</label>
                                                  <input  type="text" name="phone_number" id="phone_number"  value="<?php echo set_value('phone_number', $customer->phone); ?>" placeholder="+123-456-789" />
                                             </div>                                            
                                        </div>
                                   </div>

                                   <div class="billing-address">
                                        <div class="heading"><strong>Shipping Address</strong></div>
                                        <div class="inputs-container">
                                             <div class="custom-shipping-div">
                                                  <label for="address">Address <span class="required-must">*</span></label>
                                                  <input name="shipping_address" id="shipping_address"  value="<?php echo set_value('address_1', $customer->shipping_address); ?>" type="search" placeholder="your address" />
                                             </div>
                                             <div class="custom-shipping-div">
                                                  <label for="country">Country <span class="required-must">*</span></label>
                                                  <input readonly=""  style="background: #e9ecef" name="shipping_country" id="shipping_country"  value="<?php echo set_value('country', $customer->shipping_country); ?>" type="text" placeholder="your country" />
                                             </div>
                                             
                                             <div class="custom-shipping-div">
                                                  <label for="state">State <span class="required-must">*</span></label>
                                                  <input name="shipping_state" id="shipping_state"  value="<?php echo set_value('state', $customer->shipping_state); ?>" type="text" placeholder="your state" />
                                             </div>

                                             <div class="custom-shipping-div">
                                                  <label for="city">City <span class="required-must">*</span></label>
                                                  <input name="shipping_city" id="shipping_city" value="<?php echo set_value('city', $customer->shipping_city); ?>" type="text" placeholder="your city" />
                                             </div class="custom-shipping-div">


                                             <div>
                                                  <label for="zip-code">Zip-Code <span class="required-must">*</span></label>
                                                  <input id="shipping_zip" name="shipping_zip" value="<?php echo set_value('postal_code', $customer->shipping_zip); ?>" type="text" placeholder="your zip-code" />
                                             </div>
                                             <input type="hidden" id="address_type" name="address_type" value="<?php echo set_value('address_type', $customer->address_type); ?>">
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
     </div>

     <!-- ORDER SURMARY -->
     <div class="checkout-right child topper mt-2">
          <div class="box mt-5">
               <?php 
               $tax_amount  = 0;
               if(isset($tax->tax) and $total != 0)
               {
                    $tax_amount = $tax->tax/100*$total;
               }

               $total = $total + $tax_amount;
               ?>
               <input type="hidden" value="<?php echo number_format($total,2); ?>" class="total_of_all" id=total_of_all />
               <input type="hidden" value="<?php echo number_format($tax_amount,2); ?>" class="tax_amount_val" id="tax_amount_val" />
               <input type="hidden" value="<?php echo number_format($total-$tax_amount,2); ?>" class="total_without_tax" id= "total_without_tax" />

            

               <div class=" d-flex justify-content-between">
                    <p class="font-weight-bold">Shipping Details </p>
                    <button type="button" class="dropdown-btn btn btn-secondary shipping-btn btn-secondary ">Update</button>
               </div>
               <div class="">
                    <p id="msg_full_name"><?php echo $customer->name; ?></p>
                    <p class="show-text-only" id="msg_shipping_address"><?php echo $customer->shipping_address; ?></p>
                    <p class="show-text-only" > 
                         <span id="msg_shipping_city"><?php echo $customer->shipping_city; ?></span>
                         <span id="shipping_coma"  
                              <?php if (empty($customer->shipping_state)): ?> 
                                   style="display: none;" 
                              <?php endif ?> >,</span>   
                         <span id="msg_shipping_state"><?php echo $customer->shipping_state; ?></span>
                    </p>
                    <p id="msg_shipping_zip"><?php echo $customer->shipping_zip; ?></p>
               </div>

               <div class="summary pt-2">
                    <p>Order Summary</p>
                    <div class="order-details">
                         <div class="d-flex justify-content-between">
                              <p>Items(<?php echo count($cart_items) ?>):</p>
                              <p>$<span class="sub_total_value"><?php echo number_format($sub_total,2); ?></span></p>
                         </div>
                         <div class="mt-1">
                              <!-- <p>Shipping & Handling:</p> -->
                              <p>&nbsp;</p>
                              <!-- <p>$ <span class="shipping_total_cost">0.00</span></p> -->

                              
                              <!-- <p>$ <span class="shipping_total_cost">0.00</span></p> -->

                         </div>
                         
                         <?php foreach($cart_items as $key => $value) :?>    
                         <div class="justify-content-between" style="display:none" id="shipping_item_price_label_<?php echo $key; ?>">
                                   <span> Item <?php echo $key+1 ?> Shipping:</span>
                                   <span>$<span  id = "shipping_cost_label_<?php echo $key; ?>">0.00</span></span>

                                   <input type="hidden"  class="shipping_cost_input" name="shipping_costs[]" id="shipping_cost_<?php echo $key; ?>">
                                   <input type="hidden"  class="shipping_service_input" name="shipping_service[]" id="shipping_service_<?php echo $key; ?>">
                                   <input type="hidden"  class="shipping_service_name_input" name="shipping_service_name[]" id="shipping_service_name_<?php echo $key; ?>">
                                   <!-- </br> -->
                         </div>
                         <?php endforeach ; ?>

                         
                         <div class="d-flex justify-content-between mt-1">
                              <p>Total Shipping:</p>
                              <p>$<span id="total_shipping">0</span></p>
                              <input type="hidden" id="total_shipping_cost" name="total_shipping_cost">
                         </div>
                         <div class="d-flex justify-content-between">
                              <p>Total Before Tax:</p>
                              <p>$<span class="total_without_tax_value"><?php echo number_format($sub_total,2); ?></span></p>
                         </div>
                         <div class="d-flex justify-content-between">
                              <p>Tax:</p>
                              <p >$<span class="cart-tax"><?php echo number_format($tax_amount,2); ?></span></p>
                         </div>
                         <div class="d-flex justify-content-between">
                              <p>Order Total:</p>
                              <p>$<span class="total_of_all_text" id="grand_total_text"><?php echo number_format($total,2); ?></span></p>
                              <input type="hidden" id="grand_total" name="grand_total" value="<?php echo number_format($total,2); ?>" >
                         </div>
                    </div>
               </div>
               <div class="header">
                    <!-- <label for="terms" style="margin-top: 13px;">
                        <input type="checkbox" required name="terms" id="terms1" class="mr-2" />
                        I’ve read and accept the <a  target="_blank" href="<?php echo base_url(); ?>terms_and_conditions">Terms & Conditions</a>
                        <br>
                        <input type="checkbox" required name="sales_are_final" id="sales_are_final" class="mr-2" />
                        All sales are final. Product is sold As-Is.
                    </label>  -->
                    <button class="btn btn-warning" type="submit">Proceed to Payment</button> 
                    <!-- <button class="btn btn-warning place-order-btn btn1-place_order" type="button">Proceed to Payment</button>  -->
               </div>
          </div>
     </div>
</section>
</form >

<script>
     const checkoutBtns = document.querySelectorAll('.dropdown-btn');
     const modalBox = document.querySelectorAll('.dropdown-box');
     const closeBtns = document.querySelectorAll('.close-btn');

     checkoutBtns.forEach((btn) => {
          btn.addEventListener('click', (e) => {
               if (e.target.classList.contains('dropdown-btn')) {
                    document.querySelector('.dropdown-box').classList.add('active');
               }
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

    

     // HIDE SHIPPING OPTIONS
     // document.querySelectorAll('shipping-cost')

     function toggleToPickUp(key){
          let pickup_flag = document.querySelector(`#pickup_${key}`);
          let pickup_tick = document.querySelector(`#pickup_tick_${key}`);
          let ship_to_tick = document.querySelector(`#ship_to_tick_${key}`);
          let shipping_box = document.querySelector(`[data-shipping-box= "${key}"]`);
          let shipping_options = document.querySelector(`[data-shipping-options= "${key}"]`);
          // Toggle UI
          ship_to_tick.style.display = "none";
          pickup_tick.style.display = "inline";
          shipping_box.style.display = "none";
          shipping_options.style.display = "none";


          // update flag
          pickup_flag.value = "true";

          // remove shipping cost for this item
          let shipping_item_label = document.querySelector(`#shipping_cost_label_${key}`);
          let shipping_item_price_label = document.querySelector(`#shipping_item_price_label_${key}`);
          let shipping_item_input = document.querySelector(`#shipping_cost_${key}`);
          shipping_item_price_label.style.display = "none";
          shipping_item_label.innerHTML = `0.00`;
          shipping_item_input.value = ``;


          sumShipping();

     }
     function toggleToShipTo(key){
          let pickup_flag = document.querySelector(`#pickup_${key}`);
          let pickup_tick = document.querySelector(`#pickup_tick_${key}`);
          let ship_to_tick = document.querySelector(`#ship_to_tick_${key}`);
          let shipping_box = document.querySelector(`[data-shipping-box= "${key}"]`);                    
          let shipping_options = document.querySelector(`[data-shipping-options= "${key}"]`);   
          // Toggle UI   
          if(shipping_options.style.display == "block"){
               return;
          }
          ship_to_tick.style.display = "inline";
          if(pickup_tick){
               pickup_tick.style.display = "none";
          }
          shipping_box.style.display = "block";
          shipping_options.style.display = "block";

          // update flag
          pickup_flag.value = "false";

          // remove previously checked option
          let shipping_options_radio = document.querySelectorAll(`[type="radio"][data-key="${key}"]`);
          for(let i = 0; i < shipping_options_radio.length; i++){
               if(shipping_options_radio[i].checked == true){
                    shipping_options_radio[i].checked = false;
               }
          }
          // remove shipping cost for this item so user can select their choice
          let shipping_item_label = document.querySelector(`#shipping_cost_label_${key}`);
          let shipping_item_input = document.querySelector(`#shipping_cost_${key}`);
          shipping_item_label.innerHTML = `0.00`;
          shipping_item_input.value = ``;
          let shipping_item_price_label = document.querySelector(`#shipping_item_price_label_${key}`);
          shipping_item_price_label.style.display = "flex";

          sumShipping();
     }

     function updateShippingTotal(key){
          let selected_shipping = document.querySelector(`[type="radio"][data-key="${key}"]:checked`)
          // let key = parseInt(selected_shipping.getAttribute('data-key'));
          let total_cost = selected_shipping.getAttribute('data-total-cost');
          let service = selected_shipping.getAttribute('data-service-code');
          let service_name = selected_shipping.getAttribute('data-service');

          let shipping_item_label = document.querySelector(`#shipping_cost_label_${key}`);
          let shipping_item_input = document.querySelector(`#shipping_cost_${key}`);
          let shipping_service_input = document.querySelector(`#shipping_service_${key}`);
          let shipping_service_name_input = document.querySelector(`#shipping_service_name_${key}`);
          let shipping_item_price_label = document.querySelector(`#shipping_item_price_label_${key}`);
          shipping_item_label.innerHTML = `${total_cost}`;
          shipping_item_input.value = total_cost;
          shipping_service_input.value = service;
          shipping_service_name_input.value = service_name;

          let pickup_flag = document.querySelector(`#pickup_${key}`);
          shipping_item_price_label.style.display = pickup_flag.value == "false" ? "flex": "none";


          sumShipping();
          
     }

     function sumShipping(){
          let shipping_costs = document.querySelectorAll('.shipping_cost_input');
          let total_shipping_cost = document.querySelector("#total_shipping");
          let total_shipping_cost_input = document.querySelector("#total_shipping_cost");

          let sum = 0;
          for(let i = 0; i < shipping_costs.length; i++){
               if(shipping_costs[i].value == '' || isNaN(parseFloat(shipping_costs[i].value))) continue;
               sum =   sum +  parseFloat(shipping_costs[i].value);
          }
          total_shipping_cost.innerHTML = sum.toFixed(2);
          total_shipping_cost_input.value = sum;

          // Generate grand total
          sumAll();
     }

     function sumAll(){
          console.log('summing all')
          let total_shipping_cost = document.querySelector("#total_shipping_cost");
          let total_with_tax = document.querySelector('#total_of_all');
          let grand_total = document.querySelector('#grand_total');
          let grand_total_text = document.querySelector('#grand_total_text');

          let total_shipping = isNaN(parseFloat(total_shipping_cost.value)) || total_shipping_cost.value == ''? 0 : parseFloat(total_shipping_cost.value.replaceAll(',',''));
          let total_with_tax_value = isNaN(parseFloat(total_with_tax.value)) || total_with_tax.value == ''? 0 : parseFloat(total_with_tax.value.replaceAll(',',''));
          let sum = total_shipping + total_with_tax_value;
          grand_total.value = sum;
          grand_total_text.innerHTML = sum.toFixed(2);

     }

     

      // Add listener to shipping options

      function validateForm(){
          event.preventDefault(); 
          let items = event.target.getAttribute('data-items');

          let pickup_input = null;
          let pickup_shipping = null;
          for(let i = 0; i < items; i++){

               pickup_input = document.querySelector(`#pickup_${i}`);
               pickup_shipping = document.querySelectorAll(`[type="radio"][data-key="${i}"]:checked`);

               if(pickup_input.value == 'false' && pickup_shipping.length < 1){
                    toastr.error('Please select a shipping option For all items to be shipped.');
                    return;
               }
          }

          event.target.submit();

      }
     



</script>


<?php $google_api_key = $this->config->item('google_api_key'); ?>

<script src="https://maps.googleapis.com/maps/api/js?key=<?php echo $google_api_key; ?>&libraries=places&callback=initialize"  async defer></script>


<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/address_form.js"></script>

