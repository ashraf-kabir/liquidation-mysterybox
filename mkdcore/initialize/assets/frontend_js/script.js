// $(document).ready(() => {  

function check_cart_total_items()
{
  $.ajax({
    url: '../v1/api/check_cart_total_items',
    timeout: 30000,
    method: 'POST',
    dataType: 'JSON',  
    success: function (response)  
    {     
      if(response.cart_items)
      {
        $('#lblCartCount').text(response.cart_items)
      } 
    },
    error: function()
    { 

    } 
  });
} 
check_cart_total_items();






  let loading_gif = "../assets/image/loading.gif";




  $(document).on('click','.update__form_submit',function(e){
    e.preventDefault();
    dataForm = $('#update__form').serializeArray();
    $.ajax({
      url: '../v1/api/profile',
      timeout: 30000,
      method: 'POST',
      dataType: 'JSON', 
      data : dataForm,
      success: function (response)  
      {    
        if(response.success)
        {
          toastr.success(response.success);  
        } 

        if(response.error)
        {
          toastr.error(response.error); 
        } 
      },
      error: function()
      { 
        toastr.error('Error! Try again later.'); 
      } 
    })
  })

  $(document).on('click','.signup__form_submit',function(e){

    let first_name         = $('#name').val();
    let email              = $('#email').val();
    let password           = $('#password1').val();
    let confirm_password   = $('#password2').val();

    let error = 0;
    if(first_name == "")
    {
      toastr.error("Error! Name is required."); 
      error = 1;
    }

    if(email == "")
    {
      toastr.error("Error! Email is required."); 
      error = 1;
    }


    if(password == "")
    {
      toastr.error("Error! Password is required."); 
      error = 1;
    }

    if(password != confirm_password)
    {
      toastr.error("Error! Both passwords should be same."); 
      error = 1;
    }

    if(! $('#terms').is(':checked')  )
    {
      toastr.error("Error! Vegas Liquidation Auction user agreement confirmation required."); 
      error = 1;
    }

    if(error == 0)
    { 
      $.ajax({
        type: 'POST',
        url: '../v1/api/sign_up',
        timeout: 15000,
        dataType: 'JSON', 
        data : {'first_name': first_name, 'email' : email, 'password' : password, 'confirm_password' : confirm_password },
        success: function (response)  
        { 
          if (response.success) 
          { 
            toastr.success(response.success); 
            $('#signupModal').modal('toggle')
          }

          if (response.error) 
          { 
            toastr.error(response.error); 
          }
        }
      }); 
    }
  });



  $(document).on('click','.login__form_submit',function(e){

    let email              = $('#email_login').val();
    let password           = $('#password1_login').val();


    let error = 0;
    if(email == "")
    {
      toastr.error("Error! Email is required."); 
      error = 1;
    }


    if(password == "")
    {
      toastr.error("Error! Password is required."); 
      error = 1;
    }


    if(error == 0)
    { 
      $.ajax({
        type: 'POST',
        url: '../v1/api/login_customer',
        timeout: 15000,
        dataType: 'JSON', 
        data : { 'email' : email, 'password' : password },
        success: function (response)  
        { 
          if (response.success) 
          { 
            toastr.success(response.success); 
            location.reload(); 
          }

          if (response.error) 
          { 
            toastr.error(response.error); 
          }
        }
      }); 
    }
  });




  $(document).on('click','.add_to_cart_button',function(e){
    e.preventDefault(); 

    let quantity   = $('.product_quantity').val();
    let id         = $('.product_id').val(); 

    if (quantity > 0) 
    {
      // get data and post to server
      var serialized_data = [];  
      serialized_data.push({ name: 'quantity', value :  quantity });
      serialized_data.push({ name: 'id', value :  id }); 

      hide_qty_btns();

      $.ajax({
        type: 'POST',
        url: '../v1/api/add_product_to_cart_by_customer',
        timeout: 15000,
        data: serialized_data,
        dataType: 'JSON',
        success: function (response) 
        {
          if (response.error) 
          {
            toastr.error(response.error);
            show_qty_btns();

          }

          // if success add data to cart front pos
          if (response.success) 
          {
            check_cart_total_items();
            toastr.success(response.success);
            show_qty_btns();

          } 
        },
        error: function()
        { 
          
          toastr.error('Error! Connection timeout.'); 
          show_qty_btns();
        } 
      });
    }
  });


  function hide_qty_btns()
  {
    $('.edit_to_cart_button').hide();
    $('.add_to_cart_button').hide();
    $('.minus_to_cart_button').hide();

    $('.edit_to_cart_button').parent().append('<img class="remove_able_loader_gif" style="width:60px;object-fit: cover;" src="'+  loading_gif  +'"   alt="loading" />');
    $('.minus_to_cart_button').parent().parent().find('.product-quantity').append('<img class="remove_able_loader_gif" style="width:60px;object-fit: cover;" src="'+  loading_gif  +'"   alt="loading" />');
     
  }


  function show_qty_btns()
  {
    $('.edit_to_cart_button').show();
    $('.add_to_cart_button').show();
    $('.minus_to_cart_button').show();

    $('.remove_able_loader_gif').remove();
  }

  function update_create_cart(quantity, id)
  {

    if (quantity > 0) 
    { 
      // get data and post to server
      var serialized_data = [];  
      serialized_data.push({ name: 'quantity', value :  quantity });
      serialized_data.push({ name: 'id', value :  id }); 
      serialized_data.push({ name: 'force_update', value :  true }); 

      hide_qty_btns();
      
 
      $.ajax({
        type: 'POST',
        url: '../v1/api/add_product_to_cart_by_customer',
        timeout: 15000,
        data: serialized_data,
        dataType: 'JSON',
        success: function (response) 
        {
          if (response.error) 
          {
            $('.place-order-btn').show();
            toastr.error(response.error);
            $('.remove_able_loader_gif').remove();
            show_qty_btns();

          }

          // if success add data to cart front pos
          if (response.success) 
          {
            check_cart_total_items();
            toastr.success(response.success);
            setInterval(function() {
              window.location.reload(true);
            }, 2000); 
          } 
        },
        error: function()
        { 
          
          toastr.error('Error! Connection timeout.'); 
          $('.place-order-btn').show();
          show_qty_btns();
        }  
      });
    } else{
      $('.place-order-btn').show();
    }
  }


  $(document).on('click','.edit_to_cart_button',function(e){
    e.preventDefault(); 
    let quantity   = $(this).parent().parent().find('.quantity_value').val();
    let id         = $(this).attr('data-id'); 
    update_create_cart(quantity, id)
  });






  $(document).on('click','.add_to_cart_button',function(e){
    e.preventDefault(); 
    let quantity_add   = Number($(this).attr('data-product_qty')) + 1;
    let id_product     = $(this).attr('data-id');
    $('.place-order-btn').hide();
    update_create_cart(quantity_add, id_product);

  });


  $(document).on('click','.minus_to_cart_button',function(e){
    e.preventDefault();  
    let id_item         = $(this).attr('data-id');
    let quantity_minus  = $(this).attr('data-product_qty') - 1;
    $('.place-order-btn').hide(); 
    update_create_cart(quantity_minus, id_item);

  });



  $(document).on('click','.calculate-shipping-cost', function(){
    var error_for_shipping = 0;  
    var p_object = $(this).parent().parent();

    p_object.find('.shipping-cost-options').html('');
    var postal_code  =  $('#shipping_zip').val();
    var city         =  $('#shipping_city').val();
    var state        =  $('#shipping_state').val();
    var country      =  $('#shipping_country').val(); 
    var address_type =  $('#address_type').val(); 
    var id           =  $(this).attr('data-id'); 


    if(postal_code == '' || postal_code == 0) 
    {  
      toastr.error('Postal Code is required.');
      error_for_shipping = 1;
      return false;
      exit; 
    }



    if(country == '' || country == 0) 
    {
      toastr.error('Country is required.'); 
      error_for_shipping = 1;
      return false;
      exit;
    }


    if(error_for_shipping == 1)
    {
      return false;
      exit; 
    }

    if (error_for_shipping == 0) 
    {
      p_object.find('.shipping-cost-options').html('<img style="width:60px;object-fit: cover;" src="'+  loading_gif  +'"   alt="loading" />');

      $.ajax({
        url: '../v1/api/get_shipping_cost',
        timeout: 30000,
        method: 'POST',
        dataType: 'JSON',  
        data : {'postal_code' : postal_code, 'city' : city, 'state' : state, 'country' : country, 'id' : id, 'address_type' : address_type,  },
        success: function (response)  
        {    
          if(response.list_all)
          { 
            let shipping_options = '<input type="hidden" class="shipping-cost-name" name="shipping_cost_name_' + id + '" value=""  /><input type="hidden" class="shipping-cost-price-value" name="shipping_cost_value_' + id + '" value="0"  />';


            $(response.list_all).each(function(index,object){  
              var shipping_cost_total = object.shipmentCost + object.otherCost;
              shipping_cost_total = parseFloat(shipping_cost_total).toFixed(2);
              shipping_options += '<label><input name="shipping_service_id_' + id + '" class="mr-2 shipping-cost-change" type="radio" value="' + object.serviceCode + '" data-expected-date="' + object.expected_date_only + '"  data-other-cost="' + object.otherCost + '"   data-price="' + object.shipmentCost + '" data-service-code="' + object.serviceCode + '" data-service-name="' + object.serviceName + '"  />' + object.serviceName + '  ( $' + shipping_cost_total + ' ) ' + object.expected_date + ' </label>';
            }) 
            // shipping_options += '</select>';

            p_object.find('.shipping-cost-options').html(shipping_options);
          }


          if(response.error)
          {
            toastr.error(response.error); 
            p_object.find('.shipping-cost-options').html('')
          } 
        },
        error: function()
        { 
          
          toastr.error('Error! Try again later.'); 
          window.location.reload(true);
        } 
      })
    }
  });

  




  $(document).on('keyup','#billing_state', function(){ 
    calculate_cost();
  });





  $(document).on('click','.select_card',function(){
    if($(this).is(':checked') && $(this).val() == 2 )
    {
      $('.card_div').show();
    }else{
      $('.card_div').hide();
    }
  })




  $(document).on('click','.apply_coupon',function(){
    $('#coupon_amount_now').val('0');
    let coupon_code = $('.coupon_code').val();
    if(coupon_code == '' || coupon_code == 0) 
    {
      toastr.error('Coupon Code is required.');  
      return false;
      exit;
    }
    $.ajax({
      url: '../v1/api/apply_coupon',
      timeout: 30000,
      method: 'POST',
      dataType: 'JSON', 
      data : {'coupon_code' : coupon_code },
      success: function (response)  
      {    
        if(response.success)
        {
          $('.coupon_amount').text( Number(response.amount).toFixed(2) ) 
          $('#coupon_amount_now').val( Number(response.amount).toFixed(2) ) 

        } 
        calculate_cost();
        if(response.error)
        {
          $('.coupon_amount').text( Number(0).toFixed(2) ) 
          toastr.error(response.error); 
        } 
      },
      error: function()
      { 
        toastr.error('Error! Try again later.'); 
      } 
    })
  })






  $(document).on('submit','.send_checkout',function(e){
    e.preventDefault();

    $('.place-order-btn').hide();
    $('.place-order-btn').parent().append('<img src="' + loading_gif + '" class="image-on-submit" style="width: 6%;">');


    if (!$('#terms1').is(':checked')) 
    {
       
      toastr.error("Please accept terms and condition"); 
      $('.image-on-submit').remove();
      $('.place-order-btn').show(); 
    } 
    else 
    { 
      dataForm = $('.send_checkout').serializeArray();
      $.ajax({
        url: '../v1/api/do_checkout',
        timeout: 30000,
        method: 'POST',
        dataType: 'JSON', 
        data : {'dataForm' : dataForm },
        success: function (response)  
        {    
          if(response.success)
          {
            toastr.success(response.success); 
            setInterval(function() {
              window.location.href = response.redirect_url;
            }, 2000);
          } 

          if(response.error)
          {
            $('.image-on-submit').remove();
            $('.place-order-btn').show();
            toastr.error(response.error); 
          } 
        },
        error: function()
        { 
          $('.image-on-submit').remove();
          $('.place-order-btn').show();
          toastr.error('Error! Connection timeout.'); 
        } 
      })

    }
  })








// }); 






$(document).on('click','.on_click_notification',function(e){
  e.preventDefault();
  $('.product__title_on_modal').text($(this).attr('data-product-title') );
  $('.on_click_notification_modal').modal('show');
});




$(document).on('click','.add_alert_notification',function(e){
  e.preventDefault();
  dataForm = $('#add_alert_notification').serializeArray();
  $.ajax({
    url: '../v1/api/add_alert_notification',
    timeout: 30000,
    method: 'POST',
    dataType: 'JSON',  
    data : dataForm,
    success: function (response)  
    {     
      if(response.success)
      {
        $('.on_click_notification_modal').modal('hide');
        toastr.success(response.success); 
      } 


      if(response.error)
      { 
        toastr.error(response.error); 
      } 
    },
    error: function()
    { 
      toastr.error('Error! Connection timeout.'); 
    } 
  });
});





$(document).on('click','.add-shipping-address',function(e){
  e.preventDefault();
  var error_for_updating_shipping = 0;
  var shipping_address = $('#shipping_address').val();
  var shipping_country = $('#shipping_country').val();
  var shipping_zip     = $('#shipping_zip').val();
  var shipping_state   = $('#shipping_state').val();
  var shipping_city    = $('#shipping_city').val();
  var address_type     = $('#address_type').val(); 

  if(shipping_address == '' || shipping_address == 0) 
  {
    toastr.error('Address is required.'); 
    error_for_updating_shipping = 1;
    return false;
    exit;
  }


  if(shipping_state == '' || shipping_state == 0) 
  {
    toastr.error('State is required.'); 
    error_for_updating_shipping = 1;
    return false;
    exit;
  }


  if(shipping_city == '' || shipping_city == 0) 
  {
    toastr.error('City is required.'); 
    error_for_updating_shipping = 1;
    return false;
    exit;
  }


  



  if(shipping_country == '' || shipping_country == 0) 
  {
    toastr.error('Country is required.'); 
    error_for_updating_shipping = 1;
    return false;
    exit;
  }

  if(shipping_zip == '' || shipping_zip == 0) 
  {  
    toastr.error('Zip Code is required.');
    error_for_updating_shipping = 1;
    return false;
    exit; 
  }
  




  if (error_for_updating_shipping == 0) 
  {
    $.ajax({
      url: '../v1/api/update_customer_address',
      timeout: 30000,
      method: 'POST',
      dataType: 'JSON',  
      data : {shipping_address, shipping_country, shipping_city, shipping_state, shipping_zip , address_type },
      success: function (response)  
      {     
        if(response.success)
        {
          $('.on_click_shipping_modal').trigger('click');
          toastr.success(response.success); 

          $('#msg_full_name').text($('#full_name').val())
          $('#msg_shipping_address').text(shipping_address)
   
          $('#msg_shipping_zip').text(shipping_zip)
          $('#msg_shipping_state').text(shipping_state)
          $('#msg_shipping_city').text(shipping_city) 

          $('.calculate-shipping-cost').trigger('click');

        } 


        if(response.error)
        { 
          toastr.error(response.error); 
        } 
      },
      error: function()
      { 
        toastr.error('Error! Connection timeout.'); 
      } 
    });
  }
});


$(document).on('click','.add-billing-address',function(e){
  e.preventDefault();

  var error_for_updating_billing = 0;
  var billing_address = $('#billing_address').val();
  var billing_country = $('#billing_country').val();
  var billing_zip     = $('#billing_zip').val();
  var billing_state   = $('#billing_state').val();
  var billing_city    = $('#billing_city').val();

  
  if(billing_zip == '' || billing_zip == 0) 
  {  
    toastr.error('Zip Code is required.');
    error_for_updating_billing = 1;
    return false;
    exit; 
  }



  if(billing_address == '' || billing_address == 0) 
  {
    toastr.error('Address is required.'); 
    error_for_updating_billing = 1;
    return false;
    exit;
  }

  if (error_for_updating_billing == 0) 
  {
    $.ajax({
      url: '../v1/api/update_customer_address',
      timeout: 30000,
      method: 'POST',
      dataType: 'JSON',  
      data : {billing_address, billing_country, billing_city, billing_state, billing_zip },
      success: function (response)  
      {     
        if(response.success)
        {
          $('.on_click_billing_modal').trigger('click');
          toastr.success(response.success); 

          $('#msg_billing_address').text(billing_address)
   
          $('#msg_billing_zip').text(billing_zip)
          $('#msg_billing_state').text(billing_state)
          $('#msg_billing_city').text(billing_city) 
        } 


        if(response.error)
        { 
          toastr.error(response.error); 
        } 
      },
      error: function()
      { 
        toastr.error('Error! Connection timeout.'); 
      } 
    });
  }
});









$(document).on('click','.add_new_card',function(e){
  e.preventDefault();

  var exp_month    = $('#exp_month').val();
  var card_number  = $('#account_no').val();
  var exp_year     = $('#exp_year').val();
  var cvc          = $('#cvc_numb').val(); 

  $.ajax({
    url: '../v1/api/add_new_card',
    timeout: 30000,
    method: 'POST',
    dataType: 'JSON',  
    data : {exp_month, card_number, exp_year, cvc },
    success: function (response)  
    {     
      if(response.success)
      {
        toastr.success(response.success); 
         load_customer_cards();
      } 


      if(response.error)
      { 
        toastr.error(response.error); 
      } 
    },
    error: function()
    { 
      toastr.error('Error! Connection timeout.'); 
    } 
  });
});





$(document).on('click','.shipping-cost-change', function(){  
  $('.selected_item_expected_shipping_date').text($(this).attr('data-expected-date'));  
  calculate_cost();
});


$(document).on('click','.place-order-btn', function(){    
  $('.send_checkout').submit();
});


$(document).on('click','.add_card_btn_show', function(){    
  $('.add_card_div').show();
  $('.select_card_div').hide();
});
$(document).on('click','.close_new_card_div', function(){    
  $('.add_card_div').hide();
  $('.select_card_div').show();
});








calculate_cost()

function calculate_cost()
{
  let total_shipping_price = 0; 

  $('.shipping-cost-change').each(function(index, obj){
    if($(this).is(':checked'))
    { 
      var price_shipping  = $(this).attr('data-price'); 
      var other_price     = $(this).attr('data-other-cost'); 
      var shipping_service_name    = $(this).attr('data-service-name'); 

      if(!price_shipping)
      {
        price_shipping = 0;
      }

      if(!other_price)
      {
        other_price = 0;
      }

      var selected_item_shipping_cost = 0;
      selected_item_shipping_cost = Number(price_shipping) + Number(other_price); 
      selected_item_shipping_cost = Number(selected_item_shipping_cost).toFixed(2); 

   
      total_shipping_price = Number(selected_item_shipping_cost)  + Number(total_shipping_price);
   
      $(this).parent().parent().parent().find('.selected_item_shipping_cost').text(selected_item_shipping_cost);




      $(this).parent().parent().find('.shipping-cost-name').val(shipping_service_name);   
      $(this).parent().parent().find('.shipping-cost-price-value').val(selected_item_shipping_cost);   
    }
  });



  // if(!price_shipping)
  // {
  //      price_shipping = 0;
  // }

  // if(!other_price)
  // {
  //      other_price = 0;
  // }

  coupon_amount_now = 0;
  // var coupon_amount_now    = $('#coupon_amount_now').val();

  // if(!coupon_amount_now)
  // {
  //     coupon_amount_now = 0;
  // }

  // coupon_amount_now = Number(coupon_amount_now).toFixed(2);

  // var total_shipping_price = 0;
  // total_shipping_price = Number(price_shipping) + Number(other_price);

  // $('.shipping-cost-name').val(shipping_service_name);   
  // $('.shipping-cost-price-value').val(total_shipping_price);   


  $('.shipping_total_cost').text(Number(total_shipping_price).toFixed(2));  

  var total_of_all =  0;
  if ( $('#billing_state').val().toLowerCase() == 'nevada' ||  $('#billing_state').val().toLowerCase() == 'nv'  ) 
  { 
    var tax_amount_val = $('.tax_amount_val').val();
    tax_amount_val = tax_amount_val.replace(/,/g,'');

    
    $('.cart-tax').text(Number(tax_amount_val).toFixed(2));
    total_of_all = $('.total_of_all').val();
  }
  else
  {
    $('.cart-tax').text(Number(0).toFixed(2));
    total_of_all = $('.total_without_tax').val();
  } 

  total_of_all = total_of_all.replace(/,/g,'');


  var total_after_shipping = Number(total_of_all) + Number(total_shipping_price) - Number(coupon_amount_now);
  $('.total_of_all_text').text(Number(total_after_shipping).toFixed(2)); 
}


$('.calculate-shipping-cost').trigger('click');




$(document).on('change','.shipping_service_name_change', function(){    
  $(this).parent().find('.shipping_service_name_for_selected').val(''); 
  var service_name = $(this).find(':selected').attr('data-service_name'); 
  $(this).parent().find('.shipping_service_name_for_selected').val(service_name);
});








 
function load_customer_cards()
{ 
  $.ajax({
    url: '../v1/api/load_customer_cards',
    timeout: 30000,
    method: 'POST',
    dataType: 'JSON',   
    success: function (response)  
    {    
      all_cards = "";
      if(response.all_cards)
      {  
        let card_selected = ""
        $(response.all_cards).each(function(index,object)
        {  
          card_selected = "";
          if (object.is_default == 1) 
          {
            card_selected = " checked ";
          }
          all_cards += '<label style="display: block;"><input ' + card_selected + '  style="margin-right: 7px;" type="radio" name="customer_card" value="' + object.id  + '" ><strong>' + object.brand  + '</strong>  ending in  ' + object.last4  + '</label>';
        });

        $('#customer_card').html(all_cards)
         
      } 


      if(response.error)
      { 
        toastr.error(response.error); 
      } 
    },
    error: function()
    { 

      toastr.error('Error! Connection timeout.'); 
      window.location.reload(true);
    } 
  });
}


load_customer_cards();