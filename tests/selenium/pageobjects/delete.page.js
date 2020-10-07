'use strict';

const Page = require( 'wdio-mediawiki/Page' );

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
}

module.exports = new DeletePage();
