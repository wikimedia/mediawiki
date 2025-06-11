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
	}
}

module.exports = Page;
