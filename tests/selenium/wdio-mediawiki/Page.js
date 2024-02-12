'use strict';

const querystring = require( 'querystring' );

/**
 * Based on https://webdriver.io/docs/pageobjects
 */
class Page {

	/**
	 * Navigate the browser to a given page.
	 *
	 * @since 1.0.0
	 * @see <https://webdriver.io/docs/api/browser/url>
	 * @param {string} title Page title
	 * @param {Object} [query] Query parameter
	 * @param {string} [fragment] Fragment parameter
	 * @return {Promise<void>}
	 */
	async openTitle( title, query = {}, fragment = '' ) {
		query.title = title;
		await browser.url(
			browser.options.baseUrl + '/index.php?' +
			querystring.stringify( query ) +
			( fragment ? ( '#' + fragment ) : '' )
		);
		// Wait for the page to be fully loaded. TODO: This can be replaced by the `wait` option to
		// browser.url in webdriverio 9 (T363704).
		await browser.waitUntil(
			async () => ( await browser.execute( () => document.readyState ) ) === 'complete',
			{
				timeout: 10 * 1000,
				timeoutMsg: 'Page did not load in time'
			}
		);
	}
}

module.exports = Page;
