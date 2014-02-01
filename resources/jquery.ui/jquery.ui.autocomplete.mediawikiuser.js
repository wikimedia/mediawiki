/**
 * Add autocomplete suggestions for MediaWiki users.
 */

$.fn.autocompleteMediaWikiUser = function () {
	var jq = this;
	this.autocomplete( {
		source: function( request, response ) {
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
		},
		select: function ( event, ui ) {
			jq.val( ui.term );
		}
	} );
};

( function ( $ ) {
	$( function () {
		$( '.mw-autocomplete-user' ).autocompleteMediaWikiUser();
	} );
}( jQuery ) );
