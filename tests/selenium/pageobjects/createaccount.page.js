const Page = require( './page' ),
	// https://github.com/Fannon/mwbot
	MWBot = require( 'mwbot' );

class CreateAccountPage extends Page {

	get username() { return browser.element( '#wpName2' ); }
	get password() { return browser.element( '#wpPassword2' ); }
	get confirmPassword() { return browser.element( '#wpRetype' ); }
	get create() { return browser.element( '#wpCreateaccount' ); }
	get heading() { return browser.element( '#firstHeading' ); }

	open() {
		super.open( 'Special:CreateAccount' );
	}

	createAccount( username, password ) {
		this.open();
		this.username.setValue( username );
		this.password.setValue( password );
		this.confirmPassword.setValue( password );
		this.create.click();
	}

	apiCreateAccount( username, password ) {
		let bot = new MWBot();

		return bot.loginGetCreateaccountToken( {
			apiUrl: `${browser.options.baseUrl}/api.php`,
			username: browser.options.username,
			password: browser.options.password
		} ).then( function () {
			return bot.request( {
				action: 'createaccount',
				createreturnurl: browser.options.baseUrl,
				createtoken: bot.createaccountToken,
				username: username,
				password: password,
				retype: password
			} );
		} );
	}
}

module.exports = new CreateAccountPage();
