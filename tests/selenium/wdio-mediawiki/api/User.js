/**
 * A helper class for managing users in MediaWiki through API requests.
 *
 * This class wraps common API actions like creating accounts,
 * blocking/unblocking users, and adding users to groups.
 */
export class User {
	constructor( { request, auth, session } ) {
		this.request = request;
		this.auth = auth;
		this.session = session;
	}

	/**
	 * Create a new user account.
	 *
	 * @param {string} username - The username for the new account.
	 * @param {string} password - The password for the new account.
	 * @return {Promise<Object>} API response with account creation details.
	 */
	async createAccount( username, password ) {
		const token = await this.auth.getCreateAccountToken();
		// Create the new account
		return this.request( {
			action: 'createaccount',
			createreturnurl: browser.options.baseUrl,
			createtoken: token,
			username: username,
			password: password,
			retype: password
		} );
	}

	/**
	 * Block a user account.
	 *
	 * @param {string} username - The username to block.
	 * @param {string} expiry - How long the block should last (e.g. "1 day", "infinite").
	 * @return {Promise<Object>} API response for the block action.
	 */
	async blockUser( username, expiry ) {
		if ( !this.session.csrfToken ) {
			await this.auth.getEditToken();
		}
		return await this.request( {
			action: 'block',
			user: username,
			reason: 'browser test',
			token: this.session.csrfToken,
			expiry
		} );
	}

	/**
	 * Unblock a user account.
	 *
	 * @param {string} username - The username to unblock.
	 * @return {Promise<Object>} API response for the unblock action.
	 */
	async unblockUser( username ) {
		if ( !this.session.csrfToken ) {
			await this.auth.getEditToken();
		}
		return await this.request( {
			action: 'unblock',
			user: username,
			reason: 'browser test done',
			token: this.session.csrfToken
		} );
	}

	/**
	 * Add a user to a group.
	 *
	 * Skips the request if the user is already in the group.
	 *
	 * @param {string} username - The username to modify.
	 * @param {string} groupname - The group to add the user to.
	 * @return {Promise<void>} Resolves when complete. Throws if API returns an error.
	 */
	async addUserToGroup( username, groupname ) {
		const userGroupsResponse = await this.request( {
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
			if ( respUser.groups.includes( groupname ) ) {
				return;
			}
		}
		const tokenResponse = await this.request( {
			action: 'query',
			meta: 'tokens',
			type: 'userrights'
		} );
		await this.request( {
			action: 'userrights',
			user: username,
			token: tokenResponse.query.tokens.userrightstoken,
			add: groupname,
			reason: 'Selenium testing'
		} );
	}
}
