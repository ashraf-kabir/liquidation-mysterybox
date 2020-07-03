<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
    <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/fontawesome.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="/assets/css/auth.css">
    <title>{{{title}}} | xyzRegister</title>
</head>
    <nav class="navbar navbar-expand-sm navbar-dark theme bg-primary mb-3">
        <div class="container">
            <a  style='color: #FBFDFF;' class="navbar-brand" href="#">{{{title}}}</a>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a style='color: #FBFDFF;' class="nav-link" href="/{{{portal}}}/login">xyzLogin</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <section>
        <div class="row">
            <div class="col-lg-3 col-md-3 col-sm-1 col-xs-1"></div>
            <div class="col-lg-6 col-md-6 col-sm-10 col-xs-10 p-5 auth-wrap">
                <div class='sign-up-text'>
                    <h1>xyzRegister</h1>
                </div>
                <div class="form-container p-5">
                    <?php echo form_open('/{{{portal}}}/register');?>
                        <?php if (strlen($error) > 0) : ?>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="alert alert-danger" role="alert">
                                        <?php echo $error; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php if (validation_errors()) : ?>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="alert alert-danger" role="alert">
                                        <?= validation_errors() ?>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php if (strlen($success) > 0) : ?>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="alert alert-success" role="alert">
                                        <?php echo $success; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                        <div class="form-group">
                            <label class='required' for="first name">xyzFirst Name</label>
                            <input type="text" class="form-control site-input" name="first_name" required="true" autofocus="true">
                        </div>
                        <div class="form-group">
                            <label class='required' for="last name">xyzLast Name</label>
                            <input type="text" class="form-control site-input" name="last_name" required="true">
                        </div>
                        <div class="form-group">
                            <label class='required' for="email address">xyzEmail address</label>
                            <input type="email" class="form-control site-input"  name="email" required="true">
                        </div>
                        <div class="form-group">
                            <label class='required' for="password">xyzPassword</label>
                            <input type="password" class="form-control site-input"  name="password" required="true">
                        </div>
                        <div class="form-group">
                            <label class='required' for="password">xyzRepeat Password</label>
                            <input type="password" class="form-control site-input"  name="confirm_password" required="true">
                        </div>

                        <div class="form-group">
                            <input type="submit" name='btn-login' class="btn btn-accent-light btn-block" value="xyzRegister">
                        </div>
                    </form>
                    <div class="ln-text mt-3">
                        <span> OR </span>
                    </div>
                    <div class="form-group mt-3">
                        <a href="<?php echo $google_auth_url;?>" class="btn btn-accent-light-outline btn-sign-up btn-block" >
                           <i class="fa fa-google" aria-hidden="true"></i>&nbsp;
                            xyzUse your Google account
                        </a>
                    </div>
                    <div class="form-group mt-3">
                        <a href="<?php echo '$facebook_auth_url';?>" class="btn btn-accent-light-outline btn-sign-up btn-block" >
                            <i class="fa fa-facebook" aria-hidden="true"></i>&nbsp;
                            xyzUse your Facebook account
                        </a>
                    </div>
                    <div class="form-group mt-3 d-flex justify-content-center">
                        <a href="/{{{portal}}}/login" id="mkd-forgot-password-link">xyzBack</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-1 col-xs-1"></div>
        </div>
    </section>                      
     <!-- Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019 -->
     <footer class='auth-footer mt-5'>
       <div class="container auth-footer-content pt-4 pb-4  d-none d-lg-block">
            <div class="row ">
                <div class="col-md-6 footer-left">
                    <span class='footer-text'>&copy; <?php echo date("yy"); ?> {{{title}}}. All Right Reserved.</span>
                </div>
                <div class="col-md-6 footer-right">
                </div>
            </div>
       </div>
       <div class="container auth-footer-content mt-4 pb-4   d-sm-block d-md-none d-lg-none">
            <ul class='auth-footer-mb-nav'>
                <li class='mt-1'>
                    <span class='footer-text'>&copy; <?php echo date("yy"); ?>  {{{title}}}. All Right Reserved.</span>
                </li>
                <li  class='mt-1'> 
                </li>
            </ul>
       </div>
    </footer>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" crossorigin="anonymous"></script>
<!-- Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019 -->
</body>
</html>
















