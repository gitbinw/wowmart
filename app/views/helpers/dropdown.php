<?php
class DropdownHelper extends Helper {
	/* this function use model name */
	function simple ( $strName, $modelName, $arrData, $selOption = '', $firstItem='', $name='name', $key='id') {
		
		$dropDownBox = "<select name='" . $strName . "'>";
		
		if($firstItem !== false) {
			$dropDownBox .= "<option value='' selected>" . $firstItem . "</option>";
		}
		
		$sel_option = isset($selOption) ? $selOption : '';
		foreach($arrData as $data) {
			$val = $data[$modelName][$key];
			$label = $data[$modelName][$name];
			
			$txtSel = $val == $sel_option ? "selected" : "";
			
			$dropDownBox .= "<option value='". $val . "' " . $txtSel . ">" . $label . "</option>";
		}

		$dropDownBox .= "</select>";

		return $dropDownBox;
	}
	
	/* this function don't need model name */
	function dropdown ( $strName, $arrData, $selOption = '', $firstItem='', $name='name', $key='id') {
		
		$dropDownBox = "<select name='" . $strName . "'>";
		
		if($firstItem !== false) {
			$dropDownBox .= "<option value='' selected>" . $firstItem . "</option>";
		}
		
		$sel_option = isset($selOption) ? $selOption : '';
		foreach($arrData as $data) {
			$val = $data[$key];
			$label = $data[$name];
			
			$txtSel = $val == $sel_option ? "selected" : "";
			
			$dropDownBox .= "<option value='". $val . "' " . $txtSel . ">" . $label . "</option>";
		}

		$dropDownBox .= "</select>";

		return $dropDownBox;
	}
}