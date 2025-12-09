import Page from './Page.js';

class LogoutPage extends Page {
	get logoutButton() {
		return $( '#mw-content-text form[method=post] button[type=submit]' );
	}

	async open() {
		await super.openTitle( 'Special:UserLogout' );
	}

	async logout() {
		await this.open();
		await this.logoutButton.click();
		await browser.waitUntil(
			async () => await browser.execute(
				() => typeof mw !== 'undefined' &&
					mw.config.get( 'wgUserName' ) === null
			),
			{
				timeout: 15000,
				timeoutMsg: 'Cannot submit logout form'
			}
		);
	}
}

export default new LogoutPage();
