'use strict';

const Page = require( 'wdio-mediawiki/Page' );

class RecentChangesPage extends Page {
	get changesList() { return $( '.mw-changeslist' ); }
	get liveUpdates() { return $( '.mw-rcfilters-ui-liveUpdateButtonWidget' ); }
	get titles() { return this.changesList.$$( '.mw-changeslist-title' ); }

	open() {
		super.openTitle( 'Special:RecentChanges', { hidebots: 0 } );
	}

}

module.exports = new RecentChangesPage();
