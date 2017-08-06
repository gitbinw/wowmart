<?php
class MenuHelper extends Helper {
	function url ($param='') {
		return DS.APP_DIR.DS.'pages'.DS.$param;
	}

	function tbSubMenu ( $arrSubMenus, $mainMenu, $selMenuId ) {
		$ul = "<table cellspacing='0' cellpadding='0'><tr>";
		foreach ($arrSubMenus as $menu){
			$url = '/'.WEBROOT_DIR.'/'.$menu['Menu']['url'].'/'.$menu['Menu']['id'];
			if ($selMenuId == $menu['Menu']['id']) {
				$img = "e01_sel.gif";
			} else {
				$img = "e01.gif";
			}
			$ul .= "<td><p class='menu01'><a href='$url'><img src='/".WEBROOT_DIR."/img/main/$img' border='0'>".
			       "&nbsp;&nbsp;".$menu['Menu']['name']."</a></p></td>";
		}	
		$ul .= "</tr></table>";
		return $ul;
	}

	function tbMenu ($arrMenus, $menuId) {
		$row_num = 5;
		$ul = "<table cellspacing='0' cellpadding='0'><tr>";
		foreach ($arrMenus as $key=>$menu) {
			if ($key%$row_num == 0 && $key != 0) $ul .= "</tr><tr>";
			$menuUrl = '/'.WEBROOT_DIR.'/'.$menu['Menu']['url'];
			if ($menu['Menu']['id'] == 1) {
				$class = 'first';
				if ($menu['Menu']['id'] == $menuId) {
					$class = 'first_sel';
				}
			} else {
				$class = 'menu';
				if ($menu['Menu']['id'] == $menuId) {
                                        $class = 'menu_sel';
                                }
			}
			$ul .= "<td onmouseover='over(this);' onmouseout='out(this);' class='$class'>".
			       "<a href='".$menuUrl."'><div>".$menu['Menu']['name']."</div></a></td>";
		}
		$ul .= "</tr></table>";
		return $ul;
	}

	function tbFooter ($arrMenus) {
		$ul = "<ul class='menu02'>";
                foreach ($arrMenus as $key=>$menu) {
                        $menuUrl = '/'.WEBROOT_DIR.'/'.$menu['Menu']['url'].'/'.$menu['Menu']['id'];
                        $ul .= "<li><a href='".$menuUrl."'>".$menu['Menu']['name']."</a></li>";
                }
                $ul .= "</ul>";
                return $ul;	
	}
	
	function menu ($arrMenus, $menuId) {
                $ul = "<ul>";
                foreach ($arrMenus as $key=>$menu) {
                        $menuUrl = $this->url($menu['Menu']['id']);
                        $ul .= "<li ".($menu['Menu']['id']==$menuId?"class='sel_main_menu'":"").">".
                               "<a href='".$menuUrl."'><div>".$menu['Menu']['name']."</div></a></li>";
                }
                $ul .= "</ul>";
                return $ul;
        }

	function navigator ($ancestor,$menuId) {
		$nv = "<ul id='navigator'><li><a href='".$this->url()."'><div class='home'>Home</div></a></li>";
		foreach ($ancestor as $ant) {
			$url = $this->url($ant['Menu']['id']);
			$nv .= "<li ".($ant['Menu']['id']==$menuId?"class='sel_menu'":"").">".
			       "<div class='delimiter'>&gt;</div>".
			       "<a href='$url'><div>".$ant['Menu']['name']."</div></a></li>";
		}
		$nv .= "</ul>";
		
		return $nv;
	}

	function footUrl ($param) {
                return DS.APP_DIR.DS.'foots'.DS.$param;
        }

	function footer ($footers) {
		$ul = "<ul>";
                foreach ($footers as $key=>$foot) {
			if (!empty($foot['FooterProfile']['refer'])){
				$footUrl = $this->url($foot['FooterProfile']['refer']);
			} else if (!empty($foot['FooterProfile']['url'])) {
				$footUrl = $foot['FooterProfile']['url'];
			} else {
                        	$footUrl = $this->footUrl($foot['Footer']['id']);
			}
			if (!empty($foot['FooterProfile']['target'])){
				$target = 'target='.$foot['FooterProfile']['target'];
			} else {
				$target = '';
			}
                        $ul .= "<li><a href='".$footUrl."' $target><div>".$foot['FooterProfile']['name']."</div></a></li>";
                }
                $ul .= "</ul>";
                return $ul;	
	}

	function subfooter ( $arrSubFooters, $mainFooter, $selFooterId ) {
                $ul = "<table cellspacing='0' cellpadding='0'>";
                $ul .= "<tr><td class='main_menu_title'>".$mainFooter['FooterProfile']['name']."</td></tr>";
                foreach ($arrSubFooters as $foot){
			if (!empty($foot['FooterProfile']['refer'])){
                                $subFooterUrl = $this->url($foot['FooterProfile']['refer']);
                        } else if (!empty($foot['FooterProfile']['url'])) {
                                $subFooterUrl = $foot['FooterProfile']['url'];
                        } else {
                                $subFooterUrl = $this->footUrl($foot['Footer']['id']);
                        }
                        if (!empty($foot['FooterProfile']['target'])){
                                $target = 'target='.$foot['FooterProfile']['target'];
                        } else {
                                $target = '';
                        }			
                        $ul .= "<tr><td ".($foot['Footer']['id']==$selFooterId?"class='sel_sub_menu'":"")." nowrap>".
                               "<a href='".$subFooterUrl."' $target><div>".$foot['FooterProfile']['name']."</div></a></td></tr>";
                }
                $ul .= "</table>";
                return $ul;
        }


	function footerNavigator ($ancestor,$footId) {
		$nv = "<ul id='navigator'><li><a href='".$this->url()."'><div class='home'>Home</div></a></li>";
                foreach ($ancestor as $ant) {
                        $url = $this->footUrl($ant['Footer']['id']);
                        $nv .= "<li ".($ant['Footer']['id']==$footId?"class='sel_menu'":"").">".
                               "<div class='delimiter'>&gt;</div>".
                               "<a href='$url'><div>".$ant['FooterProfile']['name']."</div></a></li>";
                }
                $nv .= "</ul>";

                return $nv;
	}
}

?>
