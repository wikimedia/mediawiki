/*!
 * Add autocomplete suggestions for categories.
 */
( function ( mw, $ ) {
	var api, config;

	config = {
		fetch: function ( categoryInput, response ) {
			var node = this[0],
				request;

			api = api || new mw.Api();

			request = api.getCategoriesByPrefix( categoryInput )
			.done( response );

			$.data( node, 'request', request );
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
		$( '.mw-autocomplete-category' ).suggestions( config );
	} );
}( mediaWiki, jQuery ) );
