<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
    <link rel="stylesheet" href="/assets/css/style.css">
    <title>Subscriptions</title>
</head>
<body>
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#stripeModal">
        Make Subscription
    </button>
    <div class="modal fade" id="stripeModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Make Subscription</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" class='billable-class' id='payment-form' >
                        <div class="form-row">
                            <div class="form-group" style='width:100%;' >
                                <label for="exampleInputEmail1">Email address</label>
                                <input type="email" name='user_email' class="form-control" id="email" aria-describedby="emailHelp" placeholder="Enter email">
                            </div><br> 
                            <div class="form-group" style='width:100%;' >
                                <label for="exampleInputEmail1">Bearer Token (Only for testing)</label>
                                <input type="text" name='token' class="form-control" id="token" aria-describedby="emailHelp" placeholder="Token">
                            </div><br>
                            <div class="form-group" style='width:100%;' >
                                <label for="exampleInputEmail1">Coupon Code</label>
                                <input type="text" name='coupon_slug' class="form-control" id="coupon_slug" aria-describedby="emailHelp" placeholder="Token">
                            </div><br>
                            <div class="form-group" style='width:100%;'>
                                <label for="end point">Choose End Point</label>
                                <select name="endpoint" id='endpoint' class="form-control">
                                    <option value='/v1/api/subscription'>Assume user logged in(api/v1/subscription)</option>
                                    <option value='/v1/api/subscription/user'>Assume does not exist(api/v1/subscription/user)</option>
                                    <option value='/v1/api/subscription/mobile'>Mobile subscribe dont create account just subscribe (/v1/api/subscription/mobile) </option> 
                                    <option value='/v1/api/subscription/user/mobile'>Assume user from token API (getting id from auth token)(/v1/api/subscription/user/mobile) </option> 
                                </select>
                            </div>
                            <div class="form-group"  style='width:100%;'>
                                <label for="exampleInputEmail1">Plan *Get this from Database</label>
                                 <select name="plan" class='form-control' id='plan_id'>
                                    <?php foreach($plans as $plan):?>
                                        <option value="<?php echo $plan->id; ?>"><?php echo $plan->display_name .' : $'. number_format($plan->amount, 2);  ?></option>
                                    <?php endforeach;?>
                                </select>
                            </div>
                            <div class="form-group" style='width:100%;' >
                                <label for="card-element">
                                    Credit or debit card
                                </label>
                                <div id="card-element" class="form-control"></div>
                                <div id="card-errors" role="alert"></div>
                            </div>
                        </div><br>
                        <center><button class='btn btn-primary'>Submit Payment</button></center>
                    </form>
                </div>
                <div class="modal-footer">
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://js.stripe.com/v3/"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" crossorigin="anonymous"></script>
    <script src="/assets/js/stripe_client.js"></script>
    <script>
        function stripeTokenHandler(token) {
           //eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9sb2NhbGhvc3Q6OTAwMCIsImF1ZCI6Imh0dHA6XC9cL2xvY2FsaG9zdDo5MDAwIiwiaWF0IjoxNTgzMjQ0NTQwLCJuYmYiOjE1ODMyNDQ1NTAsImV4cCI6MTU4MzI0ODE1MCwiZGF0YSI6eyJ1c2VyX2lkIjoiMiIsInJvbGVfaWQiOiIxIn19.8Efr2HRmfe6sFnJY3GraEx8pFzEN-5TsnPk0Dfu_hbw
            // Insert the token ID into the form so it gets submitted to the server
            var form = document.getElementById('payment-form');
            var hiddenInput = document.createElement('input');
            hiddenInput.setAttribute('type', 'hidden');
            hiddenInput.setAttribute('name', 'stripeToken');
            hiddenInput.setAttribute('value', token.id);
            form.appendChild(hiddenInput);  
            var email =  $('#email').val();
            var plan_id = $('#plan_id').val();
            var auth_token = $('#token').val();
            var endpoint = $('#endpoint').val();
            var coupon_slug = $('#coupon_slug').val();
            var source = token.id;
            console.log( {email : email, plan_id : plan_id, source : source, coupon_slug :coupon_slug });
          
           $.ajax({
                url: endpoint,
                type: 'POST',
                beforeSend: function (xhr) {
                    xhr.setRequestHeader('Authorization', 'Bearer ' +  auth_token);
                },
                data: {email : email, plan_id : plan_id, source : source, coupon_slug :coupon_slug },
                success: function (data) { console.log(data); },
                error: function (data) { console.log(data); },
            });
            //{code: 200, success: true, message: "xyzSubscription Success"}
            //form.submit();
       }
    
    </script>
    <!-- Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019 -->
</body>
</html>