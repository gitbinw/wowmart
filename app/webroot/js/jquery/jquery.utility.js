(function($) {

	/**
	  Author: Arash Karimzadeh
	  
	  [$.namespace( obj )]
	  
	  [Description]
	  This is simply extend jQuery object with new functionalities In addition
	  it always add extend method which is a shortcut for extending the object
	  with new functionalities
	  
	  [important] Never use 'extend' as function or variable name in namespaces
	 **/
	$.namespace = function(obj) {
		if (obj == undefined)
			obj = {};
		return $.extend( {}, obj, {
			extend : function(obj) {
				$.namespace.extend(this, obj);
			}
		});
	};
	/**
	  Author: Arash Karimzadeh
	  
	  [$.namespace.extend( ns, obj )]
	  
	  [Description]
	  This can extend the predefined namespace It also re-enforce the extend
	  method to prevent from users overriding
	 **/
	$.namespace.extend = function(ns, obj) {
		return $.extend(ns, obj, {
			extend : function(obj) {
				$.namespace.extend(this, obj)
			}
		});
	};
	/**
	  Author: Arash Karimzadeh
	  
	  [$.isEvent( obj )]
	  
	  [Description]
	  Check if the object is Event
	 **/
	$.isEvent = function(obj) {
		return (obj.which != undefined);
	};
	/**
	  Author: Arash Karimzadeh
	  
	  [$.isString( obj )]
	  
	  [Description]
	  Check if the object is sting
	 **/
	$.isString = function(obj) {
		return (typeof obj == 'string');
	};

})(jQuery);
