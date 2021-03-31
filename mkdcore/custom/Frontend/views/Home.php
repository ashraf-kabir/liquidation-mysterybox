<div class="container"> 
  <?php if ($this->session->flashdata('success1')): ?> 
      <div class="alert alert-success alert-dismissible " role="alert">
          <?php echo $this->session->flashdata('success1'); ?>
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
          </button>
      </div> 
  <?php endif ?>

  <?php if ($this->session->flashdata('error1')): ?> 
      <div class="alert alert-danger alert-dismissible " role="alert">
          <?php echo $this->session->flashdata('error1'); ?>
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
          </button>
      </div> 
  <?php endif ?>
</div>
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
