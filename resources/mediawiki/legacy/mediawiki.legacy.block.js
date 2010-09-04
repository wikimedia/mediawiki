/*
 * Legacy emulation for the now depricated skins/common/block.js
 */

( function( $, mw ) {

/* Extension */

$.extend( true, mw.legacy, {
	
	/* Functions */
	
	'considerChangingExpiryFocus': function() {
		var $expiry = $( '#wpBlockExpiry' );
		var $other = $( '#wpBlockOther' );
		if ( $expiry.length && $other.length ) {
			if ( $expiry.val() == 'other' ) {
				$other.css( 'display', '' );
			} else {
				$other.css( 'display', 'none' );
			}
		}
	},
	'updateBlockOptions': function() {
		var $target = $( '#mw-bi-target' );
		if ( $target.length ) {
			var address = $target.val();
			var isEmpty = address.match( /^\s*$/ );
			var isIp = address.match( /^(\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}|:(:[0-9A-Fa-f]{1,4}){1,7}|[0-9A-Fa-f]{1,4}(:{1,2}[0-9A-Fa-f]{1,4}|::$){1,7})(\/\d+)?$/ );
			var isIpRange = isIp && address.match( /\/\d+$/ );
			$( '#wpAnonOnlyRow' ).css( 'display', !isIp && !isEmpty ? 'none' : '' );
			$( '#wpEnableAutoblockRow,#wpEnableHideUser' ).css( 'display', isIp && !isEmpty ? 'none' : '' );
			$( '#wpEnableWatchUser' ).css( 'display', isIpRange && !isEmpty ? 'none' : '' );
		}
	}
} );

/* Initialization */

$( document ).ready( function() {
	mw.legacy.considerChangingExpiryFocus();
} );

} )( jQuery, mediaWiki );