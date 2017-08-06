$(document).ready(function() {
	var thisGroup = null;
	var thisMenu = null;
	$("#sidebar_main li")
		.mouseover(function(e) {
			e.stopPropagation();
			if (thisGroup != null && thisMenu != null && !hasParent($(this)[0], thisMenu)) {
				$(thisGroup).hide();
			}
			
			$(this).siblings('.hover')
				.removeClass('hover')
				.children('ul').hide();
				
			$(this)
				.addClass('hover')
				.children('ul').show();
			
			thisGroup = $(this).children('ul')[0];
			thisMenu  = $(this)[0];
		});
		
	$(document)
		.mouseover(function(e) {
			e.stopPropagation();
			if ($(e.target).parents('.menubar').length <= 0) {
				$(thisMenu).children('ul').hide();
				$(thisMenu).parents('ul:not(.menubar)').hide();
				$('#sidebar_main li.hover').removeClass('hover');
			}
		})

});

function hasParent(thisObj, thisMenu) {
	var flag = false;
	$(thisObj).parents().each(function(i, val) {
		if (val == thisMenu) {
			flag = true;
		}
	});
	return flag;
}
