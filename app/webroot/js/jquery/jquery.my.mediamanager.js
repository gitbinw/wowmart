// JavaScript Document
(function($) {
	$.fn.mediamanager = function (inSettings) {	
		var settings = $.extend({}, $.fn.mediamanager.default_settings, inSettings);
		var thisObj = this;
		
		settings.viewer_id = $(thisObj).attr('id') + '_media';
		
		return this.each(function() {
			var btn    = '<div class="' + settings.button_class + '">' + settings.button_name + '</div>';
			var viewer = '<div id="' + settings.viewer_id + '"></div>';
			$(thisObj).append(btn + viewer);
			$("#" + settings.viewer_id).slideviewer({
				isAjax : true,
				ajaxUrl : settings.ajaxUrl,
				params : settings.params,
				scroll_left_class : 'slider_scroll_left2',
				scroll_right_class : 'slider_scroll_right2',
				effects : 'mousedown'
			});
		});
	};
	
	/*********************Public Access to Properties****************************/
	$.fn.mediamanager.default_settings = {
		button_name: 'Add Images',
		button_class: 'btn_add_media',
		ajaxUrl: '/images/get'
	};
	
	$.fn.mediamanager.refreshViewer = refreshViewer;
	
	function refreshViewer() {
		$("#" + settings.viewer_id).slideviewer({
				isAjax : true,
				ajaxUrl : settings.ajaxUrl,
				params : settings.params,
				scroll_left_class : 'slider_scroll_left2',
				scroll_right_class : 'slider_scroll_right2',
				effects : 'mousedown'
			});
	}
	
})(jQuery);