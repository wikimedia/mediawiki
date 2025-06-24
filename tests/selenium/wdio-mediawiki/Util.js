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
	 * @return {Promise<boolean>} True if the target is not wikitext.
	 */
	async isTargetNotWikitext( target ) {
		// First, make sure that the 'mw' object should exist
		await this.waitForModuleState( 'mediawiki.base' );

		// Then, ask the API for the basic 'info' data about the given page
		return browser.executeAsync( ( target_, done ) => {
			mw.loader.using( 'mediawiki.api' ).then( () => {
				const api = new mw.Api();
				api.get( {
					action: 'query', prop: 'info', titles: target_,
					format: 'json', formatversion: 2
				} ).then( ( result ) => {
					// Finally, return whether said page is wikitext (or would be, if it doesn't yet exist)
					done( result.query.pages[ 0 ].contentmodel !== 'wikitext' );
				} );
			} );
		}, target );
	},

	/**
	 * Wait for a given module to reach a specific state
	 *
	 * @param {string} moduleName The name of the module to wait for
	 * @param {string} moduleStatus 'registered', 'loaded', 'loading', 'ready', 'error', 'missing'
	 * @param {number} timeout The wait time in milliseconds before the wait fails
	 */
	async waitForModuleState( moduleName, moduleStatus = 'ready', timeout = 5000 ) {

		// Wait for the mediaWiki object to be availible
		await browser.waitUntil(
			() => browser.execute( () => typeof mw !== 'undefined' ),
			{ timeout, timeoutMsg: 'mw is not availible' }
		);

		// Use the built in using when we wait for modules to become ready
		if ( moduleStatus === 'ready' ) {
			await browser.execute( async ( name ) => mw.loader.using( name ), moduleName );
		} else {
			await browser.waitUntil(
				async () => browser.execute(
					( arg ) => mw.loader.getState( arg.name ) === arg.status,
					{ status: moduleStatus, name: moduleName }
				), {
					timeout: timeout,
					timeoutMsg: 'The module ' + moduleName + ' never reached ' + moduleStatus + ' after ' + timeout + ' ms.'
				}
			);
		}
	}
};
