<!--MAIN  -->
<section class="container-fluid pl-0 mt-5">
      <div class="row">
        <div class="col-md-3 d-none d-md-block mx-2">
          <form action="" method="get" id="search_form_left_side">
            <div class="row filter">
              <div class="col-12 bg-dark h-25 py-0 my-0 text-center">
                <h4 class="filter-header my-2">Categories</h4>
              </div>

              
              <div class="col-12">
                <?php foreach($all_categories as $key => $value) { ?>  
                  <label for="retailers" class="my-2">
                    <input
                      class="mr-2"
                      type="radio" 
                      <?php  echo ($value->id == $category_id) ? "checked" : ""; ?>
                      name="category_id"
                      value="<?=  $value->id; ?>"
                      onClick="document.getElementById('search_form_left_side').submit();"
                    />
                    <?=  $value->name; ?> (2)</label
                  >
                <?php } ?>   
              </div>
            </div>

            <div class="row filter">
              <div class="col-12 bg-dark h-25 py-0 my-0 text-center">
                <h4 class="filter-header my-2">Price</h4>
              </div>
              <div class="col-12">
                <label for="0-25" class="my-2">
                  <input class="mr-2" type="radio" id="0-25" name="price" />
                  $0.00 - $24.99</label
                >
                <label for="25-50" class="my-2">
                  <input class="mr-2" type="radio" id="25-50" name="price" />
                  $25.00 - $49.99</label
                >
                <label for="50-99" class="my-2">
                  <input class="mr-2" type="radio" id="50-99" name="price" />
                  $50.00 - $99.99</label
                >
              </div>
            </div>

            <div class="row filter">
              <div class="col-12 bg-dark h-25 py-0 my-0 text-center">
                <h4 class="filter-header my-2">Location</h4>
              </div>
              <div class="col-12">
                 

                <?php foreach($all_locations as $key => $value) { ?> 
                  <label for="henderson" class="my-2">
                    <input
                      class="mr-2"
                      <?php  echo ($value->id == $location_id) ? "checked" : ""; ?>
                      type="radio" 
                      value="<?=  $value->id; ?>"
                      onClick="document.getElementById('search_form_left_side').submit();"
                      name="location_id"
                    />
                    <?=  $value->name; ?> (7)</label >
                <?php } ?>   
              </div>
            </div>
          </form>
        </div>
        <div class="col-md-8 mx-auto">
          <div class="col-12 my-3">
            <div class="row">
              <div class="col-12">
                <img
                  src="<?php echo base_url(); ?>/assets/frontend_images/category-header-img.png"
                  class="w-100"
                  alt=""
                />
              </div>
            </div>
            <div class="col-12 p-0" id="store">
              <div class="row my-5" > 
                <?php foreach($products_list as $key => $product){ ?> 

                  <div class="col-lg-4 col-md-6 col-sm-6 col-12 my-2">
                  <a href="<?php echo base_url(); ?>product/<?php echo $product->id; ?>">
                    <div class="store__item text-center" data-id="<?= $product->id; ?>"  data-price="<?= $product->selling_price; ?>" data-title="<?= $product->product_name; ?>" >
                      <i class="far fa-heart"></i>

                      <?php if( empty($product->feature_image) ) :  ?>
                        <img
                          src="<?php echo base_url(); ?>/assets/pos_images/default_product_image.jpg"
                          alt="<?= $product->product_name; ?>"
                          class="mx-auto img-width"
                        />
                      <?php else:  ?>
                        <img
                          src="<?php echo $product->feature_image; ?>"
                          alt="<?= $product->product_name; ?>"
                          class="mx-auto img-width"
                        />
                      <?php endif;  ?>

                      <div class="w-100 text-left">
                        <h6 class="mt-"><?= $product->product_name; ?> (<?= $product->quantity; ?>)</h6>
                        <h5 class="mt- text-danger mb-">$<?= number_format($product->selling_price,2); ?></h3>
                      </div>
                      <button class="btn-success w-100 addToCartBtn">
                        Add to Cart
                      </button>
                    </div>
                    </a>
                  </div>

                <?php } ?> 
                </div>
              </div>
            </div>


            <div class="col-12 col-lg-12 col-md-12 slick-current slick-center">
                <?php echo $this->pagination->create_links(); ?>
            </div>
          </div>
        </div>
      </div>
    </section>