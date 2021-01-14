<footer>
      <div class="container-fluid mt-5 mb-0 py-5" id="footer">
        <div
          class="row py-2 px-2 px-md-5 align-items-center justify-content-between justify-content-lg-start"
        >
          <div class="col-12">
            <ul class="list-unstyled">
              <li><a href="<?php echo base_url(); ?>">Home</a></li>
              <li><a href="<?php echo base_url(); ?>">Services</a></li> 
              <li><a href="<?php echo base_url(); ?>about_us">About Us</a></li>
              <li><a href="<?php echo base_url(); ?>contact_us">Contact Us</a></li>
            </ul>
          </div>
        </div>
        <p class="mx-auto w-100 text-center">
          &copy; 2020 Vegas liquidation Ecom. All rights reserved.
        </p>
      </div>
    </footer>

    <!-- SIGN UP MODAL -->
    <div
      class="modal fade"
      id="signupModal"
      tabindex="-1"
      role="dialog"
      aria-labelledby="signupModalTitle"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button
              type="button"
              class="close"
              data-dismiss="modal"
              aria-label="Close"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="container-fluid">
              <div class="row">
                <div class="col-12 text-center">
                  <img
                    class="my-3"
                    src="<?php echo base_url(); ?>/assets/frontend_images/logo.png"
                    alt="Company logo"
                  />
                  <p>Welcome to <strong>VEGAS LIQUIDATION ECOM</strong></p>
                  <h2><strong>SIGN UP</strong></h2>
                </div>
              </div>
              <form action="" id="signup__form">
                <div class="form-row justify-content-center">
                  <div class="col-md-9 col-12 my-2">
                    <label for="name">Full Name</label>
                    <input
                      type="text"
                      id="name"
                      name="name"
                      placeholder="Your full name here"
                      class="form-control"
                      required
                    />
                  </div>

                  <div class="col-md-9 col-12 my-2">
                    <label for="email">Email Address</label>
                    <input
                      type="email"
                      id="email"
                      name="email"
                      placeholder="example@google.com"
                      class="form-control"
                      required
                    />
                  </div>

                  <div class="col-md-9 col-12 my-2">
                    <label for="password1">Password</label>
                    <input
                      type="password"
                      id="password1"
                      name="password1"
                      placeholder="*********"
                      class="form-control"
                      required
                    />
                  </div>

                  <div class="col-md-9 col-12 my-2">
                    <label for="password2">Confirm Password</label>
                    <input
                      type="password"
                      id="password2"
                      name="password2"
                      placeholder="*********"
                      class="form-control"
                      required
                    />
                  </div>
                  <div class="col-md-9 col-12 my-3">
                    <label for="terms">
                      <input
                        type="checkbox"
                        id="terms"
                        name="terms"
                        class="mr-2"
                        required
                      />
                      Yes, I have read the Vegas Liquidation Ecom user
                      agreement and agree to be bound by its terms and
                      conditions.
                    </label>
                  </div>

                  <div class="col-md-9 col-12 mb-5">
                    <button type="button" class="btn btn-secondary signup__form_submit w-100">
                      Create Account
                    </button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>




    <!-- SIGN UP MODAL -->
    <div
      class="modal fade"
      id="loginModal"
      tabindex="-1"
      role="dialog"
      aria-labelledby="loginModalTitle"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button
              type="button"
              class="close"
              data-dismiss="modal"
              aria-label="Close"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="container-fluid">
              <div class="row">
                <div class="col-12 text-center">
                  <img
                    class="my-3"
                    src="<?php echo base_url(); ?>/assets/frontend_images/logo.png"
                    alt="Company logo"
                  />
                  <p>Welcome to <strong>VEGAS LIQUIDATION ECOM</strong></p>
                  <h2><strong>LOGIN</strong></h2>
                </div>
              </div>
              <form action="" id="loginsignup__form">
                <div class="form-row justify-content-center">
                   

                  <div class="col-md-9 col-12 my-2">
                    <label for="email_login">Email Address</label>
                    <input
                      type="email"
                      id="email_login"
                      name="email_login"
                      placeholder="example@google.com"
                      class="form-control"
                      required
                    />
                  </div>

                  <div class="col-md-9 col-12 my-2">
                    <label for="password1_login">Password</label>
                    <input
                      type="password"
                      id="password1_login"
                      name="password1_login"
                      placeholder="*********"
                      class="form-control"
                      required
                    />
                  </div>
 
                  <div class="col-md-9 col-12 mb-5">
                    <button type="button" class="btn btn-secondary login__form_submit w-100">
                      Login
                    </button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->

<script src="https://code.jquery.com/jquery-3.5.1.min.js" ></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" ></script>

<script>
  toastr.options = {
    "closeButton": false,
    "debug": false,
    "newestOnTop": false,
    "progressBar": false,
    "positionClass": "toast-top-right",
    "preventDuplicates": false,
    "onclick": null,
    "showDuration": "300",
    "hideDuration": "1000",
    "timeOut": "5000",
    "extendedTimeOut": "1000",
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "fadeIn",
    "hideMethod": "fadeOut"
  }
 
</script>


    <script
      src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"
      integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN"
      crossorigin="anonymous"
    ></script>
    <script
      src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"
      integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV"
      crossorigin="anonymous"
    ></script>

    <script src="<?php echo base_url(); ?>assets/frontend_js/script.js" ></script>
  </body>
</html>
