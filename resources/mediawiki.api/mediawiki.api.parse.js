/**
 * Additional mw.Api methods to assist with API calls related to parsing wikitext.
 */

( function( $, mw ) {

	$.extend( mw.Api.prototype, {
		/**
		 * Convinience method for 'action=parse'. Parses wikitext into HTML.
		 *
		 * @param wikiText {String}
		 * @param success {Function} callback to which to pass success HTML
		 * @param err {Function} callback if error (optional)
		 * @return {jqXHR}
		 */
		parse: function( wikiText, success, err ) {
			var params = {
					text: wikiText,
					action: 'parse'
				},
				ok = function( data ) {
					if ( data.parse && data.parse.text && data.parse.text['*'] ) {
						success( data.parse.text['*'] );
					}
				};
			return this.get( params, { ok: ok, err: err } );
		}

	} );

} )( jQuery, mediaWiki );
