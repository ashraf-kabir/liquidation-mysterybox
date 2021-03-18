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
			<div
			class="col-xl-6 col-lg-6 col-md-7 col-sm-10 col-11 product__imgContainer p-3"
			>
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


			<div class="col-10">
				<h5>Product Details</h5>
			</div>

			<div class="col-10 product__detailTexts">
				<p>
					Mastercraft Screwdriver Set features satin-chrome plated shafts
					for durability and rust resistance.
				</p>
				<p>Made with CRV blades for durability and strength.</p>
				<p>Soft textured grip for comfort and control.</p>
				<p>
					Set includes 15 screwdrivers (slotted：1/8" x 3", 3/16" x 4", 1/4"
					x 1-1/2", 1/4" x 4", 5/16" x 6", phillips：ph1 x 1-1/2", ph2 x
					1-1/2", ph2 x 4", ph3 x 6", two pieces square：S0 x 3", S1 x
					1-1/2", S1 x 4", S2 x 1-1/2", S2 x 4", S3 x 6")
				</p>
				<p>
					8 precision drivers (slotted: 2.0 x 50, 2.4 x 50, 3.0 x 50 mm,
					phillips: #000 x 50, #00 x 50, #0 x 50 mm, torx: T8x50, T9x50 mm).
				</p>
				<p>
					9 hex keys (5/64", 3/32", 7/64", 1/8", 9/64", 5/32", 3/16", 7/32",
					1/4").
				</p>
				<p>
					42 bits (7pc slotted：1/8" x 2pc, 3/16" x 2pc, 1/4" x 2pc, 5/16";
					11pc phillips：#0 x 2pc, #1 x 3pc, #2 x 4pc, #3 x 2pc; 10pc
					square：#0 x 2pc, #1 x 2pc, #2 x 4pc, #3 x 2pc; 8pc torx：T5, T7,
					T10, T15, T20, T25, T30, T40; 6pc hex：2, 3, 4, 5, 5.5, 6 mm)
				</p>
				<p>
					Also includes tack remover, nut driver (1/4"), nut driver (5/16"),
					demo driver (5/16"x6"), ratchet handle, and nylon bag.
				</p>
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