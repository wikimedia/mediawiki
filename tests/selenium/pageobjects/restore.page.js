'use strict';
const Page = require( './page' );

class RestorePage extends Page {

	get reason() { return browser.element( '#wpComment' ); }
	get submit() { return browser.element( '#mw-undelete-submit' ); }
	get displayedContent() { return browser.element( '#mw-content-text' ); }

	open( name ) {
		super.open( 'Special:Undelete/' + name );
	}

	restore( name, reason ) {
		this.open( name );
		this.reason.setValue( reason );
		this.submit.click();
	}

}
module.exports = new RestorePage();
