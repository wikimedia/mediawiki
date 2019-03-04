( function () {
	'use strict';

	/**
	 * Namespace for CLDR-related utility methods.
	 *
	 * @class
	 * @singleton
	 */
	mw.cldr = {
		/**
		 * Get the plural form index for the number.
		 *
		 * In case none of the rules passed, we return `pluralRules.length` -
		 * that means it is the "other" form.
		 *
		 * @param {number} number
		 * @param {Array} pluralRules
		 * @return {number} plural form index
		 */
		getPluralForm: function ( number, pluralRules ) {
			var i, pluralRuleParser = require( 'mediawiki.libs.pluralruleparser' );
			for ( i = 0; i < pluralRules.length; i++ ) {
				if ( pluralRuleParser( pluralRules[ i ], number ) ) {
					break;
				}
			}
			return i;
		}
	};

}() );
