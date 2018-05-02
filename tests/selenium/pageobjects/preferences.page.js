const Page = require( 'wdio-mediawiki/Page' );

class PreferencesPage extends Page {
	get realName() { return browser.element( '#mw-input-wprealname' ); }
	get save() { return browser.element( '#prefcontrol' ); }

	open() {
		super.openTitle( 'Special:Preferences' );
	}

	changeRealName( realName ) {
		this.open();
		this.realName.setValue( realName );
		this.save.click();
	}
}

module.exports = new PreferencesPage();
