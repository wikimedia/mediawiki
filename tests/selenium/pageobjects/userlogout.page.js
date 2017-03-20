'use strict';
const Page = require( './page' );

class UserLogoutPage extends Page {

	open() {
		super.open( 'Special:UserLogout' );
	}

}
module.exports = new UserLogoutPage();
