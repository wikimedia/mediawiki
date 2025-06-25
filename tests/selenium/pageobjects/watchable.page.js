import Page from 'wdio-mediawiki/Page.js';

class WatchablePage extends Page {

	get confirmWatch() {
		return $( '#mw-content-text button[type="submit"]' );
	}

	async watch( title ) {
		await super.openTitle( title, { action: 'watch' } );
		await this.confirmWatch.click();
	}
}

export default new WatchablePage();
