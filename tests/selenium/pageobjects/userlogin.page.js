'use strict';
const Page = require( './page' );

class UserLoginPage extends Page {

	get username() { return browser.element( '#wpName1' ); }
	get password() { return browser.element( '#wpPassword1' ); }
	get loginButton() { return browser.element( '#wpLoginAttempt' ); }
	get userPage() { return browser.element( '#pt-userpage' ); }

	open() {
		super.open( 'Special:UserLogin' );
	}

	login( username, password ) {
		this.open();
		this.username.setValue( username );
		this.password.setValue( password );
		this.loginButton.click();
	}

	loginAdmin() {
		this.login( browser.options.username, browser.options.password );
	}

}
module.exports = new UserLoginPage();
