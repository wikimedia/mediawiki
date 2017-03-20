'use strict';
const Page = require( './page' );

class PreferencesPage extends Page {

	get realName() { return browser.element( '#mw-input-wprealname' ); }
	get save() { return browser.element( '#prefcontrol' ); }

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
