import Page from 'wdio-mediawiki/Page.js';

class RecentChangesPage extends Page {
	get changesList() {
		return $( '.mw-changeslist' );
	}

	get liveUpdates() {
		return $( '.mw-rcfilters-ui-liveUpdateButtonWidget' );
	}

	get titles() {
		return this.changesList.$$( '.mw-changeslist-title' );
	}

	async open() {
		return super.openTitle( 'Special:RecentChanges', { hidebots: 0 } );
	}

}

export default new RecentChangesPage();
