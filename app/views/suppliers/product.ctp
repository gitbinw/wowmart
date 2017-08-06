<?php
$this->set('page_title', $product['Product']['name']);

$select_id = 'subitems_list';
$extra_combo = '';
$prod_tabs = '<div id="tb_produts" class="tab_element tab_current">Description</div>' .
             '<div id="tb_story" class="tab_element">Delivery Fees</div>';
$prod_tab_views = '<div id="tb_produts_cnt" class="tab_cnt">' . 
						$product['Product']['long_desc'] . 
				   '</div>' . 
                   '<div id="tb_story_cnt" class="tab_cnt">' . 
                   '<h3>We delivery items by Post Office. The following is the delivery fee list:</h3>
				    <p>
						<table width=100%>
							<tr>
								<th>Weight</th><th>Delivery Fee</th>
							</tr>
					';
if ($product['Product']['pack_type'] == DELIVERY_TYPE_LETTER) {
	$prod_tab_views .= '<tr><td>Up to 250g</td><td>$1.80</td></tr>' . 
					   '<tr><td>250g to 500g</td><td>$3.00</td></tr>';
} else {
	$prod_tab_views .= '<tr><td>Up to 250g</td><td>$4.55</td></tr>' . 
					   '<tr><td>250g to 500g</td><td>$5.80</td></tr>';
}
	$prod_tab_views .= '<tr><td>500g to 1kg</td><td>$10.50</td></tr>' . 
					   '<tr><td>1kg to 2kg</td><td>$12.80</td></tr>' . 
					   '<tr><td>2kg to 3kg</td><td>$14.35</td></tr>' . 
					   '<tr><td>3kg to 4kg</td><td>$15.85</td></tr>' .
					   '<tr><td>4kg to 5kg</td><td>$17.35</td></tr>' .
					   '<tr><td>More than 5kg</td><td>Add $1 per KG</td></tr>' .
					   '</table>
					</p>
				   ' .     
                   '</div>';
						
if (isset($product['Subproduct']) && count($product['Subproduct']) > 0) {			   
	$extra_combo = "<div>" . 
   				   "<span>" . $product['Product']['subitems_title'] . "</span>" .
   				   "<select name='" . $select_id . "' id='" . $select_id . 
				   "' class='subitems_select'>";
				   
	foreach($product['Subproduct'] as $item) { 
   		$default_selected = '';
   		if($item['is_default'] == 1) {
   			$default_selected = 'selected=1';
   			$default_selected_name = $item['name'];
			$prod_current_price = $item['price'];
			$prod_subitem_id = $item['id'];
   		}
		$item_val = json_encode($item);
		$extra_combo .= "<option id='opt_" . $item['id'] . "' value='" . 
						$item_val . "' " . $default_selected . ">" . 
   						$item['name'] . "</option>";
	} 
	
	$extra_combo .= "</select></div>";
}
 
$logo = $photo = "";
foreach($thisItem['Image'] as $img) {
	if ($img['image_type'] == IMAGE_SUPPLIER_LOGO) {
		$logo = '/img/images/supplier/' . $img['id'] . 
						'/' . $img['id'] . 'a1' . $img['extension'];
	} else if ($img['image_type'] == IMAGE_SUPPLIER_PHOTO) {
		$photo = '/img/images/supplier/' . $img['id'] . 
						'/' . $img['id'] . 'a1' . $img['extension'];
	}
}

$default_img = $img_list = '';
if (isset($product['Image']) && count($product['Image']) > 0) {
	$img_src_root = '/img/images/product';	
	$default_img_src = '';
	foreach($product['Image'] as $key => $img) {
		$currentClass = '';
		if ($img['is_default'] == 1) {
			$default_img_src = $img_src_root . '/' . $img['id'] . '/' . $img['id'] . 'a2' . $img['extension'];
			$currentClass = 'current_img';
		} 
		$img_src = $img_src_root . '/' . $img['id'] . '/' . $img['id'] . 'a4' . $img['extension'];
		$img_list .= '<div class="slider_item ' . $currentClass . '">' . 	
					 '	<div class="prod_img_frame">' . 
					 '		<div class="prod_img"><img src="' . $img_src . '" border="0" width="60" height="45"/></div>' .
					 '	</div>' .
					 '</div>';
	}
	if (empty($default_img_src)) {
		$default_img_src = $img_src_root . '/' . $product['Image'][0]['id'] . '/' . 
							$product['Image'][0]['id'] . 'a4' . $product['Image'][0]['extension'];
	}
	
	$default_img = '<img src="' . $default_img_src . '" border="0" width="456" height="342" />';
} else {
	$default_img = '<img src="/img/home/noimage_big.gif" border="0" width="456" height="342" />';
	$img_list .= '<div class="slider_item ' . $currentClass . '">' . 	
				 '	<div class="prod_img_frame">' . 
				 '		<div class="prod_img"><img src="/img/home/noimage_medium.gif" border="0" width="60" height="45"/></div>' .
				 '	</div>' .
				 '</div>';
}

$prod_subitem_id = '';
$prod_current_price = $product['Product']['deal_price'];

/*more products from the supplier*/
$product_list = "";
$subdomain = 'http://' . $thisItem['Supplier']['subdomain'] . "." . SITE_DOMAIN;

if (isset($thisItem['Product']) && is_array($thisItem['Product'])) {
	$product_list = "<div class='oneline first_line'>";
	foreach($thisItem['Product'] as $key => $prod) {
		$img_src = '/img/home/noimage_medium.gif';
		if (isset($prod['Image'][0]['id'])) {
			$img_src = '/img/images/product/' . $prod['Image'][0]['id'] . 
							 '/' . $prod['Image'][0]['id'] . 'a3' . $prod['Image'][0]['extension'];
		}
		$oneitem_class = "";
		$mod = $key % 4;
		if ($mod==0) {
			$oneitem_class = "first_item";
			if ($key!=0) {
				$product_list .= "</div><div class='oneline'>";
			}
		}
		$product_list .= '<div class="oneitem ' . $oneitem_class . '">'
									.	 '<a title="' . $prod['name'] 
											. '" alt="' . $prod['name'] 
											. '" href="' . $subdomain . "/" . $prod['product_alias'] . '">'
									.	 '<img width="140" height="105" src="'  
											. $img_src . '" border="0" />'
									.	 '</a>'
									.	 '<h3><a title="' . $prod['name'] 
											. '" alt="' . $prod['name'] 
											. '" href="' . $subdomain . "/" . $prod['product_alias'] . '">'
									.	 $prod['name'] 
									.	 '</a></h3>'	
									.	 '<h4><a href="/" title="' . $thisItem['Supplier']['biz_name']  
														. '" alt="' . $thisItem['Supplier']['biz_name'] . '">'
									.	 $thisItem['Supplier']['biz_name']
									.	 '</a></h4>'
									.  '<h4>'
									.	 '$' . $prod['deal_price'] 
									. 	(!empty($prod['unit']) ? "&nbsp;per&nbsp;" 
											. $prod['unit'] : "")
									.	 '</h4>'
									.	 '</div>';
									
	}
	$product_list .= "</div>";
}
?>
<div class="nav_bar_page">
  <ul>
    <li>
      <a title="Go to Home Page" href="<?=SITE_URL;?>">Freshla</a>
    </li>
    <li>
      <a title="Buy" href="<?=SITE_URL;?>/buy/">Buy</a>
    </li>
    <li>
      <a title="<?=$thisItem['Supplier']['biz_name'];?>" href="/"><?=$thisItem['Supplier']['biz_name'];?></a>
    </li>
    <li>
    	<?=$product['Product']['name'];?>
    </li>
   </ul>
</div>

<div class="page-title">
   <h1><?=$product['Product']['name'];?></h1>
   <div class="social"></div>
</div>

<div class="page_main">
   <div class="page_left">
      <div class="page_board" style="background-color:<?=$thisItem['Supplier']['bgcolor'];?>">
        <a href="/"><img width="167" alt="<?=$thisItem['Supplier']['biz_name'];?>" src="<?=$logo;?>" border="0"></a>
      	<div class="page_board_info">
           <h3 class="page_board_title">
           	<a href="/"><?=$thisItem['Supplier']['biz_name'];?></a>
           </h3>
           <span class="location">
           		<?=strtoupper($thisItem['Supplier']['return_suburb'] . ", " .$thisItem['Supplier']['return_state']);?>
           </span>
           <p>
           		<img class="supplier_img" src="<?=$photo;?>" border="0" width="52" height="52" />
           		<?=$thisItem['Supplier']['short_desc'];?>
           </p>
       		 <a class="contact_us" href="mailto:sales@freshla.com.au">
       		 		<strong>Contact Us</strong>
       		 </a>
       		 <a class="supplier_shop" href="/">
       		 		<strong>Visit Our Shop</strong>
       		 </a>
         </div>
      </div>
			
			<?php if (!empty($thisItem['Supplier']['shipping_info'])) { ?>
      <div class="page_block">
    		<h3><a href="javascript:void(0);" class="block_open">Store Shipping Policy</a></h3>
    		<div class="block_content">
        	<?=$thisItem['Supplier']['shipping_info'];?>    
        </div>
			</div>
			<?php } ?>
			
			<?php if (!empty($thisItem['Supplier']['return_policy'])) { ?> 
			<div class="page_block">
    		<h3><a href="javascript:void(0);" class="block_open">Store Return Policy</a></h3>
    		<div class="block_content">
        	<?=$thisItem['Supplier']['return_policy'];?>    
        </div>
			</div>
			<?php } ?>

   </div>
   
   <div class="page_right">
   		<div class="prod_info">
   			<div class="prod_main">
   				<div id="prod_main_img">
   					<?=$default_img;?>
   				</div>
   				<div class="slider_frame">
						<div id="prod_slider">
							<?=$img_list;?>
						</div>
					</div>
   				<div class="prod_desc">
                	<div id="tab_view" class="tab_view_prod">
                        <div class="tab_bar">
                        	<?=$prod_tabs;?>
                    	</div>
                    
                        <?=$prod_tab_views;?>
                     </div>
   				</div>
   			</div>
   			<div class="prod_cart">
   				<div class="prod_freight">
   					<h3>Shipping Info</h3>
   					<p><b>Time to Ship Out:&nbsp;</b><?=$thisItem['Supplier']['shipping_info'];?></p>
   				<?php if(!empty($thisItem['Supplier']['freight_policy'])) { ?>
   					<p><b>Shipping &amp; Packaging Fee:&nbsp;</b><?=$thisItem['Supplier']['freight_policy'];?></p>
   				<?php } ?>
   				</div>
                
   				<?=$extra_combo;?>
   				
                <div>
   					<div class="prod_buy">
   						<div class="prod_buy_top"></div>
   						<div class="prod_buy_main">
   						<?php if (isset($default_selected_name)) { ?>
   							<div id="subitems_name">
   								<?=$default_selected_name;?>
   							</div>
   						<?php } ?>
                            
   							<div class="prod_qty">
   								<form action="<?=SITE_URL;?>/carts/add" method="post" name="form_cart">
                                <div>
   								<input type="hidden" name="data[Product][id]" value="<?=$product['Product']['id'];?>" />
								<input type="hidden" id="subitems_id" name="data[Subproduct][id]" value="<?=$prod_subitem_id;?>" />
   								$<span id="subitems_price"><?=$prod_current_price;?></span>&nbsp;x&nbsp;
   								<input type="text" name="data[Product][qty]" size="2" value=1 maxlength="4">
                                </div>
                                <ul id="extra_items">
                                </ul>
   								<button id="btn_add2cart" class="btn_add2cart" type="submit">Add to Cart</button>
   								</form>
   							</div>
   						</div>
   						<div class="prod_buy_btm"></div>
   					</div>
   				</div>
   			</div>
      </div>
      
      <div class="prod_other">
      <?php if (!empty($product_list)) { ?>
      	<h3><a href='/'>More products from <?=$thisItem['Supplier']['biz_name'];?>(view all)</a></h3>
      	<?=$product_list;?>
      <?php } ?>
      </div>
   </div>
</div>