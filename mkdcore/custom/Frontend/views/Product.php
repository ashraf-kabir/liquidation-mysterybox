<style type="text/css">
    .mySlides img {
        max-height: 350px !important;
        object-fit: contain;
    }

    .image-fit-in-div{
        width: 100%;
        min-height: 200px;
        max-height: 200px;
        object-fit: cover;
    }

    .slider_img_css{
        width: 100%;
        object-fit: contain;
        height: 200px;
    }
</style>
<?php 
$total_images =  count($gallery_lists) + 1; 
?>
    <main class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12 col-md-10 my-4">
                <div class="product__pathLink"><a href="<?php echo base_url() ?>categories">Home ></a> 
                <!-- <a href="">Truckloads > </a> -->
                <span><?php echo $product->product_name; ?></span></div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-7 bg-white p-2 p-md-4">
                <h2 class="product__title"><?php echo $product->product_name; ?></h2> 

                <div class="row">
                    <div class="col-4">

                        <?php if(!empty($product->feature_image)){   ?>
                            <div class="column w-75">
                                <img class="demo cursor slider_img_css" src="<?php echo $product->feature_image; ?>" style="width:100%; object-fit: contain;" onclick="currentSlide(1)" alt="<?php echo $product->product_name; ?>" >
                            </div> 
                        <?php }else{ ?>
                            <div class="column w-75">
                                <img class="demo cursor slider_img_css" src="/assets/frontend_images/noun_pallet_box_1675914.png" style="width:100%;object-fit: contain;" onclick="currentSlide(1)" alt="<?php echo $product->product_name; ?>"  >
                            </div>  
                        <?php } ?> 


                        <?php if (!empty($gallery_lists)): $k= 2; ?> 
                            <?php foreach ($gallery_lists as $key => $value): ?>

                                <div class="column w-75">
                                    <img class="demo cursor slider_img_css" src="<?php echo $value->image_name; ?>" style="width:100%" onclick="currentSlide(<?php echo $k++; ?>)" alt="<?php echo $product->product_name; ?>" >
                                </div>
                                 
                            <?php endforeach ?> 
                        <?php endif ?>  
                       
                    </div>
                    <div class="col-8">
                        <?php if(!empty($product->feature_image)){   ?>
                            <div class="mySlides">
                                <div class="numbertext">1 / <?php echo $total_images ?></div>
                                <img src="<?php echo $product->feature_image; ?>" style="width:100%" alt="<?php echo $product->product_name; ?>">
                            </div> 
                        <?php }else{ ?>
                            <div class="mySlides">
                                <div class="numbertext">1 / <?php echo $total_images ?></div>
                                <img src="/assets/frontend_images/noun_pallet_box_1675914.png" style="width:100%"  alt="<?php echo $product->product_name; ?>">
                            </div> 
                        <?php } ?> 
                        <?php if (!empty($gallery_lists)): ?> 
                            <?php foreach ($gallery_lists as $key => $value): ?>
                                <div class="mySlides">
                                    <div class="numbertext"><?php echo $key+2 ?> / <?php echo $total_images ?></div>
                                    <img src="<?php echo $value->image_name; ?>" style="width:100%" alt="<?php echo $product->product_name; ?>">
                                </div>
                            <?php endforeach ?> 
                        <?php endif ?> 
                        
                        <a class="prev" onclick="plusSlides(-1)">❮</a>
                        <a class="next" onclick="plusSlides(1)">❯</a>
                    
                        <div class="caption-container" style="padding: 11px 0px;">
                            <p id="caption" style=" margin: 0px;"></p>
                        </div>
                    </div>
                </div> 
            </div>
            <div class="col-md-3 ">
                <div class="bg-white w-100 p-2 p-md-4"> 
                    <ul class="list-unstyled">
                        <li class="my-4">Price <span class="product__price">$<?php echo number_format($product->selling_price,2); ?></span></li> 
                         
                    </ul> 
                </div>

                <?php  if ($product->quantity > 0)  { ?>
                    <div class="col-12 bg-white w-100 p-2 p-md-4 ">
                        <label for="quantity" >Qty 
                            <select type="number" name="quantity" id="quantity" class="form-control d-inline product_quantity w-75 w-md-50"  > 
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
                        </label> 

                        <input type="hidden" class="product_id" name="product_id" value="<?= $product->id; ?>" />
                        <button style="width: 100%" class="btn add_to_cart_button btn-success addToCartBtn">Add To Cart</button>
                    </div>
                <?php }else{ ?>
                    <div class="col-12 bg-white w-100 p-2 p-md-4 ">
                        <label style="color: red;" for="quantity" ><strong>Out of stock</strong> 
                            <a href="" class="on_click_notification btn btn-primary" data-product-title="<?php echo $product->product_name; ?>"> Notify me when available</a>
                        </label>  
                    </div>
                <?php } ?> 

                <div class="row mt-4">
                    <div class="col-12  ">
                        <div class="col-12 p-2 p-md-4 bg-white">
                            <h4  style="width: 100%;text-align: left;">DESCRIPTION</h4>
                            <p class="my-3"><?php echo $product->inventory_note; ?></p> 
                             
                        </div> 
                    </div> 
                </div>
            </div>
        </div>
    </main>

    <section class="container-fluid mt-5 px-md-5 my-5" id="product__description">
        
        
        <div class="row justify-content-center bg-white mx-md-5 ">
            <div class="col-10 p-0 py-3 bg-white">
                <h4 style="text-align: center;">Review Videos</h4> 
            </div> 
        </div>

        <div class="row justify-content-center bg-white mx-md-5 ">  
            <div class="col-10 p-0 bg-white"> 
                <div class="row justify-content-between">  
                    
                    <?php 
                    $video_url = json_decode($product->video_url);  
                    if (!empty($video_url)) 
                    { 
                        $total_videos = 0;
                        foreach ($video_url as $key => $video)
                        {
                            if (!empty($video)) 
                            {
                                $total_videos++;
                            }
                        }

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
                                <div class="video-container" style="padding: 20px 10px;width: <?php echo $total_percentage; ?>%"> 
                                    <a href="<?php echo $video; ?>" target="_blank"> 
                                        <img  

                                        class="img-thumbnail image-fit-in-div"

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

        <div class="row justify-content-center bg-white mx-md-5 ">
            <div class="col-10 p-0 py-3 bg-white">
                <h4>Terms and Conditions</h4>
                <ul>
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

