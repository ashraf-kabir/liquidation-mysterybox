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
     </style>
</head>
<body>
     <header class="container-fluid py-4">
          <form method="GET" action="<?php echo base_url(); ?>categories">
               <div class="row justify-content-center align-items-center my-2">

                    <div class="col-10 col-md-5 text-center text-d-left">
                         <a href="<?php echo base_url(); ?>"><h1>Vegas Liquidation</h1></a>
                    </div>
                    <div class="col-10 col-md-3 text-center text-md-right d-flex align-items-center my-2">
                         <input type="text" name="search_term" id="" class="form-control" placeholder="Product name or Sku">
                         <button class="btn btn-secondary" type="submit">SEARCH</button>
                    </div>
                    <div class="col-10 col-md-3 text-md-left d-flex align-items-center my-2 justify-content-center justify-content-md-start">
                         <i class="fas fa-phone-alt"></i>
                         <div class="header__contact">
                              <span class="text-gray">Phone:</span>
                              <h5 class="text-bold">702-451-1000</h5>
                         </div>
                    </div>

               </div>
          </form>
          <nav class="navbar navbar-expand-md row py-3 py-md-0 justify-content-md-center">
               <button class="navbar-toggler ml-3" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"><i class="fas fa-bars mt-1"></i></span>
               </button>
               <div class="collapse navbar-collapse col-md-12" id="navbarNavDropdown">
                    <ul class="navbar-nav">
                         <li class="nav-item <?php if( isset($active)  and $active == 'home' ){ echo 'active'; } ?> ">
                              <a class="nav-link <?php if( isset($active)  AND $active == 'home' ){ echo 'active'; } ?>" href="<?php echo base_url(); ?>categories">HOME <span class="sr-only">(current)</span></a>
                         </li>


                         <li class="dropdown-submenu nav-item d-none d-md-block">
                              <a  class="dropdown-item nav-link" tabindex="-1" href="<?php echo base_url(); ?>categories">Shop</a>
                              <ul class="dropdown-menu"> 
                                   <li class="dropdown-submenu">
                                   <a class="main-item" href="">
                                        Mystery Box
                                   </a> <br>

                                   <?php 
                                   foreach ($all_categories as $key => $category): 
                                        if (!empty($category->parent_category_id) ) 
                                        { 
                                             ?>
                                             <a class="sub-item" href="<?php echo base_url(); ?>categories/?category=<?php echo $category->id; ?>">
                                                  <?php echo $category->name; ?>
                                             </a> <br> 
                                        <?php } ?>
                                   <?php endforeach ?>
                                   
                                   </li> 
                              </ul>
                         </li>
 
                         <li class="nav-item">
                              <a class="nav-link" href="http://www.bulldogliquidators702.com/" target="_blank">store front</a>
                         </li>
                         <li class="nav-item">
                              <a class="nav-link" href="http://www.bulldogauctionz.com/Las-Vegas-nv/" target="_blank">auctions</a>
                         </li>
                         <li class="nav-item <?php if( isset($active)  and $active == 'about'  ){ echo 'active'; } ?>">
                              <a class="nav-link <?php if( isset($active)  and $active == 'about'  ){ echo 'active'; } ?>" href="<?php echo base_url(); ?>about_us/">about us</a>
                         </li>
                         <li class="nav-item <?php if( isset($active) and $active == 'contact' ){ echo 'active'; } ?>">
                              <a class="nav-link <?php if( isset($active)  and $active == 'contact'  ){ echo 'active'; } ?>" href="<?php echo base_url(); ?>contact_us/">contact</a>
                         </li> 

                          <li class="nav-item <?php if( isset($active) and $active == 'cart' ){ echo 'active'; } ?>">

                              <a class="nav-link <?php if( isset($active)  and $active == 'cart'  ){ echo 'active'; } ?>" href="<?php echo base_url(); ?>cart/">Cart 
                                   <i class="fa" style="font-size:15px">&#xf07a;</i>
                                   <span class='badge badge-warning' id='lblCartCount'> 0 </span>
                              </a>
                         </li>

                         
                         <?php if( !$this->session->userdata('customer_login') ){ ?> 

                              

                              <li class="nav-item <?php if( isset($active) and $active == 'contact' ){ echo 'active'; } ?>">
                                   <a class="nav-link <?php if( isset($active)  and $active == 'contact'  ){ echo 'active'; } ?>"   data-target="#loginModal" data-toggle="modal">Login</a>
                              </li> 

                              <li class="nav-item <?php if( isset($active) and $active == 'contact' ){ echo 'active'; } ?>">
                                   <a class="nav-link <?php if( isset($active)  and $active == 'contact'  ){ echo 'active'; } ?>"  data-target="#signupModal" data-toggle="modal">Register</a>
                              </li> 


                         <?php }else { ?> 

                              <li class="nav-item <?php if( isset($active) and $active == 'profile' ){ echo 'active'; } ?>">
                                   <a  href="<?php echo base_url(); ?>profile" class="nav-link <?php if( isset($active)  and $active == 'profile'  ){ echo 'active'; } ?>"    >Profile</a>
                              </li> 


                              <li class="nav-item <?php if( isset($active) and $active == 'contact' ){ echo 'active'; } ?>">
                                   <a class="nav-link <?php if( isset($active)  and $active == 'contact'  ){ echo 'active'; } ?>"  href="<?php echo base_url(); ?>logout" >Logout</a>
                              </li>  
                         <?php } ?>
                    </ul>
               </div>
          </nav>
     </header> 