
var oldAjax = jQuery.ajax;

jQuery.ajax = function( ) {
	var jQXHR = oldAjax.apply( this, arguments );

	// Be sure we got a jQXHR (e.g., not sync)
	if ( jQXHR.promise ) {
		migrateWarnFunc( jQXHR, "success", jQXHR.done,
			"jQXHR.success is deprecated and removed" );
		migrateWarnFunc( jQXHR, "error", jQXHR.fail,
			"jQXHR.error is deprecated and removed" );
		migrateWarnFunc( jQXHR, "complete", jQXHR.always,
			"jQXHR.complete is deprecated and removed" );
	}

	return jQXHR;
};
