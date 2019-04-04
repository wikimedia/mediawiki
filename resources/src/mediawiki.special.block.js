/*!
 * JavaScript for Special:Block
 */
( function () {
	// Like OO.ui.infuse(), but if the element doesn't exist, return null instead of throwing an exception.
	function infuseIfExists( $el ) {
		if ( !$el.length ) {
			return null;
		}
		return OO.ui.infuse( $el );
	}

	$( function () {
		// This code is also loaded on the "block succeeded" page where there is no form,
		// so username and expiry fields might also be missing.
		var blockTargetWidget = infuseIfExists( $( '#mw-bi-target' ) ),
			anonOnlyField = infuseIfExists( $( '#mw-input-wpHardBlock' ).closest( '.oo-ui-fieldLayout' ) ),
			enableAutoblockField = infuseIfExists( $( '#mw-input-wpAutoBlock' ).closest( '.oo-ui-fieldLayout' ) ),
			hideUserWidget = infuseIfExists( $( '#mw-input-wpHideUser' ) ),
			hideUserField = infuseIfExists( $( '#mw-input-wpHideUser' ).closest( '.oo-ui-fieldLayout' ) ),
			watchUserField = infuseIfExists( $( '#mw-input-wpWatch' ).closest( '.oo-ui-fieldLayout' ) ),
			expiryWidget = infuseIfExists( $( '#mw-input-wpExpiry' ) ),
			editingWidget = infuseIfExists( $( '#mw-input-wpEditing' ) ),
			editingRestrictionWidget = infuseIfExists( $( '#mw-input-wpEditingRestriction' ) ),
			preventTalkPageEdit = infuseIfExists( $( '#mw-input-wpDisableUTEdit' ) ),
			pageRestrictionsWidget = infuseIfExists( $( '#mw-input-wpPageRestrictions' ) ),
			namespaceRestrictionsWidget = infuseIfExists( $( '#mw-input-wpNamespaceRestrictions' ) ),
			createAccountWidget = infuseIfExists( $( '#mw-input-wpCreateAccount' ) ),
			userChangedCreateAccount = mw.config.get( 'wgCreateAccountDirty' ),
			updatingBlockOptions = false;

		function updateBlockOptions() {
			var blocktarget = blockTargetWidget.getValue().trim(),
				isEmpty = blocktarget === '',
				isIp = mw.util.isIPAddress( blocktarget, true ),
				isIpRange = isIp && blocktarget.match( /\/\d+$/ ),
				isNonEmptyIp = isIp && !isEmpty,
				expiryValue = expiryWidget.getValue(),
				// infinityValues are the values the SpecialBlock class accepts as infinity (sf. wfIsInfinity)
				infinityValues = [ 'infinite', 'indefinite', 'infinity', 'never' ],
				isIndefinite = infinityValues.indexOf( expiryValue ) !== -1,
				// editingRestrictionWidget only exists if partial blocks is enabled; if not, block must be sitewide
				editingRestrictionValue = editingRestrictionWidget ? editingRestrictionWidget.getValue() : 'sitewide',
				editingIsSelected = editingWidget ? editingWidget.isSelected() : false,
				isSitewide = editingIsSelected && editingRestrictionValue === 'sitewide';

			if ( enableAutoblockField ) {
				enableAutoblockField.toggle( !isNonEmptyIp );
			}
			if ( hideUserField ) {
				hideUserField.toggle( !isNonEmptyIp && isIndefinite && isSitewide );
				if ( !hideUserField.isVisible() ) {
					hideUserWidget.setSelected( false );
				}
			}
			if ( anonOnlyField ) {
				anonOnlyField.toggle( isIp || isEmpty );
			}
			if ( watchUserField ) {
				watchUserField.toggle( !isIpRange || isEmpty );
			}
			if ( editingRestrictionWidget ) {
				editingRestrictionWidget.setDisabled( !editingIsSelected );
			}
			if ( pageRestrictionsWidget ) {
				pageRestrictionsWidget.setDisabled( !editingIsSelected || isSitewide );
			}
			if ( namespaceRestrictionsWidget ) {
				namespaceRestrictionsWidget.setDisabled( !editingIsSelected || isSitewide );
			}
			if ( preventTalkPageEdit && namespaceRestrictionsWidget ) {
				// This option is disabled for partial blocks unless a namespace restriction
				// for the User_talk namespace is in place.
				preventTalkPageEdit.setDisabled(
					editingIsSelected &&
					editingRestrictionValue === 'partial' &&
					namespaceRestrictionsWidget.getValue().indexOf(
						String( mw.config.get( 'wgNamespaceIds' ).user_talk )
					) === -1
				);
			}
			if ( !userChangedCreateAccount ) {
				updatingBlockOptions = true;
				createAccountWidget.setSelected( isSitewide );
				updatingBlockOptions = false;
			}

		}

		if ( blockTargetWidget ) {
			// Bind functions so they're checked whenever stuff changes
			blockTargetWidget.on( 'change', updateBlockOptions );
			expiryWidget.on( 'change', updateBlockOptions );
			if ( editingWidget ) {
				editingWidget.on( 'change', updateBlockOptions );
			}
			if ( editingRestrictionWidget ) {
				editingRestrictionWidget.on( 'change', updateBlockOptions );
			}
			if ( namespaceRestrictionsWidget ) {
				namespaceRestrictionsWidget.on( 'change', updateBlockOptions );
			}

			createAccountWidget.on( 'change', function () {
				if ( !updatingBlockOptions ) {
					userChangedCreateAccount = true;
				}
			} );

			// Call them now to set initial state (ie. Special:Block/Foobar?wpBlockExpiry=2+hours)
			updateBlockOptions();
		}
	} );
}() );
