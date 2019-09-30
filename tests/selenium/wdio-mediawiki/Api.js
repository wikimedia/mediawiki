const MWBot = require( 'mwbot' );

// TODO: Once we require Node 7 or later, we can use async-await.

module.exports = {
	/**
	 * Get a logged-in instance of `MWBot` with edit token already set up.
	 * Default username, password and base URL is used unless specified.
	 *
	 * @since 0.5.0
	 * @param {string} username - Optional
	 * @param {string} password - Optional
	 * @param {string} baseUrl - Optional
	 * @return {Promise<MWBot>}
	 */
	bot(
		username = browser.config.mwUser,
		password = browser.config.mwPwd,
		baseUrl = browser.config.baseUrl
	) {
		const bot = new MWBot();

		return bot.loginGetEditToken( {
			apiUrl: `${baseUrl}/api.php`,
			username: username,
			password: password
		} ).then( function () {
			return bot;
		} );
	},

	/**
	 * Shortcut for `MWBot#edit( .. )`.
	 * Default username, password and base URL is used unless specified
	 *
	 * @since 0.1.0
	 * @see <https://www.mediawiki.org/wiki/API:Edit>
	 * @param {string} title
	 * @param {string} content
	 * @param {string} username - Optional
	 * @param {string} password - Optional
	 * @param {baseUrl} baseUrl - Optional
	 * @return {Object} Promise for API action=edit response data.
	 */
	edit( title,
		content,
		username = browser.config.mwUser,
		password = browser.config.mwPwd,
		baseUrl = browser.config.baseUrl
	) {
		return this.bot( username, password, baseUrl )
			.then( function ( bot ) {
				return bot.edit( title, content, `Created or updated page with "${content}"` );
			} );
	},

	/**
	 * Shortcut for `MWBot#delete( .. )`.
	 *
	 * @since 0.1.0
	 * @see <https://www.mediawiki.org/wiki/API:Delete>
	 * @param {string} title
	 * @param {string} reason
	 * @return {Object} Promise for API action=delete response data.
	 */
	delete( title, reason ) {
		return this.bot()
			.then( function ( bot ) {
				return bot.delete( title, reason );
			} );
	},

	/**
	 * Shortcut for `MWBot#request( { acount: 'createaccount', .. } )`.
	 *
	 * @since 0.1.0
	 * @see <https://www.mediawiki.org/wiki/API:Account_creation>
	 * @param {string} username
	 * @param {string} password
	 * @return {Object} Promise for API action=createaccount response data.
	 */
	createAccount( username, password ) {
		const bot = new MWBot();

		// Log in as admin
		return bot.loginGetCreateaccountToken( {
			apiUrl: `${browser.config.baseUrl}/api.php`,
			username: browser.config.mwUser,
			password: browser.config.mwPwd
		} ).then( function () {
			// Create the new account
			return bot.request( {
				action: 'createaccount',
				createreturnurl: browser.config.baseUrl,
				createtoken: bot.createaccountToken,
				username: username,
				password: password,
				retype: password
			} );
		} );
	},

	/**
	 * Shortcut for `MWBot#request( { action: 'block', .. } )`.
	 *
	 * @since 0.3.0
	 * @see <https://www.mediawiki.org/wiki/API:Block>
	 * @param {string} [username] defaults to user making the request
	 * @param {string} [expiry] default is not set. For format see API docs
	 * @return {Object} Promise for API action=block response data.
	 */
	blockUser( username, expiry ) {
		return this.bot()
			.then( function ( bot ) {
				// block user. default = admin
				return bot.request( {
					action: 'block',
					user: username || browser.config.mwUser,
					reason: 'browser test',
					token: bot.editToken,
					expiry
				} );
			} );
	},

	/**
	 * Shortcut for `MWBot#request( { action: 'unblock', .. } )`.
	 *
	 * @since 0.3.0
	 * @see <https://www.mediawiki.org/wiki/API:Block>
	 * @param {string} [username] defaults to user making the request
	 * @return {Object} Promise for API action=unblock response data.
	 */
	unblockUser( username ) {
		return this.bot()
			.then( function ( bot ) {
				// unblock user. default = admin
				return bot.request( {
					action: 'unblock',
					user: username || browser.config.mwUser,
					reason: 'browser test done',
					token: bot.editToken
				} );
			} );
	}
};
