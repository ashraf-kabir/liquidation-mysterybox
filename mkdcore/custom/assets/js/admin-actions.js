
document.addEventListener('DOMContentLoaded',function(){
 
        $(document).on('click','.gallery-img-delete-close',function(e)
        { 
            let gallery_id = $(this).attr('data-id'); 

            $('#manaknight_multiple_image_'+gallery_id).remove();
            $('#manaknight_multiple_image_id_'+gallery_id).remove();
            $(this).parent().remove();
        });

        $(document).on('click','.youtube-image-delete-close',function(e)
        {  
            $(this).parent().find('.output_youtube_thumbnail_1').attr('src','');
            $(this).parent().find('.validate_img_field').val(''); 
            $(this).remove(); 
        });


}, false)