<style>
	.div_box {
		width:100%;
		height: 500px;
		display:flex;
		justify-content:center;
		align-items:center;
		overflow:hidden;
	}
	.div_box img {
		flex-shrink:0;
		-webkit-flex-shrink: 0;
		max-width:70%;
		max-height:90%;
	}
</style>
 
<section>
	<div class="container-fluid bg-white" id="product">
		<div class="row product__overview justify-content-center py-5">
			<div class="col-xl-6 col-lg-6 col-md-7 col-sm-10 col-11 product__imgContainer p-3" >
				<div class="text-right div_box">

				<?php if( !empty($product->feature_image) ) : ?>
					<img
					src="<?= $product->feature_image; ?>"
					alt="<?= $product->product_name; ?>"
					class="product__mainImg mx-auto img-responsive w-100 product_active_image"
					/>
					<?php else: ?>
						<img
						src="<?php echo base_url(); ?>/assets/pos_images/default_product_image.jpg"
						alt="<?= $product->product_name; ?>"
						class="product__mainImg mx-auto img-responsive w-100 product_active_image"
						/>
					<?php endif; ?>
				</div>
				<div class="d-flex product__subImages" <?php if(isset($gallery_lists) and !empty($gallery_lists)) { ?> style=" height: 150px;" <?php  }   ?>   >
					<?php   
					if(isset($gallery_lists) and !empty($gallery_lists))
					{
						foreach($gallery_lists as $key => $value) { ?>
							<img src="<?php echo $value->image_name; ?>"  class="select_image" alt="<?= $product->product_name; ?>" />
							<?php 
						} 
					} 
					?> 
				</div>
			</div>
			<div class="col-xl-4 col-lg-5 col-md-5 col-sm-10 col-11 product__detail" >
				<h3><?= $product->product_name; ?>  </h3>
				<h2 class="text-danger">$<?= number_format($product->selling_price,2); ?></h2>

	                <div class="bg-white w-100 p-2 p-md-4"> 
	                    <ul class="list-unstyled">
	                        <li class="my-4">Price <span class="product__price">$55.00</span></li> 
                            <li class="my-4"> In stock</li>  
	                        <li class="my-4">Category  <span class="product__price"> N/A</span></li>
	                        <li class="my-4">Dimension  <span class="product__price"> <?php echo $product->width; ?></span> x <?php echo $product->length; ?></li>
	                        <li class="my-4">Length  <span class="product__price">  <?php echo $product->length; ?>in</span></li>
	                        <li class="my-4">Weight <span class="product__price">  <?php echo $product->weight; ?>lb</span></li>
	                        <li class="my-4">Physical Location <span class="product__price"> N/A</span></li>
	                        <li class="my-4">Warehouse <span class="product__price"> Muhammad Zeeshan</span></li>
	                    </ul>
	                     
	                </div>
	                <div class="bg-white w-100 p-2 p-md-4 mt-5 d-flex product__deliveryDetails">
                     	<img src="<?php echo $product->barcode_image; ?>" style="width:100%">
	                </div> 
			</div>
		</div>


		<div class="row justify-content-center product__details my-4">
			
			<div class="col-10 ">
				<label for="quantity" >Qty
					<input type="number" name="quantity" id="quantity" class="form-control d-inline product_quantity w-75 w-md-50" value="1" />
				</label> 

				<input type="hidden" class="product_id" name="product_id" value="<?= $product->id; ?>" />
				<button class="btn add_to_cart_button btn-success addToCartBtn">Add To Cart</button>
			</div>


			 

			<div class="col-10 product__detailTexts">
				<?php 
				$video_url = json_decode($product->video_url);
				if (!empty($video_url)) 
				{ 
					foreach ($video_url as $key => $video):
						if (!empty($video)) 
						{
						  	echo '<a target="_blank" href= "' . $video .'" /> Watch Video </a> <br>'; 
					  	}   
		         	endforeach ;
	         	}
			 	?> 
			</div>
		</div>  
	</div>
</section>

<script>
	document.addEventListener('DOMContentLoaded',function(){
		$(document).on('click','.select_image',function(){
			let selected_image_url = $(this).attr('src');

			$('.product_active_image').attr('src',selected_image_url);
		})
	}, false)
</script>