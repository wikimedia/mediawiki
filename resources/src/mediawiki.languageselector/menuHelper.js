/**
 * Convert language data to Codex menu items format
 *
 * @param {Object} languages
 * @param {string[]|undefined} languageCodes Language codes to include; if undefined, include all languages
 * @return {{label: string, value: string}[]}
 */
function computeMenuItems( languages, languageCodes ) {
	if ( languageCodes ) {
		return languageCodes.map( ( code ) => ( {
			label: languages[ code ] || code,
			value: code
		} ) );
	}

	return Object.entries( languages ).map( ( [ code, name ] ) => ( {
		label: name,
		value: code
	} ) );
}

module.exports = {
	computeMenuItems
};
