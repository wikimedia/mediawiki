'use strict';
const Page = require( './page' );

class UserMessagePage extends Page {
	get usermessage() { return browser.element( 'div.usermessage' ); }
}
module.exports = new UserMessagePage();
