/**
 * JavaScript for signup form.
 *
 * @module mediawiki.special.createaccount
 */
const HtmlformCheckerV2 = require( './HtmlformCheckerV2.js' );
const mountUsernamePolicyPopover = require( './username-policy-popover.js' );
const SignupValidatorFactory = require( './validators.js' );

/**
 * Minerva: wire “Choose carefully” (username policy popover).
 *
 * @memberof module:mediawiki.special.createaccount
 */
function bootstrapUsernamePolicyPopover( $root ) {
	const usernameHelpButton = $root.find( '#mw-username-help' ).get( 0 );
	if ( !usernameHelpButton ) {
		return;
	}

	function onUsernameHelpClick( ev ) {
		ev.preventDefault();
		usernameHelpButton.removeEventListener( 'click', onUsernameHelpClick, true );
		mountUsernamePolicyPopover( usernameHelpButton );
	}
	usernameHelpButton.addEventListener( 'click', onUsernameHelpClick, true );
}

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
// Attach password hide functionality
mw.hook( 'htmlform.enhance' ).add( async ( $root ) => {
	const $usernameInput = $root.find( '#wpName2' ),
		$passwordInput = $root.find( '#wpPassword2' ),
		$confirmPasswordInput = $root.find( '#wpRetype' ),
		$emailInput = $root.find( '#wpEmail' ),
		$realNameInput = $root.find( '#wpRealName' );

	$usernameInput.on( 'input', () => {
		const originalCaretPosition = $usernameInput[ 0 ].selectionStart;
		const originalUsername = $usernameInput.val();
		const fixedUsername = fixUsername( originalUsername );
		if ( fixedUsername === originalUsername ) {
			return;
		}
		const newCaretPosition = Math.max( 0,
			originalCaretPosition - ( originalUsername.length - fixedUsername.length )
		);
		$usernameInput.val( fixedUsername );
		$usernameInput[ 0 ].setSelectionRange(
			newCaretPosition,
			newCaretPosition
		);
	} );

	function fixUsername( username ) {
		username = username.replace( /_/g, ' ' );
		// trim leading spaces, replace with trimStart() once T419142 is done
		username = username.replace( /^\s+/, '' );
		// Note that this has some minor differences from MediaWiki-core's normalization
		// but is expected to be fully compatible
		username = username.charAt( 0 ).toUpperCase() + username.slice( 1 );
		return username;
	}

	function attachCheckers() {
		const checkerFactory = new SignupValidatorFactory( new mw.Api() );
		const checkUsername = checkerFactory.getUsernameChecker( $usernameInput[ 0 ] );
		const usernameChecker = new HtmlformCheckerV2( $usernameInput, checkUsername, { feedback: true } );
		usernameChecker.attach();

		const checkPassword = checkerFactory.getPasswordChecker(
			$passwordInput[ 0 ],
			$usernameInput[ 0 ],
			$emailInput[ 0 ],
			$realNameInput[ 0 ]
		);
		const passwordChecker = new HtmlformCheckerV2( $passwordInput, checkPassword );
		passwordChecker.attach( $usernameInput.add( $emailInput ).add( $realNameInput ) );

		const checkConfirmPassword = checkerFactory.getConfirmPasswordChecker(
			$passwordInput[ 0 ],
			$confirmPasswordInput[ 0 ]
		);
		const confirmPasswordChecker = new HtmlformCheckerV2( $confirmPasswordInput, checkConfirmPassword );
		confirmPasswordChecker.attach( $passwordInput );
	}

	function attachPasswordRevealFunctionality() {
		Array.from( document.querySelectorAll( '#userlogin2 #wpPassword2, #userlogin2 #wpRetype' ) ).forEach( ( passwordInput ) => {
			const iconElement = Array.from( passwordInput.parentElement.children ).find(
				( element ) => element.classList.contains( 'mw-password-reveal-icon' )
			);
			iconElement.addEventListener( 'click', () => {
				passwordInput.type = passwordInput.type === 'password' ? 'text' : 'password';
			} );
		} );
	}

	attachCheckers();
	attachPasswordRevealFunctionality();
	bootstrapUsernamePolicyPopover( $root );
} );
