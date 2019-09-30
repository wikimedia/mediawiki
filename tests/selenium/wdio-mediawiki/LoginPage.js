const Page = require( './Page' );

class LoginPage extends Page {
	get username() { return $( '#wpName1' ); }
	get password() { return $( '#wpPassword1' ); }
	get loginButton() { return $( '#wpLoginAttempt' ); }
	get userPage() { return $( '#pt-userpage' ); }

	open() {
		super.openTitle( 'Special:UserLogin' );
	}

	login( username, password ) {
		this.open();
		this.username.setValue( username );
		this.password.setValue( password );
		this.loginButton.click();
	}

	loginAdmin() {
		this.login( browser.config.mwUser, browser.config.mwPwd );
	}
}

module.exports = new LoginPage();
