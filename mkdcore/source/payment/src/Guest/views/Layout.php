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

<!-- Modal -->
<div class="modal fade" id="subscriptionModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">xyzSubscribe</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <form action="/subscribe" class='billable-class' id='payment-form' >
              <div class="form-group">
                  <label for="email">xyzEmail</label>
                  <input type="email" name='email' id='subscription-form-email' class='form-control'>
              </div>
              <div class="form-group">
                  <label for="plan">xyzPlans</label>
                  <select name="plan" class='form-control' id='plan_id'>
                      <?php foreach($this->_plans as $plan):?>
                          <option value="<?php echo $plan->id; ?>"><?php echo $plan->display_name .' : $'. number_format($plan->amount, 2);  ?> xyzPer <?php echo ucfirst( $this->_interval_mapping[$plan->subscription_interval]) ?></option>
                      <?php endforeach;?>
                    </select>
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
                  <input type="submit" class='btn btn-primary btn-block' value='xyzSubscribe'>
              </div>
          </form>
      </div>
    </div>
  </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" crossorigin="anonymous"></script>
<script src="https://js.stripe.com/v3/"></script>
<script src="/assets/js/stripe_client.js"></script>
<!-- Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019 -->
</body>
</html>

