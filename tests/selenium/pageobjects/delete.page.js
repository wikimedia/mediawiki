'use strict';
const Page = require( './page' );

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

		const MWBot = require( 'mwbot' ), // https://github.com/Fannon/mwbot
			Promise = require( 'bluebird' );
		let bot = new MWBot();

		return Promise.coroutine( function* () {
			yield bot.loginGetEditToken( {
				apiUrl: `${browser.options.baseUrl}/api.php`,
				username: browser.options.username,
				password: browser.options.password
			} );
			yield bot.delete( name, reason );
		} ).call( this );

	}

}
module.exports = new DeletePage();
