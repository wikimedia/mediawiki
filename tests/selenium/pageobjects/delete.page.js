'use strict';
const Page = require( './page' );

class DeletePage extends Page {

	get reason() { return browser.element( '#wpReason' ); }
	get watch() { return browser.element( '#wpWatch' ); }
	get submit() { return browser.element( '#wpConfirmB' ); }
	get displayedContent() { return browser.element( '#mw-content-text' ); }

	openForDeleting( name ) {
		super.open( name + '&action=delete' );
	}

	delete( name, reason ) {
		this.openForDeleting( name );
		this.reason.setValue( reason );
		this.submit.click();
	}

}
module.exports = new DeletePage();
