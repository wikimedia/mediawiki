var wiki = require( 'malu' ).wiki;

module.exports = new wiki.Page( 'Special:UserLogin', {
	loginForm: 'form[name=userlogin]',
	username: '#wpName1',
	password: '#wpPassword1',
	loginButton: '#wpLoginAttempt'
} );
