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

	const createAccount = ref( details.value.indexOf( 'wpCreateAccount' ) !== -1 );
	const disableEmail = ref( details.value.indexOf( 'wpDisableEmail' ) !== -1 );
	const disableEmailVisible = ref( mw.config.get( 'blockDisableEmailVisible' ) || false );
	const disableUTEdit = ref( details.value.indexOf( 'wpDisableUTEdit' ) !== -1 );
	const disableUTEditVisible = computed( () => {
		const isVisible = mw.config.get( 'blockDisableUTEditVisible' ) || false;
		const isPartial = type.value === 'partial';
		const blocksUT = namespaces.value.indexOf( mw.config.get( 'wgNamespaceIds' ).user_talk ) !== -1;
		return isVisible && ( !isPartial || ( isPartial && blocksUT ) );
	} );

	const additionalDetails = ref( mw.config.get( 'blockAdditionalDetailsPreset' ) || [] );

	const autoBlock = ref( additionalDetails.value.indexOf( 'wpAutoBlock' ) !== -1 );
	const autoBlockExpiry = mw.config.get( 'blockAutoblockExpiry' ) || '';
	// eslint-disable-next-line arrow-body-style
	const autoBlockVisible = computed( () => {
		return !mw.util.isIPAddress( targetUser.value, true );
	} );

	const hideName = ref( additionalDetails.value.indexOf( 'wpHideName' ) !== -1 );
	// Hide the 'Hide username' checkbox if the user doesn't have the hideuser right (this is passed from PHP),
	// and the block is not sitewide and infinite.
	const hideNameVisible = computed( () => {
		const typeVal = type.value;
		return mw.config.get( 'blockHideUser' ) &&
			typeVal === 'sitewide' &&
			mw.util.isInfinity( expiry.value );
	} );

	const watch = ref( additionalDetails.value.indexOf( 'wpWatch' ) !== -1 );

	const hardBlock = ref( additionalDetails.value.indexOf( 'wpHardBlock' ) !== -1 );
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
		doBlock
	};
} );
