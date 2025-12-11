/**
 * Convert language data to Codex menu items format
 *
 * @param {string} searchQuery
 * @param {string[]} searchResults
 * @param {Object} languages
 * @return {{label: string, value: string}[]}
 */
function computeMenuItems( searchQuery, searchResults, languages ) {
	if ( searchQuery && searchQuery.trim().length > 0 ) {
		return searchResults.map( ( code ) => ( {
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
