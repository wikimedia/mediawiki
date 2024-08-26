import Page from 'wdio-mediawiki/Page.js';

class WatchlistPage extends Page {
	get titles() {
		return $( '.mw-changeslist' )
			.$$( '.mw-changeslist-line .mw-title' );
	}

	async open() {
		return super.openTitle( 'Special:Watchlist' );
	}

}

export default new WatchlistPage();
