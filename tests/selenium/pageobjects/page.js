// From http://webdriver.io/guide/testrunner/pageobjects.html
'use strict';
class Page {
	open( path ) {
		browser.url( browser.options.baseUrl + '/index.php?title=' + path );
	}
}
module.exports = Page;
