import Page from 'wdio-mediawiki/Page.js';

class RecentChangesPage extends Page {
	get changesList() {
		return $( '.mw-changeslist' );
	}

	get liveUpdates() {
		return $( '.mw-rcfilters-ui-liveUpdateButtonWidget' );
	}

	title( name ) {
		return this.changesList.$( `=${ name }` );
	}

	async open() {
		return super.openTitle( 'Special:RecentChanges', { hidebots: 0 } );
	}

}

export default new RecentChangesPage();
