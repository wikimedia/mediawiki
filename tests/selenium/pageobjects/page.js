// From http://webdriver.io/guide/testrunner/pageobjects.html
'use strict';
class Page {

	get usermessage() { return browser.element( 'div.usermessage' ); }

	constructor() {
		this.title = 'My Page';
	}
	open( path ) {
		browser.url( '/index.php?title=' + path );
	}
}
module.exports = Page;
