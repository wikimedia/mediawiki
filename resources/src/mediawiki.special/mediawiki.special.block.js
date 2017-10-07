/*!
 * JavaScript for Special:Block
 */
( function ( mw, $ ) {
	$( function () {
		var $blockTarget = $( '#mw-bi-target' ),
			$anonOnlyRow = $( '#mw-input-wpHardBlock' ).closest( 'tr' ),
			$enableAutoblockRow = $( '#mw-input-wpAutoBlock' ).closest( 'tr' ),
			$hideUser = $( '#mw-input-wpHideUser' ).closest( 'tr' ),
			$watchUser = $( '#mw-input-wpWatch' ).closest( 'tr' ),
			$expiry = $( '#mw-input-wpExpiry' ),
			$otherExpiry = $( '#mw-input-wpExpiry-other' );

		function updateBlockOptions( instant ) {
			var blocktarget = $.trim( $blockTarget.val() ),
				isEmpty = blocktarget === '',
				isIp = mw.util.isIPAddress( blocktarget, true ),
				isIpRange = isIp && blocktarget.match( /\/\d+$/ ),
				isNonEmptyIp = isIp && !isEmpty,
				expiryValue = $expiry.val(),
				// infinityValues  are the values the SpecialBlock class accepts as infinity (sf. wfIsInfinity)
				infinityValues = [ 'infinite', 'indefinite', 'infinity', 'never' ],
				isIndefinite = $.inArray( expiryValue, infinityValues ) !== -1 ||
					( expiryValue === 'other' && $.inArray( $otherExpiry.val(), infinityValues ) !== -1 );

			if ( isNonEmptyIp ) {
				$enableAutoblockRow.goOut( instant );
			} else {
				$enableAutoblockRow.goIn( instant );
			}
			if ( isNonEmptyIp || !isIndefinite ) {
				$hideUser.goOut( instant );
			} else {
				$hideUser.goIn( instant );
			}
			if ( !isIp && !isEmpty ) {
				$anonOnlyRow.goOut( instant );
			} else {
				$anonOnlyRow.goIn( instant );
			}
			if ( isIpRange && !isEmpty ) {
				$watchUser.goOut( instant );
			} else {
				$watchUser.goIn( instant );
			}
		}

		if ( $blockTarget.length ) {
			// Bind functions so they're checked whenever stuff changes
			$blockTarget.keyup( updateBlockOptions );
			$expiry.change( updateBlockOptions );
			$otherExpiry.keyup( updateBlockOptions );

			// Call them now to set initial state (ie. Special:Block/Foobar?wpBlockExpiry=2+hours)
			updateBlockOptions( /* instant= */ true );
		}
	} );
}( mediaWiki, jQuery ) );
