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
		const url = require( 'url' ), // https://nodejs.org/docs/latest/api/url.html
			baseUrl = url.parse( browser.options.baseUrl ), // http://webdriver.io/guide/testrunner/browserobject.html
			Bot = require( 'nodemw' ), // https://github.com/macbre/nodemw
			client = new Bot( {
				protocol: baseUrl.protocol,
				server: baseUrl.hostname,
				port: baseUrl.port,
				path: baseUrl.path,
				debug: false
			} );

		return new Promise( ( resolve, reject ) => {
			client.edit( name, content, `Created page with "${content}"`, function ( err ) {
				if ( err ) {
					return reject( err );
				}
				resolve();
			} );
		} );
	}

}
module.exports = new EditPage();
