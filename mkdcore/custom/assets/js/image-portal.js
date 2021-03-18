document.addEventListener('DOMContentLoaded',function(){

 // <button data-url="feature_image" data-id="feature_image_id" type="button" class="btn btn-primary btn-sm add-image-form-portal create-image-portal-modal">+</button>

	let window_path_name = window.location.pathname;
	window_path = window_path_name.split("/")[1] + "/";
	let ajax_url_path = document.location.origin + "/"; 
	let loading_image = "<img src='../assets/image/loading.gif'  />";





	$(document).on('click', '.add-image-form-portal', function(){ 
	    $('.add-image-form-portal-modal').modal('toggle'); 
 
	    load_user_images_list(1);
	});


	$(document).on('click', '.trigger-checkbox-event', function(e){ 
		
		if ($(this).prop('checked')) 
		{
		    var image_url = $(this).parent().find('img').attr('image-url');
		    var image_id  = $(this).parent().find('img').attr('image-id');


		    var image_name_url  = $('.add-image-form-portal').attr('data-url');
		    var image_name_id   = $('.add-image-form-portal').attr('data-id'); 
	 

		  	$.ajax({
				type: "POST",
				url: ajax_url_path + "v1/api/upload_image_portal_image_to_s3",
				timeout: 30000,
				dataType: "JSON", 
				data: {'image_url' : image_url, 'image_id' : image_id},
				success: function (response) 
				{	 
				    $('#' + image_name_url).val(response.image);
				    $('#' + image_name_id).val(response.id);
				},
				error: function()
				{
					alert('Error! Connection timeout.')
				}
		  	}); 

	  	}
	});




	function load_user_images_list(page_no = 1) 
	{ 
		$('.image-form-portal-body').html(loading_image);
	  	$.ajax({
			type: "POST",
			url: ajax_url_path + "v1/api/get_images",
			timeout: 30000,
			dataType: "JSON", 
			data: {'page_no' : page_no},
			success: function (response) {
			    if (response.content_div) 
			    {
		         	$('.image-form-portal-body').html(response.content_div); 
			    }
			},
			error: function()
			{
				alert('Error! Connection timeout.')
			}
	  	});
	}



	$(document).on("click", ".image_portal_pagination", function (e) {
		e.preventDefault();
		const page_no = e.currentTarget.getAttribute("page-no");  
		load_user_images_list(page_no); 
	});



	$(document).on("click", ".image_portal_pagination", function (e) {
		e.preventDefault();

		const data_url  =  $(".create-image-portal-modal").attr('data-url');
		const data_id   =  $(".create-image-portal-modal").attr('data-id');

		//trigger upload event here
	});



	function create_image_portal_modal()
	{
		if($('.add-image-form-portal').hasClass('create-image-portal-modal'))
		{

			let build_modal = '<div class="modal add-image-form-portal-modal" tabindex="-1" role="dialog">';
			    build_modal += '<div class="modal-dialog" role="document" style="max-width: 55%;"  >';
			    build_modal += '<div class="modal-content">';
			    build_modal += '<div class="modal-header"> <h5 class="modal-title">Select Image</h5>';
			    build_modal += '<button type="button" class="close" data-dismiss="modal" aria-label="Close">';
			    build_modal += '<span aria-hidden="true">&times;</span>';
			    build_modal += '</button> </div><div class="modal-body image-form-portal-body">';
			    build_modal += '</div> <div class="modal-footer">  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> </div>';
			    build_modal += '</div> </div> </div>';


		 	$('.add-image-form-portal').append(build_modal);
		}
	}


	// create_image_portal_modal();
}, false);