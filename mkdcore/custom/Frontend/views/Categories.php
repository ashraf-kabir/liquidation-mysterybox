<style type="text/css">
     .woocommerce-products-header h1 {
          font-weight: 500 !important;
          font-size: 18px !important;
          padding: 15px 10px !important;
     }
     .pt-categories{
          list-style-type: none;
          padding: 0;
          margin: 0;
     }
     .widget.widget_tz_categories ul li {
          padding: 5px 0;
          margin: 0;
     }
     .site-sidebar a, .woocommerce-MyAccount-navigation a {
          color: #626262;
     }
     .style-2 .widget .widget-title {
          padding-bottom: 1em;
     }
     .widget-title {
          border-color: #ebebeb;
          font-size: 1em;
          font-weight: 500;
          margin: 0 0 1.5em 0;
          text-transform: uppercase;
          border-style: solid;
          border-width: 0 0 1px 0;
     }
     .widget_tz_categories{
          background-color: #fff;
          padding: 1.5em 20px;
          margin: 0;
          font-size: 0.933em;
     }
     .product__pathLink a {
          padding: 0px 11px;
     }
     .image-fit-in-div{
          width: 100%;
          min-height: 200px;
          max-height: 200px;
          object-fit: contain;
     }

     @media only screen and (max-width: 767px) {
           
          #tz_categories-2{
               width: 100% !important;
               margin-bottom: 26px;
          }
     } 

     .active_category{
        background: #F6F6F6 !important;
        padding: 7px 10px !important;
     }

     .widget.widget_tz_categories ul li{
        padding: 5px 11px !important;
     }
    
</style> 
     <div class="container-fluid  " style="min-height:650px"> 
          <div class="row">
               <div class="col-md-12 my-4 col-sm-12 product__pathLink">
                    <nav class="woocommerce-breadcrumb"><a href="<?php echo base_url(); ?>">Home</a><span><i class="fa fa-angle-right" aria-hidden="true"></i></span>

                         <?php if (isset($category->name)): ?>
                              <a ><b  style="color: black;"><?php echo $category->name ?></b></a><span> 
                         <?php else: ?>
                              <a >Shop</a><span> 
                         <?php endif; ?>
                         

                         </i> </span><?php if( isset($_GET['search_term']) AND !empty($_GET['search_term']) ){ ?>  Search results for “<?php echo $_GET['search_term']; ?>” <?php } ?> 
                    </nav>
               </div>
          </div>
          
          <div class="row">
               <div class="col-md-3">  
                    <aside id="sidebar-shop" class="widget-area site-sidebar style-2" role="complementary">
                        <section  id="tz_categories-2" style="width: 100%;" class="widget widget_tz_categories"><h3 style="margin: 0px;" class="widget-title" itemprop="name"><span>Mystery Box</span></h3>

                              <ul class="pt-categories">
                                   <li class="cat-item cat-item-116">
                                        <a href="<?php echo base_url(); ?>categories">All</a>  
                                   </li>
                                   <?php if( !empty($all_categories) ){ ?>
                                        <?php foreach($all_categories as $key => $value){ 
                                             if (!empty($value->parent_category_id) ) 
                                             {
                                             ?>  
                                             <li class="cat-item cat-item-116 

                                             <?php if (isset($_GET['category']) and $_GET['category'] == $value->id): ?> active_category <?php endif; ?>    


                                             "><a href="<?php echo base_url(); ?>categories/?category=<?php echo $value->id; ?>"><?php echo $value->name; ?></a> 
                                             </li>
                                        <?php } } ?>
                                   <?php } ?>            
                              </ul>
                        </section>        
                    </aside>  

                   <!--  <aside id="sidebar-shop" class="widget-area site-sidebar style-2" role="complementary">
                        <section  id="tz_categories-2" class="widget widget_tz_categories"><h3 class="widget-title" itemprop="name"><span>Type</span></h3>

                              <ul class="pt-categories"> 
                                   <li class="cat-item cat-item-116"><a href="<?php echo base_url(); ?>categories/?type=1">Regular</a></li>
                                   <li class="cat-item cat-item-116"><a href="<?php echo base_url(); ?>categories/?type=2">Generic</a></li>  
                              </ul>
                        </section>        
                    </aside>     -->        
               </div>

               <div class="col-md-9"> 
                    <?php if( isset($_GET['search_term']) AND !empty($_GET['search_term']) ){ ?> 
                         <div class="row">
                              <div class="col-sm-12 col-md-12"> 
                                   <main class="site-content store-content" itemscope="itemscope" style="margin-bottom: 1.5rem;" itemprop="mainContentOfPage" role="main"><!-- Main content -->
                                   <header class="woocommerce-products-header">
                                        <h1 class="woocommerce-products-header__title page-title">Search results: “<?php echo $_GET['search_term']; ?>”</h1>
                                   </header>
                              </div>
                         </div>
                    <?php } ?>     

                    <div class="row">
                    <?php  if( !empty($products_list) ){  ?>                    
                         <?php foreach($products_list as $key => $value){ ?> 
                              <div class="col-12 col-lg-4 col-md-4 slick-current slick-center" >
                                   <div class="offer__item">
                                        <a href="<?php echo base_url(); ?>product/<?php echo $value->id; ?>" tabindex="-1"> 
                                             <?php if(!empty($value->feature_image)){   ?>
                                                  <img  src="<?php echo $value->feature_image; ?>" class="w-100 img-thumbnail image-fit-in-div" alt="<?php echo $value->product_name; ?>">
                                             <?php }else{ ?>
                                                  <img   src="/assets/frontend_images/noun_pallet_box_1675914.png" class="w-100 image-fit-in-div img-thumbnail" alt="<?php echo $value->product_name; ?>">     
                                             <?php } ?>
                                             <!-- <h5 class="text-gray mt-2"><?php if($value->product_type == 1 ) { echo "Regular"; } elseif($value->product_type == 2 ){ echo "Generic"; } ?> </h5> -->
                                             <h5 class="offer__itemTitle mt-2"><?php echo $value->product_name; ?></h5>
                                             <h5>Price : $<?php echo number_format($value->selling_price,2); ?></h5>
                                        </a>
                                   </div>
                              </div>
                         <?php } ?> 
                    <?php }else { ?>
                         <div class="col-12 col-lg-12 col-md-12 slick-current slick-center" >
                              <p class="alert alert-info">No Products Found.</p>
                         </div>
                    <?php } ?> 

                         <div class="col-12 col-lg-12 col-md-12 slick-current slick-center">
                              <?php echo $this->pagination->create_links(); ?>
                         </div>
                    </div> 
               </div>
          </div>
     </div>
