import Page from 'wdio-mediawiki/Page.js';

class HistoryPage extends Page {
	get heading() {
		return $( '#firstHeading' );
	}

	get comment() {
		return $( '#pagehistory .comment' );
	}

	async open( title ) {
		return super.openTitle( title, { action: 'history' } );
	}
}

export default new HistoryPage();
