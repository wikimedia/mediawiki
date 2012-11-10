/**
 *  CLDR related utility methods.
 */
( function ( mw ) {
	'use strict';

	var cldr = {
		/**
		 * For the number, get the plural for index
		 * In case none of the rules passed, we return pluralRules.length
		 * That means it is the "other" form.
		 * @param number
		 * @param {Array} pluralRules
		 * @return {number} plural form index
		 */
		getPluralForm: function ( number, pluralRules ) {
			var i;
			for ( i = 0; i < pluralRules.length; i++ ) {
				if ( mw.libs.pluralRuleParser( pluralRules[i], number ) ) {
					break;
				}
			}
			return i;
		}
	};

	mw.cldr = cldr;

}( mediaWiki ) );
