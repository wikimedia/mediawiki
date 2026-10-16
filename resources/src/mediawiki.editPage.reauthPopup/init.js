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
	const canResubmit = typeof form.requestSubmit === 'function';
	let inProgress = false;
	let resubmitting = false;
	let resubmitEvent = null;
	let reauthDone = false;
	let submitter = null;

	/**
	 * Unlike form.submit(), this fires the submit event and sends the submitter.
	 *
	 * @ignore
	 */
	const resubmit = () => {
		if ( !canResubmit ) {
			form.submit();
			return;
		}
		resubmitting = true;
		resubmitEvent = null;
		try {
			form.requestSubmit( submitter );
		} catch ( err ) {
			// The submitter left the form. Submit without it, so that the handlers still run.
			form.requestSubmit();
		} finally {
			resubmitting = false;
		}
		// Let the user try again if the submit did not go out (e.g. hCaptcha stopped it).
		inProgress = !!resubmitEvent && !resubmitEvent.defaultPrevented;
	};

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
				// A later submit only needs the server check, not a second popup.
				reauthDone = true;

				// we need to mint a new edit token here, as a successful
				// reauth will have invalidated the previous token
				const api = new mw.Api();
				api.get( { action: 'query', meta: 'tokens' } ).done( ( data ) => {
					$( 'input[name="wpEditToken"]' ).val( data.query.tokens.csrftoken );
					resubmit();
				} ).fail( () => {
					// fall back to session_fail_preview
					resubmit();
				} );
			},
			() => {
				// popup blocked or error: fall back to data stash flow via submit/save
				resubmit();
			}
		);
	};

	// Capture on document to run before the submit handlers of the form.
	document.addEventListener( 'submit', ( e ) => {
		if ( e.target !== form ) {
			return;
		}
		if ( resubmitting ) {
			resubmitEvent = e;
			return;
		}

		// block submit button re-click
		if ( inProgress ) {
			e.preventDefault();
			if ( canResubmit ) {
				e.stopImmediatePropagation();
			}
			return;
		}

		// check we're in form = save state, should mirror EditPage logic
		if (
			!e.submitter ||
			e.submitter.name === 'wpPreview' ||
			e.submitter.name === 'wpDiff' ) {
			return;
		}

		e.preventDefault();
		// Hold other submit handlers until reauth. Only requestSubmit() runs them again.
		if ( canResubmit ) {
			e.stopImmediatePropagation();
		}
		inProgress = true;
		submitter = e.submitter;

		if ( requiredAtRender && !reauthDone ) {
			// Reauth was already required when the page rendered: open the
			// popup synchronously within the submit gesture (existing flow).
			popupThenSubmit();
			return;
		}

		// Reauth was satisfied at render time, but may have expired while the
		// user sat on the form. Verify before letting the POST out.
		reauthNeeded().then( ( needed ) => {
			if ( !needed ) {
				// Still satisfied: proceed with the normal save.
				resubmit();
				return;
			}
			// Expired: reauth via popup. window.open() here happens after an
			// async API call.
			popupThenSubmit();
		}, () => {
			resubmit();
		} );
	}, true );
}
