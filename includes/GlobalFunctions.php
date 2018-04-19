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

use MediaWiki\Logger\LoggerFactory;
use MediaWiki\ProcOpenError;
use MediaWiki\Session\SessionManager;
use MediaWiki\MediaWikiServices;
use MediaWiki\Shell\Shell;
use Wikimedia\ScopedCallback;
use Wikimedia\Rdbms\DBReplicationWaitError;

/**
 * Load an extension
 *
 * This queues an extension to be loaded through
 * the ExtensionRegistry system.
 *
 * @param string $ext Name of the extension to load
 * @param string|null $path Absolute path of where to find the extension.json file
 * @since 1.25
 */
function wfLoadExtension( $ext, $path = null ) {
	if ( !$path ) {
		global $wgExtensionDirectory;
		$path = "$wgExtensionDirectory/$ext/extension.json";
	}
	ExtensionRegistry::getInstance()->queue( $path );
}

/**
 * Load multiple extensions at once
 *
 * Same as wfLoadExtension, but more efficient if you
 * are loading multiple extensions.
 *
 * If you want to specify custom paths, you should interact with
 * ExtensionRegistry directly.
 *
 * @see wfLoadExtension
 * @param string[] $exts Array of extension names to load
 * @since 1.25
 */
function wfLoadExtensions( array $exts ) {
	global $wgExtensionDirectory;
	$registry = ExtensionRegistry::getInstance();
	foreach ( $exts as $ext ) {
		$registry->queue( "$wgExtensionDirectory/$ext/extension.json" );
	}
}

/**
 * Load a skin
 *
 * @see wfLoadExtension
 * @param string $skin Name of the extension to load
 * @param string|null $path Absolute path of where to find the skin.json file
 * @since 1.25
 */
function wfLoadSkin( $skin, $path = null ) {
	if ( !$path ) {
		global $wgStyleDirectory;
		$path = "$wgStyleDirectory/$skin/skin.json";
	}
	ExtensionRegistry::getInstance()->queue( $path );
}

/**
 * Load multiple skins at once
 *
 * @see wfLoadExtensions
 * @param string[] $skins Array of extension names to load
 * @since 1.25
 */
function wfLoadSkins( array $skins ) {
	global $wgStyleDirectory;
	$registry = ExtensionRegistry::getInstance();
	foreach ( $skins as $skin ) {
		$registry->queue( "$wgStyleDirectory/$skin/skin.json" );
	}
}

/**
 * Like array_diff( $a, $b ) except that it works with two-dimensional arrays.
 * @param array $a
 * @param array $b
 * @return array
 */
function wfArrayDiff2( $a, $b ) {
	return array_udiff( $a, $b, 'wfArrayDiff2_cmp' );
}

/**
 * @param array|string $a
 * @param array|string $b
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
		while ( key( $a ) !== null && key( $b ) !== null ) {
			$valueA = current( $a );
			$valueB = current( $b );
			$cmp = strcmp( $valueA, $valueB );
			if ( $cmp !== 0 ) {
				return $cmp;
			}
			next( $a );
			next( $b );
		}
		return 0;
	}
}

/**
 * Like array_filter with ARRAY_FILTER_USE_BOTH, but works pre-5.6.
 *
 * @param array $arr
 * @param callable $callback Will be called with the array value and key (in that order) and
 *   should return a bool which will determine whether the array element is kept.
 * @return array
 */
function wfArrayFilter( array $arr, callable $callback ) {
	if ( defined( 'ARRAY_FILTER_USE_BOTH' ) ) {
		return array_filter( $arr, $callback, ARRAY_FILTER_USE_BOTH );
	}
	$filteredKeys = array_filter( array_keys( $arr ), function ( $key ) use ( $arr, $callback ) {
		return call_user_func( $callback, $arr[$key], $key );
	} );
	return array_intersect_key( $arr, array_fill_keys( $filteredKeys, true ) );
}

/**
 * Like array_filter with ARRAY_FILTER_USE_KEY, but works pre-5.6.
 *
 * @param array $arr
 * @param callable $callback Will be called with the array key and should return a bool which
 *   will determine whether the array element is kept.
 * @return array
 */
function wfArrayFilterByKey( array $arr, callable $callback ) {
	return wfArrayFilter( $arr, function ( $val, $key ) use ( $callback ) {
		return call_user_func( $callback, $key );
	} );
}

/**
 * Appends to second array if $value differs from that in $default
 *
 * @param string|int $key
 * @param mixed $value
 * @param mixed $default
 * @param array &$changed Array to alter
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
 * Merge arrays in the style of getUserPermissionsErrors, with duplicate removal
 * e.g.
 *     wfMergeErrorArrays(
 *       [ [ 'x' ] ],
 *       [ [ 'x', '2' ] ],
 *       [ [ 'x' ] ],
 *       [ [ 'y' ] ]
 *     );
 * returns:
 *     [
 *       [ 'x', '2' ],
 *       [ 'x' ],
 *       [ 'y' ]
 *     ]
 *
 * @param array $array1,...
 * @return array
 */
function wfMergeErrorArrays( /*...*/ ) {
	$args = func_get_args();
	$out = [];
	foreach ( $args as $errors ) {
		foreach ( $errors as $params ) {
			$originalParams = $params;
			if ( $params[0] instanceof MessageSpecifier ) {
				$msg = $params[0];
				$params = array_merge( [ $msg->getKey() ], $msg->getParams() );
			}
			# @todo FIXME: Sometimes get nested arrays for $params,
			# which leads to E_NOTICEs
			$spec = implode( "\t", $params );
			$out[$spec] = $originalParams;
		}
	}
	return array_values( $out );
}

/**
 * Insert array into another array after the specified *KEY*
 *
 * @param array $array The array.
 * @param array $insert The array to insert.
 * @param mixed $after The key to insert after
 * @return array
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
 * @param object|array $objOrArray
 * @param bool $recursive
 * @return array
 */
function wfObjectToArray( $objOrArray, $recursive = true ) {
	$array = [];
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
 * @note This is designed for use in relation to Special:RandomPage
 *       and the page_random database field.
 *
 * @return string
 */
function wfRandom() {
	// The maximum random value is "only" 2^31-1, so get two random
	// values to reduce the chance of dupes
	$max = mt_getrandmax() + 1;
	$rand = number_format( ( mt_rand() * $max + mt_rand() ) / $max / $max, 12, '.', '' );
	return $rand;
}

/**
 * Get a random string containing a number of pseudo-random hex characters.
 *
 * @note This is not secure, if you are trying to generate some sort
 *       of token please use MWCryptRand instead.
 *
 * @param int $length The length of the string to generate
 * @return string
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
 * RFC 1738 says ~ is unsafe, however RFC 3986 considers it an unreserved
 * character which should not be encoded. More importantly, google chrome
 * always converts %7E back to ~, and converting it in this function can
 * cause a redirect loop (T105265).
 *
 * But + is not safe because it's used to indicate a space; &= are only safe in
 * paths and not in queries (and we don't distinguish here); ' seems kind of
 * scary; and urlencode() doesn't touch -_. to begin with.  Plus, although /
 * is reserved, we don't care.  So the list we unescape is:
 *
 * ;:@$!*(),/~
 *
 * However, IIS7 redirects fail when the url contains a colon (see T24709),
 * so no fancy : for IIS7.
 *
 * %2F in the page titles seems to fatally break for some reason.
 *
 * @param string $s
 * @return string
 */
function wfUrlencode( $s ) {
	static $needle;

	if ( is_null( $s ) ) {
		$needle = null;
		return '';
	}

	if ( is_null( $needle ) ) {
		$needle = [ '%3B', '%40', '%24', '%21', '%2A', '%28', '%29', '%2C', '%2F', '%7E' ];
		if ( !isset( $_SERVER['SERVER_SOFTWARE'] ) ||
			( strpos( $_SERVER['SERVER_SOFTWARE'], 'Microsoft-IIS/7' ) === false )
		) {
			$needle[] = '%3A';
		}
	}

	$s = urlencode( $s );
	$s = str_ireplace(
		$needle,
		[ ';', '@', '$', '!', '*', '(', ')', ',', '/', '~', ':' ],
		$s
	);

	return $s;
}

/**
 * This function takes one or two arrays as input, and returns a CGI-style string, e.g.
 * "days=7&limit=100". Options in the first array override options in the second.
 * Options set to null or false will not be output.
 *
 * @param array $array1 ( String|Array )
 * @param array|null $array2 ( String|Array )
 * @param string $prefix
 * @return string
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
 * @param string $query Query string
 * @return string[] Array version of input
 */
function wfCgiToArray( $query ) {
	if ( isset( $query[0] ) && $query[0] == '?' ) {
		$query = substr( $query, 1 );
	}
	$bits = explode( '&', $query );
	$ret = [];
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
				$temp = [ $k => $temp ];
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
		// Remove the fragment, if there is one
		$fragment = false;
		$hashPos = strpos( $url, '#' );
		if ( $hashPos !== false ) {
			$fragment = substr( $url, $hashPos );
			$url = substr( $url, 0, $hashPos );
		}

		// Add parameter
		if ( false === strpos( $url, '?' ) ) {
			$url .= '?';
		} else {
			$url .= '&';
		}
		$url .= $query;

		// Put the fragment back
		if ( $fragment !== false ) {
			$url .= $fragment;
		}
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
 * @param string $url Either fully-qualified or a local path + query
 * @param string|int|null $defaultProto One of the PROTO_* constants. Determines the
 *    protocol to use if $url or $wgServer is protocol-relative
 * @return string|false Fully-qualified URL, current-path-relative URL or false if
 *    no valid URL can be constructed
 */
function wfExpandUrl( $url, $defaultProto = PROTO_CURRENT ) {
	global $wgServer, $wgCanonicalServer, $wgInternalServer, $wgRequest,
		$wgHttpsPort;
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

	// ensure proper port for HTTPS arrives in URL
	// https://phabricator.wikimedia.org/T67184
	if ( $defaultProto === PROTO_HTTPS && $wgHttpsPort != 443 ) {
		$bits['port'] = $wgHttpsPort;
	}

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
 * @todo Need to integrate this into wfExpandUrl (see T34168)
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
 * @todo Need to integrate this into wfExpandUrl (see T34168)
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
 * @return string
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
		$protocols = [];
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
 * @return string
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
 * 3) Adds a "delimiter" element to the array (see (2)).
 * 4) Verifies that the protocol is on the $wgUrlProtocols whitelist.
 * 5) Rejects some invalid URLs that parse_url doesn't, e.g. the empty string or URLs starting with
 *    a line feed character.
 *
 * @param string $url A URL to parse
 * @return string[]|bool Bits of the URL in an associative array, or false on failure.
 *   Possible fields:
 *   - scheme: URI scheme (protocol), e.g. 'http', 'mailto'. Lowercase, always present, but can
 *       be an empty string for protocol-relative URLs.
 *   - delimiter: either '://', ':' or '//'. Always present.
 *   - host: domain name / IP. Always present, but could be an empty string, e.g. for file: URLs.
 *   - user: user name, e.g. for HTTP Basic auth URLs such as http://user:pass@example.com/
 *       Missing when there is no username.
 *   - pass: password, same as above.
 *   - path: path including the leading /. Will be missing when empty (e.g. 'http://example.com')
 *   - query: query string (as a string; see wfCgiToArray() for parsing it), can be missing.
 *   - fragment: the part after #, can be missing.
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
	Wikimedia\suppressWarnings();
	$bits = parse_url( $url );
	Wikimedia\restoreWarnings();
	// parse_url() returns an array without scheme for some invalid URLs, e.g.
	// parse_url("%0Ahttp://example.com") == [ 'host' => '%0Ahttp', 'path' => 'example.com' ]
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

	/* Provide an empty host for eg. file:/// urls (see T30627) */
	if ( !isset( $bits['host'] ) ) {
		$bits['host'] = '';

		// See T47069
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
 * @param string $url
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
 * @param string $url
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
		return [ "http:$index", "https:$index" ];
	} else {
		return [ $index ];
	}
}

/**
 * Check whether a given URL has a domain that occurs in a given set of domains
 * @param string $url
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
 * @since 1.25 support for additional context data
 *
 * @param string $text
 * @param string|bool $dest Destination of the message:
 *     - 'all': both to the log and HTML (debug toolbar or HTML comments)
 *     - 'private': excluded from HTML output
 *   For backward compatibility, it can also take a boolean:
 *     - true: same as 'all'
 *     - false: same as 'private'
 * @param array $context Additional logging context data
 */
function wfDebug( $text, $dest = 'all', array $context = [] ) {
	global $wgDebugRawPage, $wgDebugLogPrefix;
	global $wgDebugTimestamps;

	if ( !$wgDebugRawPage && wfIsDebugRawPage() ) {
		return;
	}

	$text = trim( $text );

	if ( $wgDebugTimestamps ) {
		$context['seconds_elapsed'] = sprintf(
			'%6.4f',
			microtime( true ) - $_SERVER['REQUEST_TIME_FLOAT']
		);
		$context['memory_used'] = sprintf(
			'%5.1fM',
			( memory_get_usage( true ) / ( 1024 * 1024 ) )
		);
	}

	if ( $wgDebugLogPrefix !== '' ) {
		$context['prefix'] = $wgDebugLogPrefix;
	}
	$context['private'] = ( $dest === false || $dest === 'private' );

	$logger = LoggerFactory::getInstance( 'wfDebug' );
	$logger->debug( $text, $context );
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
 * Send a line giving PHP memory usage.
 *
 * @param bool $exact Print exact byte values instead of kibibytes (default: false)
 */
function wfDebugMem( $exact = false ) {
	$mem = memory_get_usage();
	if ( !$exact ) {
		$mem = floor( $mem / 1024 ) . ' KiB';
	} else {
		$mem .= ' B';
	}
	wfDebug( "Memory usage: $mem\n" );
}

/**
 * Send a line to a supplementary debug log file, if configured, or main debug
 * log if not.
 *
 * To configure a supplementary log file, set $wgDebugLogGroups[$logGroup] to
 * a string filename or an associative array mapping 'destination' to the
 * desired filename. The associative array may also contain a 'sample' key
 * with an integer value, specifying a sampling factor. Sampled log events
 * will be emitted with a 1 in N random chance.
 *
 * @since 1.23 support for sampling log messages via $wgDebugLogGroups.
 * @since 1.25 support for additional context data
 * @since 1.25 sample behavior dependent on configured $wgMWLoggerDefaultSpi
 *
 * @param string $logGroup
 * @param string $text
 * @param string|bool $dest Destination of the message:
 *     - 'all': both to the log and HTML (debug toolbar or HTML comments)
 *     - 'private': only to the specific log if set in $wgDebugLogGroups and
 *       discarded otherwise
 *   For backward compatibility, it can also take a boolean:
 *     - true: same as 'all'
 *     - false: same as 'private'
 * @param array $context Additional logging context data
 */
function wfDebugLog(
	$logGroup, $text, $dest = 'all', array $context = []
) {
	$text = trim( $text );

	$logger = LoggerFactory::getInstance( $logGroup );
	$context['private'] = ( $dest === false || $dest === 'private' );
	$logger->info( $text, $context );
}

/**
 * Log for database errors
 *
 * @since 1.25 support for additional context data
 *
 * @param string $text Database error message.
 * @param array $context Additional logging context data
 */
function wfLogDBError( $text, array $context = [] ) {
	$logger = LoggerFactory::getInstance( 'wfLogDBError' );
	$logger->error( trim( $text ), $context );
}

/**
 * Throws a warning that $function is deprecated
 *
 * @param string $function
 * @param string|bool $version Version of MediaWiki that the function
 *    was deprecated in (Added in 1.19).
 * @param string|bool $component Added in 1.19.
 * @param int $callerOffset How far up the call stack is the original
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
 * @param string $msg Message to send
 * @param int $callerOffset Number of items to go back in the backtrace to
 *        find the correct caller (1 = function calling wfWarn, ...)
 * @param int $level PHP error level; defaults to E_USER_NOTICE;
 *        only used when $wgDevelopmentWarnings is true
 */
function wfWarn( $msg, $callerOffset = 1, $level = E_USER_NOTICE ) {
	MWDebug::warning( $msg, $callerOffset + 1, $level, 'auto' );
}

/**
 * Send a warning as a PHP error and the debug log. This is intended for logging
 * warnings in production. For logging development warnings, use WfWarn instead.
 *
 * @param string $msg Message to send
 * @param int $callerOffset Number of items to go back in the backtrace to
 *        find the correct caller (1 = function calling wfLogWarning, ...)
 * @param int $level PHP error level; defaults to E_USER_WARNING
 */
function wfLogWarning( $msg, $callerOffset = 1, $level = E_USER_WARNING ) {
	MWDebug::warning( $msg, $callerOffset + 1, $level, 'production' );
}

/**
 * Log to a file without getting "file size exceeded" signals.
 *
 * Can also log to TCP or UDP with the syntax udp://host:port/prefix. This will
 * send lines to the specified port, prefixed by the specified prefix and a space.
 * @since 1.25 support for additional context data
 *
 * @param string $text
 * @param string $file Filename
 * @param array $context Additional logging context data
 * @throws MWException
 * @deprecated since 1.25 Use \MediaWiki\Logger\LegacyLogger::emit or UDPTransport
 */
function wfErrorLog( $text, $file, array $context = [] ) {
	wfDeprecated( __METHOD__, '1.25' );
	$logger = LoggerFactory::getInstance( 'wfErrorLog' );
	$context['destination'] = $file;
	$logger->info( trim( $text ), $context );
}

/**
 * @todo document
 * @todo Move logic to MediaWiki.php
 */
function wfLogProfilingData() {
	global $wgDebugLogGroups, $wgDebugRawPage;

	$context = RequestContext::getMain();
	$request = $context->getRequest();

	$profiler = Profiler::instance();
	$profiler->setContext( $context );
	$profiler->logData();

	// Send out any buffered statsd metrics as needed
	MediaWiki::emitBufferedStatsdData(
		MediaWikiServices::getInstance()->getStatsdDataFactory(),
		$context->getConfig()
	);

	// Profiling must actually be enabled...
	if ( $profiler instanceof ProfilerStub ) {
		return;
	}

	if ( isset( $wgDebugLogGroups['profileoutput'] )
		&& $wgDebugLogGroups['profileoutput'] === false
	) {
		// Explicitly disabled
		return;
	}
	if ( !$wgDebugRawPage && wfIsDebugRawPage() ) {
		return;
	}

	$ctx = [ 'elapsed' => $request->getElapsedTime() ];
	if ( !empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
		$ctx['forwarded_for'] = $_SERVER['HTTP_X_FORWARDED_FOR'];
	}
	if ( !empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
		$ctx['client_ip'] = $_SERVER['HTTP_CLIENT_IP'];
	}
	if ( !empty( $_SERVER['HTTP_FROM'] ) ) {
		$ctx['from'] = $_SERVER['HTTP_FROM'];
	}
	if ( isset( $ctx['forwarded_for'] ) ||
		isset( $ctx['client_ip'] ) ||
		isset( $ctx['from'] ) ) {
		$ctx['proxy'] = $_SERVER['REMOTE_ADDR'];
	}

	// Don't load $wgUser at this late stage just for statistics purposes
	// @todo FIXME: We can detect some anons even if it is not loaded.
	// See User::getId()
	$user = $context->getUser();
	$ctx['anon'] = $user->isItemLoaded( 'id' ) && $user->isAnon();

	// Command line script uses a FauxRequest object which does not have
	// any knowledge about an URL and throw an exception instead.
	try {
		$ctx['url'] = urldecode( $request->getRequestURL() );
	} catch ( Exception $ignored ) {
		// no-op
	}

	$ctx['output'] = $profiler->getOutput();

	$log = LoggerFactory::getInstance( 'profileoutput' );
	$log->info( "Elapsed: {elapsed}; URL: <{url}>\n{output}", $ctx );
}

/**
 * Increment a statistics counter
 *
 * @param string $key
 * @param int $count
 * @return void
 */
function wfIncrStats( $key, $count = 1 ) {
	$stats = MediaWikiServices::getInstance()->getStatsdDataFactory();
	$stats->updateCount( $key, $count );
}

/**
 * Check whether the wiki is in read-only mode.
 *
 * @return bool
 */
function wfReadOnly() {
	return MediaWikiServices::getInstance()->getReadOnlyMode()
		->isReadOnly();
}

/**
 * Check if the site is in read-only mode and return the message if so
 *
 * This checks wfConfiguredReadOnlyReason() and the main load balancer
 * for replica DB lag. This may result in DB connection being made.
 *
 * @return string|bool String when in read-only mode; false otherwise
 */
function wfReadOnlyReason() {
	return MediaWikiServices::getInstance()->getReadOnlyMode()
		->getReason();
}

/**
 * Get the value of $wgReadOnly or the contents of $wgReadOnlyFile.
 *
 * @return string|bool String when in read-only mode; false otherwise
 * @since 1.27
 */
function wfConfiguredReadOnlyReason() {
	return MediaWikiServices::getInstance()->getConfiguredReadOnlyMode()
		->getReason();
}

/**
 * Return a Language object from $langcode
 *
 * @param Language|string|bool $langcode Either:
 *                  - a Language object
 *                  - code of the language to get the message for, if it is
 *                    a valid code create a language for that language, if
 *                    it is a string but not a valid code then make a basic
 *                    language object
 *                  - a boolean: if it's false then use the global object for
 *                    the current user's language (as a fallback for the old parameter
 *                    functionality), or if it is true then use global object
 *                    for the wiki's content language.
 * @return Language
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
 * @param string|string[]|MessageSpecifier $key Message key, or array of keys, or a MessageSpecifier
 * @param mixed $params,... Normal message parameters
 * @return Message
 *
 * @since 1.17
 *
 * @see Message::__construct
 */
function wfMessage( $key /*...*/ ) {
	$message = new Message( $key );

	// We call Message::params() to reduce code duplication
	$params = func_get_args();
	array_shift( $params );
	if ( $params ) {
		call_user_func_array( [ $message, 'params' ], $params );
	}

	return $message;
}

/**
 * This function accepts multiple message keys and returns a message instance
 * for the first message which is non-empty. If all messages are empty then an
 * instance of the first message key is returned.
 *
 * @param string|string[] $keys,... Message keys
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
	if ( is_array( $args ) && $args ) {
		if ( is_array( $args[0] ) ) {
			$args = array_values( $args[0] );
		}
		$replacementKeys = [];
		foreach ( $args as $n => $param ) {
			$replacementKeys['$' . ( $n + 1 )] = $param;
		}
		$message = strtr( $message, $replacementKeys );
	}

	return $message;
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
	global $wgShowHostnames;

	$elapsed = ( microtime( true ) - $_SERVER['REQUEST_TIME_FLOAT'] );
	// seconds to milliseconds
	$responseTime = round( $elapsed * 1000 );
	$reportVars = [ 'wgBackendResponseTime' => $responseTime ];
	if ( $wgShowHostnames ) {
		$reportVars['wgHostname'] = wfHostname();
	}
	return Skin::makeVariablesScript( $reportVars );
}

/**
 * Safety wrapper for debug_backtrace().
 *
 * Will return an empty array if debug_backtrace is disabled, otherwise
 * the output from debug_backtrace() (trimmed).
 *
 * @param int $limit This parameter can be used to limit the number of stack frames returned
 *
 * @return array Array of backtrace information
 */
function wfDebugBacktrace( $limit = 0 ) {
	static $disabled = null;

	if ( is_null( $disabled ) ) {
		$disabled = !function_exists( 'debug_backtrace' );
		if ( $disabled ) {
			wfDebug( "debug_backtrace() is disabled\n" );
		}
	}
	if ( $disabled ) {
		return [];
	}

	if ( $limit ) {
		return array_slice( debug_backtrace( DEBUG_BACKTRACE_PROVIDE_OBJECT, $limit + 1 ), 1 );
	} else {
		return array_slice( debug_backtrace(), 1 );
	}
}

/**
 * Get a debug backtrace as a string
 *
 * @param bool|null $raw If true, the return value is plain text. If false, HTML.
 *   Defaults to $wgCommandLineMode if unset.
 * @return string
 * @since 1.25 Supports $raw parameter.
 */
function wfBacktrace( $raw = null ) {
	global $wgCommandLineMode;

	if ( $raw === null ) {
		$raw = $wgCommandLineMode;
	}

	if ( $raw ) {
		$frameFormat = "%s line %s calls %s()\n";
		$traceFormat = "%s";
	} else {
		$frameFormat = "<li>%s line %s calls %s()</li>\n";
		$traceFormat = "<ul>\n%s</ul>\n";
	}

	$frames = array_map( function ( $frame ) use ( $frameFormat ) {
		$file = !empty( $frame['file'] ) ? basename( $frame['file'] ) : '-';
		$line = isset( $frame['line'] ) ? $frame['line'] : '-';
		$call = $frame['function'];
		if ( !empty( $frame['class'] ) ) {
			$call = $frame['class'] . $frame['type'] . $call;
		}
		return sprintf( $frameFormat, $file, $line, $call );
	}, wfDebugBacktrace() );

	return sprintf( $traceFormat, implode( '', $frames ) );
}

/**
 * Get the name of the function which called this function
 * wfGetCaller( 1 ) is the function with the wfGetCaller() call (ie. __FUNCTION__)
 * wfGetCaller( 2 ) [default] is the caller of the function running wfGetCaller()
 * wfGetCaller( 3 ) is the parent of that.
 *
 * @param int $level
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
 * @param int $limit The maximum depth of the stack frame to return, or false for the entire stack.
 * @return string
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
 * @param array $frame
 * @return string
 */
function wfFormatStackFrame( $frame ) {
	if ( !isset( $frame['function'] ) ) {
		return 'NO_FUNCTION_GIVEN';
	}
	return isset( $frame['class'] ) && isset( $frame['type'] ) ?
		$frame['class'] . $frame['type'] . $frame['function'] :
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
 * Whether the client accept gzip encoding
 *
 * Uses the Accept-Encoding header to check if the client supports gzip encoding.
 * Use this when considering to send a gzip-encoded response to the client.
 *
 * @param bool $force Forces another check even if we already have a cached result.
 * @return bool
 */
function wfClientAcceptsGzip( $force = false ) {
	static $result = null;
	if ( $result === null || $force ) {
		$result = false;
		if ( isset( $_SERVER['HTTP_ACCEPT_ENCODING'] ) ) {
			# @todo FIXME: We may want to blacklist some broken browsers
			$m = [];
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
 * Escapes the given text so that it may be output using addWikiText()
 * without any linking, formatting, etc. making its way through. This
 * is achieved by substituting certain characters with HTML entities.
 * As required by the callers, "<nowiki>" is not used.
 *
 * @param string $text Text to be escaped
 * @return string
 */
function wfEscapeWikiText( $text ) {
	global $wgEnableMagicLinks;
	static $repl = null, $repl2 = null;
	if ( $repl === null || defined( 'MW_PARSER_TEST' ) || defined( 'MW_PHPUNIT_TEST' ) ) {
		// Tests depend upon being able to change $wgEnableMagicLinks, so don't cache
		// in those situations
		$repl = [
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
		];

		$magicLinks = array_keys( array_filter( $wgEnableMagicLinks ) );
		// We have to catch everything "\s" matches in PCRE
		foreach ( $magicLinks as $magic ) {
			$repl["$magic "] = "$magic&#32;";
			$repl["$magic\t"] = "$magic&#9;";
			$repl["$magic\r"] = "$magic&#13;";
			$repl["$magic\n"] = "$magic&#10;";
			$repl["$magic\f"] = "$magic&#12;";
		}

		// And handle protocols that don't use "://"
		global $wgUrlProtocols;
		$repl2 = [];
		foreach ( $wgUrlProtocols as $prot ) {
			if ( substr( $prot, -1 ) === ':' ) {
				$repl2[] = preg_quote( substr( $prot, 0, -1 ), '/' );
			}
		}
		$repl2 = $repl2 ? '/\b(' . implode( '|', $repl2 ) . '):/i' : '/^(?!)/';
	}
	$text = substr( strtr( "\n$text", $repl ), 1 );
	$text = preg_replace( $repl2, '$1&#58;', $text );
	return $text;
}

/**
 * Sets dest to source and returns the original value of dest
 * If source is NULL, it just returns the value, it doesn't set the variable
 * If force is true, it will set the value even if source is NULL
 *
 * @param mixed &$dest
 * @param mixed $source
 * @param bool $force
 * @return mixed
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
 * @param int &$dest
 * @param int $bit
 * @param bool $state
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
 * @param mixed $var A PHP variable to dump.
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
 * @param int|string $code
 * @param string $label
 * @param string $desc
 */
function wfHttpError( $code, $label, $desc ) {
	global $wgOut;
	HttpStatus::header( $code );
	if ( $wgOut ) {
		$wgOut->disable();
		$wgOut->sendCacheControl();
	}

	MediaWiki\HeaderCallback::warnIfHeadersSent();
	header( 'Content-type: text/html; charset=utf-8' );
	print '<!DOCTYPE html>' .
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
 * @param bool $resetGzipEncoding
 */
function wfResetOutputBuffers( $resetGzipEncoding = true ) {
	if ( $resetGzipEncoding ) {
		// Suppress Content-Encoding and Content-Length
		// headers from OutputHandler::handle.
		global $wgDisableOutputCompression;
		$wgDisableOutputCompression = true;
	}
	while ( $status = ob_get_status() ) {
		if ( isset( $status['flags'] ) ) {
			$flags = PHP_OUTPUT_HANDLER_CLEANABLE | PHP_OUTPUT_HANDLER_REMOVABLE;
			$deleteable = ( $status['flags'] & $flags ) === $flags;
		} elseif ( isset( $status['del'] ) ) {
			$deleteable = $status['del'];
		} else {
			// Guess that any PHP-internal setting can't be removed.
			$deleteable = $status['type'] !== 0; /* PHP_OUTPUT_HANDLER_INTERNAL */
		}
		if ( !$deleteable ) {
			// Give up, and hope the result doesn't break
			// output behavior.
			break;
		}
		if ( $status['name'] === 'MediaWikiTestCase::wfResetOutputBuffersBarrier' ) {
			// Unit testing barrier to prevent this function from breaking PHPUnit.
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
 * @param string $def Default
 * @return float[] Associative array of string => float pairs
 */
function wfAcceptToPrefs( $accept, $def = '*/*' ) {
	# No arg means accept anything (per HTTP spec)
	if ( !$accept ) {
		return [ $def => 1.0 ];
	}

	$prefs = [];

	$parts = explode( ',', $accept );

	foreach ( $parts as $part ) {
		# @todo FIXME: Doesn't deal with params like 'text/html; level=1'
		$values = explode( ';', trim( $part ) );
		$match = [];
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
		$mainType = explode( '/', $type )[0];
		if ( array_key_exists( "$mainType/*", $avail ) ) {
			return "$mainType/*";
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
 * @param array $cprefs Client's acceptable type list
 * @param array $sprefs Server's offered types
 * @return string
 *
 * @todo FIXME: Doesn't handle params like 'text/plain; charset=UTF-8'
 * XXX: generalize to negotiate other stuff
 */
function wfNegotiateType( $cprefs, $sprefs ) {
	$combine = [];

	foreach ( array_keys( $sprefs ) as $type ) {
		$subType = explode( '/', $type )[1];
		if ( $subType != '*' ) {
			$ckey = mimeTypeMatch( $type, $cprefs );
			if ( $ckey ) {
				$combine[$type] = $sprefs[$type] * $cprefs[$ckey];
			}
		}
	}

	foreach ( array_keys( $cprefs ) as $type ) {
		$subType = explode( '/', $type )[1];
		if ( $subType != '*' && !array_key_exists( $type, $sprefs ) ) {
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
 * @deprecated since 1.26, use Wikimedia\suppressWarnings() directly
 * @param bool $end
 */
function wfSuppressWarnings( $end = false ) {
	Wikimedia\suppressWarnings( $end );
}

/**
 * @deprecated since 1.26, use Wikimedia\restoreWarnings() directly
 * Restore error level to previous value
 */
function wfRestoreWarnings() {
	Wikimedia\restoreWarnings();
}

/**
 * Get a timestamp string in one of various formats
 *
 * @param mixed $outputtype A timestamp in one of the supported formats, the
 *   function will autodetect which format is supplied and act accordingly.
 * @param mixed $ts Optional timestamp to convert, default 0 for the current time
 * @return string|bool String / false The same date in the format specified in $outputtype or false
 */
function wfTimestamp( $outputtype = TS_UNIX, $ts = 0 ) {
	$ret = MWTimestamp::convert( $outputtype, $ts );
	if ( $ret === false ) {
		wfDebug( "wfTimestamp() fed bogus time value: TYPE=$outputtype; VALUE=$ts\n" );
	}
	return $ret;
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
	return MWTimestamp::now( TS_MW );
}

/**
 * Check if the operating system is Windows
 *
 * @return bool True if it's Windows, false otherwise.
 */
function wfIsWindows() {
	static $isWindows = null;
	if ( $isWindows === null ) {
		$isWindows = strtoupper( substr( PHP_OS, 0, 3 ) ) === 'WIN';
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
 * Check if we are running from the commandline
 *
 * @since 1.31
 * @return bool
 */
function wfIsCLI() {
	return PHP_SAPI === 'cli' || PHP_SAPI === 'phpdbg';
}

/**
 * Tries to get the system directory for temporary files. First
 * $wgTmpDirectory is checked, and then the TMPDIR, TMP, and TEMP
 * environment variables are then checked in sequence, then
 * sys_get_temp_dir(), then upload_tmp_dir from php.ini.
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

	return TempFSFile::getUsableTempDirectory();
}

/**
 * Make directory, and make all parent directories if they don't exist
 *
 * @param string $dir Full path to directory to create
 * @param int $mode Chmod value to use, default is $wgDirectoryMode
 * @param string $caller Optional caller param for debugging.
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

	if ( strval( $dir ) === '' || is_dir( $dir ) ) {
		return true;
	}

	$dir = str_replace( [ '\\', '/' ], DIRECTORY_SEPARATOR, $dir );

	if ( is_null( $mode ) ) {
		$mode = $wgDirectoryMode;
	}

	// Turn off the normal warning, we're doing our own below
	Wikimedia\suppressWarnings();
	$ok = mkdir( $dir, $mode, true ); // PHP5 <3
	Wikimedia\restoreWarnings();

	if ( !$ok ) {
		// directory may have been created on another request since we last checked
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
 * @param string $dir
 */
function wfRecursiveRemoveDir( $dir ) {
	wfDebug( __FUNCTION__ . "( $dir )\n" );
	// taken from https://secure.php.net/manual/en/function.rmdir.php#98622
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
 * @param int $nr The number to format
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
	return wfStringToBool( ini_get( $setting ) );
}

/**
 * Convert string value to boolean, when the following are interpreted as true:
 * - on
 * - true
 * - yes
 * - Any number, except 0
 * All other strings are interpreted as false.
 *
 * @param string $val
 * @return bool
 * @since 1.31
 */
function wfStringToBool( $val ) {
	$val = strtolower( $val );
	// 'on' and 'true' can't have whitespace around them, but '1' can.
	return $val == 'on'
		|| $val == 'true'
		|| $val == 'yes'
		|| preg_match( "/^\s*[+-]?0*[1-9]/", $val ); // approx C atoi() function
}

/**
 * Version of escapeshellarg() that works better on Windows.
 *
 * Originally, this fixed the incorrect use of single quotes on Windows
 * (https://bugs.php.net/bug.php?id=26285) and the locale problems on Linux in
 * PHP 5.2.6+ (bug backported to earlier distro releases of PHP).
 *
 * @param string $args,... strings to escape and glue together,
 *  or a single array of strings parameter
 * @return string
 * @deprecated since 1.30 use MediaWiki\Shell::escape()
 */
function wfEscapeShellArg( /*...*/ ) {
	$args = func_get_args();

	return call_user_func_array( Shell::class . '::escape', $args );
}

/**
 * Check if wfShellExec() is effectively disabled via php.ini config
 *
 * @return bool|string False or 'disabled'
 * @since 1.22
 * @deprecated since 1.30 use MediaWiki\Shell::isDisabled()
 */
function wfShellExecDisabled() {
	wfDeprecated( __FUNCTION__, '1.30' );
	return Shell::isDisabled() ? 'disabled' : false;
}

/**
 * Execute a shell command, with time and memory limits mirrored from the PHP
 * configuration if supported.
 *
 * @param string|string[] $cmd If string, a properly shell-escaped command line,
 *   or an array of unescaped arguments, in which case each value will be escaped
 *   Example:   [ 'convert', '-font', 'font name' ] would produce "'convert' '-font' 'font name'"
 * @param null|mixed &$retval Optional, will receive the program's exit code.
 *   (non-zero is usually failure). If there is an error from
 *   read, select, or proc_open(), this will be set to -1.
 * @param array $environ Optional environment variables which should be
 *   added to the executed command environment.
 * @param array $limits Optional array with limits(filesize, memory, time, walltime)
 *   this overwrites the global wgMaxShell* limits.
 * @param array $options Array of options:
 *   - duplicateStderr: Set this to true to duplicate stderr to stdout,
 *     including errors from limit.sh
 *   - profileMethod: By default this function will profile based on the calling
 *     method. Set this to a string for an alternative method to profile from
 *
 * @return string Collected stdout as a string
 * @deprecated since 1.30 use class MediaWiki\Shell\Shell
 */
function wfShellExec( $cmd, &$retval = null, $environ = [],
	$limits = [], $options = []
) {
	if ( Shell::isDisabled() ) {
		$retval = 1;
		// Backwards compatibility be upon us...
		return 'Unable to run external programs, proc_open() is disabled.';
	}

	if ( is_array( $cmd ) ) {
		$cmd = Shell::escape( $cmd );
	}

	$includeStderr = isset( $options['duplicateStderr'] ) && $options['duplicateStderr'];
	$profileMethod = isset( $options['profileMethod'] ) ? $options['profileMethod'] : wfGetCaller();

	try {
		$result = Shell::command( [] )
			->unsafeParams( (array)$cmd )
			->environment( $environ )
			->limits( $limits )
			->includeStderr( $includeStderr )
			->profileMethod( $profileMethod )
			// For b/c
			->restrict( Shell::RESTRICT_NONE )
			->execute();
	} catch ( ProcOpenError $ex ) {
		$retval = -1;
		return '';
	}

	$retval = $result->getExitCode();

	return $result->getStdout();
}

/**
 * Execute a shell command, returning both stdout and stderr. Convenience
 * function, as all the arguments to wfShellExec can become unwieldy.
 *
 * @note This also includes errors from limit.sh, e.g. if $wgMaxShellFileSize is exceeded.
 * @param string|string[] $cmd If string, a properly shell-escaped command line,
 *   or an array of unescaped arguments, in which case each value will be escaped
 *   Example:   [ 'convert', '-font', 'font name' ] would produce "'convert' '-font' 'font name'"
 * @param null|mixed &$retval Optional, will receive the program's exit code.
 *   (non-zero is usually failure)
 * @param array $environ Optional environment variables which should be
 *   added to the executed command environment.
 * @param array $limits Optional array with limits(filesize, memory, time, walltime)
 *   this overwrites the global wgMaxShell* limits.
 * @return string Collected stdout and stderr as a string
 * @deprecated since 1.30 use class MediaWiki\Shell\Shell
 */
function wfShellExecWithStderr( $cmd, &$retval = null, $environ = [], $limits = [] ) {
	return wfShellExec( $cmd, $retval, $environ, $limits,
		[ 'duplicateStderr' => true, 'profileMethod' => wfGetCaller() ] );
}

/**
 * Formerly set the locale for locale-sensitive operations
 *
 * This is now done in Setup.php.
 *
 * @deprecated since 1.30, no longer needed
 * @see $wgShellLocale
 */
function wfInitShellLocale() {
	wfDeprecated( __FUNCTION__, '1.30' );
}

/**
 * Generate a shell-escaped command line string to run a MediaWiki cli script.
 * Note that $parameters should be a flat array and an option with an argument
 * should consist of two consecutive items in the array (do not use "--option value").
 *
 * @deprecated since 1.31, use Shell::makeScriptCommand()
 *
 * @param string $script MediaWiki cli script path
 * @param array $parameters Arguments and options to the script
 * @param array $options Associative array of options:
 *     'php': The path to the php executable
 *     'wrapper': Path to a PHP wrapper to handle the maintenance script
 * @return string
 */
function wfShellWikiCmd( $script, array $parameters = [], array $options = [] ) {
	global $wgPhpCli;
	// Give site config file a chance to run the script in a wrapper.
	// The caller may likely want to call wfBasename() on $script.
	Hooks::run( 'wfShellWikiCmd', [ &$script, &$parameters, &$options ] );
	$cmd = isset( $options['php'] ) ? [ $options['php'] ] : [ $wgPhpCli ];
	if ( isset( $options['wrapper'] ) ) {
		$cmd[] = $options['wrapper'];
	}
	$cmd[] = $script;
	// Escape each parameter for shell
	return Shell::escape( array_merge( $cmd, $parameters ) );
}

/**
 * wfMerge attempts to merge differences between three texts.
 * Returns true for a clean merge and false for failure or a conflict.
 *
 * @param string $old
 * @param string $mine
 * @param string $yours
 * @param string &$result
 * @param string &$mergeAttemptResult
 * @return bool
 */
function wfMerge( $old, $mine, $yours, &$result, &$mergeAttemptResult = null ) {
	global $wgDiff3;

	# This check may also protect against code injection in
	# case of broken installations.
	Wikimedia\suppressWarnings();
	$haveDiff3 = $wgDiff3 && file_exists( $wgDiff3 );
	Wikimedia\restoreWarnings();

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
	$cmd = Shell::escape( $wgDiff3, '-a', '--overlap-only', $mytextName,
		$oldtextName, $yourtextName );
	$handle = popen( $cmd, 'r' );

	$mergeAttemptResult = '';
	do {
		$data = fread( $handle, 8192 );
		if ( strlen( $data ) == 0 ) {
			break;
		}
		$mergeAttemptResult .= $data;
	} while ( true );
	pclose( $handle );

	$conflict = $mergeAttemptResult !== '';

	# Merge differences
	$cmd = Shell::escape( $wgDiff3, '-a', '-e', '--merge', $mytextName,
		$oldtextName, $yourtextName );
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
 * "Useful" for machine processing of diffs.
 *
 * @deprecated since 1.25, use DiffEngine/UnifiedDiffFormatter directly
 *
 * @param string $before The text before the changes.
 * @param string $after The text after the changes.
 * @param string $params Command-line options for the diff command.
 * @return string Unified diff of $before and $after
 */
function wfDiff( $before, $after, $params = '-u' ) {
	if ( $before == $after ) {
		return '';
	}

	global $wgDiff;
	Wikimedia\suppressWarnings();
	$haveDiff = $wgDiff && file_exists( $wgDiff );
	Wikimedia\restoreWarnings();

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
	$cmd = "$wgDiff " . $params . ' ' . Shell::escape( $oldtextName, $newtextName );

	$h = popen( $cmd, 'r' );
	if ( !$h ) {
		unlink( $oldtextName );
		unlink( $newtextName );
		throw new Exception( __METHOD__ . '(): popen() failed' );
	}

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
	if ( isset( $diff_lines[0] ) && strpos( $diff_lines[0], '---' ) === 0 ) {
		unset( $diff_lines[0] );
	}
	if ( isset( $diff_lines[1] ) && strpos( $diff_lines[1], '+++' ) === 0 ) {
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
 * @param string|int|float $req_ver The version to check, can be a string, an integer, or a float
 *
 * @deprecated since 1.30
 *
 * @throws MWException
 */
function wfUsePHP( $req_ver ) {
	wfDeprecated( __FUNCTION__, '1.30' );
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
 * @deprecated since 1.26, use the "requires" property of extension.json
 * @param string|int|float $req_ver The version to check, can be a string, an integer, or a float
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
 * https://bugs.php.net/bug.php?id=33898
 *
 * PHP's basename() only considers '\' a pathchar on Windows and Netware.
 * We'll consider it so always, as we don't want '\s' in our Unix paths either.
 *
 * @param string $path
 * @param string $suffix String to remove if present
 * @return string
 */
function wfBaseName( $path, $suffix = '' ) {
	if ( $suffix == '' ) {
		$encSuffix = '';
	} else {
		$encSuffix = '(?:' . preg_quote( $suffix, '#' ) . ')?';
	}

	$matches = [];
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
 * @param string $path Absolute destination path including target filename
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
 * Reset the session id
 *
 * @deprecated since 1.27, use MediaWiki\Session\SessionManager instead
 * @since 1.22
 */
function wfResetSessionID() {
	wfDeprecated( __FUNCTION__, '1.27' );
	$session = SessionManager::getGlobalSession();
	$delay = $session->delaySave();

	$session->resetId();

	// Make sure a session is started, since that's what the old
	// wfResetSessionID() did.
	if ( session_id() !== $session->getId() ) {
		wfSetupSession( $session->getId() );
	}

	ScopedCallback::consume( $delay );
}

/**
 * Initialise php session
 *
 * @deprecated since 1.27, use MediaWiki\Session\SessionManager instead.
 *  Generally, "using" SessionManager will be calling ->getSessionById() or
 *  ::getGlobalSession() (depending on whether you were passing $sessionId
 *  here), then calling $session->persist().
 * @param bool|string $sessionId
 */
function wfSetupSession( $sessionId = false ) {
	wfDeprecated( __FUNCTION__, '1.27' );

	if ( $sessionId ) {
		session_id( $sessionId );
	}

	$session = SessionManager::getGlobalSession();
	$session->persist();

	if ( session_id() !== $session->getId() ) {
		session_id( $session->getId() );
	}
	Wikimedia\quietCall( 'session_start' );
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
 * Make a cache key for the local wiki.
 *
 * @deprecated since 1.30 Call makeKey on a BagOStuff instance
 * @param string $args,...
 * @return string
 */
function wfMemcKey( /*...*/ ) {
	return call_user_func_array(
		[ ObjectCache::getLocalClusterInstance(), 'makeKey' ],
		func_get_args()
	);
}

/**
 * Make a cache key for a foreign DB.
 *
 * Must match what wfMemcKey() would produce in context of the foreign wiki.
 *
 * @param string $db
 * @param string $prefix
 * @param string $args,...
 * @return string
 */
function wfForeignMemcKey( $db, $prefix /*...*/ ) {
	$args = array_slice( func_get_args(), 2 );
	$keyspace = $prefix ? "$db-$prefix" : $db;
	return call_user_func_array(
		[ ObjectCache::getLocalClusterInstance(), 'makeKeyInternal' ],
		[ $keyspace, $args ]
	);
}

/**
 * Make a cache key with database-agnostic prefix.
 *
 * Doesn't have a wiki-specific namespace. Uses a generic 'global' prefix
 * instead. Must have a prefix as otherwise keys that use a database name
 * in the first segment will clash with wfMemcKey/wfForeignMemcKey.
 *
 * @deprecated since 1.30 Call makeGlobalKey on a BagOStuff instance
 * @since 1.26
 * @param string $args,...
 * @return string
 */
function wfGlobalCacheKey( /*...*/ ) {
	return call_user_func_array(
		[ ObjectCache::getLocalClusterInstance(), 'makeGlobalKey' ],
		func_get_args()
	);
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
 *            master (for write queries), DB_REPLICA for potentially lagged read
 *            queries, or an integer >= 0 for a particular server.
 *
 * @param string|string[] $groups Query groups. An array of group names that this query
 *                belongs to. May contain a single string if the query is only
 *                in one group.
 *
 * @param string|bool $wiki The wiki ID, or false for the current wiki
 *
 * Note: multiple calls to wfGetDB(DB_REPLICA) during the course of one request
 * will always return the same object, unless the underlying connection or load
 * balancer is manually destroyed.
 *
 * Note 2: use $this->getDB() in maintenance scripts that may be invoked by
 * updater to ensure that a proper database is being updated.
 *
 * @todo Replace calls to wfGetDB with calls to LoadBalancer::getConnection()
 *       on an injected instance of LoadBalancer.
 *
 * @return \Wikimedia\Rdbms\Database
 */
function wfGetDB( $db, $groups = [], $wiki = false ) {
	return wfGetLB( $wiki )->getConnection( $db, $groups, $wiki );
}

/**
 * Get a load balancer object.
 *
 * @deprecated since 1.27, use MediaWikiServices::getDBLoadBalancer()
 *              or MediaWikiServices::getDBLoadBalancerFactory() instead.
 *
 * @param string|bool $wiki Wiki ID, or false for the current wiki
 * @return \Wikimedia\Rdbms\LoadBalancer
 */
function wfGetLB( $wiki = false ) {
	if ( $wiki === false ) {
		return MediaWikiServices::getInstance()->getDBLoadBalancer();
	} else {
		$factory = MediaWikiServices::getInstance()->getDBLoadBalancerFactory();
		return $factory->getMainLB( $wiki );
	}
}

/**
 * Get the load balancer factory object
 *
 * @deprecated since 1.27, use MediaWikiServices::getDBLoadBalancerFactory() instead.
 *
 * @return \Wikimedia\Rdbms\LBFactory
 */
function wfGetLBFactory() {
	return MediaWikiServices::getInstance()->getDBLoadBalancerFactory();
}

/**
 * Find a file.
 * Shortcut for RepoGroup::singleton()->findFile()
 *
 * @param string|Title $title String or Title object
 * @param array $options Associative array of options (see RepoGroup::findFile)
 * @return File|bool File, or false if the file does not exist
 */
function wfFindFile( $title, $options = [] ) {
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
 * extensions; this is a wrapper around $wgScriptPath etc.
 * except for 'index' and 'load' which use $wgScript/$wgLoadScript
 *
 * @param string $script Script filename, sans extension
 * @return string
 */
function wfScript( $script = 'index' ) {
	global $wgScriptPath, $wgScript, $wgLoadScript;
	if ( $script === 'index' ) {
		return $wgScript;
	} elseif ( $script === 'load' ) {
		return $wgLoadScript;
	} else {
		return "{$wgScriptPath}/{$script}.php";
	}
}

/**
 * Get the script URL.
 *
 * @return string Script URL
 */
function wfGetScriptUrl() {
	if ( isset( $_SERVER['SCRIPT_NAME'] ) ) {
		/* as it was called, minus the query string.
		 *
		 * Some sites use Apache rewrite rules to handle subdomains,
		 * and have PHP set up in a weird way that causes PHP_SELF
		 * to contain the rewritten URL instead of the one that the
		 * outside world sees.
		 *
		 * If in this mode, use SCRIPT_URL instead, which mod_rewrite
		 * provides containing the "before" URL.
		 */
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
 * Waits for the replica DBs to catch up to the master position
 *
 * Use this when updating very large numbers of rows, as in maintenance scripts,
 * to avoid causing too much lag. Of course, this is a no-op if there are no replica DBs.
 *
 * By default this waits on the main DB cluster of the current wiki.
 * If $cluster is set to "*" it will wait on all DB clusters, including
 * external ones. If the lag being waiting on is caused by the code that
 * does this check, it makes since to use $ifWritesSince, particularly if
 * cluster is "*", to avoid excess overhead.
 *
 * Never call this function after a big DB write that is still in a transaction.
 * This only makes sense after the possible lag inducing changes were committed.
 *
 * @param float|null $ifWritesSince Only wait if writes were done since this UNIX timestamp
 * @param string|bool $wiki Wiki identifier accepted by wfGetLB
 * @param string|bool $cluster Cluster name accepted by LBFactory. Default: false.
 * @param int|null $timeout Max wait time. Default: 1 day (cli), ~10 seconds (web)
 * @return bool Success (able to connect and no timeouts reached)
 * @deprecated since 1.27 Use LBFactory::waitForReplication
 */
function wfWaitForSlaves(
	$ifWritesSince = null, $wiki = false, $cluster = false, $timeout = null
) {
	if ( $timeout === null ) {
		$timeout = wfIsCLI() ? 60 : 10;
	}

	if ( $cluster === '*' ) {
		$cluster = false;
		$wiki = false;
	} elseif ( $wiki === false ) {
		$wiki = wfWikiID();
	}

	try {
		$lbFactory = MediaWikiServices::getInstance()->getDBLoadBalancerFactory();
		$lbFactory->waitForReplication( [
			'wiki' => $wiki,
			'cluster' => $cluster,
			'timeout' => $timeout,
			// B/C: first argument used to be "max seconds of lag"; ignore such values
			'ifWritesSince' => ( $ifWritesSince > 1e9 ) ? $ifWritesSince : null
		] );
	} catch ( DBReplicationWaitError $e ) {
		return false;
	}

	return true;
}

/**
 * Count down from $seconds to zero on the terminal, with a one-second pause
 * between showing each number. For use in command-line scripts.
 *
 * @deprecated since 1.31, use Maintenance::countDown()
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
 * Replace all invalid characters with '-'.
 * Additional characters can be defined in $wgIllegalFileChars (see T22489).
 * By default, $wgIllegalFileChars includes ':', '/', '\'.
 *
 * @param string $name Filename to process
 * @return string
 */
function wfStripIllegalFilenameChars( $name ) {
	global $wgIllegalFileChars;
	$illegalFileChars = $wgIllegalFileChars ? "|[" . $wgIllegalFileChars . "]" : '';
	$name = preg_replace(
		"/[^" . Title::legalChars() . "]" . $illegalFileChars . "/",
		'-',
		$name
	);
	// $wgIllegalFileChars may not include '/' and '\', so we still need to do this
	$name = wfBaseName( $name );
	return $name;
}

/**
 * Set PHP's memory limit to the larger of php.ini or $wgMemoryLimit
 *
 * @return int Resulting value of the memory limit.
 */
function wfMemoryLimit() {
	global $wgMemoryLimit;
	$memlimit = wfShorthandToInteger( ini_get( 'memory_limit' ) );
	if ( $memlimit != -1 ) {
		$conflimit = wfShorthandToInteger( $wgMemoryLimit );
		if ( $conflimit == -1 ) {
			wfDebug( "Removing PHP's memory limit\n" );
			Wikimedia\suppressWarnings();
			ini_set( 'memory_limit', $conflimit );
			Wikimedia\restoreWarnings();
			return $conflimit;
		} elseif ( $conflimit > $memlimit ) {
			wfDebug( "Raising PHP's memory limit to $conflimit bytes\n" );
			Wikimedia\suppressWarnings();
			ini_set( 'memory_limit', $conflimit );
			Wikimedia\restoreWarnings();
			return $conflimit;
		}
	}
	return $memlimit;
}

/**
 * Set PHP's time limit to the larger of php.ini or $wgTransactionalTimeLimit
 *
 * @return int Prior time limit
 * @since 1.26
 */
function wfTransactionalTimeLimit() {
	global $wgTransactionalTimeLimit;

	$timeLimit = ini_get( 'max_execution_time' );
	// Note that CLI scripts use 0
	if ( $timeLimit > 0 && $wgTransactionalTimeLimit > $timeLimit ) {
		set_time_limit( $wgTransactionalTimeLimit );
	}

	ignore_user_abort( true ); // ignore client disconnects

	return $timeLimit;
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
 * See mediawiki.language.bcp47 for the JavaScript implementation.
 *
 * @deprecated since 1.31, use LanguageCode::bcp47() directly.
 *
 * @param string $code The language code.
 * @return string The language code which complying with BCP 47 standards.
 */
function wfBCP47( $code ) {
	return LanguageCode::bcp47( $code );
}

/**
 * Get a specific cache object.
 *
 * @param int|string $cacheType A CACHE_* constants, or other key in $wgObjectCaches
 * @return BagOStuff
 */
function wfGetCache( $cacheType ) {
	return ObjectCache::getInstance( $cacheType );
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
 * @deprecated since 1.30, use MediaWikiServices::getParserCache()->getCacheStorage()
 * @return BagOStuff
 */
function wfGetParserCacheStorage() {
	global $wgParserCacheType;
	return ObjectCache::getInstance( $wgParserCacheType );
}

/**
 * Call hook functions defined in $wgHooks
 *
 * @param string $event Event name
 * @param array $args Parameters passed to hook functions
 * @param string|null $deprecatedVersion Optionally mark hook as deprecated with version number
 *
 * @return bool True if no handler aborted the hook
 * @deprecated since 1.25 - use Hooks::run
 */
function wfRunHooks( $event, array $args = [], $deprecatedVersion = null ) {
	wfDeprecated( __METHOD__, '1.25' );
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
 * @throws MWException If $data not long enough, or if unpack fails
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

	Wikimedia\suppressWarnings();
	$result = unpack( $format, $data );
	Wikimedia\restoreWarnings();

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
 * @param string $name The image name to check
 * @param Title|bool $contextTitle The page on which the image occurs, if known
 * @param string $blacklist Wikitext of a file blacklist
 * @return bool
 */
function wfIsBadImage( $name, $contextTitle = false, $blacklist = null ) {
	# Handle redirects; callers almost always hit wfFindFile() anyway,
	# so just use that method because it has a fast process cache.
	$file = wfFindFile( $name ); // get the final name
	$name = $file ? $file->getTitle()->getDBkey() : $name;

	# Run the extension hook
	$bad = false;
	if ( !Hooks::run( 'BadImage', [ $name, &$bad ] ) ) {
		return (bool)$bad;
	}

	$cache = ObjectCache::getLocalServerInstance( 'hash' );
	$key = $cache->makeKey(
		'bad-image-list', ( $blacklist === null ) ? 'default' : md5( $blacklist )
	);
	$badImages = $cache->get( $key );

	if ( $badImages === false ) { // cache miss
		if ( $blacklist === null ) {
			$blacklist = wfMessage( 'bad_image_list' )->inContentLanguage()->plain(); // site list
		}
		# Build the list now
		$badImages = [];
		$lines = explode( "\n", $blacklist );
		foreach ( $lines as $line ) {
			# List items only
			if ( substr( $line, 0, 1 ) !== '*' ) {
				continue;
			}

			# Find all links
			$m = [];
			if ( !preg_match_all( '/\[\[:?(.*?)\]\]/', $line, $m ) ) {
				continue;
			}

			$exceptions = [];
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
		$cache->set( $key, $badImages, 60 );
	}

	$contextKey = $contextTitle ? $contextTitle->getPrefixedDBkey() : false;
	$bad = isset( $badImages[$name] ) && !isset( $badImages[$name][$contextKey] );

	return $bad;
}

/**
 * Determine whether the client at a given source IP is likely to be able to
 * access the wiki via HTTPS.
 *
 * @param string $ip The IPv4/6 address in the normal human-readable form
 * @return bool
 */
function wfCanIPUseHTTPS( $ip ) {
	$canDo = true;
	Hooks::run( 'CanIPUseHTTPS', [ $ip, &$canDo ] );
	return !!$canDo;
}

/**
 * Determine input string is represents as infinity
 *
 * @param string $str The string to determine
 * @return bool
 * @since 1.25
 */
function wfIsInfinity( $str ) {
	// These are hardcoded elsewhere in MediaWiki (e.g. mediawiki.special.block.js).
	$infinityValues = [ 'infinite', 'indefinite', 'infinity', 'never' ];
	return in_array( $str, $infinityValues );
}

/**
 * Returns true if these thumbnail parameters match one that MediaWiki
 * requests from file description pages and/or parser output.
 *
 * $params is considered non-standard if they involve a non-standard
 * width or any non-default parameters aside from width and page number.
 * The number of possible files with standard parameters is far less than
 * that of all combinations; rate-limiting for them can thus be more generious.
 *
 * @param File $file
 * @param array $params
 * @return bool
 * @since 1.24 Moved from thumb.php to GlobalFunctions in 1.25
 */
function wfThumbIsStandard( File $file, array $params ) {
	global $wgThumbLimits, $wgImageLimits, $wgResponsiveImages;

	$multipliers = [ 1 ];
	if ( $wgResponsiveImages ) {
		// These available sizes are hardcoded currently elsewhere in MediaWiki.
		// @see Linker::processResponsiveImages
		$multipliers[] = 1.5;
		$multipliers[] = 2;
	}

	$handler = $file->getHandler();
	if ( !$handler || !isset( $params['width'] ) ) {
		return false;
	}

	$basicParams = [];
	if ( isset( $params['page'] ) ) {
		$basicParams['page'] = $params['page'];
	}

	$thumbLimits = [];
	$imageLimits = [];
	// Expand limits to account for multipliers
	foreach ( $multipliers as $multiplier ) {
		$thumbLimits = array_merge( $thumbLimits, array_map(
			function ( $width ) use ( $multiplier ) {
				return round( $width * $multiplier );
			}, $wgThumbLimits )
		);
		$imageLimits = array_merge( $imageLimits, array_map(
			function ( $pair ) use ( $multiplier ) {
				return [
					round( $pair[0] * $multiplier ),
					round( $pair[1] * $multiplier ),
				];
			}, $wgImageLimits )
		);
	}

	// Check if the width matches one of $wgThumbLimits
	if ( in_array( $params['width'], $thumbLimits ) ) {
		$normalParams = $basicParams + [ 'width' => $params['width'] ];
		// Append any default values to the map (e.g. "lossy", "lossless", ...)
		$handler->normaliseParams( $file, $normalParams );
	} else {
		// If not, then check if the width matchs one of $wgImageLimits
		$match = false;
		foreach ( $imageLimits as $pair ) {
			$normalParams = $basicParams + [ 'width' => $pair[0], 'height' => $pair[1] ];
			// Decide whether the thumbnail should be scaled on width or height.
			// Also append any default values to the map (e.g. "lossy", "lossless", ...)
			$handler->normaliseParams( $file, $normalParams );
			// Check if this standard thumbnail size maps to the given width
			if ( $normalParams['width'] == $params['width'] ) {
				$match = true;
				break;
			}
		}
		if ( !$match ) {
			return false; // not standard for description pages
		}
	}

	// Check that the given values for non-page, non-width, params are just defaults
	foreach ( $params as $key => $value ) {
		if ( !isset( $normalParams[$key] ) || $normalParams[$key] != $value ) {
			return false;
		}
	}

	return true;
}

/**
 * Merges two (possibly) 2 dimensional arrays into the target array ($baseArray).
 *
 * Values that exist in both values will be combined with += (all values of the array
 * of $newValues will be added to the values of the array of $baseArray, while values,
 * that exists in both, the value of $baseArray will be used).
 *
 * @param array $baseArray The array where you want to add the values of $newValues to
 * @param array $newValues An array with new values
 * @return array The combined array
 * @since 1.26
 */
function wfArrayPlus2d( array $baseArray, array $newValues ) {
	// First merge items that are in both arrays
	foreach ( $baseArray as $name => &$groupVal ) {
		if ( isset( $newValues[$name] ) ) {
			$groupVal += $newValues[$name];
		}
	}
	// Now add items that didn't exist yet
	$baseArray += $newValues;

	return $baseArray;
}

/**
 * Get system resource usage of current request context.
 * Invokes the getrusage(2) system call, requesting RUSAGE_SELF if on PHP5
 * or RUSAGE_THREAD if on HHVM. Returns false if getrusage is not available.
 *
 * @since 1.24
 * @return array|bool Resource usage data or false if no data available.
 */
function wfGetRusage() {
	if ( !function_exists( 'getrusage' ) ) {
		return false;
	} elseif ( defined( 'HHVM_VERSION' ) && PHP_OS === 'Linux' ) {
		return getrusage( 2 /* RUSAGE_THREAD */ );
	} else {
		return getrusage( 0 /* RUSAGE_SELF */ );
	}
}
