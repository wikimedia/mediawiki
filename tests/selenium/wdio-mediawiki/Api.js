import MWBot from 'mwbot';

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
export async function mwbot(
	username = browser.options.capabilities[ 'mw:user' ],
	password = browser.options.capabilities[ 'mw:pwd' ],
	baseUrl = browser.options.baseUrl
) {
	const bot = new MWBot();

	await bot.loginGetEditToken( {
		apiUrl: `${ baseUrl }/api.php`,
		username: username,
		password: password
	} );
	return bot;
}

/**
 * Shortcut for `MWBot#request( { acount: 'createaccount', .. } )`.
 *
 * @since 0.1.0
 * @see <https://www.mediawiki.org/wiki/API:Account_creation>
 * @param {MWBot} adminBot
 * @param {string} username New user name
 * @param {string} password New user password
 * @return {Object} Promise for API action=createaccount response data.
 */
export async function createAccount( adminBot, username, password ) {
	await adminBot.getCreateaccountToken();

	// Create the new account
	return await adminBot.request( {
		action: 'createaccount',
		createreturnurl: browser.options.baseUrl,
		createtoken: adminBot.createaccountToken,
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
 * @param {MWBot} adminBot
 * @param {string} [username] defaults to blocking the admin user
 * @param {string} [expiry] default is not set. For format see API docs
 * @return {Object} Promise for API action=block response data.
 */
export async function blockUser( adminBot, username, expiry ) {
	return await adminBot.request( {
		action: 'block',
		user: username || browser.options.capabilities[ 'mw:user' ],
		reason: 'browser test',
		token: adminBot.editToken,
		expiry
	} );
}

/**
 * Shortcut for `MWBot#request( { action: 'unblock', .. } )`.
 *
 * @since 0.3.0
 * @see <https://www.mediawiki.org/wiki/API:Block>
 * @param {MWBot} adminBot
 * @param {string} [username] defaults to unblocking the admin user
 * @return {Object} Promise for API action=unblock response data.
 */
export async function unblockUser( adminBot, username ) {
	return await adminBot.request( {
		action: 'unblock',
		user: username || browser.options.capabilities[ 'mw:user' ],
		reason: 'browser test done',
		token: adminBot.editToken
	} );
}

/**
 * Assign a new user group to the given username.
 *
 * @since 2.7.0
 * @param {MWBot} adminBot
 * @param {string} username
 * @param {string} groupName
 */
export async function addUserToGroup( adminBot, username, groupName ) {
	const userGroupsResponse = await adminBot.request( {
		action: 'query',
		list: 'users',
		usprop: 'groups',
		ususers: username,
		formatversion: 2
	} );

	if (
		userGroupsResponse.query.users.length &&
                        userGroupsResponse.query.users[ 0 ].groups.includes( groupName )
	) {
		return;
	}
	const tokenResponse = await adminBot.request( {
		action: 'query',
		meta: 'tokens',
		type: 'userrights'
	} );
	await adminBot.request( {
		action: 'userrights',
		user: username,
		token: tokenResponse.query.tokens.userrightstoken,
		add: groupName,
		reason: 'Selenium testing'
	} );
	// If there is an error, the above already throws.
}
