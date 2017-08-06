<div class="board">
	<div class="board_header">Subscribe &amp; Win</div>
	<div class="board_main">
		<h5>Sign up for our newsletter &amp; WIN $200 Gift Voucher.</h5>
		<a>
			<div class="board_button" id="btn_subscribe">
				Subscribe
			</div>
		</a>
	</div>
</div>

<div class="clear_line_10"></div>

<div id="arrival_box">
	<div class="arrival_header">WHAT'S NEW</div>
	<div class="arrival_main">
		<div class="arrival_list">
	<?php
		$products = $this->requestAction('/products/newarrivals');
		if (isset($products) && count($products) > 0) {
			foreach($products as $key => $prod) {
				$subdomain = 'http://' . $prod['Supplier']['subdomain'] . "." . SITE_DOMAIN;
				$url = $subdomain . "/" . $prod['Product']['product_alias'];
				$img_src = '';
				if (isset($prod['Image'][0]['id'])) {
					$img_src = '/img/images/product/' . $prod['Image'][0]['id'] . 
							 	     '/' . $prod['Image'][0]['id'] . 'a3' . $prod['Image'][0]['extension'];
				}
	?>
				<div class="arrival_item <?=$key==0?'first':'';?>">
					<a href="<?=$url;?>">
						<img src="<?=$img_src;?>" border="0" width="62" height="62" class="arrival_img" />
						<div class="arrival_info"><?=substr($prod['Product']['name'], 0, 20);?><br>
							$<?=$prod['Product']['deal_price'];?></div>
					</a>
				</div>
	<?php
			}
		}
	?>
		</div>
	</div>
</div>

<div class="clear_line_10"></div>

<div class="board board_Recipe">
	<div class="board_header">Sydney Competition</div>
	<div class="board_main">
		<h5>Win Brasa Combos each week. click the submit button below to enter in our weekly lucky draw. 
		Refer up to 5 friends to get more chances to win. </h5>
		<a>
			<div class="board_button" id="btn_competition">
				Submit
			</div>
		</a>
	</div>
</div>