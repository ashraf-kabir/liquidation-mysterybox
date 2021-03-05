<main class="container-fluid">
      <div class="row bg-white justify-content-center">
        <div class="col-12 col-md-8">
          <img class="main__img w-100" src="./assets/frontend_images/vegas_img.jpg" alt="">
        </div>
      </div>
      
      <div class="row justify-content-center p-4">
        <div class="col-11 col-md-5 my-3 main__imgLink">
          <a href="<?php echo base_url(); ?>?type=3">
            <img src="./assets/frontend_images/by_truck.jpg" class="w-100" alt="">
          </a>
        </div>
        <div class="col-11 col-md-5 my-3 main__imgLink">
          <a href="<?php echo base_url(); ?>?type=1">
            <img src="./assets/frontend_images/by_pallette.jpg" class="w-100" alt="">
          </a>
        </div>
      </div>
    </main>


    <!-- <section class="container-fluid glide" id="what-we-offer">
      <div class="col-12 col-md-10 mx-auto mt-4">
      <div class="row">
        <div class="col-12 d-flex justify-content-between">
          <h2 class="section__header">WHAT <span>WE OFFER</span></h2>
          <ul class="nav nav-tabs ml-auto" id="myTab" role="tablist">
            <li class="nav-item">
              <a class="nav-link active offer__toggler" id="truckloads-tab" data-toggle="tab" href="#truckloads" role="tab" aria-controls="truckloads" aria-selected="true">TRUCKLOADS</a>
            </li>
            <li class="nav-item">
              <a class="nav-link offer__toggler" id="pallets-tab" data-toggle="tab" href="#pallets" role="tab" aria-controls="pallets" aria-selected="false">PALLETS</a>
            </li>
          </ul>
          <div class="slick__buttons">
            <button class="slick__btnLeft btn" onclick="Array.from(document.querySelectorAll('.slick-prev')).map((i) => i.click())">&lt;</button>
            <button class="slick__btnRight btn" onclick="Array.from(document.querySelectorAll('.slick-next')).map((i) => i.click())">&gt;</button>
          </div>
           
        </div>
      </div>
      <hr>
        <div class="tab-content" id="myTabContent">
          <div id="truckloads" role="tabpanel" aria-labelledby="truckloads-tab" class="tab-pane fade show active row justify-content-center bg-light slider  mt-5">
            <?php foreach($trucks as $key => $value ) { ?> 
            <div class="col-12 col-lg-3 col-md-4 ">
              <div class="offer__item">
                <a href="<?php echo base_url(); ?>detail/<?php echo $value->id ?>">
                <img src="./assets/frontend_images/offer-Img-1.jpg" class="w-100" alt="Offer Image">
                <h5 class="text-gray mt-2">TRUCKLOADS</h5>
                <h5 class="offer__itemTitle mt-2"><?php echo $value->item_name ?></h5>
              </a>
              </div>
            </div>
            <?php } ?>
          </div>

          <div id="pallets" role="tabpanel" aria-labelledby="pallets-tab" class="tab-pane fade show active row justify-content-center bg-light slider  mt-5">
            <?php foreach($pallets as $key => $value ) { ?> 
              <div class="col-12 col-lg-3 col-md-4 ">
                <div class="offer__item">
                  <a href="<?php echo base_url(); ?>detail/<?php echo $value->id ?>">
                  <img src="./assets/frontend_images/offer-Img-1.jpg" class="w-100" alt="Offer Image">
                  <h5 class="text-gray mt-2">PALLETS</h5>
                  <h5 class="offer__itemTitle mt-2"><?php echo $value->item_name; ?></h5>
                </a>
                </div>
              </div>
            <?php } ?> 
          </div>
          
        </div>
    </div>
    </section>

    <section class="container-fluid" id="our-updates">
      <div class="row justify-content-center my-5">
        <div class="col-12 col-md-10">
          <h2 class="section__header">Our Latest Updates</h2>
        </div>
        <div class="col-12 col-md-5">
          <a href="">
            <img src="./assets/frontend_images/bulldog-auction.jpg" alt="Update image" class="w-100">
          </a>
        </div>
        <div class="col-12 col-md-5">
          <a href="">
            <img src="./assets/frontend_images/bulldog-liquidation.jpg" alt="Update image" class="w-100">
          </a>
        </div>
      </div>
    </section> -->