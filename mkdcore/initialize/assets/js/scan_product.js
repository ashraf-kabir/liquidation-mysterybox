var pathName = window.location.pathname; 
path = pathName.split('/')[1]+'/';
var ajaxURLPath = document.location.origin + '/';

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
                    alert(response.msg)
                }

                if(response.success)
                {
                     
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

        console.log("Initialization finished. Ready to start");
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