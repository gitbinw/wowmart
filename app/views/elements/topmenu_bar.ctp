<?php 
	$menus = $this->requestAction('/pages/topmenus');
	
	/*$output = "<ul>";
	foreach($_LIMIT_AREAS as $area) {
		$output .= "<li>" . 
				   "<a href=''>" . $area['name'] . "</a>";
		$output .= "<ul>";
		foreach($area['suburbs'] as $suburb) {
			$strSub .= "<li><a href=''>" . $suburb['name'] . "</a></li>";
		}
		$output .= "</ul>" .
				   "</li>";
	}
	$output .= "</ul>";*/
?>

<div id="topmenu_bar">
<ul>
<?php foreach($menus as $key=>$menu) : ?>
	<?php if ($key !== 0) { ?> 
		<li class="delimiter"></li>
	<?php } ?>
	<li class="menu_item <?="topmenu_" . $key;?>">
		<a href="<?=SITE_URL;?>/webpage/<?=$menu['PageDetail']['alias'];?>">
			<div class="menu_name">
				<?=$menu['PageDetail']['name'];?>
			</div>
		</a>
	</li>
<?php endforeach ?>
</ul>
</div>