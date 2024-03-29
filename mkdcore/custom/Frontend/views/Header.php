<!DOCTYPE html>
<html lang="en">

<head>
     <!-- Required meta tags -->
     <meta charset="utf-8">
     <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">

     <!-- Bootstrap CSS -->
     <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
     <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/sanjaya007/flex-library@master/dist/css/sanjaya.min.css" crossorigin="anonymous">

     <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
     <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.1/css/all.css" integrity="sha384-vp86vTRFVJgpjF9jiIGPEEqYqlDwgyBgEF109VFjmqGmIY/Y4HV4d3Gp2irVfcrp" crossorigin="anonymous">

     <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css" />

     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">
     <!-- Local CSS -->
     <link rel="stylesheet" href="/assets/css/frontend_style.css">
     <link rel="stylesheet" href="/assets/frontend_css/swiper-bundle.min.css">

     <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
     <title>Vegas Liquidation</title>

     <style type="text/css">
          .navbar-toggler {
               border: none;
               outline: none;
               padding: 0.25rem 0;
               padding-left: 2px;
          }

          .navbar-toggler:focus {
               outline: none;
          }

          .swiper-button-next:hover {
               background-color: #94959957;
               color: black;
          }

          .swiper-button-prev:hover {
               background-color: #94959957;
               color: black;
          }

          .badge {
               padding-left: 9px;
               padding-right: 9px;
               -webkit-border-radius: 9px;
               -moz-border-radius: 9px;
               border-radius: 9px;
          }

          .label-warning[href],
          .badge-warning[href] {
               background-color: #c67605;
          }

          #lblCartCount {
               font-size: 10px;
               background: #ff0000;
               color: #fff;
               padding: 2px 5px;
               vertical-align: top;
               margin-left: -4px;
          }






          .top_header_css {
               background: <?php echo $home_page_setting->home_page_top_bg; ?>;
               color: <?php echo $home_page_setting->home_page_top_color; ?>;
               text-align: center;
               padding: 12px 0px;
          }

          .make-dropdown-menu-full {
               width: 100% !important;
               padding: 0px !important;
          }

          .submenu-anchor {
               display: block !important;
               background: #fff !important;
               color: #212121 !important;
               font-family: inherit !important;
               font-size: 14px !important;
               font-weight: normal !important;
               padding: 5px 0px 5px 0px !important;
               line-height: 26px !important;
               text-decoration: none !important;
               text-transform: none !important;
               vertical-align: baseline !important;
          }

          .submenu-anchor-l2 {
               display: block !important;
               background: #fff !important;
               color: #212121 !important;
               font-family: inherit !important;
               font-size: 14px !important;
               font-weight: normal !important;
               line-height: 18px !important;
               text-decoration: none !important;
               text-transform: none !important;
               vertical-align: baseline !important;
               padding: 10px 18px 10px 10px !important;
          }

          .submenu-li {
               border-bottom: 1px solid #d4dde2
          }

          .dropdown-menu {
               border-radius: 0px !important;
          }

          .submenu-anchor-l2:hover {
               background-color: #E0E0E0 !important;
          }

          .search-nav-link {
               cursor: pointer;
          }

          .search-wrapper {
               position: fixed;
               height: 100vh;
               width: 100%;
               background-color: rgba(255, 255, 255, 0.3);
               display: flex;
               justify-content: center;
               align-items: flex-start;
               top: 0;
               left: 0;
               z-index: 999;
          }

          .search-wrapper .search-input-container {
               border-bottom: 1px solid #979797;
          }

          .search-wrapper .search-input-container a {
               text-decoration: none;
               color: #000000;
          }

          .search-wrapper .search-input-container a i {
               font-size: 35px;
          }

          .search-wrapper .search-input-container input {
               border: none;
               outline: none;
               font-size: 35px;
               padding-left: 20px;
               padding-right: 20px;
               font-weight: 100;
               text-transform: uppercase;
          }

          .search-wrapper.hidden {
               display: none;
          }

          .shop-dropdown a {
               font-size: 13px !important;
          }

          .parent-cat {
               position: relative;
          }

          .parent-cat .child-cat {
               padding: 3px 0 !important;
               position: absolute;
               right: -101px;
               background: #ffffff;
               width: 100px;
               top: 0;
               display: none;
               border-radius: 1px;
          }

          .parent-cat .child-cat a {
               width: 100%;
               padding: 5px !important;
          }

          .parent-cat:hover .child-cat {
               display: flex;
          }

          .parent-cat .child-cat a:hover {
               background-color: #F6F6F6;
          }

          .logo-nav {
               position: absolute;
               left: calc(50% - 99px);
          }

          .logo-img {
               width: 198px;
          }

          .mobile-icons {
               display: none;
               color: #000000;
               text-decoration: none;
          }

          .mobile-icons a {
               color: #000000;
               text-decoration: none;
          }


          .mobile-icons.account-icon {
               position: absolute;
               right: 1rem;
          }


          @media screen and (max-width: 991px) {
               .navbar .nav-item .nav-link {
                    padding: 13px 4px !important;
               }

               .dropdown-submenu>.dropdown-menu {
                    margin-top: 0px !important;
               }

               .parent-cat .child-cat {
                    position: relative;
                    display: flex;
                    width: 100%;
                    left: 0;
                    padding: 3px 0 3px 20px !important
               }

               .footer_move {
                    padding-left: 0px !important;
               }

               .logo-hide-mbl {
                    display: none !important;
               }

               .mobile-icons {
                    display: block;
               }
          }


          @media screen and (max-width: 1100px) {
               .logo-nav {
                    left: calc(50% - 32px)
               }

               .logo-img {
                    width: 150px;
               }
          }

          @media screen and (max-width:767px) {

               .dropdown-submenu>.dropdown-menu {
                    top: 0;
                    margin-top: 0px;
               }

               .remove-margin-left {
                    margin-left: 0px !important;
               }

               .search-wrapper .search-input-container a i {
                    font-size: 20px;
               }

               .search-wrapper .search-input-container input {

                    font-size: 20px;
                    padding-left: 10px;
                    padding-right: 10px;

               }


          }

          .swiper-button-disabled {
               display: none !important;
          }

          .goog-logo-link {
               display: none !important;
          }

          .goog-te-gadget {
               color: transparent !important;
          }

          select.goog-te-combo {
               padding: 7px 0px !important;
               margin: 0px 0 !important;
               width: 130px !important;
               background: #fff;
               border: 2px solid black;
               font-weight: 400;
          }

          .language-button {
               margin: 0px !important;
               border: 0px !important;
               background: none !important;
               box-shadow: none !important;
          }

          div.goog-te-gadget {
               padding-top: 16px !important;
          }

          .goog-te-banner-frame {
               display: none !important;
          }

          body {
               top: 0px !important;
          }
     </style>
</head>

<body>



     <?php if (!empty($home_page_setting->home_page_top_text)) : ?>
          <header class="container-fluid top_header_css">
               <?php echo $home_page_setting->home_page_top_text; ?>
          </header>
     <?php endif ?>

     <div class="search-wrapper hidden">
          <form action="<?php echo base_url() ?>categories">
               <div class="search-input-container pt-5 pb-1 px-2">
                    <a href="" class="search-icon icon">
                         <i class="fas fa-search"></i>
                    </a>
                    <input type="text" name="search_term" placeholder="SEARCH" />
                    <a href="" class="close-icon icon">
                         <i class="fas fa-times"></i>
                    </a>
               </div>
          </form>
     </div>


     <header class="container-fluid ">
          <nav class="navbar navbar-expand-lg row py-3 py-md-0 justify-lg-content-between" style="margin-top: 0px;background: none !important;">
               <div class="mobile-nav-list flex-css">
                    <button class="navbar-toggler ml-3" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                         <span class="navbar-toggler-icon"><i class="fas fa-bars mt-1"></i></span>
                    </button>
                    <!-- <a href="#" class="mobile-icons">
                         <i class="fa fa-search"></i>
                    </a> -->
               </div>

               <!-- <div class="account-icon mobile-icons account-icon">
               <a href="#">
               <i class="fas fa-user"></i></a>
               </div> -->

               <div class="collapse navbar-collapse col-md-12" id="navbarNavDropdown" style="justify-content: space-between;">

                    <ul class="nav navbar-nav">


                         <li class="dropdown-submenu nav-item ">
                              <a class="dropdown-item  nav-link" tabindex="-1">Shop Mysterybox<b class="caret"></b></a>
                              <ul class="dropdown-menu make-dropdown-menu-full">
                                   <li class="dropdown-submenu shop-dropdown">
                                        <a class="sub-item submenu-anchor-l2" href="<?php echo base_url(); ?>categories">
                                             All
                                        </a>
                                   </li>
                                   <?php foreach ($header_categories as $key => $category) : ?>
                                        <li class="dropdown-submenu shop-dropdown parent-cat">
                                             <a class="sub-item submenu-anchor-l2" href="<?php echo base_url(); ?>categories/?category=<?php echo $category->id; ?>">
                                                  <?php echo $category->name; ?>
                                             </a>
                                             <?php if (!empty($category->childs_list)) : ?>
                                                  <div class="child-cat flex-top-column">
                                                       <?php foreach ($category->childs_list as $key => $value) : ?>
                                                            <a href="<?php echo base_url(); ?>categories/?category=<?php echo $value->id; ?>"> <?php echo $value->name ?> </a>
                                                       <?php endforeach ?>
                                                  </div>
                                             <?php endif ?>
                                        </li>
                                   <?php endforeach ?>
                              </ul>
                         </li>


                         <li class="dropdown-submenu nav-item ">
                              <a class="dropdown-item  nav-link" tabindex="-1">View Current Inventory<b class="caret"></b></a>
                              <ul class="dropdown-menu make-dropdown-menu-full">

                                   <li class="dropdown-submenu">
                                        <a class="sub-item submenu-anchor-l2" href="<?php echo $liquidation_url ?>">
                                             All
                                        </a>
                                   </li>

                                   <li class="dropdown-submenu">
                                        <a class="sub-item submenu-anchor-l2" href="<?php echo $liquidation_url ?>?type=1">
                                             Liquidation Pallet
                                        </a>
                                   </li>

                                   <li class="dropdown-submenu">
                                        <a class="sub-item submenu-anchor-l2" href="<?php echo $liquidation_url ?>?type=2">
                                             Liquidation Lots
                                        </a>
                                   </li>

                                   <li class="dropdown-submenu">
                                        <a class="sub-item submenu-anchor-l2" href="<?php echo $liquidation_url ?>?type=3">
                                             Liquidation Truckloads
                                        </a>
                                   </li>
                              </ul>
                         </li>
                    </ul>


                    <ul class="nav navbar-nav logo-nav logo-hide-mbl ">
                         <li class="nav-item">
                              <a style="    padding: 0px;" class="dropdown-item  nav-link" tabindex="-1" href="<?php echo base_url(); ?>"><img class="logo-img" style="padding-bottom: 9px;" src="<?php echo base_url() ?>uploads/vegas-liquidation.png"></a>
                         </li>
                    </ul>


                    <ul class="navbar-nav ">

                         <li class="dropdown-submenu nav-item search-nav-link">
                              <a class="dropdown-item  nav-link"><i class="fa fa-search mr-2"></i>Search </a>
                         </li>

                         <li class="dropdown-submenu nav-item ">
                              <a class="dropdown-item  nav-link" tabindex="-1">My Account</a>
                              <ul class="dropdown-menu make-dropdown-menu-full">
                                   <?php if ($this->session->userdata('customer_login') && $this->session->userdata('user_id')) { ?>
                                        <li class="dropdown-submenu" style="padding-top: 5px;padding-bottom: 5px;">
                                             <a style="font-size: 16px;cursor: pointer;" href="<?php echo base_url(); ?>profile" class="sub-item submenu-anchor-l2  <?php if (isset($active)  and $active == 'profile') {
                                                                                                                                                                          echo 'active';
                                                                                                                                                                     } ?>">Profile</a>
                                        </li>
                                        <li class="dropdown-submenu" style="padding-top: 5px;padding-bottom: 5px;">
                                             <a style="font-size: 16px;cursor: pointer;" href="<?php echo base_url(); ?>customer/orders" class="sub-item submenu-anchor-l2  <?php if (isset($active)  and $active == 'customer_orders') {
                                                                                                                                                                                    echo 'active';
                                                                                                                                                                               } ?>">My Orders</a>
                                        </li>
                                        <li class="dropdown-submenu" style="padding-top: 5px;padding-bottom: 5px;"> <a style="font-size: 16px;cursor: pointer;" class=" sub-item submenu-anchor-l2 <?php if (isset($active)  and $active == 'contact') {
                                                                                                                                                                                                        echo 'active';
                                                                                                                                                                                                   } ?>" href="<?php echo base_url(); ?>logout">Logout</a>
                                        </li>
                                   <?php } else { ?>

                                        <li class="dropdown-submenu" style="padding-top: 5px;padding-bottom: 5px;">
                                             <a class="sub-item" style="font-size: 16px;cursor: pointer;padding-left: 5px;" data-target="#signupModal" data-toggle="modal">Register</a> |
                                             <a class="sub-item" style="font-size: 16px;cursor: pointer;" data-target="#loginModal" data-toggle="modal">Login</a>
                                        </li>
                                   <?php } ?>
                              </ul>
                         </li>

                         <li class="nav-item <?php if (isset($active) and $active == 'cart') {
                                                  echo 'active';
                                             } ?>">

                              <a class="nav-link <?php if (isset($active)  and $active == 'cart') {
                                                       echo 'active';
                                                  } ?>" href="<?php echo base_url(); ?>cart/">
                                   <i class="fa" style="font-size:15px">&#xf07a;</i>
                                   <span class='badge badge-warning' id='lblCartCount'> 0 </span>
                              </a>
                         </li>

                    </ul>
               </div>
          </nav>
     </header>

     <div class="row" style=" width: 100%;height: 49px; margin-bottom: 18px; margin-left: 15px !important;">
          <div id="google_translate_element" style="padding-right: 0px; padding-left: 10px"></div>
     </div>