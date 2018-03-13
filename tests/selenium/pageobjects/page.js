// From http://webdriver.io/guide/testrunner/pageobjects.html
'use strict';
class Page {
	open( path ) {
		let mwScriptPath = process.env.MW_SCRIPT_PATH === undefined ?
			'/w' :
			process.env.MW_SCRIPT_PATH;
		browser.url( mwScriptPath + '/index.php?title=' + path );
	}
}
module.exports = Page;
