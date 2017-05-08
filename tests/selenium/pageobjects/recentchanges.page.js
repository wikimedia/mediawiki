'use strict';
const Page = require( './page' );

class RecentChangesPage extends Page {

	open() {
		super.open( 'Special:RecentChanges' );
	}

	page( name ) {
		return browser.element( `a[title='${name}']` );
	}

}
module.exports = new RecentChangesPage();
