// From http://webdriver.io/guide/testrunner/pageobjects.html
'use strict';
class Page {
	open( path ) {
		browser.url( 'w/index.php?title=' + path );
	}
}
module.exports = Page;
