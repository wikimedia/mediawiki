'use strict';

const Page = require( 'wdio-mediawiki/Page' );

class HistoryPage extends Page {
	get heading() { return $( '#firstHeading' ); }
	get comment() { return $( '#pagehistory .comment' ); }

	open( title ) {
		super.openTitle( title, { action: 'history' } );
	}
}

module.exports = new HistoryPage();
