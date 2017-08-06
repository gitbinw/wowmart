<?php
$accMenus = array(
	array('title' => 'Account Dashboard', ''),
	array('title' => 'Account Information', 'action' => 'edit'),
	array('title' => 'Address Book', 'action' => 'address'),
	array('title' => 'My Orders', 'action' => 'history'),
	array('title' => 'Billing Agreements', 'action' => 'billing'),
	array('title' => 'Recurring Profiles', 'action' => 'profile'),
	array('title' => 'My Product Reviews', 'action' => 'reviews'),
	array('title' => 'My Tags', 'action' => 'tags'),
	array('title' => 'My Wishlist', 'action' => 'wishlist'),
	array('title' => 'My Applications', 'action' => 'applications'),
	array('title' => 'Newsletter Subscriptions', 'action' => 'newsletter'),
	array('title' => 'My Downloadable Products', 'action' => 'downloads')
);
?>

<div class="nav-container">
    <!-- <div class="nav" style="width: ;"> -->
    <div class="nav">
        <div class="block-title"><strong>My Account</strong></div>
        <ul class="grid-full"> 
		<?php
            $level = 0;
            $i = 1; 
            foreach($accMenus as $menu) {
                $counts = 0;
                if ($i == 1) $class = 'first ' . $class;
        ?>                   	
            <li class="level nav-<?=$i . ' ' . $class;?> no-level-thumbnail">
                <a class="" href="/customer/<?=$menu['action'];?>">
                    <div class="thumbnail"></div>
                    <span><?=$menu['title'];?></span>
                </a>
             </li>
        <?php
				$i ++;
            }
        ?>
        	<li></li><!--border line-->
    	</ul>
    </div>
</div> <!-- end: nav-container -->