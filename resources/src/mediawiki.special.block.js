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
		var blockTargetWidget, anonOnlyWidget, enableAutoblockWidget, hideUserWidget, watchUserWidget,
			expiryWidget, editingWidget, editingRestrictionWidget, preventTalkPageEditWidget,
			pageRestrictionsWidget, namespaceRestrictionsWidget, createAccountWidget, data,
			blockAllowsUTEdit, userChangedCreateAccount, updatingBlockOptions;

		function preserveSelectedStateOnDisable( widget ) {
			var widgetWasSelected;

			if ( !widget ) {
				return;
			}

			// 'disable' event fires if disabled state changes
			widget.on( 'disable', function ( disabled ) {
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
			var blocktarget = blockTargetWidget.getValue().trim(),
				isEmpty = blocktarget === '',
				isIp = mw.util.isIPAddress( blocktarget, true ),
				isIpRange = isIp && blocktarget.match( /\/\d+$/ ),
				isNonEmptyIp = isIp && !isEmpty,
				expiryValue = expiryWidget.getValue(),
				// infinityValues are the values the SpecialBlock class accepts as infinity (sf. wfIsInfinity)
				infinityValues = [ 'infinite', 'indefinite', 'infinity', 'never' ],
				isIndefinite = infinityValues.indexOf( expiryValue ) !== -1,
				editingRestrictionValue = editingRestrictionWidget.getValue(),
				editingIsSelected = editingWidget.isSelected(),
				isSitewide = editingIsSelected && editingRestrictionValue === 'sitewide';

			enableAutoblockWidget.setDisabled( isNonEmptyIp );

			anonOnlyWidget.setDisabled( !isIp && !isEmpty );

			if ( hideUserWidget ) {
				hideUserWidget.setDisabled( isNonEmptyIp || !isIndefinite || !isSitewide );
			}

			if ( watchUserWidget ) {
				watchUserWidget.setDisabled( isIpRange && !isEmpty );
			}

			editingRestrictionWidget.setDisabled( !editingIsSelected );
			pageRestrictionsWidget.setDisabled( !editingIsSelected || isSitewide );
			namespaceRestrictionsWidget.setDisabled( !editingIsSelected || isSitewide );
			if ( blockAllowsUTEdit ) {
				// Disable for partial blocks, unless the block is against the User_talk namespace
				preventTalkPageEditWidget.setDisabled(
					// Partial block that doesn't block editing
					!editingIsSelected ||
					// Partial block that blocks editing and doesn't block the User_talk namespace
					(
						editingRestrictionValue === 'partial' &&
						namespaceRestrictionsWidget.getValue().indexOf(
							String( mw.config.get( 'wgNamespaceIds' ).user_talk )
						) === -1
					)
				);
			}

			if ( !userChangedCreateAccount ) {
				updatingBlockOptions = true;
				createAccountWidget.setSelected( isSitewide );
				updatingBlockOptions = false;
			}

		}

		// This code is also loaded on the "block succeeded" page where there is no form,
		// so check for block target widget; if it exists, the form is present
		blockTargetWidget = infuseIfExists( $( '#mw-bi-target' ) );

		if ( blockTargetWidget ) {
			data = require( './config.json' );
			blockAllowsUTEdit = data.BlockAllowsUTEdit;
			userChangedCreateAccount = mw.config.get( 'wgCreateAccountDirty' );
			updatingBlockOptions = false;

			// Always present if blockTargetWidget is present
			editingWidget = OO.ui.infuse( $( '#mw-input-wpEditing' ) );
			expiryWidget = OO.ui.infuse( $( '#mw-input-wpExpiry' ) );
			createAccountWidget = OO.ui.infuse( $( '#mw-input-wpCreateAccount' ) );
			enableAutoblockWidget = OO.ui.infuse( $( '#mw-input-wpAutoBlock' ) );
			anonOnlyWidget = OO.ui.infuse( $( '#mw-input-wpHardBlock' ) );
			blockTargetWidget.on( 'change', updateBlockOptions );
			editingWidget.on( 'change', updateBlockOptions );
			expiryWidget.on( 'change', updateBlockOptions );
			createAccountWidget.on( 'change', function () {
				if ( !updatingBlockOptions ) {
					userChangedCreateAccount = true;
				}
			} );
			editingRestrictionWidget = OO.ui.infuse( $( '#mw-input-wpEditingRestriction' ) );
			pageRestrictionsWidget = OO.ui.infuse( $( '#mw-input-wpPageRestrictions' ) );
			namespaceRestrictionsWidget = OO.ui.infuse( $( '#mw-input-wpNamespaceRestrictions' ) );
			editingRestrictionWidget.on( 'change', updateBlockOptions );
			namespaceRestrictionsWidget.on( 'change', updateBlockOptions );

			// Present for certain rights
			watchUserWidget = infuseIfExists( $( '#mw-input-wpWatch' ) );
			hideUserWidget = infuseIfExists( $( '#mw-input-wpHideUser' ) );

			// Present for certain global configs
			if ( blockAllowsUTEdit ) {
				preventTalkPageEditWidget = infuseIfExists( $( '#mw-input-wpDisableUTEdit' ) );
			}

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
