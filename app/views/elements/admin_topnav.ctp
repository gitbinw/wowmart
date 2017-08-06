<?php
	$loginMenu = isset($Auth) && $Auth ? 'logout' : 'login';
?>
<ul class='head_menu'>
	<li>
		<a href='/admin/users/<?=$loginMenu;?>'>
			<?=ucwords($loginMenu);?>
		</a>
	</li>
</ul>
