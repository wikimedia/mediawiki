'use strict';
const Page = require( './page' );

class CreateAccountPage extends Page {

	get username() { return browser.element( '#wpName2' ); }
	get password() { return browser.element( '#wpPassword2' ); }
	get confirmPassword() { return browser.element( '#wpRetype' ); }
	get create() { return browser.element( '#wpCreateaccount' ); }
	get heading() { return browser.element( '#firstHeading' ); }

	open() {
		super.open( 'Special:CreateAccount' );
	}

	createAccount( username, password ) {
		this.open();
		this.username.setValue( username );
		this.password.setValue( password );
		this.confirmPassword.setValue( password );
		this.create.click();
	}

	apiCreateAccount( username, password ) {

		const url = require( 'url' ), // https://nodejs.org/docs/latest/api/url.html
			baseUrl = url.parse( browser.options.baseUrl ), // http://webdriver.io/guide/testrunner/browserobject.html
			Bot = require( 'nodemw' ), // https://github.com/macbre/nodemw
			client = new Bot( {
				protocol: baseUrl.protocol,
				server: baseUrl.hostname,
				port: baseUrl.port,
				path: baseUrl.path,
				debug: true
			} );

		var params = {
			action: 'query',
			meta: 'tokens',
			type: 'createaccount'
		};

		client.api.call( params /* api.php parameters */, function ( err /* Error instance or null */, info /* processed query result */, next /* more results? */, data /* raw data */ ) {

			var paramsCreateaccount = {
				action: 'createaccount',
				createreturnurl: browser.options.baseUrl,
				createtoken: data.query.tokens.createaccounttoken,
				username: username,
				password: password,
				retype: password
			};

			if ( err ) {
				console.error( err );
				return;
			}

			client.api.call( paramsCreateaccount /* api.php parameters */, function ( err /* Error instance or null */ ) {
				if ( err ) {
					console.error( err );
					return;
				}
			}, 'POST' );

		} );

	}

}
module.exports = new CreateAccountPage();
