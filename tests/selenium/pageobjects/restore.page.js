'use strict';

const Page = require( 'wdio-mediawiki/Page' );

class RestorePage extends Page {
	get reason() { return $( '#wpComment' ); }
	get submit() { return $( '#mw-undelete-submit' ); }
	get displayedContent() { return $( '#mw-content-text' ); }

	open( subject ) {
		super.openTitle( 'Special:Undelete/' + subject );
	}

	async restore( subject, reason ) {
		await this.open( subject );
		await this.reason.setValue( reason );
		await this.submit.click();
	}
}

module.exports = new RestorePage();
