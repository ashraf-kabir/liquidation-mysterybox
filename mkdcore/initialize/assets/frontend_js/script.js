$(document).ready(() => {
   
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
});
 




