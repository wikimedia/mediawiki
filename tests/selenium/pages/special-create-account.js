var po = require( '../lib/page-object.js' ),
		by = require( 'selenium-webdriver' ).By,
    page = new po.Page( 'Special:CreateAccount' );

page.defineElements( by.css, {
	signUpForm: 'form[name=userlogin2]',
	username: '#wpName2',
	password: '#wpPassword2',
	passwordConfirmation: '#wpRetype',
	createAccountButton: '#wpCreateaccount',
	accountCreationError: '#mw-createacct-status-area.errorbox',
	userPageLink: '#pt-userpage'
} );

module.exports = page;
