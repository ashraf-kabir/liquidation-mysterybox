var canvas = document.getElementById('signature-pad');
 
function resizeCanvas() { 
    var ratio =  Math.max(window.devicePixelRatio || 1, 1);
    canvas.width = canvas.offsetWidth * ratio;
    canvas.height = canvas.offsetHeight * ratio;
    canvas.getContext("2d").scale(ratio, ratio);
}

window.onresize = resizeCanvas;
resizeCanvas();

var signaturePad = new SignaturePad(canvas, {
  backgroundColor: 'rgb(255, 255, 255)' // necessary for saving image as JPEG; can be removed is only saving as PNG or SVG
});

document.getElementById('save-png').addEventListener('click', function () {
    if (signaturePad.isEmpty()) {
        return alert("Please provide a signature first.");
    }
  
    var data = signaturePad.toDataURL('image/png');
    $('#signature64').val(data)
    console.log(data);
    // window.open(data);
});


document.getElementById('erase').addEventListener('click', function () {
    var ctx = canvas.getContext('2d');
    ctx.globalCompositeOperation = 'destination-out';
});
