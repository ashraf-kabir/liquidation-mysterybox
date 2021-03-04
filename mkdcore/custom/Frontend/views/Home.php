
    <main>
      <div class="container-fluid my-4" id="main-display">
        <div class="row justify-content-center">
          <div class="col-8 text-center" style="min-height: 200px">
            <img
              src="<?php echo base_url(); ?>/assets/frontend_images/image-main.png"
              alt="Main image"
              class="w-100 h-100"
            />
          </div>
        </div>
      </div>
    </main>

    <section>
      <div class="container-fluid px-0 my-5" id="discounted-items">
        <div class="row w-100 m-0">
          <div
            class="col-lg-3 col-md-4 col-sm-6 col-12 item-box"
          >
            <a href="" class="hvr-ripple-out">
              <img src="<?php echo base_url(); ?>/assets/frontend_images/discount-img-1.png" alt=""
            /></a>
          </div>
          <div class="col-lg-3 col-md-4 col-sm-6 col-12 item-box">
            <a href="" class="hvr-ripple-out">
              <img src="<?php echo base_url(); ?>/assets/frontend_images/discount-img-2.png" alt=""
            /></a>
          </div>
          <div class="col-lg-3 col-md-4 col-sm-6 col-12 item-box">
            <a href="" class="hvr-ripple-out">
              <img src="<?php echo base_url(); ?>/assets/frontend_images/discount-img-3.png" alt=""
            /></a>
          </div>
          <div
            class="col-lg-3 col-md-4 col-sm-6 col-12 item-box"
          >
            <a href="" class="hvr-ripple-out">
              <img src="<?php echo base_url(); ?>/assets/frontend_images/discount-img-4.png" alt=""
            /></a>
          </div>
        </div>
      </div>
    </section>

    <!-- Featured Products -->
    <section>
      <div class="container-fluid px-0 my-5" id="featured-products">
        <div class="row w-100 m-0">
          <div class="col-12">
            <h3>Featured Products</h3>
          </div>
        </div>
        <div class="row w-100 m-0">
          <div class="col-12 text-center">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
              <li class="nav-item" role="presentation">
                <a
                  class="nav-link active"
                  id="accessories-tab"
                  data-toggle="tab"
                  href="#accessories"
                  role="tab"
                  aria-controls="accessories"
                  aria-selected="true"
                  >Accessories</a
                >
              </li>
              <li class="nav-item" role="presentation">
                <a
                  class="nav-link"
                  id="power-tab"
                  data-toggle="tab"
                  href="#power"
                  role="tab"
                  aria-controls="power"
                  aria-selected="false"
                  >Power Tools</a
                >
              </li>
              <li class="nav-item" role="presentation">
                <a
                  class="nav-link"
                  id="hand-tab"
                  data-toggle="tab"
                  href="#hand"
                  role="tab"
                  aria-controls="hand"
                  aria-selected="false"
                  >Hand Tools</a
                >
              </li>
            </ul>
            <div class="tab-content bg-white" id="myTabContent">
              <div
                class="tab-pane fade show active"
                id="accessories"
                role="tabpanel"
                aria-labelledby="accessories-tab"
              >
                <div
                  class="row d-flex w-100 justify-content-around py-2 mx-auto"
                >
                  <a href="" class="col-xl-3 col-lg-3 col-md-6 col-12 my-3">
                    <img
                      src="<?php echo base_url(); ?>/assets/frontend_images/accessories-img.png"
                      alt=""
                      class="w-100"
                    />
                  </a>
                  <a href="" class="col-xl-3 col-lg-3 col-md-6 col-12 my-3">
                    <img
                      src="<?php echo base_url(); ?>/assets/frontend_images/accessories-img.png"
                      alt=""
                      class="w-100"
                    />
                  </a>
                  <a href="" class="col-xl-3 col-lg-3 col-md-6 col-12 my-3">
                    <img
                      src="<?php echo base_url(); ?>/assets/frontend_images/accessories-img.png"
                      alt=""
                      class="w-100"
                    />
                  </a>
                  <a href="" class="col-xl-3 col-lg-3 col-md-6 col-12 my-3">
                    <img
                      src="<?php echo base_url(); ?>/assets/frontend_images/accessories-img.png"
                      alt=""
                      class="w-100"
                    />
                  </a>
                </div>
              </div>
              <div
                class="tab-pane fade"
                id="power"
                role="tabpanel"
                aria-labelledby="power-tab"
              >
                <div class="col-12 d-flex justify-content-around py-2">
                  <a href="" class="col">
                    <img
                      src="<?php echo base_url(); ?>/assets/frontend_images/accessories-img.png"
                      alt=""
                      class="w-100"
                    />
                  </a>
                  <a href="" class="col">
                    <img
                      src="<?php echo base_url(); ?>/assets/frontend_images/accessories-img.png"
                      alt=""
                      class="w-100"
                    />
                  </a>
                  <a href="" class="col">
                    <img
                      src="<?php echo base_url(); ?>/assets/frontend_images/accessories-img.png"
                      alt=""
                      class="w-100"
                    />
                  </a>
                  <a href="" class="col">
                    <img
                      src="<?php echo base_url(); ?>/assets/frontend_images/accessories-img.png"
                      alt=""
                      class="w-100"
                    />
                  </a>
                </div>
              </div>
              <div
                class="tab-pane fade"
                id="hand"
                role="tabpanel"
                aria-labelledby="hand-tab"
              >
                <div class="col-12 d-flex justify-content-around py-2">
                  <a href="" class="col">
                    <img
                      src="<?php echo base_url(); ?>/assets/frontend_images/accessories-img.png"
                      alt=""
                      class="w-100"
                    />
                  </a>
                  <a href="" class="col">
                    <img
                      src="<?php echo base_url(); ?>/assets/frontend_images/accessories-img.png"
                      alt=""
                      class="w-100"
                    />
                  </a>
                  <a href="" class="col">
                    <img
                      src="<?php echo base_url(); ?>/assets/frontend_images/accessories-img.png"
                      alt=""
                      class="w-100"
                    />
                  </a>
                  <a href="" class="col">
                    <img
                      src="<?php echo base_url(); ?>/assets/frontend_images/accessories-img.png"
                      alt=""
                      class="w-100"
                    />
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Category -->
    <section>
      <div class="container-fluid px-0" id="category">
        <div class="row w-100 m-0">
          <div class="col-12">
            <h3>Categories</h3>
          </div>
        </div>
        <div class="row w-100 m-0">
          <div class="col-xl-8 col-lg-8 col-md-7 col-12">
            <div class="row justify-content-center py-3"> 
              <?php foreach($all_categories as $key => $category){ ?>
                  <div class="category-item col-xl-3 col-lg-3 col-md-5 col-10 text-center" >
                      <img src="<?php echo base_url(); ?>/assets/frontend_images/drill.svg" class="category-img" alt="" />
                      <h6><?= $category->name; ?></h6>
                  </div>
               <?php } ?> 
            </div>
          </div>
          <div class="col-xl-4 col-lg-4 col-md-5 col-12 bg-dark">
            <img
              src="<?php echo base_url(); ?>/assets/frontend_images/category-img.png"
              class="w-100"
              alt="tractor-img"
            />
            <h3 class="text-orange">Lorem Ipsum</h3>
            <p class="text-white">
              Lorem ipsum dolor sit amet consectetur adipisicing elit. Soluta,
              quod. Perferendis velit amet reiciendis dolores.
            </p>
          </div>
        </div>
      </div>
    </section>

    <section>
      <div class="container-fluid bg-dark mt-5" id="about-us">
        <div class="row justify-content-center px-2 px-md-4 py-4">
          <div class="col-10">
            <h3 class="text-white">About Us</h3>
          </div>

          <div class="col-10 text-light about-details">
            <p>
              Lorem ipsum dolor sit amet consectetur adipisicing elit.
              Explicabo, rerum? Corporis, accusamus suscipit dolores id,
              nesciunt quibusdam provident rem atque numquam qui vitae
              accusantium doloremque a harum cupiditate explicabo omnis!
            </p>
            <p>
              Lorem ipsum dolor sit amet consectetur adipisicing elit.
              Explicabo, rerum? Corporis, accusamus suscipit dolores id,
              nesciunt quibusdam provident rem atque numquam qui vitae
              accusantium doloremque a harum cupiditate explicabo omnis!
            </p>
            <p>
              Lorem ipsum dolor sit amet consectetur adipisicing elit.
              Explicabo, rerum? Corporis, accusamus suscipit dolores id,
              nesciunt quibusdam provident rem atque numquam qui vitae
              accusantium doloremque a harum cupiditate explicabo omnis!
            </p>
          </div>
        </div>
      </div>
    </section>
