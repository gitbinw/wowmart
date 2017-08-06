<?php
$returns = $utility->breadcrumbs($categories, $currentCategoryId);
$breadcrumbs = $returns['breadcrumbs'];
$currentCatName = $returns['cat_name'];
$currentCatComment = $returns['cat_comment'];

$this->set('page_title', $currentCatName);
$this->set('page_breadcrumbs', $breadcrumbs);
?>

<div class="page-title category-title">
   <h1><?=$currentCatName;?></h1>
</div>

<div id="key-category-produts" class="category-products">
<?php
		if (isset($products) && count($products) > 0) {
			$product_list = $view_all_link = $currLimit = "";
			$product_counts = $paginator->counter(array('format' => '%count%'));
	
			$currPage = isset($paginator->options['url']['page']) ? $paginator->options['url']['page'] : 1;
			$currSort = isset($paginator->options['url']['sort']) ? $paginator->options['url']['sort'] : 'Product.name';
			$currDir  = isset($paginator->options['url']['direction']) ? $paginator->options['url']['direction'] : 'asc';
			$currSortDir = $currSort . ':' . $currDir;
			$sortUrl  = '/category/' . $paginator->options['url'][0] . '/page:' . $currPage;
			
			if (isset($paginator->options['url']['limit'])) {
				$currLimit = '/limit:' . $paginator->options['url']['limit'];
			}
		
			$view_all_link = "<a href='/category/" . $paginator->options['url'][0] . 
											 "/limit:" . $product_counts . "'>view all</a>";
			if ($product_counts == count($products)) {
				$view_all_link = "<a href='/category/" . $paginator->options['url'][0] . "'>view less</a>";
			}
			
			$rowCount = 3;
			$product_list = "";
			$maxLen = count($products);
			foreach($products as $key => $prod) {
				$img_src = '/img/home/noimage_medium.gif';
				$arrMedias = array();
				if (isset($prod['Media'])) { 
					$arrMedias = $utility->getFrontEndImage($prod['Media'], PRODUCT_IMAGE_URL_ROOT, 
											$prod['Product']['id'], $prod['Product']['product_alias']);
				}
				if (isset($arrMedias[0])) {
					$img_src = $arrMedias[0];
				}
				
				$class = "";
				if ($key % 2 === 0) $class .= " even";
				else $class .= " odd";
				
				$mod = $key % $rowCount;
				$oneitem_class = "";
				if ($mod === 0) {
					$oneitem_class .= " first";
					//first row
					if ($key < $rowCount) $class = ' first' . $class;
					//last row
					if ($key >= ($maxLen - $rowCount)) $class = ' last' . $class;
					$product_list .= "<ul class='products-grid row" . $class . "'>";
				} else if ($mod === ($rowCount - 1)) {
					$oneitem_class .= " last";
				}
				
				$product_list .= '<li id="item-' . $prod['Product']['serial_no'] . '" class="item' . $oneitem_class . ' col-xs-12 col-sm-4">'
							  .	 '	<div class="wrapper-hover">' 
							  .	 '		<a class="product-image" title="' . $prod['Product']['name'] . '"'
							  .	 '		  alt="' . $prod['Product']['name'] . '"' 
							  .  '		  href="/product/' . $prod['Product']['product_alias'] . '">'
							  .	 '			<img src="' . $img_src . '" />'
							  .	 '		</a>'
							  .	 '		<div class="product-shop">' 
							  .  '			<h2 class="product-name">' 
							  .	 '				<a title="' . $prod['Product']['name'] . '" ' 
							  .  '				   alt="' . $prod['Product']['name'] . '" ' 
							  .	 '				   href="/product/' . $prod['Product']['product_alias'] . '">'
							  .	 						$prod['Product']['name'] 
							  .	 '				</a>' 
							  .	 '			</h2>'
							  .  '			<div class="price-box">';
							  
				if (isset($prod['Product']['price']) && !empty($prod['Product']['price'])) {
					$product_list .= '			<p class="old-price"><span class="price">' 
							  .  '					$' . number_format($prod['Product']['price'], 2) 
							  .  '				</span></p>'
							  .  '				<p class="special-price"><span class="price">' 
							  .  '					$' . number_format($prod['Product']['deal_price'], 2) 
							  .  '				</span></p>';
							  
				} else {
					$product_list .=  '			<span class="regular-price"><span class="price">' 
							  .  '					$' . number_format($prod['Product']['deal_price'], 2) 
							  .  '				</span></span>';
				}
				
				$product_list .= '			</div>'
							  .  '			<div class="wrapper-hover-hiden">'
							  .  '				<div class="desc_grid">'
							  .  					substr($prod['Product']['long_desc'], 0, 60) . '...'
							  .	 '				</div>'
							  .	 '				<div class="actions">'
							  .  '					<button class="button btn-cart" type="button">'
							  .  '						<span>'
							  .  '							<i class="material-design-shopping231"></i>'
							  .  '							<span>Add to Cart</span>'
							  .  '						</span>'	
							  .  '					</button>'				
							  .  '				</div>'
							  .  '			</div>'
							  .  '		</div>'
							  .  '  </div>'
							  .	 '</li>';
										
				if ($mod === ($rowCount - 1) || $key === ($maxLen - 1)) $product_list .= "</ul>";
			}
?>
			<!-- Top pagination bar -->
			<div class="toolbar">
				<div class="pager">
                	<p class="amount"><strong><?=$product_counts;?> Item(s)</strong></p>
					<div class="limiter">
                    	<label>Show</label>
                        <select class="sort_opts">
                        	<option value="http://127.0.0.1/magento/index.php/appliances.html?limit=9"> 15 </option>
                            <option value="http://127.0.0.1/magento/index.php/appliances.html?limit=15"> 30 </option>
                            <option value="http://127.0.0.1/magento/index.php/appliances.html?limit=30"> 60 </option>
                      	</select>			
					</div>
              	</div>
                <div class="sorter">
                	<div class="page_bar">
                        <ul>
                            <li class="page_prev" alt="Previous Page" title="Previous Page">
                                <?=$paginator->prev('&nbsp;', array('tag' => 'div', 'escape' => false), null, null);?>
                            </li>
                            <li class="page_title">
                                Pages:
                            </li>
                                <?=$paginator->numbers(array('tag' => 'li', 'separator' => ''));?>
                            <li class="page_next" alt="Next Page" title="Next Page">
                                <?=$paginator->next('&nbsp;', array('tag' => 'div', 'escape' => false), null, null);?>
                            </li>
                        </ul>
                    </div>
                    
                	<div class="sort-by">
                    	<label>Sort By</label>
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
			</div>
			<!-- End of Top pagination bar -->
			
			<?=$product_list;?>
			
			<!-- Bottom pagination bar -->
			<div class="toolbar-bottom">
            	<div class="pager">
                	<p class="amount"><strong><?=$product_counts;?> Item(s)</strong></p>
					<div class="limiter">
                    	<label>Show</label>
                        <select class="sort_opts">
                        	<option value="http://127.0.0.1/magento/index.php/appliances.html?limit=9"> 15 </option>
                            <option value="http://127.0.0.1/magento/index.php/appliances.html?limit=15"> 30 </option>
                            <option value="http://127.0.0.1/magento/index.php/appliances.html?limit=30"> 60 </option>
                      	</select>			
					</div>
              	</div>
                <div class="sorter">
                	<div class="page_bar">
                        <ul>
                            <li class="page_prev" alt="Previous Page" title="Previous Page">
                                <?=$paginator->prev('&nbsp;', array('tag' => 'div', 'escape' => false), null, null);?>
                            </li>
                            <li class="page_title">
                                Pages:
                            </li>
                                <?=$paginator->numbers(array('tag' => 'li', 'separator' => ''));?>
                            <li class="page_next" alt="Next Page" title="Next Page">
                                <?=$paginator->next('&nbsp;', array('tag' => 'div', 'escape' => false), null, null);?>
                            </li>
                        </ul>
                    </div>
                
                	<div class="sort-by">
                    	<label>Sort By</label>
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
			</div>
			<!-- End of Bottom pagination bar -->
			
<?php
		} else {
?>
			<div class="prod_empty">Currrently there are no products!</div>
<?php
		}
?>
</div> <!-- category-products -->