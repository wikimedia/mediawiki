class SignupValidatorFactory {

	/**
	 * @param {MwApi} api
	 */
	constructor( api ) {
		/**
		 * @private
		 * @type {MwApi}
		 */
		this.api = api;
	}

	/**
	 * @param {HTMLInputElement} usernameInputElement
	 * @return {(function(AbortSignal): Promise<{valid: boolean, messages: Array<JQuery<HTMLElement>|string>, type: 'warning'|'success'|'error'}>)}
	 */

	getUsernameChecker( usernameInputElement ) {

		/**
		 * @param {AbortSignal} signal
		 * @return {Promise<{valid: boolean, messages: Array<JQuery<HTMLElement>|string>, type: 'warning'|'success'|'error'}>}
		 */
		const checkUsername = async ( signal ) => {
			let username = usernameInputElement.value;
			// Leading/trailing/multiple whitespace characters are always stripped in usernames,
			// this should not require a warning. We do warn about underscores.
			username = username.replace( / +/g, ' ' ).trim();

			return this.api.get( {
				action: 'query',
				list: 'users',
				ususers: username,
				usprop: 'cancreate',
				formatversion: 2,
				errorformat: 'html',
				errorsuselocal: true,
				uselang: mw.config.get( 'wgUserLanguage' )
			}, { signal } )
				.then( ( resp ) => {
					const userinfo = resp.query.users[ 0 ];

					if ( resp.query.users.length !== 1 || userinfo.invalid ) {
						mw.track( 'specialCreateAccount.validationErrors', [ 'no_user_name' ] );
						return {
							valid: false,
							messages: [ mw.message( 'noname' ).parseDom() ],
							type: 'error'
						};
					} else if ( userinfo.userid !== undefined ) {
						mw.track( 'specialCreateAccount.validationErrors', [ 'user_exists' ] );
						return {
							valid: false,
							messages: [ mw.message( 'userexists' ).parseDom() ],
							type: 'warning'
						};
					} else if ( !userinfo.cancreate ) {
						const canCreateErrors = userinfo.cancreateerror || [];
						mw.track(
							'specialCreateAccount.validationErrors',
							canCreateErrors.map( ( m ) => m.code.replace( '-', '_' ) )
						);

						return {
							valid: false,
							messages: canCreateErrors.map( ( m ) => m.html ),
							type: 'warning'
						};
					} else if ( userinfo.name !== username ) {
						return {
							valid: true,
							messages: [ mw.message( 'createacct-normalization', username, userinfo.name ).parseDom() ],
							type: 'success'
						};
					} else {
						return {
							valid: true,
							messages: [ mw.message( 'available-username' ).parseDom() ],
							type: 'success'
						};
					}
				} );
		};

		return checkUsername;
	}

	/**
	 * @param {HTMLInputElement} passwordInputElement
	 * @param {HTMLInputElement} usernameInputElement
	 * @param {HTMLInputElement|undefined} emailInputElement
	 * @param {HTMLInputElement|undefined} realNameInputElement
	 * @return {(function(AbortSignal): Promise<{valid: boolean, messages: Array<JQuery<HTMLElement>|string>, type: 'warning'|'success'|'error'}>)}
	 */

	getPasswordChecker(
		passwordInputElement,
		usernameInputElement,
		emailInputElement,
		realNameInputElement
	) {

		/**
		 * @param {AbortSignal} signal
		 * @return {Promise<{valid: boolean, messages: Array<JQuery<HTMLElement>|string>, type: 'warning'|'success'|'error'}>}
		 */
		const checkPassword = async ( signal ) => {
			if ( usernameInputElement.value.trim() === '' ) {
				return { valid: true, messages: [], type: 'success' };
			}

			mw.track( 'stats.mediawiki_signup_validatepassword_total' );

			return this.api.post( {
				action: 'validatepassword',
				user: usernameInputElement.value,
				password: passwordInputElement.value,
				email: emailInputElement ? emailInputElement.value : '',
				realname: realNameInputElement ? realNameInputElement.value : '',
				formatversion: 2,
				errorformat: 'html',
				errorsuselocal: true,
				uselang: mw.config.get( 'wgUserLanguage' )
			}, { signal } )
				.then( ( resp ) => {
					const pwinfo = resp.validatepassword || {};
					const validityMessages = pwinfo.validitymessages || [];
					const valid = pwinfo.validity === 'Good';

					mw.track(
						'specialCreateAccount.validationErrors',
						validityMessages.map( ( m ) => m.code )
					);

					return {
						valid,
						messages: validityMessages.map( ( m ) => m.html ),
						type: valid ? 'success' : 'warning'
					};
				} );
		};

		return checkPassword;
	}

	/**
	 * @param {HTMLInputElement} passwordInputElement
	 * @param {HTMLInputElement} confirmPasswordInputElement
	 * @return {(function(): Promise<{valid: boolean, messages: Array<JQuery<HTMLElement>|string>, type: 'warning'|'success'|'error'}>)}
	 */

	getConfirmPasswordChecker( passwordInputElement, confirmPasswordInputElement ) {
		/**
		 * @return {Promise<{valid: boolean, messages: Array<JQuery<HTMLElement>|string>, type: 'warning'|'success'|'error'}>}
		 */
		async function checkConfirmPassword() {
			const password = passwordInputElement.value;
			const confirmedPassword = confirmPasswordInputElement.value;
			if ( password === '' || confirmedPassword === '' || password === confirmedPassword ) {
				return {
					valid: true,
					messages: [],
					type: 'success'
				};
			}

			return {
				valid: false,
				messages: [ mw.message( 'badretype' ).parseDom() ],
				type: 'warning'
			};
		}

		return checkConfirmPassword;
	}
}

module.exports = SignupValidatorFactory;
