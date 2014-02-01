/**
 * Add autocomplete suggestions for MediaWiki users.
 */

( function ( mw, $ ) {
	$.fn.autocompleteMediaWikiUser = function () {
		this.autocomplete( {
			source: function ( request, response ) {
				( new mw.Api ).get( {
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
			}
		} );
	};

	$( function () {
		$( '.mw-autocomplete-user' ).autocompleteMediaWikiUser();
	} );
}( mediaWiki, jQuery ) );
