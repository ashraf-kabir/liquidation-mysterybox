let image_id_uppload_library = '';
let image_url_uppload_library = '';


var picture = new window.uppload_Uppload({
    call: ".uppload-button",
    bind: ".uppload-image",
    lang: window.uppload_en,
    uploader: window.uppload_fetchUploader({
      endpoint: "/v1/api/file/upload",
      responseFunction: json => {  
           console.log(json)
          $("#" + image_url_uppload_library).val(json.file);
          $("#" + image_url_uppload_library + "_id").val(json.id);
          $("#" + image_url_uppload_library + "_text").html(json.file); 
      }
    })
});

$(document).on('click','.image_id_uppload_library',function(){
  image_id_uppload_library  = $(this).attr('data-image-id'); 
  image_url_uppload_library  = $(this).attr('data-image-url'); 

});
 

 

picture.use([
  new window.uppload_Local(),
  new window.uppload_Camera(), 
  new window.uppload_Instagram(),
  new window.uppload_Facebook(), 
  new window.uppload_Instagram(),
  new window.uppload_URL(),
  new window.uppload_Instagram(),
  new window.uppload_Screenshot(),   
  new window.uppload_Pinterest(),
  new window.uppload_Flickr(),
  new window.uppload_NineGag(),
  new window.uppload_DeviantArt(),
  new window.uppload_ArtStation(),
  new window.uppload_Twitter(),
  new window.uppload_Flipboard(),
  new window.uppload_Fotki(),
  new window.uppload_LinkedIn(),
  new window.uppload_Reddit(),
  new window.uppload_Tumblr(),
  new window.uppload_WeHeartIt(), 
  new window.uppload_Crop(),  
  new window.uppload_Brightness(), 
  new window.uppload_Rotate(),  
  new window.uppload_Flip(),  
  new window.uppload_Preview(),  
  new window.uppload_Blur(),  
  new window.uppload_Contrast(),  
  new window.uppload_Grayscale(),  
  new window.uppload_HueRotate(),  
  new window.uppload_Invert(),  
  new window.uppload_Sepia(),  
  new window.uppload_Saturate(),  
]);