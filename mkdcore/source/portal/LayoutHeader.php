<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>{{{title}}}</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" crossorigin="anonymous">
    <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/solid.js" crossorigin="anonymous"></script>
    <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/fontawesome.js" crossorigin="anonymous"></script>
    <!-- Our Vendor CSS -->
    <!-- Our Custom CSS -->
    {{{css}}}
</head>
<body>
<div class="wrapper">
        <!-- Sidebar  -->
    <?php if (!$layout_clean_mode) { ?>
        
        <nav class="navbar navbar-light px-4">
        <a class="navbar-brand" href="#">
            <h3>{{{company}}}</h3>
        </a>
        <a href="/{{{portal}}}/logout">
          <div class="logout d-flex align-items-center">
            <img src="/assets/image/logout.svg " alt="" class="mr-2" />
            <p class="paragraphText mb-0 mr-2">Logout</p>
          </div>
        </a>
      </nav>
     
    <?php } ?>
    
  <?php if (!$layout_clean_mode) { ?>
      <section class="content-wrapper ">
        <div class="category row">
          <img
            src="/assets/image/collapse-category.svg"
            class="category-expand-img bg-dark"
            alt=""
          />
          <div class="categoryList col-2 p-0">
            <div class="list-group pl-3" id="collapse-nav">
              <div class="collapse-category d-flex justify-content-end p-4">
                <img src="/assets/image/collapse-category.svg" class="category-collapse-img" alt=""/>
              </div>
             {{{menu}}}
           </div>
        </div> 
  <!--
        <nav id="sidebar category row">
            <ul class="list-unstyled components">
                {{{menu}}}
            </ul>
            <span class="copyright">{{{copyright}}}</span>
            <span class="copyright">{{{powered_by}}}</span>
        </nav>-->
    <?php } ?>
    <section class="d-flex flex-column col categoryContent mx-md-4 mt-2 px-md-4 p-0">
        <?php if(isset($page_section)):?>
            <?php  $this->load->view($page_section); ?>
        <?php endif;?>
    </section>
        </section>