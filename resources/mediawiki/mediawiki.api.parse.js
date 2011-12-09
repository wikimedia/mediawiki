// library to assist with action=parse, that is, get rendered HTML of wikitext

( function( mw, $ ) {

	$.extend( mw.Api.prototype, { 
		/**
		 * Parse wikitext into HTML
		 * @param {String} wikitext
		 * @param {Function} callback to which to pass success HTML
		 * @param {Function} callback if error (optional)
		 */
		parse: function( wikiText, useHtml, error ) {
			var params = {
				text: wikiText,
				action: 'parse'
			};
			var ok = function( data ) {
				if ( data && data.parse && data.parse.text && data.parse.text['*'] ) {
					useHtml( data.parse.text['*'] );
				} 
			};
			this.get( params, ok, error );
		}


	} ); // end extend
} )( window.mediaWiki, jQuery );


