const { defineStore } = require( 'pinia' );
const { computed, ComputedRef, ref, Ref, watch } = require( 'vue' );
const api = new mw.Api();

module.exports = exports = defineStore( 'block', () => {
	const formErrors = ref( mw.config.get( 'blockPreErrors' ) || [] );
	const formSubmitted = ref( false );
	/**
	 * Whether the block was successful.
	 *
	 * @type {Ref<boolean>}
	 */
	const success = ref( false );
	const targetUser = ref( mw.config.get( 'blockTargetUser' ) || '' );
	const blockId = ref( String );
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

	const watchUser = ref( Boolean );

	const hardBlock = ref( Boolean );
	// eslint-disable-next-line arrow-body-style
	const hardBlockVisible = computed( () => {
		return mw.util.isIPAddress( targetUser.value, true ) || false;
	} );

	/**
	 * Keep track of all UI-blocking API requests that are currently in flight.
	 *
	 * @type {Ref<Set<Promise|jQuery.Promise>>}
	 */
	const promises = ref( new Set() );

	/**
	 * Confirmation dialog message. When not null, the confirmation dialog will be
	 * shown on submission. This is set automatically by a watcher in the store.
	 *
	 * @type {Ref<string>}
	 */
	const confirmationMessage = ref( '' );

	/**
	 * Convenience computed prop indicating if confirmation is needed on submission.
	 *
	 * @type {ComputedRef<boolean>}
	 */
	const confirmationNeeded = computed( () => !!confirmationMessage.value );

	// Show confirm checkbox if 'Hide username' is visible and selected,
	// or if the target user is the current user.
	const computedConfirmation = computed(
		() => [ targetUser.value, hideName.value, hideNameVisible.value ]
	);
	watch(
		computedConfirmation,
		( [ newTargetUser, newHideName, newHideNameVisible ] ) => {
			if ( newHideNameVisible && newHideName ) {
				confirmationMessage.value = mw.message( 'ipb-confirmhideuser' ).parse();
			} else if ( newTargetUser === mw.config.get( 'wgUserName' ) ) {
				confirmationMessage.value = mw.msg( 'ipb-blockingself' );
			} else {
				confirmationMessage.value = '';
			}
		},
		// Ensure confirmationMessage is set on initial load.
		{ immediate: true }
	);

	/**
	 * The current in-flight API request for block log data. This is used to
	 * avoid redundant API queries when rendering multiple BlockLog components.
	 *
	 * @type {Promise|null}
	 */
	let blockLogPromise = null;
	// Reset the blockLogPromise when the target user changes or the form is submitted.
	watch( [ targetUser, formSubmitted ], () => {
		blockLogPromise = null;
	} );

	// ** Actions (exported functions) **

	/**
	 * Load block data from an action=blocks API response.
	 *
	 * @param {Object} blockData The block's item from the API.
	 */
	function loadFromData( blockData ) {
		$reset();
		blockId.value = blockData.id;
		type.value = blockData.partial ? 'partial' : 'sitewide';
		pages.value = blockData.restrictions.pages || [];
		namespaces.value = blockData.restrictions.namespaces || [];
		expiry.value = blockData.expiry;
		reasonOther.value = blockData.reason;
		// @todo Sort out remaining field values.
	}

	/**
	 * Reset the form to its initial state.
	 */
	function $reset() {
		formSubmitted.value = false;
		success.value = false;
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
		const details = mw.config.get( 'blockDetailsPreset' ) || [];
		createAccount.value = details.indexOf( 'wpCreateAccount' ) !== -1;
		disableEmail.value = details.indexOf( 'wpDisableEmail' ) !== -1;
		disableUTEdit.value = details.indexOf( 'wpDisableUTEdit' ) !== -1;
		const additionalDetails = mw.config.get( 'blockAdditionalDetailsPreset' ) || [];
		watchUser.value = additionalDetails.indexOf( 'wpWatch' ) !== -1;
		hardBlock.value = additionalDetails.indexOf( 'wpHardBlock' ) !== -1;
		hideName.value = additionalDetails.indexOf( 'wpHideName' ) !== -1;
		autoBlock.value = additionalDetails.indexOf( 'wpAutoBlock' ) !== -1;
		autoBlockExpiry.value = mw.config.get( 'blockAutoblockExpiry' ) || '';
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

		if ( hideNameVisible.value && hideName.value ) {
			params.hidename = 1;
		}

		if ( watchUser.value ) {
			params.watchuser = 1;
		}

		if ( hardBlock.value ) {
			params.nocreate = 1;
		}

		// Clear any previous errors.
		formErrors.value = [];

		return pushPromise( api.postWithEditToken( params ) );
	}

	/**
	 * Query the API for data needed by the BlockLog component. This method caches the response
	 * by target user to consolidate API requests across multiple BlockLog components.
	 * The cache is cleared when the target user changes by a watcher in the store.
	 *
	 * @param {string} blockLogType Which data to fetch. One of 'recent', 'active', or 'suppress'.
	 * @return {Promise|jQuery.Promise}
	 */
	function getBlockLogData( blockLogType ) {
		if ( blockLogPromise && blockLogType !== 'suppress' ) {
			// Serve block log data from cache if available.
			return blockLogPromise;
		}

		const params = {
			action: 'query',
			format: 'json',
			leprop: 'ids|title|type|user|timestamp|comment|details',
			letitle: `User:${ targetUser.value }`,
			list: 'logevents',
			formatversion: 2
		};

		if ( blockLogType === 'suppress' ) {
			const localPromises = [];
			// Query both the block and reblock actions of the suppression log.
			params.leaction = 'suppress/block';
			localPromises.push( pushPromise( api.get( params ) ) );
			params.leaction = 'suppress/reblock';
			localPromises.push( pushPromise( api.get( params ) ) );
			return Promise.all( localPromises );
		}

		// Cache miss for block log data.
		// Add params needed to fetch block log and active blocks in one request.
		params.list = 'logevents|blocks';
		params.letype = 'block';
		params.bkprop = 'id|user|by|timestamp|expiry|reason|range|flags|restrictions';
		params.bkusers = targetUser.value;

		blockLogPromise = Promise.all( [ api.get( params ) ] );
		return pushPromise( blockLogPromise );
	}

	/**
	 * Add a promise to the `Set` of pending promises.
	 * This is used solely to disable the form while waiting for a response,
	 * and should only be used for requests that need to block UI interaction.
	 * The promise will be removed from the Set when it resolves, and
	 * once the Set is empty, the form will be re-enabled.
	 *
	 * @param {Promise|jQuery.Promise} promise
	 * @return {Promise|jQuery.Promise} The same unresolved promise that was passed in.
	 */
	function pushPromise( promise ) {
		promises.value.add( promise );
		// Can't use .finally() because it's not supported in jQuery.
		promise.then(
			() => promises.value.delete( promise ),
			() => promises.value.delete( promise )
		);
		return promise;
	}

	return {
		formErrors,
		formSubmitted,
		targetUser,
		success,
		blockId,
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
		watchUser,
		hardBlock,
		hardBlockVisible,
		promises,
		confirmationMessage,
		confirmationNeeded,
		loadFromData,
		$reset,
		doBlock,
		getBlockLogData
	};
} );
