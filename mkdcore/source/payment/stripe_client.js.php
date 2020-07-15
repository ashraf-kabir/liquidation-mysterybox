$(document).ready(function(){
    //rk_test_DcibeRpdjOo6eEcchZbP1mWF00JobfrQoa
    var stripe = Stripe('{{{stripe_publish_key}}}');
    var elements = stripe.elements();
    var style = {
        base: {
          // Add your base input styles here. For example:
          fontSize: '16px',
          color: "#32325d",
        }
      };
      
      // Create an instance of the card Element.
    if(document.getElementById('payment-form')){
        var card = elements.create('card', {style: style, hidePostalCode: true});

        // Add an instance of the card Element into the `card-element` <div>.
        card.mount(document.getElementById('card-element'));
        var form = document.getElementById('payment-form');
        form.addEventListener('submit', function(event) {
          event.preventDefault();
          stripe.createToken(card).then(function(result) {
            if (result.error) {
              // Inform the customer that there was an error.
              var errorElement = document.getElementById('card-errors');
              errorElement.textContent = result.error.message;
            } else {
              // Send the token to your server.
              stripeTokenHandler(result.token);
            }
          });
        });
    }
   

    //member pages scripts
    $('.change-plan').click(function(e){
      e.preventDefault();
      var url = $(this).attr('href');
      $('#btn-change-plan').attr('href', url);
      $('#chooseCardModal').modal('show');
    });
    
    $('.btn-select-card').click(function(e){
      e.preventDefault();
      var card_id = $(this).data('id');
      var url =  $('#btn-change-plan').attr('href').split("?")[0];
      var url = url + '?card=' + card_id;
      $('#btn-change-plan').attr('href', url);
      $(".btn-select-card").each(function(){
        $(this).html('Choose');
      });
      $(this).html('Selected');
   });
 
   $('#subscription-form-email').blur(function(e){
      var email = $(this).val();
      $(this).removeClass('border border-danger');
      if(isValidEmailAddress(email)){
          $.ajax({ 
            type: 'GET', 
            url: '/save_payment_email', 
            data: { email: email }, 
            dataType: 'json',
            success: function (data) { 
              console.log(data);
              $(this).removeClass('border border-danger');
            }
          });
       }else{
        $(this).addClass('border border-danger');
      }
   });

  
  });
    
  function stripeTokenHandler(token) {
      // Insert the token ID into the form so it gets submitted to the server
      var form = document.getElementById('payment-form');
      var hiddenInput = document.createElement('input');
      hiddenInput.setAttribute('type', 'hidden');
      hiddenInput.setAttribute('name', 'stripeToken');
      hiddenInput.setAttribute('value', token.id);
      form.appendChild(hiddenInput);  
      // Submit the form
      form.submit();
  }
    
  function isValidEmailAddress(emailAddress) {
    var pattern = /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i;
    return pattern.test(emailAddress);
  };