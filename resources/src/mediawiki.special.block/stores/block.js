const { defineStore } = require( 'pinia' );
const { computed, ref } = require( 'vue' );
const api = new mw.Api();

module.exports = exports = defineStore( 'block', () => {
	const formErrors = ref( mw.config.get( 'blockPreErrors' ) || [] );
	const targetUser = ref( mw.config.get( 'blockTargetUser' ) || '' );
	const alreadyBlocked = ref( Boolean );
	const type = ref( String );
	const expiry = ref( String );
	const partialOptions = ref( Array );
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
	const reason = ref( String );
	const reasonOther = ref( String );
	const details = ref( mw.config.get( 'blockDetailsPreset' ) || [] );
	const createAccount = ref( Boolean );
	const disableEmail = ref( Boolean );
	const disableEmailVisible = ref( mw.config.get( 'blockDisableEmailVisible' ) || false );
	const disableUTEdit = ref( Boolean );
	const disableUTEditVisible = computed( () => {
		const isVisible = mw.config.get( 'blockDisableUTEditVisible' ) || false;
		const isPartial = type.value === 'partial';
		const blocksUT = namespaces.value.indexOf( mw.config.get( 'wgNamespaceIds' ).user_talk ) !== -1;
		return isVisible && ( !isPartial || ( isPartial && blocksUT ) );
	} );

	const additionalDetails = ref( mw.config.get( 'blockAdditionalDetailsPreset' ) || [] );

	const autoBlock = ref( Boolean );
	const autoBlockExpiry = mw.config.get( 'blockAutoblockExpiry' ) || '';
	// eslint-disable-next-line arrow-body-style
	const autoBlockVisible = computed( () => {
		return !mw.util.isIPAddress( targetUser.value, true );
	} );

	const hideName = ref( Boolean );
	// Hide the 'Hide username' checkbox if the user doesn't have the hideuser right (this is passed from PHP),
	// and the block is not sitewide and infinite.
	const hideNameVisible = computed( () => {
		const typeVal = type.value;
		return mw.config.get( 'blockHideUser' ) &&
			typeVal === 'sitewide' &&
			mw.util.isInfinity( expiry.value );
	} );

	const watch = ref( Boolean );

	const hardBlock = ref( Boolean );
	// eslint-disable-next-line arrow-body-style
	const hardBlockVisible = computed( () => {
		return mw.util.isIPAddress( targetUser.value, true ) || false;
	} );

	// Show confirm checkbox if 'Hide username' is visible and selected, or if the target user is the current user.
	// eslint-disable-next-line arrow-body-style
	const confirmationRequired = computed( () => {
		return targetUser.value === mw.config.get( 'wgUserName' ) || (
			type.value === 'sitewide' &&
			hideNameVisible.value &&
			hideName.value
		);
	} );

	const confirmationChecked = ref( Boolean );

	function $reset() {
		alreadyBlocked.value = mw.config.get( 'blockAlreadyBlocked' ) || false;
		type.value = mw.config.get( 'blockTypePreset' ) || 'sitewide';
		pages.value = ( mw.config.get( 'blockPageRestrictions' ) || '' )
			.split( '\n' )
			.filter( Boolean );
		namespaces.value = ( mw.config.get( 'blockNamespaceRestrictions' ) || '' )
			.split( '\n' )
			.filter( Boolean )
			.map( Number );

		expiry.value = mw.config.get( 'blockExpiryPreset' ) || mw.config.get( 'blockExpiryDefault' ) || '';
		partialOptions.value = [ 'ipb-action-create' ];
		reason.value = 'other';
		reasonOther.value = mw.config.get( 'blockReasonOtherPreset' ) || '';
		createAccount.value = details.value.indexOf( 'wpCreateAccount' ) !== -1;
		disableEmail.value = details.value.indexOf( 'wpDisableEmail' ) !== -1;
		disableUTEdit.value = details.value.indexOf( 'wpDisableUTEdit' ) !== -1;
		watch.value = additionalDetails.value.indexOf( 'wpWatch' ) !== -1;
		hardBlock.value = additionalDetails.value.indexOf( 'wpHardBlock' ) !== -1;
		hideName.value = additionalDetails.value.indexOf( 'wpHideName' ) !== -1;
		autoBlock.value = additionalDetails.value.indexOf( 'wpAutoBlock' ) !== -1;
		autoBlockExpiry.value = mw.config.get( 'blockAutoblockExpiry' ) || '';
		confirmationChecked.value = false;
	}

	/**
	 * Execute the block.
	 *
	 * @return {jQuery.Promise}
	 */
	function doBlock() {
		const params = {
			action: 'block',
			format: 'json',
			user: targetUser.value,
			expiry: expiry.value,
			// Localize errors
			uselang: mw.config.get( 'wgUserLanguage' ),
			errorlang: mw.config.get( 'wgUserLanguage' ),
			errorsuselocal: true
		};

		if ( alreadyBlocked.value ) {
			params.reblock = 1;
		}

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

		if ( createAccount.value ) {
			params.nocreate = 1;
		}

		if ( disableEmail.value ) {
			params.noemail = 1;
		}

		if ( !disableUTEdit.value ) {
			params.allowusertalk = 1;
		}

		if ( autoBlock.value ) {
			params.autoblock = 1;
		}

		if ( hideName.value ) {
			params.hidename = 1;
		}

		if ( watch.value ) {
			params.watchuser = 1;
		}

		if ( hardBlock.value ) {
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
		createAccount,
		disableEmail,
		disableEmailVisible,
		disableUTEdit,
		disableUTEditVisible,
		autoBlock,
		autoBlockExpiry,
		autoBlockVisible,
		hideName,
		hideNameVisible,
		watch,
		hardBlock,
		hardBlockVisible,
		confirmationRequired,
		confirmationChecked,
		doBlock,
		$reset
	};
} );
