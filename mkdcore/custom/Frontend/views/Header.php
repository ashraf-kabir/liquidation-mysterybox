<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1, shrink-to-fit=no"
    />

    <!-- Bootstrap CSS -->
    <link
      rel="stylesheet"
      href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"
      integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z"
      crossorigin="anonymous"
    />

    <!-- Font Awesome -->
    <link
      rel="stylesheet"
      href="https://use.fontawesome.com/releases/v5.14.0/css/all.css"
      integrity="sha384-HzLeBuhoNPvSl5KYnjx0BT+WB0QEEqLprO+NBkkk5gbc67FTaL7XIGa2w1L0Xbgc"
      crossorigin="anonymous"
    />
    <!-- Google Font -->
    <link
      href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;800;900&display=swap"
      rel="stylesheet"
    />
    <!-- Hover css -->
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/hover.css/2.3.1/css/hover-min.css"
      integrity="sha512-csw0Ma4oXCAgd/d4nTcpoEoz4nYvvnk21a8VA2h2dzhPAvjbUIK6V3si7/g/HehwdunqqW18RwCJKpD7rL67Xg=="
      crossorigin="anonymous"
    />

    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/frontend_css/style.css" />
    <title>Vegas</title>
  </head>
   
  <body>
    <header class="container-fluid">
      <div class="row justify-content-between py-4 px-md-5">
        <div class="col-xl-3 col-lg-3 col-md-2 col-sm-2 col-12 mb-2 mb-md-0">
          <img src="<?php echo base_url(); ?>/assets/frontend_images/logo.png" alt="logo" class="logo" />
        </div>

        <div
class=" col-xl-5 col-lg-3 col-md-5 col-sm-7 col-12 d-flex justify-content-around align-items-center"
        >
          <a href="text-dark">My WishList <i class="fas fa-heart"></i></a>

          <?php if( !$this->session->userdata('customer_login') ){ ?>
            <button
              class="btn btn-outline-secondary"
              data-target="#loginModal"
              data-toggle="modal"
            >
              Login
            </button>
          <?php } ?>


          <?php if( !$this->session->userdata('customer_login') ){ ?>
          <button
            class="btn btn-outline-secondary"
            data-target="#signupModal"
            data-toggle="modal"
          >
            Register
          </button>
          <?php } ?>

          <?php if( $this->session->userdata('customer_login') ){ ?>  
          <a class="btn btn-outline-secondary" style="line-height: 2.5 ;" href="<?php echo base_url(); ?>cart" >
            Cart
          </a>


          <a class="btn btn-outline-secondary" style="line-height: 2.5 ;" href="<?php echo base_url(); ?>logout" >
            Logout
          </a>
          <?php } ?>

        </div>
      </div>

      <nav
        class="row navbar row navbar-expand-lg px-4 px-md-5 justify-content-end"
      >
        <button
          class="navbar-toggler bg-white"
          type="button"
          data-toggle="collapse"
          data-target="#navbarNav"
          aria-controls="navbarNav"
          aria-expanded="false"
          aria-label="Toggle navigation"
        >
          <i class="fas fa-bars navbar-toggler-icon"></i>
        </button>
        <div class="collapse navbar-collapse pr-md-4 mb-3 mt-3" id="navbarNav">
          <ul class="navbar-nav ml-auto">
            

            <li class="nav-item active">
              <a class="nav-link hvr-underline-from-left active-nav" href="<?php echo base_url(); ?>"
                >Home <span class="sr-only">(current)</span></a
              >
            </li>
            <li class="nav-item">
              <a class="nav-link hvr-underline-from-left" href="<?php echo base_url(); ?>categories"
                >Category</a
              >
            </li>
            
            <li class="nav-item">
              <a class="nav-link hvr-underline-from-left" href="<?php echo base_url(); ?>about_us">About</a>
            </li>
            <li class="nav-item">
              <a class="nav-link hvr-underline-from-left" href="<?php echo base_url(); ?>contact_us"
                >Contact Us</a
              >
            </li>
          </ul>
        </div>
      </nav>
    </header>

    
    <?php if(!isset($no_detail)){ ?>
    <div class="container-fluid">
      <div class="row justify-content-center mt-4 w-100">
        <form class="form-row justify-content-center col-12 col-md-8" method="GET" action="<?php echo base_url(); ?>categories">
          <div class="col-xl-3 col-lg-3 col-md-4 col-sm-5 col-12">
            <select name="category_id" id="category_id" class="form-control my-2">
                <option value="">Select Category</option>
               <?php foreach($all_categories as $key => $category){ ?>
                  <option <?= ( isset($category_id) AND $category_id == $category->id) ? 'selected' : ''; ?>  value="<?= $category->id; ?>"><?= $category->name; ?></option>
               <?php } ?>
            </select>
          </div>
          <div class="col-xl-6 col-lg-6 col-md-5 col-sm-6 col-12">
            <input
              type="text"
              id="search-query"
              name="search_query"
              placeholder="Search"
              class="form-control mx-md-2 my-2"
            />
          </div>
          <div class="col-xl-3 col-lg-3 col-md-6 col-sm-11 col-12">
            <button class="btn btn-secondary mx-md-2 w-100 my-2">Search</button>
          </div>
        </form>
      </div>
    </div>
    <?php } ?>