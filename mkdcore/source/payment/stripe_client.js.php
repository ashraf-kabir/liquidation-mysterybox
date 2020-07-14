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
        var card = elements.create('card', {style: style, hidePostalCode: false});

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
      alert(url);
      $('#btn-change-plan').attr('href', url);
      $('#chooseCardModal').modal('show');
    });
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
    