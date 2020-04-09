<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" crossorigin="anonymous">
    <link rel="stylesheet" href="/assets/css/style.css">
    <title>{{{title}}}</title>
</head>
<body>
    <div id="mkd-login-container">
        <form action="/{{{portal}}}/forgot"  method="POST" class="mkd-reset-form-container">
            <h1 class="h3 mb-3 font-weight-normal text-center"> xyzForgot Password</h1>
            <input type="email" name="email" class="form-control" placeholder="xyzEmail address" required="true" autofocus="true">
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
            <button class="btn btn-primary btn-block" type="submit">xyzReset Password</button>
            <a href="/{{{portal}}}/login" id="mkd-cancel-reset-link"><i class="fas fa-angle-left"></i> xyzBack</a>
        </form>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" crossorigin="anonymous"></script>
    <!-- Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019 -->
</body>
</html>