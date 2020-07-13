<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
    <link rel="stylesheet" href="/assets/css/auth.css">
    <title><?php echo $title ?? 'xyzPayments'; ?></title>
</head>
<body>
    <nav class="navbar navbar-expand-sm navbar-dark theme bg-primary mb-3">
        <div class="container">
            <a  style='color: #FBFDFF;' class="navbar-brand" href="#"><?php echo $site_title ?? ''; ?></a>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <?php if(isset($page)):?>
        <?php $this->load->view($page);?>
    <?php endif;?>
    <!-- Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019 -->
    <footer class='auth-footer mt-5'>
       <div class="container auth-footer-content pt-4 pb-4  d-none d-lg-block">
            <div class="row ">
                <div class="col-md-6 footer-left">
                    <span class='footer-text'>&copy; <?php echo date("yy"); ?> <?php echo $site_title ?? ''; ?>. All Rights Reserved.</span>
                </div>
                <div class="col-md-6 footer-right">
                </div>
            </div>
       </div>
       <div class="container auth-footer-content mt-4 pb-4   d-sm-block d-md-none d-lg-none">
            <ul class='auth-footer-mb-nav'>
                <li class='mt-1'>
                    <span class='footer-text'>&copy; <?php echo date("yy"); ?> <?php echo $site_title ?? ''; ?> . All Right Reserved.</span>
                </li>
                <li  class='mt-1'> 
                </li>
            </ul>
       </div>
    </footer>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" crossorigin="anonymous"></script>
<script src="https://js.stripe.com/v3/"></script>
<script src="/assets/js/stripe_client.js"></script>
<!-- Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019 -->
</body>
</html>

