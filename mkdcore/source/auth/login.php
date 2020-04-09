<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
    <link rel="stylesheet" href="/assets/css/style.css">
    <title>{{{title}}}</title>
</head>
<body>
    <div id="mkd-login-container">
        <form class="mkd-login-form-container" method="POST" action="/{{{portal}}}/login">
            <h1 class="h3 mb-3 font-weight-normal text-center"> xyzSign in</h1>
            <div class="social-login">
                <a href="<?php echo '$facebook_auth_url';?>" class="btn facebook-btn social-btn"><span><i class="fab fa-facebook-f"></i> xyzSign in with Facebook</span> </a>
                <a href="<?php echo $google_auth_url;?>" class="btn google-btn social-btn"><span><i class="fab fa-google-plus-g"></i> xyzSign in with Google+</span> </a>
            </div>
            <p class="text-center"> xyzOR  </p>
            <input name="email" type="email" class="form-control" placeholder="xyzEmail address" required="true" autofocus="true"/>
            <input name="password" type="password" class="form-control" placeholder="xyzPassword" required="true"/>
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
            <button class="btn btn-success btn-block" type="submit">
                <i class="fas fa-sign-in-alt"></i> xyzSign in
            </button>
                <a href="/{{{portal}}}/forgot" id="mkd-forgot-password-link">xyzForgot password?</a>
            <hr>
            <a class="btn btn-primary btn-block text-white" href="/{{{portal}}}/register">
                <i class="fas fa-user-plus"></i> xyzSign up New Account
            </a>
        </form>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" crossorigin="anonymous"></script>
    <!-- Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019 -->
</body>
</html>