<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootoast@1.0.1/dist/bootoast.min.css" crossorigin="anonymous">
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
    <div class="">
        <div class='text-center'>
            <h1>xyzVerify your Number</h1>
        </div>
        <div class="form-container p-5">
                    <?php echo form_open();?>
                        <?php if (strlen($error ?? '') > 0) : ?>
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
                        <?php if (strlen($success  ?? '') > 0) : ?>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="alert alert-success" role="alert">
                                        <?php echo $success; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                        <div class="form-group text-container">
                            <label class='required' for="Email">xyzVerification Code</label>
                            <input type="text" class="form-control site-input" id="verification_code" name="code"   value="<?php echo set_value('code'); ?>" required="true" >
                        </div>
                        <div class="form-group">
                            <input type="hidden" class="form-control site-input" id="phone_number" disabled name="phone" value="<?php echo $phone; ?>" />
                            <a id='btn-verify' class='btn btn-accent-light btn-block' href="#" style='text-decoration:underline;'>xyzResend Verification Code</a>
                        </div>
                        <div class="form-group">
                            <input type="submit" name='btn-login' class="btn btn-primary btn-block" value="xyzLogin">
                        </div>
                    </form>
    </div>
</div>
<div class='text-center'>&copy; <?php echo date("yy"); ?> {{{title}}}. All Right Reserved.</div>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootoast@1.0.1/dist/bootoast.min.js" crossorigin="anonymous"></script>
<script>
    $(document).ready(function () {
    $("#btn-verify").click(function (e) {
        e.preventDefault();
        var phone = $("#phone_number").val();
        var url = "/{{{portal}}}/sent_verification";
        var posting = $.post(url, { phone: phone });

        posting.done(function (data) {
        var data = JSON.parse(data);
        if (data.error == false) {
            bootoast.toast({
            message: "xyzVerification sent",
            type: "info",
            });
            return;
        }
        if (data.error == true) {
            bootoast.toast({
            message: data.message || "Error",
            type: "danger",
            });
            return;
        }
        });
        posting.fail(function () {
        bootoast.toast({
            message: "xyzError Sending Code",
            type: "danger",
        });
        });
    });
    });
</script>
<!-- Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2020 -->
</body>
</html>

