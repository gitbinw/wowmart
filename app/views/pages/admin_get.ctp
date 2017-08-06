<?php
if ( isset ( $thisItem ) && is_array ( $thisItem ) && count ( $thisItem ) > 0 ) {
	echo "<td>Sub-Page:</td><td>";
	echo $combobox->combobox('','sub_cat',$thisItem,'','','- - Please Select Sub-Page - -');
	echo "</td>";
} else {
	echo '0';
}
?>