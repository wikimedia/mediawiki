/**
 * Add autocomplete suggestions for MediaWiki users.
 */

( function ( mw, $ ) {
	var autoCompleteUser = {
		fetch: function ( userInput ) {
			var textbox = this;
			this.data(
				'autoCompleteApiPromise',
				( new mw.Api ).get( {
					action: 'query',
					list: 'allusers',
					auprefix: userInput.charAt( 0 ).toUpperCase() + userInput.slice( 1 )
				} ).done( function ( data ) {
					var users = [];
					$.each( data.query.allusers, function ( userId, userObj ) {
						users.push( userObj.name );
					} );
					textbox.suggestions( { suggestions: users } ); // Set the results as the autocomplete options
				} )
			);
		},
		cancel: function () {
			if ( this.data( 'autoCompleteApiPromise' ) ) {
				this.data( 'autoCompleteApiPromise' ).abort();
			}
		}
	};

	$( function () {
		$( '.mw-autocomplete-user' ).suggestions( autoCompleteUser );
	} );
}( mediaWiki, jQuery ) );
