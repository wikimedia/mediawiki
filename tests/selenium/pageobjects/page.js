// From http://webdriver.io/guide/testrunner/pageobjects.html
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
