var fileobj;
function upload_file(e) {
    e.preventDefault();
    ajax_file_upload(e.dataTransfer.files);
}
 
function file_explorer() {
    document.getElementById('selectfile').click();
    document.getElementById('selectfile').onchange = function() {
        files = document.getElementById('selectfile').files;
        ajax_file_upload(files);
    };
}
 
function ajax_file_upload(file_obj) {
    if(file_obj != undefined) {
        var form_data = new FormData();
        for(i=0; i<file_obj.length; i++) {  
            form_data.append('file[]', file_obj[i]);  
        }
        $.ajax({
            type: 'POST',
            url: $('#upload-url').val(),
            contentType: false,
            processData: false,
            data: form_data,
            success:function(response) {
                $('#selectfile').val('');
                var files = $.makeArray(JSON.parse(response));
                var current_files = $('#release-paper-work').val() != '' ? $.makeArray(JSON.parse($('#release-paper-work').val())) : [];
                $('#release-paper-work').val(JSON.stringify(current_files.concat(files)));
                var base_url = config.base_url;
                var html = '';
                for(var i = 0; i < files.length; i ++ ){
   					html = `<tr><td>${files[i]} </td><td><a target='_blank'href='${config.base_url}uploads/${files[i]}'>preview</a></td></tr>`;
   					$('#uploaded-files').append(html)
                }
            }
        });
    }
}