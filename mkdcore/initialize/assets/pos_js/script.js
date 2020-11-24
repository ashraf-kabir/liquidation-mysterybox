$(document).ready(() => {
  let pathName = window.location.pathname; 
  path = pathName.split('/')[1]+'/';
  let ajaxURLPath = document.location.origin + '/';
  let loading_gif = "../assets/image/loading.gif";
    // $("#drawer-modal").modal("show");
  // Get list Of Pickup From Shelf
  
  
  /**
   *  Search Customer
   *  Select2 Library
   *  
   */
  // $(".search_customer_list").select2({
  //   minimumInputLength: 2, 
  //   placeholder: "Select",
  //   allowClear: true
  //   ajax: {
  //       url: ajaxURLPath + 'v1/api/get_customer_search',
  //       dataType: 'json',
  //       type: "GET",
  //       quietMillis: 50,
  //       minimumResultsForSearch: 10,
  //       data: function (term) {
  //           return term;
  //       },
  //       results:  function (data) {  
  //           return  { results: data  }; 
  //       } 
  //   }
  // }); 
  

  //load customers list
function load_customers_list()
{
    $.ajax({
        type: 'GET',
        url: '../v1/api/pos_get_all_active_customers',
        timeout: 15000,
        dataType: 'JSON', 
        success: function (response)  
        { 
            if (response.customers_list) 
            { 
                let options = '<option value="" >Select Customer </option>';
                $.each(response.customers_list,function(index, obj)
                {
                    options += '<option value="'+obj.id+'" >'+obj.name+'</option>';
                });

                $('.customer-list-api').html(options);
            } 
        }
    }); 
} 
load_customers_list();
  
  
  
  
  
  //pickup from shelf
  function load_pickup_from_shelf()
  {
      $.ajax({
          type: 'GET',
          url: '../v1/api/pos_pickup_from_shelf',
          timeout: 15000,
          dataType: 'JSON', 
          success: function (response)  
          { 
              if (response.pickup_shelf) 
              { 
                  $("#pickup tbody").html(response.pickup_shelf);
              } 
          }
      }); 
  } 
  
  
  
  
  //past orders
  function load_pos_past_orders(search_past_orders_from = '', search_past_orders_to  = '', search_past_orders_customer  = '')
  {
      $.ajax({
          type: 'POST',
          url: '../v1/api/pos/pos_past_order',
          timeout: 15000,
          dataType: 'JSON', 
          data : {'start_date': search_past_orders_from, 'end_date' : search_past_orders_to, 'customer_name' : search_past_orders_customer},
          success: function (response)  
          { 
              if (response.pos_past_order) 
              { 
                  $("#past-order tbody").html(response.pos_past_order);
              } 
          }
      }); 
  } 
   
  
  
  //remove from shelf
  function load_items_in_shelf()
  {
      $.ajax({
          type: 'GET',
          url: '../v1/api/pos_items_in_shelf',
          timeout: 15000,
          dataType: 'JSON', 
          success: function (response)  
          { 
              if (response.items_in_shelf) 
              { 
                  $("#remove-from-shelf tbody").html(response.items_in_shelf);
              } 
          }
      });
  }
  
  // cart items in pos
  function load_cart_items_list()
  {
      $.ajax({
          type: 'GET',
          url: '../v1/api/pos_get_cart_items',
          timeout: 15000,
          dataType: 'JSON', 
          success: function (response)  
          { 
              if (response.pos_cart_items_list) 
              { 
                  var cart_list = '';
                  $.each(response.pos_cart_items_list,function(index,obj)
                  {
                    const quantity = obj.product_qty;
                    const newItem  = { id : obj.product_id, name: obj.product_name, price : obj.unit_price, quantity: Number(quantity) };
                    if (cartItems.length) 
                    {
                        const found = cartItems.find((item) => item.id == newItem.id);
                        cartItems.forEach((item) => 
                        {
                            if (item.id == newItem.id) 
                            {
                                item.quantity += Number(newItem.quantity);
                            }
                        }); 
                        if (!found) 
                        {
                            cartItems.push(newItem);
                        }
                    } else 
                    {
                        cartItems.push(newItem);
                    }
                    displayCartItems(cartItems); 
  
                    cart_list   +=  '<li class="row w-100 align-items-center added-item" data-id="'+  obj.product_id   +'" data-toggle="tooltip" data-placement="top" title="Click to Edit Quantity">';
  
                    cart_list   +=  '<span class="col-4"> <h4 class="d-block py-0 my-0">'+  obj.product_name   +'</h4> <small class="py-0 my-0">1 Unit(s) at $'+  Number(obj.unit_price).toFixed(2)   +'</small></span>';
  
                    cart_list   +=  '<span class="col-3 text-primary">Qty: '+  obj.product_qty   +'</span> <span class="col-3 text-danger text-center">$'+  Number(obj.total_price).toFixed(2)   +'</span>';
  
                    cart_list   +=  '<span class="col-2 text-right"><i class="fa fa-trash-alt remove-item" data-id="'+  obj.product_id   +'"></i></span></li>';
  
                  });
  
                  $('#cart-ul').html(cart_list);
                  
                  // Initialize event listeners for delete icons
                  initializeDeleteMethod();
                  initializeEditMethod();
                  
              } 
          }
      }); 
  } 
  load_cart_items_list();
  
  
  
  // load product in pos
  function load_pos_products(search_product_value = '' )
  {
    $('.pos-products-list').html('<img style="width: 140px;object-fit: cover;" src="'+  loading_gif  +'"   alt="loading" />');
      $.ajax({
          type: 'POST',
          url: '../v1/api/pos_get_all_inventory_items',
          timeout: 15000,
          data: {'search_product_value' : search_product_value}, 
          dataType: 'JSON', 
          success: function (response)  
          { 
              if (response.products_list) 
              { 
                  var pos_products = '';
                  $.each(response.products_list,function(index,obj)
                  {
                    pos_products   +=  '<div class="col-xl-6 col-lg-6 col-md-5 col-sm-5 col-10">';
  
                    pos_products   +=  '<div class="item" data-price="'+  Number(obj.selling_price).toFixed(2)   +'" data-id="'+  obj.id   +'">';

                    if(obj.feature_image != '')
                    {
                      pos_products   +=  '<div class="w-100"> <img src="'+  obj.feature_image   +'" alt="'+  obj.product_name   +'" class="mx-auto"> </div>';
                    } else {
                      pos_products   +=  '<div class="w-100"> <img src="../assets/pos_images/default_product_image.jpg" alt="'+  obj.product_name   +'" class="mx-auto"> </div>';
                    }
   
                    
  
                    pos_products   +=  '<h5 class="cart-item-title">'+  obj.product_name   +'</h5> </div> </div>';
  
                  });
  
                  $('.pos-products-list').html(pos_products); 
              } 
          }
      }); 
  } 
  load_pos_products();
  
  
  //Mark Item as picked
  $(document).on("click",".mark_pickup_product",function(e){
    
    e.preventDefault();
    const pickup_order_id = e.currentTarget.getAttribute("data-order-id");
    $.ajax({
      type: 'POST',
      url: '../v1/api/pos_mark_order_pickup',
      timeout: 15000,
      data: {'pickup_order_id' : pickup_order_id },
      dataType: 'JSON', 
      success: function (response)  
      { 
          if (response.success) 
          { 
            toastr.success(response.success);
               load_pickup_from_shelf();
          }
          
          if (response.error) 
          { 
            toastr.error(response.error);
               load_pickup_from_shelf();
          }
      }
    });
  });
  
  
  //Past Order Filter
  $(document).on("click",".search_past_orders",function(e){
    e.preventDefault();
  
    var search_past_orders_from     = $('.search_past_orders_from').val();
    var search_past_orders_to       = $('.search_past_orders_to').val();
    var search_past_orders_customer = $('.search_past_orders_customer').val();
     
    load_pos_past_orders(search_past_orders_from, search_past_orders_to, search_past_orders_customer);
  });
    
  
  
  //Search POS Products
  $(document).on("keyup",".search-pos-items-sku",function(e){
    
      e.preventDefault();
      let search_product_value = $(this).val();
      const pickup_order_id = e.currentTarget.getAttribute("data-order-id");
      load_pos_products(search_product_value);
  }) 
    
  
  
  $(document).on("change",".customer-list-api",function(e){
    e.preventDefault();
    const customer_id = $(this).val();
    $('.checkout-address').val('');
    if(customer_id !== '')
    {
      $.ajax({
        type: 'POST',
        url: '../v1/api/pos_customer_detail',
        timeout: 15000,
        data: {'customer_id' : customer_id },
        dataType: 'JSON', 
        success: function (response)  
        {  
            if (response.customer_detail) 
            { 
              var customer_data = response.customer_detail; 
  
              $('.customer-name').text(customer_data.name);
              $('#checkout-name').val(customer_data.name);
              $('#checkout-city').val(customer_data.billing_city);
              $('#checkout-country').val(customer_data.billing_country);
              $('#checkout-state').val(customer_data.billing_state);
              $('#checkout-postal_code').val(customer_data.billing_zip);
              $('#checkout-address').val(customer_data.billing_address); 
            } 
        }
      }); 
    } 
  }) 
  
  
    let totalPrice = 0,
      discountedTotal = 0,
      discount = 0,
      customerData = {};
  
    // Ser the date for the Record page
    let date = new Date(),
      year = date.getFullYear(),
      month = date.getMonth() + 1,
      day = date.getDate();
    month.toString().length === 1 ? (month = `0${month}`) : month;
    day.toString().length < 1 ? (day = `0${day}`) : day;
    const todayDate = `${year}-${month}-0${day}`;
    $("input#date")[0].value = todayDate;
    // console.log("2020-08-08", todayDate);
    let cartItems = [];
  
    // METHODS
      const initializeDeleteMethod = () => {
          $(".remove-item").click((e) => 
          {
            e.preventDefault();
           
              var product_id  = e.currentTarget.getAttribute("data-id");
  
              Swal.fire({
                  title: 'Are you sure you want to delete this item?',
                  text: "You won't be able to revert this!",
                  icon: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#3085d6',
                  cancelButtonColor: '#d33',
                  confirmButtonText: 'Yes, delete it!'
              }).then((result) => 
              {
                  if (result.isConfirmed) 
                  {
  
                      $.ajax({
                          type: 'POST',
                          url: '../v1/api/delete_cart_item',
                          timeout: 15000,
                          dataType: 'JSON',
                          data : { 'product_id' : product_id },
                          success: function (response)  
                          {
                              if (response.error) 
                              {
                                toastr.error(response.error);
                              }
  
                              if (response.success) 
                              {
                                toastr.success(response.success);
                                   
                                deleteCartItem(e.currentTarget.parentElement.parentElement);
                              }
                               
                          }
                      }); 
                  }
              })
            
              
          });
          $("#edit-quantity-modal").modal("hide");
      };
  
    const initializeEditMethod = () => {
      $(".added-item").click((e) => {
        e.preventDefault();
        const htmlId = Number(e.currentTarget.getAttribute("data-id"));
  
        let item = cartItems.find((item) => item.id == htmlId);
        const { id, name, price, quantity } = item;
        $("#edit-quantity-form")[0].setAttribute("data-id", id);
        $(".edit-title")[0].innerHTML = name;
        $(".edit-price")[0].innerHTML = Number(price).toFixed(2);
        $("input[name='quantity']").val(quantity);
        $("#edit-quantity-modal").modal("show");
      });
    };
  
    const displayCartItems = (cartItemsArray) => {
      $("#cart-ul")[0].innerHTML = "";
      totalPrice = cartItemsArray.reduce(
        (acc, next) =>
          acc + Number(next.price).toFixed(2) * Number(next.quantity),
        0
      );
      let totalQuantity = cartItemsArray.reduce(
        (acc, next) => acc + Number(next.quantity),
        0
      );
      totalPrice = totalPrice.toFixed(2);
      cartItemsArray.forEach((item) => {
        const newCartItem = `<li class="row w-100 align-items-center added-item" data-id=${
          item.id
        } data-toggle="tooltip"  data-placement="top" title="Click to Edit Quantity" >
          <span class="col-4">
            <h4 class="d-block py-0 my-0"> ${item.name}</h4>
            <small class="py-0 my-0">1 Unit(s) at $${Number(item.price).toFixed(
              2
            )}</small>
          </span>
          <span class="col-3 text-primary">Qty: ${item.quantity}</span>
          <span class="col-3 text-danger text-center">$${(
            Number(item.price) * Number(item.quantity)
          ).toFixed(2)}</span>
          <span class="col-2 text-right"><i class="fa fa-trash-alt remove-item" data-id=${
          item.id
        } ></i></span>
        </li>`;
        $("#cart-ul")[0].innerHTML += newCartItem;
      });
  
      for (let i = 0; i < $(".counter").length; i++) {
        $(".counter")[i].innerHTML = totalQuantity;
      }
      for (let i = 0; i < $(".item-total").length; i++) {
        $(".item-total")[i].innerHTML = totalPrice;
        // $(".item-discount-value")[i].innerHTML = discount.toFixed(2);
      }
  
      // Reset the Discount and Enable the discount button
      $(".item-discount-value")[0].innerHTML = "0.00";
      $(".discount-btn").prop("disabled", false);
      $(".discount-btn").html("Discount");
    };
  
    const deleteCartItem = (item) => {
      console.log(item);
      const itemToRemoveId = item.getAttribute("data-id");
  
      cartItems = cartItems.filter((item) => item.id != itemToRemoveId);
  
      displayCartItems(cartItems);
  
      // Re initialize delete method annd remove from shelf
      initializeDeleteMethod();
    };
  
    //Remove Items from shelf
    $(document).on('click','.shelf-remove',function(e){
      var product_id = e.currentTarget.getAttribute('data-product-item-id');
      Swal.fire({
          title: 'Are you sure you want to remove this item from shelf?', 
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes'
      }).then((result) => 
      {
          if (result.isConfirmed) 
          { 
              $.ajax({
                  type: 'POST',
                  url: '../v1/api/remove_from_shelf',
                  timeout: 15000,
                  dataType: 'JSON',
                  data : {'product_id' : product_id},
                  success: function (response) 
                  {
                    if(response.error)
                    {
                      toastr.error(response.error);
                    }
                    if(response.success)
                    {
                      toastr.success(response.success);
                      const parentEl = e.target.parentElement;
                      parentEl.remove(e.target); 
                      load_items_in_shelf();
                      load_pos_products();
                    } 
                  }
              });   
          }
      })  
    })
    
    
  
  const emptyCart = () => 
  {
  
      Swal.fire({
          title: 'Are you sure you want to empty cart?',
          text: "You won't be able to revert this!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, delete it!'
      }).then((result) => 
      {
          if (result.isConfirmed) 
          {
  
              $.ajax({
                  type: 'POST',
                  url: '../v1/api/delete_cart_all',
                  timeout: 15000,
                  dataType: 'JSON',
                  success: function (response) 
                  {
                      if (response.error) 
                      {
                        toastr.error(response.error);
                      }
  
                      if (response.success) 
                      {
                        toastr.success(response.success);
                          cartItems = [];
                          displayCartItems(cartItems);
                      } 
                  }
              });  
               
          }
      })  
  };
  
    // EVENT LISTENERS
    $(document).on('click',".item",function(e){ 
      const item = e.currentTarget.children[1].innerHTML;
      const price = e.currentTarget.getAttribute("data-price");
  
      const cartItemId = e.currentTarget.getAttribute("data-id");
      $("#item-title")[0].innerHTML = item;
      $(".modal-title")[0].innerHTML = item;
      $("#item-price")[0].innerHTML = price;
      $("#addToCart-form")[0].setAttribute("data-id", cartItemId);
      $("#addCart-modal").modal("show");
    })
    // $(".item").click((e) => {
    //   const item = e.currentTarget.children[1].innerHTML;
    //   const price = e.currentTarget.getAttribute("data-price");
  
    //   const cartItemId = e.currentTarget.getAttribute("data-id");
    //   $("#item-title")[0].innerHTML = item;
    //   $(".modal-title")[0].innerHTML = item;
    //   $("#item-price")[0].innerHTML = price;
    //   $("#addToCart-form")[0].setAttribute("data-id", cartItemId);
    //   $("#addCart-modal").modal("show");
    // });
  
      $("#addToCart-form").submit((e) => {
          e.preventDefault();
          const id   = $("#addToCart-form")[0].getAttribute("data-id");
          const item = $("#item-title")[0].innerHTML;
          let price  = $("#item-price")[0].innerHTML;
          price = Number(price).toFixed(2);
          const formData = $("#addToCart-form").serializeArray();
          const quantity = formData[0].value;
          const newItem = { id, name: item, price, quantity: Number(quantity) };
  
  
  
          // get data and post to server
          var serialized_data = [];  
          serialized_data.push({ name: 'quantity', value :  quantity });
          serialized_data.push({ name: 'id', value :  id });
          serialized_data.push({ name: 'item', value :  item });
          serialized_data.push({ name: 'price', value :  price });
          $.ajax({
              type: 'POST',
              url: '../v1/api/add_product_to_cart',
              timeout: 15000,
              data: serialized_data,
              dataType: 'JSON',
              success: function (response) 
              {
                  if (response.error) 
                  {
                      toastr.error(response.error);
                  }
  
                  // if success add data to cart front pos
                  if (response.success) 
                  {
  
                      if (cartItems.length) 
                      {
                          const found = cartItems.find((item) => item.id == newItem.id);
                          cartItems.forEach((item) => 
                          {
                              if (item.id == newItem.id) 
                              {
                                  item.quantity += Number(newItem.quantity);
                              }
                          });
  
                          if (!found) 
                          {
                              cartItems.push(newItem);
                          }
                      } else 
                      {
                          cartItems.push(newItem);
                      }
                      displayCartItems(cartItems);
  
                      // Hide modal and clear form inputs
                      $("#addCart-modal").modal("hide");
                      $("#addToCart-form")[0].reset();
  
                      // Initialize event listeners for delete icons
                      initializeDeleteMethod();
                      initializeEditMethod();
                  }
                   
              }
          });
      });
  
      $("#customCart-form").submit((e) => {
          e.preventDefault();
          const formData = $("#customCart-form").serializeArray();
          const title = formData[0].value;
          let   price = formData[1].value;
          const quantity = formData[2].value;
          const note = formData[3].value;
  
  
          let new_product_id = '';
          $.ajax({
              type: 'POST',
              url: '../v1/api/pos/add_custom_product',
              timeout: 15000,
              data: formData,
              dataType: 'JSON',
              success: function (response) 
              {
                  if (response.error) 
                  {
                     toastr.error(response.error);
                  }
  
                  if (response.product_id) 
                  { 

                    
                    toastr.success(response.success);
                      new_product_id = response.product_id;
  
                      const newItem = 
                      {
                          id: new_product_id,
                          name: title,
                          price,
                          quantity,
                          note,
                      };
  
                      cartItems.push(newItem);
  
                      displayCartItems(cartItems);
  
                      // // Hide modal and clear form inputs
                      $("#customCart-modal").modal("hide");
                      $("#customCart-form")[0].reset();
  
                      // // Initialize event listeners for delete icons
                      initializeDeleteMethod();
                      initializeEditMethod();
                      load_pos_products();
                  } 
              }
          }); 
      });
  
  
      // EDIT ITEMS QUANTITY
      $("#edit-quantity-form").submit((e) => {
          e.preventDefault();
          const id = $("#edit-quantity-form").attr("data-id");
          const formData = $("#edit-quantity-form").serializeArray();
          const quantity = formData[0].value;
  
  
          // get data and post to server
          var serialized_data = [];  
          serialized_data.push({ name: 'quantity', value :  quantity });
          serialized_data.push({ name: 'id', value :  id }); 
  
          $.ajax({
              type: 'POST',
              url: '../v1/api/edit_product_in_cart',
              timeout: 15000,
              data: serialized_data,
              dataType: 'JSON',
              success: function (response) 
              {
                  if (response.error) 
                  {
                    toastr.error(response.error);
                  }
  
                  // if success add data to cart front pos
                  if (response.success) 
                  {
                    toastr.success(response.success);
  
                      displayCartItems(cartItems);
  
                      cartItems = cartItems.map((item) => 
                      {
                          if (item.id == id) {
                              item.quantity = quantity;
                              return item;
                          }
                          return item;
                      }); 
                      displayCartItems(cartItems);
  
                      // Hide modal and clear form inputs
                      $("#edit-quantity-modal").modal("hide");
                      $("#edit-quantity-form")[0].reset();
                      // Initialize event listeners for delete and edif functionality
                      initializeDeleteMethod();
                      initializeEditMethod();
                  }
                   
              }
          }); 
      });
  
    // SHOW MODAL TO ADD NEW CUSTOMER
    $(".added-item").click((e) => {
      $("#customer-modal").modal("show");
    });
    // EMPTY CARTS
    $("#emty-carts").click(() => emptyCart());
  
    // SWITCH BETWEEN PAGES
    $(".page-toggler").click((e) => {
      const navTo = e.target.getAttribute("id");
      $(".active-nav").removeClass("active-nav");
      $(".active-page").addClass("d-none");
      $(".active-page").removeClass("active-page");
      e.target.classList.add("active-nav");
      switch (navTo) {
          case "pos-toggler":
              $("#pos").removeClass("d-none");
              $("#pos").addClass("active-page");
              break;
          case "pickup-toggler":
              $("#pickup").removeClass("d-none");
              $("#pickup").addClass("active-page");
              load_pickup_from_shelf();
              break;
          case "remove-toggler":
              $("#remove-from-shelf").removeClass("d-none");
              $("#remove-from-shelf").addClass("active-page");
              load_items_in_shelf();
              break;
          case "past-order-toggler":
              $("#past-order").removeClass("d-none");
              $("#past-order").addClass("active-page");
              load_pos_past_orders();
              break;
          case "report-toggler":
              $("#report").removeClass("d-none");
              $("#report").addClass("active-page");
              break; 
        default:
          null;
      }
    });
  
      // HANDLE CHECKOUT FORM
      $("#checkout-form").submit((e) => 
      {
          e.preventDefault();
          const formData = $("#checkout-form").serializeArray();
          const address = formData[0].value;
          const message = formData[1].value;
          const paymentMethod = formData[2].value;
          const cardNumber    = formData[3].value;
          const cardMonth     = formData[4].value;
          const cardYear      = formData[5].value;
          const cvc           = formData[6].value;
  
  
          //add order to db
          $.ajax({
              type: 'POST',
              url: '../v1/api/pos_checkout_order',
              timeout: 15000,
              data: { 'form_data' : formData, 'cart_items' : cartItems },
              dataType: 'JSON',
              success: function (response) 
              {
                  if (response.error) 
                  {
                      toastr.error(response.error);
                  } else{ 
                    toastr.success(response.success);
                    // Hide the active Page and show the receipt page
                    $(".active-page").addClass("d-none");
                    $(".active-page").removeClass("active-page");
                    $("#receipt").removeClass("d-none");
                    $("#receipt").addClass("active-page");
  
                    // Set the details for the receipt
                    $("#receipt-address")[0].innerHTML = address;
                    $("#receipt-table-body")[0].innerHTML = "";
                    cartItems.forEach((item) => {
                        $("#receipt-table-body")[0].innerHTML += `
                            <tr>
                                <th scope="row">${item.quantity}</th>
                                <td>${item.name}</td>
                                <td>$ ${Number(item.quantity * Number(item.price)).toFixed(2)}</td>
                            </tr>
                            `;
                        $("");
                        $("#checkout-modal").modal("hide");
                        $("#checkout-form")[0].reset();
                    });
                    let date = new Date(),
                    year = date.getFullYear(),
                    month = date.getMonth() + 1,
                    day = date.getDate(),
                    min = date.getMinutes(),
                    hour = date.getHours(),
                    time = "";
                    month.toString().length === 1 ? (month = `0${month}`) : month;
                    day.toString().length < 1 ? (day = `0${day}`) : day;
                    min.length < 1 ? (min = `0${min}`) : min;
                    const todayDate = `${day}/${month}/${year}`;
                    if (hour > 12) {
                      time = `0${hour - 12}:${min} PM`;
                    } else if (hour < 12) {
                      time = `${hour}:${min} AM`;
                    } else {
                      time = `${hour}:${min} PM`;
                    }
  
                    // Set Receipt Time and date
                    $(".receipt-date")[0].innerHTML = todayDate;
                    $(".receipt-time")[0].innerHTML = time;
  
                    $("#receipt-address")[0].innerHTML = response.address;
                    $("#receipt-order-id")[0].innerHTML = response.order_id;
                    $("#receipt-customer-name")[0].innerHTML = response.customer_name;
  
                    // Hide and Reset modal and form respectively
                    $("#checkout-modal").modal("hide");
                    $("#checkout-form")[0].reset();
                }
              }
          });
  
          
      });
  
    // EDIT ADDRESS
    $(".edit-address-btn").click(() => {
      if ($(".checkout-address").prop("readonly")) {
        $(".checkout-address").prop("readonly", false);
        $(".edit-address-btn").toggleClass("fa-edit fa-check");
      } else {
        $(".checkout-address").prop("readonly", true);
        $(".edit-address-btn").toggleClass("fa-check fa-edit");
      }
    });
  
    // SWITCH BETWEEN CARD AND CASH
    $("input[name='payment']").click((e) => {
      const clickedItem = e.target.getAttribute("id");
      switch (clickedItem) {
        case "cash":
          !$("#card-input-area").hasClass("d-none")
            ? $("#card-input-area").addClass("d-none")
            : null;
          break;
        case "card":
          $("#card-input-area").hasClass("d-none")
            ? $("#card-input-area").removeClass("d-none")
            : null;
          break;
        default:
          null;
      }
    });
  
    // HANDLE DISCOUNT
    $("#discount-form").submit((e) => {
      e.preventDefault();
  
      const formData = $("#discount-form").serializeArray();
      const discountType = formData[0].value;
      const discountPercentage = Number(formData[1].value);
      discount = (discountPercentage / 100) * Number(totalPrice);
      totalPrice = totalPrice - discount;
      totalPrice = Number(totalPrice);
      discountedTotal = totalPrice;
      for (let i = 0; i < $(".discounted-total").length; i++) {
        $(".discounted-total")[i].innerHTML = discountedTotal.toFixed(2);
      }
      for (let i = 0; i < $(".item-discount-value").length; i++) {
        $(".item-discount-value")[i].innerHTML = discount.toFixed(2);
      }
  
      // Disable and Change the button text
      $(".discount-btn").html("Discount Added");
      $(".discount-btn").prop("disabled", true);
  
      // Hide modal and clear form inputs
      $("#discount-modal").modal("hide");
      $("#discount-form")[0].reset();
    });
  
    // SAVE CUSTOMER'S DATA
    $("#customer-form").submit((e) => {
      e.preventDefault();
      // customerData
  
      const formData = $("#customer-form").serializeArray();
      const firstname = formData[0].value;
      const lastname = formData[1].value;
      const email = formData[2].value;
      const phone = formData[3].value;
      const street = formData[4].value;
      const streetTwo = formData[5].value;
      const city = formData[6].value;
      const postalCode = formData[7].value;
      const country = formData[8].value;
      const state = formData[9].value;
  
      customerData = {
          firstname,
          lastname,
          email,
          phone,
          street,
          streetTwo,
          city,
          postalCode,
          country,
          state,
      };
  
      $.ajax({
          type: 'POST',
          url: '../v1/api/add_customer',
          timeout: 15000,
          dataType: 'JSON',
          data : customerData,
          success: function (response)  
          {
              if (response.error) 
              {
                toastr.error(response.error);
              }
  
              if (response.success) 
              {

                load_customers_list();
                toastr.success(response.success); 
                  // Set Customer Details on Checkout Page
                  $(".customer-name").html(`${lastname} ${firstname}`);
                  $("#checkout-address").val(`${street}`);
  
                  // Disable and change the button text after adding a customer
                  $(".customer-btn").html("Customer Added");
                  $(".customer-btn").prop("disabled", true);
  
                  // Hide modal and clear form inputs
                  $("#customer-modal").modal("hide");
                  $("#customer-form")[0].reset();
              } 
          }
      });  
    });


 
  
 
  
  var _scannerIsRunning = false;
  
  
  var action_in_process = 0;
  function check_barcode_in_inventory(barcode_value)
  { 
      var barcode_value = barcode_value;  
      if(barcode_value != '' && action_in_process == 0)
      {
          action_in_process = 1;
          $.ajax({
              url: ajaxURLPath + 'v1/api/check_barcode_in_inventory',
              timeout: 15000,
              method: 'post',
              dataType: 'JSON',
              data : {'barcode_value' : barcode_value},
              success: function (response)  
              {   
                  if(response.error)
                  {
                      action_in_process = 0;
                      toastr.error(response.msg);
                  }
  
                  if(response.success)
                  {
                    toastr.success('Your data has been added to cart successfully.');
                    load_cart_items_list();
                  }
                    
              } 
          })
      }
  } 
   
  
  
  function startScanner2() {
      $("#scanner-container2").show();
      $(".drawingBuffer").hide();
      
      Quagga.init({
          inputStream: {
              name: "Live",
              type: "LiveStream",
              target: document.querySelector('#scanner-container2'),
              constraints: {
                  width: 480,
                  height: 320,
                  facingMode: "environment"  
              },
          },
          decoder: {
              readers: [
                  "code_128_reader",
                  "ean_reader",
                  "ean_8_reader",
                  "code_39_reader",
                  "code_39_vin_reader",
                  "codabar_reader",
                  "upc_reader",
                  "upc_e_reader",
                  "i2of5_reader"
              ],
              debug: {
                  showCanvas: true,
                  showPatches: true,
                  showFoundPatches: true,
                  showSkeleton: true,
                  showLabels: true,
                  showPatchLabels: true,
                  showRemainingPatchLabels: true,
                  boxFromPatches: {
                      showTransformed: true,
                      showTransformedBox: true,
                      showBB: true
                  }
              }
          },
  
      }, function (err) {
          if (err) {
              console.log(err);
              alert(err)
              return
          } 
          Quagga.start();
  
          // Set flag to is running
          _scannerIsRunning = true;
      });
  
      Quagga.onProcessed(function (result) {
          var drawingCtx = Quagga.canvas.ctx.overlay,
          drawingCanvas = Quagga.canvas.dom.overlay;
  
          if (result) {
              if (result.boxes) {
                  drawingCtx.clearRect(0, 0, parseInt(drawingCanvas.getAttribute("width")), parseInt(drawingCanvas.getAttribute("height")));
                  result.boxes.filter(function (box) {
                      return box !== result.box;
                  }).forEach(function (box) {
                      Quagga.ImageDebug.drawPath(box, { x: 0, y: 1 }, drawingCtx, { color: "green", lineWidth: 2 });
                  });
              }
  
              if (result.box) {
                  Quagga.ImageDebug.drawPath(result.box, { x: 0, y: 1 }, drawingCtx, { color: "#00F", lineWidth: 2 });
              }
  
              if (result.codeResult && result.codeResult.code) {
                  Quagga.ImageDebug.drawPath(result.line, { x: 'x', y: 'y' }, drawingCtx, { color: 'red', lineWidth: 3 });
              }
          }
      });
  
  
      Quagga.onDetected(function (result) 
      {
          var barcode = result.codeResult.code;   
          stop_barcode_camera(); 
          check_barcode_in_inventory(barcode); 
      });
  }
  
  
  function stop_barcode_camera()
  {
      Quagga.stop();
      $("#scanner-container2").hide();  
      _scannerIsRunning = false;
  }
  
  // Start/stop scanner 
  $(document).on('click','#btn-scanner-camera2',function(){
      $('#scan-product-modal').modal('toggle');
      if (_scannerIsRunning) 
      {
          stop_barcode_camera();
      } else {
          startScanner2();
      }
  });
  
  $(document).on('click','.close-scanner-camera2',function(){
      $('#scan-product-modal').modal('toggle'); 
      stop_barcode_camera(); 
  });

});