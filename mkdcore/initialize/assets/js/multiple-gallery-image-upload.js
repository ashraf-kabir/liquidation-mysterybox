
let manaknight_multiple_image_id_to_add = 1;
function manaknightMultipleImageUploader(event, imgid) {
    const files = event.target.files;
    Object.keys(files).forEach(i => {
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
                success: function (data) {

                    $('#manaknight_multiple_image_' + manaknight_multiple_image_id_to_add).val(data.file);
                    $('#barcode_image_' + manaknight_multiple_image_id_to_add).val(data.file);
                    $('#manaknight_multiple_image_id_' + manaknight_multiple_image_id_to_add).val(data.id);
                    $('#barcode_image_id_' + manaknight_multiple_image_id_to_add).val(data.id);



                    var data_md4 = '<div class="col-md-3 form-group"><span data-id="' + manaknight_multiple_image_id_to_add + '" class="gallery-img-delete-close"><i class="fa fa-trash img-wrapper-delete-close"></i></span><img style="height: 150px;width: 70%;" src="' + data.file + '" /></div>';

                    $('.add_images_gallery').append(data_md4);

                    manaknight_multiple_image_id_to_add = manaknight_multiple_image_id_to_add + 1;
                    $('.gallery_image_add_inputs').append('<input type="hidden" id="manaknight_multiple_image_' + manaknight_multiple_image_id_to_add + '" name="gallery_image[]"/><input type="hidden" id="manaknight_multiple_image_id_' + manaknight_multiple_image_id_to_add + '" name="gallery_image_id[]"/>');
                }
            });
        }
        reader.readAsDataURL(file);
    });
} 