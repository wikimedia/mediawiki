<?php
/*
 * Collection of public static functions to play with IP address
 * and IP blocks.
 *
 * @Author "Ashar Voultoiz" <hashar@altern.org>
 * @License GPL v2 or later
 */

class IP {
	// Some regex definition to "play" with IP address and IP address blocks

	// An IP is made of 4 bytes from x00 to xFF which is d0 to d255
	const RE_BYTE = '(25[0-5]|2[0-4]\d|1?\d{1,2})';
	const RE_ADD = self::RE_BYTE . '\.' . self::RE_BYTE . '\.' . self::RE_BYTE . '\.' . self::RE_BYTE;
	// An IP block is an IP address and a prefix (d1 to d32)
	const RE_PREFIX = '(3[0-2]|[12]?\d)';
	const RE_BLOCK = self::RE_ADD . '\/' . self::RE_PREFIX;

	/**
	 * Validate an IP address.
	 * @return boolean True if it is valid.
	 */
	public static function isValid( $ip ) {
		return preg_match( '/^' . self::RE_ADD . '$/', $ip, $matches) ;
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
	function isPublic( $ip ) {
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
		if(! preg_match( '/^' . self::RE_ADD . '(?:\/(?:'.self::RE_PREFIX.'))?' . '$/', $ipblock, $matches ) ) {
			return false;
		} else {
			return $matches;
		}
	}

	/**
	 * Return a zero-padded hexadecimal representation of an IP address
	 * Comes from ProxyTools.php
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
		$n = ip2long( $ip );
		if ( $n == -1 || $n === false ) { # Return value on error depends on PHP version
			$n = false;
		} elseif ( $n < 0 ) {
			$n += pow( 2, 32 );
		}
		return $n;
	}
}
?>
