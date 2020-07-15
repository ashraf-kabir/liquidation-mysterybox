<section>
        <div class="row">
            <div class="col-lg-3 col-md-3 col-sm-1 col-xs-1"></div>
            <div class="col-lg-6 col-md-6 col-sm-10 col-xs-10 p-5 auth-wrap">
                <div class='sign-up-text'>
                    <h1>xyzSubscribe</h1>
                </div>
                <div class="form-container p-5">
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
                    <form action="/subscribe" method='POST' class='billable-class' id='payment-form' >
                        <div class="form-group">
                            <label for="email">xyzEmail</label>
                            <input type="email" name='email' id='subscription-form-email' class='form-control'>
                        </div>
                        <div class="form-group">
                            <label for="plan">xyzPlans</label>
                            <select name="plan_id" class='form-control' id='plan_id'>
                            <option value="" selected>xyzChoose</option>
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
                            <input type="submit" class='btn  btn-accent-light btn-block' value='xyzSubscribe'>
                        </div>
                    </form>  
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-1 col-xs-1"></div>
        </div>
</section>