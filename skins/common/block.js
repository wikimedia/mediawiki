// @TODO: find some better JS file for this
// Note: borrows from IP.php
window.isIPv4Address = function( address, allowBlock ) {
	var block = allowBlock ? '(?:\\/(?:3[0-2]|[12]?\\d))?' : '';
	var RE_IP_BYTE = '(?:25[0-5]|2[0-4][0-9]|1[0-9][0-9]|0?[0-9]?[0-9])';
	var RE_IP_ADD = '(?:' + RE_IP_BYTE + '\\.){3}' + RE_IP_BYTE;
	return address.search( new RegExp( '^' + RE_IP_ADD + block + '$' ) ) != -1;
};

// @TODO: find some better JS file for this
// Note: borrows from IP.php
window.isIPv6Address = function( address, allowBlock ) {
	var block = allowBlock ? '(?:\\/(?:12[0-8]|1[01][0-9]|[1-9]?\\d))?' : '';
	var RE_IPV6_ADD =
	'(?:' + // starts with "::" (including "::")
		':(?::|(?::' + '[0-9A-Fa-f]{1,4}' + '){1,7})' +
	'|' + // ends with "::" (except "::")
		'[0-9A-Fa-f]{1,4}' + '(?::' + '[0-9A-Fa-f]{1,4}' + '){0,6}::' +
	'|' + // contains no "::"
		'[0-9A-Fa-f]{1,4}' + '(?::' + '[0-9A-Fa-f]{1,4}' + '){7}' +
	')';
	if ( address.search( new RegExp( '^' + RE_IPV6_ADD + block + '$' ) ) != -1 ) {
		return true;
	}
	var RE_IPV6_ADD = // contains one "::" in the middle (single '::' check below)
		'[0-9A-Fa-f]{1,4}' + '(?:::?' + '[0-9A-Fa-f]{1,4}' + '){1,6}';
	return address.search( new RegExp( '^' + RE_IPV6_ADD + block + '$' ) ) != -1
		&& address.search( /::/ ) != -1 && address.search( /::.*::/ ) == -1;
};

window.considerChangingExpiryFocus = function() {
	if ( !document.getElementById ) {
		return;
	}
	var drop = document.getElementById( 'wpBlockExpiry' );
	if ( !drop ) {
		return;
	}
	var field = document.getElementById( 'wpBlockOther' );
	if ( !field ) {
		return;
	}
	var opt = drop.value;
	if ( opt == 'other' ) {
		field.style.display = '';
	} else {
		field.style.display = 'none';
	}
};

window.updateBlockOptions = function() {
	if ( !document.getElementById ) {
		return;
	}

	var target = document.getElementById( 'mw-bi-target' );
	if ( !target ) {
		return;
	}

	var addy = target.value.replace( /(^\s*|\s*$)/, '' ); // trim
	var isEmpty = (addy == ""); 

	var isIp = isIPv4Address( addy, true ) || isIPv6Address( addy, true );
	var isIpRange = isIp && addy.match(/\/\d+$/);

	var anonymousRow = document.getElementById( 'wpAnonOnlyRow' );
	if( anonymousRow ) {
		anonymousRow.style.display = ( !isIp && !isEmpty ) ? 'none' : '';
	}

	var autoblockRow = document.getElementById( 'wpEnableAutoblockRow' );
	if( autoblockRow ) {
		autoblockRow.style.display = isIp && !isEmpty ? 'none' : '';
	}
	
	var hideuserRow = document.getElementById( 'wpEnableHideUser' );
	if( hideuserRow ) {
		hideuserRow.style.display = isIp && !isEmpty ? 'none' : '';
	}

	var watchuserRow = document.getElementById( 'wpEnableWatchUser' );
	if( watchuserRow ) {
		watchuserRow.style.display = isIpRange && !isEmpty ? 'none' : '';
	}
};

addOnloadHook( updateBlockOptions );
addOnloadHook( considerChangingExpiryFocus );
