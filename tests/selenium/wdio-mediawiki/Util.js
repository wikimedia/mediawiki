'use strict';

module.exports = {
	/**
	 * Generate a random number string with some additional extended ASCII.
	 *
	 * @param {string} prefix A prefix to appply to the generated output.
	 * @return {string}
	 */
	getTestString( prefix = '' ) {
		return prefix + Math.random().toString() + '-Iñtërnâtiônàlizætiøn';
	},

	/**
	 * Wrapper for running mw.Api.get() in the browser
	 *
	 * @param {Object} request The name of the module to wait for
	 * @return {Object|false} The raw response, or false if the request failed.
	 */
	async getMWApiResponse( request ) {
		const result = await browser.execute( ( query ) => {
			if ( typeof mw !== 'undefined' ) {
				return false;
			}

			const api = new mw.Api();

			api.get( query ).then(
				function ( response ) {
					return response;
				},
				function () {
					return false;
				}
			);
		}, request );

		return result;
	},

	/**
	 * Check if a page is (or, if it doesn't yet exist, would be by default) a wikitext content
	 * object, as opposed to e.g. a JSON blob or a content model provided by an extension. This
	 * is useful for when a target of a test requires wikitext behaviour, such as having a talk
	 * page, being subject to redirects, being editable, or similar concerns.
	 *
	 * @param {string} target The name of the page in question
	 * @return {boolean} True if the target is not wikitext.
	 */
	isTargetNotWikitext( target ) {
		const apiResponse = this.getMWApiResponse( { action: 'query', format: 'json', prop: 'info', titles: target } );
		return !(
			apiResponse.query &&
			apiResponse.query.pages &&
			apiResponse.query.pages[ -1 ] &&
			apiResponse.query.pages[ -1 ].contentmodel &&
			apiResponse.query.pages[ -1 ].contentmodel === 'wikitext'
		);
	},

	/**
	 * Wait for a given module to reach a specific state
	 *
	 * @param {string} moduleName The name of the module to wait for
	 * @param {string} moduleStatus 'registered', 'loaded', 'loading', 'ready', 'error', 'missing'
	 * @param {number} timeout The wait time in milliseconds before the wait fails
	 */
	waitForModuleState( moduleName, moduleStatus = 'ready', timeout = 2000 ) {
		browser.waitUntil( () => {
			return browser.execute( ( arg ) => {
				return typeof mw !== 'undefined' &&
					mw.loader.getState( arg.name ) === arg.status;
			}, { status: moduleStatus, name: moduleName } );
		}, timeout, 'Failed to wait for ' + moduleName + ' to be ' + moduleStatus + ' after ' + timeout + ' ms.' );
	}
};
