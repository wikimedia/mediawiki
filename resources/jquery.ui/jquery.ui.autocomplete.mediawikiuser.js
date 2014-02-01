/**
 * Add autocomplete suggestions for MediaWiki users.
 * Based on code by Legoktm (mainly from http://jqueryui.com/autocomplete/ and resources/mediawiki/mediawiki.searchSuggest.js)
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
