<?php
$returns = $utility->breadcrumbs($categories, '', $product['Product']['name']);
$breadcrumbs = $returns['breadcrumbs'];

$this->set('page_title', $product['Product']['name']);
$this->set('page_breadcrumbs', $breadcrumbs);

$arrMedias = array();
if (isset($product['Media'])) { 
	$arrMedias = $utility->getFrontEndImageInfo($product['Media'], PRODUCT_IMAGE_URL_ROOT, 
							$product['Product']['id'], $product['Product']['product_alias']);
}

$fistImgSrc = $orgImgSrc = '';
$slideImgs = $swipeImgs = '';
foreach($arrMedias as $key => $m) { 
	$imgBase = $m['img_base'] . $m['img_delimiter'];
	if ($key === 0) {
		$fistImgSrc = $imgBase . 'medium.' . $m['img_ext'];
		$orgImgSrc = $imgBase . 'origin.' . $m['img_ext'];
	}
	
    $slideImgs .= "<li>
						<a href='" . $imgBase . 'origin.' . $m['img_ext'] . "' class='cloud-zoom-gallery' title=''
								rel=\"useZoom: 'zoom1', smallImage: '" . $imgBase . 'medium.' . $m['img_ext'] . "'\">
							<img src='" . $imgBase . 'xsmall.' . $m['img_ext'] . "' alt='' />
						</a>
					</li>";
	
	$swipeImgs .= "<li>
						<a href='" . $imgBase . 'large.' . $m['img_ext'] . "' title=''>
                    		<img src='" . $imgBase . 'large.' . $m['img_ext'] . "' alt='' />
                    	</a>
				   </li>"; 
}						
?>

<div id="messages_product_view"></div>
<div class="product-view">
	<div class="product-essential">
    	<form action="/carts/ajaxAdd" method="post" id="product_addtocart_form" name="product_addtocart_form" enctype="multipart/form-data">
        	<div class="no-display">
            	<input type="hidden" name="product" value="<?=$product['Product']['serial_no'];?>" />
            	<input type="hidden" name="related_product" id="related-products-field" value="" />
        	</div>
        	<div class="product-img-box">
        		<div class="product-box-customs">
        			<p class="product-image">
        				<a  href='<?=$orgImgSrc;?>' class='cloud-zoom' id='zoom1' rel="position:'right',showTitle:1,titleOpacity:0.5,lensOpacity:0.5,adjustX: 10,adjustY:-4">
							<img class="big" src="<?=$fistImgSrc;?>" alt='' title="<?=$product['Product']['name'];?>" />
        				</a>
    				</p>
    				
    				<div class="more-views">
        				<h2>More Views</h2>
        				<div class="container-slider">
            				<ul class="slider tumbSlider-none" >
            					<?=$slideImgs;?>
                        	</ul>
                    	</div>
    				</div>
    				
        			<div class="gallery-swipe-content">
            			<ul id="gallery-swipe" class="gallery-swipe">
            				<?=$swipeImgs;?>
                   		</ul>
        			</div>
        			
    			</div><!--product-box-customs-->
    			
        	</div><!--product-img-box-->
        	
        	<div class="product-shop">
        		<div class="product-name">
                	<h1 itemprop="name"><?=$product['Product']['name'];?></h1>
            	</div>
                <p class="availability in-stock">Availability: <span>In stock</span></p>
    			<p class="availability-only">
        			<span title="Only 999999 left">Only <strong><?=$product['Product']['stock'];?></strong> left</span>
    			</p>

				<div class="price-box">
				<?php if (isset($product['Product']['price']) && !empty($product['Product']['price'])) { ?>
					<p class="old-price"><span class="price">
						$<?=number_format($product['Product']['price'], 2);?>
					</span></p>
					<p class="special-price"><span class="price">
						$<?=number_format($product['Product']['deal_price'], 2);?>
					</span></p>
				<?php } else { ?>
					<span class="regular-price">
						<span class="price">$<?=number_format($product['Product']['deal_price'], 2);?></span>
					</span>
				<?php } ?>
				</div>
				<div class="clear"></div>
                
                <div class="short-description">
					<h2>Quick Overview</h2>
                    <div class="std" itemprop="description">
                    	<?=$product['Product']['long_desc'];?>
                    </div>
                </div>
				<div class="clear"></div>
        
        		<div class="add-to-box">
        			<div class="add-to-cart">
        				<div class="qty-block">
        					<div class="qty-control">
        						<label for="qty">Qty:</label>
        						<input type="text" name="qty" maxlength="2" value="1" title="Qty" class="input-text qty form-control" />
        						<div></div>
        					</div>
        				</div>
        				<button type="button" title="Add to Cart" class="button btn-cart" id="btn_test" onclick="productAddToCartForm.submit(this)">
        					<span><span>Add to Cart</span></span>
        				</button>	
        			</div>
        			<span class="or">OR</span>
        			<ul class="add-to-links">
        				<li><a href="" class="link-wishlist">Add to Wishlist</a></li>
        			</ul>
        		</div>
        		
        		<div class="row-product" style="display:none;">
        			<p class="no-rating"><a href="">Be the first to review this product</a></p>
        			<p class="email-friend"><a href="">Email to a Friend</a></p>
        		</div>
        		
        		<div class="addthis_toolbox addthis_default_style addthis_32x32_style" style="display:none;">
					<a href="#" title="Facebook" class="addthis_button_facebook addthis_button_preferred_1 at300b">
						<span style="background-color: rgb(48, 88, 145);" class="at4-icon-left at4-icon aticon-facebook">
							<span class="at_a11y">Share on facebook</span>
						</span>
					</a>
					<a href="#" title="Tweet" class="addthis_button_twitter addthis_button_preferred_2 at300b">
						<span style="background-color: rgb(44, 168, 210);" class="at4-icon-left at4-icon aticon-twitter">
							<span class="at_a11y">Share on twitter</span>
						</span>
					</a>
					<a href="#" title="Email" target="_blank" class="addthis_button_email addthis_button_preferred_3 at300b">
						<span style="background-color: rgb(115, 138, 141);" class="at4-icon-left at4-icon aticon-email">
							<span class="at_a11y">Share on email</span>
						</span>
					</a>
					<a href="#" title="Print" class="addthis_button_print addthis_button_preferred_4 at300b">
						<span style="background-color: rgb(115, 138, 141);" class="at4-icon-left at4-icon aticon-print">
							<span class="at_a11y">Share on print</span>
						</span>
					</a>
					<a href="#" class="addthis_button_compact at300m">
						<span style="background-color: rgb(252, 109, 76);" class="at4-icon-left at4-icon aticon-compact">
							<span class="at_a11y">More Sharing Services</span>
						</span>
					</a>
					<a href="#" class="addthis_button_compact at300m">
						<span style="background-color: rgb(252, 109, 76);" class="at4-icon-left at4-icon aticon-compact">
							<span class="at_a11y">More Sharing Services</span>
						</span>
					</a>
				</div>  
			
			</div>
			<div class="clear"></div>
			
		</form>
		
	</div> <!--product-essential-->
	
    <div class="product-collateral">
		<div class="box-collateral box-description">
			<h2>Details<span class="toggle"></span></h2>
			<div class="box-collateral-content">
				<div class="std"><?=$product['Product']['long_desc'];?></div>
			</div>
		</div>
		<div class="box-collateral video-box">
			<h2>Video<span class="toggle"></span></h2>  
			<div class="box-collateral-content">
				<div class="video">
					<iframe src="http://www.youtube.com/embed/wAachrYkS0Y?wmode=opaque" frameborder="0" allowfullscreen=""></iframe>     
				</div>
			</div>
		</div>
	</div>
	
</div><!--product-view-->
    		

<?php
	$prod_desc = $prod_feature = $prod_video = $prod_doc = "";
	if (isset($product['Product']['long_desc']) && !empty($product['Product']['long_desc'])) {
		$prod_desc = $product['Product']['long_desc'];
?>
<?php
	}
	if (isset($product['Feature']) && count($product['Feature']) > 0) {
		$prod_feature = "<ul>";
		foreach($product['Feature'] as $feature) {
			$prod_feature .= "<li>" . $feature['feature'] . "</li>";
		}
		$prod_feature .= "</ul>";
?>
<?php
	}
	if (isset($product['Media']) && count($product['Media']) > 0) {
		$prod_video = $product['Media'][0]['scripts'];
?>
<?php
	}
	if (isset($product['Document']) && count($product['Document']) > 0) {
		$prod_doc = "<ul>";
		$fileRoot = '/files/product/' . $product['Product']['id'] . '/';
		foreach($product['Document'] as $doc) {
			$docUrl = $fileRoot . $doc['file_name'];
			$docIcon = 'document_icon_' . substr($doc['extension'], 1);
			$prod_doc .= '<li>' 
								.	 '	<a href="' . $docUrl . '" target="doc_view">'
								.	 '	<div class="document_item_url ' . $docIcon . '">' . $doc['file_name'] . '</div></a>'
								.	 ' 	<div class="document_item_size">' . $doc['file_size'] . ' KB' . '</div>'
								.  '</li>';
		}
		$prod_doc .= "</ul>";
?>
<?php
	}
?>

	