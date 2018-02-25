/*!
 * JavaScript for Special:Block
 */
( function ( mw, $ ) {
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
			// mw.widgets.SelectWithInputWidget
			expiryWidget = infuseOrNull( 'mw-input-wpExpiry' );

		function updateBlockOptions() {
			var blocktarget = blockTargetWidget.getValue().trim(),
				isEmpty = blocktarget === '',
				isIp = mw.util.isIPAddress( blocktarget, true ),
				isIpRange = isIp && blocktarget.match( /\/\d+$/ ),
				isNonEmptyIp = isIp && !isEmpty,
				expiryValue = expiryWidget.dropdowninput.getValue(),
				// infinityValues  are the values the SpecialBlock class accepts as infinity (sf. wfIsInfinity)
				infinityValues = [ 'infinite', 'indefinite', 'infinity', 'never' ],
				isIndefinite = infinityValues.indexOf( expiryValue ) !== -1 ||
					( expiryValue === 'other' && infinityValues.indexOf( expiryWidget.textinput.getValue() ) !== -1 );

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
			expiryWidget.dropdowninput.on( 'change', updateBlockOptions );
			expiryWidget.textinput.on( 'change', updateBlockOptions );

			// Call them now to set initial state (ie. Special:Block/Foobar?wpBlockExpiry=2+hours)
			updateBlockOptions();
		}
	} );

	var showCIDRResults = function ( size, cidr ) {
		$( '#mw-cidr-result' ).val( cidr );
		$( '#mw-ipnote' ).text( size );
	};

	/*
	* This function calculates the common range of a list of
	* IPs. It should be set to update on keyUp.
	*/
	var updateCIDRResult = function () {
		var form = document.getElementById( 'mw-cidrform' );
		if ( !form ) {
			return; // no JS form
		}
		form.style.display = 'inline'; // unhide form (JS active)
		var iplist = document.getElementById( 'mw-iplist' );
		if ( !iplist ) {
			return; // no JS form
		}
		var text = iplist.value, ips;
		// Each line should have one IP or range
		if ( text.indexOf( '\n' ) !== -1 ) {
			ips = text.split( '\n' );
		// Try some other delimiters too...
		} else if ( text.indexOf( '\t' ) !== -1 ) {
			ips = text.split( '\t' );
		} else if ( text.indexOf( ',' ) !== -1 ) {
			ips = text.split( ',' );
		} else if ( text.indexOf( ' - ' ) !== -1 ) {
			ips = text.split( ' - ' );
		} else if ( text.indexOf( '-' ) !== -1 ) {
			ips = text.split( '-' );
		} else if ( text.indexOf( ' ' ) !== -1 ) {
			ips = text.split( ' ' );
		} else {
			ips = text.split( ';' );
		}
		var binPrefix = 0;
		var prefixCidr = 0;
		var prefix = '';
		var foundV4 = false;
		var foundV6 = false;
		var ipCount;
		var blocs;
		// Go through each IP in the list, get its binary form, and
		// track the largest binary prefix among them...
		for ( var i = 0; i < ips.length; i++ ) {
			// ...in the spirit of mediawiki.special.block.js, call this "addy"
			var addy = ips[i].replace( /^\s*|\s*$/, '' ); // trim
			// Match the first IP in each list (ignore other garbage)
			var ipV4 = mw.util.isIPv4Address( addy, true );
			var ipV6 = mw.util.isIPv6Address( addy, true );
			var ipCidr = addy.match( /^(.*)(?:\/(\d+))?$/ );
			// Binary form
			var bin = '';
			var x = 0, z = 0, start = 0, end = 0, ip, cidr, bloc, binBlock;
			// Convert the IP to binary form: IPv4
			if ( ipV4 ) {
				foundV4 = true;
				if ( foundV6 ) { // disjoint address space
					prefix = '';
					break;
				}
				ip = ipCidr[1];
				cidr = ipCidr[2] ? ipCidr[2] : null; // CIDR, if it exists
				// Get each quad integer
				blocs = ip.split( '.' );
				for ( x = 0; x < blocs.length; x++ ) {
					bloc = parseInt( blocs[x], 10 );
					binBlock = bloc.toString( 2 ); // concat bin with binary form of bloc
					while ( binBlock.length < 8 ) {
						binBlock = '0' + binBlock; // pad out as needed
					}
					bin += binBlock;
				}
				prefix = ''; // Rebuild formatted binPrefix for each IP
				// Apply any valid CIDRs
				if ( cidr ) {
					bin = bin.substring( 0, cidr ); // truncate bin
				}
				// Init binPrefix
				if ( binPrefix === 0 ) {
					binPrefix = bin;
				// Get largest common binPrefix
				} else {
					for ( x = 0; x < binPrefix.length; x++ ) {
						// binPrefix always smaller than bin unless a CIDR was used on bin
						if ( bin[x] === undefined || binPrefix[x] !== bin[x] ) {
							binPrefix = binPrefix.substring( 0, x ); // shorten binPrefix
							break;
						}
					}
				}
				// Build the IP in CIDR form
				prefixCidr = binPrefix.length;
				// CIDR too small?
				if ( prefixCidr < 16 ) {
					showCIDRResults( '!',  '>' + Math.pow( 2, 32 - prefixCidr ) );
					return; // too big
				}
				// Build the IP in dotted-quad form
				for ( z = 0; z <= 3; z++ ) {
					bloc = 0;
					start = z * 8;
					end = start + 7;
					for ( x = start; x <= end; x++ ) {
						if ( binPrefix[x] === undefined ) {
							break;
						}
						bloc += parseInt( binPrefix[x], 10 ) * Math.pow( 2, end - x );
					}
					prefix += ( z === 3 ) ? bloc : bloc + '.';
				}
				// Get IPs affected
				ipCount = Math.pow( 2, 32 - prefixCidr );
				// Is the CIDR meaningful?
				if ( prefixCidr === 32 ) {
					prefixCidr = false;
				}
			// Convert the IP to binary form: IPv6
			} else if ( ipV6 ) {
				foundV6 = true;
				if ( foundV4 ) { // disjoint address space
					prefix = '';
					break;
				}
				ip = ipCidr[1];
				cidr = ipCidr[2] ? ipCidr[2] : null; // CIDR, if it exists
				// Expand out "::"s
				var abbrevs = ip.match( /::/g );
				if ( abbrevs && abbrevs.length > 0 ) {
					var colons = ip.match( /:/g );
					var needed = 7 - ( colons.length - 2 ); // 2 from "::"
					var insert = '';
					while ( needed > 1 ) {
						insert += ':0';
						needed--;
					}
					ip = ip.replace( '::', insert + ':' );
					// For IPs that start with "::", correct the final IP
					// so that it starts with '0' and not ':'
					if ( ip[0] === ':' ) {
						ip = '0' + ip;
					}
				}
				// Get each hex octant
				blocs = ip.split( ':' );
				for ( x = 0; x <= 7; x++ ) {
					bloc = blocs[x] ? blocs[x] : '0';
					var intBlock = hex2int( bloc ); // convert hex -> int
					binBlock = intBlock.toString( 2 ); // concat bin with binary form of bloc
					while ( binBlock.length < 16 ) {
						binBlock = '0' + binBlock; // pad out as needed
					}
					bin += binBlock;
				}
				prefix = ''; // Rebuild formatted binPrefix for each IP
				// Apply any valid CIDRs
				if ( cidr ) {
					bin = bin.substring( 0, cidr ); // truncate bin
				}
				// Init binPrefix
				if ( binPrefix === 0 ) {
					binPrefix = bin;
				// Get largest common binPrefix
				} else {
					for ( x = 0; x < binPrefix.length; x++ ) {
						// binPrefix always smaller than bin unless a CIDR was used on bin
						if ( bin[x] === undefined || binPrefix[x] !== bin[x] ) {
							binPrefix = binPrefix.substring( 0, x ); // shorten binPrefix
							break;
						}
					}
				}
				// Build the IP in CIDR form
				prefixCidr = binPrefix.length;
				// CIDR too small?
				if ( prefixCidr < 32 ) {
					showCIDRResults( '!', '>' + Math.pow( 2, 128 - prefixCidr ) );
					return; // too big
				}
				// Build the IP in dotted-quad form
				for ( z = 0; z <= 7; z++ ) {
					bloc = 0;
					start = z*16;
					end = start + 15;
					for ( x = start; x <= end; x++ ) {
						if ( binPrefix[x] === undefined ) {
							break;
						}
						bloc += parseInt( binPrefix[x], 10 ) * Math.pow( 2, end - x );
					}
					bloc = bloc.toString( 16 ); // convert to hex
					prefix += ( z === 7 ) ? bloc : bloc + ':';
				}
				// Get IPs affected
				ipCount = Math.pow( 2, 128 - prefixCidr );
				// Is the CIDR meaningful?
				if ( prefixCidr === 128 ) {
					prefixCidr = false;
				}
			}
		}
		// Update form
		if ( prefix !== '' ) {
			var full = prefix;
			if ( prefixCidr !== false ) {
				full += '/' + prefixCidr;
			}
			showCIDRResults( '~' + ipCount, full );
		} else {
			showCIDRResults( '?', '' );
		}

	};

	// Utility function to convert hex to integers
	var hex2int = function ( hex ) {
		hex = hex.toLowerCase();
		var intform = 0;
		for ( var i = 0; i < hex.length; i++ ) {
			var digit = 0;
			switch ( hex[i] ) {
				case 'a':
					digit = 10;
					break;
				case 'b':
					digit = 11;
					break;
				case 'c':
					digit = 12;
					break;
				case 'd':
					digit = 13;
					break;
				case 'e':
					digit = 14;
					break;
				case 'f':
					digit = 15;
					break;
				default:
					digit = parseInt( hex[i], 10 );
					break;
			}
			intform += digit * Math.pow( 16, hex.length - 1 - i );
		}
		return intform;
	};

	$( function () {
		updateCIDRResult();
		$( '#mw-iplist' ).on( 'keyup click', function () {
			updateCIDRResult();
		} );
	} );
}( mediaWiki, jQuery ) );
