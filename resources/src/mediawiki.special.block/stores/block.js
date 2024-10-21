const { defineStore } = require( 'pinia' );
const { computed, ref } = require( 'vue' );
const api = new mw.Api();

module.exports = exports = defineStore( 'block', () => {
	const formErrors = ref( mw.config.get( 'blockPreErrors' ) || [] );
	const targetUser = ref( mw.config.get( 'blockTargetUser' ) || '' );
	const alreadyBlocked = ref( mw.config.get( 'blockAlreadyBlocked' ) || false );
	const type = ref( mw.config.get( 'blockTypePreset' ) || 'sitewide' );
	const expiry = ref( '' );
	const partialOptions = ref( [ 'ipb-action-create' ] );
	const pages = ref(
		( mw.config.get( 'blockPageRestrictions' ) || '' )
			.split( '\n' )
			.filter( Boolean )
	);
	const namespaces = ref(
		( mw.config.get( 'blockNamespaceRestrictions' ) || '' )
			.split( '\n' )
			.filter( Boolean )
			.map( Number )
	);
	const reason = ref( 'other' );
	const reasonOther = ref( mw.config.get( 'blockReasonOtherPreset' ) || '' );
	const details = ref( mw.config.get( 'blockDetailsPreset' ) || [] );
	// Disable the 'Hide username' checkbox if the block type is not sitewide with an 'infinite' expiry.
	// eslint-disable-next-line arrow-body-style
	const hideNameDisabled = computed( () => {
		return type.value !== 'sitewide' || !mw.util.isInfinity( expiry.value );
	} );
	const additionalDetails = ref( mw.config.get( 'blockAdditionalDetailsPreset' ) || [] );
	// Show confirm checkbox if 'Hide username' is selected or if the target user is the current user.
	// eslint-disable-next-line arrow-body-style
	const confirmationRequired = computed( () => {
		return targetUser.value === mw.config.get( 'wgUserName' ) || (
			type.value === 'sitewide' &&
			additionalDetails.value.indexOf( 'wpHideName' ) !== -1 &&
			!hideNameDisabled.value
		);
	} );
	const confirmationChecked = ref( false );

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
			params.actionrestrictions = actionRestrictions.join( '|' );

			if ( pages.value.length ) {
				params.pagerestrictions = pages.value.join( '|' );
			}
			if ( namespaces.value.length ) {
				params.namespacerestrictions = namespaces.value.join( '|' );
			}
		}

		if ( details.value.indexOf( 'wpCreateAccount' ) !== -1 ) {
			params.nocreate = 1;
		}

		if ( details.value.indexOf( 'wpDisableEmail' ) !== -1 ) {
			params.noemail = 1;
		}

		if ( details.value.indexOf( 'wpDisableUTEdit' ) === -1 ) {
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
		pages,
		namespaces,
		reason,
		reasonOther,
		details,
		hideNameDisabled,
		additionalDetails,
		confirmationRequired,
		confirmationChecked,
		doBlock
	};
} );
