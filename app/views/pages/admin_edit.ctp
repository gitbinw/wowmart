<?php 
	header('Cache-Control: no-cache, must-revalidate');
	$currentHomepage = "";
	$disableHomepage = "";
	if (isset($homepage['PageDetail']['alias']) && !empty($homepage['PageDetail']['alias'])) {
		if (!isset($thisItem) || $homepage['PageDetail']['alias'] != $thisItem['PageDetail']['alias']) {
			$currentHomepage = "&nbsp;(current homepage is - " . $homepage['PageDetail']['name'] . ")";
			$disableHomepage = "disabled";
		}
	}
	
	$otherBannerUrls = array();
	$top_banner_images = array();
	$top_banner_urls = array();
	$featureBannerImages = array();
	$featureBannerUrls = array();
	if (isset($thisItem['PageBanner'])) {
		$pageBanners = $thisItem['PageBanner'];
		foreach($pageBanners as $bn) {
			$bannerType = $bn['banner_type'];
			$lnkUrl = isset($bn['url']) ? $bn['url'] : '';
			$imgSrc = isset($bn['image_src']) ? $bn['image_src'] : '';
			$bannerSlog = isset($bn['banner_slog']) ? $bn['banner_slog'] : '';
			$linkName = isset($bn['link_name']) ? $bn['link_name'] : '';
			$bannerTitle = isset($bn['banner_text']) ? $bn['banner_text'] : '';
			$hoverText = isset($bn['hover_text']) ? $bn['hover_text'] : '';
			$imgId = isset($bn['id']) ? $bn['id'] : '';
			
			if ($bannerType == PAGE_BANNER_TYPE_TOP) {
				$top_banner_images[] = $imgSrc;
				$top_banner_urls[] = $lnkUrl;
				$top_banner_text[] = array(
					'banner_id' => $imgId, 
					'banner_title' => $bannerTitle, 
					'hover_text' => $hoverText,
					'link_name' => $linkName,
					'banner_slog' => $bannerSlog
				);
			} else if ($bannerType == PAGE_BANNER_TYPE_FEATURE) { //other banners
				$featureBannerImages[] = $imgSrc;
				$featureBannerUrls[] = $lnkUrl;
				$featureBannerText[] = array('banner_id' => $imgId, 'banner_title' => $bannerTitle, 'hover_text' => $hoverText);
			}
		}
	}
	for($i=0; $i<4; $i++) {
		${"featureBanner" . ($i + 1)} = isset($featureBannerImages[$i]) ? $featureBannerImages[$i] : '';
	}

	$tplOptions = $templateId = '';
	if (isset($thisItem['Page']['page_template_id'])) $templateId = $thisItem['Page']['page_template_id'];
	if (isset($pageTemplates)) {
		foreach($pageTemplates as $tpl) {
			$checked = $tpl['id'] == $templateId ? 'selected' : '';
			
			$tplOptions .= '<option value="' . $tpl['id'] . '"' . $checked . '>' . 
								$tpl['name'] . '</option>';
		}
	}
	
	$isHomePage = isset($thisItem['PageDetail']['is_home_page']) && 
					$thisItem['PageDetail']['is_home_page'] == 1 ? true : false;
	
	$targetOptions = '<option value=""></option>';
	$arrTargets = array('_blank', '_new', '_parent', '_self', '_top');
	foreach($arrTargets as $tg) {
		$tgChecked = $tg == isset($thisItem['PageDetail']['url_target']) && $thisItem['PageDetail']['url_target'] ? 'checked' : '';
		$targetOptions .= '<option value="' . $tg . '"' . $tgChecked . '>' . $tg. '</option>';
	}
	
?>
<form id='form_detail' onsubmit='return false'>
<input type='hidden' name='data[Page][id]' value="<?=@$thisItem['Page']['id'];?>">
<input type='hidden' name='data[PageDetail][id]' value="<?=@$thisItem['PageDetail']['id'];?>">
<table class='form_table' cellspacing='0' cellpadding='0'>
<tr><td align='center' class='reg_form_title'>Page Detail Form</td></tr>
<tr><td align='center'><table cellspacing='2' cellpadding='0' class="reg_table">

<tr bgcolor="#CCCCCC">
<td colspan="3">
<input type='hidden' name='data[PageDetail][is_shown]' value='0'>
<input type='checkbox' name='data[PageDetail][is_shown]' value='1' 
	<?=isset($thisItem['PageDetail']['is_shown'])&&$thisItem['PageDetail']['is_shown']==1?"CHECKED":"";?>>
	Publish this page on the website
</td>
<td></td>
</tr>

<tr bgcolor="#FFD5FF">
<td colspan="3">
<input type='hidden' name='data[PageDetail][is_menu]' value='0'>
<input type='checkbox' name='data[PageDetail][is_menu]' value='1' 
	<?=isset($thisItem['PageDetail']['is_menu'])&&$thisItem['PageDetail']['is_menu']==1?"CHECKED":"";?>>
	Show this page on the menu bar
</td>
<td></td>
</tr>

<tr bgcolor="#FFD5FF">
<td colspan="3">
<input type='hidden' name='data[PageDetail][is_display_only]' value='0'>
<input type='checkbox' name='data[PageDetail][is_display_only]' value='1' 
	<?=isset($thisItem['PageDetail']['is_display_only'])&&$thisItem['PageDetail']['is_display_only']==1?"CHECKED":"";?>>
	Only for displaying in menu bar (Note: no link when it is turned on)
</td>
<td></td>
</tr>

<tr bgcolor="#FFD5FF">
<td colspan="3">
<input type='hidden' name='data[PageDetail][is_foot_menu]' value='0'>
<input type='checkbox' name='data[PageDetail][is_foot_menu]' value='1' 
	<?=isset($thisItem['PageDetail']['is_foot_menu'])&&$thisItem['PageDetail']['is_foot_menu']==1?"CHECKED":"";?>>
	Show this page on the footer bar
</td>
<td></td>
</tr>

<tr bgcolor="#CCCCCC">
<td colspan="3">
<input type='hidden' name='data[PageDetail][is_home_page]' value='0'>
<input type='checkbox' id='chk_is_homepage' name='data[PageDetail][is_home_page]' value='1' <?=$disableHomepage;?> 
	<?=isset($thisItem['PageDetail']['is_home_page'])&&$thisItem['PageDetail']['is_home_page']==1?"CHECKED":"";?>>
	Use this page as home page <?=$currentHomepage;?> 
</td>
<td></td>
</tr>

<?php if ($isHomePage === true) { ?>
<tr class="upload_image_strip" id="upload_image_strip" style="display:none;">
	<td colspan="4">
    	<table cellpadding="0" cellspacing="0" width="100%">
        	<!--<tr>
            	<td width="160px" height="50px">Set up Youtube Video</td>
                <td valign="middle">
                	<label>Video URL: </label>
                    <input type="text" name="data[PageDetail][video_url]" 
                    	value="<?= isset($thisItem['PageDetail']['video_url']) ? $thisItem['PageDetail']['video_url'] : '';?>" 
                        style="width:400px;" />
               	</td>
            </tr>-->
            
            <?php if (isset($featureBannerText) && count($featureBannerText) > 0) { ?>
        	<tr valign="top">
            	<td style="padding-top:10px;">Set up feature banners</td>
                <td style="padding-top:10px;">
                	<table cellpadding="0" cellspacing="0">
                    <?php
						for($i=0; $i<4; $i++) {
							$index = $i + 1;
					?>
                    	<tr>
                            <td>
                                <input type="hidden" name="feature_banner_id[]" class="home-banner-id" value="<?=$featureBannerText[$i]['banner_id'];?>" />
                            	<input type='hidden' name='feature_banner_images[]' id='feature_banner_<?=$index;?>' 
                                  	value="<?=${"featureBanner" . $index};?>">
								<div id="feature-banner-<?=$index;?>" class="image-section image-section-feature"></div>
							</td>
                            <td valign="top">
                                <label>Banner <?=$index;?> Link: </label>
                                <input type="text" name="feature_banner_urls[]" 
                                	value="<?=isset($featureBannerUrls[$i]) ? $featureBannerUrls[$i] : '';?>" 
                                    style="width:400px;" />
                            </td>
                        </tr>
                    <?php
						}
					?>
                    </table>
    			</td>
        	</tr>
            <?php } ?>
            
      	</table>
    </td>
</tr>
<?php } ?>

<tr>
<td>Page Name:</td>
<td><input type='text' id='page_name' name='data[PageDetail][name]' value="<?=@$thisItem['PageDetail']['name'];?>">
<span class='msg_note'>required</span>
</td>
<td class="form_error">
	<?=isset($errors['PageDetail']['name']) ? $errors['PageDetail']['name'] : '';?>
</td>
</tr>

<tr>
<td>Page Alias:</td>
<td><input type='text' id='page_alias' name='data[PageDetail][alias]' value="<?=@$thisItem['PageDetail']['alias'];?>">
<span class='msg_note'>required (will be shown on url)</span>
</td>
<td class="form_error">
	<?=isset($errors['PageDetail']['alias']) ? $errors['PageDetail']['alias'] : '';?>
</td>
</tr>

<tr bgcolor="#FFD5FF">
    <td valign="top">Set up Page Top Banners</td>
    <td colspan="2">
        <table cellpadding="0" cellspacing="0" id="homebanner_list">
            <tr>
                <td>
                    <button id="btn_homebanner_add">Add</button>
                </td>
            </tr>
        </table>
    </td>
</tr>

<tr>
<td>Page Template:</td>
<td><select name="data[Page][page_template_id]" id="page_template"><?=$tplOptions;?></select>
</td>
<td class="form_error">
	<?=isset($errors['Page']['page_template_id']) ? $errors['Page']['page_template_id'] : '';?>
</td>
</tr>

<tr bgcolor="#CCCCCC">
<td>Priority:</td>
<td><input type='text' name='data[PageDetail][priority]' value="<?=@$thisItem['PageDetail']['priority'];?>">
<span class='msg_note'>This will affect menu display order</span>
</td>
<td class="form_error">
	<?=isset($errors['PageDetail']['priority']) ? $errors['PageDetail']['priority'] : '';?>
</td>
</tr>

<?php if (!$isHomePage) { ?>
<tr bgcolor="#FFD5FF" id="service_images_wrap" style="<?=$templateId == PAGE_TEMPLATE_SERVICE ? '' : 'display:none';?>">
	<td valign="top">Set up Service Images</td>
    <td colspan="2">
        <table cellpadding="0" cellspacing="0" id="service_images_list">
            <tr>
                <td>
                    <button id="btn_service_add">Add</button>
                </td>
            </tr>
        </table>
    </td>
</tr>
<?php } ?>

<tr>
<td valign="top">Content:<br /> 
<a href="javascript:showEditor('<?='htmleditor/cmsedit.php';?>','content_editor');" onfocus='this.blur();'><b>Html Editor</b></a></td>
<td><textarea id="content_editor" name="data[PageDetail][content]" cols="40" rows="5"><?=@$thisItem['PageDetail']['content'];?></textarea></td>
<td class="form_error">
</td>
</tr>

<tr>
<td valign="top">External URL:</td>
<td colspan="2"><input type="text" name="data[PageDetail][url]" value="<?=@$thisItem['PageDetail']['url'];?>">
<span class='msg_note'>Note: if set up this, then the page will redirect to this url. It could be internal url as well.</span>
</td>
</tr>

<tr>
<td valign="top">URL Target:</td>
<td colspan="2">
<select name="data[PageDetail][url_target]" id="url_target"><?=$targetOptions;?></select>
<span class='msg_note'></span>
</td>
</tr>


</table>
</td></tr>
</table>
</form>

<script language="javascript" type="text/javascript" src="/js/jquery/jquery.my.mediabox.js"></script>
<script language="javascript" type="text/javascript">
<?php if ($isHomePage === true) { ?>
	$("#upload_image_strip").show();
<?php } else { ?>
	$("#upload_image_strip").hide();
<?php } ?>

<?php if(isset($thisItem['Page']['id']) && !empty($thisItem['Page']['id'])) { ?>
	/*$("#upload_image_strip").mediabox({
		modelId   : "<?=$thisItem['Page']['id'];?>",
		modelName : "page",
		width: 550,
		height: 400,
		fileInputSize: 15,
		linkModel: true,
		note : "(Note: The best image dimension is 600 x 260)"
	});*/
<?php } else { ?>
	//$("#upload_image_strip").mediabox({modelName : "page"});
<?php } ?>

	$("#chk_is_homepage").click(function() {
		if ($(this).is(':checked')) {
			$("#upload_image_strip").show();
		} else {
			$("#upload_image_strip").hide();
		}
	});

	$(document).ready(function(e) {
		setupAutoFillup('page_alias', 'page_name');
		
		setXhrUploadPhoto({
			photo_parent: 'feature-banner-1',
			wrapper: 'feature-media-uploader-1',
			upload_info: 'Please upload 355 X 441px image.',
			width: 355,
			height: 441,
			url: '<?=SITE_URL_ROOT . $featureBanner1;?>',
			media_center: true,
			callback: function(data) {
				addImageToMedeaCenter(data, function(jData) {
					if (jData.status == 1) {
						$('input#feature_banner_1').val(jData.data.url);
					}
				});
			},
			afterImageSelected: function(imgSrc) {
				$('input#feature_banner_1').val(imgSrc);
			}
		});
		setXhrUploadPhoto({
			photo_parent: 'feature-banner-2',
			wrapper: 'feature-media-uploader-2',
			upload_info: 'Please upload 334 X 199px image.',
			width: 334,
			height: 199,
			url: '<?=SITE_URL_ROOT . $featureBanner2;?>',
			media_center: true,
			callback: function(data) {
				addImageToMedeaCenter(data, function(jData) {
					if (jData.status == 1) {
						$('input#feature_banner_2').val(jData.data.url);
					}
				});
			},
			afterImageSelected: function(imgSrc) {
				$('input#feature_banner_2').val(imgSrc);
			}
		});
		setXhrUploadPhoto({
			photo_parent: 'feature-banner-3',
			wrapper: 'feature-media-uploader-3',
			upload_info: 'Please upload 334 X 199px image.',
			width: 334,
			height: 199,
			url: '<?=SITE_URL_ROOT . $featureBanner3;?>',
			media_center: true,
			callback: function(data) {
				addImageToMedeaCenter(data, function(jData) {
					if (jData.status == 1) {
						$('input#feature_banner_3').val(jData.data.url);
					}
				});
			},
			afterImageSelected: function(imgSrc) {
				$('input#feature_banner_3').val(imgSrc);
			}
		});
		setXhrUploadPhoto({
			photo_parent: 'feature-banner-4',
			wrapper: 'feature-media-uploader-4',
			upload_info: 'Please upload 445 X 240px image.',
			width: 445,
			height: 240,
			url: '<?=SITE_URL_ROOT . $featureBanner4;?>',
			media_center: true,
			callback: function(data) {
				addImageToMedeaCenter(data, function(jData) {
					if (jData.status == 1) {
						$('input#feature_banner_4').val(jData.data.url);
					}
				});
			},
			afterImageSelected: function(imgSrc) {
				$('input#feature_banner_4').val(imgSrc);
			}
		});
		
		$('#btn_homebanner_add').unbind('click').click(function(e) {
			var $this = $(this),
				$parent = $this.parent().parent(),
				$siblings = $parent.siblings('.one-item'),
				num = $siblings.length,
				bid = 'home-banner-' + num,
				wrapId = 'home-banner-wrap-' + num,
				oneBanner = '<tr class="one-item"><td>' + 
							'	<input type="hidden" name="top_banner_id[]" class="home-banner-id" value="" />' + 
							'	<input type="text" name="top_banner_slogs[]" class="home-banner-slog banner-input" value="" placeholder="enter banner slog" /><br>' +
							'	<input type="text" name="top_banner_links[]" class="home-banner-link banner-input" value="" placeholder="enter banner link name" /><br>' +
							'	<input type="text" name="top_banner_titles[]" class="home-banner-title banner-input" value="" placeholder="enter banner title" /><br>' +
							'	<input type="text" name="top_banner_urls[]" class="home-banner-url banner-input" value="" placeholder="enter url here" />' +
							'	<div id="' + bid + '" class="home-banner-one image-section"></div>' +
							'	<input type="hidden" name="top_banner_images[]" class="home-banner-img" value="" />' +
							'	<button class="home-banner-del">Delete</button>' +
							'</td></tr>';
							
			$parent.after(oneBanner);
			setXhrUploadPhoto({
				photo_parent: bid,
				wrapper: wrapId,
				upload_info: 'Please upload 870 X 362px image.',
				width: 870,
				height: 362,
				url: '',
				media_center: true,
				callback: function(data) {
					addImageToMedeaCenter(data, function(jData) {
						if (jData.status == 1) {
							$('#' + bid).next('.home-banner-img').val(jData.data.url);
						}
					});
				},
				afterImageSelected: function(imgSrc) {
					$('#' + bid).next('.home-banner-img').val(imgSrc);
				}
			});
			
			return false;
		});
		
		$('#homebanner_list, #service_images_list').off('click').on('click', 'tr.one-item .home-banner-del', function(e) {
			var $this = $(this),
				$tr = $this.parent().parent();
				
			$tr.remove();
		});
		
		$('#btn_service_add').unbind('click').click(function(e) {
			var $this = $(this),
				$parent = $this.parent().parent(),
				$siblings = $parent.siblings('.one-item'),
				num = $siblings.length,
				bid = 'service-banner-' + num,
				wrapId = 'service-banner-wrap-' + num,
				oneBanner = '<tr class="one-item"><td>' + 
							'	<input type="hidden" name="feature_banner_id[]" class="home-banner-id" value="" />' + 
							'	<input type="text" name="feature_banner_urls[]" class="home-banner-url" value="" placeholder="enter url here" /><br>' +
							'	<input type="text" name="feature_banner_alts[]" class="home-banner-alt" value="" placeholder="enter image title text" /><br>' +
							'	<input type="text" name="feature_banner_text[]" class="home-banner-text" value="" placeholder="enter image hover text" />' +
							'	<div id="' + bid + '" class="home-banner-one image-section"></div>' +
							'	<input type="hidden" name="feature_banner_images[]" class="home-banner-img" value="" />' +
							'	<button class="home-banner-del">Delete</button>' +
							'</td></tr>';
							
			$parent.after(oneBanner);
			setXhrUploadPhoto({
				photo_parent: bid,
				wrapper: wrapId,
				upload_info: 'Please upload 1024 X 388px image.',
				width: 1024,
				height: 254,
				url: '',
				media_center: true,
				callback: function(data) {
					addImageToMedeaCenter(data, function(jData) {
						if (jData.status == 1) {
							$('#' + bid).next('.home-banner-img').val(jData.data.url);
						}
					});
				},
				afterImageSelected: function(imgSrc) {
					$('#' + bid).next('.home-banner-img').val(imgSrc);
				}
			});
			
			return false;
		});
		
		$('#page_template').change(function(e) {
			var tpl = $(this).val();
			if (tpl == PAGE_TEMPLATE_SERVICE) { //service template
				$('#service_images_wrap').show();
			} else {
				$('#service_images_wrap').hide();
			}
		});
		
		<?php
			foreach($top_banner_images as $key => $imgSrc) {
		?>
				$('#btn_homebanner_add').trigger('click');
				$('#home-banner-<?=$key;?> .photo-crop').html('<img src="<?=SITE_URL_ROOT . $imgSrc;?>" />');
				$('#home-banner-<?=$key;?>').next('input.home-banner-img').val('<?=$imgSrc;?>');
		<?php
				if (isset($top_banner_urls[$key])) {
		?>
					$('#home-banner-<?=$key;?>').siblings('input.home-banner-url').val('<?=$top_banner_urls[$key];?>');
		<?php
				}
				if (isset($top_banner_text[$key])) {
		?>
					$('#home-banner-<?=$key;?>').siblings('input.home-banner-id').val('<?=$top_banner_text[$key]['banner_id'];?>');
					$('#home-banner-<?=$key;?>').siblings('input.home-banner-slog').val('<?=$top_banner_text[$key]['banner_slog'];?>');
					$('#home-banner-<?=$key;?>').siblings('input.home-banner-link').val('<?=$top_banner_text[$key]['link_name'];?>');
					$('#home-banner-<?=$key;?>').siblings('input.home-banner-title').val('<?=$top_banner_text[$key]['banner_title'];?>');
		<?php
				}
			}

			//if ($templateId == PAGE_TEMPLATE_SERVICE) {
		?>
		<?php		
				foreach($featureBannerImages as $key => $imgSrc) {
		?>
					$('#btn_service_add').trigger('click');
					$('#service-banner-<?=$key;?> .photo-crop').html('<img src="<?=SITE_URL_ROOT . $imgSrc;?>" />');
					$('#service-banner-<?=$key;?>').next('input.home-banner-img').val('<?=$imgSrc;?>');
		<?php
					if (isset($featureBannerUrls[$key])) {
		?>	
						$('#service-banner-<?=$key;?>').siblings('input.home-banner-url').val('<?=$featureBannerUrls[$key];?>');
		<?php
					}
					if (isset($featureBannerText)) {
		?>
						$('#service-banner-<?=$key;?>').siblings('input.home-banner-alt').val('<?=$featureBannerText[$key]['banner_title'];?>');
						$('#service-banner-<?=$key;?>').siblings('input.home-banner-text').val('<?=$featureBannerText[$key]['hover_text'];?>');
						$('#service-banner-<?=$key;?>').siblings('input.home-banner-id').val('<?=$featureBannerText[$key]['banner_id'];?>');
		<?php
					}
				}
			//}
		?>
		
	});
</script>