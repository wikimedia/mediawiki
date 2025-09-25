import MWBot from 'mwbot';

/**
 * The API class is the way to talk to the MediaWiki API from webdriver.io.
 *
 * It wraps the background API implementation with convenience methods made for
 * browser tests. Default credentials and base URL are read from the webdriver.io config.
 *
 */
class Api {
	// Question: Is there any use case where we don't want to use the settings
	// from the webdriver.io config?
	constructor(
		username = browser.options.capabilities[ 'mw:user' ],
		password = browser.options.capabilities[ 'mw:pwd' ],
		baseUrl = browser.options.baseUrl ) {

		this.username = username;
		this.password = password;
		this.baseUrl = baseUrl;

		this.bot = new MWBot();

		// We bind the functions for mwbot that is used in core
		// Is there a good way to JSDoc them?
		this.request = this.bot.request.bind( this.bot );
		this.edit = this.bot.edit.bind( this.bot );
		this.delete = this.bot.delete.bind( this.bot );
		this.read = this.bot.read.bind( this.bot );
		this.loginGetEditToken = this.bot.loginGetEditToken.bind( this.bot );
	}

	/**
	 * Log in with the configured user and obtain an edit token.
	 *
	 * @return {Promise<Object>}
	 */
	async loginGetEditToken() {
		return this.bot.loginGetEditToken( {
			apiUrl: `${ this.baseUrl }/api.php`,
			username: this.username,
			password: this.password
		} );
	}

	/**
	 * Shortcut for `MWBot#request( { acount: 'createaccount', .. } )`.
	 *
	 * @since 0.1.0
	 * @see <https://www.mediawiki.org/wiki/API:Account_creation>
	 * @param {string} username New user name
	 * @param {string} password New user password
	 * @return {Object} Promise for API action=createaccount response data.
	 */
	async createAccount( username, password ) {
		await this.bot.getCreateaccountToken();

		// Create the new account
		return await this.bot.request( {
			action: 'createaccount',
			createreturnurl: browser.options.baseUrl,
			createtoken: this.bot.createaccountToken,
			username: username,
			password: password,
			retype: password
		} );
	}

	/**
	 * Shortcut for `MWBot#request( { action: 'block', .. } )`.
	 *
	 * @since 0.3.0
	 * @see <https://www.mediawiki.org/wiki/API:Block>
	 * @param {string} [username] defaults to blocking the admin user
	 * @param {string} [expiry] default is not set. For format see API docs
	 * @return {Object} Promise for API action=block response data.
	 */
	async blockUser( username, expiry ) {
		return await this.bot.request( {
			action: 'block',
			user: username || browser.options.capabilities[ 'mw:user' ],
			reason: 'browser test',
			token: this.bot.editToken,
			expiry
		} );
	}

	/**
	 * Shortcut for `MWBot#request( { action: 'unblock', .. } )`.
	 *
	 * @since 0.3.0
	 * @see <https://www.mediawiki.org/wiki/API:Block>
	 * @param {string} [username] defaults to unblocking the admin user
	 * @return {Object} Promise for API action=unblock response data.
	 */
	async unblockUser( username ) {
		return await this.bot.request( {
			action: 'unblock',
			user: username || browser.options.capabilities[ 'mw:user' ],
			reason: 'browser test done',
			token: this.bot.editToken
		} );
	}

	/**
	 * Assign a new user group to the given username.
	 *
	 * @since 2.7.0
	 * @param {string} username
	 * @param {string} groupName
	 */
	async addUserToGroup( username, groupName ) {
		const userGroupsResponse = await this.bot.request( {
			action: 'query',
			list: 'users',
			usprop: 'groups',
			ususers: username,
			formatversion: 2
		} );

		if ( userGroupsResponse.query.users.length ) {
			const respUser = userGroupsResponse.query.users[ 0 ];
			if ( !respUser.groups ) {
				// Should not happen, except it does: T393428
				throw new Error( 'API response does not include user groups: ' + JSON.stringify( userGroupsResponse ) );
			}
			if ( respUser.groups.includes( groupName ) ) {
				return;
			}
		}
		const tokenResponse = await this.bot.request( {
			action: 'query',
			meta: 'tokens',
			type: 'userrights'
		} );
		await this.bot.request( {
			action: 'userrights',
			user: username,
			token: tokenResponse.query.tokens.userrightstoken,
			add: groupName,
			reason: 'Selenium testing'
		} );
		// If there is an error, the above already throws.
	}
}

/**
 * Factory that creates and logs in an Api(client) instance.
 *
 * @return {Promise<Api>}
 */
export const createApiClient = async function () {
	const api = new Api();

	await api.loginGetEditToken( {
		apiUrl: `${ api.baseUrl }/api.php`,
		username: api.username,
		password: api.password
	} );
	return api;
};
