( function ( mw ) {
	/**
	 * @class mw.RegExp
	 */
	mw.RegExp = {
		/**
		 * Escape string for safe inclusion in regular expression
		 *
		 * The following characters are escaped:
		 *
		 *     \ { } ( ) | . ? * + - ^ $ [ ]
		 *
		 * @since 1.26
		 * @static
		 * @param {string} str String to escape
		 * @return {string} Escaped string
		 */
		escape: function ( str ) {
			return str.replace( /([\\{}()|.?*+\-^$\[\]])/g, '\\$1' ); // eslint-disable-line no-useless-escape
		}
	};
}( mediaWiki ) );
