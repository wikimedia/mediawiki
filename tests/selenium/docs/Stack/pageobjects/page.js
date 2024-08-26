// Example code for Selenium/Explanation/Stack
// https://www.mediawiki.org/wiki/Selenium/Explanation/Stack

'use strict';

class Page {
	async open( path ) {
		await browser.url( path );
	}
}
module.exports = Page;
