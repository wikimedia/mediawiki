// / <reference lib="@wikimedia/types" />
/** @module restSearchClient */
/**
 * @typedef {import('./urlGenerator.js').UrlGenerator} UrlGenerator
 */

const fetchJson = require( './fetch.js' );

/**
 * @typedef {Object} RestResponse
 * @property {RestResult[]} pages
 */

/**
 * @typedef {Object} SearchResponse
 * @property {string} query
 * @property {SearchResult[]} results
 */

/**
 * Nullish coalescing operator (??) helper
 *
 * @param {any} a
 * @param {any} b
 * @return {any}
 */
function nullish( a, b ) {
	return ( a !== null && a !== undefined ) ? a : b;
}

/**
 * @param {UrlGenerator} urlGeneratorInstance
 * @param {string} query
 * @param {RestResponse} restResponse
 * @param {boolean} showDescription
 * @return {SearchResponse}
 */
function adaptApiResponse( urlGeneratorInstance, query, restResponse, showDescription ) {
	return {
		query,
		results: restResponse.pages.map( ( page, index ) => {
			const thumbnail = page.thumbnail;
			return {
				id: page.id,
				value: page.id || -( index + 1 ),
				label: page.title,
				key: page.key,
				title: page.title,
				description: showDescription ? page.description : undefined,
				url: urlGeneratorInstance.generateUrl( page ),
				thumbnail: thumbnail ? {
					url: thumbnail.url,
					width: nullish( thumbnail.width, undefined ),
					height: nullish( thumbnail.height, undefined )
				} : undefined
			};
		} )
	};
}

/**
 * @typedef {Object} AbortableSearchFetch
 * @property {Promise<SearchResponse>} fetch
 * @property {Function} abort
 */

/**
 * @callback fetchByTitle
 * @param {string} query The search term.
 * @param {number} [limit] Maximum number of results.
 * @param {boolean} [showDescription] Whether descriptions should be added to the results.
 * @return {AbortableSearchFetch}
 */

/**
 * @callback loadMore
 * @param {string} query The search term.
 * @param {number} offset The number of search results that were already loaded.
 * @param {number} [limit] How many further search results to load (at most).
 * @param {boolean} [showDescription] Whether descriptions should be added to the results.
 * @return {AbortableSearchFetch}
 */

/**
 * @typedef {Object} SearchClient
 * @property {fetchByTitle} fetchByTitle
 * @property {loadMore} [loadMore]
 */

/**
 * @param {string} searchApiUrl
 * @param {UrlGenerator} urlGeneratorInstance
 * @return {SearchClient}
 */
function restSearchClient( searchApiUrl, urlGeneratorInstance ) {
	return {
		/**
		 * @type {fetchByTitle}
		 */
		fetchByTitle: ( q, limit = 10, showDescription = true ) => {
			const params = { q, limit: limit.toString() };
			const search = new URLSearchParams( params );
			const url = `${ searchApiUrl }/v1/search/title?${ search.toString() }`;
			const result = fetchJson( url, {
				headers: {
					accept: 'application/json'
				}
			} );
			const searchResponsePromise = result.fetch
				.then( ( /** @type {RestResponse} */ res ) => adaptApiResponse(
					urlGeneratorInstance, q, res, showDescription
				) );
			return {
				abort: result.abort,
				fetch: searchResponsePromise
			};
		}
	};
}

module.exports = restSearchClient;
