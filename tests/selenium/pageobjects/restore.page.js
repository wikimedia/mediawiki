const Page = require( 'wdio-mediawiki/Page' );

class RestorePage extends Page {
	get reason() { return browser.element( '#wpComment' ); }
	get submit() { return browser.element( '#mw-undelete-submit' ); }
	get displayedContent() { return browser.element( '#mw-content-text' ); }

	open( subject ) {
		super.openTitle( 'Special:Undelete/' + subject );
	}

	restore( subject, reason ) {
		this.open( subject );
		this.reason.setValue( reason );
		this.submit.click();
	}
}

module.exports = new RestorePage();
