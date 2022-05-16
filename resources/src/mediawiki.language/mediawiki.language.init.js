( function () {
	/**
	 * Base language object with methods related to language support, attempting to mirror some of the
	 * functionality of the Language class in MediaWiki:
	 *
	 *   - storing and retrieving language data
	 *   - transforming message syntax (`{{PLURAL:}}`, `{{GRAMMAR:}}`, `{{GENDER:}}`)
	 *   - formatting numbers
	 *
	 * @class
	 * @singleton
	 */
	mw.language = {
		/**
		 * Language-related data (keyed by language, contains instances of mw.Map).
		 *
		 * Exported dynamically by the ResourceLoaderLanguageDataModule class in PHP.
		 *
		 * To set data:
		 *
		 *     // Override, extend or create the language data object of 'nl'
		 *     mw.language.setData( 'nl', 'myKey', 'My value' );
		 *
		 *     // Set multiple key/values pairs at once
		 *     mw.language.setData( 'nl', { foo: 'X', bar: 'Y' } );
		 *
		 * To get GrammarForms data for language 'nl':
		 *
		 *     var grammarForms = mw.language.getData( 'nl', 'grammarForms' );
		 *
		 * Possible data keys:
		 *
		 *  - `digitTransformTable`
		 *  - `separatorTransformTable`
		 *  - `minimumGroupingDigits`
		 *  - `grammarForms`
		 *  - `pluralRules`
		 *  - `digitGroupingPattern`
		 *  - `fallbackLanguages`
		 *  - `bcp47Map`
		 *  - `languageNames`
		 *
		 * @property {Object}
		 */
		data: {},

		/**
		 * Convenience method for retrieving language data.
		 *
		 * Structured by language code and data key, covering for the potential inexistence of a
		 * data object for this language.
		 *
		 * @param {string} langCode
		 * @param {string} dataKey
		 * @return {Mixed} Value stored in the mw.Map (or `undefined` if there is no map for the
		 *  specified langCode)
		 */
		getData: function ( langCode, dataKey ) {
			var langData = mw.language.data;
			langCode = langCode.toLowerCase();
			if ( langData && langData[ langCode ] instanceof mw.Map ) {
				return langData[ langCode ].get( dataKey );
			}
			return undefined;
		},

		/**
		 * Convenience method for setting language data.
		 *
		 * Creates the data mw.Map if there isn't one for the specified language already.
		 *
		 * @param {string} langCode
		 * @param {string|Object} dataKey Key or object of key/values
		 * @param {Mixed} [value] Value for dataKey, omit if dataKey is an object
		 */
		setData: function ( langCode, dataKey, value ) {
			var langData = mw.language.data;
			langCode = langCode.toLowerCase();
			if ( !( langData[ langCode ] instanceof mw.Map ) ) {
				langData[ langCode ] = new mw.Map();
			}
			if ( arguments.length > 2 ) {
				langData[ langCode ].set( dataKey, value );
			} else {
				langData[ langCode ].set( dataKey );
			}
		}
	};

}() );
