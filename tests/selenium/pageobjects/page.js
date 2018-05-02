/**
 * Based on http://webdriver.io/guide/testrunner/pageobjects.html
 */

class Page {
	open( path ) {
		browser.url( browser.options.baseUrl + '/index.php?title=' + path );
	}
}

module.exports = Page;
