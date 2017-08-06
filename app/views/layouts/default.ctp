<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Wowmart - <?=!empty($page_title) ? $page_title : DEFAULT_PAGE_TITLE;?></title>
<?=$html->charset('UTF-8');?>
<meta content="width=device-width, initial-scale=1, maximum-scale=1" name="viewport">
<meta content="width=device-width,minimum-scale=1,maximum-scale=1" name="viewport">
<meta content="yes" name="apple-mobile-web-app-capable">
<meta name="description" content="<?=!empty($meta_desc) ? $meta_desc : DEFAULT_META_DESC;?>" />
<meta name="keywords" content="<?=!empty($meta_keywords) ? $meta_keywords : DEFAULT_META_KEYWORDS;?>" />
<meta name="robots" content="INDEX,FOLLOW" />
<link type="image/x-icon" href="/img/favicon.ico" rel="shortcut icon">
<link href='//fonts.googleapis.com/css?family=Ubuntu:400,300,300italic,500,700italic&subset=latin,cyrillic-ext' rel='stylesheet' type='text/css'>

<link href="/magento/js/calendar/calendar-win2k-1.css" type="text/css" rel="stylesheet">
<link media="all" href="/magento/skin/frontend/default/theme713/css/material-design.css" type="text/css" rel="stylesheet">
<link media="all" href="/magento/skin/frontend/default/theme713/css/font-awesome.css" type="text/css" rel="stylesheet">
<link media="all" href="/magento/skin/frontend/default/theme713/css/jquery.bxslider.css" type="text/css" rel="stylesheet">
<link media="all" href="/magento/skin/frontend/default/theme713/css/photoswipe.css" type="text/css" rel="stylesheet">
<link media="all" href="/magento/skin/frontend/default/theme713/css/bootstrap.css" type="text/css" rel="stylesheet">
<link media="all" href="/magento/skin/frontend/default/theme713/css/extra_style.css" type="text/css" rel="stylesheet">
<link media="all" href="/magento/skin/frontend/default/theme713/css/styles.css" type="text/css" rel="stylesheet">
<link media="all" href="/magento/skin/frontend/default/theme713/css/responsive.css" type="text/css" rel="stylesheet">
<link media="all" href="/magento/skin/frontend/default/theme713/css/superfish.css" type="text/css" rel="stylesheet">
<link media="all" href="/magento/skin/frontend/default/theme713/css/camera.css" type="text/css" rel="stylesheet">
<link media="all" href="/magento/skin/frontend/base/default/css/widgets.css" type="text/css" rel="stylesheet">
<link media="all" href="/magento/skin/frontend/default/theme713/aw_blog/css/style.css" type="text/css" rel="stylesheet">
<link media="all" href="/magento/skin/frontend/default/theme713/css/cmsmart/megamenu/megamenu.css" type="text/css" rel="stylesheet">
<link media="all" href="/magento/skin/frontend/default/theme713/css/ecommerceteam/cloud-zoom.css" type="text/css" rel="stylesheet">
<link media="all" href="/magento/skin/frontend/default/theme713/css/catalogsale.css" type="text/css" rel="stylesheet">
<link media="all" href="/magento/skin/frontend/default/theme713/css/tm/googlemap/tm_googlemap.css" type="text/css" rel="stylesheet">
<link media="all" href="/magento/skin/frontend/default/theme713/css/tm/instagram/instagram.css" type="text/css" rel="stylesheet">
<link media="all" href="/magento/skin/frontend/default/theme713/css/tm/productlistgallery/style.css" type="text/css" rel="stylesheet">
<link media="all" href="/magento/skin/frontend/default/theme713/css/youama/ajaxlogin/ajaxlogin.css" type="text/css" rel="stylesheet">
<link media="print" href="/magento/skin/frontend/default/theme713/css/print.css" type="text/css" rel="stylesheet">

<!--[if lt IE 8]>
<link rel="stylesheet" type="text/css" href="/magento/skin/frontend/default/theme713/css/styles-ie.css" media="all" />
<![endif]-->
<!--[if lt IE 7]>
<script type="text/javascript" src="/magento/js/lib/ds-sleight.js"></script>
<script type="text/javascript" src="/magento/skin/frontend/base/default/js/ie6.js"></script>
<![endif]-->

<link type="text/css" href="/magento/skin/frontend/default/theme713/cmsmart/ajaxcart/css/default.css" rel="stylesheet">

<?=$html->css('jquery-ui-1.10.4.custom.min');?>
<?=$html->css('global');?>
</head>

<body class="ps-static">

<div class="wrapper en-lang-class">
	<div class="page">
    	<div class="shadow"></div>
        <div class="swipe-left"></div>
        
		<?=$this->element('page_header');?>
		
		<?=$this->element('page_category_mobile');?>
        
		<div class="main-container col2-left-layout">
			<div class="container">
				<div class="row">
					<div class="col-xs-12">
						<div class="main">
						
							<?=isset($page_breadcrumbs) && !empty($page_breadcrumbs) ? $page_breadcrumbs : '';?>
						
                        	<div class="row">
								<div class="col-main col-xs-12 col-sm-9">
									<div class="padding-s">
        								<?=$content_for_layout;?>
                                    </div>
                               	</div>
                                <div class="col-left sidebar col-xs-12 col-sm-3">
                                	<?=$this->element('page_left');?>
                                </div>
                            </div>
                      	</div>
                  	</div>
              	</div>
          	</div>
       	</div>
        
        <?=isset($showFooterBanner) && $showFooterBanner === true ? $this->element('page_footer_banner', array('cache'=>true)) : '';?>
        <?=$this->element('page_footer', array('cache'=>true));?>            
    </div>
</div>

<script src="/js/config.js" type="text/javascript"></script>

<script src="/magento/js/jquery/jquery-1.11.1.min.js" type="text/javascript"></script>
<script src="/magento/js/jquery/jquery-migrate-1.2.1.min.js" type="text/javascript"></script>
<script src="/magento/js/jquery/jquery_noconflict.js" type="text/javascript"></script>
<script src="/magento/js/prototype/prototype.js" type="text/javascript"></script>
<script src="/magento/js/lib/ccard.js" type="text/javascript"></script>
<script src="/magento/js/prototype/validation.js" type="text/javascript"></script>
<script src="/magento/js/scriptaculous/builder.js" type="text/javascript"></script>
<script src="/magento/js/scriptaculous/effects.js" type="text/javascript"></script>
<script src="/magento/js/scriptaculous/dragdrop.js" type="text/javascript"></script>
<script src="/magento/js/scriptaculous/controls.js" type="text/javascript"></script>
<script src="/magento/js/scriptaculous/slider.js" type="text/javascript"></script>
<script src="/magento/js/varien/js.js" type="text/javascript"></script>
<script src="/magento/js/varien/form.js" type="text/javascript"></script>
<script src="/magento/js/mage/translate.js" type="text/javascript"></script>
<script src="/magento/js/mage/cookies.js" type="text/javascript"></script>
<script src="/magento/js/cmsmart/jquery/ajaxcart/cmsmart-ajaxcart.js" type="text/javascript"></script>
<script src="/magento/js/varien/product.js" type="text/javascript"></script>
<script src="/magento/js/varien/configurable.js" type="text/javascript"></script>
<script src="/magento/js/calendar/calendar.js" type="text/javascript"></script>
<script src="/magento/js/calendar/calendar-setup.js" type="text/javascript"></script>
<script src="/magento/js/ecommerceteam/cloud-zoom.1.0.2.js" type="text/javascript"></script>
<script src="/magento/skin/frontend/default/theme713/js/bootstrap.js" type="text/javascript"></script>
<script src="/magento/skin/frontend/default/theme713/js/jquery.easing.1.3.js" type="text/javascript"></script>
<script src="/magento/skin/frontend/default/theme713/js/jquery.mobile.customized.min.js" type="text/javascript"></script>
<script src="/magento/skin/frontend/default/theme713/js/jquery.carouFredSel-6.2.1.js" type="text/javascript"></script>
<script src="/magento/skin/frontend/default/theme713/js/jquery.touchSwipe.js" type="text/javascript"></script>
<script src="/magento/skin/frontend/default/theme713/js/jquery.bxslider.min.js" type="text/javascript"></script>
<script src="/magento/skin/frontend/default/theme713/js/jquery.unveil.js" type="text/javascript"></script>
<script src="/magento/skin/frontend/default/theme713/js/cherry-fixed-parallax.js" type="text/javascript"></script>
<script src="/magento/skin/frontend/default/theme713/js/smoothing-scroll.js" type="text/javascript"></script>
<script src="/magento/skin/frontend/default/theme713/js/superfish.js" type="text/javascript"></script>
<script src="/magento/skin/frontend/default/theme713/js/scripts.js" type="text/javascript"></script>
<script src="/magento/skin/frontend/default/theme713/js/jquery-ui.js" type="text/javascript"></script>
<script src="/magento/skin/frontend/base/default/js/bundle.js" type="text/javascript"></script>
<script src="/magento/skin/frontend/default/theme713/js/cmsmart/megamenu/cmsmartmenu.js" type="text/javascript"></script>
<script src="/magento/skin/frontend/default/theme713/js/carousel.js" type="text/javascript"></script>
<script src="/magento/skin/frontend/default/theme713/js/tm/productlistgallery/thumbs.js" type="text/javascript"></script>
<script src="/magento/skin/frontend/default/theme713/js/youama/ajaxlogin/jquery-ui-1-10-4.js" type="text/javascript"></script>
<script src="/magento/skin/frontend/default/theme713/js/youama/ajaxlogin/ajaxlogin.js" type="text/javascript"></script>
<script src="/magento/skin/frontend/default/theme713/js/msrp.js" type="text/javascript"></script>
<script src="/magento/skin/frontend/default/theme713/js/camera.js" type="text/javascript"></script>
<script src="/magento/skin/frontend/default/theme713/js/klass.min.js" type="text/javascript"></script>
<script src="/magento/skin/frontend/default/theme713/js/code.photoswipe.jquery-3.0.5.js" type="text/javascript"></script>

<script src="/js/numeral.min.js" type="text/javascript"></script>
<script src="/js/global.js" type="text/javascript"></script>
</body>
</html>