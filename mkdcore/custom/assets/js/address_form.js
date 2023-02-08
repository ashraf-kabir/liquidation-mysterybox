 

     var placeSearch, autocomplete, autocomplete2;
     var componentForm = { 
          street_number: 'short_name',
          route: 'long_name',
          locality: 'long_name',
          administrative_area_level_1: 'short_name',
          country: 'short_name',
          postal_code: 'short_name'
     };

     var residence = [
          "premise", 
          "subpremise", 
          "street_address",
          "street_number",
          "neighborhood",
          "room"

     ];
 

     var business_types = 
     [
          "restaurant", 
          "food", 
          "shopping_mall", 
          "store", 
          "health", 
          "airport", 
          "art_gallery", 
          "atm", 
          "bakery", 
          "bank", 
          "bar", 
          "beauty_salon", 
          "book_store", 
          "bowling_alley", 
          "bus_station", 
          "cafe", 
          "zoo", 
          "university", 
          "travel_agency", 
          "transit_station", 
          "train_station", 
          "supermarket", 
          "subway_station", 
          "stadium", 
          "spa", 
          "shopping_mall", 
          "shoe_store", 
          "secondary_school", 
          "school", 
          "rv_park", 
          "real_estate_agency", 
          "primary_school", 
          "post_office", 
          "pharmacy", 
          "pet_store", 
          "park", 
          "night_club", 
          "museum", 
          "moving_company", 
          "movie_theater", 
          "local_government_office", 
          "liquor_store", 
          "light_rail_station", 
          "library", 
          "laundry", 
          "jewelry_store", 
          "insurance_agency", 
          "hospital", 
          "home_goods_store", 
          "hindu_temple", 
          "hardware_store", 
          "hair_care", 
          "gym", 
          "gas_station", 
          "furniture_store", 
          "fire_station", 
          "embassy", 
          "electronics_store", 
          "drugstore", 
          "department_store", 
          "courthouse", 
          "convenience_store", 
          "clothing_store", 
          "city_hall", 
          "casino", 
          "car_wash", 
          "establishment", 
          "car_repair" 
     ];

     

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

          console.log(place) 

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
                    document.getElementById('billing_country').value = val ;
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
                    document.getElementById('shipping_country').value = val;
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

          var isResidental = 3;
          for (var i = 0; i < place.types.length; i++) 
          { 
               var address_type = place.types[i]; 
               console.log(address_type)
                
               if (residence.includes(address_type) ) 
               {
                    isResidental = 1;
               }
 

               if (business_types.includes(address_type)) 
               {
                    isResidental = 2;
               }
          }

          $('#address_type').val(isResidental); 
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





function update_card_details(exp_month,card_number,exp_year,cvc,card_default) {

     $.ajax({
       url: '../v1/api/nmi/add_new_card',
       timeout: 30000,
       method: 'POST',
       dataType: 'JSON',
       data: { exp_month, card_number, exp_year, cvc, card_default },
       success: function (response) {
         if (response.success) {
           toastr.success(response.success);
           load_customer_cards();
           $('.close-btn').trigger('click');
         }
   
         if (response.error) {
           toastr.error(response.error);
         }
       },
       error: function () {
         toastr.error('Error! Something went wrong.');
       }
     });
   
}


function update_billing_details(billing_address,billing_country,billing_city,billing_state,billing_zip){

     var error_for_updating_billing = 0;
     
     if (error_for_updating_billing == 0) {
       $.ajax({
         url: '../v1/api/update_customer_address',
         timeout: 30000,
         method: 'POST',
         dataType: 'JSON',
         data: { billing_address, billing_country, billing_city, billing_state, billing_zip },
         success: function (response) {
           if (response.success) {
             $('.on_click_billing_modal').trigger('click');
             toastr.success(response.success);
   
             $('#msg_billing_address').text(billing_address);
   
             $('#msg_billing_zip').text(billing_zip);
             $('#msg_billing_state').text(billing_state);
             $('#msg_billing_city').text(billing_city);
   
             if (billing_state != "") {
               $('#billing_coma').show();
             } else {
               $('#billing_coma').hide();
             }
           }
   
   
           if (response.error) {
             toastr.error(response.error);
           }
         },
         error: function () {
           toastr.error('Error! Connection timeout.');
         }
       });
     }
   
}



function get_billing_nd_card(){
   
     var billing_address = document.getElementById("billing_address").value;
     var billing_country = document.getElementById("billing_country").value;
     var billing_zip = document.getElementById("billing_zip").value;
     var billing_state = document.getElementById("billing_state").value;
     var billing_city = document.getElementById("billing_city").value;
   
   
     var exp_month = document.getElementById("exp_month").value;
     var card_number = document.getElementById("account_no").value;
     var exp_year = document.getElementById("exp_year").value;
     var cvc = document.getElementById("cvc_numb").value;
     var card_default = document.getElementById("card_default").value;

     if (billing_address == '' || billing_address == 0) {
          toastr.error('Address is required.');
          error_for_updating_billing = 1;
          return false;
          exit;
        }
      
      
     if (billing_zip == '' || billing_zip == 0) {
          toastr.error('Zip Code is required.');
          error_for_updating_billing = 1;
          return false;
          exit;
     }

     
     // console.log(exp_month,card_number,exp_year,cvc,card_default)
     // console.log(billing_address,billing_country,billing_city,billing_state,billing_zip)

     update_card_details(exp_month,card_number,exp_year,cvc,card_default)
     update_billing_details(billing_address,billing_country,billing_city,billing_state,billing_zip)

}

document.querySelectorAll(".add_billing_and_card").forEach(function(element) {
     element.addEventListener("click", get_billing_nd_card);     
 });