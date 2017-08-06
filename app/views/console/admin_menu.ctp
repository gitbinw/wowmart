<?php
if (isset ($menu_list)) {
	echo '<input type="hidden" value="' . implode(',', $menu_list) . '" id="menu_allow_child" />';
}

if ( isset($menu_tree) && !empty($menu_tree) ) {
	echo $menu_tree;
}
else {
	echo 'No Menus So Far!';
}
?>
