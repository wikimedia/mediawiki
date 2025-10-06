import { Cookies } from './api/Cookies.js';
import { MwApiHttpClient } from './api/MwApiHttpClient.js';
import { Auth } from './api/Auth.js';
import { Pages } from './api/Page.js';
import { User } from './api/User.js';

/**
 * The API class is the way to talk to the MediaWiki API from webdriver.io.
 * https://www.mediawiki.org/wiki/API:Action_API
 *
 * The goal is to simplify API calls to make it easier to run automatic browser
 * tests.
 * Default credentials and base URL are read from the webdriver.io config.
 */
class Api {
	constructor( options ) {
		const {
			baseUrl,
			username,
			password,
			verbose
		} = options;

		this.session = {
			loggedIn: false,
			csrfToken: null,
			createAccountToken: null
		};

		this.cookies = new Cookies();
		this.httpClient = new MwApiHttpClient( {
			cookies: this.cookies,
			options: {
				defaultSummary: 'MwApiClient',
				apiUrl: `${ baseUrl }/api.php`,
				username: username,
				password: password,
				verbose: verbose
			}
		} );

		this.auth = new Auth( {
			request: this.httpClient.request.bind( this.httpClient ),
			session: this.session
		} );

		this.pages = new Pages( {
			request: this.httpClient.request.bind( this.httpClient ),
			session: this.session,
			summary: 'MwApiClient',
			auth: this.auth
		} );

		this.user = new User( {
			request: this.httpClient.request.bind( this.httpClient ),
			auth: this.auth,
			session: this.session
		} );
	}

	/**
	 * Do a request with custom parameters to the API. If you
	 * use this function maybe something is missing in core?
	 *
	 * @param params
	 * @return {Promise<Object>} The JSON response from the API
	 * @throws {Error} If the request fails
	 */
	async request( params ) {
		return this.httpClient.request( params );
	}

	/**
	 * Login a user using the MediaWiki API.
	 *
	 * @see https://www.mediawiki.org/wiki/API:Login
	 * @param {string} username
	 * @param {string} password
	 * @return {Promise<void>} Resolves when the user is logged in.
	 * @throws {Error} If the login request fails or the API returns an error.
	 */
	async login( username, password ) {
		return this.auth.login( username, password );
	}

	/**
	 * Get a CSRF edit token. The token will be cached for the next request.
	 *
	 * @return {Promise<string>} A CSRF token suitable for write actions.
	 * @throws {Error} If the API does not return a token.
	 */
	async getEditToken() {
		return this.auth.getEditToken();
	}

	/**
	 * Get a create-account token. The token wil be cached for the next request.
	 *
	 * @return {Promise<string>} A token for create account.
	 * @throws {Error} If the API does not return a token.
	 */
	async getCreateAccountToken() {
		return this.auth.getCreateAccountToken();
	}

	/**
	 * Login the user and get an edit token.
	 *
	 * @param {string} username
	 * @param {string} password
	 * @return {Promise<string>} A CSRF token suitable for write actions.
	 * @throws {Error} If the API does not return a token or the login fails
	 */
	async loginGetEditToken( username, password ) {
		return this.auth.loginGetEditToken( username, password );
	}

	/**
	 * Read content/meta data from one or many wiki pages.
	 *
	 * @param {string} title - for multiple pages use PageA|PageB|PageC
	 * @return {Promise<Object>} The JSON response from the API.
	 * @throws {Error} if something fails when talking to the API
	 */
	async read( title ) {
		return this.pages.read( title );
	}

	/**
	 * Edits a new wiki pages. Creates a new page if it does not exist yet.
	 * Automatically fetches a CSRF token if it's not available.
	 *
	 * @param {string} title
	 * @param {string} text
	 * @param {string} summary
	 * @return {Promise<Object>} The JSON response from the API.
	 * @throws {Error} if something fails when talking to the API
	 */
	async edit( title, text, summary ) {
		return this.pages.edit( title, text, summary );
	}

	/**
	 * Delete a page.
	 *
	 * Automatically fetches a CSRF token if it's not available.
	 *
	 * @param {string} title - Page title to delete.
	 * @param {string} reason - Deletion reason.
	 * @return {Promise<Object>} The JSON response from the API
	 * @throws {Error} if something fails when talking to the API
	 */
	async delete( title, reason ) {
		return this.pages.delete( title, reason );
	}

	/**
	 * Create a new user account.
	 *
	 * @param {string} username - The username for the new account.
	 * @param {string} password - The password for the new account.
	 * @return {Promise<Object>} API response with account creation details.
	 * @throws {Error} if something fails when talking to the API
	 */
	async createAccount( username, password ) {
		return this.user.createAccount( username, password );
	}

	/**
	 * Block a user account.
	 *
	 * @param {string} username - The username to block.
	 * @param {string} expiry - How long the block should last (e.g. "1 day", "infinite").
	 * @return {Promise<Object>} API response for the block action.
	 * @throws {Error} if something fails when talking to the API
	 */
	async blockUser( username, expiry ) {
		return this.user.blockUser( username, expiry );
	}

	/**
	 * Unblock a user account.
	 *
	 * @param {string} username - The username to unblock.
	 * @return {Promise<Object>} API response for the unblock action.
	 * @throws {Error} if something fails when talking to the API
	 */
	async unblockUser( username ) {
		return this.user.unblockUser( username );
	}

	/**
	 * Add a user to a group.
	 *
	 * Skips the request if the user is already in the group.
	 *
	 * @param {string} username - The username to modify.
	 * @param {string} groupName - The group to add the user to.
	 * @return {Promise<void>} Resolves when complete. Throws if API returns an error.
	 *  @throws {Error} if something fails when talking to the API
	 */
	async addUserToGroup( username, groupName ) {
		return this.user.addUserToGroup( username, groupName );
	}
}

/**
 * Create and return an authenticated MediaWiki API client for webdriver.io tests.
 *
 * The factory uses the options configuration, else it falls back to webdriver.io config:
 * - options.baseUrl -  browser.options.baseUrl
 * - options.username - browser.options.capabilities['mw:user']
 * - options.password - browser.options.capabilities['mw:pwd']
 * - options.verbose set to true logs every response from MediaWiki
 *
 * @param {Object} [options={}] Optional api configuration.
 * @return {Promise<Api>} An authenticated API client instance.
 * @throws {Error} If login fails
 *
 * @example
 * // Example configuration:
 * const api = await createApiClient({
 *   baseUrl: 'https://mw.example.org',
 *   username: 'Admin',
 *   password: process.env.MW_PWD,
 *   verbose: true
 * });
 */
export const createApiClient = async function ( options = {} ) {
	const username = options.username || browser.options.capabilities[ 'mw:user' ];
	const password = options.password || browser.options.capabilities[ 'mw:pwd' ];
	const baseUrl = options.baseUrl || browser.options.baseUrl;
	const api = new Api( {
		baseUrl,
		username,
		password,
		verbose: options.verbose ?? false
	} );
	await api.loginGetEditToken( username, password );
	return api;
};
