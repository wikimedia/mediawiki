/**
 * Add autocomplete suggestions to Special:Log.
 * Based on code by Legoktm (mainly from http://jqueryui.com/autocomplete/ and resources/mediawiki/mediawiki.searchSuggest.js)
 */

( function ( mw, $ ) {
	$( function () {
		$( '#mw-log-user' ).autocomplete( {
			source: function( request, response ) {
				var api = new mw.Api();
				api.get( {
					action: 'query',
					list: 'allusers',
					auprefix: request.term // This is the current value of the user's input
				} ).done( function ( data ) {
					var users = [];
					$.each( data.query.allusers, function ( userId, userObj ) {
						users.push( userObj.name );
					} );
					response( users ); // Set the results as the autocomplete options
				} );
			},
			select: function ( event, ui ) {
				$( '#mw-log-user' ).val( ui.term );
			}
		} );
	} );
}( mediaWiki, jQuery ) );
