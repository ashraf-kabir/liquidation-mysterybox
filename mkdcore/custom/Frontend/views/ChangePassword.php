<?php echo form_open('set_new_password'); ?>
    <section>
        <div class="container-fluid px-5 py-5">
            <input type="hidden" name="token_b" value="<?php echo $token ?>">
            <div class="row justify-content-center align-items-center">

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


                <div class="col-xl-5 col-lg-5 col-md-5 col-5 my-3"> 
                    <div class="bg-white w-100 p-2 p-md-4">
                        <h5>Reset your password</h5>
                        <hr /> 
                        <div class="form-row justify-content-between">
                             


                            <div class="col-xl-12 col-lg-12 col-md-5 col-12 ">
                                <label for="name">Password</label> 
                            </div> 

                            <div class="col-xl-12 col-lg-12 col-md-5 col-12">
                                <input
                                    type="password"
                                    name="password"
                                    id="password"
                                    value="<?php echo set_value('password'); ?>"
                                    placeholder="****************"
                                    class="form-control"
                                    />
                            </div> 



                            <div class="col-xl-12 col-lg-12 col-md-5 col-12 mt-2 ">
                                <label for="name">Confirm Password</label> 
                            </div> 

                            <div class="col-xl-12 col-lg-12 col-md-5 col-12">
                                <input
                                    type="password"
                                    name="password2"
                                    id="password2"
                                    value="<?php echo set_value('password2'); ?>"
                                    placeholder="****************"
                                    class="form-control"
                                    />
                            </div> 
 
                            <div class="col-xl-12 col-lg-12 col-md-5 col-12 mt-3 " style="text-align: center;">
                                <button type="submit" class="btn btn-secondary w-100" style="width: 50% !important;">Update</button>
                            </div> 
                        </div> 
                    </div> 
                </div> 
            </div>
        </div>
    </section> 
</form>