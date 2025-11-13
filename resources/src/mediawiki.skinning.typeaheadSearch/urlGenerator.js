/**
 * @typedef {Record<string,string>} UrlParams
 * @param {string} title
 * @param {string} fulltext
 */

/**
 * @callback generateUrl
 * @param {RestResult|SearchResult|string} searchResult
 * @param {UrlParams} [params]
 * @param {string} [articlePath]
 * @return {string}
 */

/**
 * @typedef {Object} UrlGenerator
 * @property {generateUrl} generateUrl
 */

/**
 * Generates URLs for suggestions like those in MediaWiki's mediawiki.searchSuggest implementation.
 *
 * @param {string} articlePath
 * @param {string} searchPageTitle
 * @return {UrlGenerator}
 */
function urlGenerator( articlePath, searchPageTitle = 'Special:Search' ) {
	return {
		/**
		 * @param {RestResult|SearchResult|string} suggestion
		 * @param {UrlParams} params
		 * @return {string}
		 */
		generateUrl(
			suggestion,
			params = {
				title: searchPageTitle
			}
		) {
			if ( typeof suggestion !== 'string' ) {
				suggestion = suggestion.title + ( suggestion.anchor ? `#${ suggestion.anchor }` : '' );
			} else {
				// Add `fulltext` query param to search within pages and for navigation
				// to the search results page (prevents being redirected to a certain
				// article).
				params = Object.assign( {}, params, {
					fulltext: '1'
				} );
			}

			const searchParams = new URLSearchParams(
				Object.assign( {}, params, { search: suggestion } )
			);
			return `${ articlePath }?${ searchParams.toString() }`;
		}
	};
}

/** @module urlGenerator */
module.exports = urlGenerator;
