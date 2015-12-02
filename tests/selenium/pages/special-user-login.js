var po = require( '../lib/page-object.js' ),
		by = require( 'selenium-webdriver' ).By,
    page = new po.Page( 'Special:UserLogin' );

page.defineElements( by.css, {
	loginForm: 'form[name=userlogin]',
	username: '#wpName1',
	password: '#wpPassword1',
	loginButton: '#wpLoginAttempt'
} );

module.exports = page;
