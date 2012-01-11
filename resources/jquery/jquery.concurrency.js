/**
 * Base jQuery plugin for Concurrency 
 *
 * @author Rob Moen
 */

(function ( $ ) {
	$.concurrency = {
		/* 
		 * Checkin our checkout an object via API
		 * @param ccaction: checkout, checkin
		 * @param resourcetype: extension specific type (string)
		 * @param record: resource id (int) 
		 * @param callback: function to handle response 
		 */
		check: function( params, callback ) {
			params = $.extend({
				action: 'concurrency',
				token: mw.user.tokens.get( 'editToken' ),
				format: 'json'
			}, params);
				
			return $.ajax( {
				type: 'POST',
				url: mw.util.wikiScript( 'api' ),
				data: params,
				success: function( data ){
					if ( typeof callback == 'function' ){
						callback(data);	
					}
				},
				error: function( data ){
					if ( typeof callback == 'function' ){
						callback(data);	
					}
				},
				dataType: 'json'
			} );
		}
	};	

})( jQuery );