$(function() {
	
	template_center = $.namespace ({
		
		get: function(tplAlias) {
			var htmlTpl = '';
			
			switch(tplAlias) {
				case 'magazine-template-1' : 
					htmlTpl = '<div class="template-wrapper magazine-template-1">' + 
							  '		<div class="template-section template-image template-section-1"></div>' + 
							  '		<div class="template-section template-image template-section-2"></div>' + 
							  '		<div class="template-section template-text template-section-3"></div>' + 
							  '</div>';
					break;
				
				case 'magazine-template-2' : 
					htmlTpl = '<div class="template-wrapper magazine-template-2">' + 
							  '		<div class="template-section template-image template-section-1"></div>' + 
							  '		<div class="template-section template-text template-section-2"></div>' + 
							  '		<div class="template-section template-image template-section-3"></div>' + 
							  '</div>';
					break;
				
				case 'magazine-template-3' : 
					htmlTpl = '<div class="template-wrapper magazine-template-3">' + 
							  '		<div class="template-section template-image template-section-1"></div>' + 
							  '		<div class="template-section template-text template-section-2"></div>' + 
							  '		<div class="template-section template-image template-section-3"></div>' + 
							  '</div>';
					break;
					
				case 'magazine-template-4' : 
					htmlTpl = '<div class="template-wrapper magazine-template-4">' + 
							  '		<div class="template-section template-image template-section-1"></div>' + 
							  '		<div class="template-section template-image template-section-2"></div>' + 
							  '</div>';
					break;
				
				case 'magazine-template-5' : 
					htmlTpl = '<div class="template-wrapper magazine-template-5">' + 
							  '		<div class="template-section template-image template-section-1"></div>' + 
							  '</div>';
					break;
					
				case 'magazine-template-6' : 
					htmlTpl = '<div class="template-wrapper magazine-template-6">' + 
							  '		<div class="template-section template-text template-section-1"></div>' + 
							  '		<div class="template-section template-image template-section-2"></div>' + 
							  '</div>';
					break;
					
				case 'magazine-template-7' : 
					htmlTpl = '<div class="template-wrapper magazine-template-7">' + 
							  '		<div class="template-section template-text template-section-1"></div>' + 
							  '		<div class="template-section template-image template-section-2"></div>' + 
							  '		<div class="template-section template-image template-section-3"></div>' + 
							  '		<div class="template-section template-image template-section-4"></div>' + 
							  '		<div class="template-section template-text template-section-5"></div>' + 
							  '</div>';
					break;
			
				case 'magazine-template-8' : 
					htmlTpl = '<div class="template-wrapper magazine-template-8">' + 
							  '		<div class="template-section template-image template-section-1"></div>' + 
							  '		<div class="template-section template-text template-section-2"></div>' + 
							  '		<div class="template-section template-image template-section-3"></div>' + 
							  '		<div class="template-section template-image template-section-4"></div>' + 
							  '		<div class="template-section template-image template-section-5"></div>' + 
							  '		<div class="template-section template-image template-section-6"></div>' + 
							  '</div>';
					break;
			}
			
			return htmlTpl;
		}
		
	});
	
});