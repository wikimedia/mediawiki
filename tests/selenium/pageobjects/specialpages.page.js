'use strict';
const Page = require( './page' );

class SpecialPages extends Page {

	get edit() { return browser.element( '#ca-edit a[accesskey="e"]' ); }

	open() {
		super.open( 'Special:SpecialPages' );
	}

}
module.exports = new SpecialPages();
