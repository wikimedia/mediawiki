'use strict';

// We can use .replaceAll since Node.js 15
/* eslint-disable es-x/no-string-prototype-replaceall */

/**
 * JSDoc incorrectly handles dots in 'module' annotations. When the module name includes any dots,
 * it removes everything up to the last dot. This makes no sense because file names (and MediaWiki
 * module names) can contain dots. https://github.com/jsdoc/jsdoc/issues/1157
 *
 * To work around this bug, replace dots with special markers before JSDoc parses the code,
 * and then replace them back after it has parsed the code.
 */
exports.handlers = {
	beforeParse: function ( e ) {
		e.source = e.source.replaceAll( /@(module|exports) .+/g, function ( m ) {
			return m.replaceAll( '.', '(DOT)' );
		} );
	},

	newDoclet: function ( e ) {
		for ( const key in e.doclet ) {
			if ( typeof e.doclet[ key ] === 'string' ) {
				e.doclet[ key ] = e.doclet[ key ].replaceAll( '(DOT)', '.' );
			}
		}
	}
};
