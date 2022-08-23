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
            <?= form_open() ?>
            <input type="hidden" id="encoded-stores" value="<?php echo $encoded_stores ?>">
                <div class="my-2" >
                    <div class="form-group col-md-5 p-0 d-inline-block">
                        <label for="">Scan Item</label>
                        <input type="text" name="sku" id="sku" class="form-control" focus="true">
                    </div>
                    <div class="form-group col-md-4 p-0 d-inline-block">
                        <span type="button" class="btn btn-primary" onclick="getProduct()">Get Product</span>
                    </div>
                </div>

                <div class="" id="transfer-section">
                    <!-- <div class='row mb-1 ' id="product-info" style="visibility:hidden">
                        <div class='display-5 p-3'>
                            Product Name: <span product-info="name"> </span>
                        </div>
                    </div> -->
                    <div class='row mb-1 ' id="product-not-found" style="">
                        <div class='display-5 p-3'>
                            <span id="message"> </span>
                        </div>
                    </div>
    
    
                    <div class="form-group">
                        <label for="">Transfer From</label>
                        <select required name="from_store" id="from_store" class="form-control"  store-data="">
                        <option value="">--Select Store--</option>
                        
                        
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
                        
                        
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary" name="submit_inventory_transfer"> Submit</button>
                </div>
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

    document.querySelector('#sku').addEventListener('DOMContentLoaded', function (event){
        event.target.focus();
    });
    document.querySelector('#sku').addEventListener('change', function (event){
        getProductBySKU(event.target.value);
    });


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
        for(let i = 1; i <= store.quantity; i++ ){
            options_template += `<option value="${i}"> ${i} </option>`;
        }
        document.querySelector("#from_quantity").innerHTML = options_template;

    });


    function getProductBySKU(sku) {
        let url = `/v1/api/product/sku/${sku}`;
        fetch(encodeURI(url))
        .then((response) => response.json())
        .then((data) => {
            // console.log(data)
            if(data.success){
                setMessage(''); //clear message
                setProductForTransfer(data.product);
            }else{
                setMessage('Item Not Found.');
                clearStoreInfo();
                
            }
        })
        .catch((err) => {
            console.error(err);
        });
    }

    function getProduct() {
        getProductBySKU(document.querySelector('#sku').value);
    }

    function setMessage(msg= ''){
        document.querySelector('#message').innerText = msg;
    }

    function setProductInfo(name = ''){
        document.querySelector('[product-info=name]').innerHTML = name;
        document.querySelector('#product-info').style.visibility = name === '' ? 'hidden' : 'visible';
    }

    function clearStoreInfo(){
        // setProductInfo('');
        document.querySelector('#to_store').innerHTML = "";
        document.querySelector('#from_store').innerHTML = "";

    }
    function setProductForTransfer(product) {
        console.log(product);
        //Set product name
        // setProductInfo(product.product_name);
        setMessage(`Product Name: ${product.product_name}`);
        // set From Store
        let store_data = JSON.parse(product.store_inventory) ?? [];
        let from_store_options = '<option value="">--Select Store--</option>';
        
        store_data.forEach(element => {
            from_store_options += `<option value="${element.store_id}">${getStoreName(element.store_id)}</option>`;
        });
        document.querySelector('#from_store').setAttribute('store-data', btoa(product.store_inventory));
        document.querySelector('#from_store').innerHTML = from_store_options; console.log(from_store_options)
        // Handle from store quantity
        // Set to store
        let to_store_options = '';
        store_data.forEach(element => {
            to_store_options += `<option value="${element.store_id}">${getStoreName(element.store_id)}</option>`;
        });
        document.querySelector('#to_store').innerHTML = to_store_options;
        // show product section

    }

    function getStoreName(store_id){
        let stores = JSON.parse( `${atob(document.querySelector('#encoded-stores').value) }`);
        let store = stores.filter(store => {
            return store.id === store_id;
        });

        return store[0].name;
    }

</script>