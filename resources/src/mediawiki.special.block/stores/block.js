const { defineStore } = require( 'pinia' );
const { ref } = require( 'vue' );
const api = new mw.Api();

module.exports = exports = defineStore( 'block', () => {
	const formErrors = ref( mw.config.get( 'blockPreErrors' ) || [] );
	const targetUser = ref( mw.config.get( 'blockTargetUser' ) || '' );
	const alreadyBlocked = ref( mw.config.get( 'blockAlreadyBlocked' ) || false );
	const type = ref( mw.config.get( 'blockTypePreset' ) || 'sitewide' );
	const expiry = ref( '' );
	const partialOptions = ref( [ 'ipb-action-create' ] );
	const reason = ref( 'other' );
	const reasonOther = ref( mw.config.get( 'blockReasonOtherPreset' ) || '' );
	const details = ref( mw.config.get( 'blockDetailsPreset' ) || [] );
	const additionalDetails = ref( mw.config.get( 'blockAdditionalDetailsPreset' ) || [] );

	/**
	 * Execute the block.
	 *
	 * @return {jQuery.Promise}
	 */
	function doBlock() {
		const params = {
			action: 'block',
			reblock: alreadyBlocked.value ? 1 : 0,
			format: 'json',
			user: targetUser.value,
			// Remove browser-specific milliseconds for consistency.
			expiry: expiry.value.replace( /\.000$/, '' ),
			// Localize errors
			uselang: mw.config.get( 'wgUserLanguage' ),
			errorlang: mw.config.get( 'wgUserLanguage' ),
			errorsuselocal: true
		};

		// Reason selected concatenated with 'Other' field
		if ( reason.value === 'other' ) {
			params.reason = reasonOther.value;
		} else {
			params.reason = reason.value + (
				reasonOther.value ? mw.msg( 'colon-separator' ) + reasonOther.value : ''
			);
		}

		if ( type.value === 'partial' ) {
			const actionRestrictions = [];
			params.partial = 1;
			if ( partialOptions.value.indexOf( 'ipb-action-upload' ) !== -1 ) {
				actionRestrictions.push( 'upload' );
			}
			if ( partialOptions.value.indexOf( 'ipb-action-move' ) !== -1 ) {
				actionRestrictions.push( 'move' );
			}
			if ( partialOptions.value.indexOf( 'ipb-action-create' ) !== -1 ) {
				actionRestrictions.push( 'create' );
			}
			params.actionRestrictions = actionRestrictions.join( '|' );
		}

		if ( details.value.indexOf( 'wpCreateAccount' ) !== -1 ) {
			params.nocreate = 1;
		}

		if ( details.value.indexOf( 'wpDisableEmail' ) !== -1 ) {
			params.noemail = 1;
		}

		if ( details.value.indexOf( 'wpDisableUTEdit' ) !== -1 ) {
			params.allowusertalk = 1;
		}

		if ( additionalDetails.value.indexOf( 'wpAutoBlock' ) !== -1 ) {
			params.autoblock = 1;
		}

		if ( additionalDetails.value.indexOf( 'wpHideName' ) !== -1 ) {
			params.hidename = 1;
		}

		if ( additionalDetails.value.indexOf( 'wpWatch' ) !== -1 ) {
			params.watchuser = 1;
		}

		if ( additionalDetails.value.indexOf( 'wpHardBlock' ) !== -1 ) {
			params.nocreate = 1;
		}

		// Clear any previous errors.
		formErrors.value = [];

		return api.postWithEditToken( params );
	}

	return {
		formErrors,
		targetUser,
		alreadyBlocked,
		type,
		expiry,
		partialOptions,
		reason,
		reasonOther,
		details,
		additionalDetails,
		doBlock
	};
} );
