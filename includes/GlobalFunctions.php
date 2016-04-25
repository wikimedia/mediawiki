<?php
/**
 * Global functions used everywhere.
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
 */

if ( !defined( 'MEDIAWIKI' ) ) {
	die( "This file is part of MediaWiki, it is not a valid entry point" );
}

// Hide compatibility functions from Doxygen
/// @cond
/**
 * Compatibility functions
 *
 * We support PHP 5.3.2 and up.
 * Re-implementations of newer functions or functions in non-standard
 * PHP extensions may be included here.
 */

if ( !function_exists( 'iconv' ) ) {
	/**
	 * @codeCoverageIgnore
	 * @return string
	 */
	function iconv( $from, $to, $string ) {
		return Fallback::iconv( $from, $to, $string );
	}
}

if ( !function_exists( 'mb_substr' ) ) {
	/**
	 * @codeCoverageIgnore
	 * @return string
	 */
	function mb_substr( $str, $start, $count = 'end' ) {
		return Fallback::mb_substr( $str, $start, $count );
	}

	/**
	 * @codeCoverageIgnore
	 * @return int
	 */
	function mb_substr_split_unicode( $str, $splitPos ) {
		return Fallback::mb_substr_split_unicode( $str, $splitPos );
	}
}

if ( !function_exists( 'mb_strlen' ) ) {
	/**
	 * @codeCoverageIgnore
	 * @return int
	 */
	function mb_strlen( $str, $enc = '' ) {
		return Fallback::mb_strlen( $str, $enc );
	}
}

if ( !function_exists( 'mb_strpos' ) ) {
	/**
	 * @codeCoverageIgnore
	 * @return int
	 */
	function mb_strpos( $haystack, $needle, $offset = 0, $encoding = '' ) {
		return Fallback::mb_strpos( $haystack, $needle, $offset, $encoding );
	}
}

if ( !function_exists( 'mb_strrpos' ) ) {
	/**
	 * @codeCoverageIgnore
	 * @return int
	 */
	function mb_strrpos( $haystack, $needle, $offset = 0, $encoding = '' ) {
		return Fallback::mb_strrpos( $haystack, $needle, $offset, $encoding );
	}
}

// gzdecode function only exists in PHP >= 5.4.0
// http://php.net/gzdecode
if ( !function_exists( 'gzdecode' ) ) {
	/**
	 * @codeCoverageIgnore
	 * @return string
	 */
	function gzdecode( $data ) {
		return gzinflate( substr( $data, 10, -8 ) );
	}
}

// hash_equals function only exists in PHP >= 5.6.0
if ( !function_exists( 'hash_equals' ) ) {
  /**
   * Check whether a user-provided string is equal to a fixed-length secret without
   * revealing bytes of the secret through timing differences.
   *
   * This timing guarantee -- that a partial match takes the same time as a complete
   * mismatch -- is why this function is used in some security-sensitive parts of the code.
   * For example, it shouldn't be possible to guess an HMAC signature one byte at a time.
   *
   * Longer explanation: http://www.emerose.com/timing-attacks-explained
   *
   * @codeCoverageIgnore
   * @param string $known_string Fixed-length secret to compare against
   * @param string $user_string User-provided string
   * @return bool True if the strings are the same, false otherwise
   */
  function hash_equals( $known_string, $user_string ) {
    // Strict type checking as in PHP's native implementation
    if ( !is_string( $known_string ) ) {
      trigger_error( 'hash_equals(): Expected known_string to be a string, ' .
        gettype( $known_string ) . ' given', E_USER_WARNING );

      return false;
    }

    if ( !is_string( $user_string ) ) {
      trigger_error( 'hash_equals(): Expected user_string to be a string, ' .
        gettype( $user_string ) . ' given', E_USER_WARNING );

      return false;
    }

    // Note that we do one thing PHP doesn't: try to avoid leaking information about
    // relative lengths of $known_string and $user_string, and of multiple $known_strings.
    // However, lengths may still inevitably leak through, for example, CPU cache misses.
    $known_string_len = strlen( $known_string );
    $user_string_len = strlen( $user_string );
    $result = $known_string_len ^ $user_string_len;
    for ( $i = 0; $i < $user_string_len; $i++ ) {
      $result |= ord( $known_string[$i % $known_string_len] ) ^ ord( $user_string[$i] );
    }

    return ( $result === 0 );
  }
}
/// @endcond

/**
 * Like array_diff( $a, $b ) except that it works with two-dimensional arrays.
 * @param $a array
 * @param $b array
 * @return array
 */
function wfArrayDiff2( $a, $b ) {
	return array_udiff( $a, $b, 'wfArrayDiff2_cmp' );
}

/**
 * @param $a array|string
 * @param $b array|string
 * @return int
 */
function wfArrayDiff2_cmp( $a, $b ) {
	if ( is_string( $a ) && is_string( $b ) ) {
		return strcmp( $a, $b );
	} elseif ( count( $a ) !== count( $b ) ) {
		return count( $a ) < count( $b ) ? -1 : 1;
	} else {
		reset( $a );
		reset( $b );
		while ( ( list( , $valueA ) = each( $a ) ) && ( list( , $valueB ) = each( $b ) ) ) {
			$cmp = strcmp( $valueA, $valueB );
			if ( $cmp !== 0 ) {
				return $cmp;
			}
		}
		return 0;
	}
}

/**
 * Array lookup
 * Returns an array where the values in array $b are replaced by the
 * values in array $a with the corresponding keys
 *
 * @deprecated since 1.22; use array_intersect_key()
 * @param $a Array
 * @param $b Array
 * @return array
 */
function wfArrayLookup( $a, $b ) {
	wfDeprecated( __FUNCTION__, '1.22' );
	return array_flip( array_intersect( array_flip( $a ), array_keys( $b ) ) );
}

/**
 * Appends to second array if $value differs from that in $default
 *
 * @param $key String|Int
 * @param $value Mixed
 * @param $default Mixed
 * @param array $changed to alter
 * @throws MWException
 */
function wfAppendToArrayIfNotDefault( $key, $value, $default, &$changed ) {
	if ( is_null( $changed ) ) {
		throw new MWException( 'GlobalFunctions::wfAppendToArrayIfNotDefault got null' );
	}
	if ( $default[$key] !== $value ) {
		$changed[$key] = $value;
	}
}

/**
 * Backwards array plus for people who haven't bothered to read the PHP manual
 * XXX: will not darn your socks for you.
 *
 * @deprecated since 1.22; use array_replace()
 *
 * @param array $array1 Initial array to merge.
 * @param array [$array2,...] Variable list of arrays to merge.
 * @return array
 */
function wfArrayMerge( $array1 /*...*/ ) {
	wfDeprecated( __FUNCTION__, '1.22' );
	$args = func_get_args();
	$args = array_reverse( $args, true );
	$out = array();
	foreach ( $args as $arg ) {
		$out += $arg;
	}
	return $out;
}

/**
 * Merge arrays in the style of getUserPermissionsErrors, with duplicate removal
 * e.g.
 *	wfMergeErrorArrays(
 *		array( array( 'x' ) ),
 *		array( array( 'x', '2' ) ),
 *		array( array( 'x' ) ),
 *		array( array( 'y' ) )
 *	);
 * returns:
 * 		array(
 *   		array( 'x', '2' ),
 *   		array( 'x' ),
 *   		array( 'y' )
 *   	)
 *
 * @param array [$array1,...]
 * @return array
 */
function wfMergeErrorArrays( /*...*/ ) {
	$args = func_get_args();
	$out = array();
	foreach ( $args as $errors ) {
		foreach ( $errors as $params ) {
			# @todo FIXME: Sometimes get nested arrays for $params,
			# which leads to E_NOTICEs
			$spec = implode( "\t", $params );
			$out[$spec] = $params;
		}
	}
	return array_values( $out );
}

/**
 * Insert array into another array after the specified *KEY*
 *
 * @param array $array The array.
 * @param array $insert The array to insert.
 * @param $after Mixed: The key to insert after
 * @return Array
 */
function wfArrayInsertAfter( array $array, array $insert, $after ) {
	// Find the offset of the element to insert after.
	$keys = array_keys( $array );
	$offsetByKey = array_flip( $keys );

	$offset = $offsetByKey[$after];

	// Insert at the specified offset
	$before = array_slice( $array, 0, $offset + 1, true );
	$after = array_slice( $array, $offset + 1, count( $array ) - $offset, true );

	$output = $before + $insert + $after;

	return $output;
}

/**
 * Recursively converts the parameter (an object) to an array with the same data
 *
 * @param $objOrArray Object|Array
 * @param $recursive Bool
 * @return Array
 */
function wfObjectToArray( $objOrArray, $recursive = true ) {
	$array = array();
	if ( is_object( $objOrArray ) ) {
		$objOrArray = get_object_vars( $objOrArray );
	}
	foreach ( $objOrArray as $key => $value ) {
		if ( $recursive && ( is_object( $value ) || is_array( $value ) ) ) {
			$value = wfObjectToArray( $value );
		}

		$array[$key] = $value;
	}

	return $array;
}

/**
 * Get a random decimal value between 0 and 1, in a way
 * not likely to give duplicate values for any realistic
 * number of articles.
 *
 * @return string
 */
function wfRandom() {
	# The maximum random value is "only" 2^31-1, so get two random
	# values to reduce the chance of dupes
	$max = mt_getrandmax() + 1;
	$rand = number_format( ( mt_rand() * $max + mt_rand() ) / $max / $max, 12, '.', '' );

	return $rand;
}

/**
 * Get a random string containing a number of pseudo-random hex
 * characters.
 * @note This is not secure, if you are trying to generate some sort
 *       of token please use MWCryptRand instead.
 *
 * @param int $length The length of the string to generate
 * @return String
 * @since 1.20
 */
function wfRandomString( $length = 32 ) {
	$str = '';
	for ( $n = 0; $n < $length; $n += 7 ) {
		$str .= sprintf( '%07x', mt_rand() & 0xfffffff );
	}
	return substr( $str, 0, $length );
}

/**
 * We want some things to be included as literal characters in our title URLs
 * for prettiness, which urlencode encodes by default.  According to RFC 1738,
 * all of the following should be safe:
 *
 * ;:@&=$-_.+!*'(),
 *
 * But + is not safe because it's used to indicate a space; &= are only safe in
 * paths and not in queries (and we don't distinguish here); ' seems kind of
 * scary; and urlencode() doesn't touch -_. to begin with.  Plus, although /
 * is reserved, we don't care.  So the list we unescape is:
 *
 * ;:@$!*(),/
 *
 * However, IIS7 redirects fail when the url contains a colon (Bug 22709),
 * so no fancy : for IIS7.
 *
 * %2F in the page titles seems to fatally break for some reason.
 *
 * @param $s String:
 * @return string
 */
function wfUrlencode( $s ) {
	static $needle;

	if ( is_null( $s ) ) {
		$needle = null;
		return '';
	}

	if ( is_null( $needle ) ) {
		$needle = array( '%3B', '%40', '%24', '%21', '%2A', '%28', '%29', '%2C', '%2F' );
		if ( !isset( $_SERVER['SERVER_SOFTWARE'] ) ||
			( strpos( $_SERVER['SERVER_SOFTWARE'], 'Microsoft-IIS/7' ) === false )
		) {
			$needle[] = '%3A';
		}
	}

	$s = urlencode( $s );
	$s = str_ireplace(
		$needle,
		array( ';', '@', '$', '!', '*', '(', ')', ',', '/', ':' ),
		$s
	);

	return $s;
}

/**
 * This function takes two arrays as input, and returns a CGI-style string, e.g.
 * "days=7&limit=100". Options in the first array override options in the second.
 * Options set to null or false will not be output.
 *
 * @param array $array1 ( String|Array )
 * @param array $array2 ( String|Array )
 * @param $prefix String
 * @return String
 */
function wfArrayToCgi( $array1, $array2 = null, $prefix = '' ) {
	if ( !is_null( $array2 ) ) {
		$array1 = $array1 + $array2;
	}

	$cgi = '';
	foreach ( $array1 as $key => $value ) {
		if ( !is_null( $value ) && $value !== false ) {
			if ( $cgi != '' ) {
				$cgi .= '&';
			}
			if ( $prefix !== '' ) {
				$key = $prefix . "[$key]";
			}
			if ( is_array( $value ) ) {
				$firstTime = true;
				foreach ( $value as $k => $v ) {
					$cgi .= $firstTime ? '' : '&';
					if ( is_array( $v ) ) {
						$cgi .= wfArrayToCgi( $v, null, $key . "[$k]" );
					} else {
						$cgi .= urlencode( $key . "[$k]" ) . '=' . urlencode( $v );
					}
					$firstTime = false;
				}
			} else {
				if ( is_object( $value ) ) {
					$value = $value->__toString();
				}
				$cgi .= urlencode( $key ) . '=' . urlencode( $value );
			}
		}
	}
	return $cgi;
}

/**
 * This is the logical opposite of wfArrayToCgi(): it accepts a query string as
 * its argument and returns the same string in array form.  This allows compatibility
 * with legacy functions that accept raw query strings instead of nice
 * arrays.  Of course, keys and values are urldecode()d.
 *
 * @param string $query query string
 * @return string[] Array version of input
 */
function wfCgiToArray( $query ) {
	if ( isset( $query[0] ) && $query[0] == '?' ) {
		$query = substr( $query, 1 );
	}
	$bits = explode( '&', $query );
	$ret = array();
	foreach ( $bits as $bit ) {
		if ( $bit === '' ) {
			continue;
		}
		if ( strpos( $bit, '=' ) === false ) {
			// Pieces like &qwerty become 'qwerty' => '' (at least this is what php does)
			$key = $bit;
			$value = '';
		} else {
			list( $key, $value ) = explode( '=', $bit );
		}
		$key = urldecode( $key );
		$value = urldecode( $value );
		if ( strpos( $key, '[' ) !== false ) {
			$keys = array_reverse( explode( '[', $key ) );
			$key = array_pop( $keys );
			$temp = $value;
			foreach ( $keys as $k ) {
				$k = substr( $k, 0, -1 );
				$temp = array( $k => $temp );
			}
			if ( isset( $ret[$key] ) ) {
				$ret[$key] = array_merge( $ret[$key], $temp );
			} else {
				$ret[$key] = $temp;
			}
		} else {
			$ret[$key] = $value;
		}
	}
	return $ret;
}

/**
 * Append a query string to an existing URL, which may or may not already
 * have query string parameters already. If so, they will be combined.
 *
 * @param string $url
 * @param string|string[] $query String or associative array
 * @return string
 */
function wfAppendQuery( $url, $query ) {
	if ( is_array( $query ) ) {
		$query = wfArrayToCgi( $query );
	}
	if ( $query != '' ) {
		if ( false === strpos( $url, '?' ) ) {
			$url .= '?';
		} else {
			$url .= '&';
		}
		$url .= $query;
	}
	return $url;
}

/**
 * Expand a potentially local URL to a fully-qualified URL. Assumes $wgServer
 * is correct.
 *
 * The meaning of the PROTO_* constants is as follows:
 * PROTO_HTTP: Output a URL starting with http://
 * PROTO_HTTPS: Output a URL starting with https://
 * PROTO_RELATIVE: Output a URL starting with // (protocol-relative URL)
 * PROTO_CURRENT: Output a URL starting with either http:// or https:// , depending
 *    on which protocol was used for the current incoming request
 * PROTO_CANONICAL: For URLs without a domain, like /w/index.php , use $wgCanonicalServer.
 *    For protocol-relative URLs, use the protocol of $wgCanonicalServer
 * PROTO_INTERNAL: Like PROTO_CANONICAL, but uses $wgInternalServer instead of $wgCanonicalServer
 *
 * @todo this won't work with current-path-relative URLs
 * like "subdir/foo.html", etc.
 *
 * @param string $url either fully-qualified or a local path + query
 * @param $defaultProto Mixed: one of the PROTO_* constants. Determines the
 *    protocol to use if $url or $wgServer is protocol-relative
 * @return string Fully-qualified URL, current-path-relative URL or false if
 *    no valid URL can be constructed
 */
function wfExpandUrl( $url, $defaultProto = PROTO_CURRENT ) {
	global $wgServer, $wgCanonicalServer, $wgInternalServer, $wgRequest;
	if ( $defaultProto === PROTO_CANONICAL ) {
		$serverUrl = $wgCanonicalServer;
	} elseif ( $defaultProto === PROTO_INTERNAL && $wgInternalServer !== false ) {
		// Make $wgInternalServer fall back to $wgServer if not set
		$serverUrl = $wgInternalServer;
	} else {
		$serverUrl = $wgServer;
		if ( $defaultProto === PROTO_CURRENT ) {
			$defaultProto = $wgRequest->getProtocol() . '://';
		}
	}

	// Analyze $serverUrl to obtain its protocol
	$bits = wfParseUrl( $serverUrl );
	$serverHasProto = $bits && $bits['scheme'] != '';

	if ( $defaultProto === PROTO_CANONICAL || $defaultProto === PROTO_INTERNAL ) {
		if ( $serverHasProto ) {
			$defaultProto = $bits['scheme'] . '://';
		} else {
			// $wgCanonicalServer or $wgInternalServer doesn't have a protocol.
			// This really isn't supposed to happen. Fall back to HTTP in this
			// ridiculous case.
			$defaultProto = PROTO_HTTP;
		}
	}

	$defaultProtoWithoutSlashes = substr( $defaultProto, 0, -2 );

	if ( substr( $url, 0, 2 ) == '//' ) {
		$url = $defaultProtoWithoutSlashes . $url;
	} elseif ( substr( $url, 0, 1 ) == '/' ) {
		// If $serverUrl is protocol-relative, prepend $defaultProtoWithoutSlashes,
		// otherwise leave it alone.
		$url = ( $serverHasProto ? '' : $defaultProtoWithoutSlashes ) . $serverUrl . $url;
	}

	$bits = wfParseUrl( $url );
	if ( $bits && isset( $bits['path'] ) ) {
		$bits['path'] = wfRemoveDotSegments( $bits['path'] );
		return wfAssembleUrl( $bits );
	} elseif ( $bits ) {
		# No path to expand
		return $url;
	} elseif ( substr( $url, 0, 1 ) != '/' ) {
		# URL is a relative path
		return wfRemoveDotSegments( $url );
	}

	# Expanded URL is not valid.
	return false;
}

/**
 * This function will reassemble a URL parsed with wfParseURL.  This is useful
 * if you need to edit part of a URL and put it back together.
 *
 * This is the basic structure used (brackets contain keys for $urlParts):
 * [scheme][delimiter][user]:[pass]@[host]:[port][path]?[query]#[fragment]
 *
 * @todo Need to integrate this into wfExpandUrl (bug 32168)
 *
 * @since 1.19
 * @param array $urlParts URL parts, as output from wfParseUrl
 * @return string URL assembled from its component parts
 */
function wfAssembleUrl( $urlParts ) {
	$result = '';

	if ( isset( $urlParts['delimiter'] ) ) {
		if ( isset( $urlParts['scheme'] ) ) {
			$result .= $urlParts['scheme'];
		}

		$result .= $urlParts['delimiter'];
	}

	if ( isset( $urlParts['host'] ) ) {
		if ( isset( $urlParts['user'] ) ) {
			$result .= $urlParts['user'];
			if ( isset( $urlParts['pass'] ) ) {
				$result .= ':' . $urlParts['pass'];
			}
			$result .= '@';
		}

		$result .= $urlParts['host'];

		if ( isset( $urlParts['port'] ) ) {
			$result .= ':' . $urlParts['port'];
		}
	}

	if ( isset( $urlParts['path'] ) ) {
		$result .= $urlParts['path'];
	}

	if ( isset( $urlParts['query'] ) ) {
		$result .= '?' . $urlParts['query'];
	}

	if ( isset( $urlParts['fragment'] ) ) {
		$result .= '#' . $urlParts['fragment'];
	}

	return $result;
}

/**
 * Remove all dot-segments in the provided URL path.  For example,
 * '/a/./b/../c/' becomes '/a/c/'.  For details on the algorithm, please see
 * RFC3986 section 5.2.4.
 *
 * @todo Need to integrate this into wfExpandUrl (bug 32168)
 *
 * @param string $urlPath URL path, potentially containing dot-segments
 * @return string URL path with all dot-segments removed
 */
function wfRemoveDotSegments( $urlPath ) {
	$output = '';
	$inputOffset = 0;
	$inputLength = strlen( $urlPath );

	while ( $inputOffset < $inputLength ) {
		$prefixLengthOne = substr( $urlPath, $inputOffset, 1 );
		$prefixLengthTwo = substr( $urlPath, $inputOffset, 2 );
		$prefixLengthThree = substr( $urlPath, $inputOffset, 3 );
		$prefixLengthFour = substr( $urlPath, $inputOffset, 4 );
		$trimOutput = false;

		if ( $prefixLengthTwo == './' ) {
			# Step A, remove leading "./"
			$inputOffset += 2;
		} elseif ( $prefixLengthThree == '../' ) {
			# Step A, remove leading "../"
			$inputOffset += 3;
		} elseif ( ( $prefixLengthTwo == '/.' ) && ( $inputOffset + 2 == $inputLength ) ) {
			# Step B, replace leading "/.$" with "/"
			$inputOffset += 1;
			$urlPath[$inputOffset] = '/';
		} elseif ( $prefixLengthThree == '/./' ) {
			# Step B, replace leading "/./" with "/"
			$inputOffset += 2;
		} elseif ( $prefixLengthThree == '/..' && ( $inputOffset + 3 == $inputLength ) ) {
			# Step C, replace leading "/..$" with "/" and
			# remove last path component in output
			$inputOffset += 2;
			$urlPath[$inputOffset] = '/';
			$trimOutput = true;
		} elseif ( $prefixLengthFour == '/../' ) {
			# Step C, replace leading "/../" with "/" and
			# remove last path component in output
			$inputOffset += 3;
			$trimOutput = true;
		} elseif ( ( $prefixLengthOne == '.' ) && ( $inputOffset + 1 == $inputLength ) ) {
			# Step D, remove "^.$"
			$inputOffset += 1;
		} elseif ( ( $prefixLengthTwo == '..' ) && ( $inputOffset + 2 == $inputLength ) ) {
			# Step D, remove "^..$"
			$inputOffset += 2;
		} else {
			# Step E, move leading path segment to output
			if ( $prefixLengthOne == '/' ) {
				$slashPos = strpos( $urlPath, '/', $inputOffset + 1 );
			} else {
				$slashPos = strpos( $urlPath, '/', $inputOffset );
			}
			if ( $slashPos === false ) {
				$output .= substr( $urlPath, $inputOffset );
				$inputOffset = $inputLength;
			} else {
				$output .= substr( $urlPath, $inputOffset, $slashPos - $inputOffset );
				$inputOffset += $slashPos - $inputOffset;
			}
		}

		if ( $trimOutput ) {
			$slashPos = strrpos( $output, '/' );
			if ( $slashPos === false ) {
				$output = '';
			} else {
				$output = substr( $output, 0, $slashPos );
			}
		}
	}

	return $output;
}

/**
 * Returns a regular expression of url protocols
 *
 * @param bool $includeProtocolRelative If false, remove '//' from the returned protocol list.
 *        DO NOT USE this directly, use wfUrlProtocolsWithoutProtRel() instead
 * @return String
 */
function wfUrlProtocols( $includeProtocolRelative = true ) {
	global $wgUrlProtocols;

	// Cache return values separately based on $includeProtocolRelative
	static $withProtRel = null, $withoutProtRel = null;
	$cachedValue = $includeProtocolRelative ? $withProtRel : $withoutProtRel;
	if ( !is_null( $cachedValue ) ) {
		return $cachedValue;
	}

	// Support old-style $wgUrlProtocols strings, for backwards compatibility
	// with LocalSettings files from 1.5
	if ( is_array( $wgUrlProtocols ) ) {
		$protocols = array();
		foreach ( $wgUrlProtocols as $protocol ) {
			// Filter out '//' if !$includeProtocolRelative
			if ( $includeProtocolRelative || $protocol !== '//' ) {
				$protocols[] = preg_quote( $protocol, '/' );
			}
		}

		$retval = implode( '|', $protocols );
	} else {
		// Ignore $includeProtocolRelative in this case
		// This case exists for pre-1.6 compatibility, and we can safely assume
		// that '//' won't appear in a pre-1.6 config because protocol-relative
		// URLs weren't supported until 1.18
		$retval = $wgUrlProtocols;
	}

	// Cache return value
	if ( $includeProtocolRelative ) {
		$withProtRel = $retval;
	} else {
		$withoutProtRel = $retval;
	}
	return $retval;
}

/**
 * Like wfUrlProtocols(), but excludes '//' from the protocol list. Use this if
 * you need a regex that matches all URL protocols but does not match protocol-
 * relative URLs
 * @return String
 */
function wfUrlProtocolsWithoutProtRel() {
	return wfUrlProtocols( false );
}

/**
 * parse_url() work-alike, but non-broken.  Differences:
 *
 * 1) Does not raise warnings on bad URLs (just returns false).
 * 2) Handles protocols that don't use :// (e.g., mailto: and news:, as well as
 *    protocol-relative URLs) correctly.
 * 3) Adds a "delimiter" element to the array, either '://', ':' or '//' (see (2)).
 *
 * @param string $url a URL to parse
 * @return string[] Bits of the URL in an associative array, per PHP docs
 */
function wfParseUrl( $url ) {
	global $wgUrlProtocols; // Allow all protocols defined in DefaultSettings/LocalSettings.php

	// Protocol-relative URLs are handled really badly by parse_url(). It's so
	// bad that the easiest way to handle them is to just prepend 'http:' and
	// strip the protocol out later.
	$wasRelative = substr( $url, 0, 2 ) == '//';
	if ( $wasRelative ) {
		$url = "http:$url";
	}
	wfSuppressWarnings();
	$bits = parse_url( $url );
	wfRestoreWarnings();
	// parse_url() returns an array without scheme for some invalid URLs, e.g.
	// parse_url("%0Ahttp://example.com") == array( 'host' => '%0Ahttp', 'path' => 'example.com' )
	if ( !$bits || !isset( $bits['scheme'] ) ) {
		return false;
	}

	// parse_url() incorrectly handles schemes case-sensitively. Convert it to lowercase.
	$bits['scheme'] = strtolower( $bits['scheme'] );

	// most of the protocols are followed by ://, but mailto: and sometimes news: not, check for it
	if ( in_array( $bits['scheme'] . '://', $wgUrlProtocols ) ) {
		$bits['delimiter'] = '://';
	} elseif ( in_array( $bits['scheme'] . ':', $wgUrlProtocols ) ) {
		$bits['delimiter'] = ':';
		// parse_url detects for news: and mailto: the host part of an url as path
		// We have to correct this wrong detection
		if ( isset( $bits['path'] ) ) {
			$bits['host'] = $bits['path'];
			$bits['path'] = '';
		}
	} else {
		return false;
	}

	/* Provide an empty host for eg. file:/// urls (see bug 28627) */
	if ( !isset( $bits['host'] ) ) {
		$bits['host'] = '';

		// bug 45069
		if ( isset( $bits['path'] ) ) {
			/* parse_url loses the third / for file:///c:/ urls (but not on variants) */
			if ( substr( $bits['path'], 0, 1 ) !== '/' ) {
				$bits['path'] = '/' . $bits['path'];
			}
		} else {
			$bits['path'] = '';
		}
	}

	// If the URL was protocol-relative, fix scheme and delimiter
	if ( $wasRelative ) {
		$bits['scheme'] = '';
		$bits['delimiter'] = '//';
	}
	return $bits;
}

/**
 * Take a URL, make sure it's expanded to fully qualified, and replace any
 * encoded non-ASCII Unicode characters with their UTF-8 original forms
 * for more compact display and legibility for local audiences.
 *
 * @todo handle punycode domains too
 *
 * @param $url string
 * @return string
 */
function wfExpandIRI( $url ) {
	return preg_replace_callback(
		'/((?:%[89A-F][0-9A-F])+)/i',
		'wfExpandIRI_callback',
		wfExpandUrl( $url )
	);
}

/**
 * Private callback for wfExpandIRI
 * @param array $matches
 * @return string
 */
function wfExpandIRI_callback( $matches ) {
	return urldecode( $matches[1] );
}

/**
 * Make URL indexes, appropriate for the el_index field of externallinks.
 *
 * @param $url String
 * @return array
 */
function wfMakeUrlIndexes( $url ) {
	$bits = wfParseUrl( $url );

	// Reverse the labels in the hostname, convert to lower case
	// For emails reverse domainpart only
	if ( $bits['scheme'] == 'mailto' ) {
		$mailparts = explode( '@', $bits['host'], 2 );
		if ( count( $mailparts ) === 2 ) {
			$domainpart = strtolower( implode( '.', array_reverse( explode( '.', $mailparts[1] ) ) ) );
		} else {
			// No domain specified, don't mangle it
			$domainpart = '';
		}
		$reversedHost = $domainpart . '@' . $mailparts[0];
	} else {
		$reversedHost = strtolower( implode( '.', array_reverse( explode( '.', $bits['host'] ) ) ) );
	}
	// Add an extra dot to the end
	// Why? Is it in wrong place in mailto links?
	if ( substr( $reversedHost, -1, 1 ) !== '.' ) {
		$reversedHost .= '.';
	}
	// Reconstruct the pseudo-URL
	$prot = $bits['scheme'];
	$index = $prot . $bits['delimiter'] . $reversedHost;
	// Leave out user and password. Add the port, path, query and fragment
	if ( isset( $bits['port'] ) ) {
		$index .= ':' . $bits['port'];
	}
	if ( isset( $bits['path'] ) ) {
		$index .= $bits['path'];
	} else {
		$index .= '/';
	}
	if ( isset( $bits['query'] ) ) {
		$index .= '?' . $bits['query'];
	}
	if ( isset( $bits['fragment'] ) ) {
		$index .= '#' . $bits['fragment'];
	}

	if ( $prot == '' ) {
		return array( "http:$index", "https:$index" );
	} else {
		return array( $index );
	}
}

/**
 * Check whether a given URL has a domain that occurs in a given set of domains
 * @param string $url URL
 * @param array $domains Array of domains (strings)
 * @return bool True if the host part of $url ends in one of the strings in $domains
 */
function wfMatchesDomainList( $url, $domains ) {
	$bits = wfParseUrl( $url );
	if ( is_array( $bits ) && isset( $bits['host'] ) ) {
		$host = '.' . $bits['host'];
		foreach ( (array)$domains as $domain ) {
			$domain = '.' . $domain;
			if ( substr( $host, -strlen( $domain ) ) === $domain ) {
				return true;
			}
		}
	}
	return false;
}

/**
 * Sends a line to the debug log if enabled or, optionally, to a comment in output.
 * In normal operation this is a NOP.
 *
 * Controlling globals:
 * $wgDebugLogFile - points to the log file
 * $wgDebugRawPage - if false, 'action=raw' hits will not result in debug output.
 * $wgDebugComments - if on, some debug items may appear in comments in the HTML output.
 *
 * @param $text String
 * @param string|bool $dest Destination of the message:
 *     - 'all': both to the log and HTML (debug toolbar or HTML comments)
 *     - 'log': only to the log and not in HTML
 *   For backward compatibility, it can also take a boolean:
 *     - true: same as 'all'
 *     - false: same as 'log'
 */
function wfDebug( $text, $dest = 'all' ) {
	global $wgDebugLogFile, $wgDebugRawPage, $wgDebugLogPrefix;

	if ( !$wgDebugRawPage && wfIsDebugRawPage() ) {
		return;
	}

	// Turn $dest into a string if it's a boolean (for b/c)
	if ( $dest === true ) {
		$dest = 'all';
	} elseif ( $dest === false ) {
		$dest = 'log';
	}

	$timer = wfDebugTimer();
	if ( $timer !== '' ) {
		$text = preg_replace( '/[^\n]/', $timer . '\0', $text, 1 );
	}

	if ( $dest === 'all' ) {
		MWDebug::debugMsg( $text );
	}

	if ( $wgDebugLogFile != '' ) {
		# Strip unprintables; they can switch terminal modes when binary data
		# gets dumped, which is pretty annoying.
		$text = preg_replace( '![\x00-\x08\x0b\x0c\x0e-\x1f]!', ' ', $text );
		$text = $wgDebugLogPrefix . $text;
		wfErrorLog( $text, $wgDebugLogFile );
	}
}

/**
 * Returns true if debug logging should be suppressed if $wgDebugRawPage = false
 * @return bool
 */
function wfIsDebugRawPage() {
	static $cache;
	if ( $cache !== null ) {
		return $cache;
	}
	# Check for raw action using $_GET not $wgRequest, since the latter might not be initialised yet
	if ( ( isset( $_GET['action'] ) && $_GET['action'] == 'raw' )
		|| (
			isset( $_SERVER['SCRIPT_NAME'] )
			&& substr( $_SERVER['SCRIPT_NAME'], -8 ) == 'load.php'
		)
	) {
		$cache = true;
	} else {
		$cache = false;
	}
	return $cache;
}

/**
 * Get microsecond timestamps for debug logs
 *
 * @return string
 */
function wfDebugTimer() {
	global $wgDebugTimestamps, $wgRequestTime;

	if ( !$wgDebugTimestamps ) {
		return '';
	}

	$prefix = sprintf( "%6.4f", microtime( true ) - $wgRequestTime );
	$mem = sprintf( "%5.1fM", ( memory_get_usage( true ) / ( 1024 * 1024 ) ) );
	return "$prefix $mem  ";
}

/**
 * Send a line giving PHP memory usage.
 *
 * @param bool $exact print exact values instead of kilobytes (default: false)
 */
function wfDebugMem( $exact = false ) {
	$mem = memory_get_usage();
	if ( !$exact ) {
		$mem = floor( $mem / 1024 ) . ' kilobytes';
	} else {
		$mem .= ' bytes';
	}
	wfDebug( "Memory usage: $mem\n" );
}

/**
 * Send a line to a supplementary debug log file, if configured, or main debug log if not.
 * To configure a supplementary log file, set $wgDebugLogGroups[$logGroup] to a string
 * filename or an associative array mapping 'destination' to the desired filename. The
 * associative array may also contain a 'sample' key with an integer value, specifying
 * a sampling factor.
 *
 * @since 1.23 support for sampling log messages via $wgDebugLogGroups.
 *
 * @param string $logGroup
 * @param string $text
 * @param string|bool $dest Destination of the message:
 *     - 'all': both to the log and HTML (debug toolbar or HTML comments)
 *     - 'log': only to the log and not in HTML
 *     - 'private': only to the specifc log if set in $wgDebugLogGroups and
 *       discarded otherwise
 *   For backward compatibility, it can also take a boolean:
 *     - true: same as 'all'
 *     - false: same as 'private'
 */
function wfDebugLog( $logGroup, $text, $dest = 'all' ) {
	global $wgDebugLogGroups;

	$text = trim( $text ) . "\n";

	// Turn $dest into a string if it's a boolean (for b/c)
	if ( $dest === true ) {
		$dest = 'all';
	} elseif ( $dest === false ) {
		$dest = 'private';
	}

	if ( !isset( $wgDebugLogGroups[$logGroup] ) ) {
		if ( $dest !== 'private' ) {
			wfDebug( "[$logGroup] $text", $dest );
		}
		return;
	}

	if ( $dest === 'all' ) {
		MWDebug::debugMsg( "[$logGroup] $text" );
	}

	$logConfig = $wgDebugLogGroups[$logGroup];
	if ( $logConfig === false ) {
		return;
	}
	if ( is_array( $logConfig ) ) {
		if ( isset( $logConfig['sample'] ) && mt_rand( 1, $logConfig['sample'] ) !== 1 ) {
			return;
		}
		$destination = $logConfig['destination'];
	} else {
		$destination = strval( $logConfig );
	}

	$time = wfTimestamp( TS_DB );
	$wiki = wfWikiID();
	$host = wfHostname();
	wfErrorLog( "$time $host $wiki: $text", $destination );
}

/**
 * Log for database errors
 *
 * @param string $text database error message.
 */
function wfLogDBError( $text ) {
	global $wgDBerrorLog, $wgDBerrorLogTZ;
	static $logDBErrorTimeZoneObject = null;

	if ( $wgDBerrorLog ) {
		$host = wfHostname();
		$wiki = wfWikiID();

		if ( $wgDBerrorLogTZ && !$logDBErrorTimeZoneObject ) {
			$logDBErrorTimeZoneObject = new DateTimeZone( $wgDBerrorLogTZ );
		}

		// Workaround for https://bugs.php.net/bug.php?id=52063
		// Can be removed when min PHP > 5.3.2
		if ( $logDBErrorTimeZoneObject === null ) {
			$d = date_create( "now" );
		} else {
			$d = date_create( "now", $logDBErrorTimeZoneObject );
		}

		$date = $d->format( 'D M j G:i:s T Y' );

		$text = "$date\t$host\t$wiki\t" . trim( $text ) . "\n";
		wfErrorLog( $text, $wgDBerrorLog );
	}
}

/**
 * Throws a warning that $function is deprecated
 *
 * @param $function String
 * @param string|bool $version Version of MediaWiki that the function
 *    was deprecated in (Added in 1.19).
 * @param string|bool $component Added in 1.19.
 * @param $callerOffset integer: How far up the call stack is the original
 *    caller. 2 = function that called the function that called
 *    wfDeprecated (Added in 1.20)
 *
 * @return null
 */
function wfDeprecated( $function, $version = false, $component = false, $callerOffset = 2 ) {
	MWDebug::deprecated( $function, $version, $component, $callerOffset + 1 );
}

/**
 * Send a warning either to the debug log or in a PHP error depending on
 * $wgDevelopmentWarnings. To log warnings in production, use wfLogWarning() instead.
 *
 * @param string $msg message to send
 * @param $callerOffset Integer: number of items to go back in the backtrace to
 *        find the correct caller (1 = function calling wfWarn, ...)
 * @param $level Integer: PHP error level; defaults to E_USER_NOTICE;
 *        only used when $wgDevelopmentWarnings is true
 */
function wfWarn( $msg, $callerOffset = 1, $level = E_USER_NOTICE ) {
	MWDebug::warning( $msg, $callerOffset + 1, $level, 'auto' );
}

/**
 * Send a warning as a PHP error and the debug log. This is intended for logging
 * warnings in production. For logging development warnings, use WfWarn instead.
 *
 * @param $msg String: message to send
 * @param $callerOffset Integer: number of items to go back in the backtrace to
 *        find the correct caller (1 = function calling wfLogWarning, ...)
 * @param $level Integer: PHP error level; defaults to E_USER_WARNING
 */
function wfLogWarning( $msg, $callerOffset = 1, $level = E_USER_WARNING ) {
	MWDebug::warning( $msg, $callerOffset + 1, $level, 'production' );
}

/**
 * Log to a file without getting "file size exceeded" signals.
 *
 * Can also log to TCP or UDP with the syntax udp://host:port/prefix. This will
 * send lines to the specified port, prefixed by the specified prefix and a space.
 *
 * @param $text String
 * @param string $file filename
 * @throws MWException
 */
function wfErrorLog( $text, $file ) {
	if ( substr( $file, 0, 4 ) == 'udp:' ) {
		# Needs the sockets extension
		if ( preg_match( '!^(tcp|udp):(?://)?\[([0-9a-fA-F:]+)\]:(\d+)(?:/(.*))?$!', $file, $m ) ) {
			// IPv6 bracketed host
			$host = $m[2];
			$port = intval( $m[3] );
			$prefix = isset( $m[4] ) ? $m[4] : false;
			$domain = AF_INET6;
		} elseif ( preg_match( '!^(tcp|udp):(?://)?([a-zA-Z0-9.-]+):(\d+)(?:/(.*))?$!', $file, $m ) ) {
			$host = $m[2];
			if ( !IP::isIPv4( $host ) ) {
				$host = gethostbyname( $host );
			}
			$port = intval( $m[3] );
			$prefix = isset( $m[4] ) ? $m[4] : false;
			$domain = AF_INET;
		} else {
			throw new MWException( __METHOD__ . ': Invalid UDP specification' );
		}

		// Clean it up for the multiplexer
		if ( strval( $prefix ) !== '' ) {
			$text = preg_replace( '/^/m', $prefix . ' ', $text );

			// Limit to 64KB
			if ( strlen( $text ) > 65506 ) {
				$text = substr( $text, 0, 65506 );
			}

			if ( substr( $text, -1 ) != "\n" ) {
				$text .= "\n";
			}
		} elseif ( strlen( $text ) > 65507 ) {
			$text = substr( $text, 0, 65507 );
		}

		$sock = socket_create( $domain, SOCK_DGRAM, SOL_UDP );
		if ( !$sock ) {
			return;
		}

		socket_sendto( $sock, $text, strlen( $text ), 0, $host, $port );
		socket_close( $sock );
	} else {
		wfSuppressWarnings();
		$exists = file_exists( $file );
		$size = $exists ? filesize( $file ) : false;
		if ( !$exists || ( $size !== false && $size + strlen( $text ) < 0x7fffffff ) ) {
			file_put_contents( $file, $text, FILE_APPEND );
		}
		wfRestoreWarnings();
	}
}

/**
 * @todo document
 */
function wfLogProfilingData() {
	global $wgRequestTime, $wgDebugLogFile, $wgDebugLogGroups, $wgDebugRawPage;
	global $wgProfileLimit, $wgUser, $wgRequest;

	StatCounter::singleton()->flush();

	$profiler = Profiler::instance();

	# Profiling must actually be enabled...
	if ( $profiler->isStub() ) {
		return;
	}

	// Get total page request time and only show pages that longer than
	// $wgProfileLimit time (default is 0)
	$elapsed = microtime( true ) - $wgRequestTime;
	if ( $elapsed <= $wgProfileLimit ) {
		return;
	}

	$profiler->logData();

	// Check whether this should be logged in the debug file.
	if ( isset( $wgDebugLogGroups['profileoutput'] )
		&& $wgDebugLogGroups['profileoutput'] === false
	) {
		// Explicitely disabled
		return;
	}
	if ( !isset( $wgDebugLogGroups['profileoutput'] ) && $wgDebugLogFile == '' ) {
		// Logging not enabled; no point going further
		return;
	}
	if ( !$wgDebugRawPage && wfIsDebugRawPage() ) {
		return;
	}

	$forward = '';
	if ( !empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
		$forward = ' forwarded for ' . $_SERVER['HTTP_X_FORWARDED_FOR'];
	}
	if ( !empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
		$forward .= ' client IP ' . $_SERVER['HTTP_CLIENT_IP'];
	}
	if ( !empty( $_SERVER['HTTP_FROM'] ) ) {
		$forward .= ' from ' . $_SERVER['HTTP_FROM'];
	}
	if ( $forward ) {
		$forward = "\t(proxied via {$_SERVER['REMOTE_ADDR']}{$forward})";
	}
	// Don't load $wgUser at this late stage just for statistics purposes
	// @todo FIXME: We can detect some anons even if it is not loaded. See User::getId()
	if ( $wgUser->isItemLoaded( 'id' ) && $wgUser->isAnon() ) {
		$forward .= ' anon';
	}

	// Command line script uses a FauxRequest object which does not have
	// any knowledge about an URL and throw an exception instead.
	try {
		$requestUrl = $wgRequest->getRequestURL();
	} catch ( MWException $e ) {
		$requestUrl = 'n/a';
	}

	$log = sprintf( "%s\t%04.3f\t%s\n",
		gmdate( 'YmdHis' ), $elapsed,
		urldecode( $requestUrl . $forward ) );

	wfDebugLog( 'profileoutput', $log . $profiler->getOutput() );
}

/**
 * Increment a statistics counter
 *
 * @param $key String
 * @param $count Int
 * @return void
 */
function wfIncrStats( $key, $count = 1 ) {
	StatCounter::singleton()->incr( $key, $count );
}

/**
 * Check whether the wiki is in read-only mode.
 *
 * @return bool
 */
function wfReadOnly() {
	return wfReadOnlyReason() !== false;
}

/**
 * Get the value of $wgReadOnly or the contents of $wgReadOnlyFile.
 *
 * @return string|bool: String when in read-only mode; false otherwise
 */
function wfReadOnlyReason() {
	global $wgReadOnly, $wgReadOnlyFile;

	if ( $wgReadOnly === null ) {
		// Set $wgReadOnly for faster access next time
		if ( is_file( $wgReadOnlyFile ) && filesize( $wgReadOnlyFile ) > 0 ) {
			$wgReadOnly = file_get_contents( $wgReadOnlyFile );
		} else {
			$wgReadOnly = false;
		}
	}

	return $wgReadOnly;
}

/**
 * Return a Language object from $langcode
 *
 * @param $langcode Mixed: either:
 *                  - a Language object
 *                  - code of the language to get the message for, if it is
 *                    a valid code create a language for that language, if
 *                    it is a string but not a valid code then make a basic
 *                    language object
 *                  - a boolean: if it's false then use the global object for
 *                    the current user's language (as a fallback for the old parameter
 *                    functionality), or if it is true then use global object
 *                    for the wiki's content language.
 * @return Language object
 */
function wfGetLangObj( $langcode = false ) {
	# Identify which language to get or create a language object for.
	# Using is_object here due to Stub objects.
	if ( is_object( $langcode ) ) {
		# Great, we already have the object (hopefully)!
		return $langcode;
	}

	global $wgContLang, $wgLanguageCode;
	if ( $langcode === true || $langcode === $wgLanguageCode ) {
		# $langcode is the language code of the wikis content language object.
		# or it is a boolean and value is true
		return $wgContLang;
	}

	global $wgLang;
	if ( $langcode === false || $langcode === $wgLang->getCode() ) {
		# $langcode is the language code of user language object.
		# or it was a boolean and value is false
		return $wgLang;
	}

	$validCodes = array_keys( Language::fetchLanguageNames() );
	if ( in_array( $langcode, $validCodes ) ) {
		# $langcode corresponds to a valid language.
		return Language::factory( $langcode );
	}

	# $langcode is a string, but not a valid language code; use content language.
	wfDebug( "Invalid language code passed to wfGetLangObj, falling back to content language.\n" );
	return $wgContLang;
}

/**
 * This is the function for getting translated interface messages.
 *
 * @see Message class for documentation how to use them.
 * @see https://www.mediawiki.org/wiki/Manual:Messages_API
 *
 * This function replaces all old wfMsg* functions.
 *
 * @param string $key Message key
 * @param mixed [$params,...] Normal message parameters
 * @return Message
 *
 * @since 1.17
 *
 * @see Message::__construct
 */
function wfMessage( $key /*...*/ ) {
	$params = func_get_args();
	array_shift( $params );
	if ( isset( $params[0] ) && is_array( $params[0] ) ) {
		$params = $params[0];
	}
	return new Message( $key, $params );
}

/**
 * This function accepts multiple message keys and returns a message instance
 * for the first message which is non-empty. If all messages are empty then an
 * instance of the first message key is returned.
 *
 * @param string|string[] [$keys,...] Message keys
 * @return Message
 *
 * @since 1.18
 *
 * @see Message::newFallbackSequence
 */
function wfMessageFallback( /*...*/ ) {
	$args = func_get_args();
	return call_user_func_array( 'Message::newFallbackSequence', $args );
}

/**
 * Get a message from anywhere, for the current user language.
 *
 * Use wfMsgForContent() instead if the message should NOT
 * change depending on the user preferences.
 *
 * @deprecated since 1.18
 *
 * @param string $key lookup key for the message, usually
 *    defined in languages/Language.php
 *
 * Parameters to the message, which can be used to insert variable text into
 * it, can be passed to this function in the following formats:
 * - One per argument, starting at the second parameter
 * - As an array in the second parameter
 * These are not shown in the function definition.
 *
 * @return String
 */
function wfMsg( $key ) {
	wfDeprecated( __METHOD__, '1.21' );

	$args = func_get_args();
	array_shift( $args );
	return wfMsgReal( $key, $args );
}

/**
 * Same as above except doesn't transform the message
 *
 * @deprecated since 1.18
 *
 * @param $key String
 * @return String
 */
function wfMsgNoTrans( $key ) {
	wfDeprecated( __METHOD__, '1.21' );

	$args = func_get_args();
	array_shift( $args );
	return wfMsgReal( $key, $args, true, false, false );
}

/**
 * Get a message from anywhere, for the current global language
 * set with $wgLanguageCode.
 *
 * Use this if the message should NOT change dependent on the
 * language set in the user's preferences. This is the case for
 * most text written into logs, as well as link targets (such as
 * the name of the copyright policy page). Link titles, on the
 * other hand, should be shown in the UI language.
 *
 * Note that MediaWiki allows users to change the user interface
 * language in their preferences, but a single installation
 * typically only contains content in one language.
 *
 * Be wary of this distinction: If you use wfMsg() where you should
 * use wfMsgForContent(), a user of the software may have to
 * customize potentially hundreds of messages in
 * order to, e.g., fix a link in every possible language.
 *
 * @deprecated since 1.18
 *
 * @param string $key lookup key for the message, usually
 *     defined in languages/Language.php
 * @return String
 */
function wfMsgForContent( $key ) {
	wfDeprecated( __METHOD__, '1.21' );

	global $wgForceUIMsgAsContentMsg;
	$args = func_get_args();
	array_shift( $args );
	$forcontent = true;
	if ( is_array( $wgForceUIMsgAsContentMsg )
		&& in_array( $key, $wgForceUIMsgAsContentMsg )
	) {
		$forcontent = false;
	}
	return wfMsgReal( $key, $args, true, $forcontent );
}

/**
 * Same as above except doesn't transform the message
 *
 * @deprecated since 1.18
 *
 * @param $key String
 * @return String
 */
function wfMsgForContentNoTrans( $key ) {
	wfDeprecated( __METHOD__, '1.21' );

	global $wgForceUIMsgAsContentMsg;
	$args = func_get_args();
	array_shift( $args );
	$forcontent = true;
	if ( is_array( $wgForceUIMsgAsContentMsg )
		&& in_array( $key, $wgForceUIMsgAsContentMsg )
	) {
		$forcontent = false;
	}
	return wfMsgReal( $key, $args, true, $forcontent, false );
}

/**
 * Really get a message
 *
 * @deprecated since 1.18
 *
 * @param string $key key to get.
 * @param array $args
 * @param bool $useDB
 * @param string|bool $forContent Language code, or false for user lang, true for content lang.
 * @param bool $transform Whether or not to transform the message.
 * @return string The requested message.
 */
function wfMsgReal( $key, $args, $useDB = true, $forContent = false, $transform = true ) {
	wfDeprecated( __METHOD__, '1.21' );

	wfProfileIn( __METHOD__ );
	$message = wfMsgGetKey( $key, $useDB, $forContent, $transform );
	$message = wfMsgReplaceArgs( $message, $args );
	wfProfileOut( __METHOD__ );
	return $message;
}

/**
 * Fetch a message string value, but don't replace any keys yet.
 *
 * @deprecated since 1.18
 *
 * @param string $key
 * @param bool $useDB
 * @param string|bool $langCode Code of the language to get the message for, or
 *                  behaves as a content language switch if it is a boolean.
 * @param bool $transform Whether to parse magic words, etc.
 * @return string
 */
function wfMsgGetKey( $key, $useDB = true, $langCode = false, $transform = true ) {
	wfDeprecated( __METHOD__, '1.21' );

	wfRunHooks( 'NormalizeMessageKey', array( &$key, &$useDB, &$langCode, &$transform ) );

	$cache = MessageCache::singleton();
	$message = $cache->get( $key, $useDB, $langCode );
	if ( $message === false ) {
		$message = '&lt;' . htmlspecialchars( $key ) . '&gt;';
	} elseif ( $transform ) {
		$message = $cache->transform( $message );
	}
	return $message;
}

/**
 * Replace message parameter keys on the given formatted output.
 *
 * @param string $message
 * @param array $args
 * @return string
 * @private
 */
function wfMsgReplaceArgs( $message, $args ) {
	# Fix windows line-endings
	# Some messages are split with explode("\n", $msg)
	$message = str_replace( "\r", '', $message );

	// Replace arguments
	if ( count( $args ) ) {
		if ( is_array( $args[0] ) ) {
			$args = array_values( $args[0] );
		}
		$replacementKeys = array();
		foreach ( $args as $n => $param ) {
			$replacementKeys['$' . ( $n + 1 )] = $param;
		}
		$message = strtr( $message, $replacementKeys );
	}

	return $message;
}

/**
 * Return an HTML-escaped version of a message.
 * Parameter replacements, if any, are done *after* the HTML-escaping,
 * so parameters may contain HTML (eg links or form controls). Be sure
 * to pre-escape them if you really do want plaintext, or just wrap
 * the whole thing in htmlspecialchars().
 *
 * @deprecated since 1.18
 *
 * @param string $key
 * @param string [$args,...] Parameters
 * @return string
 */
function wfMsgHtml( $key ) {
	wfDeprecated( __METHOD__, '1.21' );

	$args = func_get_args();
	array_shift( $args );
	return wfMsgReplaceArgs( htmlspecialchars( wfMsgGetKey( $key ) ), $args );
}

/**
 * Return an HTML version of message
 * Parameter replacements, if any, are done *after* parsing the wiki-text message,
 * so parameters may contain HTML (eg links or form controls). Be sure
 * to pre-escape them if you really do want plaintext, or just wrap
 * the whole thing in htmlspecialchars().
 *
 * @deprecated since 1.18
 *
 * @param string $key
 * @param string [$args,...] Parameters
 * @return string
 */
function wfMsgWikiHtml( $key ) {
	wfDeprecated( __METHOD__, '1.21' );

	$args = func_get_args();
	array_shift( $args );
	return wfMsgReplaceArgs(
		MessageCache::singleton()->parse( wfMsgGetKey( $key ), null,
		/* can't be set to false */ true, /* interface */ true )->getText(),
		$args );
}

/**
 * Returns message in the requested format
 *
 * @deprecated since 1.18
 *
 * @param string $key key of the message
 * @param array $options processing rules.
 *   Can take the following options:
 *     parse: parses wikitext to HTML
 *     parseinline: parses wikitext to HTML and removes the surrounding
 *       p's added by parser or tidy
 *     escape: filters message through htmlspecialchars
 *     escapenoentities: same, but allows entity references like &#160; through
 *     replaceafter: parameters are substituted after parsing or escaping
 *     parsemag: transform the message using magic phrases
 *     content: fetch message for content language instead of interface
 *   Also can accept a single associative argument, of the form 'language' => 'xx':
 *     language: Language object or language code to fetch message for
 *       (overridden by content).
 * Behavior for conflicting options (e.g., parse+parseinline) is undefined.
 *
 * @return String
 */
function wfMsgExt( $key, $options ) {
	wfDeprecated( __METHOD__, '1.21' );

	$args = func_get_args();
	array_shift( $args );
	array_shift( $args );
	$options = (array)$options;

	foreach ( $options as $arrayKey => $option ) {
		if ( !preg_match( '/^[0-9]+|language$/', $arrayKey ) ) {
			# An unknown index, neither numeric nor "language"
			wfWarn( "wfMsgExt called with incorrect parameter key $arrayKey", 1, E_USER_WARNING );
		} elseif ( preg_match( '/^[0-9]+$/', $arrayKey ) && !in_array( $option,
		array( 'parse', 'parseinline', 'escape', 'escapenoentities',
		'replaceafter', 'parsemag', 'content' ) ) ) {
			# A numeric index with unknown value
			wfWarn( "wfMsgExt called with incorrect parameter $option", 1, E_USER_WARNING );
		}
	}

	if ( in_array( 'content', $options, true ) ) {
		$forContent = true;
		$langCode = true;
		$langCodeObj = null;
	} elseif ( array_key_exists( 'language', $options ) ) {
		$forContent = false;
		$langCode = wfGetLangObj( $options['language'] );
		$langCodeObj = $langCode;
	} else {
		$forContent = false;
		$langCode = false;
		$langCodeObj = null;
	}

	$string = wfMsgGetKey( $key, /*DB*/true, $langCode, /*Transform*/false );

	if ( !in_array( 'replaceafter', $options, true ) ) {
		$string = wfMsgReplaceArgs( $string, $args );
	}

	$messageCache = MessageCache::singleton();
	$parseInline = in_array( 'parseinline', $options, true );
	if ( in_array( 'parse', $options, true ) || $parseInline ) {
		$string = $messageCache->parse( $string, null, true, !$forContent, $langCodeObj );
		if ( $string instanceof ParserOutput ) {
			$string = $string->getText();
		}

		if ( $parseInline ) {
			$m = array();
			if ( preg_match( '/^<p>(.*)\n?<\/p>\n?$/sU', $string, $m ) ) {
				$string = $m[1];
			}
		}
	} elseif ( in_array( 'parsemag', $options, true ) ) {
		$string = $messageCache->transform( $string,
				!$forContent, $langCodeObj );
	}

	if ( in_array( 'escape', $options, true ) ) {
		$string = htmlspecialchars ( $string );
	} elseif ( in_array( 'escapenoentities', $options, true ) ) {
		$string = Sanitizer::escapeHtmlAllowEntities( $string );
	}

	if ( in_array( 'replaceafter', $options, true ) ) {
		$string = wfMsgReplaceArgs( $string, $args );
	}

	return $string;
}

/**
 * Since wfMsg() and co suck, they don't return false if the message key they
 * looked up didn't exist but instead the key wrapped in <>'s, this function checks for the
 * nonexistence of messages by checking the MessageCache::get() result directly.
 *
 * @deprecated since 1.18. Use Message::isDisabled().
 *
 * @param string $key The message key looked up
 * @return bool True if the message *doesn't* exist.
 */
function wfEmptyMsg( $key ) {
	wfDeprecated( __METHOD__, '1.21' );

	return MessageCache::singleton()->get( $key, /*useDB*/true, /*content*/false ) === false;
}

/**
 * Throw a debugging exception. This function previously once exited the process,
 * but now throws an exception instead, with similar results.
 *
 * @deprecated since 1.22; just throw an MWException yourself
 * @param string $msg message shown when dying.
 * @throws MWException
 */
function wfDebugDieBacktrace( $msg = '' ) {
	wfDeprecated( __FUNCTION__, '1.22' );
	throw new MWException( $msg );
}

/**
 * Fetch server name for use in error reporting etc.
 * Use real server name if available, so we know which machine
 * in a server farm generated the current page.
 *
 * @return string
 */
function wfHostname() {
	static $host;
	if ( is_null( $host ) ) {

		# Hostname overriding
		global $wgOverrideHostname;
		if ( $wgOverrideHostname !== false ) {
			# Set static and skip any detection
			$host = $wgOverrideHostname;
			return $host;
		}

		if ( function_exists( 'posix_uname' ) ) {
			// This function not present on Windows
			$uname = posix_uname();
		} else {
			$uname = false;
		}
		if ( is_array( $uname ) && isset( $uname['nodename'] ) ) {
			$host = $uname['nodename'];
		} elseif ( getenv( 'COMPUTERNAME' ) ) {
			# Windows computer name
			$host = getenv( 'COMPUTERNAME' );
		} else {
			# This may be a virtual server.
			$host = $_SERVER['SERVER_NAME'];
		}
	}
	return $host;
}

/**
 * Returns a script tag that stores the amount of time it took MediaWiki to
 * handle the request in milliseconds as 'wgBackendResponseTime'.
 *
 * If $wgShowHostnames is true, the script will also set 'wgHostname' to the
 * hostname of the server handling the request.
 *
 * @return string
 */
function wfReportTime() {
	global $wgRequestTime, $wgShowHostnames;

	$responseTime = round( ( microtime( true ) - $wgRequestTime ) * 1000 );
	$reportVars = array( 'wgBackendResponseTime' => $responseTime );
	if ( $wgShowHostnames ) {
		$reportVars[ 'wgHostname' ] = wfHostname();
	}
	return Skin::makeVariablesScript( $reportVars );
}

/**
 * Safety wrapper for debug_backtrace().
 *
 * With Zend Optimizer 3.2.0 loaded, this causes segfaults under somewhat
 * murky circumstances, which may be triggered in part by stub objects
 * or other fancy talking'.
 *
 * Will return an empty array if Zend Optimizer is detected or if
 * debug_backtrace is disabled, otherwise the output from
 * debug_backtrace() (trimmed).
 *
 * @param int $limit This parameter can be used to limit the number of stack frames returned
 *
 * @return array of backtrace information
 */
function wfDebugBacktrace( $limit = 0 ) {
	static $disabled = null;

	if ( extension_loaded( 'Zend Optimizer' ) ) {
		wfDebug( "Zend Optimizer detected; skipping debug_backtrace for safety.\n" );
		return array();
	}

	if ( is_null( $disabled ) ) {
		$disabled = false;
		$functions = explode( ',', ini_get( 'disable_functions' ) );
		$functions = array_map( 'trim', $functions );
		$functions = array_map( 'strtolower', $functions );
		if ( in_array( 'debug_backtrace', $functions ) ) {
			wfDebug( "debug_backtrace is in disabled_functions\n" );
			$disabled = true;
		}
	}
	if ( $disabled ) {
		return array();
	}

	if ( $limit && version_compare( PHP_VERSION, '5.4.0', '>=' ) ) {
		return array_slice( debug_backtrace( DEBUG_BACKTRACE_PROVIDE_OBJECT, $limit + 1 ), 1 );
	} else {
		return array_slice( debug_backtrace(), 1 );
	}
}

/**
 * Get a debug backtrace as a string
 *
 * @return string
 */
function wfBacktrace() {
	global $wgCommandLineMode;

	if ( $wgCommandLineMode ) {
		$msg = '';
	} else {
		$msg = "<ul>\n";
	}
	$backtrace = wfDebugBacktrace();
	foreach ( $backtrace as $call ) {
		if ( isset( $call['file'] ) ) {
			$f = explode( DIRECTORY_SEPARATOR, $call['file'] );
			$file = $f[count( $f ) - 1];
		} else {
			$file = '-';
		}
		if ( isset( $call['line'] ) ) {
			$line = $call['line'];
		} else {
			$line = '-';
		}
		if ( $wgCommandLineMode ) {
			$msg .= "$file line $line calls ";
		} else {
			$msg .= '<li>' . $file . ' line ' . $line . ' calls ';
		}
		if ( !empty( $call['class'] ) ) {
			$msg .= $call['class'] . $call['type'];
		}
		$msg .= $call['function'] . '()';

		if ( $wgCommandLineMode ) {
			$msg .= "\n";
		} else {
			$msg .= "</li>\n";
		}
	}
	if ( $wgCommandLineMode ) {
		$msg .= "\n";
	} else {
		$msg .= "</ul>\n";
	}

	return $msg;
}

/**
 * Get the name of the function which called this function
 * wfGetCaller( 1 ) is the function with the wfGetCaller() call (ie. __FUNCTION__)
 * wfGetCaller( 2 ) [default] is the caller of the function running wfGetCaller()
 * wfGetCaller( 3 ) is the parent of that.
 *
 * @param $level Int
 * @return string
 */
function wfGetCaller( $level = 2 ) {
	$backtrace = wfDebugBacktrace( $level + 1 );
	if ( isset( $backtrace[$level] ) ) {
		return wfFormatStackFrame( $backtrace[$level] );
	} else {
		return 'unknown';
	}
}

/**
 * Return a string consisting of callers in the stack. Useful sometimes
 * for profiling specific points.
 *
 * @param int $limit The maximum depth of the stack frame to return, or false for
 *               the entire stack.
 * @return String
 */
function wfGetAllCallers( $limit = 3 ) {
	$trace = array_reverse( wfDebugBacktrace() );
	if ( !$limit || $limit > count( $trace ) - 1 ) {
		$limit = count( $trace ) - 1;
	}
	$trace = array_slice( $trace, -$limit - 1, $limit );
	return implode( '/', array_map( 'wfFormatStackFrame', $trace ) );
}

/**
 * Return a string representation of frame
 *
 * @param $frame Array
 * @return string
 */
function wfFormatStackFrame( $frame ) {
	return isset( $frame['class'] ) ?
		$frame['class'] . '::' . $frame['function'] :
		$frame['function'];
}

/* Some generic result counters, pulled out of SearchEngine */

/**
 * @todo document
 *
 * @param int $offset
 * @param int $limit
 * @return string
 */
function wfShowingResults( $offset, $limit ) {
	return wfMessage( 'showingresults' )->numParams( $limit, $offset + 1 )->parse();
}

/**
 * Generate (prev x| next x) (20|50|100...) type links for paging
 *
 * @param string $offset
 * @param int $limit
 * @param string $link
 * @param string $query optional URL query parameter string
 * @param bool $atend optional param for specified if this is the last page
 * @return string
 * @deprecated in 1.19; use Language::viewPrevNext() instead
 */
function wfViewPrevNext( $offset, $limit, $link, $query = '', $atend = false ) {
	wfDeprecated( __METHOD__, '1.19' );

	global $wgLang;

	$query = wfCgiToArray( $query );

	if ( is_object( $link ) ) {
		$title = $link;
	} else {
		$title = Title::newFromText( $link );
		if ( is_null( $title ) ) {
			return false;
		}
	}

	return $wgLang->viewPrevNext( $title, $offset, $limit, $query, $atend );
}

/**
 * @todo document
 * @todo FIXME: We may want to blacklist some broken browsers
 *
 * @param bool $force
 * @return bool Whereas client accept gzip compression
 */
function wfClientAcceptsGzip( $force = false ) {
	static $result = null;
	if ( $result === null || $force ) {
		$result = false;
		if ( isset( $_SERVER['HTTP_ACCEPT_ENCODING'] ) ) {
			# @todo FIXME: We may want to blacklist some broken browsers
			$m = array();
			if ( preg_match(
					'/\bgzip(?:;(q)=([0-9]+(?:\.[0-9]+)))?\b/',
					$_SERVER['HTTP_ACCEPT_ENCODING'],
					$m
				)
			) {
				if ( isset( $m[2] ) && ( $m[1] == 'q' ) && ( $m[2] == 0 ) ) {
					$result = false;
					return $result;
				}
				wfDebug( "wfClientAcceptsGzip: client accepts gzip.\n" );
				$result = true;
			}
		}
	}
	return $result;
}

/**
 * Obtain the offset and limit values from the request string;
 * used in special pages
 *
 * @param int $deflimit default limit if none supplied
 * @param string $optionname Name of a user preference to check against
 * @return array
 *
 */
function wfCheckLimits( $deflimit = 50, $optionname = 'rclimit' ) {
	global $wgRequest;
	return $wgRequest->getLimitOffset( $deflimit, $optionname );
}

/**
 * Escapes the given text so that it may be output using addWikiText()
 * without any linking, formatting, etc. making its way through. This
 * is achieved by substituting certain characters with HTML entities.
 * As required by the callers, "<nowiki>" is not used.
 *
 * @param string $text text to be escaped
 * @return String
 */
function wfEscapeWikiText( $text ) {
	static $repl = null, $repl2 = null;
	if ( $repl === null ) {
		$repl = array(
			'"' => '&#34;', '&' => '&#38;', "'" => '&#39;', '<' => '&#60;',
			'=' => '&#61;', '>' => '&#62;', '[' => '&#91;', ']' => '&#93;',
			'{' => '&#123;', '|' => '&#124;', '}' => '&#125;', ';' => '&#59;',
			"\n#" => "\n&#35;", "\r#" => "\r&#35;",
			"\n*" => "\n&#42;", "\r*" => "\r&#42;",
			"\n:" => "\n&#58;", "\r:" => "\r&#58;",
			"\n " => "\n&#32;", "\r " => "\r&#32;",
			"\n\n" => "\n&#10;", "\r\n" => "&#13;\n",
			"\n\r" => "\n&#13;", "\r\r" => "\r&#13;",
			"\n\t" => "\n&#9;", "\r\t" => "\r&#9;", // "\n\t\n" is treated like "\n\n"
			"\n----" => "\n&#45;---", "\r----" => "\r&#45;---",
			'__' => '_&#95;', '://' => '&#58;//',
		);

		// We have to catch everything "\s" matches in PCRE
		foreach ( array( 'ISBN', 'RFC', 'PMID' ) as $magic ) {
			$repl["$magic "] = "$magic&#32;";
			$repl["$magic\t"] = "$magic&#9;";
			$repl["$magic\r"] = "$magic&#13;";
			$repl["$magic\n"] = "$magic&#10;";
			$repl["$magic\f"] = "$magic&#12;";
		}

		// And handle protocols that don't use "://"
		global $wgUrlProtocols;
		$repl2 = array();
		foreach ( $wgUrlProtocols as $prot ) {
			if ( substr( $prot, -1 ) === ':' ) {
				$repl2[] = preg_quote( substr( $prot, 0, -1 ), '/' );
			}
		}
		$repl2 = $repl2 ? '/\b(' . join( '|', $repl2 ) . '):/i' : '/^(?!)/';
	}
	$text = substr( strtr( "\n$text", $repl ), 1 );
	$text = preg_replace( $repl2, '$1&#58;', $text );
	return $text;
}

/**
 * Get the current unix timestamp with microseconds.  Useful for profiling
 * @deprecated since 1.22; call microtime() directly
 * @return Float
 */
function wfTime() {
	wfDeprecated( __FUNCTION__, '1.22' );
	return microtime( true );
}

/**
 * Sets dest to source and returns the original value of dest
 * If source is NULL, it just returns the value, it doesn't set the variable
 * If force is true, it will set the value even if source is NULL
 *
 * @param $dest Mixed
 * @param $source Mixed
 * @param $force Bool
 * @return Mixed
 */
function wfSetVar( &$dest, $source, $force = false ) {
	$temp = $dest;
	if ( !is_null( $source ) || $force ) {
		$dest = $source;
	}
	return $temp;
}

/**
 * As for wfSetVar except setting a bit
 *
 * @param $dest Int
 * @param $bit Int
 * @param $state Bool
 *
 * @return bool
 */
function wfSetBit( &$dest, $bit, $state = true ) {
	$temp = (bool)( $dest & $bit );
	if ( !is_null( $state ) ) {
		if ( $state ) {
			$dest |= $bit;
		} else {
			$dest &= ~$bit;
		}
	}
	return $temp;
}

/**
 * A wrapper around the PHP function var_export().
 * Either print it or add it to the regular output ($wgOut).
 *
 * @param $var mixed A PHP variable to dump.
 */
function wfVarDump( $var ) {
	global $wgOut;
	$s = str_replace( "\n", "<br />\n", var_export( $var, true ) . "\n" );
	if ( headers_sent() || !isset( $wgOut ) || !is_object( $wgOut ) ) {
		print $s;
	} else {
		$wgOut->addHTML( $s );
	}
}

/**
 * Provide a simple HTTP error.
 *
 * @param $code Int|String
 * @param $label String
 * @param $desc String
 */
function wfHttpError( $code, $label, $desc ) {
	global $wgOut;
	$wgOut->disable();
	header( "HTTP/1.0 $code $label" );
	header( "Status: $code $label" );
	$wgOut->sendCacheControl();

	header( 'Content-type: text/html; charset=utf-8' );
	print "<!doctype html>" .
		'<html><head><title>' .
		htmlspecialchars( $label ) .
		'</title></head><body><h1>' .
		htmlspecialchars( $label ) .
		'</h1><p>' .
		nl2br( htmlspecialchars( $desc ) ) .
		"</p></body></html>\n";
}

/**
 * Clear away any user-level output buffers, discarding contents.
 *
 * Suitable for 'starting afresh', for instance when streaming
 * relatively large amounts of data without buffering, or wanting to
 * output image files without ob_gzhandler's compression.
 *
 * The optional $resetGzipEncoding parameter controls suppression of
 * the Content-Encoding header sent by ob_gzhandler; by default it
 * is left. See comments for wfClearOutputBuffers() for why it would
 * be used.
 *
 * Note that some PHP configuration options may add output buffer
 * layers which cannot be removed; these are left in place.
 *
 * @param $resetGzipEncoding Bool
 */
function wfResetOutputBuffers( $resetGzipEncoding = true ) {
	if ( $resetGzipEncoding ) {
		// Suppress Content-Encoding and Content-Length
		// headers from 1.10+s wfOutputHandler
		global $wgDisableOutputCompression;
		$wgDisableOutputCompression = true;
	}
	while ( $status = ob_get_status() ) {
		if ( $status['type'] == 0 /* PHP_OUTPUT_HANDLER_INTERNAL */ ) {
			// Probably from zlib.output_compression or other
			// PHP-internal setting which can't be removed.
			//
			// Give up, and hope the result doesn't break
			// output behavior.
			break;
		}
		if ( !ob_end_clean() ) {
			// Could not remove output buffer handler; abort now
			// to avoid getting in some kind of infinite loop.
			break;
		}
		if ( $resetGzipEncoding ) {
			if ( $status['name'] == 'ob_gzhandler' ) {
				// Reset the 'Content-Encoding' field set by this handler
				// so we can start fresh.
				header_remove( 'Content-Encoding' );
				break;
			}
		}
	}
}

/**
 * More legible than passing a 'false' parameter to wfResetOutputBuffers():
 *
 * Clear away output buffers, but keep the Content-Encoding header
 * produced by ob_gzhandler, if any.
 *
 * This should be used for HTTP 304 responses, where you need to
 * preserve the Content-Encoding header of the real result, but
 * also need to suppress the output of ob_gzhandler to keep to spec
 * and avoid breaking Firefox in rare cases where the headers and
 * body are broken over two packets.
 */
function wfClearOutputBuffers() {
	wfResetOutputBuffers( false );
}

/**
 * Converts an Accept-* header into an array mapping string values to quality
 * factors
 *
 * @param string $accept
 * @param string $def default
 * @return float[] Associative array of string => float pairs
 */
function wfAcceptToPrefs( $accept, $def = '*/*' ) {
	# No arg means accept anything (per HTTP spec)
	if ( !$accept ) {
		return array( $def => 1.0 );
	}

	$prefs = array();

	$parts = explode( ',', $accept );

	foreach ( $parts as $part ) {
		# @todo FIXME: Doesn't deal with params like 'text/html; level=1'
		$values = explode( ';', trim( $part ) );
		$match = array();
		if ( count( $values ) == 1 ) {
			$prefs[$values[0]] = 1.0;
		} elseif ( preg_match( '/q\s*=\s*(\d*\.\d+)/', $values[1], $match ) ) {
			$prefs[$values[0]] = floatval( $match[1] );
		}
	}

	return $prefs;
}

/**
 * Checks if a given MIME type matches any of the keys in the given
 * array. Basic wildcards are accepted in the array keys.
 *
 * Returns the matching MIME type (or wildcard) if a match, otherwise
 * NULL if no match.
 *
 * @param string $type
 * @param array $avail
 * @return string
 * @private
 */
function mimeTypeMatch( $type, $avail ) {
	if ( array_key_exists( $type, $avail ) ) {
		return $type;
	} else {
		$parts = explode( '/', $type );
		if ( array_key_exists( $parts[0] . '/*', $avail ) ) {
			return $parts[0] . '/*';
		} elseif ( array_key_exists( '*/*', $avail ) ) {
			return '*/*';
		} else {
			return null;
		}
	}
}

/**
 * Returns the 'best' match between a client's requested internet media types
 * and the server's list of available types. Each list should be an associative
 * array of type to preference (preference is a float between 0.0 and 1.0).
 * Wildcards in the types are acceptable.
 *
 * @param array $cprefs client's acceptable type list
 * @param array $sprefs server's offered types
 * @return string
 *
 * @todo FIXME: Doesn't handle params like 'text/plain; charset=UTF-8'
 * XXX: generalize to negotiate other stuff
 */
function wfNegotiateType( $cprefs, $sprefs ) {
	$combine = array();

	foreach ( array_keys( $sprefs ) as $type ) {
		$parts = explode( '/', $type );
		if ( $parts[1] != '*' ) {
			$ckey = mimeTypeMatch( $type, $cprefs );
			if ( $ckey ) {
				$combine[$type] = $sprefs[$type] * $cprefs[$ckey];
			}
		}
	}

	foreach ( array_keys( $cprefs ) as $type ) {
		$parts = explode( '/', $type );
		if ( $parts[1] != '*' && !array_key_exists( $type, $sprefs ) ) {
			$skey = mimeTypeMatch( $type, $sprefs );
			if ( $skey ) {
				$combine[$type] = $sprefs[$skey] * $cprefs[$type];
			}
		}
	}

	$bestq = 0;
	$besttype = null;

	foreach ( array_keys( $combine ) as $type ) {
		if ( $combine[$type] > $bestq ) {
			$besttype = $type;
			$bestq = $combine[$type];
		}
	}

	return $besttype;
}

/**
 * Reference-counted warning suppression
 *
 * @param $end Bool
 */
function wfSuppressWarnings( $end = false ) {
	static $suppressCount = 0;
	static $originalLevel = false;

	if ( $end ) {
		if ( $suppressCount ) {
			--$suppressCount;
			if ( !$suppressCount ) {
				error_reporting( $originalLevel );
			}
		}
	} else {
		if ( !$suppressCount ) {
			$originalLevel = error_reporting( E_ALL & ~(
				E_WARNING |
				E_NOTICE |
				E_USER_WARNING |
				E_USER_NOTICE |
				E_DEPRECATED |
				E_USER_DEPRECATED |
				E_STRICT
			) );
		}
		++$suppressCount;
	}
}

/**
 * Restore error level to previous value
 */
function wfRestoreWarnings() {
	wfSuppressWarnings( true );
}

# Autodetect, convert and provide timestamps of various types

/**
 * Unix time - the number of seconds since 1970-01-01 00:00:00 UTC
 */
define( 'TS_UNIX', 0 );

/**
 * MediaWiki concatenated string timestamp (YYYYMMDDHHMMSS)
 */
define( 'TS_MW', 1 );

/**
 * MySQL DATETIME (YYYY-MM-DD HH:MM:SS)
 */
define( 'TS_DB', 2 );

/**
 * RFC 2822 format, for E-mail and HTTP headers
 */
define( 'TS_RFC2822', 3 );

/**
 * ISO 8601 format with no timezone: 1986-02-09T20:00:00Z
 *
 * This is used by Special:Export
 */
define( 'TS_ISO_8601', 4 );

/**
 * An Exif timestamp (YYYY:MM:DD HH:MM:SS)
 *
 * @see http://exif.org/Exif2-2.PDF The Exif 2.2 spec, see page 28 for the
 *       DateTime tag and page 36 for the DateTimeOriginal and
 *       DateTimeDigitized tags.
 */
define( 'TS_EXIF', 5 );

/**
 * Oracle format time.
 */
define( 'TS_ORACLE', 6 );

/**
 * Postgres format time.
 */
define( 'TS_POSTGRES', 7 );

/**
 * ISO 8601 basic format with no timezone: 19860209T200000Z.  This is used by ResourceLoader
 */
define( 'TS_ISO_8601_BASIC', 9 );

/**
 * Get a timestamp string in one of various formats
 *
 * @param $outputtype Mixed: A timestamp in one of the supported formats, the
 *                    function will autodetect which format is supplied and act
 *                    accordingly.
 * @param $ts Mixed: optional timestamp to convert, default 0 for the current time
 * @return Mixed: String / false The same date in the format specified in $outputtype or false
 */
function wfTimestamp( $outputtype = TS_UNIX, $ts = 0 ) {
	try {
		$timestamp = new MWTimestamp( $ts );
		return $timestamp->getTimestamp( $outputtype );
	} catch ( TimestampException $e ) {
		wfDebug( "wfTimestamp() fed bogus time value: TYPE=$outputtype; VALUE=$ts\n" );
		return false;
	}
}

/**
 * Return a formatted timestamp, or null if input is null.
 * For dealing with nullable timestamp columns in the database.
 *
 * @param int $outputtype
 * @param string $ts
 * @return string
 */
function wfTimestampOrNull( $outputtype = TS_UNIX, $ts = null ) {
	if ( is_null( $ts ) ) {
		return null;
	} else {
		return wfTimestamp( $outputtype, $ts );
	}
}

/**
 * Convenience function; returns MediaWiki timestamp for the present time.
 *
 * @return string
 */
function wfTimestampNow() {
	# return NOW
	return wfTimestamp( TS_MW, time() );
}

/**
 * Check if the operating system is Windows
 *
 * @return bool True if it's Windows, false otherwise.
 */
function wfIsWindows() {
	static $isWindows = null;
	if ( $isWindows === null ) {
		$isWindows = substr( php_uname(), 0, 7 ) == 'Windows';
	}
	return $isWindows;
}

/**
 * Check if we are running under HHVM
 *
 * @return bool
 */
function wfIsHHVM() {
	return defined( 'HHVM_VERSION' );
}

/**
 * Swap two variables
 *
 * @param mixed $x
 * @param mixed $y
 */
function swap( &$x, &$y ) {
	$z = $x;
	$x = $y;
	$y = $z;
}

/**
 * Tries to get the system directory for temporary files. First
 * $wgTmpDirectory is checked, and then the TMPDIR, TMP, and TEMP
 * environment variables are then checked in sequence, and if none are
 * set try sys_get_temp_dir().
 *
 * NOTE: When possible, use instead the tmpfile() function to create
 * temporary files to avoid race conditions on file creation, etc.
 *
 * @return string
 */
function wfTempDir() {
	global $wgTmpDirectory;

	if ( $wgTmpDirectory !== false ) {
		return $wgTmpDirectory;
	}

	$tmpDir = array_map( "getenv", array( 'TMPDIR', 'TMP', 'TEMP' ) );

	foreach ( $tmpDir as $tmp ) {
		if ( $tmp && file_exists( $tmp ) && is_dir( $tmp ) && is_writable( $tmp ) ) {
			return $tmp;
		}
	}
	return sys_get_temp_dir();
}

/**
 * Make directory, and make all parent directories if they don't exist
 *
 * @param string $dir full path to directory to create
 * @param int $mode Chmod value to use, default is $wgDirectoryMode
 * @param string $caller optional caller param for debugging.
 * @throws MWException
 * @return bool
 */
function wfMkdirParents( $dir, $mode = null, $caller = null ) {
	global $wgDirectoryMode;

	if ( FileBackend::isStoragePath( $dir ) ) { // sanity
		throw new MWException( __FUNCTION__ . " given storage path '$dir'." );
	}

	if ( !is_null( $caller ) ) {
		wfDebug( "$caller: called wfMkdirParents($dir)\n" );
	}

	if ( strval( $dir ) === '' || ( file_exists( $dir ) && is_dir( $dir ) ) ) {
		return true;
	}

	$dir = str_replace( array( '\\', '/' ), DIRECTORY_SEPARATOR, $dir );

	if ( is_null( $mode ) ) {
		$mode = $wgDirectoryMode;
	}

	// Turn off the normal warning, we're doing our own below
	wfSuppressWarnings();
	$ok = mkdir( $dir, $mode, true ); // PHP5 <3
	wfRestoreWarnings();

	if ( !$ok ) {
		//directory may have been created on another request since we last checked
		if ( is_dir( $dir ) ) {
			return true;
		}

		// PHP doesn't report the path in its warning message, so add our own to aid in diagnosis.
		wfLogWarning( sprintf( "failed to mkdir \"%s\" mode 0%o", $dir, $mode ) );
	}
	return $ok;
}

/**
 * Remove a directory and all its content.
 * Does not hide error.
 */
function wfRecursiveRemoveDir( $dir ) {
	wfDebug( __FUNCTION__ . "( $dir )\n" );
	// taken from http://de3.php.net/manual/en/function.rmdir.php#98622
	if ( is_dir( $dir ) ) {
		$objects = scandir( $dir );
		foreach ( $objects as $object ) {
			if ( $object != "." && $object != ".." ) {
				if ( filetype( $dir . '/' . $object ) == "dir" ) {
					wfRecursiveRemoveDir( $dir . '/' . $object );
				} else {
					unlink( $dir . '/' . $object );
				}
			}
		}
		reset( $objects );
		rmdir( $dir );
	}
}

/**
 * @param number $nr The number to format
 * @param int $acc The number of digits after the decimal point, default 2
 * @param bool $round Whether or not to round the value, default true
 * @return string
 */
function wfPercent( $nr, $acc = 2, $round = true ) {
	$ret = sprintf( "%.${acc}f", $nr );
	return $round ? round( $ret, $acc ) . '%' : "$ret%";
}

/**
 * Safety wrapper around ini_get() for boolean settings.
 * The values returned from ini_get() are pre-normalized for settings
 * set via php.ini or php_flag/php_admin_flag... but *not*
 * for those set via php_value/php_admin_value.
 *
 * It's fairly common for people to use php_value instead of php_flag,
 * which can leave you with an 'off' setting giving a false positive
 * for code that just takes the ini_get() return value as a boolean.
 *
 * To make things extra interesting, setting via php_value accepts
 * "true" and "yes" as true, but php.ini and php_flag consider them false. :)
 * Unrecognized values go false... again opposite PHP's own coercion
 * from string to bool.
 *
 * Luckily, 'properly' set settings will always come back as '0' or '1',
 * so we only have to worry about them and the 'improper' settings.
 *
 * I frickin' hate PHP... :P
 *
 * @param string $setting
 * @return bool
 */
function wfIniGetBool( $setting ) {
	$val = strtolower( ini_get( $setting ) );
	// 'on' and 'true' can't have whitespace around them, but '1' can.
	return $val == 'on'
		|| $val == 'true'
		|| $val == 'yes'
		|| preg_match( "/^\s*[+-]?0*[1-9]/", $val ); // approx C atoi() function
}

/**
 * Windows-compatible version of escapeshellarg()
 * Windows doesn't recognise single-quotes in the shell, but the escapeshellarg()
 * function puts single quotes in regardless of OS.
 *
 * Also fixes the locale problems on Linux in PHP 5.2.6+ (bug backported to
 * earlier distro releases of PHP)
 *
 * @param string [$args,...]
 * @return string
 */
function wfEscapeShellArg( /*...*/ ) {
	wfInitShellLocale();

	$args = func_get_args();
	$first = true;
	$retVal = '';
	foreach ( $args as $arg ) {
		if ( !$first ) {
			$retVal .= ' ';
		} else {
			$first = false;
		}

		if ( wfIsWindows() ) {
			// Escaping for an MSVC-style command line parser and CMD.EXE
			// @codingStandardsIgnoreStart For long URLs
			// Refs:
			//  * http://web.archive.org/web/20020708081031/http://mailman.lyra.org/pipermail/scite-interest/2002-March/000436.html
			//  * http://technet.microsoft.com/en-us/library/cc723564.aspx
			//  * Bug #13518
			//  * CR r63214
			// Double the backslashes before any double quotes. Escape the double quotes.
			// @codingStandardsIgnoreEnd
			$tokens = preg_split( '/(\\\\*")/', $arg, -1, PREG_SPLIT_DELIM_CAPTURE );
			$arg = '';
			$iteration = 0;
			foreach ( $tokens as $token ) {
				if ( $iteration % 2 == 1 ) {
					// Delimiter, a double quote preceded by zero or more slashes
					$arg .= str_replace( '\\', '\\\\', substr( $token, 0, -1 ) ) . '\\"';
				} elseif ( $iteration % 4 == 2 ) {
					// ^ in $token will be outside quotes, need to be escaped
					$arg .= str_replace( '^', '^^', $token );
				} else { // $iteration % 4 == 0
					// ^ in $token will appear inside double quotes, so leave as is
					$arg .= $token;
				}
				$iteration++;
			}
			// Double the backslashes before the end of the string, because
			// we will soon add a quote
			$m = array();
			if ( preg_match( '/^(.*?)(\\\\+)$/', $arg, $m ) ) {
				$arg = $m[1] . str_replace( '\\', '\\\\', $m[2] );
			}

			// Add surrounding quotes
			$retVal .= '"' . $arg . '"';
		} else {
			$retVal .= escapeshellarg( $arg );
		}
	}
	return $retVal;
}

/**
 * Check if wfShellExec() is effectively disabled via php.ini config
 *
 * @return bool|string False or one of (safemode,disabled)
 * @since 1.22
 */
function wfShellExecDisabled() {
	static $disabled = null;
	if ( is_null( $disabled ) ) {
		$disabled = false;
		if ( wfIniGetBool( 'safe_mode' ) ) {
			wfDebug( "wfShellExec can't run in safe_mode, PHP's exec functions are too broken.\n" );
			$disabled = 'safemode';
		} else {
			$functions = explode( ',', ini_get( 'disable_functions' ) );
			$functions = array_map( 'trim', $functions );
			$functions = array_map( 'strtolower', $functions );
			if ( in_array( 'proc_open', $functions ) ) {
				wfDebug( "proc_open is in disabled_functions\n" );
				$disabled = 'disabled';
			}
		}
	}
	return $disabled;
}

/**
 * Execute a shell command, with time and memory limits mirrored from the PHP
 * configuration if supported.
 *
 * @param string $cmd Command line, properly escaped for shell.
 * @param &$retval null|Mixed optional, will receive the program's exit code.
 *                 (non-zero is usually failure). If there is an error from
 *                 read, select, or proc_open(), this will be set to -1.
 * @param array $environ optional environment variables which should be
 *                 added to the executed command environment.
 * @param array $limits optional array with limits(filesize, memory, time, walltime)
 *                 this overwrites the global wgMaxShell* limits.
 * @param array $options Array of options:
 *    - duplicateStderr: Set this to true to duplicate stderr to stdout,
 *      including errors from limit.sh
 *
 * @return string collected stdout as a string
 */
function wfShellExec( $cmd, &$retval = null, $environ = array(),
	$limits = array(), $options = array()
) {
	global $IP, $wgMaxShellMemory, $wgMaxShellFileSize, $wgMaxShellTime,
		$wgMaxShellWallClockTime, $wgShellCgroup;

	$disabled = wfShellExecDisabled();
	if ( $disabled ) {
		$retval = 1;
		return $disabled == 'safemode' ?
			'Unable to run external programs in safe mode.' :
			'Unable to run external programs, proc_open() is disabled.';
	}

	$includeStderr = isset( $options['duplicateStderr'] ) && $options['duplicateStderr'];

	wfInitShellLocale();

	$envcmd = '';
	foreach ( $environ as $k => $v ) {
		if ( wfIsWindows() ) {
			/* Surrounding a set in quotes (method used by wfEscapeShellArg) makes the quotes themselves
			 * appear in the environment variable, so we must use carat escaping as documented in
			 * http://technet.microsoft.com/en-us/library/cc723564.aspx
			 * Note however that the quote isn't listed there, but is needed, and the parentheses
			 * are listed there but doesn't appear to need it.
			 */
			$envcmd .= "set $k=" . preg_replace( '/([&|()<>^"])/', '^\\1', $v ) . '&& ';
		} else {
			/* Assume this is a POSIX shell, thus required to accept variable assignments before the command
			 * http://www.opengroup.org/onlinepubs/009695399/utilities/xcu_chap02.html#tag_02_09_01
			 */
			$envcmd .= "$k=" . escapeshellarg( $v ) . ' ';
		}
	}
	$cmd = $envcmd . $cmd;

	$useLogPipe = false;
	if ( php_uname( 's' ) == 'Linux' ) {
		$time = intval ( isset( $limits['time'] ) ? $limits['time'] : $wgMaxShellTime );
		if ( isset( $limits['walltime'] ) ) {
			$wallTime = intval( $limits['walltime'] );
		} elseif ( isset( $limits['time'] ) ) {
			$wallTime = $time;
		} else {
			$wallTime = intval( $wgMaxShellWallClockTime );
		}
		$mem = intval ( isset( $limits['memory'] ) ? $limits['memory'] : $wgMaxShellMemory );
		$filesize = intval ( isset( $limits['filesize'] ) ? $limits['filesize'] : $wgMaxShellFileSize );

		if ( $time > 0 || $mem > 0 || $filesize > 0 || $wallTime > 0 ) {
			$cmd = '/bin/bash ' . escapeshellarg( "$IP/includes/limit.sh" ) . ' ' .
				escapeshellarg( $cmd ) . ' ' .
				escapeshellarg(
					"MW_INCLUDE_STDERR=" . ( $includeStderr ? '1' : '' ) . ';' .
					"MW_CPU_LIMIT=$time; " .
					'MW_CGROUP=' . escapeshellarg( $wgShellCgroup ) . '; ' .
					"MW_MEM_LIMIT=$mem; " .
					"MW_FILE_SIZE_LIMIT=$filesize; " .
					"MW_WALL_CLOCK_LIMIT=$wallTime; " .
					"MW_USE_LOG_PIPE=yes"
				);
			$useLogPipe = true;
		} elseif ( $includeStderr ) {
			$cmd .= ' 2>&1';
		}
	} elseif ( $includeStderr ) {
		$cmd .= ' 2>&1';
	}
	wfDebug( "wfShellExec: $cmd\n" );

	// Don't try to execute commands that exceed Linux's MAX_ARG_STRLEN.
	// Other platforms may be more accomodating, but we don't want to be
	// accomodating, because very long commands probably include user
	// input. See T129506.
	if ( strlen( $cmd ) > SHELL_MAX_ARG_STRLEN ) {
		throw new Exception( __METHOD__ . '(): total length of $cmd must not exceed SHELL_MAX_ARG_STRLEN' );
	}

	$desc = array(
		0 => array( 'file', 'php://stdin', 'r' ),
		1 => array( 'pipe', 'w' ),
		2 => array( 'file', 'php://stderr', 'w' ) );
	if ( $useLogPipe ) {
		$desc[3] = array( 'pipe', 'w' );
	}
	$pipes = null;
	$proc = proc_open( $cmd, $desc, $pipes );
	if ( !$proc ) {
		wfDebugLog( 'exec', "proc_open() failed: $cmd" );
		$retval = -1;
		return '';
	}
	$outBuffer = $logBuffer = '';
	$emptyArray = array();
	$status = false;
	$logMsg = false;

	// According to the documentation, it is possible for stream_select()
	// to fail due to EINTR. I haven't managed to induce this in testing
	// despite sending various signals. If it did happen, the error
	// message would take the form:
	//
	// stream_select(): unable to select [4]: Interrupted system call (max_fd=5)
	//
	// where [4] is the value of the macro EINTR and "Interrupted system
	// call" is string which according to the Linux manual is "possibly"
	// localised according to LC_MESSAGES.
	$eintr = defined( 'SOCKET_EINTR' ) ? SOCKET_EINTR : 4;
	$eintrMessage = "stream_select(): unable to select [$eintr]";

	// Build a table mapping resource IDs to pipe FDs to work around a
	// PHP 5.3 issue in which stream_select() does not preserve array keys
	// <https://bugs.php.net/bug.php?id=53427>.
	$fds = array();
	foreach ( $pipes as $fd => $pipe ) {
		$fds[(int)$pipe] = $fd;
	}

	$running = true;
	$timeout = null;
	$numReadyPipes = 0;

	while ( $running === true || $numReadyPipes !== 0 ) {
		if ( $running ) {
			$status = proc_get_status( $proc );
			// If the process has terminated, switch to nonblocking selects
			// for getting any data still waiting to be read.
			if ( !$status['running'] ) {
				$running = false;
				$timeout = 0;
			}
		}

		$readyPipes = $pipes;

		// Clear last error
		@trigger_error( '' );
		$numReadyPipes = @stream_select( $readyPipes, $emptyArray, $emptyArray, $timeout );
		if ( $numReadyPipes === false ) {
			$error = error_get_last();
			if ( strncmp( $error['message'], $eintrMessage, strlen( $eintrMessage ) ) == 0 ) {
				continue;
			} else {
				trigger_error( $error['message'], E_USER_WARNING );
				$logMsg = $error['message'];
				break;
			}
		}
		foreach ( $readyPipes as $pipe ) {
			$block = fread( $pipe, 65536 );
			$fd = $fds[(int)$pipe];
			if ( $block === '' ) {
				// End of file
				fclose( $pipes[$fd] );
				unset( $pipes[$fd] );
				if ( !$pipes ) {
					break 2;
				}
			} elseif ( $block === false ) {
				// Read error
				$logMsg = "Error reading from pipe";
				break 2;
			} elseif ( $fd == 1 ) {
				// From stdout
				$outBuffer .= $block;
			} elseif ( $fd == 3 ) {
				// From log FD
				$logBuffer .= $block;
				if ( strpos( $block, "\n" ) !== false ) {
					$lines = explode( "\n", $logBuffer );
					$logBuffer = array_pop( $lines );
					foreach ( $lines as $line ) {
						wfDebugLog( 'exec', $line );
					}
				}
			}
		}
	}

	foreach ( $pipes as $pipe ) {
		fclose( $pipe );
	}

	// Use the status previously collected if possible, since proc_get_status()
	// just calls waitpid() which will not return anything useful the second time.
	if ( $running ) {
		$status = proc_get_status( $proc );
	}

	if ( $logMsg !== false ) {
		// Read/select error
		$retval = -1;
		proc_close( $proc );
	} elseif ( $status['signaled'] ) {
		$logMsg = "Exited with signal {$status['termsig']}";
		$retval = 128 + $status['termsig'];
		proc_close( $proc );
	} else {
		if ( $status['running'] ) {
			$retval = proc_close( $proc );
		} else {
			$retval = $status['exitcode'];
			proc_close( $proc );
		}
		if ( $retval == 127 ) {
			$logMsg = "Possibly missing executable file";
		} elseif ( $retval >= 129 && $retval <= 192 ) {
			$logMsg = "Probably exited with signal " . ( $retval - 128 );
		}
	}

	if ( $logMsg !== false ) {
		wfDebugLog( 'exec', "$logMsg: $cmd" );
	}

	return $outBuffer;
}

/**
 * Execute a shell command, returning both stdout and stderr. Convenience
 * function, as all the arguments to wfShellExec can become unwieldy.
 *
 * @note This also includes errors from limit.sh, e.g. if $wgMaxShellFileSize is exceeded.
 * @param string $cmd Command line, properly escaped for shell.
 * @param &$retval null|Mixed optional, will receive the program's exit code.
 *                 (non-zero is usually failure)
 * @param array $environ optional environment variables which should be
 *                 added to the executed command environment.
 * @param array $limits optional array with limits(filesize, memory, time, walltime)
 *                 this overwrites the global wgShellMax* limits.
 * @return string collected stdout and stderr as a string
 */
function wfShellExecWithStderr( $cmd, &$retval = null, $environ = array(), $limits = array() ) {
	return wfShellExec( $cmd, $retval, $environ, $limits, array( 'duplicateStderr' => true ) );
}

/**
 * Workaround for http://bugs.php.net/bug.php?id=45132
 * escapeshellarg() destroys non-ASCII characters if LANG is not a UTF-8 locale
 */
function wfInitShellLocale() {
	static $done = false;
	if ( $done ) {
		return;
	}
	$done = true;
	global $wgShellLocale;
	if ( !wfIniGetBool( 'safe_mode' ) ) {
		putenv( "LC_CTYPE=$wgShellLocale" );
		setlocale( LC_CTYPE, $wgShellLocale );
	}
}

/**
 * Alias to wfShellWikiCmd()
 *
 * @see wfShellWikiCmd()
 */
function wfShellMaintenanceCmd( $script, array $parameters = array(), array $options = array() ) {
	return wfShellWikiCmd( $script, $parameters, $options );
}

/**
 * Generate a shell-escaped command line string to run a MediaWiki cli script.
 * Note that $parameters should be a flat array and an option with an argument
 * should consist of two consecutive items in the array (do not use "--option value").
 *
 * @param string $script MediaWiki cli script path
 * @param array $parameters Arguments and options to the script
 * @param array $options Associative array of options:
 * 		'php': The path to the php executable
 * 		'wrapper': Path to a PHP wrapper to handle the maintenance script
 * @return string
 */
function wfShellWikiCmd( $script, array $parameters = array(), array $options = array() ) {
	global $wgPhpCli;
	// Give site config file a chance to run the script in a wrapper.
	// The caller may likely want to call wfBasename() on $script.
	wfRunHooks( 'wfShellWikiCmd', array( &$script, &$parameters, &$options ) );
	$cmd = isset( $options['php'] ) ? array( $options['php'] ) : array( $wgPhpCli );
	if ( isset( $options['wrapper'] ) ) {
		$cmd[] = $options['wrapper'];
	}
	$cmd[] = $script;
	// Escape each parameter for shell
	return implode( " ", array_map( 'wfEscapeShellArg', array_merge( $cmd, $parameters ) ) );
}

/**
 * wfMerge attempts to merge differences between three texts.
 * Returns true for a clean merge and false for failure or a conflict.
 *
 * @param string $old
 * @param string $mine
 * @param string $yours
 * @param string $result
 * @return bool
 */
function wfMerge( $old, $mine, $yours, &$result ) {
	global $wgDiff3;

	# This check may also protect against code injection in
	# case of broken installations.
	wfSuppressWarnings();
	$haveDiff3 = $wgDiff3 && file_exists( $wgDiff3 );
	wfRestoreWarnings();

	if ( !$haveDiff3 ) {
		wfDebug( "diff3 not found\n" );
		return false;
	}

	# Make temporary files
	$td = wfTempDir();
	$oldtextFile = fopen( $oldtextName = tempnam( $td, 'merge-old-' ), 'w' );
	$mytextFile = fopen( $mytextName = tempnam( $td, 'merge-mine-' ), 'w' );
	$yourtextFile = fopen( $yourtextName = tempnam( $td, 'merge-your-' ), 'w' );

	# NOTE: diff3 issues a warning to stderr if any of the files does not end with
	#       a newline character. To avoid this, we normalize the trailing whitespace before
	#       creating the diff.

	fwrite( $oldtextFile, rtrim( $old ) . "\n" );
	fclose( $oldtextFile );
	fwrite( $mytextFile, rtrim( $mine ) . "\n" );
	fclose( $mytextFile );
	fwrite( $yourtextFile, rtrim( $yours ) . "\n" );
	fclose( $yourtextFile );

	# Check for a conflict
	$cmd = wfEscapeShellArg( $wgDiff3 ) . ' -a --overlap-only ' .
		wfEscapeShellArg( $mytextName ) . ' ' .
		wfEscapeShellArg( $oldtextName ) . ' ' .
		wfEscapeShellArg( $yourtextName );
	$handle = popen( $cmd, 'r' );

	if ( fgets( $handle, 1024 ) ) {
		$conflict = true;
	} else {
		$conflict = false;
	}
	pclose( $handle );

	# Merge differences
	$cmd = wfEscapeShellArg( $wgDiff3 ) . ' -a -e --merge ' .
		wfEscapeShellArg( $mytextName, $oldtextName, $yourtextName );
	$handle = popen( $cmd, 'r' );
	$result = '';
	do {
		$data = fread( $handle, 8192 );
		if ( strlen( $data ) == 0 ) {
			break;
		}
		$result .= $data;
	} while ( true );
	pclose( $handle );
	unlink( $mytextName );
	unlink( $oldtextName );
	unlink( $yourtextName );

	if ( $result === '' && $old !== '' && !$conflict ) {
		wfDebug( "Unexpected null result from diff3. Command: $cmd\n" );
		$conflict = true;
	}
	return !$conflict;
}

/**
 * Returns unified plain-text diff of two texts.
 * Useful for machine processing of diffs.
 *
 * @param string $before the text before the changes.
 * @param string $after the text after the changes.
 * @param string $params command-line options for the diff command.
 * @return string Unified diff of $before and $after
 */
function wfDiff( $before, $after, $params = '-u' ) {
	if ( $before == $after ) {
		return '';
	}

	global $wgDiff;
	wfSuppressWarnings();
	$haveDiff = $wgDiff && file_exists( $wgDiff );
	wfRestoreWarnings();

	# This check may also protect against code injection in
	# case of broken installations.
	if ( !$haveDiff ) {
		wfDebug( "diff executable not found\n" );
		$diffs = new Diff( explode( "\n", $before ), explode( "\n", $after ) );
		$format = new UnifiedDiffFormatter();
		return $format->format( $diffs );
	}

	# Make temporary files
	$td = wfTempDir();
	$oldtextFile = fopen( $oldtextName = tempnam( $td, 'merge-old-' ), 'w' );
	$newtextFile = fopen( $newtextName = tempnam( $td, 'merge-your-' ), 'w' );

	fwrite( $oldtextFile, $before );
	fclose( $oldtextFile );
	fwrite( $newtextFile, $after );
	fclose( $newtextFile );

	// Get the diff of the two files
	$cmd = "$wgDiff " . $params . ' ' . wfEscapeShellArg( $oldtextName, $newtextName );

	$h = popen( $cmd, 'r' );

	$diff = '';

	do {
		$data = fread( $h, 8192 );
		if ( strlen( $data ) == 0 ) {
			break;
		}
		$diff .= $data;
	} while ( true );

	// Clean up
	pclose( $h );
	unlink( $oldtextName );
	unlink( $newtextName );

	// Kill the --- and +++ lines. They're not useful.
	$diff_lines = explode( "\n", $diff );
	if ( strpos( $diff_lines[0], '---' ) === 0 ) {
		unset( $diff_lines[0] );
	}
	if ( strpos( $diff_lines[1], '+++' ) === 0 ) {
		unset( $diff_lines[1] );
	}

	$diff = implode( "\n", $diff_lines );

	return $diff;
}

/**
 * This function works like "use VERSION" in Perl, the program will die with a
 * backtrace if the current version of PHP is less than the version provided
 *
 * This is useful for extensions which due to their nature are not kept in sync
 * with releases, and might depend on other versions of PHP than the main code
 *
 * Note: PHP might die due to parsing errors in some cases before it ever
 *       manages to call this function, such is life
 *
 * @see perldoc -f use
 *
 * @param string|number $req_ver The version to check, can be a string, an integer, or
 *                 a float
 * @throws MWException
 */
function wfUsePHP( $req_ver ) {
	$php_ver = PHP_VERSION;

	if ( version_compare( $php_ver, (string)$req_ver, '<' ) ) {
		throw new MWException( "PHP $req_ver required--this is only $php_ver" );
	}
}

/**
 * This function works like "use VERSION" in Perl except it checks the version
 * of MediaWiki, the program will die with a backtrace if the current version
 * of MediaWiki is less than the version provided.
 *
 * This is useful for extensions which due to their nature are not kept in sync
 * with releases
 *
 * Note: Due to the behavior of PHP's version_compare() which is used in this
 * function, if you want to allow the 'wmf' development versions add a 'c' (or
 * any single letter other than 'a', 'b' or 'p') as a post-fix to your
 * targeted version number. For example if you wanted to allow any variation
 * of 1.22 use `wfUseMW( '1.22c' )`. Using an 'a' or 'b' instead of 'c' will
 * not result in the same comparison due to the internal logic of
 * version_compare().
 *
 * @see perldoc -f use
 *
 * @param string|number $req_ver The version to check, can be a string, an integer, or
 *                 a float
 * @throws MWException
 */
function wfUseMW( $req_ver ) {
	global $wgVersion;

	if ( version_compare( $wgVersion, (string)$req_ver, '<' ) ) {
		throw new MWException( "MediaWiki $req_ver required--this is only $wgVersion" );
	}
}

/**
 * Return the final portion of a pathname.
 * Reimplemented because PHP5's "basename()" is buggy with multibyte text.
 * http://bugs.php.net/bug.php?id=33898
 *
 * PHP's basename() only considers '\' a pathchar on Windows and Netware.
 * We'll consider it so always, as we don't want '\s' in our Unix paths either.
 *
 * @param string $path
 * @param string $suffix to remove if present
 * @return string
 */
function wfBaseName( $path, $suffix = '' ) {
	if ( $suffix == '' ) {
		$encSuffix = '';
	} else {
		$encSuffix = '(?:' . preg_quote( $suffix, '#' ) . ')?';
	}

	$matches = array();
	if ( preg_match( "#([^/\\\\]*?){$encSuffix}[/\\\\]*$#", $path, $matches ) ) {
		return $matches[1];
	} else {
		return '';
	}
}

/**
 * Generate a relative path name to the given file.
 * May explode on non-matching case-insensitive paths,
 * funky symlinks, etc.
 *
 * @param string $path absolute destination path including target filename
 * @param string $from Absolute source path, directory only
 * @return string
 */
function wfRelativePath( $path, $from ) {
	// Normalize mixed input on Windows...
	$path = str_replace( '/', DIRECTORY_SEPARATOR, $path );
	$from = str_replace( '/', DIRECTORY_SEPARATOR, $from );

	// Trim trailing slashes -- fix for drive root
	$path = rtrim( $path, DIRECTORY_SEPARATOR );
	$from = rtrim( $from, DIRECTORY_SEPARATOR );

	$pieces = explode( DIRECTORY_SEPARATOR, dirname( $path ) );
	$against = explode( DIRECTORY_SEPARATOR, $from );

	if ( $pieces[0] !== $against[0] ) {
		// Non-matching Windows drive letters?
		// Return a full path.
		return $path;
	}

	// Trim off common prefix
	while ( count( $pieces ) && count( $against )
		&& $pieces[0] == $against[0] ) {
		array_shift( $pieces );
		array_shift( $against );
	}

	// relative dots to bump us to the parent
	while ( count( $against ) ) {
		array_unshift( $pieces, '..' );
		array_shift( $against );
	}

	array_push( $pieces, wfBaseName( $path ) );

	return implode( DIRECTORY_SEPARATOR, $pieces );
}

/**
 * Convert an arbitrarily-long digit string from one numeric base
 * to another, optionally zero-padding to a minimum column width.
 *
 * Supports base 2 through 36; digit values 10-36 are represented
 * as lowercase letters a-z. Input is case-insensitive.
 *
 * @param string $input Input number
 * @param int $sourceBase Base of the input number
 * @param int $destBase Desired base of the output
 * @param int $pad Minimum number of digits in the output (pad with zeroes)
 * @param bool $lowercase Whether to output in lowercase or uppercase
 * @param string $engine Either "gmp", "bcmath", or "php"
 * @return string|bool The output number as a string, or false on error
 */
function wfBaseConvert( $input, $sourceBase, $destBase, $pad = 1,
	$lowercase = true, $engine = 'auto'
) {
	$input = (string)$input;
	if (
		$sourceBase < 2 ||
		$sourceBase > 36 ||
		$destBase < 2 ||
		$destBase > 36 ||
		$sourceBase != (int)$sourceBase ||
		$destBase != (int)$destBase ||
		$pad != (int)$pad ||
		!preg_match(
			"/^[" . substr( '0123456789abcdefghijklmnopqrstuvwxyz', 0, $sourceBase ) . "]+$/i",
			$input
		)
	) {
		return false;
	}

	static $baseChars = array(
		10 => 'a', 11 => 'b', 12 => 'c', 13 => 'd', 14 => 'e', 15 => 'f',
		16 => 'g', 17 => 'h', 18 => 'i', 19 => 'j', 20 => 'k', 21 => 'l',
		22 => 'm', 23 => 'n', 24 => 'o', 25 => 'p', 26 => 'q', 27 => 'r',
		28 => 's', 29 => 't', 30 => 'u', 31 => 'v', 32 => 'w', 33 => 'x',
		34 => 'y', 35 => 'z',

		'0' => 0, '1' => 1, '2' => 2, '3' => 3, '4' => 4, '5' => 5,
		'6' => 6, '7' => 7, '8' => 8, '9' => 9, 'a' => 10, 'b' => 11,
		'c' => 12, 'd' => 13, 'e' => 14, 'f' => 15, 'g' => 16, 'h' => 17,
		'i' => 18, 'j' => 19, 'k' => 20, 'l' => 21, 'm' => 22, 'n' => 23,
		'o' => 24, 'p' => 25, 'q' => 26, 'r' => 27, 's' => 28, 't' => 29,
		'u' => 30, 'v' => 31, 'w' => 32, 'x' => 33, 'y' => 34, 'z' => 35
	);

	if ( extension_loaded( 'gmp' ) && ( $engine == 'auto' || $engine == 'gmp' ) ) {
		$result = gmp_strval( gmp_init( $input, $sourceBase ), $destBase );
	} elseif ( extension_loaded( 'bcmath' ) && ( $engine == 'auto' || $engine == 'bcmath' ) ) {
		$decimal = '0';
		foreach ( str_split( strtolower( $input ) ) as $char ) {
			$decimal = bcmul( $decimal, $sourceBase );
			$decimal = bcadd( $decimal, $baseChars[$char] );
		}

		for ( $result = ''; bccomp( $decimal, 0 ); $decimal = bcdiv( $decimal, $destBase, 0 ) ) {
			$result .= $baseChars[bcmod( $decimal, $destBase )];
		}

		$result = strrev( $result );
	} else {
		$inDigits = array();
		foreach ( str_split( strtolower( $input ) ) as $char ) {
			$inDigits[] = $baseChars[$char];
		}

		// Iterate over the input, modulo-ing out an output digit
		// at a time until input is gone.
		$result = '';
		while ( $inDigits ) {
			$work = 0;
			$workDigits = array();

			// Long division...
			foreach ( $inDigits as $digit ) {
				$work *= $sourceBase;
				$work += $digit;

				if ( $workDigits || $work >= $destBase ) {
					$workDigits[] = (int)( $work / $destBase );
				}
				$work %= $destBase;
			}

			// All that division leaves us with a remainder,
			// which is conveniently our next output digit.
			$result .= $baseChars[$work];

			// And we continue!
			$inDigits = $workDigits;
		}

		$result = strrev( $result );
	}

	if ( !$lowercase ) {
		$result = strtoupper( $result );
	}

	return str_pad( $result, $pad, '0', STR_PAD_LEFT );
}

/**
 * Check if there is sufficient entropy in php's built-in session generation
 *
 * @return bool true = there is sufficient entropy
 */
function wfCheckEntropy() {
	return (
			( wfIsWindows() && version_compare( PHP_VERSION, '5.3.3', '>=' ) )
			|| ini_get( 'session.entropy_file' )
		)
		&& intval( ini_get( 'session.entropy_length' ) ) >= 32;
}

/**
 * Override session_id before session startup if php's built-in
 * session generation code is not secure.
 */
function wfFixSessionID() {
	// If the cookie or session id is already set we already have a session and should abort
	if ( isset( $_COOKIE[session_name()] ) || session_id() ) {
		return;
	}

	// PHP's built-in session entropy is enabled if:
	// - entropy_file is set or you're on Windows with php 5.3.3+
	// - AND entropy_length is > 0
	// We treat it as disabled if it doesn't have an entropy length of at least 32
	$entropyEnabled = wfCheckEntropy();

	// If built-in entropy is not enabled or not sufficient override PHP's
	// built in session id generation code
	if ( !$entropyEnabled ) {
		wfDebug( __METHOD__ . ": PHP's built in entropy is disabled or not sufficient, " .
			"overriding session id generation using our cryptrand source.\n" );
		session_id( MWCryptRand::generateHex( 32 ) );
	}
}

/**
 * Reset the session_id
 *
 * @since 1.22
 */
function wfResetSessionID() {
	global $wgCookieSecure;
	$oldSessionId = session_id();
	$cookieParams = session_get_cookie_params();
	if ( wfCheckEntropy() && $wgCookieSecure == $cookieParams['secure'] ) {
		session_regenerate_id( false );
	} else {
		$tmp = $_SESSION;
		session_destroy();
		wfSetupSession( MWCryptRand::generateHex( 32 ) );
		$_SESSION = $tmp;
	}
	$newSessionId = session_id();
	wfRunHooks( 'ResetSessionID', array( $oldSessionId, $newSessionId ) );
}

/**
 * Initialise php session
 *
 * @param bool $sessionId
 */
function wfSetupSession( $sessionId = false ) {
	global $wgSessionsInMemcached, $wgSessionsInObjectCache, $wgCookiePath, $wgCookieDomain,
			$wgCookieSecure, $wgCookieHttpOnly, $wgSessionHandler;
	if ( $wgSessionsInObjectCache || $wgSessionsInMemcached ) {
		ObjectCacheSessionHandler::install();
	} elseif ( $wgSessionHandler && $wgSessionHandler != ini_get( 'session.save_handler' ) ) {
		# Only set this if $wgSessionHandler isn't null and session.save_handler
		# hasn't already been set to the desired value (that causes errors)
		ini_set( 'session.save_handler', $wgSessionHandler );
	}
	session_set_cookie_params(
		0, $wgCookiePath, $wgCookieDomain, $wgCookieSecure, $wgCookieHttpOnly );
	session_cache_limiter( 'private, must-revalidate' );
	if ( $sessionId ) {
		session_id( $sessionId );
	} else {
		wfFixSessionID();
	}
	wfSuppressWarnings();
	session_start();
	wfRestoreWarnings();
}

/**
 * Get an object from the precompiled serialized directory
 *
 * @param string $name
 * @return mixed The variable on success, false on failure
 */
function wfGetPrecompiledData( $name ) {
	global $IP;

	$file = "$IP/serialized/$name";
	if ( file_exists( $file ) ) {
		$blob = file_get_contents( $file );
		if ( $blob ) {
			return unserialize( $blob );
		}
	}
	return false;
}

/**
 * Get a cache key
 *
 * @param string [$args,...]
 * @return string
 */
function wfMemcKey( /*...*/ ) {
	global $wgCachePrefix;
	$prefix = $wgCachePrefix === false ? wfWikiID() : $wgCachePrefix;
	$args = func_get_args();
	$key = $prefix . ':' . implode( ':', $args );
	$key = str_replace( ' ', '_', $key );
	return $key;
}

/**
 * Get a cache key for a foreign DB
 *
 * @param string $db
 * @param string $prefix
 * @param string [$args,...]
 * @return string
 */
function wfForeignMemcKey( $db, $prefix /*...*/ ) {
	$args = array_slice( func_get_args(), 2 );
	if ( $prefix ) {
		$key = "$db-$prefix:" . implode( ':', $args );
	} else {
		$key = $db . ':' . implode( ':', $args );
	}
	return str_replace( ' ', '_', $key );
}

/**
 * Get an ASCII string identifying this wiki
 * This is used as a prefix in memcached keys
 *
 * @return string
 */
function wfWikiID() {
	global $wgDBprefix, $wgDBname;
	if ( $wgDBprefix ) {
		return "$wgDBname-$wgDBprefix";
	} else {
		return $wgDBname;
	}
}

/**
 * Split a wiki ID into DB name and table prefix
 *
 * @param string $wiki
 *
 * @return array
 */
function wfSplitWikiID( $wiki ) {
	$bits = explode( '-', $wiki, 2 );
	if ( count( $bits ) < 2 ) {
		$bits[] = '';
	}
	return $bits;
}

/**
 * Get a Database object.
 *
 * @param int $db Index of the connection to get. May be DB_MASTER for the
 *            master (for write queries), DB_SLAVE for potentially lagged read
 *            queries, or an integer >= 0 for a particular server.
 *
 * @param string|string[] $groups Query groups. An array of group names that this query
 *                belongs to. May contain a single string if the query is only
 *                in one group.
 *
 * @param string|bool $wiki The wiki ID, or false for the current wiki
 *
 * Note: multiple calls to wfGetDB(DB_SLAVE) during the course of one request
 * will always return the same object, unless the underlying connection or load
 * balancer is manually destroyed.
 *
 * Note 2: use $this->getDB() in maintenance scripts that may be invoked by
 * updater to ensure that a proper database is being updated.
 *
 * @return DatabaseBase
 */
function &wfGetDB( $db, $groups = array(), $wiki = false ) {
	return wfGetLB( $wiki )->getConnection( $db, $groups, $wiki );
}

/**
 * Get a load balancer object.
 *
 * @param string|bool $wiki wiki ID, or false for the current wiki
 * @return LoadBalancer
 */
function wfGetLB( $wiki = false ) {
	return wfGetLBFactory()->getMainLB( $wiki );
}

/**
 * Get the load balancer factory object
 *
 * @return LBFactory
 */
function &wfGetLBFactory() {
	return LBFactory::singleton();
}

/**
 * Find a file.
 * Shortcut for RepoGroup::singleton()->findFile()
 *
 * @param string $title or Title object
 * @param array $options Associative array of options:
 *     time:           requested time for an archived image, or false for the
 *                     current version. An image object will be returned which was
 *                     created at the specified time.
 *
 *     ignoreRedirect: If true, do not follow file redirects
 *
 *     private:        If true, return restricted (deleted) files if the current
 *                     user is allowed to view them. Otherwise, such files will not
 *                     be found.
 *
 *     bypassCache:    If true, do not use the process-local cache of File objects
 *
 * @return File, or false if the file does not exist
 */
function wfFindFile( $title, $options = array() ) {
	return RepoGroup::singleton()->findFile( $title, $options );
}

/**
 * Get an object referring to a locally registered file.
 * Returns a valid placeholder object if the file does not exist.
 *
 * @param Title|string $title
 * @return LocalFile|null A File, or null if passed an invalid Title
 */
function wfLocalFile( $title ) {
	return RepoGroup::singleton()->getLocalRepo()->newFile( $title );
}

/**
 * Should low-performance queries be disabled?
 *
 * @return bool
 * @codeCoverageIgnore
 */
function wfQueriesMustScale() {
	global $wgMiserMode;
	return $wgMiserMode
		|| ( SiteStats::pages() > 100000
		&& SiteStats::edits() > 1000000
		&& SiteStats::users() > 10000 );
}

/**
 * Get the path to a specified script file, respecting file
 * extensions; this is a wrapper around $wgScriptExtension etc.
 * except for 'index' and 'load' which use $wgScript/$wgLoadScript
 *
 * @param string $script script filename, sans extension
 * @return string
 */
function wfScript( $script = 'index' ) {
	global $wgScriptPath, $wgScriptExtension, $wgScript, $wgLoadScript;
	if ( $script === 'index' ) {
		return $wgScript;
	} elseif ( $script === 'load' ) {
		return $wgLoadScript;
	} else {
		return "{$wgScriptPath}/{$script}{$wgScriptExtension}";
	}
}

/**
 * Get the script URL.
 *
 * @return string script URL
 */
function wfGetScriptUrl() {
	if ( isset( $_SERVER['SCRIPT_NAME'] ) ) {
		#
		# as it was called, minus the query string.
		#
		# Some sites use Apache rewrite rules to handle subdomains,
		# and have PHP set up in a weird way that causes PHP_SELF
		# to contain the rewritten URL instead of the one that the
		# outside world sees.
		#
		# If in this mode, use SCRIPT_URL instead, which mod_rewrite
		# provides containing the "before" URL.
		return $_SERVER['SCRIPT_NAME'];
	} else {
		return $_SERVER['URL'];
	}
}

/**
 * Convenience function converts boolean values into "true"
 * or "false" (string) values
 *
 * @param bool $value
 * @return string
 */
function wfBoolToStr( $value ) {
	return $value ? 'true' : 'false';
}

/**
 * Get a platform-independent path to the null file, e.g. /dev/null
 *
 * @return string
 */
function wfGetNull() {
	return wfIsWindows() ? 'NUL' : '/dev/null';
}

/**
 * Modern version of wfWaitForSlaves(). Instead of looking at replication lag
 * and waiting for it to go down, this waits for the slaves to catch up to the
 * master position. Use this when updating very large numbers of rows, as
 * in maintenance scripts, to avoid causing too much lag.  Of course, this is
 * a no-op if there are no slaves.
 *
 * @param int|bool $maxLag (deprecated)
 * @param string|bool $wiki Wiki identifier accepted by wfGetLB
 * @param string|bool $cluster Cluster name accepted by LBFactory. Default: false.
 */
function wfWaitForSlaves( $maxLag = false, $wiki = false, $cluster = false ) {
	if ( $cluster !== false ) {
		$lb = wfGetLBFactory()->getExternalLB( $cluster );
	} else {
		$lb = wfGetLB( $wiki );
	}

	// bug 27975 - Don't try to wait for slaves if there are none
	// Prevents permission error when getting master position
	if ( $lb->getServerCount() > 1 ) {
		$dbw = $lb->getConnection( DB_MASTER, array(), $wiki );
		$pos = $dbw->getMasterPos();
		// The DBMS may not support getMasterPos() or the whole
		// load balancer might be fake (e.g. $wgAllDBsAreLocalhost).
		if ( $pos !== false ) {
			$lb->waitForAll( $pos );
		}
	}
}

/**
 * Count down from $seconds to zero on the terminal, with a one-second pause
 * between showing each number. For use in command-line scripts.
 *
 * @codeCoverageIgnore
 * @param int $seconds
 */
function wfCountDown( $seconds ) {
	for ( $i = $seconds; $i >= 0; $i-- ) {
		if ( $i != $seconds ) {
			echo str_repeat( "\x08", strlen( $i + 1 ) );
		}
		echo $i;
		flush();
		if ( $i ) {
			sleep( 1 );
		}
	}
	echo "\n";
}

/**
 * Replace all invalid characters with -
 * Additional characters can be defined in $wgIllegalFileChars (see bug 20489)
 * By default, $wgIllegalFileChars = ':'
 *
 * @param string $name Filename to process
 * @return string
 */
function wfStripIllegalFilenameChars( $name ) {
	global $wgIllegalFileChars;
	$illegalFileChars = $wgIllegalFileChars ? "|[" . $wgIllegalFileChars . "]" : '';
	$name = wfBaseName( $name );
	$name = preg_replace(
		"/[^" . Title::legalChars() . "]" . $illegalFileChars . "/",
		'-',
		$name
	);
	return $name;
}

/**
 * Set PHP's memory limit to the larger of php.ini or $wgMemoryLimit;
 *
 * @return int Value the memory limit was set to.
 */
function wfMemoryLimit() {
	global $wgMemoryLimit;
	$memlimit = wfShorthandToInteger( ini_get( 'memory_limit' ) );
	if ( $memlimit != -1 ) {
		$conflimit = wfShorthandToInteger( $wgMemoryLimit );
		if ( $conflimit == -1 ) {
			wfDebug( "Removing PHP's memory limit\n" );
			wfSuppressWarnings();
			ini_set( 'memory_limit', $conflimit );
			wfRestoreWarnings();
			return $conflimit;
		} elseif ( $conflimit > $memlimit ) {
			wfDebug( "Raising PHP's memory limit to $conflimit bytes\n" );
			wfSuppressWarnings();
			ini_set( 'memory_limit', $conflimit );
			wfRestoreWarnings();
			return $conflimit;
		}
	}
	return $memlimit;
}

/**
 * Converts shorthand byte notation to integer form
 *
 * @param string $string
 * @param int $default Returned if $string is empty
 * @return int
 */
function wfShorthandToInteger( $string = '', $default = -1 ) {
	$string = trim( $string );
	if ( $string === '' ) {
		return $default;
	}
	$last = $string[strlen( $string ) - 1];
	$val = intval( $string );
	switch ( $last ) {
		case 'g':
		case 'G':
			$val *= 1024;
			// break intentionally missing
		case 'm':
		case 'M':
			$val *= 1024;
			// break intentionally missing
		case 'k':
		case 'K':
			$val *= 1024;
	}

	return $val;
}

/**
 * Get the normalised IETF language tag
 * See unit test for examples.
 *
 * @param string $code The language code.
 * @return string The language code which complying with BCP 47 standards.
 */
function wfBCP47( $code ) {
	$codeSegment = explode( '-', $code );
	$codeBCP = array();
	foreach ( $codeSegment as $segNo => $seg ) {
		// when previous segment is x, it is a private segment and should be lc
		if ( $segNo > 0 && strtolower( $codeSegment[( $segNo - 1 )] ) == 'x' ) {
			$codeBCP[$segNo] = strtolower( $seg );
		// ISO 3166 country code
		} elseif ( ( strlen( $seg ) == 2 ) && ( $segNo > 0 ) ) {
			$codeBCP[$segNo] = strtoupper( $seg );
		// ISO 15924 script code
		} elseif ( ( strlen( $seg ) == 4 ) && ( $segNo > 0 ) ) {
			$codeBCP[$segNo] = ucfirst( strtolower( $seg ) );
		// Use lowercase for other cases
		} else {
			$codeBCP[$segNo] = strtolower( $seg );
		}
	}
	$langCode = implode( '-', $codeBCP );
	return $langCode;
}

/**
 * Get a cache object.
 *
 * @param $inputType integer Cache type, one the the CACHE_* constants.
 * @return BagOStuff
 */
function wfGetCache( $inputType ) {
	return ObjectCache::getInstance( $inputType );
}

/**
 * Get the main cache object
 *
 * @return BagOStuff
 */
function wfGetMainCache() {
	global $wgMainCacheType;
	return ObjectCache::getInstance( $wgMainCacheType );
}

/**
 * Get the cache object used by the message cache
 *
 * @return BagOStuff
 */
function wfGetMessageCacheStorage() {
	global $wgMessageCacheType;
	return ObjectCache::getInstance( $wgMessageCacheType );
}

/**
 * Get the cache object used by the parser cache
 *
 * @return BagOStuff
 */
function wfGetParserCacheStorage() {
	global $wgParserCacheType;
	return ObjectCache::getInstance( $wgParserCacheType );
}

/**
 * Get the cache object used by the language converter
 *
 * @return BagOStuff
 */
function wfGetLangConverterCacheStorage() {
	global $wgLanguageConverterCacheType;
	return ObjectCache::getInstance( $wgLanguageConverterCacheType );
}

/**
 * Call hook functions defined in $wgHooks
 *
 * @param string $event event name
 * @param array $args parameters passed to hook functions
 * @param string|null $deprecatedVersion optionally mark hook as deprecated with version number
 *
 * @return bool True if no handler aborted the hook
 */
function wfRunHooks( $event, array $args = array(), $deprecatedVersion = null ) {
	return Hooks::run( $event, $args, $deprecatedVersion );
}

/**
 * Wrapper around php's unpack.
 *
 * @param string $format The format string (See php's docs)
 * @param string $data A binary string of binary data
 * @param int|bool $length The minimum length of $data or false. This is to
 *	prevent reading beyond the end of $data. false to disable the check.
 *
 * Also be careful when using this function to read unsigned 32 bit integer
 * because php might make it negative.
 *
 * @throws MWException if $data not long enough, or if unpack fails
 * @return array Associative array of the extracted data
 */
function wfUnpack( $format, $data, $length = false ) {
	if ( $length !== false ) {
		$realLen = strlen( $data );
		if ( $realLen < $length ) {
			throw new MWException( "Tried to use wfUnpack on a "
				. "string of length $realLen, but needed one "
				. "of at least length $length."
			);
		}
	}

	wfSuppressWarnings();
	$result = unpack( $format, $data );
	wfRestoreWarnings();

	if ( $result === false ) {
		// If it cannot extract the packed data.
		throw new MWException( "unpack could not unpack binary data" );
	}
	return $result;
}

/**
 * Determine if an image exists on the 'bad image list'.
 *
 * The format of MediaWiki:Bad_image_list is as follows:
 *    * Only list items (lines starting with "*") are considered
 *    * The first link on a line must be a link to a bad image
 *    * Any subsequent links on the same line are considered to be exceptions,
 *      i.e. articles where the image may occur inline.
 *
 * @param string $name the image name to check
 * @param Title|bool $contextTitle The page on which the image occurs, if known
 * @param string $blacklist wikitext of a file blacklist
 * @return bool
 */
function wfIsBadImage( $name, $contextTitle = false, $blacklist = null ) {
	static $badImageCache = null; // based on bad_image_list msg
	wfProfileIn( __METHOD__ );

	# Handle redirects
	$redirectTitle = RepoGroup::singleton()->checkRedirect( Title::makeTitle( NS_FILE, $name ) );
	if ( $redirectTitle ) {
		$name = $redirectTitle->getDBkey();
	}

	# Run the extension hook
	$bad = false;
	if ( !wfRunHooks( 'BadImage', array( $name, &$bad ) ) ) {
		wfProfileOut( __METHOD__ );
		return $bad;
	}

	$cacheable = ( $blacklist === null );
	if ( $cacheable && $badImageCache !== null ) {
		$badImages = $badImageCache;
	} else { // cache miss
		if ( $blacklist === null ) {
			$blacklist = wfMessage( 'bad_image_list' )->inContentLanguage()->plain(); // site list
		}
		# Build the list now
		$badImages = array();
		$lines = explode( "\n", $blacklist );
		foreach ( $lines as $line ) {
			# List items only
			if ( substr( $line, 0, 1 ) !== '*' ) {
				continue;
			}

			# Find all links
			$m = array();
			if ( !preg_match_all( '/\[\[:?(.*?)\]\]/', $line, $m ) ) {
				continue;
			}

			$exceptions = array();
			$imageDBkey = false;
			foreach ( $m[1] as $i => $titleText ) {
				$title = Title::newFromText( $titleText );
				if ( !is_null( $title ) ) {
					if ( $i == 0 ) {
						$imageDBkey = $title->getDBkey();
					} else {
						$exceptions[$title->getPrefixedDBkey()] = true;
					}
				}
			}

			if ( $imageDBkey !== false ) {
				$badImages[$imageDBkey] = $exceptions;
			}
		}
		if ( $cacheable ) {
			$badImageCache = $badImages;
		}
	}

	$contextKey = $contextTitle ? $contextTitle->getPrefixedDBkey() : false;
	$bad = isset( $badImages[$name] ) && !isset( $badImages[$name][$contextKey] );
	wfProfileOut( __METHOD__ );
	return $bad;
}

/**
 * Determine whether the client at a given source IP is likely to be able to
 * access the wiki via HTTPS.
 *
 * @param string $ip The IPv4/6 address in the normal human-readable form
 * @return boolean
 */
function wfCanIPUseHTTPS( $ip ) {
	$canDo = true;
	wfRunHooks( 'CanIPUseHTTPS', array( $ip, &$canDo ) );
	return !!$canDo;
}

/**
 * Work out the IP address based on various globals
 * For trusted proxies, use the XFF client IP (first of the chain)
 *
 * @deprecated in 1.19; call $wgRequest->getIP() directly.
 * @return string
 */
function wfGetIP() {
	wfDeprecated( __METHOD__, '1.19' );
	global $wgRequest;
	return $wgRequest->getIP();
}

/**
 * Checks if an IP is a trusted proxy provider.
 * Useful to tell if X-Forwarded-For data is possibly bogus.
 * Squid cache servers for the site are whitelisted.
 *
 * @param string $ip
 * @return bool
 */
function wfIsTrustedProxy( $ip ) {
	$trusted = wfIsConfiguredProxy( $ip );
	wfRunHooks( 'IsTrustedProxy', array( &$ip, &$trusted ) );
	return $trusted;
}

/**
 * Checks if an IP matches a proxy we've configured.
 *
 * @param string $ip
 * @return bool
 * @since 1.23 Supports CIDR ranges in $wgSquidServersNoPurge
 */
function wfIsConfiguredProxy( $ip ) {
	global $wgSquidServers, $wgSquidServersNoPurge;

	// quick check of known proxy servers
	$trusted = in_array( $ip, $wgSquidServers )
		|| in_array( $ip, $wgSquidServersNoPurge );

	if ( !$trusted ) {
		// slightly slower check to see if the ip is listed directly or in a CIDR
		// block in $wgSquidServersNoPurge
		foreach ( $wgSquidServersNoPurge as $block ) {
			if ( strpos( $block, '/' ) !== false && IP::isInRange( $ip, $block ) ) {
				$trusted = true;
				break;
			}
		}
	}
	return $trusted;
}
