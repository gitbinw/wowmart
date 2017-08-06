<?php
if ( isset ( $thisItem ) && is_array ( $thisItem ) && count ( $thisItem ) > 0 ) {
	echo "<td>Sub-Category:</td><td>";
	echo $combobox->combobox('','sub_cat',$thisItem,'','','- - Please Select Sub-Category - -');
	echo "</td>";
} else {
	echo '0';
}
?>