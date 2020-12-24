// var discoveredReaders;
// var paymentIntentId; 
// var base_url = document.location.origin + '/';  

// var terminal = StripeTerminal.create({
//     onFetchConnectionToken: fetchConnectionToken,
//     onUnexpectedReaderDisconnect: unexpectedDisconnect,
// });
 
  

// function unexpectedDisconnect() {
//     // In this function, your app should notify the user that the reader disconnected.
//     // You can also include a way to attempt to reconnect to a reader.
//     console.log("Disconnected from reader")
// }
  
// function fetchConnectionToken() {
//     // Do not cache or hardcode the ConnectionToken. The SDK manages the ConnectionToken's lifecycle.
//     return fetch( base_url + 'v1/api/stripe_terminal_connection_token', { method: "POST" })
//         .then(function(response) {
//             return response.json();
//         })
//         .then(function(data) {
            
//             return data.secret;
//         });
// }
  
// // Handler for a "Discover readers" button
// function discoverReaderHandler() {
//     var config = {simulated: true};
    
//     terminal.discoverReaders(config).then(function(discoverResult) { 

//         if (discoverResult.error) {
//             toastr.error('Failed to discover: '+ discoverResult.error); 
//         } else if (discoverResult.discoveredReaders.length === 0) {
//             toastr.error('No available readers.');  
//         } else {
//             discoveredReaders = discoverResult.discoveredReaders;
//             connectReaderHandler(discoveredReaders); 
//             log('terminal.discoverReaders', discoveredReaders);
//         }
//     });
// }
  
// // Handler for a "Connect Reader" button
// function connectReaderHandler(discoveredReaders) {
//     // Just select the first reader here. 
//     var selectedReader = discoveredReaders[0];
//     terminal.connectReader(selectedReader).then(function(connectResult) {
//         if (connectResult.error) {
//             console.log('Failed to connect: ', connectResult.error);
//             toastr.error(connectResult.error); 
//         } else {
//             toastr.success('Connected to reader: ' + connectResult.reader.label);
//             console.log('Connected to reader: ', connectResult.reader.label); 
//         }
//     });
// }
  
// function fetchPaymentIntentClientSecret(amount) {
//     const bodyContent = JSON.stringify({ amount: amount });
  
//     return fetch(base_url + 'v1/api/stripe_collect_payment', {
//         method: "POST",
//         headers: {
//             'Content-Type': 'application/json'
//         },
//         body: bodyContent
//     })
//     .then(function(response) {
//       return response.json();
//     })
//     .then(function(data) { 
//         return data.client_secret;
//     });
// }
  
// function collectPayment(amount) {
    
//     var card_number_is_stripe = document.getElementById('card-number-for-stripe-is').value;
//     if(card_number_is_stripe == '')
//     {
//         toastr.error('Card number is required.');
//         return ;
//     } 

//     fetchPaymentIntentClientSecret(amount).then(function(client_secret) {
//         terminal.setSimulatorConfiguration({testCardNumber: card_number_is_stripe});
//         terminal.collectPaymentMethod(client_secret).then(function(result) {
//             if (result.error) {
//                 // Placeholder for handling result.error
//             } else { 
//                 terminal.processPayment(result.paymentIntent).then(function(result) {
//                     if (result.error) 
//                     { 
//                         toastr.error(result.error.message); 
//                     } else if (result.paymentIntent) {
//                         paymentIntentId = result.paymentIntent.id;  
//                     }
//                 });
//             }
//         });
//     });
// }
  
// function capture(paymentIntentId) {
    
//     return fetch(base_url + 'v1/api/stripe_capture_payment', {
//         method: "POST",
//         headers: {
//             'Content-Type': 'application/json'
//         },
//         body: JSON.stringify({"id": paymentIntentId})
//     })
//     .then(function(response) {
//         return response.json();
//     })
//     .then(function(data) {
//         log('server.capture', data);
//     });
// }
  



// const discoverButton = document.getElementById('discover-button');
// // discoverButton.addEventListener('click', async (event) => {
// //   discoverReaderHandler();
// // });
// discoverReaderHandler();

// // const connectButton = document.getElementById('connect-button');
// // connectButton.addEventListener('click', async (event) => {
// //   connectReaderHandler(discoveredReaders);
// // });


// document.addEventListener('DOMContentLoaded',function(){
//     const collectButton = document.getElementById('collect-button');
//     collectButton.addEventListener('click', async (event) => {
//         amount = 20
//         collectPayment(amount);
//     });
    
//     const captureButton = document.getElementById('capture-button');
//     captureButton.addEventListener('click', async (event) => {
//         capture(paymentIntentId);
//     });
// }, false)

  
// function log(method, message){
//     var logs = document.getElementById("logs");
//     var title = document.createElement("div");
//     var log = document.createElement("div");
//     var lineCol = document.createElement("div");
//     var logCol = document.createElement("div");
//     title.classList.add('row');
//     title.classList.add('log-title');
//     title.textContent = method;
//     log.classList.add('row');
//     log.classList.add('log');
//     var hr = document.createElement("hr");
//     var pre = document.createElement("pre");
//     var code = document.createElement("code");
//     code.textContent = formatJson(JSON.stringify(message, undefined, 2));
//     pre.append(code);
//     log.append(pre);
//     logs.prepend(hr);
//     logs.prepend(log);
//     logs.prepend(title);
// }
  
// function formatJson(message){
//     var lines = message.split('\n');
//     var space = " ".repeat(2);
//     var json = "";
//     for(var i = 1; i <= lines.length; i += 1){
//         line = i + space + lines[i-1];
//         json = json + line + '\n';
//     }
//     return json;
// }
  