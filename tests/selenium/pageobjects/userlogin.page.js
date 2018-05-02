const LoginPage = require( 'wdio-mediawiki/LoginPage' );

/**
 * @deprecated Use wdio-mediawiki/LoginPage instead.
 */
class UserLoginPage extends LoginPage {
}

module.exports = new UserLoginPage();
