'use strict';

const Page = require( 'wdio-mediawiki/Page' );

class HistoryPage extends Page {
	get heading() {
		return $( '#firstHeading' );
	}

	get comment() {
		return $( '#pagehistory .comment' );
	}

	async open( title ) {
		return super.openTitle( title, { action: 'history' } );
	}
}

module.exports = new HistoryPage();
