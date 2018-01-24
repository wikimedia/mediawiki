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
		const url = require( 'url' ), // https://nodejs.org/docs/latest/api/url.html
			baseUrl = url.parse( browser.options.baseUrl ), // http://webdriver.io/guide/testrunner/browserobject.html
			Bot = require( 'nodemw' ), // https://github.com/macbre/nodemw
			client = new Bot( {
				protocol: baseUrl.protocol,
				server: baseUrl.hostname,
				port: baseUrl.port,
				path: baseUrl.path,
				username: browser.options.username,
				password: browser.options.password,
				debug: false
			} );

		return new Promise( ( resolve, reject ) => {
			client.logIn( function ( err ) {
				if ( err ) {
					console.log( err );
					return reject( err );
				}
				client.delete( name, reason, function ( err ) {
					if ( err ) {
						return reject( err );
					}
					resolve();
				} );
			} );
		} );
	}

}
module.exports = new DeletePage();
