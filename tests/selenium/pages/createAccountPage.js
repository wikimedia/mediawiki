/* global browser */
var Page = require( './page' ),
	createAccountPage = Object.create( Page, {

		username: { get: function () { return browser.element( '#wpName2' ); } },
		password: { get: function () { return browser.element( '#wpPassword2' ); } },
		confirmPassword: { get: function () { return browser.element( '#wpRetype' ); } },
		create: { get: function () { return browser.element( '#wpCreateaccount' ); } },
		heading: { get: function () { return browser.element( '#firstHeading' ); } },

		open: { value: function() {
			Page.open.call( this, 'Special:CreateAccount' );
		} },

		createAccount: { value: function( username, password ) {
			this.open();
			this.username.setValue( username );
			this.password.setValue( password );
			this.confirmPassword.setValue( password );
			this.create.click();
		} }

	} );
module.exports = createAccountPage;
