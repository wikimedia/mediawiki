<?php
/**
 * Functions and constants to play with IP addresses and ranges
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 * @author Antoine Musso "<hashar at free dot fr>", Aaron Schulz
 */

// Some regex definition to "play" with IP address and IP address blocks

// An IPv4 address is made of 4 bytes from x00 to xFF which is d0 to d255
define( 'RE_IP_BYTE', '(25[0-5]|2[0-4][0-9]|1[0-9][0-9]|0?[0-9]?[0-9])' );
define( 'RE_IP_ADD', RE_IP_BYTE . '\.' . RE_IP_BYTE . '\.' . RE_IP_BYTE . '\.' . RE_IP_BYTE );
// An IPv4 block is an IP address and a prefix (d1 to d32)
define( 'RE_IP_PREFIX', '(3[0-2]|[12]?\d)' );
define( 'RE_IP_BLOCK', RE_IP_ADD . '\/' . RE_IP_PREFIX );

// An IPv6 address is made up of 8 words (each x0000 to xFFFF).
// However, the "::" abbreviation can be used on consecutive x0000 words.
define( 'RE_IPV6_WORD', '([0-9A-Fa-f]{1,4})' );
define( 'RE_IPV6_PREFIX', '(12[0-8]|1[01][0-9]|[1-9]?\d)' );
define( 'RE_IPV6_ADD',
	'(?:' . // starts with "::" (including "::")
		':(?::|(?::' . RE_IPV6_WORD . '){1,7})' .
	'|' . // ends with "::" (except "::")
		RE_IPV6_WORD . '(?::' . RE_IPV6_WORD . '){0,6}::' .
	'|' . // contains one "::" in the middle (the ^ makes the test fail if none found)
		RE_IPV6_WORD . '(?::((?(-1)|:))?' . RE_IPV6_WORD . '){1,6}(?(-2)|^)' .
	'|' . // contains no "::"
		RE_IPV6_WORD . '(?::' . RE_IPV6_WORD . '){7}' .
	')'
);
// An IPv6 block is an IP address and a prefix (d1 to d128)
define( 'RE_IPV6_BLOCK', RE_IPV6_ADD . '\/' . RE_IPV6_PREFIX );
// For IPv6 canonicalization (NOT for strict validation; these are quite lax!)
define( 'RE_IPV6_GAP', ':(?:0+:)*(?::(?:0+:)*)?' );
define( 'RE_IPV6_V4_PREFIX', '0*' . RE_IPV6_GAP . '(?:ffff:)?' );

// This might be useful for regexps used elsewhere, matches any IPv6 or IPv6 address or network
define( 'IP_ADDRESS_STRING',
	'(?:' .
		RE_IP_ADD . '(?:\/' . RE_IP_PREFIX . ')?' . // IPv4
	'|' .
		RE_IPV6_ADD . '(?:\/' . RE_IPV6_PREFIX . ')?' . // IPv6
	')'
);

/**
 * A collection of public static functions to play with IP address
 * and IP blocks.
 */
class IP {
	/**
	 * Determine if a string is as valid IP address or network (CIDR prefix).
	 * SIIT IPv4-translated addresses are rejected.
	 * Note: canonicalize() tries to convert translated addresses to IPv4.
	 *
	 * @param string $ip possible IP address
	 * @return Boolean
	 */
	public static function isIPAddress( $ip ) {
		return (bool)preg_match( '/^' . IP_ADDRESS_STRING . '$/', $ip );
	}

	/**
	 * Given a string, determine if it as valid IP in IPv6 only.
	 * Note: Unlike isValid(), this looks for networks too.
	 *
	 * @param string $ip possible IP address
	 * @return Boolean
	 */
	public static function isIPv6( $ip ) {
		return (bool)preg_match( '/^' . RE_IPV6_ADD . '(?:\/' . RE_IPV6_PREFIX . ')?$/', $ip );
	}

	/**
	 * Given a string, determine if it as valid IP in IPv4 only.
	 * Note: Unlike isValid(), this looks for networks too.
	 *
	 * @param string $ip possible IP address
	 * @return Boolean
	 */
	public static function isIPv4( $ip ) {
		return (bool)preg_match( '/^' . RE_IP_ADD . '(?:\/' . RE_IP_PREFIX . ')?$/', $ip );
	}

	/**
	 * Validate an IP address. Ranges are NOT considered valid.
	 * SIIT IPv4-translated addresses are rejected.
	 * Note: canonicalize() tries to convert translated addresses to IPv4.
	 *
	 * @param $ip String
	 * @return Boolean: True if it is valid.
	 */
	public static function isValid( $ip ) {
		return ( preg_match( '/^' . RE_IP_ADD . '$/', $ip )
			|| preg_match( '/^' . RE_IPV6_ADD . '$/', $ip ) );
	}

	/**
	 * Validate an IP Block (valid address WITH a valid prefix).
	 * SIIT IPv4-translated addresses are rejected.
	 * Note: canonicalize() tries to convert translated addresses to IPv4.
	 *
	 * @param $ipblock String
	 * @return Boolean: True if it is valid.
	 */
	public static function isValidBlock( $ipblock ) {
		return ( preg_match( '/^' . RE_IPV6_BLOCK . '$/', $ipblock )
			|| preg_match( '/^' . RE_IP_BLOCK . '$/', $ipblock ) );
	}

	/**
	 * Convert an IP into a verbose, uppercase, normalized form.
	 * IPv6 addresses in octet notation are expanded to 8 words.
	 * IPv4 addresses are just trimmed.
	 *
	 * @param string $ip IP address in quad or octet form (CIDR or not).
	 * @return String
	 */
	public static function sanitizeIP( $ip ) {
		$ip = trim( $ip );
		if ( $ip === '' ) {
			return null;
		}
		if ( self::isIPv4( $ip ) || !self::isIPv6( $ip ) ) {
			return $ip; // nothing else to do for IPv4 addresses or invalid ones
		}
		// Remove any whitespaces, convert to upper case
		$ip = strtoupper( $ip );
		// Expand zero abbreviations
		$abbrevPos = strpos( $ip, '::' );
		if ( $abbrevPos !== false ) {
			// We know this is valid IPv6. Find the last index of the
			// address before any CIDR number (e.g. "a:b:c::/24").
			$CIDRStart = strpos( $ip, "/" );
			$addressEnd = ( $CIDRStart !== false )
				? $CIDRStart - 1
				: strlen( $ip ) - 1;
			// If the '::' is at the beginning...
			if ( $abbrevPos == 0 ) {
				$repeat = '0:';
				$extra = ( $ip == '::' ) ? '0' : ''; // for the address '::'
				$pad = 9; // 7+2 (due to '::')
			// If the '::' is at the end...
			} elseif ( $abbrevPos == ( $addressEnd - 1 ) ) {
				$repeat = ':0';
				$extra = '';
				$pad = 9; // 7+2 (due to '::')
			// If the '::' is in the middle...
			} else {
				$repeat = ':0';
				$extra = ':';
				$pad = 8; // 6+2 (due to '::')
			}
			$ip = str_replace( '::',
				str_repeat( $repeat, $pad - substr_count( $ip, ':' ) ) . $extra,
				$ip
			);
		}
		// Remove leading zeros from each bloc as needed
		$ip = preg_replace( '/(^|:)0+(' . RE_IPV6_WORD . ')/', '$1$2', $ip );

		return $ip;
	}

	/**
	 * Prettify an IP for display to end users.
	 * This will make it more compact and lower-case.
	 *
	 * @param $ip string
	 * @return string
	 */
	public static function prettifyIP( $ip ) {
		$ip = self::sanitizeIP( $ip ); // normalize (removes '::')
		if ( self::isIPv6( $ip ) ) {
			// Split IP into an address and a CIDR
			if ( strpos( $ip, '/' ) !== false ) {
				list( $ip, $cidr ) = explode( '/', $ip, 2 );
			} else {
				list( $ip, $cidr ) = array( $ip, '' );
			}
			// Get the largest slice of words with multiple zeros
			$offset = 0;
			$longest = $longestPos = false;
			while ( preg_match(
				'!(?:^|:)0(?::0)+(?:$|:)!', $ip, $m, PREG_OFFSET_CAPTURE, $offset
			) ) {
				list( $match, $pos ) = $m[0]; // full match
				if ( strlen( $match ) > strlen( $longest ) ) {
					$longest = $match;
					$longestPos = $pos;
				}
				$offset = ( $pos + strlen( $match ) ); // advance
			}
			if ( $longest !== false ) {
				// Replace this portion of the string with the '::' abbreviation
				$ip = substr_replace( $ip, '::', $longestPos, strlen( $longest ) );
			}
			// Add any CIDR back on
			if ( $cidr !== '' ) {
				$ip = "{$ip}/{$cidr}";
			}
			// Convert to lower case to make it more readable
			$ip = strtolower( $ip );
		}

		return $ip;
	}

	/**
	 * Given a host/port string, like one might find in the host part of a URL
	 * per RFC 2732, split the hostname part and the port part and return an
	 * array with an element for each. If there is no port part, the array will
	 * have false in place of the port. If the string was invalid in some way,
	 * false is returned.
	 *
	 * This was easy with IPv4 and was generally done in an ad-hoc way, but
	 * with IPv6 it's somewhat more complicated due to the need to parse the
	 * square brackets and colons.
	 *
	 * A bare IPv6 address is accepted despite the lack of square brackets.
	 *
	 * @param string $both The string with the host and port
	 * @return array
	 */
	public static function splitHostAndPort( $both ) {
		if ( substr( $both, 0, 1 ) === '[' ) {
			if ( preg_match( '/^\[(' . RE_IPV6_ADD . ')\](?::(?P<port>\d+))?$/', $both, $m ) ) {
				if ( isset( $m['port'] ) ) {
					return array( $m[1], intval( $m['port'] ) );
				} else {
					return array( $m[1], false );
				}
			} else {
				// Square bracket found but no IPv6
				return false;
			}
		}
		$numColons = substr_count( $both, ':' );
		if ( $numColons >= 2 ) {
			// Is it a bare IPv6 address?
			if ( preg_match( '/^' . RE_IPV6_ADD . '$/', $both ) ) {
				return array( $both, false );
			} else {
				// Not valid IPv6, but too many colons for anything else
				return false;
			}
		}
		if ( $numColons >= 1 ) {
			// Host:port?
			$bits = explode( ':', $both );
			if ( preg_match( '/^\d+/', $bits[1] ) ) {
				return array( $bits[0], intval( $bits[1] ) );
			} else {
				// Not a valid port
				return false;
			}
		}

		// Plain hostname
		return array( $both, false );
	}

	/**
	 * Given a host name and a port, combine them into host/port string like
	 * you might find in a URL. If the host contains a colon, wrap it in square
	 * brackets like in RFC 2732. If the port matches the default port, omit
	 * the port specification
	 *
	 * @param $host string
	 * @param $port int
	 * @param $defaultPort bool|int
	 * @return string
	 */
	public static function combineHostAndPort( $host, $port, $defaultPort = false ) {
		if ( strpos( $host, ':' ) !== false ) {
			$host = "[$host]";
		}
		if ( $defaultPort !== false && $port == $defaultPort ) {
			return $host;
		} else {
			return "$host:$port";
		}
	}

	/**
	 * Given an unsigned integer, returns an IPv6 address in octet notation
	 *
	 * @param $ip_int String: IP address.
	 * @return String
	 */
	public static function toOctet( $ip_int ) {
		return self::hexToOctet( wfBaseConvert( $ip_int, 10, 16, 32, false ) );
	}

	/**
	 * Convert an IPv4 or IPv6 hexadecimal representation back to readable format
	 *
	 * @param string $hex number, with "v6-" prefix if it is IPv6
	 * @return String: quad-dotted (IPv4) or octet notation (IPv6)
	 */
	public static function formatHex( $hex ) {
		if ( substr( $hex, 0, 3 ) == 'v6-' ) { // IPv6
			return self::hexToOctet( substr( $hex, 3 ) );
		} else { // IPv4
			return self::hexToQuad( $hex );
		}
	}

	/**
	 * Converts a hexadecimal number to an IPv6 address in octet notation
	 *
	 * @param $ip_hex String: pure hex (no v6- prefix)
	 * @return String (of format a:b:c:d:e:f:g:h)
	 */
	public static function hexToOctet( $ip_hex ) {
		// Pad hex to 32 chars (128 bits)
		$ip_hex = str_pad( strtoupper( $ip_hex ), 32, '0', STR_PAD_LEFT );
		// Separate into 8 words
		$ip_oct = substr( $ip_hex, 0, 4 );
		for ( $n = 1; $n < 8; $n++ ) {
			$ip_oct .= ':' . substr( $ip_hex, 4 * $n, 4 );
		}
		// NO leading zeroes
		$ip_oct = preg_replace( '/(^|:)0+(' . RE_IPV6_WORD . ')/', '$1$2', $ip_oct );

		return $ip_oct;
	}

	/**
	 * Converts a hexadecimal number to an IPv4 address in quad-dotted notation
	 *
	 * @param $ip_hex String: pure hex
	 * @return String (of format a.b.c.d)
	 */
	public static function hexToQuad( $ip_hex ) {
		// Pad hex to 8 chars (32 bits)
		$ip_hex = str_pad( strtoupper( $ip_hex ), 8, '0', STR_PAD_LEFT );
		// Separate into four quads
		$s = '';
		for ( $i = 0; $i < 4; $i++ ) {
			if ( $s !== '' ) {
				$s .= '.';
			}
			$s .= base_convert( substr( $ip_hex, $i * 2, 2 ), 16, 10 );
		}

		return $s;
	}

	/**
	 * Determine if an IP address really is an IP address, and if it is public,
	 * i.e. not RFC 1918 or similar
	 *
	 * @param $ip String
	 * @return Boolean
	 */
	public static function isPublic( $ip ) {
		if ( self::isIPv6( $ip ) ) {
			return self::isPublic6( $ip );
		}
		$n = self::toUnsigned( $ip );
		if ( !$n ) {
			return false;
		}

		// ip2long accepts incomplete addresses, as well as some addresses
		// followed by garbage characters. Check that it's really valid.
		if ( $ip != long2ip( $n ) ) {
			return false;
		}

		static $privateRanges = false;
		if ( !$privateRanges ) {
			$privateRanges = array(
				array( '10.0.0.0', '10.255.255.255' ), # RFC 1918 (private)
				array( '172.16.0.0', '172.31.255.255' ), # RFC 1918 (private)
				array( '192.168.0.0', '192.168.255.255' ), # RFC 1918 (private)
				array( '0.0.0.0', '0.255.255.255' ), # this network
				array( '127.0.0.0', '127.255.255.255' ), # loopback
			);
		}

		foreach ( $privateRanges as $r ) {
			$start = self::toUnsigned( $r[0] );
			$end = self::toUnsigned( $r[1] );
			if ( $n >= $start && $n <= $end ) {
				return false;
			}
		}

		return true;
	}

	/**
	 * Determine if an IPv6 address really is an IP address, and if it is public,
	 * i.e. not RFC 4193 or similar
	 *
	 * @param $ip String
	 * @return Boolean
	 */
	private static function isPublic6( $ip ) {
		static $privateRanges = false;
		if ( !$privateRanges ) {
			$privateRanges = array(
				array( 'fc00::', 'fdff:ffff:ffff:ffff:ffff:ffff:ffff:ffff' ), # RFC 4193 (local)
				array( '0:0:0:0:0:0:0:1', '0:0:0:0:0:0:0:1' ), # loopback
			);
		}
		$n = self::toHex( $ip );
		foreach ( $privateRanges as $r ) {
			$start = self::toHex( $r[0] );
			$end = self::toHex( $r[1] );
			if ( $n >= $start && $n <= $end ) {
				return false;
			}
		}

		return true;
	}

	/**
	 * Return a zero-padded upper case hexadecimal representation of an IP address.
	 *
	 * Hexadecimal addresses are used because they can easily be extended to
	 * IPv6 support. To separate the ranges, the return value from this
	 * function for an IPv6 address will be prefixed with "v6-", a non-
	 * hexadecimal string which sorts after the IPv4 addresses.
	 *
	 * @param string $ip quad dotted/octet IP address.
	 * @return String
	 */
	public static function toHex( $ip ) {
		if ( self::isIPv6( $ip ) ) {
			$n = 'v6-' . self::IPv6ToRawHex( $ip );
		} else {
			$n = self::toUnsigned( $ip );
			if ( $n !== false ) {
				$n = wfBaseConvert( $n, 10, 16, 8, false );
			}
		}

		return $n;
	}

	/**
	 * Given an IPv6 address in octet notation, returns a pure hex string.
	 *
	 * @param string $ip octet ipv6 IP address.
	 * @return String: pure hex (uppercase)
	 */
	private static function IPv6ToRawHex( $ip ) {
		$ip = self::sanitizeIP( $ip );
		if ( !$ip ) {
			return null;
		}
		$r_ip = '';
		foreach ( explode( ':', $ip ) as $v ) {
			$r_ip .= str_pad( $v, 4, 0, STR_PAD_LEFT );
		}

		return $r_ip;
	}

	/**
	 * Given an IP address in dotted-quad/octet notation, returns an unsigned integer.
	 * Like ip2long() except that it actually works and has a consistent error return value.
	 *
	 * @param string $ip quad dotted IP address.
	 * @return Mixed: string/int/false
	 */
	public static function toUnsigned( $ip ) {
		if ( self::isIPv6( $ip ) ) {
			$n = self::toUnsigned6( $ip );
		} else {
			// Bug 60035: an IP with leading 0's fails in ip2long sometimes (e.g. *.08)
			$ip = preg_replace( '/(?<=\.)0+(?=[1-9])/', '', $ip );
			$n = ip2long( $ip );
			if ( $n < 0 ) {
				$n += pow( 2, 32 );
				# On 32-bit platforms (and on Windows), 2^32 does not fit into an int,
				# so $n becomes a float. We convert it to string instead.
				if ( is_float( $n ) ) {
					$n = (string)$n;
				}
			}
		}

		return $n;
	}

	/**
	 * @param $ip
	 * @return String
	 */
	private static function toUnsigned6( $ip ) {
		return wfBaseConvert( self::IPv6ToRawHex( $ip ), 16, 10 );
	}

	/**
	 * Convert a network specification in CIDR notation
	 * to an integer network and a number of bits
	 *
	 * @param string $range IP with CIDR prefix
	 * @return array(int or string, int)
	 */
	public static function parseCIDR( $range ) {
		if ( self::isIPv6( $range ) ) {
			return self::parseCIDR6( $range );
		}
		$parts = explode( '/', $range, 2 );
		if ( count( $parts ) != 2 ) {
			return array( false, false );
		}
		list( $network, $bits ) = $parts;
		$network = ip2long( $network );
		if ( $network !== false && is_numeric( $bits ) && $bits >= 0 && $bits <= 32 ) {
			if ( $bits == 0 ) {
				$network = 0;
			} else {
				$network &= ~( ( 1 << ( 32 - $bits ) ) - 1 );
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
	 * Given a string range in a number of formats,
	 * return the start and end of the range in hexadecimal.
	 *
	 * Formats are:
	 *     1.2.3.4/24          CIDR
	 *     1.2.3.4 - 1.2.3.5   Explicit range
	 *     1.2.3.4             Single IP
	 *
	 *     2001:0db8:85a3::7344/96                       CIDR
	 *     2001:0db8:85a3::7344 - 2001:0db8:85a3::7344   Explicit range
	 *     2001:0db8:85a3::7344                          Single IP
	 * @param string $range IP range
	 * @return array(string, string)
	 */
	public static function parseRange( $range ) {
		// CIDR notation
		if ( strpos( $range, '/' ) !== false ) {
			if ( self::isIPv6( $range ) ) {
				return self::parseRange6( $range );
			}
			list( $network, $bits ) = self::parseCIDR( $range );
			if ( $network === false ) {
				$start = $end = false;
			} else {
				$start = sprintf( '%08X', $network );
				$end = sprintf( '%08X', $network + pow( 2, ( 32 - $bits ) ) - 1 );
			}
		// Explicit range
		} elseif ( strpos( $range, '-' ) !== false ) {
			list( $start, $end ) = array_map( 'trim', explode( '-', $range, 2 ) );
			if ( self::isIPv6( $start ) && self::isIPv6( $end ) ) {
				return self::parseRange6( $range );
			}
			if ( self::isIPv4( $start ) && self::isIPv4( $end ) ) {
				$start = self::toUnsigned( $start );
				$end = self::toUnsigned( $end );
				if ( $start > $end ) {
					$start = $end = false;
				} else {
					$start = sprintf( '%08X', $start );
					$end = sprintf( '%08X', $end );
				}
			} else {
				$start = $end = false;
			}
		} else {
			# Single IP
			$start = $end = self::toHex( $range );
		}
		if ( $start === false || $end === false ) {
			return array( false, false );
		} else {
			return array( $start, $end );
		}
	}

	/**
	 * Convert a network specification in IPv6 CIDR notation to an
	 * integer network and a number of bits
	 *
	 * @param $range
	 *
	 * @return array(string, int)
	 */
	private static function parseCIDR6( $range ) {
		# Explode into <expanded IP,range>
		$parts = explode( '/', IP::sanitizeIP( $range ), 2 );
		if ( count( $parts ) != 2 ) {
			return array( false, false );
		}
		list( $network, $bits ) = $parts;
		$network = self::IPv6ToRawHex( $network );
		if ( $network !== false && is_numeric( $bits ) && $bits >= 0 && $bits <= 128 ) {
			if ( $bits == 0 ) {
				$network = "0";
			} else {
				# Native 32 bit functions WONT work here!!!
				# Convert to a padded binary number
				$network = wfBaseConvert( $network, 16, 2, 128 );
				# Truncate the last (128-$bits) bits and replace them with zeros
				$network = str_pad( substr( $network, 0, $bits ), 128, 0, STR_PAD_RIGHT );
				# Convert back to an integer
				$network = wfBaseConvert( $network, 2, 10 );
			}
		} else {
			$network = false;
			$bits = false;
		}

		return array( $network, (int)$bits );
	}

	/**
	 * Given a string range in a number of formats, return the
	 * start and end of the range in hexadecimal. For IPv6.
	 *
	 * Formats are:
	 *     2001:0db8:85a3::7344/96                       CIDR
	 *     2001:0db8:85a3::7344 - 2001:0db8:85a3::7344   Explicit range
	 *     2001:0db8:85a3::7344/96                       Single IP
	 *
	 * @param $range
	 *
	 * @return array(string, string)
	 */
	private static function parseRange6( $range ) {
		# Expand any IPv6 IP
		$range = IP::sanitizeIP( $range );
		// CIDR notation...
		if ( strpos( $range, '/' ) !== false ) {
			list( $network, $bits ) = self::parseCIDR6( $range );
			if ( $network === false ) {
				$start = $end = false;
			} else {
				$start = wfBaseConvert( $network, 10, 16, 32, false );
				# Turn network to binary (again)
				$end = wfBaseConvert( $network, 10, 2, 128 );
				# Truncate the last (128-$bits) bits and replace them with ones
				$end = str_pad( substr( $end, 0, $bits ), 128, 1, STR_PAD_RIGHT );
				# Convert to hex
				$end = wfBaseConvert( $end, 2, 16, 32, false );
				# see toHex() comment
				$start = "v6-$start";
				$end = "v6-$end";
			}
	// Explicit range notation...
		} elseif ( strpos( $range, '-' ) !== false ) {
			list( $start, $end ) = array_map( 'trim', explode( '-', $range, 2 ) );
			$start = self::toUnsigned6( $start );
			$end = self::toUnsigned6( $end );
			if ( $start > $end ) {
				$start = $end = false;
			} else {
				$start = wfBaseConvert( $start, 10, 16, 32, false );
				$end = wfBaseConvert( $end, 10, 16, 32, false );
			}
			# see toHex() comment
			$start = "v6-$start";
			$end = "v6-$end";
		} else {
			# Single IP
			$start = $end = self::toHex( $range );
		}
		if ( $start === false || $end === false ) {
			return array( false, false );
		} else {
			return array( $start, $end );
		}
	}

	/**
	 * Determine if a given IPv4/IPv6 address is in a given CIDR network
	 *
	 * @param string $addr the address to check against the given range.
	 * @param string $range the range to check the given address against.
	 * @return Boolean: whether or not the given address is in the given range.
	 */
	public static function isInRange( $addr, $range ) {
		$hexIP = self::toHex( $addr );
		list( $start, $end ) = self::parseRange( $range );

		return ( strcmp( $hexIP, $start ) >= 0 &&
			strcmp( $hexIP, $end ) <= 0 );
	}

	/**
	 * Convert some unusual representations of IPv4 addresses to their
	 * canonical dotted quad representation.
	 *
	 * This currently only checks a few IPV4-to-IPv6 related cases.  More
	 * unusual representations may be added later.
	 *
	 * @param string $addr something that might be an IP address
	 * @return String: valid dotted quad IPv4 address or null
	 */
	public static function canonicalize( $addr ) {
		// remove zone info (bug 35738)
		$addr = preg_replace( '/\%.*/', '', $addr );

		if ( self::isValid( $addr ) ) {
			return $addr;
		}
		// Turn mapped addresses from ::ce:ffff:1.2.3.4 to 1.2.3.4
		if ( strpos( $addr, ':' ) !== false && strpos( $addr, '.' ) !== false ) {
			$addr = substr( $addr, strrpos( $addr, ':' ) + 1 );
			if ( self::isIPv4( $addr ) ) {
				return $addr;
			}
		}
		// IPv6 loopback address
		$m = array();
		if ( preg_match( '/^0*' . RE_IPV6_GAP . '1$/', $addr, $m ) ) {
			return '127.0.0.1';
		}
		// IPv4-mapped and IPv4-compatible IPv6 addresses
		if ( preg_match( '/^' . RE_IPV6_V4_PREFIX . '(' . RE_IP_ADD . ')$/i', $addr, $m ) ) {
			return $m[1];
		}
		if ( preg_match( '/^' . RE_IPV6_V4_PREFIX . RE_IPV6_WORD .
			':' . RE_IPV6_WORD . '$/i', $addr, $m )
		) {
			return long2ip( ( hexdec( $m[1] ) << 16 ) + hexdec( $m[2] ) );
		}

		return null; // give up
	}

	/**
	 * Gets rid of unneeded numbers in quad-dotted/octet IP strings
	 * For example, 127.111.113.151/24 -> 127.111.113.0/24
	 * @param string $range IP address to normalize
	 * @return string
	 */
	public static function sanitizeRange( $range ) {
		list( /*...*/, $bits ) = self::parseCIDR( $range );
		list( $start, /*...*/ ) = self::parseRange( $range );
		$start = self::formatHex( $start );
		if ( $bits === false ) {
			return $start; // wasn't actually a range
		}

		return "$start/$bits";
	}
}
