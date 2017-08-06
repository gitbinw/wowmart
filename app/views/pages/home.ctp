<?php
$this->set('showFooterBanner', true);
?>
<div class="std">
	<div class="fluid_container_wrap">
		<div class="fluid_container">
			<div id="camera_wrap" class="camera_wrap camera_orange_skin">
            <?php
				if (isset($top_banners) && !empty($top_banners)) {
			?>
						<?php 
							$theUrl = '';
							foreach($top_banners as $key => $tbn) { 
								if (isset($tbn['img_url'])) {
									$url = $tbn['img_url'];
									if (!empty($url)) {
										if (strpos($url, 'http:') === 0 || strpos($url, 'https:') === 0) 
											$theUrl = $url;
										else 
											$theUrl = SITE_URL_ROOT . $url;
									}
						?>
						<!--			<a href = "<?=$theUrl;?>" target="_blank">-->
						<?php
								}
								$imgSrc = SITE_URL_ROOT . $tbn['img_src'];
						?>
									<div data-link="<?=$theUrl;?>" data-src="<?=$imgSrc?>"> 
                                    	<div class="camera_caption fadeFromRight">
                                        	<div class="lof_camera_slog">
                                            	<?=isset($tbn['banner_slog']) ? $tbn['banner_slog'] : '';?>
                                            </div>
											<div class="lof_camera_title">
                                            	<?=isset($tbn['banner_title']) ? $tbn['banner_title'] : '';?>
                                            </div>
                                        	<a href = "<?=$theUrl;?>" target="_blank">
                                            	<?=isset($tbn['link_name']) && !empty($tbn['link_name']) ? $tbn['link_name'] : "SHOP NOW";?>
                                           	</a>
                                        </div>
                                    </div>
						<?php
								if (isset($tbn['img_url'])) {
						?>
						<!--			</a>-->
						<?php
								}
							}
						?>
			<?php
				}
			?>
    
    		</div>
		</div>
	</div>
	
    
    	
    
    
    <ul class="list-1 row">
        <li class="col-sm-4">
            <a href="http://livedemo00.template-help.com/magento_53792/clothing.html" class="block-1">
                <img src="http://livedemo00.template-help.com/magento_53792/skin/frontend/default/theme713/images/media/img-1.jpg">
                <div class="content-center">
                    <div class="title-1">APPLIANCES</div>
                </div>
            </a>    
        </li>
        <li class="col-sm-4">
            <a href="http://livedemo00.template-help.com/magento_53792/electronics.html" class="block-1">
                <img src="http://livedemo00.template-help.com/magento_53792/skin/frontend/default/theme713/images/media/img-2.jpg">
                <div class="content-center">
                    <div class="title-1">CLOTHING</div>
                </div>  
            </a>
        </li>
        <li class="col-sm-4">
            <a href="http://livedemo00.template-help.com/magento_53792/furniture.html" class="block-1">
                <img src="http://livedemo00.template-help.com/magento_53792/skin/frontend/default/theme713/images/media/img-3.jpg">
                <div class="content-center">
                    <div class="title-1">COMPUTERS</div>
                </div>  
            </a>
        </li>
    </ul>
    <ul class="list-1 row">
        <li class="col-sm-4">
            <a href="http://livedemo00.template-help.com/magento_53792/jewelry.html" class="block-1">
                <img src="http://livedemo00.template-help.com/magento_53792/skin/frontend/default/theme713/images/media/img-4.jpg">
                <div class="content-center">
                    <div class="title-1">ELECTRONICS</div>
                </div>
            </a>    
        </li>
        <li class="col-sm-4">
            <a href="http://livedemo00.template-help.com/magento_53792/appliances.html" class="block-1">
                <img src="http://livedemo00.template-help.com/magento_53792/skin/frontend/default/theme713/images/media/img-5.jpg">
                <div class="content-center">
                    <div class="title-1">FURNITURE</div>
                </div>  
            </a>
        </li>
        <li class="col-sm-4">
            <a href="http://livedemo00.template-help.com/magento_53792/appliances.html" class="block-1">
                <img src="http://livedemo00.template-help.com/magento_53792/skin/frontend/default/theme713/images/media/img-6.jpg">
                <div class="content-center">
                    <div class="title-1">JEWELRY</div>
                </div>  
            </a>
        </li>
    </ul>


</div>