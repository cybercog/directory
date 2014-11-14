(function( factory ) {
	if ( typeof define === "function" && define.amd ) {

		// AMD. Register as an anonymous module.
		define([
			"jquery"
		], factory );
	} else {

		// Browser globals
		factory( jQuery );
	}
})

(function($){
    $.fn.ajaxPostHelper = function(){
        alert("ajaxPostHelper");
        return this;
    };
});