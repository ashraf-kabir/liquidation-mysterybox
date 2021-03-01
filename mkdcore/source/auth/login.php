<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
    <title>{{{title}}}</title>
    <style>
        .form-container {
            width: 40%;
            min-width: 300px;
            max-width: 500px;
            border: 1px solid #ccc;
            height: auto;
            margin: 50px auto;
        }
        .btn-primary {
            background-color: #2C5ED6;
        }
        @media (max-width: 500px) {
            h1 {
                font-size: 24px;
            }
            .form-container {
                margin: 10px auto;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-sm navbar-dark bg-primary mb-3">
        <div class="container">
            <a   class="navbar-brand" href="#">
            {{{title}}}
            <!-- <img style="height:35px" src="/assets/image/logo.png"/> -->
            </a>
            <div class="collapse navbar-collapse" id="navbarNav">
            </div>
        </div>
    </nav>

    <div>
        <div class='text-center'>
            <h1>xyzLogin to your portal</h1>
        </div>
        <div class="form-container p-5">
                <?php echo form_open();?>
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
                    <label class='required' for="xyzEmail">xyzEmail</label>
                    <input type="email" class="form-control site-input" id="email" name="email"  required="true" />
                </div>
                <div class="form-group text-container">
                    <label class='required' for="xyzPassword">xyzPassword </label>
                    <input type="password" class="form-control site-input" id="password" name="password"  required="true" >
                </div>
                <div class="form-group">
                    <input type="submit" name='btn-login' class="btn btn-primary btn-block" value="xyzLogin">
                </div>
                <div class="form-group mt-3 d-flex justify-content-center">
                    <a href="/<?php echo $portal;?>/forgot" id="mkd-forgot-password-link">xyzForgot password?</a>
                </div>
            </form>
        </div>
    </div>
    <div class='text-center'>&copy; <?php echo date("Y"); ?> {{{title}}}. All Right Reserved.</div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" crossorigin="anonymous"></script>
    <!-- Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2020 -->
</body>
</html>

