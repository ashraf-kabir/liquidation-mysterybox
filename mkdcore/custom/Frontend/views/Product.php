<style type="text/css">
    .mySlides img {
        max-height: 350px !important;
        object-fit: contain;
    }


    .numbertext {
        color: black !important; 
    }

    .quantity-to-cart{
        display: flex;
        flex-direction: row;
        align-items: center
    }
    

    .image-fit-in-div{
        width: 100%;
        min-height: 200px;
        max-height: 200px;
        max-height: 100vh;
        object-fit: cover;
        border: none !important;
    }

    .videos-thumbnail{
        max-height: 250px !important;
        min-height: 250px !important;
        width: 100% !important;
    }

    .play_icon{
        z-index: 100;
        position: absolute;
        width: 35%;
        top: 15%;
        left: 32%;
    }

    .img-thumbnail{
        padding: 0 !important;
    }

    .list-swiper-button-next, .list-swiper-button-prev{
        padding: 35px 19px !important;
    }

    .product-image-list-container{
        /* height: 70px; */
        /* width: 70px; */
    }

    .product-description{
        min-height: 345px;
        max-height: 345px;
        overflow-y: auto;
    }



    
    @media only screen and (min-width:2144px) {
           
        .play_icon{ 
            width: 18%;
            top: 40%;
            left: 39%;
        }  
    } 

    @media only screen and (max-width:2143px) {
           
        .play_icon{ 
            width: 18%;
            top: 40%;
            left: 39%;
        }  
    } 


    @media only screen and (max-width:1817px) {
           
        .play_icon{
            width: 18%;
            top: 36%;
            left: 38%;
        }  
    } 

    @media only screen and (max-width:1632px) {
           
        .play_icon{
            width: 23%;
            top: 37%;
            left: 37%;
        }  
    } 

 
 

   /* @media only screen and (min-width:1030px) {
           
        .play_icon{
            width: 35%;
            top: 31%;
            left: 32%;
        }  
    } */

    /*@media only screen and (min-width:992px) {
           
        .play_icon{
            width: 23%;
            top: 40%;
            left: 38%;
        }  
    } */

    @media only screen and (max-width:991px) {
           
        .play_icon{
            width: 14%;
            top: 41%;
            left: 40%;
        }  
        .col-991-p-0{
            padding: 0px !important;
        }
        .product_quantity{
            width: 35%;
        }
    } 
    @media only screen and (max-width:845px) {
           
        .play_icon{
            width: 14%;
            top: 41%;
            left: 40%;
        }  
    } 


    .video-container{
        position: relative;
    }

    @media only screen and (max-width:767px) {
           
        .add_margin_mobile{
            margin-left: 15px !important;
            margin-right: 15px !important;
        } 
        .product_quantity{
            width: 35%;
        }
        .remove_margin_mobile{
            padding: 0px !important;
            margin: 0px !important;
        }
        .padding-left-0-custom{
            padding-left: 15px !important;
        }
        .col-991-p-0{
            padding: 15px !important;
        }
        .product-gallery-container{
            margin-left: 15px !important;
            max-width: 95%;
        }
        
    } 

    @media (max-width:500px) {
        .product-gallery-container{
            margin-left: 15px !important;
            max-width: 93%;
        }
    }

    @media (max-width:400px) {
        .product-gallery-container{
            margin-left: 15px !important;
            max-width: 92%;
        }
    }


    .img-thumbnail{
        border: none !important;  
    }


    .myvideos-videos-list .swiper-button-disabled
    {
        display: none;
    }

    .gallery-thumbnail{
        max-height: 90px !important;
        min-height: 90px !important;
        width: 100% !important;
        object-fit: cover;
    }
    @media (max-width: 1300px){
        .gallery-thumbnail{
            max-height: 75px !important;
            min-height: 75px !important;
        }   
        .product-description{
            min-height: 331px;
            max-height: 331px;
        }
    }
    @media (max-width: 1100px){
        .gallery-thumbnail{
            max-height: 60px !important;
            min-height: 60px !important;
        }   
        .product-description{
            min-height: 317px;
            max-height: 317px;
        }
    }

    @media (max-width: 991px){
        .gallery-thumbnail{
            max-height: 75px !important;
            min-height: 75px !important;
        }   
        .product-description{
            min-height: auto;
            max-height: auto;
        }
    }

    @media (max-width: 768px){
        .gallery-thumbnail{
            max-height: 60px !important;
            min-height: 60px !important;
        }   
    }

    @media (max-width: 575px){
        .gallery-thumbnail{
            max-height: 80px !important;
            min-height: 80px !important;
        }   
    }
</style>
<?php 
 
$gallery_image_count = count($gallery_lists) + 1;


$feature_image = "/assets/frontend_images/noun_pallet_box_1675914.png";

if (!empty($product->feature_image)) 
{
    $feature_image = $product->feature_image;
}
 ?>
    <main class="container-fluid">
        <div class="row ">
            <div class="col-12 col-md-10 mt-4 padding-left-0-custom add_margin_mobile">
                <div class="product__pathLink"><a href="<?php echo base_url() ?>">Home ></a> 

                <?php if ($product->category_real_name != "N/A"): ?> 
                    <a href="<?php echo base_url(); ?>?category=<?php echo $product->category_id; ?>"><b style="color: black;"><?php echo $product->category_real_name; ?> > </b></a>
                <?php endif ?>
                


                <b style="color:black;"><?php echo $product->product_name; ?></b></div>
            </div>
        </div>
    </main>
    <div class="container-fluid">
        <div class="row " style="margin: 15px 0px;">
            <div class="col-lg-7 bg-white p-2 p-md-4 mt-4 add_margin_mobile flex-css">
                

                <div class="row "> 
                    <div class="col-12 col-sm-12">
                        <?php if(!empty($product->feature_image)){   ?>
                            <div class="mySlides">
                                <div class="numbertext">1 / <?php echo $gallery_image_count ?></div>
                                <img src="<?php echo $product->feature_image; ?>" class="img-thumbnail" style="width:100%" alt="<?php echo $product->product_name; ?>">
                            </div> 
                        <?php }else{ ?>
                            <div class="mySlides">
                                <div class="numbertext">1 / <?php echo $gallery_image_count ?></div>
                                <img  class="img-thumbnail"  src="/assets/frontend_images/noun_pallet_box_1675914.png" style="width:100%"  alt="<?php echo $product->product_name; ?>">
                            </div> 
                        <?php } ?> 
                        <?php if (!empty($gallery_lists)): $k =2; ?> 
                            <?php foreach ($gallery_lists as $key => $value): ?>
                                <div class="mySlides">
                                    <div class="numbertext"><?php echo $k++ ?> / <?php echo $gallery_image_count ?></div>
                                    <img  class="img-thumbnail"  src="<?php echo $value->image_name; ?>" style="width:100%" alt="<?php echo $product->product_name; ?>">
                                </div>
                            <?php endforeach ?> 
                        <?php endif ?> 
                        
                    </div>

                    <div class="col-12 col-sm-12 product-gallery-container" style="max-height: 350px; padding: 0px; overflow: auto;">
                        <div class="row " style="margin: 0px;"> 
                            <div class="product-image-list-box flex-css">
                                <div class="col-6 col-sm-2 testing"> 
                                    <div class="product-image-list-container pt-2 pb-2">
                                        <img style="border: none;" class="demo cursor gallery-thumbnail img-thumbnail" src="<?php echo $feature_image; ?>"   onclick="currentSlide(1)" alt="<?php echo $product->product_name; ?>">
                                    </div> 
                                </div>
                         

                            <?php if (!empty($gallery_lists)): $k= 2; ?> 
                                <?php foreach ($gallery_lists as $key => $value): ?>
                                <div class="col-6 col-sm-2 testing"> 
                                    <div class="product-image-list-container pt-2 pb-2">
                                        <img  style="border: none;" class="demo gallery-thumbnail cursor img-thumbnail" src="<?php echo $value->image_name; ?>"   onclick="currentSlide(<?php echo $k++; ?>)" alt="<?php echo $product->product_name; ?>" >
                                    </div>
                                </div>
                                <?php endforeach ?> 
                            <?php endif ?>  
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-5 col-991-p-0 mt-4 pr-md-0">
                <div class="bg-white w-100 p-2 px-md-4 "  style="padding-top: 0.5rem !important;    padding-bottom: 1px !important;"> 
                    <ul class="list-unstyled" style="margin: 0px;">

                        <li class="my-1"><h4  style="width: 100%;text-align: left;"><?php echo $product->product_name; ?></h4> </li>

                        <li class="my-1">Price: <span class="product__price">$<?php echo number_format($product->selling_price,2); ?></span></li>
 
                        
                    </ul> 
                </div>

                <?php  if ($product->quantity > 0)  { ?>
                    <div class="col-12 quantity-to-cart bg-white w-100 p-2 p-md-4 " style="padding-top: 0px !important;">
                        <label for="quantity"  style="margin-bottom:0px">Qty </label>
                             
                            <select type="number" name="quantity" id="quantity" class="form-control d-inline product_quantity mx-3"  style="font-size: 13px; padding: 0px; width: 75px; height: 38px;"> 
                                <option value="">Select</option>
                                <?php  
                                for ($i=1; $i <= $product->quantity; $i++) 
                                { 
                                    if($i <= 10)
                                    {
                                        echo '<option value="' . $i . '" >' . $i .'</option>';
                                    }
                                    
                                }
                                ?>
                                
                            </select> 

                        <input type="hidden" class="product_id" name="product_id" value="<?= $product->id; ?>" />
                        <a href="#" class="btn add_to_cart_button btn-success addToCartBtn" style="width: 100px;">
                        <i class="fas fa-shopping-cart cart-icon"></i>
                        </a>
                    </div>
                <?php }else{ ?>
                    <div class="col-12 bg-white w-100 p-2 p-md-4 "  style="padding-top: 0px !important;">
                        <label style="color: red;" for="quantity" ><strong>Out of stock</strong> 
                            <a href="" class="on_click_notification btn btn-primary" data-product-title="<?php echo $product->product_name; ?>"> Notify me when available</a>
                        </label>  
                    </div>
                <?php } ?> 

                <?php if (!empty($product->inventory_note)): ?>
                <div class="row mt-4">
                    <div class="col-12  ">
                        <div class="col-12 p-2 p-md-4 bg-white product-description">
                            <h4  style="width: 100%;text-align: left;">DESCRIPTION</h4>
                            <p class="my-3"><?php echo $product->inventory_note; ?></p> 
                             
                        </div> 
                    </div> 
                </div> 
                <?php endif ?>
                 
            </div>
        </div>
    </div>

    

    <section class="container-fluid mt-5 mb-5" id="product__description">
        
        <?php 
        $videos_data = json_decode($product->video_url);   
        $images_data = json_decode($product->youtube_thumbnail_1);  


        $jkl = 0;
        foreach ($videos_data as $key => $video)
        {
            if (!empty($video)) 
            {
                $jkl = 2;
            }
        }

        ?>
         
       
        <?php if ($jkl == 2): $count = count($videos_data)-1; ?> 
         
       

        <div class="row  bg-white   add_margin_mobile " style="margin: 15px 0px;">
            <div class="col-12 p-0 py-3 bg-white"  style="padding-bottom: 0px !important;" >
                <h4  class="pl-3 mb-1">Review Videos</h4> 
            </div>   
            <div class="col-12  pl-2 p-0 bg-white"> 
                <div class="row   p-3 " style="padding-top: 0px !important;">   


                    <div class="container">  
                        <div class="swiper-container swiper-container-liquidation mySwiper myvideos-videos-list"   >
                            <div class="swiper-wrapper">  

                                <?php  
                                if (!empty($videos_data)) 
                                {  
                                     
                                    foreach ($videos_data as $key => $video):

                                        if (!empty($videos_data[$count])) 
                                        {
                                            ?>
                                            <div class="swiper-slide swiper-slide-liquidation">

                                                <div class="video-container col-12 col-md-12 col-lg-12" style="padding: 20px 10px; "> 
                                                          
                                                    <a href="<?php echo $videos_data[$count]; ?>" target="_blank"> 
                                                        <img src="<?php echo base_url() ?>assets/image/play_circle.png" class="play_icon" />
                                                        <img  
                                                        style="border: none;"
                                                        class="img-thumbnail image-fit-in-div videos-thumbnail"

                                                         
                                                        src="<?php echo $images_data[$count]; ?>"
                                                         
                                                        /> 
                                                    </a> 
                                                </div> 

                                            </div> 

                                            <?php
                                        }  

                                        $count--; 
                                    endforeach ;
                                }
                                ?>   
                            </div>
                            <div class="swiper-button-next list-swiper-button-next swiper-button-next-videos-card"></div>
                            <div class="swiper-button-prev list-swiper-button-prev swiper-button-prev-videos-card"></div>
                            <div class="swiper-pagination swiper-pagination-videos-card"></div>
                        </div>
                    </div> 



                </div>
                
            </div> 
        </div>


        <?php endif ?>


      

        <?php if (!empty($terms_and_con)): ?> 
            <div class="row  bg-white add_margin_mobile" style="margin: 15px 0px;">
                <div class="col-12 p-0 py-3 bg-white">
                    <h4 class="pl-3 mb-3">Terms and Conditions</h4>
                    <ul  class="pr-5">
                        <?php foreach ($terms_and_con as $key => $value): ?>
                            <li><?php echo $value->description; ?></li>
                        <?php endforeach ?> 
                    </ul>
                </div> 
            </div>
        <?php endif ?>
    </section>
    
<script type="text/javascript" src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js" defer></script>
<script>
    var slideIndex = 1;
    showSlides(slideIndex);

    function plusSlides(n) {
    showSlides(slideIndex += n);
    }

    function currentSlide(n) {
    showSlides(slideIndex = n);
    }

    function showSlides(n) {
    var i;
    var slides = document.getElementsByClassName("mySlides");
    var dots = document.getElementsByClassName("demo");
    var captionText = document.getElementById("caption");
    if (n > slides.length) {slideIndex = 1}
    if (n < 1) {slideIndex = slides.length}
    for (i = 0; i < slides.length; i++) {
        slides[i].style.display = "none";
    }
    for (i = 0; i < dots.length; i++) {
        dots[i].className = dots[i].className.replace(" active", "");
    }
    slides[slideIndex-1].style.display = "block";
    dots[slideIndex-1].className += " active";
    captionText.innerHTML = dots[slideIndex-1].alt;
    }
</script>

 