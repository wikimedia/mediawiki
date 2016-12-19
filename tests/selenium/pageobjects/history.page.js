'use strict';
const Page = require( './page' );

class HistoryPage extends Page {

	get comment() { return browser.element( '#pagehistory .comment' ); }

	open( name ) {
		super.open( name + '&action=history' );
	}

}
module.exports = new HistoryPage();
