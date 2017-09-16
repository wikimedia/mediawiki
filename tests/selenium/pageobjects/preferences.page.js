'use strict';
const Page = require( './page' );

class PreferencesPage extends Page {

	get realName() { return browser.element( '#mw-input-wprealname .oo-ui-inputWidget-input' ); }
	get save() { return browser.element( '#prefcontrol .oo-ui-buttonElement-button' ); }

	open() {
		super.open( 'Special:Preferences' );
	}

	changeRealName( realName ) {
		this.open();
		this.realName.setValue( realName );
		this.save.click();
	}

}
module.exports = new PreferencesPage();
