const Page = require( './page' ),
	// https://github.com/Fannon/mwbot
	MWBot = require( 'mwbot' );

class DeletePage extends Page {
	get reason() { return browser.element( '#wpReason' ); }
	get watch() { return browser.element( '#wpWatch' ); }
	get submit() { return browser.element( '#wpConfirmB' ); }
	get displayedContent() { return browser.element( '#mw-content-text' ); }

	open( name ) {
		super.open( name + '&action=delete' );
	}

	delete( name, reason ) {
		this.open( name );
		this.reason.setValue( reason );
		this.submit.click();
	}

	apiDelete( name, reason ) {
		let bot = new MWBot();

		return bot.loginGetEditToken( {
			apiUrl: `${browser.options.baseUrl}/api.php`,
			username: browser.options.username,
			password: browser.options.password
		} ).then( function () {
			return bot.delete( name, reason );
		} );
	}
}

module.exports = new DeletePage();
