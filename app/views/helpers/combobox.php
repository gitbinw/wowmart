<?php
class ComboboxHelper extends Helper {
	function combobox ( $id, $strName, $arrData, $selOption, $css, $firstItem='', $js='', $disabled='' ) {
		$combBox = "";
		$combBox .= "<select id='".$id."' name='".$strName."' ".$disabled." class='".$css."'".
                  (isset($js) && !empty($js) ? "onchange = '$js'" : "").">";
        if(trim($firstItem) != '') {
        	$combBox .= "<option value='' selected>".$firstItem;
        }
        foreach($arrData as $key=>$data) {
        	if (is_array($selOption)) {
        		$sel_option = isset($selOption[$key])?$selOption[$key]:'';
        	} else {
        		$sel_option = $selOption;
        	}
        	$combBox .= "<option value='".$key."' ".($key == $sel_option ? "selected" : "").">".$data;
        }

    	$combBox .= "</select>";

    	return $combBox;
	}
	
	function recursiveCategory ( $label, $selected_categories, $categories, $disabled = '' ) {
		$htmlSel = '';
		if ( isset($selected_categories) && count($selected_categories)>0 ) {
			foreach ( $selected_categories as $key => $cats ) {
				if ( empty($cats['sel']) ) {
					$htmlSel = "<tr id='category_$key' name='cat' class='recursive_category'><td>Sub-Category:</td><td>".
								$this->combobox('','sub_cat',$cats['list'],'',
								'','- - Please Select Sub-Category - -', '', $disabled )."</td></tr>";
				} else {
					if ($key == 0) {
						$title = $label;
						$varName = 'cat';
					} else {
						$title = 'Sub-' . $label;
						$varName = 'sub_cat';
					}
					$htmlSel .= "<tr id='category_$key' name='$varName'>";		
					$htmlSel .= "<td>$title:</td><td>".$this->combobox('',$varName,$cats['list'],
   								 $cats['sel'],'','- - Please Select '.$title.' - -', '', $disabled )."</td></tr>";
				}
			}
		} else {
			$htmlSel = "<tr id='category_0' name='cat'><td>" . $label . ":</td><td>".
						$this->combobox('','cat',$categories,'',
						'','- - Please Select Category - -', '', $disabled )."</td></tr>";
		}
		return $htmlSel;
	}
}
?>