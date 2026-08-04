/*!
 * Progressive enhancement for EditPage: display a Reauth AuthPopup when
 * a reauth operation is necessary. Otherwise fall back to the data stashing
 * user flow.
 *
 * If reauthentication was already satisfied when the page rendered, the
 * requirement may still expire while the user sits on the form (T430197);
 * in that case we verify with the server on submit and only open the popup
 * when reauthentication is actually needed.
 *
 * Note: depends on unstable mediawiki.authenticationPopup.
 */
'use strict';

const authPopup = require( 'mediawiki.authenticationPopup' );

const operation = mw.config.get( 'wgReauthOperation' );
const requiredAtRender = mw.config.get( 'wgReauthCurrentlyRequired' );
const form = document.getElementById( 'editform' );

if ( operation && form ) {
	let inProgress = false;

	/**
	 * Ask the server whether the operation currently requires reauthentication.
	 * This is a read-only check (AuthManager::securitySensitiveOperationStatus).
	 *
	 * @ignore
	 * @return {jQuery.Promise<boolean>} Resolves true if reauth is (still) needed
	 */
	const reauthNeeded = () => ( new mw.Api() ).get( {
		action: 'query',
		meta: 'authmanagerinfo',
		amisecuritysensitiveoperation: operation
	} ).then( ( resp ) => resp.query.authmanagerinfo.securitysensitiveoperationstatus !== 'ok' );

	/**
	 * Open the reauthentication popup; on success, mint a fresh edit token
	 * (reauth invalidates the old one) and submit the form.
	 *
	 * @ignore
	 */
	const popupThenSubmit = () => {
		authPopup.forReauthentication( operation ).startPopupWindow().then(
			( ok ) => {
				if ( !ok ) {
					// canceled, send back to edit
					inProgress = false;
					return;
				}

				// we need to mint a new edit token here, as a successful
				// reauth will have invalidated the previous token
				const api = new mw.Api();
				api.get( { action: 'query', meta: 'tokens' } ).done( ( data ) => {
					$( 'input[name="wpEditToken"]' ).val( data.query.tokens.csrftoken );
					form.submit();
				} ).fail( () => {
					// fall back to session_fail_preview
					form.submit();
				} );
			},
			() => {
				// popup blocked or error: fall back to data stash flow via submit/save
				form.submit();
			}
		);
	};

	form.addEventListener( 'submit', ( e ) => {
		// block submit button re-click
		if ( inProgress ) {
			e.preventDefault();
			return;
		}

		// check we're in form = save state, should mirror EditPage logic
		if (
			!e.submitter ||
			e.submitter.name === 'wpPreview' ||
			e.submitter.name === 'wpDiff' ) {
			return;
		}

		if ( requiredAtRender ) {
			// Reauth was already required when the page rendered: open the
			// popup synchronously within the submit gesture (existing flow).
			e.preventDefault();
			inProgress = true;
			popupThenSubmit();
			return;
		}

		// Reauth was satisfied at render time, but may have expired while the
		// user sat on the form. Verify before letting the POST out.
		e.preventDefault();
		inProgress = true;
		reauthNeeded().then( ( needed ) => {
			if ( !needed ) {
				// Still satisfied: proceed with the normal save.
				// Note form.submit() does not re-fire this 'submit' handler.
				form.submit();
				return;
			}
			// Expired: reauth via popup. window.open() here happens after an
			// async API call.
			popupThenSubmit();
		}, () => {
			form.submit();
		} );
	} );
}
