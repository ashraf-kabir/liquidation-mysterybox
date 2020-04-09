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
        <form action="/{{{portal}}}/register"  method="POST" class="mkd-form-signup-container">
            <p style="text-align:center">xyzRegister</p>
            <input type="text" class="form-control" placeholder="xyzFirst Name" name="first_name" required="true" autofocus="true">
            <input type="text" class="form-control" placeholder="xyzLast Name" name="last_name" required="true">
            <input type="email" class="form-control" placeholder="xyzEmail address" name="email" required="true">
            <input type="password" class="form-control" placeholder="xyzPassword" name="password" required="true">
            <input type="password" class="form-control" placeholder="xyzRepeat Password" name="confirm_password" required="true">
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
            <button class="btn btn-primary btn-block" type="submit">
                <i class="fas fa-user-plus"></i> xyzSign Up
            </button>
        </form>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" crossorigin="anonymous"></script>
    <!-- Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019 -->
</body>
</html>