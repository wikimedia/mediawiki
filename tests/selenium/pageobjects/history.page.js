const Page = require( 'wdio-mediawiki/Page' );

class HistoryPage extends Page {

	get numberOfRevisionsShown() {
		return browser.elements( '#pagehistory > li' ).value.length;
	}

	getComment( commentIndex ) {
		return browser.element( '#pagehistory > li:nth-child(' + commentIndex + ') > .comment' );
	}

	open( title ) {
		super.openTitle( title, { action: 'history' } );
	}
}

module.exports = new HistoryPage();
