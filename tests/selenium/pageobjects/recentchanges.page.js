const Page = require( 'wdio-mediawiki/Page' );

class RecentChangesPage extends Page {
	get changesList() { return browser.element( '.mw-changeslist' ); }
	get changesListTitles() { return this.changesList.$$( '.mw-changeslist-title' ); }
	get titles() {
		return this.changesListTitles.map( function ( element ) {
			return element.getText();
		} );
	}

	open() {
		super.openTitle( 'Special:RecentChanges' );
	}

}

module.exports = new RecentChangesPage();
