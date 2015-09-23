/*!
 * JavaScript for Special:Block
 */
( function ( mw, $ ) {
	$( function () {
		var $blockTarget = $( '#mw-bi-target' ),
			$anonOnlyRow = $( '#mw-input-wpHardBlock' ).closest( 'tr' ),
			$enableAutoblockRow = $( '#mw-input-wpAutoBlock' ).closest( 'tr' ),
			$hideUser = $( '#mw-input-wpHideUser' ).closest( 'tr' ),
			$watchUser = $( '#mw-input-wpWatch' ).closest( 'tr' );

		function updateBlockOptions( instant ) {
			var blocktarget = $.trim( $blockTarget.val() ),
				isEmpty = blocktarget === '',
				isIp = mw.util.isIPAddress( blocktarget, true ),
				isIpRange = isIp && blocktarget.match( /\/\d+$/ );

			if ( isIp && !isEmpty ) {
				$enableAutoblockRow.goOut( instant );
				$hideUser.goOut( instant );
			} else {
				$enableAutoblockRow.goIn( instant );
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

			// Call them now to set initial state (ie. Special:Block/Foobar?wpBlockExpiry=2+hours)
			updateBlockOptions( /* instant= */ true );
		}
	} );
}( mediaWiki, jQuery ) );
