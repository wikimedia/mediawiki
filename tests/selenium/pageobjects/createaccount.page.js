const Page = require( 'wdio-mediawiki/Page' );

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
}

module.exports = new CreateAccountPage();
