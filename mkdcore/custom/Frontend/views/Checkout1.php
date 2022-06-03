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
<?php echo form_open('',array('class' => 'send_checkout' )); ?>
<section class="checkout-section" id="checkout-section">
     <div class="checkout-left child">
          <div class="checkout-row">
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
         <?php
//               print_r($cart_items);
          ?>

          <div class="checkout-row" id="review-and-shipping">
               <div class="first-box">
                    <span></span>
                    <p>Review items & shipping</p>
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

                         <div class="product border shadow p-2">
                              <div class="image">

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
                                   <div class="product-quantity">
                                        <p>Quantity:</p>
                                        <!-- <button data-id="<?php echo $value->product_id; ?>"  data-product_qty="<?php echo $value->product_quantity; ?>"  type="button" class="btn btn-secondary add_to_cart_button_checkout">+</button> -->
                                        <span class="quantity_for_item" style="margin-left: 11px;"><?php echo $value->product_quantity; ?></span>
                                        <!-- <button data-id="<?php echo $value->product_id; ?>" data-product_qty="<?php echo $value->product_quantity; ?>" type="button"  class="btn btn-secondary minus_to_cart_button">-</button> -->
                                   </div>

                                   <div class="d-flex justify-content-between">
                                        <div class="border p-2" onclick="toggleToPickUp('<?php echo $key ?>')">
                                             <h6>PICKUP AT <span class="float-right" id="pickup_tick_<?php echo $key; ?>">&#10004;</span></h6>
                                             <p><?php echo $store_data->address ?></p>
                                             <p><?php echo $store_data->state." ".$store_data->zip. " ".$store_data->phone ?></p>
                                        </div>
                                        <div class="border p-2" onclick="toggleToShipTo('<?php echo $key ?>')">
                                             <h6>SHIP TO <span class="float-right" id="ship_to_tick_<?php echo $key; ?>">&#10004;</span></h6>
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
                                   </div>

                                    


                                   <div class="shipping-cost" data-shipping-box="<?php echo $key ?>">
                                        <?php if ($value->can_ship == 2): ?>
                                             <div class="shipping-cost-options custom-pricing-div" style="margin-right: 5px;">

                                                  <input type="hidden" class="shipping-cost-name" name="shipping_cost_name_<?php echo $value->id; ?>" value="">

                                                  <input type="hidden" class="shipping-cost-price-value" name="shipping_cost_value_<?php echo $value->id; ?>" value="0">

                                                  <label><input name="shipping_service_id_<?php echo $value->id; ?>" class="mr-3 shipping-cost-change" type="radio" value="Local Pickup" data-other-cost="0" data-price="0" data-service-code="Local Pickup" data-service-name="Local Pickup">Local Pickup </label>

                                                  <label><input name="shipping_service_id_<?php echo $value->id; ?>" class="mr-3 shipping-cost-change" type="radio" value="Local Pickup No Shipping" data-other-cost="0" data-price="0" data-service-code="Local Pickup No Shipping" data-service-name="Local Pickup No Shipping">Local Pickup No Shipping </label>

                                                   
                                             </div>
                                        <?php else: ?>  

                                             <?php if ($value->free_ship == 1): ?>
                                                  <p>Free Shipping:</p>

                                                  <p>$<span class="selected_item_shipping_cost">0.00</span></p>

                                                  
                                                  <p><strong>Expected Delivery Date : <span class="selected_item_expected_shipping_date">N/A</span></strong></p>
                                                   
                                                  <button style="display: none;" type="button" data-id="<?php echo $value->id; ?>" class="btn btn-secondary calculate-shipping-cost">calculate</button>
                                             <?php else: ?>
                                                  <p>Shipping Cost:</p> 
                                                  <p>$<span class="selected_item_shipping_cost">0.00</span></p>

                                                  <p><strong>Expected Delivery Date : <span class="selected_item_expected_shipping_date">N/A</span></strong></p>

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
     <div class="checkout-right child topper">
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

               <div class="header btn2-place_order  mb-3"> 
                    <button style="    margin-top: 0px;" class="btn btn-warning place-order-btn btn2-place_order" type="button">Place your Order</button> 
               </div>

               <div class="">
                    <p class="font-weight-bold">Shipping Details <button type="button" class="dropdown-btn btn btn-secondary shipping-btn btn-secondary  right ">Update</button></p>
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

               <div class="summary">
                    <p>Order summary</p>
                    <div class="details">
                         <div>
                              <p>Items(<?php echo count($cart_items) ?>):</p>
                              <p>$<span class="sub_total_value"><?php echo number_format($sub_total,2); ?></span></p>
                         </div>
                         <div>
                              <p>shipping & handling:</p>
                              <p>$ <span class="shipping_total_cost">0.00</span></p>
                         </div>
                         <div>
                              <p>Total before tax:</p>
                              <p>$<span class="total_without_tax_value"><?php echo number_format($sub_total,2); ?></span></p>
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
                        I’ve read and accept the <a  target="_blank" href="<?php echo base_url(); ?>terms_and_conditions">Terms & Conditions</a>
                        <br>
                        <input type="checkbox" required name="sales_are_final" id="sales_are_final" class="mr-2" />
                        All sales are final. Product is sold As-Is.
                    </label> 
                    <button class="btn btn-warning place-order-btn btn1-place_order" type="button">Place your Order</button> 
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

     function toggleToPickUp(key){
          
     }
     function toggleToShipTo(key){

     }

</script>


<?php $google_api_key = $this->config->item('google_api_key'); ?>

<script src="https://maps.googleapis.com/maps/api/js?key=<?php echo $google_api_key; ?>&libraries=places&callback=initialize"  async defer></script>


<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/address_form.js"></script>
