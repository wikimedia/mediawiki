'use strict';

const Page = require( 'wdio-mediawiki/Page' );

class WatchablePage extends Page {

	get confirmWatch() { return $( '#mw-content-text button[type="submit"]' ); }

	watch( title ) {
		super.openTitle( title, { action: 'watch' } );
		this.confirmWatch.click();
	}
}

module.exports = new WatchablePage();
