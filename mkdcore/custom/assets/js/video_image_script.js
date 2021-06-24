let this_image_object = "";
    document.addEventListener('DOMContentLoaded',function(){
 
        $(document).on('click','.validate-videos',function(e)
        { 
            $('.thumbnail_video_row').each(function(index,object){ 
                if( $(this).find('.validate_url_field').val() != "" )
                {
                    if( $(this).find('.validate_img_field').val() == "" )
                    {
                        e.preventDefault();
                        var error_msg  = $(this).find('label').eq(0).text(); 
                        error_msg      = error_msg.trim();

                        alert('Please add thumbnail for ' + error_msg );
                        return false;
                    } 
                } 
            }); 
        });
 


        


        
        
        $(document).on('click','.mkd-choose-image-thumbnail',function(e)
        {
            this_image_object = $(this);

            $("#mkd-media-gallery-wrapper").html('');

            var form_category_id = $('#form_category_id').val();

            var view_width = Number($(this).attr("data-view-width"));
            var view_height = Number($(this).attr("data-view-height"));
            var boundary_width = Number($(this).attr("data-boundary-width"));
            var boundary_height = Number($(this).attr("data-boundary-height"));
            var image_preview = $(this).attr("data-image-preview");
            var image_id = $(this).attr("data-image-id");
            var image_url = $(this).attr("data-image-url");
            window.crop_output_image = image_preview;
            window.crop_image_id = image_id;
            window.crop_image_url = image_url;

            if (Number.isInteger(view_width)) {
              window.crop_width = Number(view_width);
            }
            if (Number.isInteger(view_height)) {
              window.crop_height = Number(view_height);
            }
            if (Number.isInteger(boundary_width)) {
              window.crop_boundary_width = Number(boundary_width);
            }
            if (Number.isInteger(boundary_height)) {
              window.crop_boundary_height = Number(boundary_height);
            }
            $("#mkd-media-image-thumbnail").modal("show");
            $("#mkd-load-more-container").show();

            $.ajax({
                type: "GET",
                data: { form_category_id },
                dataType: "JSON",
                url: "/v1/api/images/get_thumbnails_list"
            }).done(function(result) {
                window.asset_page = result.page;
                window.asset_num_page = result.num_page;
                window.asset_num_item = result.num_item;
                window.asset_per_page = result.per_page;
                var items = result.item;
                for (var i = 0; i < items.length; i++) {
                    var element = items[i];
                    $("#mkd-media-gallery-wrapper").append(
                    '<div class="col-md-2 mb-2"><img data-id="' +
                    element.id +
                    '" data-video="' +
                    element.video +
                    '" src="' +
                    element.url +
                    '" alt=""  class="img-fluid mkd-gallery-image-image mkd-gallery-image-image-2 img-size-preset"></div>'
                    );
                }

                if (result.page + result.per_page >= result.num_item) {
                    $("#mkd-load-more-container").hide();
                }

                window.asset_page = window.asset_page + window.asset_per_page;

                
            });
        });

 
        $(document).on('click','.mkd-gallery-image-image-2',function(){ 
            var id = Number($(this).attr("data-id"));
            var img_src_id = $(this).attr("src");
            var vid_src_id = $(this).attr("data-video");
            
            $("#mkd-media-image-thumbnail").modal("hide");
            this_image_object.parent().parent().find('label').eq(1).after('<span class="youtube-image-delete-close"><i class="fa fa-trash img-wrapper-delete-close"></i></span>');
            this_image_object.parent().find('.output_youtube_thumbnail_1').attr('src',img_src_id);
            this_image_object.parent().find('.youtube_thumbnail_1').val(img_src_id);
            this_image_object.parent().find('.youtube_thumbnail_1_id').val(id); 
            this_image_object.parent().parent().find('.validate_url_field').val(vid_src_id); 
        });


        $(document).on('click','#mkd-thumbnail-upload',function(){ 
            this_image_object.parent().find('.file_to_upload_class').trigger('click'); 
        });

        $(document).on('click','.img-delete-close',function(){ 
            $(this).parent().find('.check_change_event').val(''); 
            $(this).parent().find('.edit-preview-image').attr('src','');  
            $('#feature_image_complete').text('');  
            $(this).hide(); 
        });


        

    }, false);
 
function manaknightThumbnailImage(event, imgid) 
{
     
    const files = event.target.files;
    Object.keys(files).forEach(i => 
    {
        const file = files[i];
        const reader = new FileReader();
        reader.onload = (e) => {
        //server call for uploading or reading the files one-by-one
        //by using 'reader.result' or 'file'
        var formData = new FormData();
        formData.append("file", file);
        $.ajax({
            url: "/v1/api/file/upload",
            type: "post",
            data: formData,
            processData: false,
            contentType: false,
            cache: false,
            async: false,
            success: function(data)
            {


                $("#mkd-media-image-thumbnail").modal("hide");
                this_image_object.parent().parent().find('label').eq(1).after('<span class="youtube-image-delete-close"><i class="fa fa-trash img-wrapper-delete-close"></i></span>');
                this_image_object.parent().find('.output_youtube_thumbnail_1').attr('src',data.file);
                this_image_object.parent().find('.youtube_thumbnail_1').val(data.file);
                this_image_object.parent().find('.youtube_thumbnail_1_id').val(data.id); 
            }
        });
        }
        reader.readAsDataURL(file);
    });
} 