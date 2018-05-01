'use strict';
const Page = require( './page' );

class PreferencesPage extends Page {

	// TODO: Remove input#id selectors when non-OOUI mode is removed
	get realName() { return browser.element( 'input#mw-input-wprealname, #mw-input-wprealname .oo-ui-inputWidget-input' ); }
	get save() { return browser.element( 'input#prefcontrol, #prefcontrol .oo-ui-buttonElement-button' ); }

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
