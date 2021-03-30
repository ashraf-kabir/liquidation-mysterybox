$(document).ready(() => {
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


    // get data and post to server
    var serialized_data = [];  
    serialized_data.push({ name: 'quantity', value :  quantity });
    serialized_data.push({ name: 'id', value :  id }); 
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
            }

            // if success add data to cart front pos
            if (response.success) 
            {
                check_cart_total_items();
                toastr.success(response.success);
            } 
        }
    });
  });



    $(document).on('click','.edit_to_cart_button',function(e){
        e.preventDefault(); 

        let quantity   = $(this).parent().parent().find('.quantity_value').val();
        let id         = $(this).attr('data-id'); 


        // get data and post to server
        var serialized_data = [];  
        serialized_data.push({ name: 'quantity', value :  quantity });
        serialized_data.push({ name: 'id', value :  id }); 
        serialized_data.push({ name: 'force_update', value :  true }); 
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
            }
        });
    });

  $(document).on('click','.calculate-shipping-cost', function(){
      var error_for_shipping = 0;  
      $('.shipping-cost-options').html('');
      var postal_code  =  $('#checkout-postal_code').val();
      var city         =  $('#checkout-city').val();
      var state        =  $('#checkout-state').val();
      var country      =  $('#checkout-country').val();
      var from_postal  =  $('.shipping-postal-code').val();


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

      if(from_postal == '' || from_postal == 0) 
      {
          toastr.error('Shipping Postal Code is required.'); 
          error_for_shipping = 1;
          return false;
          exit;
      }

      from_postal = 93611;

      if(error_for_shipping == 1)
      {
          return false;
          exit; 
      }


      $('.shipping-cost-options').html('<img style="width:60px;object-fit: cover;" src="'+  loading_gif  +'"   alt="loading" />');

        $.ajax({
          url: '../v1/api/get_shipping_cost',
          timeout: 30000,
          method: 'POST',
          dataType: 'JSON',  
          data : {'postal_code' : postal_code, 'city' : city, 'from_postal' : from_postal,
            'state' : state,'country' : country  },
          success: function (response)  
          {   
              
              if(response.list_all)
              {
                
                let shipping_options = '<input type="hidden" class="shipping-cost-name" name="shipping_cost_name" value=""  /><input type="hidden" class="shipping-cost-price-value" name="shipping_cost_value" value="0"  /><select class="form-control shipping-cost-price  mb-2" name="shipping_service_id"><option value="">Select Service</option>';
                  $(response.list_all).each(function(index,object){
                    shipping_options += '<option value="' + object.serviceCode + '" data-other-cost="' + object.otherCost + '"   data-price="' + object.shipmentCost + '" data-service-code="' + object.serviceCode + '" data-service-name="' + object.serviceName + '"  >' + object.serviceName + '  (Shipment Cost $' + object.shipmentCost + ' ) ( Other Cost $' + object.otherCost + ' )   </option>';
                  }) 
                shipping_options += '</select>';

                $('.shipping-cost-options').html(shipping_options);
              }
              

              if(response.error)
              {
                toastr.error(response.error); 
                $('.shipping-cost-options').html('')
              } 
          },
          error: function()
          {
            $('.shipping-cost-options').html('');
            toastr.error('Error! Try again later.'); 
          } 
      })
  });
  
  calculate_cost()
    function calculate_cost()
    {
        var price_shipping = $('.shipping-cost-price').find(':selected').attr('data-price'); 
        var other_price    = $('.shipping-cost-price').find(':selected').attr('data-other-cost'); 
        var shipping_service_name    = $('.shipping-cost-price').find(':selected').attr('data-service-name'); 

        if(!price_shipping)
        {
          price_shipping = 0;
        }

        if(!other_price)
        {
          other_price = 0;
        }

        coupon_amount_now = 0;
        // var coupon_amount_now    = $('#coupon_amount_now').val();

        // if(!coupon_amount_now)
        // {
        //     coupon_amount_now = 0;
        // }

        // coupon_amount_now = Number(coupon_amount_now).toFixed(2);

        var total_shipping_price = 0;
        total_shipping_price = Number(price_shipping) + Number(other_price);

        $('.shipping-cost-name').val(shipping_service_name);   
        $('.shipping-cost-price-value').val(total_shipping_price);   
        $('.shipping_cost_selected').text(Number(total_shipping_price).toFixed(2));  

        var total_of_all =  0;
        if ( $('#checkout-state').val().toLowerCase() == 'nevada' ||  $('#checkout-state').val().toLowerCase() == 'nv'  ) 
        { 
            $('.cart-tax').text(Number($('.tax_amount_val').val()).toFixed(2));
            total_of_all = $('.total_of_all').val();
        }
        else
        {
            $('.cart-tax').text(Number(0).toFixed(2));
            total_of_all = $('.total_without_tax').val();
        } 

        total_of_all = total_of_all.replace(/,/g,'');


        var total_after_shipping = Number(total_of_all) + total_shipping_price - coupon_amount_now;
        $('.total_of_all_text').text(Number(total_after_shipping).toFixed(2)); 
    }


    $(document).on('change','.shipping-cost-price', function(){ 
        calculate_cost();
    });

    $(document).on('keyup','#checkout-state', function(){ 
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
})

     
   

 



}); 



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