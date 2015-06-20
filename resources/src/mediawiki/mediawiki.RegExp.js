( function ( mw ) {
	/**
	 * @class mw.RegExp
	 */
	mw.RegExp = {
		/**
		 * Escape string for safe inclusion in regular expression
		 *
		 * @since 1.26
		 * @static
		 * @param {string} str String to escape
		 * @return {string} Escaped string
		 */
		escape: function ( str ) {
			return str.replace( /([\\{}()|.?*+\-\^$\[\]])/g, '\\$1' );
		}
	};
}( mediaWiki ) );
