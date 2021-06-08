    <main class="container-fluid">
        

        <div class="row justify-content-center py-5 contact__row">
           <div class="col-11 col-lg-3 bg-white ml-md-4  p-4 p-lg-3 my-4">
                <h2 class="section__header">Get in touch</h2>
                <p class="mb-4">Feel free to contact us with any questions you might have.</p>

                <h5><strong><?php echo $home_page_setting->home_page_address ?></strong></h5>

                <ul class="list-unstyled my-5   ">
                    <li class="my-3"><i class="fas fa-phone"></i> <?php echo $home_page_setting->home_page_phone_no ?></li>
                    <li class="my-3"><i class="fas fa-clock"></i> <?php echo $home_page_setting->home_page_time ?></li>
                    <li class="my-3"><i class="fas fa-envelope"></i> <?php echo $home_page_setting->home_page_support_email ?></li>
                </ul>
 
           </div>
            <div class="col-11 col-lg-7 bg-white ml-md-4 p-4 my-4">
                <h2 class="section__header">Contact us:</h2>

                <?php if ($this->session->flashdata('success2')): ?> 
                    <div class="alert alert-success alert-dismissible " role="alert">
                            <?php echo $this->session->flashdata('success2'); ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                    </div> 
                <?php endif ?>

                <?php if ($this->session->flashdata('error2')): ?> 
                    <div class="alert alert-warning alert-dismissible " role="alert">
                            <?php echo $this->session->flashdata('error2'); ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                    </div> 
                <?php endif ?>
                <!-- <form action="" id="contact__form"> -->
                <?= form_open() ?>
                    <div class="form-row justify-content-between">
                        <div class="col-12 col-md-5 my-3">
                            <label for="name">Your Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="col-12 col-md-5 my-3">
                            <label for="email">Your Email <span class="text-danger">*</span></label>
                            <input type="text" name="email" class="form-control" required>
                        </div>
                        <div class="col-12 my-3">
                            <label for="subject">Subject</label>
                            <input type="text" name="subject" class="form-control">
                        </div>
                        <div class="col-12 my-3">
                            <label for="message">Your Message</label>
                            <textarea name="message" cols="30" rows="8" class="form-control"></textarea>
                        </div>
                    </div>
                    <input type="submit" value="Send" class="btn btn-secondary px-4 py-2 my-2">
                </form>
            </div>
        </div>
    </main>

    <section class="container-fluid " id="map">
        <div class="row justify-content-center mb-5 pb-5">
            <div class="col-12 col-md-10 mt-3 mb-5">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3218.850505521807!2d-115.07951334926686!3d36.218828407810214!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x80c8dd3b9a7dbb63%3A0x8d8ffe5b86546862!2s4460%20E%20Cheyenne%20Ave%2C%20Las%20Vegas%2C%20NV%2089115%2C%20USA!5e0!3m2!1sen!2sng!4v1602759804356!5m2!1sen!2sng" width="100%" height="500" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>
            </div>
        </div>
    </section>