/* JavaScript for Special:Block */

// Fade or snap depending on argument
jQuery.fn.goIn = function( instantToggle ) {
	if ( typeof instantToggle != 'undefined' && instantToggle === true ) {
		return jQuery(this).show();
	}
	return jQuery(this).stop( true, true ).fadeIn();
};
jQuery.fn.goOut = function( instantToggle ) {
	if ( typeof instantToggle != 'undefined' && instantToggle === true ) {
		return jQuery(this).hide();
	}
	return jQuery(this).stop( true, true ).fadeOut();
};

jQuery( function( $ ) {

	var	DO_INSTANT = true,
		$blockExpiry = $( '#wpBlockExpiry' ),	$blockOther = $( '#wpBlockOther' ),
		$blockTarget = $( '#mw-bi-target' ),	$anonOnlyRow = $( '#wpAnonOnlyRow' ),
		$enableAutoblockRow = $( '#wpEnableAutoblockRow' ),
		$hideUser = $( '#wpEnableHideUser' ),	$watchUser = $( '#wpEnableWatchUser' );

	var considerChangingExpiryFocus = function( instant ) {
		if ( $blockExpiry.val() == 'other' ) {
			$blockOther.goIn( instant );
		} else {
			$blockOther.goOut( instant );
		}
	};
	var updateBlockOptions = function( instant ) {
		if ( !$blockTarget.length ) {
			return;
		}

		var blocktarget = $.trim( $blockTarget.val() );
		var isEmpty = ( blocktarget === '' );
		var isIp = mw.util.isIPv4Address( blocktarget, true ) || mw.util.isIPv6Address( blocktarget, true );
		var isIpRange = isIp && blocktarget.match( /\/\d+$/ );

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
	};

	// Bind functions so they're checked whenever stuff changes
	$blockExpiry.change( considerChangingExpiryFocus );
	$blockTarget.keyup( updateBlockOptions );

	// Call them now to set initial state (ie. Special:Block/Foobar?wpBlockExpiry=2+hours)
	considerChangingExpiryFocus( DO_INSTANT );
	updateBlockOptions( DO_INSTANT );
});