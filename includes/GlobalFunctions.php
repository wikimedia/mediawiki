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

use MediaWiki\Debug\MWDebug;
use MediaWiki\Exception\ProcOpenError;
use MediaWiki\FileRepo\File\File;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\MediaWikiServices;
use MediaWiki\Message\Message;
use MediaWiki\Registration\ExtensionRegistry;
use MediaWiki\Request\WebRequest;
use MediaWiki\Shell\Shell;
use MediaWiki\Title\Title;
use MediaWiki\Utils\UrlUtils;
use Wikimedia\AtEase\AtEase;
use Wikimedia\FileBackend\FileBackend;
use Wikimedia\FileBackend\FSFile\TempFSFile;
use Wikimedia\Http\HttpStatus;
use Wikimedia\Message\MessageParam;
use Wikimedia\Message\MessageSpecifier;
use Wikimedia\ParamValidator\TypeDef\ExpiryDef;
use Wikimedia\RequestTimeout\RequestTimeout;
use Wikimedia\Timestamp\ConvertibleTimestamp;

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
 * Like array_diff( $arr1, $arr2 ) except that it works with two-dimensional arrays.
 * @deprecated since 1.43 Use StatusValue::merge() instead
 * @param string[]|array[] $arr1
 * @param string[]|array[] $arr2
 * @return array
 */
function wfArrayDiff2( $arr1, $arr2 ) {
	wfDeprecated( __FUNCTION__, '1.43' );
	/**
	 * @param string|array $a
	 * @param string|array $b
	 */
	$comparator = static function ( $a, $b ): int {
		if ( is_string( $a ) && is_string( $b ) ) {
			return strcmp( $a, $b );
		}
		if ( !is_array( $a ) && !is_array( $b ) ) {
			throw new InvalidArgumentException(
				'This function assumes that array elements are all strings or all arrays'
			);
		}
		if ( count( $a ) !== count( $b ) ) {
			return count( $a ) <=> count( $b );
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
	};
	return array_udiff( $arr1, $arr2, $comparator );
}

/**
 * Merge arrays in the style of PermissionManager::getPermissionErrors, with duplicate removal
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
 * @deprecated since 1.43 Use StatusValue::merge() instead
 * @param array[] ...$args
 * @return array
 */
function wfMergeErrorArrays( ...$args ) {
	wfDeprecated( __FUNCTION__, '1.43' );
	$out = [];
	foreach ( $args as $errors ) {
		foreach ( $errors as $params ) {
			$originalParams = $params;
			if ( $params[0] instanceof MessageSpecifier ) {
				$params = [ $params[0]->getKey(), ...$params[0]->getParams() ];
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
 * Insert an array into another array after the specified key. If the key is
 * not present in the input array, it is returned without modification.
 *
 * @param array $array
 * @param array $insert The array to insert.
 * @param mixed $after The key to insert after.
 * @return array
 */
function wfArrayInsertAfter( array $array, array $insert, $after ) {
	// Find the offset of the element to insert after.
	$keys = array_keys( $array );
	$offsetByKey = array_flip( $keys );

	if ( !\array_key_exists( $after, $offsetByKey ) ) {
		return $array;
	}
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
 * @phpcs:ignore MediaWiki.Commenting.FunctionComment.ObjectTypeHintParam
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
 * Get a random decimal value in the domain of [0, 1), in a way
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

	if ( $s === null ) {
		// Reset $needle for testing.
		$needle = null;
		return '';
	}

	if ( $needle === null ) {
		$needle = [ '%3B', '%40', '%24', '%21', '%2A', '%28', '%29', '%2C', '%2F', '%7E' ];
		if ( !isset( $_SERVER['SERVER_SOFTWARE'] ) ||
			!str_contains( $_SERVER['SERVER_SOFTWARE'], 'Microsoft-IIS/7' )
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
	if ( $array2 !== null ) {
		$array1 += $array2;
	}

	$cgi = '';
	foreach ( $array1 as $key => $value ) {
		if ( $value !== null && $value !== false ) {
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
			[ $key, $value ] = explode( '=', $bit );
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
			if ( isset( $ret[$key] ) && is_array( $ret[$key] ) ) {
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
 * @param string|array $query String or associative array
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
		if ( strpos( $url, '?' ) === false ) {
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
 * @deprecated since 1.43; get a UrlUtils from services, or construct your own
 * @internal
 * @return UrlUtils from services if initialized, otherwise make one from globals
 */
function wfGetUrlUtils(): UrlUtils {
	global $wgServer, $wgCanonicalServer, $wgInternalServer, $wgRequest, $wgHttpsPort,
		$wgUrlProtocols;

	if ( MediaWikiServices::hasInstance() ) {
		$services = MediaWikiServices::getInstance();
		if ( $services->hasService( 'UrlUtils' ) ) {
			return $services->getUrlUtils();
		}
	}

	return new UrlUtils( [
		// UrlUtils throws if the relevant $wg(|Canonical|Internal) variable is null, but the old
		// implementations implicitly converted it to an empty string (presumably by mistake).
		// Preserve the old behavior for compatibility.
		UrlUtils::SERVER => $wgServer ?? '',
		UrlUtils::CANONICAL_SERVER => $wgCanonicalServer ?? '',
		UrlUtils::INTERNAL_SERVER => $wgInternalServer ?? '',
		UrlUtils::FALLBACK_PROTOCOL => $wgRequest ? $wgRequest->getProtocol()
			: WebRequest::detectProtocol(),
		UrlUtils::HTTPS_PORT => $wgHttpsPort,
		UrlUtils::VALID_PROTOCOLS => $wgUrlProtocols,
	] );
}

/**
 * Expand a potentially local URL to a fully-qualified URL using $wgServer
 * (or one of its alternatives).
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
 * If $url specifies a protocol, or $url is domain-relative and $wgServer
 * specifies a protocol, PROTO_HTTP, PROTO_HTTPS, PROTO_RELATIVE and
 * PROTO_CURRENT do not change that.
 *
 * Parent references (/../) in the path are resolved (as in UrlUtils::removeDotSegments()).
 *
 * @deprecated since 1.39, use UrlUtils::expand(); hard-deprecated since 1.45
 * @param string $url An URL; can be absolute (e.g. http://example.com/foo/bar),
 *    protocol-relative (//example.com/foo/bar) or domain-relative (/foo/bar).
 * @param string|int|null $defaultProto One of the PROTO_* constants, as described above.
 * @return string|false Fully-qualified URL, current-path-relative URL or false if
 *    no valid URL can be constructed
 */
function wfExpandUrl( $url, $defaultProto = PROTO_CURRENT ) {
	wfDeprecated( __FUNCTION__, '1.39' );

	return wfGetUrlUtils()->expand( (string)$url, $defaultProto ) ?? false;
}

/**
 * Get the wiki's "server", i.e. the protocol and host part of the URL, with a
 * protocol specified using a PROTO_* constant as in wfExpandUrl()
 *
 * @deprecated since 1.39, use UrlUtils::getServer(); hard-deprecated since 1.43
 * @since 1.32
 * @param string|int|null $proto One of the PROTO_* constants.
 * @return string The URL
 */
function wfGetServerUrl( $proto ) {
	wfDeprecated( __FUNCTION__, '1.39' );

	return wfGetUrlUtils()->getServer( $proto ) ?? '';
}

/**
 * This function will reassemble a URL parsed with wfParseURL.  This is useful
 * if you need to edit part of a URL and put it back together.
 *
 * This is the basic structure used (brackets contain keys for $urlParts):
 * [scheme][delimiter][user]:[pass]@[host]:[port][path]?[query]#[fragment]
 *
 * @deprecated since 1.39, use UrlUtils::assemble(); hard-deprecated since 1.45
 * @since 1.19
 * @param array $urlParts URL parts, as output from wfParseUrl
 * @return string URL assembled from its component parts
 */
function wfAssembleUrl( $urlParts ) {
	wfDeprecated( __FUNCTION__, '1.39' );

	return UrlUtils::assemble( (array)$urlParts );
}

/**
 * Returns a partial regular expression of recognized URL protocols, e.g. "http:\/\/|https:\/\/"
 *
 * @deprecated since 1.39, use UrlUtils::validProtocols(); hard-deprecated since 1.43
 * @param bool $includeProtocolRelative If false, remove '//' from the returned protocol list.
 *        DO NOT USE this directly, use UrlUtils::validAbsoluteProtocols() instead
 * @return string
 */
function wfUrlProtocols( $includeProtocolRelative = true ) {
	wfDeprecated( __FUNCTION__, '1.39' );

	return $includeProtocolRelative ? wfGetUrlUtils()->validProtocols() :
		wfGetUrlUtils()->validAbsoluteProtocols();
}

/**
 * Like wfUrlProtocols(), but excludes '//' from the protocol list. Use this if
 * you need a regex that matches all URL protocols but does not match protocol-
 * relative URLs
 * @deprecated since 1.39, use UrlUtils::validAbsoluteProtocols(); hard-deprecated since 1.44
 * @return string
 */
function wfUrlProtocolsWithoutProtRel() {
	wfDeprecated( __FUNCTION__, '1.39' );

	return wfGetUrlUtils()->validAbsoluteProtocols();
}

/**
 * parse_url() work-alike, but non-broken.  Differences:
 *
 * 1) Handles protocols that don't use :// (e.g., mailto: and news:, as well as
 *    protocol-relative URLs) correctly.
 * 2) Adds a "delimiter" element to the array (see (2)).
 * 3) Verifies that the protocol is on the $wgUrlProtocols allowed list.
 * 4) Rejects some invalid URLs that parse_url doesn't, e.g. the empty string or URLs starting with
 *    a line feed character.
 *
 * @deprecated since 1.39, use UrlUtils::parse(); hard-deprecated since 1.45
 * @param string $url A URL to parse
 * @return string[]|false Bits of the URL in an associative array, or false on failure.
 *   Possible fields:
 *   - scheme: URI scheme (protocol), e.g. 'http', 'mailto'. Lowercase, always present, but can
 *       be an empty string for protocol-relative URLs.
 *   - delimiter: either '://', ':' or '//'. Always present.
 *   - host: domain name / IP. Always present, but could be an empty string, e.g. for file: URLs.
 *   - port: port number. Will be missing when port is not explicitly specified.
 *   - user: user name, e.g. for HTTP Basic auth URLs such as http://user:pass@example.com/
 *       Missing when there is no username.
 *   - pass: password, same as above.
 *   - path: path including the leading /. Will be missing when empty (e.g. 'http://example.com')
 *   - query: query string (as a string; see wfCgiToArray() for parsing it), can be missing.
 *   - fragment: the part after #, can be missing.
 */
function wfParseUrl( $url ) {
	wfDeprecated( __FUNCTION__, '1.39' );

	return wfGetUrlUtils()->parse( (string)$url ) ?? false;
}

/**
 * Take a URL, make sure it's expanded to fully qualified, and replace any
 * encoded non-ASCII Unicode characters with their UTF-8 original forms
 * for more compact display and legibility for local audiences.
 *
 * @deprecated since 1.39, use UrlUtils::expandIRI(); hard-deprecated since 1.43
 * @param string $url
 * @return string
 */
function wfExpandIRI( $url ) {
	wfDeprecated( __FUNCTION__, '1.39' );

	return wfGetUrlUtils()->expandIRI( (string)$url ) ?? '';
}

/**
 * Check whether a given URL has a domain that occurs in a given set of domains
 *
 * @deprecated since 1.39, use UrlUtils::matchesDomainList(); hard-deprecated since 1.44
 * @param string $url
 * @param array $domains Array of domains (strings)
 * @return bool True if the host part of $url ends in one of the strings in $domains
 */
function wfMatchesDomainList( $url, $domains ) {
	wfDeprecated( __FUNCTION__, '1.39' );

	return wfGetUrlUtils()->matchesDomainList( (string)$url, (array)$domains );
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

	if ( !$wgDebugRawPage && wfIsDebugRawPage() ) {
		return;
	}

	$text = trim( $text );

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
	// Check for raw action using $_GET not $wgRequest, since the latter might not be initialised yet
	// phpcs:ignore MediaWiki.Usage.SuperGlobalsUsage.SuperGlobals
	if ( ( isset( $_GET['action'] ) && $_GET['action'] == 'raw' )
		|| MW_ENTRY_POINT === 'load'
	) {
		$cache = true;
	} else {
		$cache = false;
	}
	return $cache;
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
 * Logs a warning that a deprecated feature was used.
 *
 * To write a custom deprecation message, use wfDeprecatedMsg() instead.
 *
 * @param string $function Feature that is deprecated.
 * @param string|false $version Version of MediaWiki that the feature
 *  was deprecated in (Added in 1.19).
 * @param string|bool $component Component to which the feature belongs.
 *  If false, it is assumed the function is in MediaWiki core (Added in 1.19).
 * @param int $callerOffset How far up the call stack is the original
 *  caller. 2 = function that called the function that called
 *  wfDeprecated (Added in 1.20).
 * @throws InvalidArgumentException If the MediaWiki version
 *  number specified by $version is neither a string nor false.
 */
function wfDeprecated( $function, $version = false, $component = false, $callerOffset = 2 ) {
	if ( !is_string( $version ) && $version !== false ) {
		throw new InvalidArgumentException(
			"MediaWiki version must either be a string or false. " .
			"Example valid version: '1.33'"
		);
	}

	MWDebug::deprecated( $function, $version, $component, $callerOffset + 1 );
}

/**
 * Log a deprecation warning with arbitrary message text. A caller
 * description will be appended. If the message has already been sent for
 * this caller, it won't be sent again.
 *
 * Although there are component and version parameters, they are not
 * automatically appended to the message. The message text should include
 * information about when the thing was deprecated. The component and version
 * are just used to implement $wgDeprecationReleaseLimit.
 *
 * @since 1.35
 * @param string $msg The message
 * @param string|false $version Version of MediaWiki that the function
 *  was deprecated in.
 * @param string|bool $component Component to which the function belongs.
 *  If false, it is assumed the function is in MediaWiki core.
 * @param int|false $callerOffset How far up the call stack is the original
 *  caller. 2 = function that called the function that called us. If false,
 *  the caller description will not be appended.
 */
function wfDeprecatedMsg( $msg, $version = false, $component = false, $callerOffset = 2 ) {
	MWDebug::deprecatedMsg( $msg, $version, $component,
		$callerOffset === false ? false : $callerOffset + 1 );
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
 * This is the function for getting translated interface messages.
 *
 * @see Message class for documentation how to use them.
 * @see https://www.mediawiki.org/wiki/Manual:Messages_API
 *
 * This function replaces all old wfMsg* functions.
 *
 * When the MessageSpecifier object is an instance of Message, a clone of the object is returned.
 * This is unlike the `new Message( … )` constructor, which returns a new object constructed from
 * scratch with the same key. This difference is mostly relevant when the passed object is an
 * instance of a subclass like RawMessage or ApiMessage.
 *
 * @param string|string[]|MessageSpecifier $key Message key, or array of keys, or a MessageSpecifier
 * @param MessageParam|MessageSpecifier|string|int|float|list<MessageParam|MessageSpecifier|string|int|float> ...$params
 *   See Message::params()
 * @return Message
 *
 * @since 1.17
 *
 * @see Message::__construct
 */
function wfMessage( $key, ...$params ) {
	if ( is_array( $key ) ) {
		// Fallback keys are not allowed in message specifiers
		$message = wfMessageFallback( ...$key );
	} else {
		$message = Message::newFromSpecifier( $key );
	}

	// We call Message::params() to reduce code duplication
	if ( $params ) {
		$message->params( ...$params );
	}

	return $message;
}

/**
 * This function accepts multiple message keys and returns a message instance
 * for the first message which is non-empty. If all messages are empty then an
 * instance of the last message key is returned.
 *
 * @param string ...$keys Message keys
 * @return Message
 *
 * @since 1.18
 *
 * @see Message::newFallbackSequence
 */
function wfMessageFallback( ...$keys ) {
	return Message::newFallbackSequence( ...$keys );
}

/**
 * Replace message parameter keys on the given formatted output.
 *
 * @param string $message
 * @param array $args
 * @return string
 * @internal
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
 * Get host name of the current machine, for use in error reporting.
 *
 * This helps to know which machine in a data center generated the
 * current page.
 *
 * @return string
 */
function wfHostname() {
	// Hostname overriding
	global $wgOverrideHostname;
	if ( $wgOverrideHostname !== false ) {
		return $wgOverrideHostname;
	}

	return php_uname( 'n' ) ?: 'unknown';
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

	if ( $disabled === null ) {
		$disabled = !function_exists( 'debug_backtrace' );
		if ( $disabled ) {
			wfDebug( "debug_backtrace() is disabled" );
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
 *   Defaults to true if MW_ENTRY_POINT is 'cli', otherwise false.
 * @return string
 * @since 1.25 Supports $raw parameter.
 */
function wfBacktrace( $raw = null ) {
	$raw ??= MW_ENTRY_POINT === 'cli';
	if ( $raw ) {
		$frameFormat = "%s line %s calls %s()\n";
		$traceFormat = "%s";
	} else {
		$frameFormat = "<li>%s line %s calls %s()</li>\n";
		$traceFormat = "<ul>\n%s</ul>\n";
	}

	$frames = array_map( static function ( $frame ) use ( $frameFormat ) {
		$file = !empty( $frame['file'] ) ? basename( $frame['file'] ) : '-';
		$line = $frame['line'] ?? '-';
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
 * The format will be the same as for {@see wfFormatStackFrame()}.
 * @param int $level
 * @return string function name or 'unknown'
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
 * @param int|false $limit The maximum depth of the stack frame to return, or false for the entire stack.
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
 * Typically, the returned value will be in one of these formats:
 * - method
 * - Fully\Qualified\method
 * - Fully\Qualified\Class->method
 * - Fully\Qualified\Class::method
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
			# @todo FIXME: We may want to disallow some broken browsers
			$m = [];
			if ( preg_match(
					'/\bgzip(?:;(q)=([0-9]+(?:\.[0-9]+)))?\b/',
					$_SERVER['HTTP_ACCEPT_ENCODING'],
					$m
				)
			) {
				if ( isset( $m[2] ) && ( $m[1] == 'q' ) && ( $m[2] == 0 ) ) {
					return $result;
				}
				wfDebug( "wfClientAcceptsGzip: client accepts gzip." );
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
 * @param string|null|false $input Text to be escaped
 * @param-taint $input escapes_html
 * @return string
 */
function wfEscapeWikiText( $input ): string {
	global $wgEnableMagicLinks;
	static $repl = null, $repl2 = null, $repl3 = null, $repl4 = null;
	if ( $repl === null || defined( 'MW_PHPUNIT_TEST' ) ) {
		// Tests depend upon being able to change $wgEnableMagicLinks, so don't cache
		// in those situations
		$repl = [
			'"' => '&#34;', '&' => '&#38;', "'" => '&#39;', '<' => '&#60;',
			'=' => '&#61;', '>' => '&#62;', '[' => '&#91;', ']' => '&#93;',
			'{' => '&#123;', '|' => '&#124;', '}' => '&#125;',
			';' => '&#59;', // a token inside language converter brackets
			'!!' => '&#33;!', // a token inside table context
			"\n!" => "\n&#33;", "\r!" => "\r&#33;", // a token inside table context
			"\n#" => "\n&#35;", "\r#" => "\r&#35;",
			"\n*" => "\n&#42;", "\r*" => "\r&#42;",
			"\n:" => "\n&#58;", "\r:" => "\r&#58;",
			"\n " => "\n&#32;", "\r " => "\r&#32;",
			"\n\n" => "\n&#10;", "\r\n" => "&#13;\n",
			"\n\r" => "\n&#13;", "\r\r" => "\r&#13;",
			"\n\t" => "\n&#9;", "\r\t" => "\r&#9;", // "\n\t\n" is treated like "\n\n"
			"\n----" => "\n&#45;---", "\r----" => "\r&#45;---",
			'__' => '_&#95;', '://' => '&#58;//',
			'~~~' => '~~&#126;', // protect from PST, just to be safe(r)
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
		// Additionally escape the following characters at the beginning of the
		// string, in case they merge to form tokens when spliced into a
		// string.  Tokens like -{ {{ [[ {| etc are already escaped because
		// the second character is escaped above, but the following tokens
		// are handled here: |+ |- __FOO__ ~~~
		$repl3 = [
			'+' => '&#43;', '-' => '&#45;', '_' => '&#95;', '~' => '&#126;',
		];
		// Similarly, protect the following characters at the end of the
		// string, which could turn form the start of `__FOO__` or `~~~~`
		// A trailing newline could also form the unintended start of a
		// paragraph break if it is glued to a newline in the following
		// context.
		$repl4 = [
			'_' => '&#95;', '~' => '&#126;',
			"\n" => "&#10;", "\r" => "&#13;",
			"\t" => "&#9;", // "\n\t\n" is treated like "\n\n"
		];

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
	// Tell phan that $repl2, $repl3 and $repl4 will also be non-null here
	'@phan-var string $repl2';
	'@phan-var string $repl3';
	'@phan-var string $repl4';
	// This will also stringify input in case it's not a string
	$text = substr( strtr( "\n$input", $repl ), 1 );
	if ( $text === '' ) {
		return $text;
	}
	$first = strtr( $text[0], $repl3 ); // protect first character
	if ( strlen( $text ) > 1 ) {
		$text = $first . substr( $text, 1, -1 ) .
		strtr( substr( $text, -1 ), $repl4 ); // protect last character
	} else {
		// special case for single-character strings
		$text = strtr( $first, $repl4 ); // protect last character
	}
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
	if ( $source !== null || $force ) {
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
	if ( $state !== null ) {
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
	if ( headers_sent() || $wgOut === null || !is_object( $wgOut ) ) {
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

	\MediaWiki\Request\HeaderCallback::warnIfHeadersSent();
	header( 'Content-type: text/html; charset=utf-8' );
	ob_start();
	print '<!DOCTYPE html>' .
		'<html><head><title>' .
		htmlspecialchars( $label ) .
		'</title><meta name="color-scheme" content="light dark" /></head><body><h1>' .
		htmlspecialchars( $label ) .
		'</h1><p>' .
		nl2br( htmlspecialchars( $desc ) ) .
		"</p></body></html>\n";
	header( 'Content-Length: ' . ob_get_length() );
	ob_end_flush();
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
 * is left. This should be used for HTTP 304 responses, where you need to
 * preserve the Content-Encoding header of the real result, but
 * also need to suppress the output of ob_gzhandler to keep to spec
 * and avoid breaking Firefox in rare cases where the headers and
 * body are broken over two packets.
 *
 * Note that some PHP configuration options may add output buffer
 * layers which cannot be removed; these are left in place.
 *
 * @param bool $resetGzipEncoding
 */
function wfResetOutputBuffers( $resetGzipEncoding = true ) {
	// phpcs:ignore Generic.CodeAnalysis.AssignmentInCondition.FoundInWhileCondition
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
		if ( $status['name'] === 'MediaWikiIntegrationTestCase::wfResetOutputBuffersBarrier' ) {
			// Unit testing barrier to prevent this function from breaking PHPUnit.
			break;
		}
		if ( !ob_end_clean() ) {
			// Could not remove output buffer handler; abort now
			// to avoid getting in some kind of infinite loop.
			break;
		}
		if ( $resetGzipEncoding && $status['name'] == 'ob_gzhandler' ) {
			// Reset the 'Content-Encoding' field set by this handler
			// so we can start fresh.
			header_remove( 'Content-Encoding' );
			break;
		}
	}
}

/**
 * Get a timestamp string in one of various formats
 *
 * @param mixed $outputtype Output format, one of the TS_* constants. Defaults to
 *   Unix timestamp.
 * @param mixed $ts A timestamp in any supported format. The
 *   function will autodetect which format is supplied and act accordingly. Use 0 or
 *   omit to use current time
 * @return string|false The date in the specified format, or false on error.
 */
function wfTimestamp( $outputtype = TS_UNIX, $ts = 0 ) {
	$ret = ConvertibleTimestamp::convert( $outputtype, $ts );
	if ( $ret === false ) {
		wfDebug( "wfTimestamp() fed bogus time value: TYPE=$outputtype; VALUE=$ts" );
	}
	return $ret;
}

/**
 * Return a formatted timestamp, or null if input is null.
 * For dealing with nullable timestamp columns in the database.
 *
 * @param mixed $outputtype
 * @param mixed|null $ts
 * @return string|false|null Null if called with null, otherwise the result of wfTimestamp()
 */
function wfTimestampOrNull( $outputtype = TS_UNIX, $ts = null ) {
	if ( $ts === null ) {
		return null;
	} else {
		return wfTimestamp( $outputtype, $ts );
	}
}

/**
 * Convenience function; returns MediaWiki timestamp for the present time.
 *
 * @return string TS_MW timestamp
 */
function wfTimestampNow() {
	return ConvertibleTimestamp::now( TS_MW );
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
 * @param string $dir Full path to directory to create. Callers should make sure this is not a storage path.
 * @param int|null $mode Chmod value to use, default is $wgDirectoryMode
 * @param string|null $caller Optional caller param for debugging.
 * @return bool
 */
function wfMkdirParents( $dir, $mode = null, $caller = null ) {
	global $wgDirectoryMode;

	if ( FileBackend::isStoragePath( $dir ) ) {
		throw new LogicException( __FUNCTION__ . " given storage path '$dir'." );
	}
	if ( $caller !== null ) {
		wfDebug( "$caller: called wfMkdirParents($dir)" );
	}
	if ( strval( $dir ) === '' ) {
		return true;
	}

	$dir = str_replace( [ '\\', '/' ], DIRECTORY_SEPARATOR, $dir );
	$mode ??= $wgDirectoryMode;

	// Turn off the normal warning, we're doing our own below
	// PHP doesn't include the path in its warning message, so we add our own to aid in diagnosis.
	//
	// Repeat existence check if creation failed so that we silently recover in case of
	// a race condition where another request created it since the first check.
	//
	// phpcs:ignore Generic.PHP.NoSilencedErrors.Discouraged
	$ok = is_dir( $dir ) || @mkdir( $dir, $mode, true ) || is_dir( $dir );
	if ( !$ok ) {
		trigger_error( sprintf( "failed to mkdir \"%s\" mode 0%o", $dir, $mode ), E_USER_WARNING );
	}

	return $ok;
}

/**
 * Remove a directory and all its content.
 * Does not hide error.
 * @param string $dir
 */
function wfRecursiveRemoveDir( $dir ) {
	// taken from https://www.php.net/manual/en/function.rmdir.php#98622
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
		rmdir( $dir );
	}
}

/**
 * @param float|int $nr The number to format
 * @param int $acc The number of digits after the decimal point, default 2
 * @param bool $round Whether or not to round the value, default true
 * @return string
 */
function wfPercent( $nr, int $acc = 2, bool $round = true ) {
	$accForFormat = $acc >= 0 ? $acc : 0;
	$ret = sprintf( "%.{$accForFormat}f", $nr );
	return $round ? round( (float)$ret, $acc ) . '%' : "$ret%";
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
 * Locale-independent version of escapeshellarg()
 *
 * Originally, this fixed the incorrect use of single quotes on Windows
 * (https://bugs.php.net/bug.php?id=26285) and the locale problems on Linux in
 * PHP 5.2.6+ (https://bugs.php.net/bug.php?id=54391). The second bug is still
 * open as of 2021.
 *
 * @param string|string[] ...$args strings to escape and glue together,
 *  or a single array of strings parameter
 * @return string
 * @deprecated since 1.30 use MediaWiki\Shell\Shell::escape()
 */
function wfEscapeShellArg( ...$args ) {
	return Shell::escape( ...$args );
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
 * @phan-param array{duplicateStderr?:bool,profileMethod?:string} $options
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
	$profileMethod = $options['profileMethod'] ?? wfGetCaller();

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
	} catch ( ProcOpenError ) {
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
 * @phan-param array{php?:string,wrapper?:string} $options
 * @return string
 */
function wfShellWikiCmd( $script, array $parameters = [], array $options = [] ) {
	global $wgPhpCli;
	// Give site config file a chance to run the script in a wrapper.
	// The caller may likely want to call wfBasename() on $script.
	( new HookRunner( MediaWikiServices::getInstance()->getHookContainer() ) )
		->onWfShellWikiCmd( $script, $parameters, $options );
	$cmd = [ $options['php'] ?? $wgPhpCli ];
	if ( isset( $options['wrapper'] ) ) {
		$cmd[] = $options['wrapper'];
	}
	$cmd[] = $script;
	// Escape each parameter for shell
	return Shell::escape( array_merge( $cmd, $parameters ) );
}

/**
 * wfMerge attempts to merge differences between three texts.
 *
 * @param string $old Common base revision
 * @param string $mine The edit we wish to store but which potentially conflicts with another edit
 *     which happened since we started editing.
 * @param string $yours The most recent stored revision of the article. Note that "mine" and "yours"
 *     might have another meaning depending on the specific use case.
 * @param string|null &$simplisticMergeAttempt Automatically merged text, with overlapping edits
 *     falling back to "my" text.
 * @param string|null &$mergeLeftovers Optional out parameter containing an "ed" script with the
 *     remaining bits of "your" text that could not be merged into $simplisticMergeAttempt.
 *     The "ed" file format is documented here:
 *     https://www.gnu.org/software/diffutils/manual/html_node/Detailed-ed.html
 * @return bool true for a clean merge and false for failure or a conflict.
 */
function wfMerge(
	string $old,
	string $mine,
	string $yours,
	?string &$simplisticMergeAttempt,
	?string &$mergeLeftovers = null
): bool {
	global $wgDiff3;

	# This check may also protect against code injection in
	# case of broken installations.
	AtEase::suppressWarnings();
	$haveDiff3 = $wgDiff3 && file_exists( $wgDiff3 );
	AtEase::restoreWarnings();

	if ( !$haveDiff3 ) {
		wfDebug( "diff3 not found" );
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
	$cmd = Shell::escape( $wgDiff3, '--text', '--overlap-only', $mytextName,
		$oldtextName, $yourtextName );
	$handle = popen( $cmd, 'r' );

	$mergeLeftovers = '';
	do {
		$data = fread( $handle, 8192 );
		if ( $data === false || $data === '' ) {
			break;
		}
		$mergeLeftovers .= $data;
	} while ( true );
	pclose( $handle );

	$conflict = $mergeLeftovers !== '';

	# Merge differences automatically where possible, preferring "my" text for conflicts.
	$cmd = Shell::escape( $wgDiff3, '--text', '--ed', '--merge', $mytextName,
		$oldtextName, $yourtextName );
	$handle = popen( $cmd, 'r' );
	$simplisticMergeAttempt = '';
	do {
		$data = fread( $handle, 8192 );
		if ( $data === false || $data === '' ) {
			break;
		}
		$simplisticMergeAttempt .= $data;
	} while ( true );
	pclose( $handle );
	unlink( $mytextName );
	unlink( $oldtextName );
	unlink( $yourtextName );

	if ( $simplisticMergeAttempt === '' && $old !== '' && !$conflict ) {
		wfDebug( "Unexpected null result from diff3. Command: $cmd" );
		$conflict = true;
	}
	return !$conflict;
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

	$pieces[] = wfBaseName( $path );

	return implode( DIRECTORY_SEPARATOR, $pieces );
}

/**
 * Get the URL path to a MediaWiki entry point.
 *
 * This is a wrapper to respect $wgScript and $wgLoadScript overrides.
 *
 * @see MW_ENTRY_POINT
 * @param string $script Name of entrypoint, without `.php` extension.
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
 * Raise PHP's memory limit (if needed).
 *
 * @internal For use by Setup.php
 * @param int $newLimit
 */
function wfMemoryLimit( $newLimit ) {
	$oldLimit = wfShorthandToInteger( ini_get( 'memory_limit' ) );
	// If the INI config is already unlimited, there is nothing larger
	if ( $oldLimit != -1 ) {
		$newLimit = wfShorthandToInteger( (string)$newLimit );
		if ( $newLimit == -1 ) {
			wfDebug( "Removing PHP's memory limit" );
			AtEase::suppressWarnings();
			ini_set( 'memory_limit', $newLimit );
			AtEase::restoreWarnings();
		} elseif ( $newLimit > $oldLimit ) {
			wfDebug( "Raising PHP's memory limit to $newLimit bytes" );
			AtEase::suppressWarnings();
			ini_set( 'memory_limit', $newLimit );
			AtEase::restoreWarnings();
		}
	}
}

/**
 * Raise the request time limit to $wgTransactionalTimeLimit
 *
 * @return int Prior time limit
 * @since 1.26
 */
function wfTransactionalTimeLimit() {
	global $wgTransactionalTimeLimit;

	$timeout = RequestTimeout::singleton();
	$timeLimit = $timeout->getWallTimeLimit();
	if ( $timeLimit !== INF ) {
		// RequestTimeout library is active
		if ( $wgTransactionalTimeLimit > $timeLimit ) {
			$timeout->setWallTimeLimit( $wgTransactionalTimeLimit );
		}
	} else {
		// Fallback case, likely $wgRequestTimeLimit === null
		$timeLimit = (int)ini_get( 'max_execution_time' );
		// Note that CLI scripts use 0
		if ( $timeLimit > 0 && $wgTransactionalTimeLimit > $timeLimit ) {
			$timeout->setWallTimeLimit( $wgTransactionalTimeLimit );
		}
	}
	ignore_user_abort( true ); // ignore client disconnects

	return $timeLimit;
}

/**
 * Converts shorthand byte notation to integer form
 *
 * @param null|string $string
 * @param int $default Returned if $string is empty
 * @return int
 */
function wfShorthandToInteger( ?string $string = '', int $default = -1 ): int {
	$string = trim( $string ?? '' );
	if ( $string === '' ) {
		return $default;
	}
	$last = substr( $string, -1 );
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
 * Determine input string is represents as infinity
 *
 * @param string $str The string to determine
 * @return bool
 * @since 1.25
 */
function wfIsInfinity( $str ) {
	// The INFINITY_VALS are hardcoded elsewhere in MediaWiki (e.g. mediawiki.special.block.js).
	return in_array( $str, ExpiryDef::INFINITY_VALS );
}

/**
 * Returns true if these thumbnail parameters match one that MediaWiki
 * requests from file description pages and/or parser output.
 *
 * $params is considered non-standard if they involve a non-standard
 * width or any non-default parameters aside from width and page number.
 * The number of possible files with standard parameters is far less than
 * that of all combinations; rate-limiting for them can thus be more generous.
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
			static function ( $width ) use ( $multiplier ) {
				return round( $width * $multiplier );
			}, $wgThumbLimits )
		);
		$imageLimits = array_merge( $imageLimits, array_map(
			static function ( $pair ) use ( $multiplier ) {
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
		// If not, then check if the width matches one of $wgImageLimits
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
