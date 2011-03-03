/* JavaScript for Special:Block */
jQuery( function( $ ) {

	$('#mw-bi-target').keyup(function(){
		var isIPv4Address = function( address ) {
			var RE_IP_BYTE = '(?:25[0-5]|2[0-4][0-9]|1[0-9][0-9]|0?[0-9]?[0-9])';
			var RE_IP_ADD = '(?:' + RE_IP_BYTE + '\\.){3}' + RE_IP_BYTE;
			return address.search( new RegExp( '^' + RE_IP_ADD + '(?:\\/(?:3[0-2]|[12]?\\d))?$' ) ) != -1;
		};
		var isIPv6Address = function( address ) {
			var RE_IPV6_ADD =
			'(?:' + // starts with "::" (including "::")
				':(?::|(?::' + '[0-9A-Fa-f]{1,4}' + '){1,7})' +
			'|' + // ends with "::" (except "::")
				'[0-9A-Fa-f]{1,4}' + '(?::' + '[0-9A-Fa-f]{1,4}' + '){0,6}::' +
			'|' + // contains no "::"
				'[0-9A-Fa-f]{1,4}' + '(?::' + '[0-9A-Fa-f]{1,4}' + '){7}' +
			')';
			if ( address.search( new RegExp( '^' + RE_IPV6_ADD + '(?:\\/(?:12[0-8]|1[01][0-9]|[1-9]?\\d))?$' ) ) != -1 ) {
				return true;
			}
			var RE_IPV6_ADD_SHORT = // contains one "::" in the middle (single '::' check below)
				'[0-9A-Fa-f]{1,4}' + '(?:::?' + '[0-9A-Fa-f]{1,4}' + '){1,6}';
			return address.search( new RegExp( '^' + RE_IPV6_ADD_SHORT + '(?:\\/(?:12[0-8]|1[01][0-9]|[1-9]?\\d))?$' ) ) != -1
				&& address.search( /::/ ) != -1 && address.search( /::.*::/ ) == -1;
		};

		var input = $('#mw-bi-target').val();

		var isEmpty = ( input == "" );
		var isIp = isIPv4Address( input ) || isIPv6Address( input );
		var isIpRange = isIp && input.match(/\/\d+$/);

		if( !isEmpty ){
			if( isIp ){
				$( '#wpAnonOnlyRow' ).stop( true, true ).delay(1000).fadeIn();
				$( '#wpEnableAutoblockRow, #wpEnableHideUser' ).stop( true, true ).delay(1000).fadeOut();
			} else {
				$( '#wpAnonOnlyRow' ).stop( true, true ).delay(1000).fadeOut();
				$( '#wpEnableAutoblockRow, #wpEnableHideUser' ).stop( true, true ).delay(1000).fadeIn();
			}
			if( isIpRange ){
				$( '#wpEnableWatchUser' ).stop( true, true ).delay(1000).fadeOut();
			} else {
				$( '#wpEnableWatchUser' ).stop( true, true ).delay(1000).fadeIn();
			}
		}
	}).keyup();

	$('#wpBlockExpiry').change( function(){
		if( $(this).val() == 'other' ){
			$('#wpBlockOther').stop( true, true ).fadeIn();
		} else {
			$('#wpBlockOther').stop( true, true ).fadeOut();
		}
	}).change();
} );