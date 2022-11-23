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

	async delete( title, reason ) {
		await this.open( title );
		await this.reason.setValue( reason );
		await this.submit.click();
	}
}

module.exports = new DeletePage();
