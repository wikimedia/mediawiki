'use strict';

module.exports = {
	/**
	 * Generate a random number string with some additional extended ASCII.
	 *
	 * @param {string} prefix A prefix to apply to the generated output.
	 * @return {string}
	 */
	getTestString( prefix = '' ) {
		return prefix + Math.random().toString() + '-Iñtërnâtiônàlizætiøn';
	},

	/**
	 * Check if a page is (or, if it doesn't yet exist, would be by default) a wikitext content
	 * object, as opposed to e.g. a JSON blob or a content model provided by an extension. This
	 * is useful for when a target of a test requires wikitext behaviour, such as testing for
	 * having a talk page, being subject to redirects, being editable, or similar concerns.
	 *
	 * @param {string} target The name of the page in question.
	 * @return {boolean} True if the target is not wikitext.
	 */
	async isTargetNotWikitext( target ) {
		// First, make sure that the 'mw' object should exist
		await this.waitForModuleState( 'mediawiki.base' );

		// Then, ask the API for the basic 'info' data about the given page
		const apiResponse = await browser.execute( async ( target_ ) => {

			await mw.loader.using( 'mediawiki.api' );

			const api = new mw.Api();
			return await api.get( {
				action: 'query', prop: 'info', titles: target_,
				format: 'json', formatversion: 2
			} );

		}, target );

		// Finally, return whether said page is wikitext (or would be, if it doesn't yet exist)
		return apiResponse.query.pages[ 0 ].contentmodel !== 'wikitext';
	},

	/**
	 * Wait for a given module to reach a specific state
	 *
	 * @param {string} moduleName The name of the module to wait for
	 * @param {string} moduleStatus 'registered', 'loaded', 'loading', 'ready', 'error', 'missing'
	 * @param {number} timeout The wait time in milliseconds before the wait fails
	 */
	async waitForModuleState( moduleName, moduleStatus = 'ready', timeout = 5000 ) {
		await browser.waitUntil(
			async () => await browser.execute(
				( arg ) => typeof mw !== 'undefined' && mw.loader.getState( arg.name ) === arg.status,
				{ status: moduleStatus, name: moduleName }
			), {
				timeout: timeout,
				timeoutMsg: 'Failed to wait for ' + moduleName + ' to be ' + moduleStatus + ' after ' + timeout + ' ms.'
			}
		);
	}
};
