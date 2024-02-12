// This file is used at Selenium/Explanation/Page object pattern
// https://www.mediawiki.org/wiki/Selenium/Explanation/Page_object_pattern

'use strict';

const Page = require( './Page' );

class LoginPage extends Page {
	get username() {
		return $( '#wpName1' );
	}

	get password() {
		return $( '#wpPassword1' );
	}

	get loginButton() {
		return $( '#wpLoginAttempt' );
	}

	get userPage() {
		return $( '#pt-userpage' );
	}

	async open() {
		await super.openTitle( 'Special:UserLogin' );
	}

	async getActualUsername() {
		return browser.execute( () => mw.config.get( 'wgUserName' ) );
	}

	async login( username, password ) {
		await this.open();
		await this.username.setValue( username );
		await this.password.setValue( password );
		await this.loginButton.click();
		await browser.waitUntil(
			async () => await browser.execute(
				( expectedUsername ) => typeof mw !== 'undefined' &&
					mw.config.get( 'wgUserName' ) === expectedUsername,
				username
			),
			{
				timeout: 15000,
				timeoutMsg: 'Cannot submit login form'
			}
		);
	}

	async loginAdmin() {
		await this.login( browser.options.capabilities[ 'mw:user' ], browser.options.capabilities[ 'mw:pwd' ] );
	}
}

module.exports = new LoginPage();
