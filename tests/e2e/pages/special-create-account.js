var wiki = require( 'malu' ).wiki;

module.exports = new wiki.Page( 'Special:CreateAccount', {
	signUpForm: 'form[name=userlogin2]',
	username: '#wpName2',
	password: '#wpPassword2',
	passwordConfirmation: '#wpRetype',
	createAccountButton: '#wpCreateaccount',
	accountCreationError: '#mw-createacct-status-area.errorbox',
	userPageLink: '#pt-userpage'
} );
