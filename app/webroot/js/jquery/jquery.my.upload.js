(function($) {
	var options  = {};
	var iframeId = '';
	
	jQuery.fn.upload = function (inOptions) {
		options = jQuery.extend({}, jQuery.fn.upload.default_options, inOptions);
		var formId = jQuery(this).attr('id');
		iframeId   = "ifrm_upload_" + formId;
		
		return this.each(function() {
			jQuery(this).attr( {
				target: iframeId,
				enctype: options.contentType,
				method: options.type,
				action: options.url
			} );
			
			if (jQuery("#" + iframeId).length == 0) addIframe(this);
			if (options.beforeSend) options.beforeSend.apply(this, arguments);
			jQuery("#" + iframeId).load (parseData);
			
			this.submit();
		});
	};
	
	jQuery.fn.upload.default_options = {
		type: 'POST',
		dataType: 'html',
		contentType: 'multipart/form-data',
		beforeSend: function() {},
		success: function(data) {}
	}
	
	function parseData() {
		var data = frames[iframeId].document.getElementsByTagName('body')[0].innerHTML;
		var dtp  = jQuery.trim(options.dataType);
		if ( dtp == 'html' ) {
			data = data;
		} else {
			data = eval( "(" + data + ")" );
		}
		
		if (options.success) options.success.apply(this, [data]);
	}
	
	function addIframe(obj) {
		var ifrm = "<iframe id='" + iframeId + "' name='" + iframeId + "' style='display:none;width:1px;height:1px;'></iframe>";
		jQuery("body").append(ifrm);
	}
})(jQuery);