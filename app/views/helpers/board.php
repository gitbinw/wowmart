<?php
class BoardHelper extends Helper {
	var $size = array('w'=>'255','h'=>'120');
	
	private function boardLayout ($content) {
		$size = "width='".$this->size['w']."px' height='".$this->size['h']."px'";
		$root = "/".WEBROOT_DIR."/img/main/";
		$imgR = "/".WEBROOT_DIR."/img/";
		$board = "<table cellspacing='0' cellpadding='0' border='0' $size><tr><td>".
				 "<img width='10' height='9' border='0' src='".$root."t_11.gif'/>".
				 "</td><td background='".$root."t_13.gif' align='left'>".
				 "<img width='6' height='9' border='0' src='".$root."t_12.gif'/>".
				 "</td><td background='".$root."t_13.gif' align='right'>".
				 "<img width='6' height='9' border='0' src='".$root."t_14.gif'/>".
				 "</td><td><img width='10' height='9' border='0' src='".$root."t_15.gif'/>".
				 "</td></tr><tr valign='top'><td background='".$root."t_fon_left.gif'>".
				 "<img width='10' height='6' border='0' src='".$root."t_21.gif'/>".
				 "</td><td colspan='2' rowspan='2' $size>".$content."</td>".
				 "<td background='".$root."t_fon_right.gif'>".
				 "<img width='10' height='6' border='0' src='".$root."t_23.gif'/>".
				 "</td></tr><tr valign='bottom'><td background='".$root."t_fon_left.gif'>".
				 "<img width='10' height='7' border='0' src='".$root."t_31.gif'/></td>".
				 "<td background='".$root."t_fon_right.gif'>".
				 "<img width='10' height='7' border='0' src='".$root."t_33.gif'/>".
				 "</td></tr><tr><td><img width='10' height='10' border='0' src='".$root."t_41.gif'/>".
				 "</td><td background='".$root."t_fon_bot.gif' align='left'>".
				 "<img width='6' height='10' border='0' src='".$root."t_42.gif'/></td>".
				 "<td background='".$root."t_fon_bot.gif' align='right'>".
				 "<img width='6' height='10' border='0' src='".$root."t_44.gif'/></td><td>".
				 "<img width='10' height='10' border='0' src='".$root."t_45.gif'/>".
				 "</td></tr></table>";	
		
		return $board;
	}
	
	function itemBoard ($item,$catId='',$arrImg='') {
		$size = "width='".$this->size['w']."px' height='".$this->size['h']."px'";
		$root = "/".WEBROOT_DIR."/img/main/";
		$imgR = "/".WEBROOT_DIR."/img/";
		if ( isset($item['Image']) && count($item['Image']) > 0 ) {
			foreach ( $item['Image'] as $img ) {
				if ( $img['img_index'] == 3 ) {
					$main_image = $imgR.$img['dir'].$img['file_name'];
					$w = (!empty($img['width'])?$img['width']:88).'px';
					$h = (!empty($img['height'])?$img['height']:81).'px';
				} 
			}
		} else if (!empty($arrImg)) {
			$main_image = $imgR.@$arrImg['dir'].@$arrImg['file_name'];
                        $w = (!empty($arrImg['width'])?$arrImg['width']:88).'px';
                        $h = (!empty($arrImg['height'])?$arrImg['height']:81).'px';
		}
		
		$board = "<table $size cellspacing='0' cellpadding='0' border='0'><tr><td>".
				 "<img width='".@$w."' height='".@$h."' border='0' src='".@$main_image."'/>".
				 "</td><td><p style='color:#1F86DE;font-size:15px;padding-bottom:0px;'>".
				 "<b>".$item['serial_no']."</b></p><p>".$item['long_desc']."</p>";
		if ($item['whole_disc']>0) {
			$board .= "<p style='color:#A95454;font-size:10pt;padding-bottom:5px;text-decoration:line-through;'>".
				 	  "<b>RRP: ".$item['whole_price']."</b></p>";
		}
		$board .= "<p style='color:#DA0008;font-size:17px;padding-bottom: 5px;'>".
				 "<b>DEAL: ".$item['whole_deal']."</b></p></td></tr></table>".
				 "<ul class='btn_buy'><li><a href='/".WEBROOT_DIR."/webshops/add/".$item['id'].
				 (!$catId?'':"/".$catId)."'><img border='0' src='".$root."cart.gif'/></a></li>".
				 "<li><a href='/".WEBROOT_DIR."/webitems/view/".$item['id']."'>".
				 "<img border='0' src='".$root."detail.gif'/></a></li></ul>";
				 
		return $this->boardLayout($board);
	}
	
	function newsBoard ($id,$title,$content,$date='') {
		$board = "<table width='100%' height='".$this->size['h']."'  border='0' cellpadding='5' cellspacing='0'>".
			 "<tr><td class='roll_title'>".$title."</td></tr>";
        if (!empty($date)) {
        	$board .= "<tr><td class='roll_date'>".$date."</td></tr>";
        }
        $board .= "<tr><td class='roll_content'>".substr($content,0,100)."...".
		  "(<a href='/".WEBROOT_DIR."/webnews/view/$id'>Details</a>)</td></tr></table>";
        
        return $board;
	}
	
	function newsRoller ($news,$m,$num=1) {
		$size = "width='".$this->size['w']."px' height='".$this->size['h']."px'";
		if (!is_array($news) || count($news) <= 0) return "";
		$m = ucwords($m);
		$board = "<table border='0' cellspacing='0' cellpadding='2' $size><tr><td class='roll_board'>".
				 "<script language='javascript'>var objNews = new RollBar ( 'roll_bar_news', 'objNews' );".
				 "objNews.intMaxEdge = ".$this->size['h'].";";
 		foreach ($news as $arr) {
			$tmp = $this->newsBoard($arr[$m]['id'],$arr[$m]['title'],$arr[$m]['content'],$arr[$m]['start_date']);
			$board .= "objNews.load ( \"$tmp\" );";
		}
		$board .= "objNews.startRoll ();</script></td></tr></table>";
				  
		return $this->boardLayout($board);
	}
	
	function infoBoard ($info) {
		$size = "width='".$this->size['w']."px' height='124px'";
		if (!is_array($info) || count($info) <= 0) return "";
		$board = "<table class='info' cellspacing='0' cellpadding='0' $size>";
		$board .= "<tr><td class='owner'>".$info['Owner']['company']."</td></tr>".
				  "<tr><td>".$info['Owner']['address'].
				  "&nbsp;".$info['Owner']['suburb']."&nbsp;".$info['Owner']['state']."&nbsp;".$info['Owner']['postcode']."</td></tr>".
				  "<tr><td>TEL:&nbsp;".@$info['Owner']['phone']."&nbsp;&nbsp;".@$info['Owner']['mobile']."</td></tr>".
				  "<tr><td>FAX:&nbsp;".@$info['Owner']['fax']."</td></tr>";
		$board .= "</table>";
				  
		return $this->boardLayout($board);
	}
}
?>
