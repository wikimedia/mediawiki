'use strict';
const Page = require( './page' );

class LogNewUsersPage extends Page {

	open() {
		super.open( 'Special:Log/newusers' );
	}

	user( username ) {
		return browser.element(`a[title='User:${username} (page does not exist)']`);
	}

}
module.exports = new LogNewUsersPage();
