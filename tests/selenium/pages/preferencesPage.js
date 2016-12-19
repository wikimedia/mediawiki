/* global browser */
var Page = require( './page' ),
	preferencesPage = Object.create( Page, {

		realName: { get: function () { return browser.element( '#mw-input-wprealname' ); } },
		save: { get: function () { return browser.element( '#prefcontrol' ); } },

		open: { value: function() {
			Page.open.call( this, 'Special:Preferences' );
		} },

		chageRealName: { value: function( realName ) {
			this.open();
			this.realName.setValue( realName );
			this.save.click();
		} }

	} );
module.exports = preferencesPage;
