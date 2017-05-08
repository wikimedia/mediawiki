'use strict';
const Page = require( './page' );

class EditPage extends Page {

	get content() { return browser.element( '#wpTextbox1' ); }
	get displayedContent() { return browser.element( '#mw-content-text' ); }
	get heading() { return browser.element( '#firstHeading' ); }
	get save() { return browser.element( '#wpSave' ); }

	open( name ) {
		super.open( name + '&action=edit' );
	}

	edit( name, content ) {
		this.open( name );
		this.content.setValue( content );
		this.save.click();
	}

	apiEdit( name, content ) {
		var Bot, client;

		Bot = require( 'nodemw' );

		client = new Bot( {
			server: '127.0.0.1',
			port: '8080',
			path: '/w',
			debug: false
		} );

		client.edit( name, content, 'summary', function ( err ) {
			if ( err ) {
				console.error( err );
				return;
			}
		} );
	}

}
module.exports = new EditPage();
