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

	$( () => {
		let blockTargetWidget, anonOnlyWidget, enableAutoblockWidget, hideUserWidget, watchUserWidget = null,
			expiryWidget, editingRestrictionWidget, partialActionsRestrictionsWidget, preventTalkPageEditWidget,
			pageRestrictionsWidget, namespaceRestrictionsWidget, createAccountWidget,
			userChangedCreateAccount, updatingBlockOptions;

		function preserveSelectedStateOnDisable( widget ) {
			let widgetWasSelected;

			if ( !widget ) {
				return;
			}

			// 'disable' event fires if disabled state changes
			widget.on( 'disable', ( disabled ) => {
				if ( disabled ) {
					// Disabling an enabled widget
					// Save selected and set selected to false
					widgetWasSelected = widget.isSelected();
					widget.setSelected( false );
				} else {
					// Enabling a disabled widget
					// Set selected to the saved value
					if ( widgetWasSelected !== undefined ) {
						widget.setSelected( widgetWasSelected );
					}
					widgetWasSelected = undefined;
				}
			} );
		}

		function updateBlockOptions() {
			const blocktarget = blockTargetWidget.getValue().trim(),
				isEmpty = blocktarget === '',
				isIp = mw.util.isIPAddress( blocktarget, true ),
				isNonEmptyIp = isIp && !isEmpty,
				expiryValue = expiryWidget.getValue(),
				isIndefinite = mw.util.isInfinity( expiryValue ),
				editingRestrictionValue = editingRestrictionWidget.getValue(),
				isSitewide = editingRestrictionValue === 'sitewide';

			enableAutoblockWidget.setDisabled( isNonEmptyIp );

			anonOnlyWidget.setDisabled( !isIp && !isEmpty );

			if ( hideUserWidget ) {
				hideUserWidget.setDisabled( isNonEmptyIp || !isIndefinite || !isSitewide );
			}

			updateWatchOption( blocktarget );

			pageRestrictionsWidget.setDisabled( isSitewide );
			namespaceRestrictionsWidget.setDisabled( isSitewide );
			if ( preventTalkPageEditWidget ) {
				// Disable for partial blocks, unless the block is against the User_talk namespace
				preventTalkPageEditWidget.setDisabled(
					// Partial block that blocks editing and doesn't block the User_talk namespace
					(
						editingRestrictionValue === 'partial' &&
						!namespaceRestrictionsWidget.getValue().includes( String( mw.config.get( 'wgNamespaceIds' ).user_talk ) )
					)
				);
			}

			if ( !userChangedCreateAccount ) {
				updatingBlockOptions = true;
				createAccountWidget.setSelected( isSitewide );
				updatingBlockOptions = false;
			}

			if ( partialActionsRestrictionsWidget ) {
				partialActionsRestrictionsWidget.setDisabled( isSitewide );
			}
		}

		function updateWatchOption( blocktarget ) {
			const isEmpty = blocktarget === '',
				isIp = mw.util.isIPAddress( blocktarget, true ),
				isIpRange = isIp && blocktarget.match( /\/\d+$/ ),
				isAutoBlock = blocktarget.match( /^#\d+$/ );

			if ( watchUserWidget ) {
				watchUserWidget.setDisabled( ( isAutoBlock || isIpRange ) && !isEmpty );
			}
		}

		watchUserWidget = infuseIfExists( $( '#mw-input-wpWatch' ) );
		if ( mw.config.get( 'wgCanonicalSpecialPageName' ) === 'Unblock' ) {
			const $wpTarget = $( '#mw-input-wpTarget' );
			if ( $wpTarget.attr( 'type' ) === 'hidden' ) {
				// target is not changeable, determine watch state once
				updateWatchOption( $wpTarget.val() );
				return;
			}
			blockTargetWidget = infuseIfExists( $wpTarget );
			if ( blockTargetWidget ) {
				blockTargetWidget.on( 'change', () => {
					updateWatchOption( blockTargetWidget.getValue().trim() );
				} );
				updateWatchOption( blockTargetWidget.getValue().trim() );
			}
			return;
		}

		// This code is also loaded on the "block succeeded" page where there is no form,
		// so check for block target widget; if it exists, the form is present
		blockTargetWidget = infuseIfExists( $( '#mw-bi-target' ) );

		if ( blockTargetWidget ) {
			userChangedCreateAccount = mw.config.get( 'wgCreateAccountDirty' );
			updatingBlockOptions = false;

			// Always present if blockTargetWidget is present
			expiryWidget = OO.ui.infuse( $( '#mw-input-wpExpiry' ) );
			createAccountWidget = OO.ui.infuse( $( '#mw-input-wpCreateAccount' ) );
			enableAutoblockWidget = OO.ui.infuse( $( '#mw-input-wpAutoBlock' ) );
			anonOnlyWidget = OO.ui.infuse( $( '#mw-input-wpHardBlock' ) );
			blockTargetWidget.on( 'change', updateBlockOptions );
			expiryWidget.on( 'change', updateBlockOptions );
			createAccountWidget.on( 'change', () => {
				if ( !updatingBlockOptions ) {
					userChangedCreateAccount = true;
				}
			} );
			editingRestrictionWidget = OO.ui.infuse( $( '#mw-input-wpEditingRestriction' ) );
			pageRestrictionsWidget = OO.ui.infuse( $( '#mw-input-wpPageRestrictions' ) );
			namespaceRestrictionsWidget = OO.ui.infuse( $( '#mw-input-wpNamespaceRestrictions' ) );
			editingRestrictionWidget.on( 'change', updateBlockOptions );
			namespaceRestrictionsWidget.on( 'change', updateBlockOptions );
			partialActionsRestrictionsWidget = OO.ui.infuse( $( '.mw-block-action-restriction.oo-ui-checkboxMultiselectInputWidget' ) );

			// Present for certain rights
			hideUserWidget = infuseIfExists( $( '#mw-input-wpHideUser' ) );

			// Present for certain global configs
			preventTalkPageEditWidget = infuseIfExists( $( '#mw-input-wpDisableUTEdit' ) );

			// When disabling checkboxes, preserve their selected state in case they are re-enabled
			preserveSelectedStateOnDisable( enableAutoblockWidget );
			preserveSelectedStateOnDisable( anonOnlyWidget );
			preserveSelectedStateOnDisable( watchUserWidget );
			preserveSelectedStateOnDisable( hideUserWidget );
			preserveSelectedStateOnDisable( preventTalkPageEditWidget );

			updateBlockOptions();
		}
	} );
}() );
