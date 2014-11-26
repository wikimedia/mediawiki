/*!
 * Add autocomplete suggestions for tags.
 */
( function ( mw, $ ) {
	var api, config;

	config = {
		fetch: function ( userInput ) {
			var $textbox = this,
				node = this[0];

			api = api || new mw.Api();

			$.data( node, 'request', api.get( {
				action: 'query',
				list: 'tags',
				tgcontains: userInput
			} ).done( function ( data ) {
				var tags = $.map( data.query.tags, function ( tagObj ) {
					return tagObj.name;
				} );
				// Set the results as the autocomplete options
				$textbox.suggestions( 'suggestions', tags );
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
		$( '.mw-tagfilter-input' ).suggestions( config );
	} );
}( mediaWiki, jQuery ) );
