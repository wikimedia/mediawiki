/**
 * JavaScript for signup form.
 *
 * @module mediawiki.special.createaccount
 */
const HtmlformChecker = require( './HtmlformChecker.js' );
const HtmlformCheckerV2 = require( './HtmlformCheckerV2.js' );
const mountUsernamePolicyPopover = require( './username-policy-popover.js' );

// When sending password by email, hide the password input fields.
$( () => {
	// Always required if checked, otherwise it depends, so we use the original
	const $emailLabel = $( 'label[for="wpEmail"] .cdx-label__label__text' ),
		originalText = $emailLabel.text(),
		requiredText = mw.msg( 'createacct-emailrequired' ),
		$createByMailCheckbox = $( '#wpCreateaccountMail' ),
		$beforePwds = $( '.mw-row-password' ).first().prev();
	let $pwds;

	function updateForCheckbox() {
		const checked = $createByMailCheckbox.prop( 'checked' );
		if ( checked ) {
			$pwds = $( '.mw-row-password' ).detach();
			// TODO when this uses the optional flag, show/hide that instead of changing the text
			$emailLabel.text( requiredText );
		} else {
			if ( $pwds ) {
				$beforePwds.after( $pwds );
				$pwds = null;
			}
			$emailLabel.text( originalText );
		}
	}

	$createByMailCheckbox.on( 'change', updateForCheckbox );
	updateForCheckbox();
} );

// Check if the username is invalid or already taken; show username normalisation warning
mw.hook( 'htmlform.enhance' ).add( ( $root ) => {
	const $usernameInput = $root.find( '#wpName2' ),
		$passwordInput = $root.find( '#wpPassword2' ),
		$emailInput = $root.find( '#wpEmail' ),
		$realNameInput = $root.find( '#wpRealName' ),
		api = new mw.Api();

	function checkUsername( username, signal ) {
		// Leading/trailing/multiple whitespace characters are always stripped in usernames,
		// this should not require a warning. We do warn about underscores.
		username = username.replace( / +/g, ' ' ).trim();

		return api.get( {
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
						type: ( canCreateErrors.length && canCreateErrors[ 0 ].code === 'userexists' ) ?
							'warning' : 'error'
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
	}

	function checkPassword( _password, signal ) {
		if ( $usernameInput.val().trim() === '' ) {
			return $.Deferred().resolve( { valid: true, messages: [] } );
		}

		mw.track( 'stats.mediawiki_signup_validatepassword_total' );

		return api.post( {
			action: 'validatepassword',
			user: $usernameInput.val(),
			password: $passwordInput.val(),
			email: $emailInput.val() || '',
			realname: $realNameInput.val() || '',
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
	}

	function attachCheckers( CheckerFn ) {
		const usernameChecker = new CheckerFn( $usernameInput, checkUsername, { feedback: true } );
		usernameChecker.attach();

		const passwordChecker = new CheckerFn( $passwordInput, checkPassword );
		passwordChecker.attach( $usernameInput.add( $emailInput ).add( $realNameInput ) );
	}

	const experimentPromise = mw.loader.using( [
		'ext.testKitchen'
	] )
		.then( () => mw.testKitchen.getExperiment( 'we-1-8-account-creation-form-v2' ) )
		.catch( ( error ) => {
			mw.log( 'Error loading ext.testKitchen module:', error );
			return null;
		} );

	if ( mw.config.get( 'skin' ) === 'minerva' ) {
		experimentPromise.then( ( experiment ) => {
			if ( !( experiment && experiment.isAssignedGroup( 'treatment' ) ) ) {
				attachCheckers( HtmlformChecker );
				return;
			}
			attachCheckers( HtmlformCheckerV2 );
		} );
	} else {
		// Experiment is just for Minerva, skip enrollment checks
		attachCheckers( HtmlformChecker );
	}

} );

/**
 * Minerva: wire “Choose carefully” (username policy popover).
 *
 * @memberof module:mediawiki.special.createaccount
 */
function bootstrapUsernamePolicyPopover() {
	if ( mw.config.get( 'skin' ) !== 'minerva' ) {
		return;
	}
	const chooseCarefullyLink = document.querySelector(
		'a.mw-createaccount-username-policy-choose-carefully'
	);
	if ( !chooseCarefullyLink ) {
		return;
	}
	chooseCarefullyLink.removeAttribute( 'target' );
	chooseCarefullyLink.setAttribute( 'href', '#' );

	function onChooseCarefullyClick( ev ) {
		ev.preventDefault();
		chooseCarefullyLink.removeEventListener( 'click', onChooseCarefullyClick, true );
		mountUsernamePolicyPopover( chooseCarefullyLink, { openOnMount: true } );
	}
	chooseCarefullyLink.addEventListener( 'click', onChooseCarefullyClick, true );
}

$( bootstrapUsernamePolicyPopover );
