<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <title>xyzProducts</title>
  </head>
  <body>
    <nav class="navbar navbar-dark bg-dark">
        <a class="navbar-brand" href="#">
            Shop
        </a>
    </nav>
    <main>
        <section class='p-4'>
           <div class="row">
                <div class="col-7">
                    <div class="card">
                        <div class="card-body">
                            <ul class="list-group">
                                <li class="list-group-item active">xyzProduct Details</li>
                                <li class="list-group-item"><?php echo $product->name;?></li>
                                <li class="list-group-item"><?php echo $product->description; ?></li>
                                <li class="list-group-item">$<?php echo number_format($product->price,2);?></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-5">
                    <div class="card">
                        <div class="card-body">
                            <form action="/subscribe" method='POST' class='billable-class' id='payment-form' >
                                <div class="form-group">
                                    <label for="email">xyzEmail</label>
                                    <input type="email" name='email' id='subscription-form-email' class='form-control'>
                                </div>
                                <div class="form-group">
                                    <label for="email">xyzOrder Quantity</label>
                                    <input type="email" name='email' id='subscription-form-email' class='form-control'>
                                </div>
                                <div class="form-group">
                                    <label for="card name">xyzCard Name</label>
                                    <input type="text" name='card_name' class='form-control'>
                                </div>
                                <div class="form-group" style='width:100%;' >
                                    <label for="card-element">
                                        xyzCredit or debit card
                                    </label>
                                    <div id="card-element" class="form-control"></div>
                                    <div id="card-errors" role="alert"></div>
                                </div>
                                <div class="form-group">
                                    <input type="submit" class='btn  btn-accent-light btn-block' value='xyzSubscribe'>
                                </div>
                            </form>  
                        </div>
                    </div>
                </div>

           </div>
        </section>
    </main>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="https://js.stripe.com/v3/"></script>
    <script src="/assets/js/stripe_client.js"></script>
    <style>
        body{
            background-color:#f3f3f3;
        }
    </style>
  </body>
</html>