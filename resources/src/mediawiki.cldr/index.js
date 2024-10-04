( function () {
	'use strict';

	/**
	 * Namespace for CLDR-related utility methods.
	 * Provided by the `mediawiki.cdlr` ResourceLoader module.
	 *
	 * @namespace mw.cldr
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
			const pluralRuleParser = require( 'mediawiki.libs.pluralruleparser' );
			let i;
			for ( i = 0; i < pluralRules.length; i++ ) {
				if ( pluralRuleParser( pluralRules[ i ], number ) ) {
					break;
				}
			}
			return i;
		}
	};

}() );
