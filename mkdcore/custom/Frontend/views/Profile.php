<div class="container mt-5 mb-5">
    <div class="row d-flex justify-content-center">
        <div class="col-md-12">
            <div class="card"> 
                <form action="" id="update__form">
                    <div class="container mt-5 mb-5">
                        <div class="form-row justify-content-center">
                            <div class="col-md-6 col-12 my-2">
                                <label for="name">Full Name</label>
                                <input type="text" id="name" name="name" placeholder="Your full name here" class="form-control" value="<?php echo $customer->name ?>" >
                            </div>

                            <div class="col-md-6 col-12 my-2">
                                <label for="email">Email Address</label>
                                <input type="email" readonly placeholder="example@google.com" class="form-control" value="<?php echo $customer->email ?>" >
                            </div>

                            <div class="col-md-6 col-12 my-2">
                                <label for="password1">Phone</label>
                                <input type="text" id="phone" name="phone" placeholder="*********" class="form-control" value="<?php echo $customer->phone ?>" >
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


                            <div class="col-md-6 col-12 my-2">
                              <label for="password2">Billing Address</label>
                              <input type="text" id="billing_address" name="billing_address"   class="form-control"   value="<?php echo $customer->billing_address ?>" >
                            </div>
                             

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