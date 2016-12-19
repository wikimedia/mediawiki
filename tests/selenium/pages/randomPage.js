/* global browser */
var Page = require( './page' ),
	randomPage = Object.create( Page, {

		content: { get: function () { return browser.element( '#wpTextbox1' ); } },
		displayedContent: { get: function () { return browser.element( '#mw-content-text' ); } },
		heading: { get: function () { return browser.element( '#firstHeading' ); } },
		save: { get: function () { return browser.element( '#wpSave' ); } },

		open: { value: function( name ) {
			Page.open.call( this, name + '&action=edit' );
		} },

		edit: { value: function( name, content ) {
			this.open( name );
			this.content.setValue( content );
			this.save.click();
		} }

	} );
module.exports = randomPage;
