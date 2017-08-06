<div class="nav_bar_page nopadding">
	<ul>
    <li>
      <a title="Go to Home Page" href="<?=SITE_URL;?>">Freshla</a>
    </li>
		<?php 
			if (isset($categories) && count($categories) > 0) {
				foreach($categories as $key => $cat) {
		?>
					<li><a href="/categories/view/<?=$key;?>"><?=$cat;?></a></li>
		<?php
				}
			}
		?>
		<li>
			Search Page
		</li>
	</ul>
</div>

<?php
	if (!isset($paginator->options['url']['keywords'])) {
		echo '<br><br><div class="prod_empty">Please enter your keywords for searching.</div>';
		return;
	}
?>

<div class="main_title">
	Results <span class="search_title">of Searching for "<?=$paginator->options['url']['keywords'];?>"</span>
</div>

<?php
		if (isset($products) && count($products) > 0) {
			$baseUrl = '/search/keywords:' . $paginator->options['url']['keywords'];
			$product_list = $view_all_link = $currLimit = "";
			$product_counts = $paginator->counter(array('format' => '%count%'));
	
			$currPage = isset($paginator->options['url']['page']) ? $paginator->options['url']['page'] : 1;
			$currSort = isset($paginator->options['url']['sort']) ? $paginator->options['url']['sort'] : 'Product.name';
			$currDir  = isset($paginator->options['url']['direction']) ? $paginator->options['url']['direction'] : 'asc';
			$currSortDir = $currSort . ':' . $currDir;
			$sortUrl  = $baseUrl . '/page:' . $currPage;
			
			if (isset($paginator->options['url']['limit'])) {
				$currLimit = '/limit:' . $paginator->options['url']['limit'];
			}
			
			$view_all_link = "<a href='" . $baseUrl . "/limit:" . $product_counts . "'>view all</a>";
			if ($product_counts == count($products)) {
				$view_all_link = "<a href='" . $baseUrl . "'>view less</a>";
			}
			
			$product_list = "<div class='one_row first_line'>";
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
						$product_list .= "</div><div class='one_row'>";
					}
				}
				$subdomain = 'http://' . $prod['Supplier']['subdomain'] . "." . SITE_DOMAIN;
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
											.	 '<h4><a href="' . $subdomain . '" title="' . $prod['Supplier']['biz_name']  
															. '" alt="' . $prod['Supplier']['biz_name'] . '">'
											.	 $prod['Supplier']['biz_name']
											.	 '</a></h4>'
											.  '<h4>'
											.	 '$' . $prod['Product']['deal_price'] 
											. 	(!empty($prod['Product']['unit']) ? "&nbsp;per&nbsp;" 
												. $prod['Product']['unit'] : "")
											.	 '</h4>'
											.	 '</div>';
										
			}
			$product_list .= "</div>";
?>
			<!-- Top pagination bar -->
			<div class="paginate_bar">
				<div class="total_counts">Products (<?=$product_counts;?>)</div>
				<div class="sort_bar"><b>Sort By:</b>
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
			<!-- End of Top pagination bar -->
			
			<?=$product_list;?>
			
			<!-- Bottom pagination bar -->
			<div class="paginate_bar bottom">
				<div class="view_all">
         	Total <?=$product_counts;?> item(s)
         	<?=$view_all_link;?>
        </div>
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
			</div>
			<!-- End of Bottom pagination bar -->
			
			<script language="javascript" type="text/javascript">
				$("select.sort_opts").change(function() {document.location.href=$(this).val();});
			</script>
			
<?php
		} else {
?>
			<div class="prod_empty">Currrently there are no products matched!</div>
<?php
		}
?>