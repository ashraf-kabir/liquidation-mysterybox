 

     var placeSearch, autocomplete, autocomplete2;
     var componentForm = { 
          street_number: 'short_name',
          route: 'long_name',
          locality: 'long_name',
          administrative_area_level_1: 'short_name',
          country: 'long_name',
          postal_code: 'short_name'
     };

function initialize() {  
     

     var input             = document.getElementById('billing_address');
     var shipping_address  = document.getElementById('shipping_address');
     var geocoder = new google.maps.Geocoder();
     var autocomplete = new google.maps.places.Autocomplete(input); 
     var autocomplete2 = new google.maps.places.Autocomplete(shipping_address); 
     var infowindow = new google.maps.InfoWindow();
   

     autocomplete.addListener('place_changed', function() {
          // infowindow.close();
        
          var place = autocomplete.getPlace();
          if (!place.geometry) {
               window.alert("Autocomplete's returned place contains no geometry");
               return; 
          }
   
        
          reset_billing_address();


          for (var i = 0; i < place.address_components.length; i++) 
          { 
               var addressType = place.address_components[i].types[0]; 
               var val = place.address_components[i][componentForm[addressType]];
                
               if (addressType == 'street_number') 
               {
                    document.getElementById('billing_address').value = val;
               }

               if (addressType == 'route') 
               {
                    var street_number_add = document.getElementById('billing_address').value
                    var full_address      = street_number_add + " " + val;
                    document.getElementById('billing_address').value = full_address;
               }

               if (addressType == 'country') 
               {
                    document.getElementById('billing_country').value = "US";
               }

               if (addressType == 'locality') 
               {
                    document.getElementById('billing_city').value = val;
               }

               if (addressType == 'administrative_area_level_1') 
               {
                    document.getElementById('billing_state').value = val;
               }

               if (addressType == 'postal_code') 
               {
                    document.getElementById('billing_zip').value = val;
               } 
          } 

          $('#billing_state').trigger( "keyup" );   
     });
     

     autocomplete2.addListener('place_changed', function() 
     {
          // infowindow.close();
        
          var place = autocomplete2.getPlace();
          if (!place.geometry) {
               window.alert("Autocomplete's returned place contains no geometry");
               return; 
          }
   
        
          reset_shipping_address();


          for (var i = 0; i < place.address_components.length; i++) 
          { 
               var addressType = place.address_components[i].types[0]; 
               var val = place.address_components[i][componentForm[addressType]];

               if (addressType == 'street_number') 
               {
                    document.getElementById('shipping_address').value = val;
               }

               if (addressType == 'route') 
               {
                    var street_number_add_shpping = document.getElementById('shipping_address').value
                    var full_address_shpping      = street_number_add_shpping + " " + val;
                    document.getElementById('shipping_address').value = full_address_shpping;
               }


               if (addressType == 'country') 
               {
                    document.getElementById('shipping_country').value = "US";
               }

               if (addressType == 'locality') 
               {
                    document.getElementById('shipping_city').value = val;
               }

               if (addressType == 'administrative_area_level_1') 
               {
                    document.getElementById('shipping_state').value = val;
               }

               if (addressType == 'postal_code') 
               {
                    document.getElementById('shipping_zip').value = val;
               } 
          }    
     });
     
} 

function reset_billing_address()
{
     document.getElementById('billing_country').value = "";
     document.getElementById('billing_city').value = "";
     document.getElementById('billing_state').value = "";
     document.getElementById('billing_zip').value = "";
}


function reset_shipping_address()
{
     document.getElementById('shipping_country').value = "";
     document.getElementById('shipping_city').value = "";
     document.getElementById('shipping_state').value = "";
     document.getElementById('shipping_zip').value = "";
}
  