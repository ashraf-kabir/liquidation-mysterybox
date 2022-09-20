<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
if ($layout_clean_mode) {
	echo '<style>#content{padding:0px !important;}</style>';
}
?>
<div class="tab-content mx-4" id="nav-tabContent">
              <!-- Bread Crumb -->
<div aria-label="breadcrumb">
    <ol class="breadcrumb pl-0 mb-4 bg-background d-flex justify-content-center justify-content-md-start" style="background-color: inherit;">
        <li class="breadcrumb-item active" aria-current="page">
            <a href="/admin/orders/0" class="breadcrumb-link"><?php echo $heading;?></a>
        </li>
        <li class="breadcrumb-item active" aria-current="page">
            Refund
        </li>
    </ol>
</div>
<?php if (validation_errors()) : ?>
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-danger" role="alert">
                <?= validation_errors() ?>
            </div>
        </div>
        </div>
    <?php endif; ?>
    <?php if (strlen($error) > 0) : ?>
        <div class="row">
        <div class="col-md-12">
            <div class="alert alert-danger" role="alert">
                <?php echo $error; ?>
            </div>
        </div>
        </div>
    <?php endif; ?>
    <?php if (strlen($success) > 0) : ?>
        <div class="row">
        <div class="col-md-12">
            <div class="alert alert-success" role="success">
                <?php echo $success; ?>
            </div>
        </div>
        </div>
    <?php endif; ?>
<div class="row mb-5">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="card pb-5" style='border-bottom:1px solid #ccc;'>
            <div class="card-body">
                <h5 class="primaryHeading2 text-md-left">
                    <?php echo $heading;?> Refund
                </h5>
                <?= form_open() ?>
				<div class="row">
                    <input type="hidden" required name="order_id" id="order_id" value="<?php echo $order->id; ?>">

                    <div class="form-group col-md-6 mx-1">
                        <label for="">Billing Name </label>
                        <input class="form-control"  readonly type="text"  value="<?php echo $order->billing_name ?>" />
                    </div>
                    <div class="form-group col-md-6 mx-1">
                        <label for="">Customer Email </label>
                        <input class="form-control"  readonly type="email"  value="<?php echo $order->customer_email ?>" />
                    </div>
                    <div class="form-group col-md-6 mx-1">
                        <label for="">Total Order Amount ($)</label>
                        <input class="form-control" required readonly type="number" name="total-amount" id="total-amount"  value="<?php echo number_format($order->total, 2) ?>" />
                    </div>
                    <div class="form-group col-md-6 mx-1">
                        <label for="">Subtotal ($)</label>
                        <input class="form-control"  readonly type="number"  value="<?php echo number_format($order->subtotal, 2) ?>" />
                    </div>
                    <div class="form-group col-md-6 mx-1">
                        <label for="">Tax ($)</label>
                        <input class="form-control"  readonly type="number"   value="<?php echo number_format($order->tax, 2) ?>" />
                    </div>
                    <div class="form-group col-md-6 mx-1">
                        <label for="">Amount ($)</label>
                        <input class="form-control" required type="number" name="amount" id="amount" step="0.01" value="<?php echo number_format($order->total, 2) ?>" 
                            max="<?php echo number_format($order->total, 2) ?>" oninput="handleAmountChange(this)"/>
                    </div>

                    <div class="form-group col-md-6 mx-1">
                        <button type="submit" id="refund-btn" name="refund-btn" class="btn btn-primary">Refund</button>
                    </div>
                </div>
                </form>
				

            </div>
        </div>
    </div>
</div>

<script>

    function makeRefund() {
        const amount = parseFloat(document.getElementById('amount').value);
        const total = parseFloat(document.getElementById('total-amount').value);
        if(amount < 0) {return alert('invalid amount');}
        if (amount > total ) {return  alert('invalid amount');}

        const order_id = document.getElementById('order_id').value;
        const url = "v1/api/nmi/refund";


    }

    function handleAmountChange(el) {
        const amount = parseFloat(document.getElementById('amount').value);
        const total = parseFloat(document.getElementById('total-amount').value);
        if(amount > 0 && amount <= total) {
            document.querySelector('#refund-btn').removeAttribute('disabled');
        }
        else {
            document.querySelector('#refund-btn').setAttribute('disabled', "true");
        }
    }


</script>