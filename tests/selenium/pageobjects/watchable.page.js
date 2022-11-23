'use strict';

const Page = require( 'wdio-mediawiki/Page' );

class WatchablePage extends Page {

	get confirmWatch() { return $( '#mw-content-text button[type="submit"]' ); }

	async watch( title ) {
		await super.openTitle( title, { action: 'watch' } );
		await this.confirmWatch.click();
	}
}

module.exports = new WatchablePage();
