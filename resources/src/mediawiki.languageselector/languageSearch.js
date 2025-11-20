/**
 * Creates a language search client that fetches language data from the MediaWiki API
 *
 * @param {string} [apiUrl] - The base API URL (e.g., 'https://en.wikipedia.org/w/api.php')
 * @return {Object} Language search client with search method
 */
function languageSearchClient( apiUrl ) {
	return {
		/**
		 * Search for languages matching the given query
		 *
		 * @param {string} search - The search query string
		 * @return {Promise<{languagesearch: Object}>} Promise that resolves with the search results
		 */
		searchLanguages: ( search ) => {
			const api = new mw.Api( apiUrl ? { ajax: { url: apiUrl } } : undefined );
			return new Promise( ( resolve, reject ) => {
				api.get( {
					action: 'languagesearch',
					format: 'json',
					formatversion: '2',
					search: search
				} ).then( resolve, reject );
			} );
		}
	};
}

module.exports = languageSearchClient;
