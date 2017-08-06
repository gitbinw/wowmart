<?php
	$cart = $this->requestAction('/carts/getCart');
	$itemCount = 0;
	foreach($cart['items'] as $item) {
		$itemCount += $item['quantity'];
	}
?>
        
<div class="board_search">
	<div class="board_header">
		<div class="corner_top_lft"></div>
		<div class="top_middle"></div>
		<div class="corner_top_rgt"></div>
	</div>
	
	<div class="board_main">
		<div class="title"><a href="<?=SITE_URL;?>/carts/view" class="shopcart">Shopping Cart</a></div>
		<div id="cart_summary">
			<?=$itemCount > 0 ? $itemCount . '&nbsp;items' . '&nbsp;$' . number_format($cart['totalAmount'],2) : 'Empty';?>
		</div>
		<div class="buttons">
			<a href="<?=SITE_URL;?>/checkout">Checkout</a>
			<a href="<?=SITE_URL;?>/carts/view">View</a>
		</div>
	</div>
	
	<div class="board_footer">
		<div class="corner_btm_lft"></div>
		<div class="btm_middle"></div>
		<div class="corner_btm_rgt"></div>
	</div>
</div>


