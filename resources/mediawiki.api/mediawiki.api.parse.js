/**
 * mw.Api methods for parsing wikitext.
 */
( function ( mw, $ ) {

	$.extend( mw.Api.prototype, {
		/**
		 * Convinience method for 'action=parse'. Parses wikitext into HTML.
		 *
		 * @param wikiText {String}
		 * @param ok {Function} [optional] deprecated (success callback)
		 * @param err {Function} [optional] deprecated (error callback)
		 * @return {jQuery.Promise}
		 */
		parse: function ( wikiText, ok, err ) {
			var apiDeferred = $.Deferred();

			// Backwards compatibility (< MW 1.20)
			if ( ok ) {
				apiDeferred.done( ok );
			}
			if ( err ) {
				apiDeferred.fail( err );
			}

			this.get( {
					action: 'parse',
					text: wikiText
				} )
				.done( function ( data ) {
					if ( data.parse && data.parse.text && data.parse.text['*'] ) {
						apiDeferred.resolve( data.parse.text['*'] );
					}
				} )
				.fail( apiDeferred.reject );

			// Return the promise
			return apiDeferred.promise();
		}
	} );

}( mediaWiki, jQuery ) );
