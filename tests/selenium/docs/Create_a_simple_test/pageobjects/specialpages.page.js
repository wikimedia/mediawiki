// Example code for Selenium/Getting Started/Create a simple test
// https://www.mediawiki.org/wiki/Selenium/Getting_Started/Create_a_simple_test

import Page from 'wdio-mediawiki/Page.js';

class SpecialPages extends Page {

	get edit() {
		return $( '#ca-edit a[accesskey="e"]' );
	}

	async open() {
		return super.openTitle( 'Special:SpecialPages' );
	}

}
export default new SpecialPages();
