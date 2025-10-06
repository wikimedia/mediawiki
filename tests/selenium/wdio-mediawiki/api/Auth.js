/**
 * Authentication helper for MediaWiki API.
 *
 */

export class Auth {
	constructor( { request, session } ) {
		this.request = request;
		this.session = session;
	}

	/**
	 * Log in with a username and password.
	 *
	 * Sets the session as logged in.
	 *
	 * @param {string} username - Account username.
	 * @param {string} password - Account password.
	 * @return {Promise<void>} Resolves on successful login.
	 * @throws {Error} If the API does not return a login token or the login fails.
	 */
	async login( username, password ) {
		// Get the login token
		const loginTokenResponse = await this.request( { action: 'query', meta: 'tokens', type: 'login' } );
		const loginToken = loginTokenResponse?.query?.tokens?.logintoken;
		if ( !loginToken ) {
			console.error( '[API] Failed to obtain login token', loginTokenResponse );
			throw new Error( 'Invalid response from API (no login token)' );
		}

		// Login
		const loginResponse = await this.request( {
			action: 'login',
			lgname: username,
			lgpassword: password,
			lgtoken: loginToken
		} );
		if ( loginResponse?.login?.result !== 'Success' ) {
			console.error( '[API] Login failed', loginResponse );
			throw new Error( `Could not login: ${ loginResponse?.login?.result ?? 'Unknown reason' }` );
		}
		this.session.loggedIn = true;
	}

	/**
	 * Get a CSRF edit token.
	 *
	 * Caches the token so it can be re-used on the next request.
	 *
	 * @return {Promise<string>} A CSRF token suitable for write actions.
	 * @throws {Error} If the API does not return a token.
	 */
	async getEditToken() {
		if ( this.session.csrfToken ) {
			return this.session.csrfToken;
		}

		const res = await this.request( { action: 'query', meta: 'tokens', type: 'csrf' } );
		const token = res?.query?.tokens?.csrftoken;
		if ( !token ) {
			console.error( '[API] Could not get edit token. Response:', res );
			throw new Error( 'Could not get edit token' );
		}
		this.session.csrfToken = token;
		return token;
	}

	/**
	 * Get a create-account token.
	 *
	 * Caches the token so it can be re-used on the next request.
	 *
	 * @return {Promise<string>} A token for creating an account.
	 * @throws {Error} If the API does not return a token.
	 */
	async getCreateAccountToken() {
		if ( this.session.createAccountToken ) {
			return this.session.createAccountToken;
		}

		const res = await this.request( { action: 'query', meta: 'tokens', type: 'createaccount' } );
		const token = res?.query?.tokens?.createaccounttoken;
		if ( !token ) {
			console.error( '[API] Could not get createaccount token. Response:', res );
			throw new Error( 'Could not get createaccount token' );
		}
		this.session.createAccountToken = token;
		return token;
	}

	/**
	 * Convenience: login, then fetch a CSRF edit token.
	 *
	 * Useful for test setup or scripts that need a token right after logging in.
	 *
	 * @param {string} username - Account username.
	 * @param {string} password - Account password.
	 * @return {Promise<string>} A CSRF token.
	 */
	async loginGetEditToken( username, password ) {
		return this.login( username, password ).then( () => this.getEditToken() );
	}
}
