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
        <?php if (strlen($error) > 0) : ?>
            <div class="col-md-12">
                <div class="alert alert-danger" role="alert">
                    <?php echo $error; ?>
                </div>
            </div>
        <?php endif; ?>

        <form class="mkd-login-form-container" action="/{{{portal}}}/login">
            <h1 class="h3 mb-3 font-weight-normal text-center"> Sign in</h1>
            <div class="social-login">
                <button class="btn facebook-btn social-btn" type="button"><span><i class="fab fa-facebook-f"></i> Sign in with Facebook</span> </button>
                <button class="btn google-btn social-btn" type="button"><span><i class="fab fa-google-plus-g"></i> Sign in with Google+</span> </button>
            </div>
            <p class="text-center"> OR  </p>
            <input type="email" class="form-control" placeholder="Email address" required="" autofocus="">
            <input type="password" class="form-control" placeholder="Password" required="">

            <button class="btn btn-success btn-block" type="submit">
                <i class="fas fa-sign-in-alt"></i> Sign in
            </button>
                <a href="#" id="mkd-forgot-password-link">Forgot password?</a>
            <hr>
            <button class="btn btn-primary btn-block" type="button" id="mkd-signup-button">
                <i class="fas fa-user-plus"></i> Sign up New Account
            </button>
        </form>

            <form action="/{{{portal}}}/reset" class="mkd-reset-form-container">
                <input type="email" class="form-control" placeholder="Email address" required="" autofocus="">
                <button class="btn btn-primary btn-block" type="submit">Reset Password</button>
                <a href="#" id="mkd-cancel-reset-link"><i class="fas fa-angle-left"></i> Back</a>
            </form>

            <form action="/{{{portal}}}/register" class="mkd-form-signup-container">
                <div class="social-login">
                    <button class="btn facebook-btn social-btn" type="button"><span><i class="fab fa-facebook-f"></i> Sign up with Facebook</span> </button>
                </div>
                <div class="social-login">
                    <button class="btn google-btn social-btn" type="button"><span><i class="fab fa-google-plus-g"></i> Sign up with Google+</span> </button>
                </div>

                <p style="text-align:center">OR</p>

                <input type="text" class="form-control" placeholder="Full name" required="" autofocus="">
                <input type="email" class="form-control" placeholder="Email address" required autofocus="">
                <input type="password" class="form-control" placeholder="Password" required autofocus="">
                <input type="password" class="form-control" placeholder="Repeat Password" required autofocus="">

                <button class="btn btn-primary btn-block" type="submit">
                    <i class="fas fa-user-plus"></i> Sign Up
                </button>
                <a href="#" id="mkd-cancel-signup-link"><i class="fas fa-angle-left"></i> Back</a>
            </form>
            <br>

    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script src="/assets/js/main.js"></script>
    <!-- Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019 -->
</body>
</html>