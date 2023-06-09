// / <reference lib="@wikimedia/types" />
/** @module restSearchClient */

const fetchJson = require( './fetch.js' );
const urlGenerator = require( './urlGenerator.js' );

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
 * @param {MwMap} config
 * @param {string} query
 * @param {RestResponse} restResponse
 * @param {boolean} showDescription
 * @return {SearchResponse}
 */
function adaptApiResponse( config, query, restResponse, showDescription ) {
	const urlGeneratorInstance = urlGenerator( config );
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
 * @param {MwMap} config
 * @return {SearchClient}
 */
function restSearchClient( config ) {
	return config.get( 'wgVectorSearchClient', {
		/**
		 * @type {fetchByTitle}
		 */
		fetchByTitle: ( q, limit = 10, showDescription = true ) => {
			const searchApiUrl = config.get( 'wgVectorSearchApiUrl',
				config.get( 'wgScriptPath' ) + '/rest.php'
			);
			const params = { q, limit: limit.toString() };
			const search = new URLSearchParams( params );
			const url = `${searchApiUrl}/v1/search/title?${search.toString()}`;
			const result = fetchJson( url, {
				headers: {
					accept: 'application/json'
				}
			} );
			const searchResponsePromise = result.fetch
				.then( ( /** @type {RestResponse} */ res ) => {
					return adaptApiResponse( config, q, res, showDescription );
				} );
			return {
				abort: result.abort,
				fetch: searchResponsePromise
			};
		}
	} );
}

module.exports = restSearchClient;
