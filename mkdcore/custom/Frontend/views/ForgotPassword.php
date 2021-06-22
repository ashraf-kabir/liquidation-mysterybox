<?php echo form_open(''); ?>
    <section>
        <div class="container-fluid py-5 px-xl-5 px-lg-5 px-md-4 px-3">
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


                <div class="col-xl-5 col-lg-6 col-md-7 col-sm-10 col-12 my-3"> 
                    <div class="bg-white w-100 p-2 p-md-4">
                        <h5>Forgot Password</h5>
                        <hr /> 
                        <div class="form-row justify-content-between">
                            <div class="col-12 ">
                                <label for="name">Email</label> 
                            </div> 

                            <div class="col-12">
                                <input
                                    type="email"
                                    name="email"
                                    id="email"
                                    value="<?php echo set_value('email'); ?>"
                                    placeholder="xyz@gmail.com"
                                    class="form-control"
                                    />
                            </div> 
 
                            <div class="col-12 mt-4 " style="text-align: center;">
                                <button type="submit" class="btn btn-secondary reset-btn w-100" style="width: 68% !important;">Reset my password</button>
                            </div> 
                        </div> 
                    </div> 
                </div> 
            </div>
        </div>
    </section> 
</form>