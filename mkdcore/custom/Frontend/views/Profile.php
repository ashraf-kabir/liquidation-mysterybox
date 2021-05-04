<div class="container mt-5 mb-5">
     <div class="row d-flex justify-content-center">
          <div class="col-md-12">
               <div class="card"> 
                    <form action="" id="update__form">
                         <div class="container mt-5 mb-5">
                              <div class="form-row justify-content-center">
                                   <div class="col-md-4 col-12 my-2">
                                        <label for="name">Full Name</label>
                                        <input type="text" id="name" name="name" placeholder="Your full name here" class="form-control" value="<?php echo $customer->name ?>" >
                                   </div>

                                   <div class="col-md-4 col-12 my-2">
                                        <label for="email">Email Address</label>
                                        <input type="email" readonly placeholder="example@google.com" class="form-control" value="<?php echo $customer->email ?>" >
                                   </div>

                                   <div class="col-md-4 col-12 my-2">
                                        <label for="password1">Phone</label>
                                        <input type="text" id="phone" name="phone" placeholder="*********" class="form-control" value="<?php echo $customer->phone ?>" >
                                   </div>
 

                                   <div class="col-md-12 col-12 my-2 mt-5">
                                        <label for="password2">Billing Address</label>
                                        <input type="text" id="billing_address" name="billing_address"   class="form-control"   value="<?php echo $customer->billing_address ?>" >
                                   </div>


                                   <div class="col-md-6 col-12 my-2">
                                        <label for="password2">Billing Country</label>
                                        <input type="text" id="billing_country" name="billing_country"   class="form-control" value="<?php echo $customer->billing_country ?>" >
                                   </div>



                                   <div class="col-md-6 col-12 my-2">
                                        <label for="password2">Billing State</label>
                                        <input type="text" id="billing_state" name="billing_state"   class="form-control"  value="<?php echo $customer->billing_state ?>" >
                                   </div>


                                   <div class="col-md-6 col-12 my-2">
                                        <label for="password2">Billing City</label>
                                        <input type="text" id="billing_city" name="billing_city"   class="form-control" value="<?php echo $customer->billing_city ?>" >
                                   </div>

                                   <div class="col-md-6 col-12 my-2">
                                        <label for="password2">Billing Zip</label>
                                        <input type="text" id="billing_zip" name="billing_zip"  class="form-control"  value="<?php echo $customer->billing_zip ?>" >
                                   </div>




                                   <div class="col-md-12 col-12 my-2  mt-5">
                                        <label for="shipping_address">Shipping Address</label>
                                        <input type="text" id="shipping_address" name="shipping_address"   class="form-control"   value="<?php echo $customer->shipping_address ?>" >
                                   </div> 

                                   <div class="col-md-6 col-12 my-2">
                                        <label for="shipping_country">Shipping Country</label>
                                        <input type="text" id="shipping_country" name="shipping_country"   class="form-control" value="<?php echo $customer->shipping_country ?>" >
                                   </div>



                                   <div class="col-md-6 col-12 my-2">
                                        <label for="shipping_state">Shipping State</label>
                                        <input type="text" id="shipping_state" name="shipping_state"   class="form-control"  value="<?php echo $customer->shipping_state ?>" >
                                   </div>


                                   <div class="col-md-6 col-12 my-2">
                                        <label for="shipping_city">Shipping City</label>
                                        <input type="text" id="shipping_city" name="shipping_city"   class="form-control" value="<?php echo $customer->shipping_city ?>" >
                                   </div>

                                   <div class="col-md-6 col-12 my-2">
                                        <label for="shipping_zip">Shipping Zip</label>
                                        <input type="text" id="shipping_zip" name="shipping_zip"  class="form-control"  value="<?php echo $customer->shipping_zip ?>" >
                                   </div>

                                   <input type="hidden" id="address_type" name="address_type" value="<?php echo set_value('address_type', $customer->address_type); ?>">


                              </div> 

                              <div class="form-row justify-content-center"> 
                                   <div class="col-md-3 col-12 mb-5">
                                        <button type="submit" class="btn btn-secondary update__form_submit w-100">
                                             Update
                                        </button>
                                   </div>
                              </div>
                         </div>
                    </form> 
               </div>
          </div>
     </div>
</div>

<?php $google_api_key = $this->config->item('google_api_key'); ?>

<script src="https://maps.googleapis.com/maps/api/js?key=<?php echo $google_api_key; ?>&libraries=places&callback=initialize"  async defer></script>


<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/address_form.js"></script>