import Page from './Page.js';
import { waitForModuleState } from 'wdio-mediawiki/Util.js';

class CreateAccountPage extends Page {
	get username() {
		return $( '#wpName2' );
	}

	get password() {
		return $( '#wpPassword2' );
	}

	get confirmPassword() {
		return $( '#wpRetype' );
	}

	get create() {
		return $( '#wpCreateaccount' );
	}

	get heading() {
		return $( '#firstHeading' );
	}

	get tempPasswordInput() {
		return $( '#wpCreateaccountMail' );
	}

	get reasonInput() {
		return $( '#wpReason' );
	}

	async open() {
		await super.openTitle( 'Special:CreateAccount' );
	}

	/**
	 * Navigate to Special:CreateAccount, then fill out and submit the account creation form.
	 *
	 * @param {string} username
	 * @param {string} password
	 * @return {Promise<void>}
	 */
	async createAccount( username, password ) {
		await this.open();
		await this.submitForm( username, password );
	}

	/**
	 * Fill out and submit the account creation form on Special:CreateAccount.
	 * The browser is assumed to have already navigated to this page.
	 *
	 * @param {string} username
	 * @param {string} password
	 * @return {Promise<void>}
	 */
	async submitForm( username, password ) {
		await waitForModuleState( 'mediawiki.special.createaccount', 'ready', 10000 );

		await this.username.setValue( username );
		await this.password.setValue( password );
		await this.confirmPassword.setValue( password );
		await this.create.click();
	}
}

export default new CreateAccountPage();
