/*!
 * JavaScript for Special:Block
 */
( function () {
	// Like OO.ui.infuse(), but if the element doesn't exist, return null instead of throwing an exception.
	function infuseOrNull( elem ) {
		try {
			return OO.ui.infuse( elem );
		} catch ( er ) {
			return null;
		}
	}

	$( function () {
		// This code is also loaded on the "block succeeded" page where there is no form,
		// so username and expiry fields might also be missing.
		var blockTargetWidget = infuseOrNull( 'mw-bi-target' ),
			anonOnlyField = infuseOrNull( $( '#mw-input-wpHardBlock' ).closest( '.oo-ui-fieldLayout' ) ),
			enableAutoblockField = infuseOrNull( $( '#mw-input-wpAutoBlock' ).closest( '.oo-ui-fieldLayout' ) ),
			hideUserField = infuseOrNull( $( '#mw-input-wpHideUser' ).closest( '.oo-ui-fieldLayout' ) ),
			watchUserField = infuseOrNull( $( '#mw-input-wpWatch' ).closest( '.oo-ui-fieldLayout' ) ),
			expiryWidget = infuseOrNull( 'mw-input-wpExpiry' );

		function updateBlockOptions() {
			var blocktarget = blockTargetWidget.getValue().trim(),
				isEmpty = blocktarget === '',
				isIp = mw.util.isIPAddress( blocktarget, true ),
				isIpRange = isIp && blocktarget.match( /\/\d+$/ ),
				isNonEmptyIp = isIp && !isEmpty,
				expiryValue = expiryWidget.getValue(),
				// infinityValues  are the values the SpecialBlock class accepts as infinity (sf. wfIsInfinity)
				infinityValues = [ 'infinite', 'indefinite', 'infinity', 'never' ],
				isIndefinite = infinityValues.indexOf( expiryValue ) !== -1;

			if ( enableAutoblockField ) {
				enableAutoblockField.toggle( !( isNonEmptyIp ) );
			}
			if ( hideUserField ) {
				hideUserField.toggle( !( isNonEmptyIp || !isIndefinite ) );
			}
			if ( anonOnlyField ) {
				anonOnlyField.toggle( !( !isIp && !isEmpty ) );
			}
			if ( watchUserField ) {
				watchUserField.toggle( !( isIpRange && !isEmpty ) );
			}
		}

		if ( blockTargetWidget ) {
			// Bind functions so they're checked whenever stuff changes
			blockTargetWidget.on( 'change', updateBlockOptions );
			expiryWidget.on( 'change', updateBlockOptions );

			// Call them now to set initial state (ie. Special:Block/Foobar?wpBlockExpiry=2+hours)
			updateBlockOptions();
		}
	} );
}() );
