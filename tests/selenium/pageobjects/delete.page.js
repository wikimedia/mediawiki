const Page = require( 'wdio-mediawiki/Page' ),
	Api = require( 'wdio-mediawiki/Api' );

class DeletePage extends Page {
	get reason() { return browser.element( '#wpReason' ); }
	get watch() { return browser.element( '#wpWatch' ); }
	get submit() { return browser.element( '#wpConfirmB' ); }
	get displayedContent() { return browser.element( '#mw-content-text' ); }

	open( title ) {
		super.openTitle( title, { action: 'delete' } );
	}

	delete( title, reason ) {
		this.open( title );
		this.reason.setValue( reason );
		this.submit.click();
	}

	// @deprecated Use wdio-mediawiki/Api#delete() instead.
	apiDelete( name, reason ) {
		return Api.delete( name, reason );
	}
}

module.exports = new DeletePage();
