const Page = require( 'wdio-mediawiki/Page' );

class RestorePage extends Page {
	get reason() { return $( '#wpComment' ); }
	get submit() { return $( '#mw-undelete-submit' ); }
	get displayedContent() { return $( '#mw-content-text' ); }

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
