/**
 *  CLDR related utility methods
 */
( function( mw ) {
	"use strict";

	var cldr = {
		/**
		 * For the number, get the plural for index
		 * In case none of the rules passed, we return pluralRules.length
		 * That means it is the "other" form.
		 * @param number
		 * @param pluralRules
		 * @return plural form index
		 */
		getPluralForm: function( number, pluralRules ) {
			var pluralFormIndex = 0;
			for ( pluralFormIndex = 0; pluralFormIndex < pluralRules.length; pluralFormIndex++ ) {
				if ( mw.libs.pluralRuleParser( pluralRules[pluralFormIndex], number ) ) {
					break;
				}
			}
			return pluralFormIndex;
		}
	};

	mw.cldr = cldr;
} )( mediaWiki );
