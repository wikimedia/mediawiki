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
		const MWBot = require( 'mwbot' ); // https://github.com/Fannon/mwbot
		let bot = new MWBot( {
			verbose: true,
			silent: false,
			concurrency: 1
		} );
		return new Promise( ( resolve, reject ) => {
			bot.loginGetEditToken( {
				apiUrl: 'http://127.0.0.1:8080/w/api.php',
				username: 'Admin',
				password: 'vagrant'
			} ).then( () => {
				return bot.edit( name, content, `Created page with "${content}"` );
			} ).then( ( response ) => {
			// Success
				resolve( response );
			} ).catch( ( err ) => {
			// Error
				return reject( err );
			} );
		} );
	}

}
module.exports = new EditPage();
