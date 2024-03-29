<style type="text/css">
    .facebook-icon-color {
        background-color: #3b5998 !important;
    }

    .twitter-icon-color {
        background-color: #00acee !important;
    }

    .tiktok-icon-color {
        background-color: #ff0050 !important;
    }

    .pintrest-icon-color {
        background-color: #DF1A26 !important;
    }

    .footer__col .list-unstyled li {
        line-height: 14px;
    }

    .footer_move {
        padding-left: 99px !important;
    }

    .footer__col .list-unstyled li i {
        font-size: 13px !important;
    }

    .footer__col .list-unstyled li i {
        font-size: 13px !important;
    }

    .footer__col_custom {
        padding-left: 23px;
    }

    @media screen and (max-width: 2462px) {
        .footer__col_custom {
            padding-left: 172px;
        }
    }

    @media screen and (max-width: 1924px) {
        .footer__col_custom {
            padding-left: 126px;
        }
    }

    @media screen and (max-width: 1712px) {
        .footer__col_custom {
            padding-left: 105px;
        }
    }

    @media screen and (max-width: 1711px) {
        .footer__col_custom {
            padding-left: 105px;
        }
    }






    @media screen and (max-width: 1523px) {
        .footer__col_custom {
            padding-left: 69px;
        }
    }

    @media screen and (max-width: 1491px) {
        .footer__col_custom {
            padding-left: 57px;
        }
    }

    @media screen and (max-width: 1484px) {
        .footer__col_custom {
            padding-left: 64px;
        }
    }

    @media screen and (max-width: 1450px) {
        .footer__col_custom {
            padding-left: 44px;
        }
    }



    @media screen and (max-width: 1204px) {
        .footer__col_custom {
            padding-left: 9px;
        }
    }


    @media screen and (max-width: 1191px) {
        .footer__col_custom {
            padding-left: 0px;
        }
    }

    @media screen and (max-width: 991px) {
        .follow__buttons {
            justify-content: flex-start;
            margin-left: -8px !important;
        }

        .footer-links {
            justify-content: flex-start;
        }
    }


    @media screen and (max-width: 768px) {
        .footer__col_custom {
            padding-left: 7px;
        }
    }
</style>

<footer class="container-fluid">
    <div class="row justify-content-start px-4">
        <div class="col-10 col-lg-4 p-0 footer__col order-xl-0 order-lg-0 order-md-1 order-sm-1 order-1 my-4">
            <ul class="list-unstyled">
                <li class="my-3 d-flex">
                    <i class="fas fa-map-marker-alt"></i>
                    <div><?php echo $home_page_setting->home_page_address ?></div>
                </li>
                <li class="my-3 d-flex">
                    <i class="fas fa-phone"></i>
                    <div><?php echo $home_page_setting->home_page_phone_no ?></div>
                </li>

                <li class="my-3 d-flex">
                    <i class="fas fa-clock"></i>
                    <div><?php echo $home_page_setting->home_page_time ?></div>
                </li>
                <li class="my-3 d-flex">
                    <i class="fas fa-envelope"></i>
                    <div><?php echo $home_page_setting->home_page_support_email ?></div>
                </li>
            </ul>
        </div>
        <div class="col-10 col-lg-4 footer__col my-4 footer__col_custom order-xl-1 order-lg-1 order-md-2 order-sm-2 order-2 p-0 ">
            <div class="follow__buttons flex-css">
                <a target="_blank" href="<?php echo $home_page_setting->home_page_fb_link ?>"><i class="fab fa-facebook-f facebook-icon-color"></i></a>
                <?php if (!empty($home_page_setting->home_page_pintrest_link)) : ?>
                    <a target="_blank" href="<?php echo $home_page_setting->home_page_pintrest_link ?>"><i class="fab fa-pinterest pintrest-icon-color"></i></a>
                <?php endif ?>
                <a target="_blank" href="<?php echo $home_page_setting->home_page_tiktok_link ?>"><i class="fab fa-twitter twitter-icon-color"></i></a>
                <a target="_blank" href="<?php echo $home_page_setting->home_page_insta_link ?>"><i class="fab fa-instagram in-icon"></i></a>
                <a target="_blank" href="<?php echo $home_page_setting->home_page_twitter_link ?>"><i class="fab fa-tiktok tiktok-icon-color"></i></a>


            </div>
        </div>
        <div class="col-10 col-lg-4 footer__col my-4 order-xl-2 order-lg-2 order-md-0 order-sm-0 order-0 p-0">
            <div class="footer-links flex-top-row-end">
                <ul class="p-0 site__mapUl">
                    <li><a href="<?php echo base_url(); ?>">Home</a></li>
                    <li><a href="<?php echo base_url(); ?>about_us/">About&nbsp;Us</a></li>
                    <li><a href="<?php echo base_url(); ?>contact_us/">Contact&nbsp;Us</a></li>
                    <li><a href="<?php echo base_url(); ?>privacy_policy/">Privacy&nbsp;Policy</a></li>
                    <li><a href="<?php echo base_url(); ?>terms_and_conditions/">Terms&nbsp;And&nbsp;Conditions</a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="row justify-content-center px-4">
        <div class="col-12 col-md-12 p-0 footer__col my-4" style=" text-align: center;">
            <p style="margin: 0px;">Copyright &copy; <?php echo Date('Y') ?> VegasLiquidation.com</p>
        </div>
    </div>


    <div class="modal fade" id="signupModal" tabindex="-1" role="dialog" aria-labelledby="signupModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12 text-center">
                                <img class="my-3" src="<?php echo base_url(); ?>/assets/frontend_images/vegas_img.jpg" alt="Company logo" style="width: 160px;" />
                                <p>Welcome to <strong>VEGAS LIQUIDATION ECOM</strong></p>
                                <h2><strong>SIGN UP</strong></h2>
                            </div>
                        </div>
                        <form action="" id="signup__form" class="signup__form_submit_c">
                            <div class="form-row justify-content-center">
                                <div class="col-md-9 col-12 my-2">
                                    <label for="name">Full Name</label>
                                    <input type="text" id="name" name="name" placeholder="Your full name here" class="form-control" required />
                                </div>

                                <div class="col-md-9 col-12 my-2">
                                    <label for="email">Email Address</label>
                                    <input type="email" id="email" name="email" placeholder="example@google.com" class="form-control" required />
                                </div>

                                <div class="col-md-9 col-12 my-2">
                                    <label for="password1">Password</label>
                                    <input type="password" id="password1" name="password1" placeholder="*********" class="form-control" required />
                                </div>

                                <div class="col-md-9 col-12 my-2">
                                    <label for="password2">Confirm Password</label>
                                    <input type="password" id="password2" name="password2" placeholder="*********" class="form-control" required />
                                </div>
                                <div class="col-md-9 col-12 my-3">
                                    <label for="terms">
                                        <input type="checkbox" id="terms" name="terms" class="mr-2" required />
                                        Yes, I have read the Vegas Liquidation Ecom user
                                        agreement and agree to be bound by its <a href="<?php echo base_url(); ?>terms_and_conditions">Terms and
                                            conditions.</a>
                                    </label>
                                </div>

                                <div class="col-md-9 col-12 mb-5">
                                    <button type="submit" class="btn btn-secondary signup__form_submit w-100">
                                        Create Account
                                    </button>
                                </div>

                                <div class="col-md-9 col-12 mb-5" style="text-align: center;">
                                    Already have an account? <a class="login_now" href="<?php echo base_url() ?>">Login Now</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>




    <!-- SIGN UP MODAL -->
    <div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close signupModal_close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12 text-center">
                                <img class="my-3" src="<?php echo base_url(); ?>/assets/frontend_images/vegas_img.jpg" alt="Company logo" style="width: 160px;" />
                                <p>Welcome to <strong>VEGAS LIQUIDATION ECOM</strong></p>
                                <h2><strong>LOGIN</strong></h2>
                            </div>
                        </div>
                        <form action="" id="loginsignup__form" class="login__form_submit_c">
                            <div class="form-row justify-content-center">


                                <div class="col-md-9 col-12 my-2">
                                    <label for="email_login">Email Address</label>
                                    <input type="email" id="email_login" name="email_login" placeholder="example@google.com" class="form-control" required />
                                </div>

                                <div class="col-md-9 col-12 my-2">
                                    <label for="password1_login">Password</label>
                                    <input type="password" id="password1_login" name="password1_login" placeholder="*********" class="form-control" required />
                                </div>

                                <div class="col-md-9 col-12 mb-5">
                                    <button type="submit" class="btn btn-secondary login__form_submit w-100">
                                        Login
                                    </button>
                                    <a href="<?php echo base_url() ?>forgot_password">Forgot my password</a>
                                </div>

                                <div class="col-md-9 col-12 mb-5" style="text-align: center;">
                                    Don't have an account? <a href="" class="register_now">Register Now</a>
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



</footer>

<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script>

<script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
    toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": false,
        "progressBar": true,
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
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>

<script type="text/javascript" src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>

<script src="<?php echo base_url(); ?>/assets/frontend_js/swiper-bundle.min.js"></script>

<script src="<?php echo base_url(); ?>/assets/frontend_js/script.js"></script>

<script>
    $(document).ready(function() {


        var swiper = new Swiper('.swiper-container-sliders-custom', {
            speed: 700,
            autoplay: {
                delay: 4000,
            },
            slidesPerView: 1,
            spaceBetween: 30,
            slidesPerGroup: 1,
            observer: true,
            observeParents: true,
            parallax: true,
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
        });

        var swiper_mysterybox = new Swiper(".mySwiper-mysterybox", {
            slidesPerView: 3,
            spaceBetween: 30,
            slidesPerGroup: 3,
            observer: true,
            observeParents: true,
            parallax: true,
            breakpoints: {
                992: {
                    slidesPerView: 3,
                    spaceBetween: 30,
                    slidesPerGroup: 1,
                },
                768: {
                    slidesPerView: 2,
                    spaceBetween: 30,
                    slidesPerGroup: 2,
                },
                200: {
                    slidesPerView: 1,
                    spaceBetween: 30,
                    slidesPerGroup: 1,
                },
            },
            loop: false,
            loopFillGroupWithBlank: true,
            pagination: {
                el: ".swiper-pagination-mysterybox-card-removed",
                clickable: true,
            },
            navigation: {
                nextEl: ".swiper-button-next-mysterybox-card",
                prevEl: ".swiper-button-prev-mysterybox-card",
            },
        });

        var swiper_liquidation = new Swiper(".mySwiper-liquidation", {
            slidesPerView: 3,
            spaceBetween: 30,
            slidesPerGroup: 3,
            observer: true,
            observeParents: true,
            parallax: true,
            loop: false,
            loopFillGroupWithBlank: true,
            breakpoints: {
                992: {
                    slidesPerView: 3,
                    spaceBetween: 30,
                    slidesPerGroup: 3,
                },
                768: {
                    slidesPerView: 2,
                    spaceBetween: 30,
                    slidesPerGroup: 2,
                },
                200: {
                    slidesPerView: 1,
                    spaceBetween: 30,
                    slidesPerGroup: 1,
                },
            },
            pagination: {
                el: ".swiper-pagination-liquidation-card-removed",
                clickable: false,
            },
            navigation: {
                nextEl: ".swiper-button-next-liquidation-card",
                prevEl: ".swiper-button-prev-liquidation-card",
            },
        });




        var swiper_mysterybox = new Swiper(".myvideos-videos-list", {
            slidesPerView: 3,
            spaceBetween: 30,
            slidesPerGroup: 3,
            observer: true,
            observeParents: true,
            parallax: true,
            breakpoints: {
                992: {
                    slidesPerView: 3,
                    spaceBetween: 30,
                    slidesPerGroup: 1,
                },
                768: {
                    slidesPerView: 2,
                    spaceBetween: 30,
                    slidesPerGroup: 2,
                },
                200: {
                    slidesPerView: 1,
                    spaceBetween: 30,
                    slidesPerGroup: 1,
                },
            },
            loop: false,
            loopFillGroupWithBlank: true,
            pagination: {
                el: ".swiper-pagination-videos-card-removed",
                clickable: true,
            },
            navigation: {
                nextEl: ".swiper-button-next-videos-card",
                prevEl: ".swiper-button-prev-videos-card",
            },
        });


        $('.slider').slick({
            centerMode: true,
            centerPadding: '20px',
            slidesToShow: 4,
            responsive: [{
                    breakpoint: 768,
                    settings: {
                        arrows: false,
                        centerMode: true,
                        centerPadding: '40px',
                        slidesToShow: 2
                    }
                },
                {
                    breakpoint: 480,
                    settings: {
                        arrows: false,
                        centerMode: true,
                        centerPadding: '40px',
                        slidesToShow: 1
                    }
                }
            ]
        });
    });
</script>
</body>

</html>

<script type="text/javascript">
    $(document).on('click', '.register_now', function(e) {
        e.preventDefault();
        $('#loginModal').modal('hide');
        $('#signupModal').modal('show');
        $('.modal').css('overflow-y', 'auto');
    });


    $(document).on('click', '.login_now', function(e) {
        e.preventDefault();

        $('#signupModal').modal('hide');
        $('#loginModal').modal('show');
        $('.modal').css('overflow-y', 'auto');
    })


    document.querySelector(".search-nav-link").addEventListener('click', function() {
        document.querySelector(".search-wrapper").classList.remove("hidden");
    })

    document.querySelector(".search-wrapper .close-icon").addEventListener('click', function() {
        document.querySelector(".search-wrapper").classList.add("hidden");
    })


    $(".child-cat").on("mouseover", function(e) {
        $($($(this).parent()).children()[0]).attr("style", "background-color: #E0E0E0 !important;");
    })

    $(".child-cat").on("mouseout", function(e) {
        $($($(this).parent()).children()[0]).attr("style", "");

    })
</script>

<?php $google_api_key = $this->config->item('google_api_key'); ?>

<script src="https://maps.googleapis.com/maps/api/js?key=<?php echo $google_api_key; ?>&libraries=places&callback=initialize" async defer></script>

<script type="text/javascript">
    var m = false;

    function googleTranslateElementInit() {
        new google.translate.TranslateElement({
            includedLanguages: 'en,es',
            autoDisplay: false
        }, 'google_translate_element');

    }
</script>

<script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>