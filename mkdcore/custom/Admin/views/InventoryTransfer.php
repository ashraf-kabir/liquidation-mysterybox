<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2020*/
$QUERY_STRING = isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : '';
?>
<div class="tab-content mx-4" id="nav-tabContent">
<br>
<div class="clear"></div>
<div aria-label="breadcrumb">
    <ol class="breadcrumb pl-0 mb-4 bg-background d-flex justify-content-center justify-content-md-start" style="background-color: inherit;">
        <li class="breadcrumb-item active" aria-current="page">
            <?php if($this->session->userdata('role') == 2) { ?>
                <a href="/admin/inventory/0" class="breadcrumb-link"><?php echo $heading;?></a>  
            <?php }elseif($this->session->userdata('role') == 4) { ?>
                <a href="/manager/inventory/0" class="breadcrumb-link"><?php echo $heading;?></a>
            <?php } ?>
        </li>
        <li class="breadcrumb-item active" aria-current="page">
            Transfer
        </li>
    </ol>
</div>
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

<section>
<div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="card" id="">
            <div class="card-body">
            ``  <div class='row mb-4'>
					<div class='col-2'>
						Product Name:
					</div>
					<div class='col'>
						<?php echo $inventory_item->product_name;?>
					</div>
				</div>

				<div class='row mb-4'>
					<div class='col-2'>
						SKU:
					</div>
					<div class='col'>
						<?php echo $inventory_item->sku;?>
					</div>
				</div>

                <?= form_open() ?>

                    <div class="form-group">
                        <label for="">Transfer From</label>
                        <select required name="from_store" id="from_store" class="form-control" store-data="<?php echo  $store_data ?>">
                        <option value="">--Select Store--</option>
                        <?php foreach ($store_inventory as $key => $value): ?>
                            <option value="<?php echo $value->store_id ?>">
                            <?php echo $value->store ?>
                            </option>

                        <?php endforeach; ?>
                        
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="">Quantity</label>
                        <select required name="from_quantity" id="from_quantity" class="form-control">
                            
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="">To</label>
                        <select required name="to_store" id="to_store" class="form-control">
                        <?php foreach ($store_inventory as $key => $value): ?>
                            <option value="<?php echo $value->store_id ?>">
                            <?php echo $value->store ?>
                            </option>

                        <?php endforeach; ?>
                        
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary" name="submit_inventory_transfer"> Submit</button>
                </form>
                

                
            </div>
        </div>
    </div>
</section>


</div>
<?php
if ($layout_clean_mode) {
    echo '<style>#content{padding:0px !important;}</style>';
    echo '<style>#tab-content{padding:0px !important; margin:0px !important;}</style>';
}
?>


<script>
    document.querySelector('#from_store').addEventListener('change', function (event){
        if(event.target == ''){
            return;
        }
        let store_id = event.target.value;
        let store_data = JSON.parse( `${atob(event.target.getAttribute('store-data')) }`);

        let store = null;
        store_data.forEach((element)=>{
            if(element.store_id === store_id){
                store = element;
            }
        });
        let options_template = '';
        console.log(store);
        for(let i = 1; i <= store.quantity; i++ ){
            options_template += `<option value="${i}"> ${i} </option>`;
        }
        document.querySelector("#from_quantity").innerHTML = options_template;

        console.log(options_template);
        // console.log(store);

        // console.log(store_data);
        // console.log(store_id);
    });

    function validateInventoryTransferForm(){

    }
</script>