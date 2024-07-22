// Example code for Selenium/Getting Started/Create a simple test
// https://www.mediawiki.org/wiki/Selenium/Getting_Started/Create_a_simple_test

'use strict';

const Page = require( 'wdio-mediawiki/Page' );

class SpecialPages extends Page {

	get edit() {
		return $( '#ca-edit a[accesskey="e"]' );
	}

	async open() {
		return super.openTitle( 'Special:SpecialPages' );
	}

}
module.exports = new SpecialPages();
