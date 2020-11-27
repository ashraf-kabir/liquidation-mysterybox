<!--MAIN  -->
<section class="container-fluid pl-0 mt-5">
      <div class="row justify-content-center">
        <div class="col-xl-2 col-lg-2 col-md-3 d-none d-md-block mx-2">
          <form action="">
            <div class="row filter">
              <div class="col-12 bg-dark h-25 py-0 my-0 text-center">
                <h4 class="filter-header my-2">Categories</h4>
              </div>
              <div class="col-12">
                <label for="retailers" class="my-2">
                  <input
                    class="mr-2"
                    type="radio"
                    id="retailers"
                    name="categories"
                  />
                  Online Retailers Return (2)</label
                >
                <label for="appliances" class="my-2">
                  <input
                    class="mr-2"
                    type="radio"
                    id="appliances"
                    name="categories"
                  />
                  Appliances (2)</label
                >
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
                <label for="vegas" class="my-2">
                  <input class="mr-2" type="radio" id="vegas" name="location" />
                  Las Vegas NV (8)</label
                >
                <label for="henderson" class="my-2">
                  <input
                    class="mr-2"
                    type="radio"
                    id="henderson"
                    name="location"
                  />
                  Henderson, NV (7)</label
                >
                <label for="phoenix" class="my-2">
                  <input
                    class="mr-2"
                    type="radio"
                    id="phoenix"
                    name="location"
                  />
                  Phoenix Az (2)</label
                >
              </div>
            </div>
          </form>
        </div>
        <div class="col-xl-8 col-lg-8 col-md-9 col-12">
          <div class="col-xl-12 col-lg-12 col-md-12 col-12 my-3">
            <div class="row justify-content-center">
              <div class="col-md-10 col-12">
                <img
                  src="<?php echo base_url(); ?>/assets/frontend_images/category-header-img.png"
                  class="w-100"
                  alt=""
                />
              </div>
            </div>
            <div class="col-xl-12 col-lg-12 col-md-8 col-12" id="store">
              <div class="row justify-content-center justify-content-md-between my-5" > 
                <?php foreach($products_list as $key => $product){ ?> 

                  <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-12 my-2">
                    <div
                      class="store__item text-center"
                      data-id="1"
                      data-price="49.99"
                      data-title="Screw drivers & Nut drivers (93)"
                    >
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
                        <h3 class="mt- text-danger mb-">$<?= number_format($product->selling_price,2); ?></h3>
                      </div>
                      <button class="btn btn-success mt-5 w-100 addToCartBtn">
                        Add to Cart
                      </button>
                    </div>
                  </div>

                <?php } ?> 
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>