<?php
/*
 * Collection of public static functions to play with IP address
 * and IP blocks.
 *
 * @Author "Ashar Voultoiz" <hashar@altern.org>
 * @License GPL v2 or later
 */

// Some regex definition to "play" with IP address and IP address blocks

// An IP is made of 4 bytes from x00 to xFF which is d0 to d255
define( 'RE_IP_BYTE', '(25[0-5]|2[0-4]\d|1?\d{1,2})');
define( 'RE_IP_ADD' , RE_IP_BYTE . '\.' . RE_IP_BYTE . '\.' . RE_IP_BYTE . '\.' . RE_IP_BYTE );
// An IP block is an IP address and a prefix (d1 to d32)
define( 'RE_IP_PREFIX' , '(3[0-2]|[12]?\d)');
define( 'RE_IP_BLOCK', RE_IP_ADD . '\/' . RE_IP_PREFIX);

class IP {

	/**
	 * Validate an IP address.
	 * @return boolean True if it is valid.
	 */
	public static function isValid( $ip ) {
		return preg_match( '/^' . RE_IP_ADD . '$/', $ip, $matches) ;
	}

	/**
	 * Validate an IP Block.
	 * @return boolean True if it is valid.
	 */
	public static function isValidBlock( $ipblock ) {
		return ( count(self::toArray($ipblock)) == 1 + 5 );
	}

	/**
	 * Determine if an IP address really is an IP address, and if it is public,
	 * i.e. not RFC 1918 or similar
	 * Comes from ProxyTools.php
	 */
	public static function isPublic( $ip ) {
		$n = IP::toUnsigned( $ip );
		if ( !$n ) {
			return false;
		}

		// ip2long accepts incomplete addresses, as well as some addresses
		// followed by garbage characters. Check that it's really valid.
		if( $ip != long2ip( $n ) ) {
			return false;
		}

		static $privateRanges = false;
		if ( !$privateRanges ) {
			$privateRanges = array(
				array( '10.0.0.0',    '10.255.255.255' ),   # RFC 1918 (private)
				array( '172.16.0.0',  '172.31.255.255' ),   #     "
				array( '192.168.0.0', '192.168.255.255' ),  #     "
				array( '0.0.0.0',     '0.255.255.255' ),    # this network
				array( '127.0.0.0',   '127.255.255.255' ),  # loopback
			);
		}

		foreach ( $privateRanges as $r ) {
			$start = IP::toUnsigned( $r[0] );
			$end = IP::toUnsigned( $r[1] );
			if ( $n >= $start && $n <= $end ) {
				return false;
			}
		}
		return true;
	}

	/**
	 * Split out an IP block as an array of 4 bytes and a mask,
	 * return false if it cant be determined
	 *
	 * @parameter $ip string A quad dotted IP address
	 * @return array
	 */
	public static function toArray( $ipblock ) {
		if(! preg_match( '/^' . RE_IP_ADD . '(?:\/(?:'.RE_IP_PREFIX.'))?' . '$/', $ipblock, $matches ) ) {
			return false;
		} else {
			return $matches;
		}
	}

	/**
	 * Return a zero-padded hexadecimal representation of an IP address.
	 *
	 * Hexadecimal addresses are used because they can easily be extended to
	 * IPv6 support. To separate the ranges, the return value from this 
	 * function for an IPv6 address will be prefixed with "v6-", a non-
	 * hexadecimal string which sorts after the IPv4 addresses.
	 *
	 * @param $ip Quad dotted IP address.
	 */
	public static function toHex( $ip ) {
		$n = self::toUnsigned( $ip );
		if ( $n !== false ) {
			$n = sprintf( '%08X', $n );
		}
		return $n;
	}

	/**
	 * Given an IP address in dotted-quad notation, returns an unsigned integer.
	 * Like ip2long() except that it actually works and has a consistent error return value.
	 * Comes from ProxyTools.php
	 * @param $ip Quad dotted IP address.
	 */
	public static function toUnsigned( $ip ) {
		if ( $ip == '255.255.255.255' ) {
			$n = -1;
		} else {
			$n = ip2long( $ip );
			if ( $n == -1 || $n === false ) { # Return value on error depends on PHP version
				$n = false;
			}
		}
		if ( $n < 0 ) {
			$n += pow( 2, 32 );
		}
		return $n;
	}

	/**
	 * Convert a dotted-quad IP to a signed integer
	 * Returns false on failure
	 */
	public static function toSigned( $ip ) {
		if ( $ip == '255.255.255.255' ) {
			$n = -1;
		} else {
			$n = ip2long( $ip );
			if ( $n == -1 ) {
				$n = false;
			}
		}
		return $n;
	}

	/**
	 * Convert a network specification in CIDR notation to an integer network and a number of bits
	 */
	public static function parseCIDR( $range ) {
		$parts = explode( '/', $range, 2 );
		if ( count( $parts ) != 2 ) {
			return array( false, false );
		}
		$network = IP::toSigned( $parts[0] );
		if ( $network !== false && is_numeric( $parts[1] ) && $parts[1] >= 0 && $parts[1] <= 32 ) {
			$bits = $parts[1];
			if ( $bits == 0 ) {
				$network = 0;
			} else {
				$network &= ~((1 << (32 - $bits)) - 1);
			}
			# Convert to unsigned
			if ( $network < 0 ) {
				$network += pow( 2, 32 );
			}
		} else {
			$network = false;
			$bits = false;
		}
		return array( $network, $bits );
	}

	/**
	 * Given a string range in a number of formats, return the start and end of 
	 * the range in hexadecimal.
	 *
	 * Formats are:
	 *     1.2.3.4/24          CIDR
	 *     1.2.3.4 - 1.2.3.5   Explicit range
	 *     1.2.3.4             Single IP
	 */
	public static function parseRange( $range ) {
		if ( strpos( $range, '/' ) !== false ) {
			# CIDR
			list( $network, $bits ) = IP::parseCIDR( $range );
			if ( $network === false ) {
				$start = $end = false;
			} else {
				$start = sprintf( '%08X', $network );
				$end = sprintf( '%08X', $network + pow( 2, (32 - $bits) ) - 1 );
			}
		} elseif ( strpos( $range, '-' ) !== false ) {
			# Explicit range
			list( $start, $end ) = array_map( 'trim', explode( '-', $range, 2 ) );
			if ( $start > $end ) {
				$start = $end = false;
			} else {
				$start = IP::toHex( $start );
				$end = IP::toHex( $end );
			}
		} else {
			# Single IP
			$start = $end = IP::toHex( $range );
		}
		if ( $start === false || $end === false ) {
			return array( false, false );
		} else {				
			return array( $start, $end );
		}
    }

    /**
     * Determine if a given integer IPv4 address is in a given CIDR network
     * @param $addr The address to check against the given range.
     * @param $range The range to check the given address against.
     * @return bool Whether or not the given address is in the given range.
     */
    public static function isInRange( $addr, $range ) {
        $unsignedIP = IP::toUnsigned($addr);
        list( $start, $end ) = IP::parseRange($range);

        return (($unsignedIP >= $start) && ($unsignedIP <= $end));
    }
}
?>
