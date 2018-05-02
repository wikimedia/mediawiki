const MWBot = require( 'mwbot' );

// TODO: Once we require Node 7 or later, we can use async-await.

module.exports = {
	/**
	 * Shortcut for `MWBot#edit( .. )`.
	 *
	 * @since 1.0.0
	 * @see <https://www.mediawiki.org/wiki/API:Edit>
	 * @param {string} title
	 * @param {string} content
	 * @return {Object} Promise for API action=edit response data.
	 */
	edit( title, content ) {
		let bot = new MWBot();

		return bot.loginGetEditToken( {
			apiUrl: `${browser.options.baseUrl}/api.php`,
			username: browser.options.username,
			password: browser.options.password
		} ).then( function () {
			return bot.edit( title, content, `Created page with "${content}"` );
		} );
	},

	/**
	 * Shortcut for `MWBot#delete( .. )`.
	 *
	 * @since 1.0.0
	 * @see <https://www.mediawiki.org/wiki/API:Delete>
	 * @param {string} title
	 * @param {string} reason
	 * @return {Object} Promise for API action=delete response data.
	 */
	delete( title, reason ) {
		let bot = new MWBot();

		return bot.loginGetEditToken( {
			apiUrl: `${browser.options.baseUrl}/api.php`,
			username: browser.options.username,
			password: browser.options.password
		} ).then( function () {
			return bot.delete( title, reason );
		} );
	},

	/**
	 * Shortcut for `MWBot#request( { acount: 'createaccount', .. } )`.
	 *
	 * @since 1.0.0
	 * @see <https://www.mediawiki.org/wiki/API:Account_creation>
	 * @param {string} username
	 * @param {string} password
	 * @return {Object} Promise for API action=createaccount response data.
	 */
	createAccount( username, password ) {
		let bot = new MWBot();

		// Log in as admin
		return bot.loginGetCreateaccountToken( {
			apiUrl: `${browser.options.baseUrl}/api.php`,
			username: browser.options.username,
			password: browser.options.password
		} ).then( function () {
			// Create the new account
			return bot.request( {
				action: 'createaccount',
				createreturnurl: browser.options.baseUrl,
				createtoken: bot.createaccountToken,
				username: username,
				password: password,
				retype: password
			} );
		} );
	}
};
