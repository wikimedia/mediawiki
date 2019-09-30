const MWBot = require( 'mwbot' );

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
	async bot(
		username = browser.config.mwUser,
		password = browser.config.mwPwd,
		baseUrl = browser.config.baseUrl
	) {
		const bot = new MWBot();

		await bot.loginGetEditToken( {
			apiUrl: `${baseUrl}/api.php`,
			username: username,
			password: password
		} );
		return bot;
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
	async edit( title,
		content,
		username = browser.config.mwUser,
		password = browser.config.mwPwd,
		baseUrl = browser.config.baseUrl
	) {
		const bot = await this.bot( username, password, baseUrl );
		return await bot.edit( title, content, `Created or updated page with "${content}"` );
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
	async delete( title, reason ) {
		const bot = await this.bot();
		return await bot.delete( title, reason );
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
	async createAccount( username, password ) {
		const bot = new MWBot();

		// Log in as admin
		await bot.loginGetCreateaccountToken( {
			apiUrl: `${browser.config.baseUrl}/api.php`,
			username: browser.config.mwUser,
			password: browser.config.mwPwd
		} );
		// Create the new account
		return await bot.request( {
			action: 'createaccount',
			createreturnurl: browser.config.baseUrl,
			createtoken: bot.createaccountToken,
			username: username,
			password: password,
			retype: password
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
	async blockUser( username, expiry ) {
		const bot = await this.bot();
		// block user. default = admin
		return await bot.request( {
			action: 'block',
			user: username || browser.config.mwUser,
			reason: 'browser test',
			token: bot.editToken,
			expiry
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
	async unblockUser( username ) {
		const bot = await this.bot();
		// unblock user. default = admin
		return await bot.request( {
			action: 'unblock',
			user: username || browser.config.mwUser,
			reason: 'browser test done',
			token: bot.editToken
		} );
	}
};
