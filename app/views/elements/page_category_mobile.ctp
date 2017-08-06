<?php
$categories = isset($CATEGORY_HIERACHY) ? $CATEGORY_HIERACHY : array();
?>

<div class="nav-container-mobile">
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
				<div class="sf-menu-block">
					<div id="menu-icon">Categories</div>
					<ul class="sf-menu-phone">
					<?php
						$level = 0;
						$i = 1; 
						foreach($categories as $cat) {
							$counts = 0;
							if (isset($cat['children'])) $counts = count($cat['children']);
							$class = 'level-top';
							if ($counts > 0) $class = $class . ' parent';
							if ($i == 1) $class = 'first ' . $class;
					?>                   	
						<li class="level0 nav-<?=$i . ' ' . $class;?>">
							<a class="level-top" href="/category/<?=$cat['category_alias'];?>">
								<span><?=$cat['name'];?></span>
							</a>
							<?=$utility->showMobileSubCategories($cat, $i);?>
						 </li>
					<?php
							$i ++;
						}
					?>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div> <!-- end: nav-container-mobile -->