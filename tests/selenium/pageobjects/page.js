// From http://webdriver.io/guide/testrunner/pageobjects.html
'use strict';
export default class Page {
	open( path ) {
		browser.url( '/index.php?title=' + path );
	}
}
