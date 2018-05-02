const Page = require( 'wdio-mediawiki/Page' );

class HistoryPage extends Page {
	get comment() { return browser.element( '#pagehistory .comment' ); }

	open( title ) {
		super.openTitle( title, { action: 'history' } );
	}
}

module.exports = new HistoryPage();
