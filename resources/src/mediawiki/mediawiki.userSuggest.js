/*!
 * Add autocomplete suggestions for names of registered users.
 */
( function ( mw, $ ) {
	var api, config;

	config = {
		fetch: function ( userInput, response, maxRows ) {
			var node = this[0];

			api = api || new mw.Api();

			$.data( node, 'request', api.get( {
				action: 'query',
				list: 'allusers',
				// Prefix of list=allusers is case sensitive. Normalise first
				// character to uppercase so that "fo" may yield "Foo".
				auprefix: userInput.charAt( 0 ).toUpperCase() + userInput.slice( 1 ),
				aulimit: maxRows
			} ).done( function ( data ) {
				var users = $.map( data.query.allusers, function ( userObj ) {
					return userObj.name;
				} );
				response( users );
			} ) );
		},
		cancel: function () {
			var node = this[0],
				request = $.data( node, 'request' );

			if ( request ) {
				request.abort();
				$.removeData( node, 'request' );
			}
		}
	};

	$( function () {
		$( '.mw-autocomplete-user' ).suggestions( config );
	} );
}( mediaWiki, jQuery ) );
