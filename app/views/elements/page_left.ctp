<?php
$categories = isset($CATEGORY_HIERACHY) ? $CATEGORY_HIERACHY : array();
$showAccount = isset($isMyAccount) ? true : false;
?>

<?php if ($showAccount === true) { ?>
<div class="block block-account first">
    <div class="block-title">
        <strong><span>My Account</span></strong>
    	<span class="toggle"></span>
    </div>
    <div class="block-content">
    	<ul>
        	<li class="current"><a href="/account">Account Dashboard</a></li>
            <li><a href="/account/edit">Account Information</a></li>
            <li><a href="/account/address">Address Book</a></li>
            <li><a href="/order/history/">My Orders</a></li>
           <!-- <li><a href="http://127.0.0.1/magento/index.php/sales/billing_agreement/">Billing Agreements</a></li>
            <li><a href="http://127.0.0.1/magento/index.php/sales/recurring_profile/">Recurring Profiles</a></li>
            <li><a href="http://127.0.0.1/magento/index.php/review/customer/">My Product Reviews</a></li>
            <li><a href="http://127.0.0.1/magento/index.php/tag/customer/">My Tags</a></li>-->
            <li class="last"><a href="http://127.0.0.1/magento/index.php/wishlist/">My Wishlist</a></li>
            <!--<li><a href="http://127.0.0.1/magento/index.php/oauth/customer_token/">My Applications</a></li>
            <li><a href="http://127.0.0.1/magento/index.php/newsletter/manage/">Newsletter Subscriptions</a></li>
            <li class="last"><a href="http://127.0.0.1/magento/index.php/downloadable/customer/products/">My Downloadable Products</a></li>-->
		</ul>
    </div>
</div>
<?php } ?>

<div class="nav-container">
    <!-- <div class="nav" style="width: ;"> -->
    <div class="nav">
        <div class="block-title"><strong>Categories</strong></div>
        <ul id="nav" class="grid-full"> 
		<?php
            $level = 0;
            $i = 1; 
            foreach($categories as $cat) {
                $counts = 0;
                if (isset($cat['children'])) $counts = count($cat['children']);
                $class = $counts > 0 ? 'parent' : '';
                if ($i == 1) $class = 'first ' . $class;
        ?>                   	
            <li class="level nav-<?=$i . ' ' . $class;?> no-level-thumbnail">
                <a class="" href="/category/<?=$cat['category_alias'];?>">
                    <div class="thumbnail"></div>
                    <span><?=$cat['name'];?></span>
                </a>
                <?=$utility->showSubCategories($cat, $i);?>
             </li>
        <?php
				$i ++;
            }
        ?>
        	<li></li><!--border line-->
    	</ul>
    </div>
</div> <!-- end: nav-container -->

<div class="widget-products  sale-products" style="display:none;">
	<div class="page-title category-title">
		<h1>Special products</h1>
	</div>
	<div style="display: block; text-align: left; float: none; position: relative; top: 0px; right: 0px; bottom: 0px; left: 0px; z-index: auto; width: 270px; height: 378px; margin: 0px 0px 10px; overflow: hidden;" class="caroufredsel_wrapper">
		<div class="caroufredsel_wrapper" style="display: block; text-align: left; float: none; position: absolute; top: 0px; right: 0px; bottom: -1008px; left: 0px; z-index: auto; width: 270px; height: 389px; margin: 0px; overflow: hidden;">
			<ul style="text-align: left; float: none; position: absolute; top: 0px; right: auto; bottom: auto; left: 0px; margin: 0px; height: 1413px; width: 270px; z-index: auto;" class="products-grid homeSider1">
				<li class="item free odd">
                	<div class="wrapper-hover">
						<a href="index.php/logo-band-ring.html" title="Logo Band Ring " class="product-image noSwipe">
							<img src="" alt="Logo Band Ring ">
						</a>
	                	<div class="product-shop">
	                   		<div class="wrap-extra">
	                        	<h2 class="product-name">
	                            	<a href="index.php/logo-band-ring.html" title="Logo Band Ring ">
	                                	Logo Band Ring                                     
	                                </a>
	                        	</h2>
								<div class="price-box">
	                            	<p class="old-price">
	        							<span class="price-label">Regular Price:</span>
	        							<span class="price">$230.00</span>
	        						</p>
	
	                    			<p class="special-price">
	            						<span class="price-label">Special Price</span>
	        							<span class="price">$200.00</span>
	        						</p>
	            				</div>
	
	                    	</div>
	                	</div>
						<div class="label-product"> 
							<span class="sale">Sale</span>                        
						</div>
					</div>
				</li>
				
				<li class="item free last even">
                    <div class="wrapper-hover">
                        <a href="" title="HRB-701FF-SS French Door" class="product-image noSwipe">
                            <img src="" alt="HRB-701FF-SS French Door">
                        </a>
                        <div class="product-shop">
                       		<div class="wrap-extra">
								<h2 class="product-name">
									<a href="" title="HRB-701FF-SS French Door">
                                        HRB-701FF-SS French ...                                    
                                  	</a>
                                </h2>
                               	
                               	<div class="price-box">
									<p class="old-price">
                						<span class="price-label">Regular Price:</span>
                						<span class="price">$1,200.00</span>
            						</p>
									
									<p class="special-price">
										<span class="price-label">Special Price</span>
										<span class="price">
											$1,100.00
										</span>
                					</p>
								</div>
							</div>
                        </div>
						<div class="label-product"></div>
                        
                    </div>
                </li>
			</ul>
		</div>
	</div>
	<div class="home-carousel-control">
		<a style="display: block;" class="carousel-prev1 fa fa-angle-up" href="#"></a>
		<a style="display: block;" class="carousel-next1 fa fa-angle-down" href="#"></a>
	</div> 
</div>