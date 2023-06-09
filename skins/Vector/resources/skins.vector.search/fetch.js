/**
 * @typedef {Object} AbortableFetch
 * @property {Promise<any>} fetch
 * @property {Function} abort
 */

/**
 * @typedef {Object} NullableAbortController
 * @property {AbortSignal | undefined} signal
 * @property {Function} abort
 */
const nullAbortController = {
	signal: undefined,
	abort: () => {} // Do nothing (no-op)
};

/**
 * A wrapper which combines native fetch() in browsers and the following json() call.
 *
 * @param {string} resource
 * @param {RequestInit} [init]
 * @return {AbortableFetch}
 */
function fetchJson( resource, init ) {
	// As of 2020, browser support for AbortController is limited:
	// https://caniuse.com/abortcontroller
	// so replacing it with no-op if it doesn't exist.
	const controller = window.AbortController ?
		new AbortController() :
		nullAbortController;

	const getJson = fetch( resource, Object.assign( {}, init, {
		signal: controller.signal
	} ) ).then( ( response ) => {
		if ( !response.ok ) {
			return Promise.reject(
				'Network request failed with HTTP code ' + response.status
			);
		}
		return response.json();
	} );

	return {
		fetch: getJson,
		abort: () => {
			controller.abort();
		}
	};
}

module.exports = fetchJson;
