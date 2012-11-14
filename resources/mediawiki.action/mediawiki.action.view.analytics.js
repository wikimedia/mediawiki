/**
 * Send a quick API request to log views for analytics
 * purposes. This will only be included if anlytics is enabled and
 * the user has not turned on DNT in either their browser or
 * preferences.
 */
( function ( mw, $ ) {
	$( document ).ready( function() {
		var api = new mw.Api();
		api.post( {
			action: 'view',
			title: mw.util.getParamValue( 'title' ),
			client: $.client.profile()
		} );
	} );
}( mediaWiki, jQuery ) );
