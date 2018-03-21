'use strict';
const Page = require( './page' );

class EditPage extends Page {

	get content() { return browser.element( '#wpTextbox1' ); }
	get displayedContent() { return browser.element( '#mw-content-text' ); }
	get heading() { return browser.element( '#firstHeading' ); }
	get save() { return browser.element( '#wpSave' ); }

	openForEditing( name ) {
		super.open( name + '&action=edit' );
	}

	edit( name, content ) {
		this.openForEditing( name );
		this.content.setValue( content );
		this.save.click();
	}

	apiEdit( name, content ) {

		const MWBot = require( 'mwbot' ), // https://github.com/Fannon/mwbot
			Promise = require( 'bluebird' );
		let bot = new MWBot();

		return Promise.coroutine( function* () {
			yield bot.loginGetEditToken( {
				apiUrl: `${browser.options.baseUrl}/api.php`,
				username: browser.options.username,
				password: browser.options.password
			} );
			yield bot.edit( name, content, `Created page with "${content}"` );
		} ).call( this );

	}

}
module.exports = new EditPage();
