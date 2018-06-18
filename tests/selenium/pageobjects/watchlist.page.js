const Page = require( 'wdio-mediawiki/Page' );

class WatchlistPage extends Page {
	get titles() {
		return browser.element( '.mw-changeslist' )
			.$$( '.mw-changeslist-line .mw-title' );
	}

	open() {
		super.openTitle( 'Special:Watchlist' );
	}

}

module.exports = new WatchlistPage();
