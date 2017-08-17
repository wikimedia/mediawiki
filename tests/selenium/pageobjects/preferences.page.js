'use strict';
const Page = require( './page' );

class PreferencesPage extends Page {

	get existingSignature() { return browser.element( '#mw-htmlform-signature a' ); }
	get newSignature() { return browser.element( '#mw-input-wpnickname' ); }
	get save() { return browser.element( '#prefcontrol' ); }

	open() {
		super.open( 'Special:Preferences' );
	}

	changeSignature( signature ) {
		this.open();
		this.newSignature.setValue( signature );
		this.save.click();
	}

}
module.exports = new PreferencesPage();
