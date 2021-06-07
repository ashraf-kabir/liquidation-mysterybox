<style type="text/css">
    .mySlides img {
        max-height: 350px !important;
        object-fit: contain;
    }

    .image-fit-in-div{
        width: 100%;
        min-height: 200px;
        max-height: 200px;
        max-height: 100vh;
        object-fit: cover;
        border: none !important;
    }

     
    .quantity-to-cart{
        display: flex;
        flex-direction: row;
        align-items: center
    }
    .numbertext{
        color: black !important;
    }

    @media only screen and (max-width:767px) {
           
        .add_margin_mobile{
            margin-left: 15px !important;
            margin-right: 15px !important;
        } 

        .remove_margin_mobile{
            padding: 0px !important;
            margin: 0px !important;
        }
        .padding-left-0-custom{
            padding-left: 15px !important;
        }
        
    } 

    .videos-thumbnail{
        max-height: 300px !important;
        width: 100% !important;
    }


    .slider-image-custom{
        width:100% !important;
        object-fit: contain;
        border: none !important;
    }

    .play_icon{
        z-index: 100;
        position: absolute;
        width: 35%;
        top: 15%;
        left: 32%;
    }


    @media only screen and (min-width:1632px) {
           
        .play_icon{
            width: 35%;
            top: 19%;
            left: 32%;
        }  
    } 

    @media only screen and (max-width:1631px) {
           
        .play_icon{
            width: 35%;
            top: 19%;
            left: 32%;
        }  
    } 

    @media only screen and (min-width:1030px) {
           
        .play_icon{
            width: 35%;
            top: 31%;
            left: 32%;
        }  
    } 

    @media only screen and (min-width:992px) {
           
        .play_icon{
            width: 23%;
            top: 40%;
            left: 32%;
        }  
    } 

    @media only screen and (max-width:991px) {
           
        .play_icon{
            width: 14%;
            top: 41%;
            left: 40%;
        }  
    } 
    @media only screen and (max-width:845px) {
           
        .play_icon{
            width: 14%;
            top: 36%;
            left: 40%;
        }  
    } 


    .video-container{
        position: relative;
    }
    

</style>
<?php 
$total_images =  count($gallery_lists) + 1; 
?>
    <main class="container-fluid">
        <div class="row "  style="margin: 15px 0px;" >
            <div style="padding-left: 0px" class="col-12 col-md-12 mt-4 padding-left-0-custom">
                <div class="product__pathLink"><a href="<?php echo base_url() ?>categories">Home ></a> 
                <a href="<?php echo base_url() ?>categories/?category=<?php echo $product->category_id;  ?>"><b  style="color: black;"><?php echo $product->category_real_name;  ?> > </b></a>
                <b style="color: black;"><?php echo $product->product_name; ?></b></div>
            </div>
        </div>
    </main>
    <main class="container-fluid">
        <div class="row  " style="margin: 15px 0px;" >
            <div class="col-lg-9 bg-white p-2 p-lg-4 mt-4 add_margin_mobile">
                 

                <div class="row">
                    <div class="col-2 col-sm-1 padding-left-0-custom" style="max-height: 500px; padding: 0px;">

                        <?php if(!empty($product->feature_image)){   ?>
                            <div class="column w-100">
                                <img class="img-thumbnail demo cursor slider-image-custom " src="<?php echo $product->feature_image; ?>"  onclick="currentSlide(1)" alt="<?php echo $product->product_name; ?>" >
                            </div> 
                        <?php }else{ ?>
                            <div class="column w-100">
                                <img class="img-thumbnail demo cursor slider-image-custom " src="/assets/frontend_images/noun_pallet_box_1675914.png"  onclick="currentSlide(1)" alt="<?php echo $product->product_name; ?>"  >
                            </div>  
                        <?php } ?> 


                        <?php if (!empty($gallery_lists)): $k= 2; ?> 
                            <?php foreach ($gallery_lists as $key => $value): ?>

                                <div class="column w-100">
                                    <img class="img-thumbnail demo cursor slider-image-custom " src="<?php echo $value->image_name; ?>"  onclick="currentSlide(<?php echo $k++; ?>)" alt="<?php echo $product->product_name; ?>" >
                                </div>
                                 
                            <?php endforeach ?> 
                        <?php endif ?>  
                       
                    </div>
                    <div class="col-10 col-sm-11">
                        <?php if(!empty($product->feature_image)){   ?>
                            <div class="mySlides">
                                <div class="numbertext">1 / <?php echo $total_images ?></div>
                                <img class="img-thumbnail" src="<?php echo $product->feature_image; ?>" style="width:100%" alt="<?php echo $product->product_name; ?>">
                            </div> 
                        <?php }else{ ?>
                            <div class="mySlides">
                                <div class="numbertext">1 / <?php echo $total_images ?></div>
                                <img class="img-thumbnail" src="/assets/frontend_images/noun_pallet_box_1675914.png" style="width:100%"  alt="<?php echo $product->product_name; ?>">
                            </div> 
                        <?php } ?> 
                        <?php if (!empty($gallery_lists)): ?> 
                            <?php foreach ($gallery_lists as $key => $value): ?>
                                <div class="mySlides">
                                    <div class="numbertext"><?php echo $key+2 ?> / <?php echo $total_images ?></div>
                                    <img class="img-thumbnail" src="<?php echo $value->image_name; ?>" style="width:100%" alt="<?php echo $product->product_name; ?>">
                                </div>
                            <?php endforeach ?> 
                        <?php endif ?> 
                        
                        <a class="prev" onclick="plusSlides(-1)">❮</a>
                        <a class="next" onclick="plusSlides(1)">❯</a> 
                    </div>
                </div> 
            </div>
            <div class="col-lg-3  mt-4 ">
                <div class="bg-white w-100 p-2 p-md-4" style="padding-bottom: 1px !important;"> 
                    <ul class="list-unstyled">
                        <li class="my-1"><span class="product__price"><?php echo $product->product_name; ?></span></li> 

                        <li class="my-1">Price:  <span class="product__price">$<?php echo number_format($product->selling_price,2); ?></span></li> 
                        <li class="my-1">SKU: <span class="product__price"> <?php echo $product->sku; ?></span></li>
  
                        <li class="my-1"><?php echo $home_page_setting->product_text_note; ?> </li>
                    </ul> 
                </div>

                <?php  if ($product->quantity > 0)  { ?>
                    <div class="col-12 quantity-to-cart bg-white w-100 p-2 p-md-4 " style="padding-top: 0px !important;">
                        <label for="quantity" >Qty </label>
                             
                            <select type="number" name="quantity" id="quantity" class="form-control d-inline product_quantity mx-3"  > 
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
                        <a href="#" class="btn add_to_cart_button btn-success addToCartBtn">
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
                        <div class="col-12 p-2 p-md-4 bg-white">
                            <h4  style="width: 100%;text-align: left;">DESCRIPTION</h4>
                            <p class="my-3"><?php echo $product->inventory_note; ?></p> 
                             
                        </div> 
                    </div> 
                </div> 
                <?php endif ?>
            </div>
        </div>
    </main>

    <section class="container-fluid mt-5 mb-5" id="product__description">
        
        <?php 
        $video_url = json_decode($product->video_url);  
        $total_videos = 0;
        if (!empty($video_url)) 
        {  
            foreach ($video_url as $key => $video)
            {
                if (!empty($video)) 
                {
                    $total_videos++;
                }
            }
        }
        ?>
        
        
        <?php if ($total_videos != 0 ): ?>
        <div class="row  bg-white   remove_margin_mobile " style="margin: 15px 0px;">  

              
            <div class="col-12 p-0 py-3 bg-white" style="padding-bottom: 0px !important;">
                <h4 class="pl-3">Review Videos</h4> 
            </div>  
            
            <div class="col-12 p-0 bg-white  pl-2" style="padding-top: 0px !important;"> 
                <div class="row  " style="margin: 0px 0px;">  
                    
                    <?php  
                    if (!empty($video_url)) 
                    {  
                        if ($total_videos == 1) 
                        {
                            $total_videos = 2;
                        }

                        if ($total_videos != 0 ) 
                        {
                            $total_percentage = 100/$total_videos;
                        }

                        

                        foreach ($video_url as $key => $video):
                            if (!empty($video)) 
                            {
                                ?>
                                <div class="video-container col-12 col-md-6 col-lg-4" style="padding: 20px 10px; "> 

                                    <img src="<?php echo base_url() ?>assets/image/play_circle.png" class="play_icon" />
                                    
                                    <a href="<?php echo $video; ?>" target="_blank"> 
                                        <img  

                                        class="img-thumbnail image-fit-in-div videos-thumbnail"

                                        <?php if ($key == 0): ?>
                                            src="<?php echo $product->youtube_thumbnail_1; ?>"
                                        <?php endif ?>
                                        

                                        <?php if ($key == 1): ?>
                                            src="<?php echo $product->youtube_thumbnail_2; ?>"
                                        <?php endif ?>


                                        <?php if ($key == 2): ?>
                                            src="<?php echo $product->youtube_thumbnail_3; ?>"
                                        <?php endif ?>


                                        <?php if ($key == 3): ?>
                                            src="<?php echo $product->youtube_thumbnail_4; ?>"
                                        <?php endif ?>
                                        /> 
                                    </a> 
                                </div> 

                                <?php
                            }   
                        endforeach ;
                    }
                    ?>   
                </div>
                
            </div> 
        </div>
        <?php endif ?>

        
        <div class="row  bg-white   remove_margin_mobile" style="margin: 15px 0px;">
            <div class="col-12 p-0 py-3 bg-white">
                <h4 class="pl-3">Terms and Conditions</h4>
                <ul  class="pr-5">
                    <li>publishing any Website material in any other media;</li>
                    <li>selling, sublicensing and/or otherwise commercializing any Website material;</li>
                    <li>publicly performing and/or showing any Website material;</li>
                    <li>using this Website in any way that is or may be damaging to this Website;</li>
                </ul>
            </div> 
        </div>
    </section>

<div class="modal on_click_notification_modal" tabindex="-1">
    <div class="modal-dialog">
        <form id="add_alert_notification">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title product__title_on_modal">Product name</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                
                        
                   
                    <p>Enter your email address and we will let you know when it's back.</p>

                    <input type="email" class="form-control" name="email" value=""  required placeholder="xyz@gmail.com" />
                    <input type="hidden" name="product_id" value="<?php echo $product->id ?>"   />
                    <input type="hidden" name="product_name" value="<?php echo $product->product_name ?>"    />
                    <input type="hidden" name="product_sku" value="<?php echo $product->sku ?>"    />
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary add_alert_notification">Add</button>
            </div>
        </div> 
        </form>
    </div>
</div>
 
    
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

