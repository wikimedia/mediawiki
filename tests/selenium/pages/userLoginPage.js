/* global browser */
var Page = require( './page' ),
	userLoginPage = Object.create( Page, {

		username: { get: function () { return browser.element( '#wpName1' ); } },
		password: { get: function () { return browser.element( '#wpPassword1' ); } },
		loginButton: { get: function () { return browser.element( '#wpLoginAttempt' ); } },
		userPage: { get: function () { return browser.element( '#pt-userpage' ); } },

		open: { value: function() {
			Page.open.call( this, 'Special:UserLogin' );
		} },

		login: { value: function( username, password ) {
			this.open();
			this.username.setValue( username );
			this.password.setValue( password );
			this.loginButton.click();
		} }

	} );
module.exports = userLoginPage;
