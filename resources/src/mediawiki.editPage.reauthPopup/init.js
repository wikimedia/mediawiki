/*!
 * Progressive enhancement for EditPage: display a Reauth AuthPopup when
 * a reauth operation is necessary.  Otherwise fall back to the data stashing
 * user flow.
 *
 * Note: depends on unstable mediawiki.authenticationPopup.
 */
'use strict';

const authPopup = require( 'mediawiki.authenticationPopup' );

const operation = mw.config.get( 'wgReauthOperation' );
const form = document.getElementById( 'editform' );

if ( operation && form ) {
	let inProgress = false;

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

		e.preventDefault();
		inProgress = true;

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
	} );
}
