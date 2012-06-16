/**
 *  CLDR related utility methods
 */
(function(mw) {
	"use strict";

	var cldr = {
		/**
		 * For the number, get the plural for index
		 * @param number
 		 * @param pluralRules
 		 * @return plural form index
		 */
		getPluralForm : function( number, pluralRules){
			var pluralFormIndex = 0;
			for( var pluralFormIndex = 0; pluralFormIndex < pluralRules.length; pluralFormIndex++){
				if( pluralRuleParser( pluralRules[pluralFormIndex], count ) ) {
					break;
				}
			}
			return pluralFormIndex;
		}
	};
	mw.cldr = cldr;
})(mediaWiki);