const Page = require( 'wdio-mediawiki/Page' ),
	Api = require( 'wdio-mediawiki/Api' );

class CreateAccountPage extends Page {
	get username() { return $( '#wpName2' ); }
	get password() { return $( '#wpPassword2' ); }
	get confirmPassword() { return $( '#wpRetype' ); }
	get create() { return $( '#wpCreateaccount' ); }
	get heading() { return $( '#firstHeading' ); }

	open() {
		super.openTitle( 'Special:CreateAccount' );
	}

	createAccount( username, password ) {
		this.open();
		this.username.setValue( username );
		this.password.setValue( password );
		this.confirmPassword.setValue( password );
		this.create.click();
	}

	// @deprecated Use wdio-mediawiki/Api#createAccount() instead.
	apiCreateAccount( username, password ) {
		return Api.createAccount( username, password );
	}
}

module.exports = new CreateAccountPage();
