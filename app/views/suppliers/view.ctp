<?php
$this->set('page_title', $thisItem['Supplier']['biz_name']);
?>
<?php
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
	
	$product_list = $view_all_link = $currLimit = "";
	$subdomain = 'http://' . $thisItem['Supplier']['subdomain'] . "." . SITE_DOMAIN;
	$product_counts = $paginator->counter(array('format' => '%count%'));
	
	if (isset($products) && is_array($products)) {
		$currPage = isset($paginator->options['url']['page']) ? $paginator->options['url']['page'] : 1;
		$currSort = isset($paginator->options['url']['sort']) ? $paginator->options['url']['sort'] : 'Product.name';
		$currDir  = isset($paginator->options['url']['direction']) ? $paginator->options['url']['direction'] : 'asc';
		$currSortDir = $currSort . ':' . $currDir;
		$sortUrl  = '/items/page:' . $currPage;
		
		if (isset($paginator->options['url']['limit'])) {
			$currLimit = '/limit:' . $paginator->options['url']['limit'];
		}
		$paginator->options(array('url' => '../../items'));
		
		$view_all_link = "<a href='/items/limit:" . $product_counts . "'>view all</a>";
		if ($product_counts == count($products)) $view_all_link = "<a href='/'>view less</a>";
		
		$product_list = "<div class='oneline first_line'>";
		foreach($products as $key => $prod) {
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
										.	 '<a title="' . $prod['Product']['name'] 
												. '" alt="' . $prod['Product']['name'] 
												. '" href="' . $subdomain . "/" . $prod['Product']['product_alias'] . '">'
										.	 '<img width="140" height="105" src="'  
												. $img_src . '" border="0" />'
										.	 '</a>'
										.	 '<h3><a title="' . $prod['Product']['name'] 
												. '" alt="' . $prod['Product']['name'] 
												. '" href="' . $subdomain . "/" . $prod['Product']['product_alias'] . '">'
										.	 $prod['Product']['name'] 
										.	 '</a></h3>'	
										.	 '<h4><a href="/" title="' . $thisItem['Supplier']['biz_name']  
															. '" alt="' . $thisItem['Supplier']['biz_name'] . '">'
										.	 $thisItem['Supplier']['biz_name']
										.	 '</a></h4>'
										.  '<h4>'
										.	 '$' . $prod['Product']['deal_price'] 
										. 	(!empty($prod['Product']['unit']) ? "&nbsp;per&nbsp;" 
												. $prod['Product']['unit'] : "")
										.	 '</h4>'
										.	 '</div>';
										
		}
		$product_list .= "</div>";
	} else {
		$product_list = "Products are comming soon!";
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
      <?=$thisItem['Supplier']['biz_name'];?>
    </li>
   </ul>
</div>

<div class="page-title">
   <h1><?=$thisItem['Supplier']['biz_name'];?></h1>
   <h2 class="location">
   		<?=strtoupper($thisItem['Supplier']['return_suburb'] . ", " .$thisItem['Supplier']['return_state']);?>
   </h2>
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
           		<?=$thisItem['Supplier']['short_desc'];?>
           </p>
       		 <a class="contact_us" href="mailto:sales@freshla.com.au">
       		 		<strong>Contact Us</strong>
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
   		<div class="supplier_info">
   			<div class="description">
   				<?=$thisItem['Supplier']['long_desc'];?>
   			</div>
   			<div class="photo">
   				<img src="<?=$photo;?>" border="0" />
   			</div>
   		</div>
   		
   		<div id="tab_view">
   			<div class="tab_bar">
      		<div id="tb_produts" class="tab_element tab_current">Shop</div>
        	<div id="tb_story" class="tab_element">Story</div>
      	</div>
      
      	<div id="tb_produts_cnt" class="tab_cnt">
      		<!--sort bar-->
      		<div class="paginate_bar">
      			<div class="total_counts">Products (<?=$product_counts;?>)</div>
      			<div class="sort_bar"><label><b>Sort By:</b></label>
							<select class="sort_opts">
								<option value="<?=$sortUrl;?>/sort:Product.name/direction:asc<?=$currLimit;?>" <?='Product.name:asc' == $currSortDir ? 'selected' : '';?>>
									Product Name (A-Z)
								</option>
								<option value="<?=$sortUrl;?>/sort:Product.name/direction:desc<?=$currLimit;?>" <?='Product.name:desc' == $currSortDir ? 'selected' : '';?>>
									Product Name (Z-A)
								</option>
								<option value="<?=$sortUrl;?>/sort:Product.deal_price/direction:asc<?=$currLimit;?>" <?='Product.deal_price:asc' == $currSortDir ? 'selected' : '';?>>
									Price (Low to High)
								</option>	
								<option value="<?=$sortUrl;?>/sort:Product.deal_price/direction:desc<?=$currLimit;?>" <?='Product.deal_price:desc' == $currSortDir ? 'selected' : '';?>>
									Price (High to Low)
								</option>
							</select>
						</div>
      		</div>
      		<!--end of sort bar-->
      		
         	<?=$product_list;?>
         	
         	<!--paginator bar-->
         	<div class="paginate_bar bottom">
         		<div class="view_all">
         			Total <?=$product_counts;?> item(s)
         			<?=$view_all_link;?>
         		</div>
						<div class="page_bar">
							<ul>
								<li class="page_title">
									Pages:
								</li>
								<li class="page_prev" alt="Previous Page" title="Previous Page">
									<?=$paginator->prev('&nbsp;', array('tag' => 'div', 'escape' => false), null, null);?>
								</li>
									<?=$paginator->numbers(array('tag' => 'li', 'separator' => ''));?>
								<li class="page_next" alt="Next Page" title="Next Page">
									<?=$paginator->next('&nbsp;', array('tag' => 'div', 'escape' => false), null, null);?>
								</li>
							</ul>
						</div>
					</div>
					<!--end of paginator bar-->
      	</div>
     	 	<div id="tb_story_cnt" class="tab_cnt">
     	 	<?php if(!empty($thisItem['Supplier']['motto'])) { ?>
     	 		<blockquote class="motto">
     	 			<p class="quote_open"></p>
						<p class="motto_cnt">
							<?=$thisItem['Supplier']['motto'];?>
						</p>
						<p class="quote_close"></p>
					</blockquote>
				<?php } ?>
				
         	<?=$thisItem['Supplier']['story'];?>
      	</div>
      </div>
   </div>
</div>