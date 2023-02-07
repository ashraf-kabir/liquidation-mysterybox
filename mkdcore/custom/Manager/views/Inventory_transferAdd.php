<?php
defined('BASEPATH') or exit('No direct script access allowed');
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2020*/
$QUERY_STRING = isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : '';
?>

<div class="tab-content mx-4" id="nav-tabContent">
    <br>
    <div class="clear"></div>
    <div aria-label="breadcrumb">
        <ol class="breadcrumb pl-0 mb-4 bg-background d-flex justify-content-center justify-content-md-start" style="background-color: inherit;">
            <li class="breadcrumb-item active" aria-current="page">
                <?php if ($this->session->userdata('role') == 2) { ?>
                    <a href="/admin/inventory/0" class="breadcrumb-link"><?php echo $heading; ?></a>
                <?php } elseif ($this->session->userdata('role') == 4) { ?>
                    <a href="/manager/inventory/0" class="breadcrumb-link"><?php echo $heading; ?></a>
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
                        <input type="hidden" id="encoded-locations" value="<?php echo $encoded_locations ?>">
                        <div class="my-2">
                            <div class="form-group col-md-5 p-0 d-inline-block">
                                <label for="">Scan Item</label>
                                <input type="text" name="sku" id="sku" class="form-control" focus="true">
                            </div>
                            <!-- <div class="form-group col-md-4 p-0 d-inline-block">
                        <span type="button" class="btn btn-primary" onclick="getProduct()">Get Product</span>
                    </div> -->
                        </div>
                        <div class="my-2">
                            <div class="form-group col-md-5 p-0 d-inline-block">
                                <label for=""> Items</label>
                                <select name="" id="items" class="form-control">
                                    <option value=""></option>
                                    <?php foreach ($inventory_items as $item) : ?>
                                        <option value="<?php echo $item->sku ?>"> <?php echo $item->sku . " - " . $item->product_name ?></option>
                                    <?php endforeach; ?>
                                </select>
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
                                <label for="">Product</label>
                                <input class="form-control" type="text" name="" id="product_name" value="" disabled>
                            </div>

                            <div class="form-group">
                                <label for="">Transfer From Store <span class="text-danger">*</span></label>
                                <select name="from_store" id="from_store" class="form-control" store-data="">
                                    <option value="">--Select Store--</option>


                                </select>
                            </div>

                            <div class="form-group">
                                <label for="">Store Physical Location <span class="text-danger">*</span></label>
                                <select name="from_location" id="from_location" class="form-control" location-data="">
                                    <option value="">--Select Store Physical Location--</option>


                                </select>
                            </div>

                            <div class="form-group">
                                <label for="">Quantity <span class="text-danger">*</span></label>
                                <select name="from_quantity" id="from_quantity" class="form-control">

                                </select>
                            </div>

                            <div class="form-group">
                                <label for="">To Store<span class="text-danger">*</span></label>
                                <select name="to_store" id="to_store" class="form-control">


                                </select>
                            </div>

                            <button type="button" class="btn btn-primary" onclick="addToTransferList()"> Add</button>
                        </div>

                        <div class="mt-2">
                            <table class="table">
                                <tr>
                                    <th>SKU</th>
                                    <th>From</th>
                                    <th>From Location</th>
                                    <th>To</th>
                                    <th>Quantity</th>
                                    <th>Action</th>
                                </tr>
                                <tbody id="transfer-list-table-body">

                                </tbody>
                            </table>
                        </div>

                        <button type="submit" class="btn btn-success" name="submit_inventory_transfer"> Submit</button>
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
    document.querySelector('#sku').addEventListener('DOMContentLoaded', function(event) {
        event.target.focus();
    });
    document.querySelector('#sku').addEventListener('input', function(event) {
        getProductBySKU(event.target.value);
    });
    // Hide TO STORE if it is selected in FROM STORE //////////
    document.querySelector('#from_store').addEventListener('change', function(event) {
        let selected_value = event.target.value;
        let to_options = document.querySelectorAll('#to_store option');
        for (const element of to_options) {
            element.style.display = 'block';
        }
        document.querySelector(`#to_store > option[value="${selected_value}"]`).style.display = 'none';
        document.querySelector('#to_store').value = "";
    });
    // /////////////////////////////////////   //////// //////

    document.querySelector('#items').addEventListener('change', function(event) {
        let sku = event.target.value;
        if (sku === '') {
            clearStoreInfo();
        }
        document.querySelector('#sku').value = sku;
        getProductBySKU(sku);
    });


    document.querySelector('#from_store').addEventListener('change', function(event) {
        initFromLocationHandler();
        if (event.target == '') {
            return;
        }
        let store_id = event.target.value;
        let store_data = JSON.parse(`${atob(event.target.getAttribute('store-data')) }`);

        let store = null;
        store_data.forEach((element) => {
            if (element.store_id === store_id) {
                store = element;
            }
        });
        let locations = store.locations;
        document.querySelector("#from_location").setAttribute('location-data', locations);

        let location_options_template = '<option value=""> -- Select Physical Location -- </option>';
        Object.entries(locations).forEach((location) => {
            location_options_template += `<option value="${location[0]}">${getLocationName(location[0])}</option>`;
        });

        document.querySelector("#from_location").innerHTML = location_options_template;
        document.querySelector("#from_location").setAttribute('location-data', JSON.stringify(locations));

    });

    document.querySelector('#from_location').addEventListener('change', function(event) {
        if (event.target == '') {
            console.log('nothing selected');
            return;
        }
        let location_id = event.target.value;
        let location_data = JSON.parse(event.target.getAttribute('location-data'));
        let location_quantity = 0;
        Object.entries(location_data).forEach((location) => {
            if (location[0] === location_id) {
                location_quantity = location[1];
            }
        });
        console.log('something');
        let options_template = '';
        for (let i = 1; i <= location_quantity; i++) {
            options_template += `<option value="${i}"> ${i} </option>`;
        }
        document.querySelector("#from_quantity").innerHTML = options_template;

    });
    // Select2 Handler for #from_location 
    function initFromLocationHandler() {
        $('#from_location').on('select2:select', function(event) {
            if (event.target == '') {
                return;
            }
            let location_id = event.target.value;
            let location_data = JSON.parse(event.target.getAttribute('location-data'));
            let location_quantity = 0;
            Object.entries(location_data).forEach((location) => {
                if (location[0] === location_id) {
                    location_quantity = location[1];
                }
            });
            let options_template = '';
            for (let i = 1; i <= location_quantity; i++) {
                options_template += `<option value="${i}"> ${i} </option>`;
            }
            document.querySelector("#from_quantity").innerHTML = options_template;
        });

    }


    function getProductBySKU(sku) {
        let url = `/v1/api/product/sku/${encodeURIComponent(sku)}`;
        fetch(url)
            .then((response) => response.json())
            .then((data) => {
                // console.log(data)
                if (data.success) {
                    setMessage(''); //clear message
                    setProductForTransfer(data.product);
                } else {
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

    function setMessage(msg = '') {
        document.querySelector('#message').innerText = msg;
    }

    function setProductInfo(name = '') {

        document.querySelector('#product_name').value = name;
        // document.querySelector('#product-info').style.visibility = name === '' ? 'hidden' : 'visible';
    }

    function clearStoreInfo() {
        setProductInfo('');
        document.querySelector('#to_store').innerHTML = "";
        document.querySelector('#from_store').innerHTML = "";

    }

    function setProductForTransfer(product) {
        //Set product name
        setProductInfo(product.product_name);
        // setMessage(`Product Name: ${product.product_name}`);
        // set From Store
        let store_data = JSON.parse(product.store_inventory) ?? [];
        let from_store_options = '<option value="">--Select Store--</option>';

        store_data.forEach(element => {
            from_store_options += `<option value="${element.store_id}">${getStoreName(element.store_id)}</option>`;
        });
        document.querySelector('#from_store').setAttribute('store-data', btoa(product.store_inventory));
        document.querySelector('#from_store').innerHTML = from_store_options;
        console.log(from_store_options)
        // Handle from store quantity
        // Set to store
        let to_store_options = '<option value=""></option>';
        store_data.forEach(element => {
            to_store_options += `<option value="${element.store_id}">${getStoreName(element.store_id)}</option>`;
        });
        document.querySelector('#to_store').innerHTML = to_store_options;
        // show product section

    }

    function getStoreName(store_id) {
        let stores = JSON.parse(`${atob(document.querySelector('#encoded-stores').value) }`);
        let store = stores.filter(store => {
            return store.id === store_id;
        });

        return store[0].name;
    }

    function getLocationName(location_id) {
        let locations = JSON.parse(`${atob(document.querySelector('#encoded-locations').value) }`);
        let location = locations.filter(location => {
            return location.id === location_id;
        });

        return location[0].name;
    }

    function addToTransferList() {
        let sku = document.querySelector('#sku').value.trim();
        let from = document.querySelector('#from_store').value.trim();
        let from_location = document.querySelector('#from_location').value.trim();
        let to = document.querySelector('#to_store').value.trim();
        let quantity = document.querySelector('#from_quantity').value;
        if (sku === '' || from === '' || to === '' || quantity < 1 || from_location === '') {
            alert('Select all required fields.');
            return;
        }
        document.querySelector('#transfer-list-table-body')
            .innerHTML += TransferItemsRow({
                sku,
                from,
                to,
                quantity,
                from_location
            });
    }

    function TransferItemsRow(data) {
        return `
            <tr>
                <td><input type="hidden" name="_sku[]" id="" value="${data.sku}" > ${data.sku} </td>
                <td><input type="hidden" name="_from[]" id="" value="${data.from}" > ${getStoreName(data.from)}</td>
                <td><input type="hidden" name="_from_location[]" id="" value="${data.from_location}" > ${getLocationName(data.from_location)}</td>
                <td><input type="hidden" name="_to[]" id="" value="${data.to}" > ${getStoreName(data.to)}</td>
                <td><input type="hidden" name="_quantity[]" id="" value="${data.quantity}" >  ${data.quantity}</td>
                <td>
                    <button class="btn btn-danger" onclick="this.parentElement.parentElement.remove()">
                        <i class="fa fa-trash"></i>
                    </button>
                </td>
            </tr>
        `;
    }
</script>