'use strict';

const Page = require( './Page' );

class CreateAccountPage extends Page {
	get username() { return $( '#wpName2' ); }
	get password() { return $( '#wpPassword2' ); }
	get confirmPassword() { return $( '#wpRetype' ); }
	get create() { return $( '#wpCreateaccount' ); }
	get heading() { return $( '#firstHeading' ); }

	open() {
		super.openTitle( 'Special:CreateAccount' );
	}

	async createAccount( username, password ) {
		await this.open();
		await this.username.setValue( username );
		await this.password.setValue( password );
		await this.confirmPassword.setValue( password );
		await this.create.click();
	}
}

module.exports = new CreateAccountPage();
