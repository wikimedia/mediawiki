/*!
 * Add autocomplete suggestions for MediaWiki users.
 */
( function ( mw, $ ) {
	var api, config;

	config = {
		fetch: function ( userInput ) {
			var promise,
				$textbox = this;

			api = api || new mw.Api();

			promise = api.get( {
				action: 'query',
				list: 'allusers',
				// Prefix of list=allusers is case sensitive. Normalise first
				// character to uppercase so that "fo" can yield "Foo".
				auprefix: userInput.charAt( 0 ).toUpperCase() + userInput.slice( 1 )
			} ).done( function ( data ) {
				var users = $.map( data.query.allusers, function ( userObj ) {
					return userObj.name;
				} );
				// Set the results as the autocomplete options
				$textbox.suggestions( { suggestions: users } );
			} );

			this.data( 'autoCompleteApiPromise', promise );
		},
		cancel: function () {
			var promise = this.data( 'autoCompleteApiPromise' );
			if ( promise ) {
				promise.abort();
			}
		}
	};

	$( function () {
		$( '.mw-autocomplete-user' ).suggestions( config );
	} );
}( mediaWiki, jQuery ) );
