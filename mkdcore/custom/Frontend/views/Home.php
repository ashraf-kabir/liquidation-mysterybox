<style type="text/css">
    .main-swiper-button-next {
        margin-right: 5px;
        padding: 35px 19px;
    }

    .main-swiper-button-prev {
        margin-left: 5px;
        padding: 35px 19px;
    }

    .list-swiper-button-next {
        padding: 35px 19px;
    }

    .list-swiper-button-prev {
        padding: 35px 19px;
    }

    .slide-list-box {
        min-width: 290px;
    }

    .slider-container {
        margin-left: 0;
    }

    .swiper-slide-slider-custom img {
        display: block;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .swiper-slide-mysterybox img {
        display: block;
        width: 100%;
        height: 100%;
        object-fit: fill;
    }

    .swiper-container-mysterybox {
        width: 100%;
        height: 300px;
        margin: 20px auto;
    }

    .heading-padding-mysterybox {
        padding-top: 20px;
        padding-bottom: 20px;
    }

    .heading-padding-liquidation {
        padding-top: 20px;
        padding-bottom: 20px;
    }

    .swiper-slide-liquidation img {
        display: block;
        width: 100%;
        height: 100%;
        object-fit: fill;
    }

    .swiper-container-liquidation {
        width: 100%;
        height: 300px;
        margin: 20px auto;
    }

    .swiper-pagination-fraction {
        color: white !important;
    }

    .swiper-button-next-mysterybox-card {
        right: 16px !important;
    }

    .swiper-button-prev-mysterybox-card {
        left: 25px !important;
    }

    .top_div_categories {
        position: absolute;
        background: white;
        color: #151414;
        z-index: 2;
        padding: 5px;
        right: 41%;
        top: 44%;
    }




    @media (max-width: 991px) {
        .main-swiper-button-next {
            margin-right: 0px;
        }

        .main-swiper-button-prev {
            margin-left: 0px;
        }

        .main-swiper-button-next:hover {
            padding: 35px 19px;
            transform: scale(0.8);
        }

        .main-swiper-button-prev:hover {
            padding: 35px 19px;
            transform: scale(0.8);
        }

    }


    .slider-container {
        margin-left: 0px !important;
    }

    .swiper-container-liquidation .swiper-button-disabled {
        display: none;
    }

    @media (min-width: 767px) {
        .swiper-pagination-liquidation-card {
            display: none !important;
        }

    }


    .swiper-button-prev,
    .swiper-container-rtl .swiper-button-next {
        left: 21px;
        right: auto;
    }

    /* #google_translate_element {
        padding-right: 0px !important;
    } */
</style>


<?php if (!empty($carosal_sliders)) : ?>

    <div class="swiper-container swiper-container-sliders-custom" style="min-height: 200px;max-height: 550px" role='button'>
        <div class="swiper-wrapper" onclick="window.location.href= '/categories'">
            <?php foreach ($carosal_sliders as $key => $value) : ?>
                <div class="swiper-slide swiper-slide-slider-custom"><img src="<?php echo $value->feature_image ?>"></div>
            <?php endforeach ?>
        </div>

        <div class="swiper-button-next main-swiper-button-next"></div>
        <div class="swiper-button-prev main-swiper-button-prev"></div>
    </div>

<?php endif ?>


<div class="container">
    <?php if ($this->session->flashdata('success1')) : ?>
        <div class="alert alert-success alert-dismissible " role="alert">
            <?php echo $this->session->flashdata('success1'); ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif ?>

    <?php if ($this->session->flashdata('error1')) : ?>
        <div class="alert alert-danger alert-dismissible " role="alert">
            <?php echo $this->session->flashdata('error1'); ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif ?>
</div>




<?php if (!empty($all_categories)) : ?>
    <h5 class="heading-padding-mysterybox pl-4">Shop Mysteryboxes</h5>
    <!-- <div class="row" style=" width: 100%;height: 49px; margin-bottom: 18px; margin-left: 15px !important;">
        <div id="google_translate_element" style="padding-right: 0px; padding-left: 10px"></div>
    </div>
    <div class="clear"></div> -->
    <div class="container   pl-4">

        <div class="swiper-button-next list-swiper-button-next swiper-button-next-mysterybox-card"></div>
        <div class="swiper-button-prev list-swiper-button-prev swiper-button-prev-mysterybox-card"></div>
        <div class="swiper-container swiper-container-mysterybox mySwiper mySwiper-mysterybox" style="min-height: 200px;max-height: 300px">
            <div class="swiper-wrapper">
                <?php foreach ($all_categories as $key => $value) : ?>
                    <?php if (!empty($value->feature_image)) : ?>
                        <div class="swiper-slide swiper-slide-mysterybox slide-list-box">
                            <a href="<?php echo base_url() ?>categories/?category=<?php echo $value->id; ?>">
                                <img class="img-thumbnail" src="<?php echo $value->feature_image; ?>">
                            </a>
                        </div>
                    <?php endif ?>
                <?php endforeach ?>
            </div>

            <div class="swiper-pagination swiper-pagination-mysterybox-card"></div>
        </div>
    </div>
<?php endif ?>



<h5 class="heading-padding-liquidation pl-4">Current Inventory</h5>
<div class="container   pl-4">

    <div class="swiper-container swiper-container-liquidation mySwiper mySwiper-liquidation" style="min-height: 250px;max-height: 250px">
        <div class="swiper-wrapper">

            <div class="swiper-slide swiper-slide-liquidation slide-list-box">
                <a href="<?php echo $liquidation_url ?>?type=1">
                    <img class="img-thumbnail" src="./uploads/shop_pallet.jpg">
                </a>
            </div>





            <div class="swiper-slide swiper-slide-liquidation slide-list-box">
                <a href="<?php echo $liquidation_url ?>?type=2">
                    <img class="img-thumbnail" src="./uploads/placeholder.jpg">

                </a>
            </div>




            <div class="swiper-slide swiper-slide-liquidation slide-list-box">
                <a href="<?php echo $liquidation_url ?>?type=3">
                    <img class="img-thumbnail" src="./uploads/truckload.jpg">
                </a>
            </div>

        </div>
        <div class="swiper-button-next list-swiper-button-next swiper-button-next-liquidation-card"></div>
        <div class="swiper-button-prev list-swiper-button-prev swiper-button-prev-liquidation-card"></div>
        <div class="swiper-pagination swiper-pagination-liquidation-card"></div>
    </div>
</div>