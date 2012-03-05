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
}
window.updateBlockOptions = function() {
	if ( !document.getElementById ) {
		return;
	}

	var target = document.getElementById( 'mw-bi-target' );
	if ( !target ) {
		return;
	}

	var addy = target.value;
	var isEmpty = addy.match(/^\s*$/);
	var isIp = addy.match(/^(\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}|:(:[0-9A-Fa-f]{1,4}){1,7}|[0-9A-Fa-f]{1,4}(:{1,2}[0-9A-Fa-f]{1,4}|::$){1,7})(\/\d+)?$/);
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
}

addOnloadHook( considerChangingExpiryFocus );