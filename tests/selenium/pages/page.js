// From http://webdriver.io/guide/testrunner/pageobjects.html
/* global browser */
'use strict';
class Page {
	constructor() {
		this.title = 'My Page';
	}
	open( path ) {
		browser.url( '/index.php?title=' + path );
	}
}
module.exports = Page;
