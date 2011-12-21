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
		 * @param error {Function} callback if error (optional)
		 * @return {jqXHR}
		 */
		parse: function( wikiText, success, error ) {
			var params = {
					text: wikiText,
					action: 'parse'
				},
				ok = function( data ) {
					if ( data && data.parse && data.parse.text && data.parse.text['*'] ) {
						success( data.parse.text['*'] );
					}
				};
			return this.get( params, ok, error );
		}

	} );

} )( jQuery, mediaWiki );
