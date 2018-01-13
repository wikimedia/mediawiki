'use strict';
const Page = require( './page' );

class RestorePage extends Page {

	get reason() { return browser.element( '#wpComment' ); }
	get submit() { return browser.element( '#mw-undelete-submit' ); }

	openForRestoring( name ) {
		browser.url( '/index.php?title=Special:Undelete/' + name );
	}

	restore( name, reason ) {
		this.openForRestoring( name );
		this.reason.setValue( reason );
		this.submit.click();
	}

}
module.exports = new RestorePage();
