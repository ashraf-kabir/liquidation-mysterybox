<!DOCTYPE html>
<html lang="en">
<head>
     <!-- Required meta tags -->
     <meta charset="utf-8">
     <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

     <!-- Bootstrap CSS -->
     <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">

     <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
     <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.1/css/all.css" integrity="sha384-vp86vTRFVJgpjF9jiIGPEEqYqlDwgyBgEF109VFjmqGmIY/Y4HV4d3Gp2irVfcrp" crossorigin="anonymous">

     <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>

     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">
     <!-- Local CSS -->
     <link rel="stylesheet" href="/assets/css/frontend_style.css">
     <link rel="stylesheet" href="/assets/frontend_css/swiper-bundle.min.css">

     <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
     <title>Vegas Liquidation</title>

     <style type="text/css">
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
               font-size: 13px;
              background: #ff0000;
              color: #fff;
              padding: 0 5px;
              vertical-align: top;
              margin-left: -4px;
          }

          
          @media  screen and (max-width:767px){

               .dropdown-submenu ul{
                    background-color: #6f6d6d !important;
               }
               .dropdown-menu .dropdown-submenu a
               {
                    color: white !important;
               }
          }

          .top_header_css{
               background: <?php echo $home_page_setting->home_page_top_bg; ?>;
              color: <?php echo $home_page_setting->home_page_top_color; ?>;
              text-align: center;
              padding: 12px 0px;
          }
          .make-dropdown-menu-full{
               width: 100% !important;
          }

          .submenu-anchor{
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

          .submenu-anchor-l2{
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
              padding: 0px 18px 0px 10px !important;
          }

          .submenu-li{
             border-bottom: 1px solid #d4dde2
          }





 
          .dropdown-submenu-c {
              position: relative;
              padding: 0px 18px 0px 10px;
          }

          .dropdown-submenu-c>.dropdown-menu {
              top: 0;
              left: 100%;
              margin-top: -6px;
              margin-left: -1px;
              -webkit-border-radius: 0 6px 6px 6px;
              -moz-border-radius: 0 6px 6px;
              border-radius: 0 6px 6px 6px;
          }

          .dropdown:hover>.dropdown-menu {
              display: block;
          }

          .dropdown-submenu-c:hover>.dropdown-menu {
              display: block;
          }

          .dropdown-submenu-c>a:after {
              display: block;
              content: " ";
              float: right;
              width: 0;
              height: 0;
              border-color: transparent;
              border-style: solid;
              border-width: 5px 0 5px 5px;
              border-left-color: #ccc;
              margin-top: 5px;
              margin-right: -10px;
          }

          .dropdown-submenu-c:hover>a:after {
              border-left-color: #fff;
          }


     </style>
</head>
<body>


     <?php if (!empty($home_page_setting->home_page_top_text) ): ?>   
     <header class="container-fluid top_header_css" >
          <?php echo $home_page_setting->home_page_top_text; ?>
     </header>
     <?php endif ?>


     <header class="container-fluid "> 
          <nav class="navbar navbar-expand-md row py-3 py-md-0 justify-content-md-center" style="margin-top: 0px;background: none !important;">
               <button class="navbar-toggler ml-3" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"><i class="fas fa-bars mt-1"></i></span>
               </button>
               <div class="collapse navbar-collapse col-md-12" id="navbarNavDropdown">
                     
                    <ul class="nav navbar-nav">
                         
                           
                         <li class="dropdown-submenu nav-item ">
                              <a  class="dropdown-item nav-link" tabindex="-1"  >Shop Mysterybox<b class="caret"></b></a>
                              <ul class="dropdown-menu make-dropdown-menu-full"> 
                                   <li class="dropdown-submenu">
                                   <a class="main-item"  style="text-align: center; padding: 4px 9px;">
                                        Mystery Box 
                                   </a> 
                                   <div class="dropdown-divider"></div>  
                                   <?php 
                                   foreach ($all_categories as $key => $category): 
                                        if (!empty($category->parent_category_id) ) 
                                        { 
                                             ?>
                                             <a class="sub-item submenu-anchor-l2" href="<?php echo base_url(); ?>categories/?category=<?php echo $category->id; ?>">
                                                  <?php echo $category->name; ?>
                                             </a> 
                                             <div class="dropdown-divider"></div> 
                                        <?php } ?>
                                   <?php endforeach ?>
                                   
                                   </li> 
                              </ul>
                         </li>


                         <li class="dropdown nav-item ">
                              <a href="#" class="dropdown-toggle dropdown-item nav-link" data-toggle="dropdown">View Current Inventory <b class="caret"></b></a>
                              <ul class="dropdown-menu multi-level" style="width: 100%;top: 89%;">                        
                                   <li class="dropdown-submenu dropdown-submenu-c">
                                        <a   class="dropdown-toggle submenu-anchor" data-toggle="dropdown">Liquidation Pallets</a>
                                      
                                        <ul class="dropdown-menu">
                                             <?php foreach ($liquidation_pal as $key => $value): ?>
                                                  <li><a class="submenu-anchor-l2" href="#"><?php echo $value->item_name ?></a><div class="dropdown-divider"></div></li>
                                             <?php endforeach ?>  
                                        </ul>
                                        <div class="dropdown-divider"></div>
                                   </li>


                                   <li class="dropdown-submenu dropdown-submenu-c">
                                        <a   class="dropdown-toggle submenu-anchor" data-toggle="dropdown">Liquidation Lots</a>
                                      
                                        <ul class="dropdown-menu">
                                             <?php foreach ($liquidation_lot as $key => $value): ?>
                                                  <li><a class="submenu-anchor-l2" href="#"><?php echo $value->item_name ?></a><div class="dropdown-divider"></div></li>
                                             <?php endforeach ?> 
                                        </ul>
                                        <div class="dropdown-divider"></div>
                                   </li>

                                   <li class="dropdown-submenu dropdown-submenu-c">
                                        <a   class="dropdown-toggle submenu-anchor" data-toggle="dropdown">Liquidation Truckloads</a>
                                      
                                        <ul class="dropdown-menu">
                                             <?php foreach ($liquidation_trk as $key => $value): ?>
                                                  <li><a class="submenu-anchor-l2" href="#"><?php echo $value->item_name ?></a><div class="dropdown-divider"></div></li>
                                             <?php endforeach ?> 
                                        </ul>
                                        <div class="dropdown-divider"></div>
                                   </li>
                              </ul>
                         </li>  
                    </ul>

                    <ul class="navbar-nav ml-auto">
                           
                         <?php if($this->session->userdata('customer_login') && $this->session->userdata('user_id') ){ ?>  
                              
                              <li class="nav-item <?php if( isset($active) and $active == 'profile' ){ echo 'active'; } ?>">
                                   <a  href="<?php echo base_url(); ?>profile" class="nav-link <?php if( isset($active)  and $active == 'profile'  ){ echo 'active'; } ?>"    >Profile</a>
                              </li> 


                              <li class="nav-item <?php if( isset($active) and $active == 'contact' ){ echo 'active'; } ?>">
                                   <a class="nav-link <?php if( isset($active)  and $active == 'contact'  ){ echo 'active'; } ?>"  href="<?php echo base_url(); ?>logout" >Logout</a>
                              </li>   

                         <?php }else { ?>  
                              <li class="dropdown-submenu nav-item ">
                                   <a  class="dropdown-item nav-link" tabindex="-1"  >My Account</a>
                                   <ul class="dropdown-menu make-dropdown-menu-full"> 
                                        <li class="dropdown-submenu">  
                                             <a class="sub-item" style="font-size: 16px;cursor: pointer;" data-target="#signupModal" data-toggle="modal">Register</a> |  
                                             <a class="sub-item" style="font-size: 16px;cursor: pointer;"  data-target="#loginModal" data-toggle="modal">Login</a> 
                                        </li> 
                                   </ul>
                              </li> 
                         <?php } ?>

                         <li class="nav-item <?php if( isset($active) and $active == 'cart' ){ echo 'active'; } ?>">

                              <a class="nav-link <?php if( isset($active)  and $active == 'cart'  ){ echo 'active'; } ?>" href="<?php echo base_url(); ?>cart/"> 
                                   <i class="fa" style="font-size:15px">&#xf07a;</i>
                                   <span class='badge badge-warning' id='lblCartCount'> 0 </span>
                              </a>
                         </li> 
                         
                    </ul>
               </div>
          </nav>
     </header> 