/**
 * Base language object with methods for storing and getting
 * language data.
 */
( function( $, mw ) {

var language = {
	/**
	 * @var data {Object} Language related data (keyed by language,
	 * contains instances of mw.Map).
	 * @example Set data
	 * <code>
	 *     // Override, extend or create the language data object of 'nl'
	 *     mw.language.setData( 'nl', 'myKey', 'My value' );
	 * </code>
	 * @example Get GrammarForms data for language 'nl':
	 * <code>
	 *     var grammarForms = mw.language.getData( 'nl', 'grammarForms' );
	 * </code>
	 */
	data: {},

	/**
	 * Convenience method for retreiving language data by language code and data key,
	 * covering for the potential inexistance of a data object for this langiage.
	 * @param langCode {String}
	 * @param dataKey {String}
	 * @return {mixed} Value stored in the mw.Map (or undefined if there is no map for
	   the specified langCode).
	 */
	getData: function ( langCode, dataKey ) {
		var langData = language.data;
		if ( langData[langCode] instanceof mw.Map ) {
			return langData[langCode].get( dataKey );
		}
		return undefined;
	},

	/**
	 * Convenience method for setting language data by language code and data key.
	 * Creates a data object if there isn't one for the specified language already.
	 * @param langCode {String}
	 * @param dataKey {String}
	 * @param value {mixed}
	 */
	setData: function ( langCode, dataKey, value ) {
		var langData = language.data;
		if ( !( langData[langCode] instanceof mw.Map ) ) {
			langData[langCode] = new mw.Map();
		}
		langData[langCode].set( dataKey, value );
	}
}
mw.language = language;

} )( jQuery, mediaWiki );
