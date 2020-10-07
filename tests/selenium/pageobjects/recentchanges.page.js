'use strict';

const Page = require( 'wdio-mediawiki/Page' );

class RecentChangesPage extends Page {
	get changesList() { return $( '.mw-changeslist' ); }
	get titles() { return this.changesList.$$( '.mw-changeslist-title' ); }

	open() {
		super.openTitle( 'Special:RecentChanges' );
	}

}

module.exports = new RecentChangesPage();
