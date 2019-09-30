const Page = require( 'wdio-mediawiki/Page' ),
	Api = require( 'wdio-mediawiki/Api' );

class DeletePage extends Page {
	get reason() { return $( '#wpReason' ); }
	get watch() { return $( '#wpWatch' ); }
	get submit() { return $( '#wpConfirmB' ); }
	get displayedContent() { return $( '#mw-content-text' ); }

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
