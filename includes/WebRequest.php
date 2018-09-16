<?php
/**
 * Deal with importing all those nasty globals and things
 *
 * Copyright © 2003 Brion Vibber <brion@pobox.com>
 * https://www.mediawiki.org/
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

use MediaWiki\MediaWikiServices;
use MediaWiki\Session\Session;
use MediaWiki\Session\SessionId;
use MediaWiki\Session\SessionManager;

// The point of this class is to be a wrapper around super globals
// phpcs:disable MediaWiki.Usage.SuperGlobalsUsage.SuperGlobals

/**
 * The WebRequest class encapsulates getting at data passed in the
 * URL or via a POSTed form stripping illegal input characters and
 * normalizing Unicode sequences.
 *
 * @ingroup HTTP
 */
class WebRequest {
	protected $data, $headers = [];

	/**
	 * Flag to make WebRequest::getHeader return an array of values.
	 * @since 1.26
	 */
	const GETHEADER_LIST = 1;

	/**
	 * The unique request ID.
	 * @var string
	 */
	private static $reqId;

	/**
	 * Lazy-init response object
	 * @var WebResponse
	 */
	private $response;

	/**
	 * Cached client IP address
	 * @var string
	 */
	private $ip;

	/**
	 * The timestamp of the start of the request, with microsecond precision.
	 * @var float
	 */
	protected $requestTime;

	/**
	 * Cached URL protocol
	 * @var string
	 */
	protected $protocol;

	/**
	 * @var SessionId|null Session ID to use for this
	 *  request. We can't save the session directly due to reference cycles not
	 *  working too well (slow GC in Zend and never collected in HHVM).
	 */
	protected $sessionId = null;

	/** @var bool Whether this HTTP request is "safe" (even if it is an HTTP post) */
	protected $markedAsSafe = false;

	/**
	 * @codeCoverageIgnore
	 */
	public function __construct() {
		$this->requestTime = $_SERVER['REQUEST_TIME_FLOAT'];

		// POST overrides GET data
		// We don't use $_REQUEST here to avoid interference from cookies...
		$this->data = $_POST + $_GET;
	}

	/**
	 * Extract relevant query arguments from the http request uri's path
	 * to be merged with the normal php provided query arguments.
	 * Tries to use the REQUEST_URI data if available and parses it
	 * according to the wiki's configuration looking for any known pattern.
	 *
	 * If the REQUEST_URI is not provided we'll fall back on the PATH_INFO
	 * provided by the server if any and use that to set a 'title' parameter.
	 *
	 * @param string $want If this is not 'all', then the function
	 * will return an empty array if it determines that the URL is
	 * inside a rewrite path.
	 *
	 * @return array Any query arguments found in path matches.
	 */
	public static function getPathInfo( $want = 'all' ) {
		global $wgUsePathInfo;
		// PATH_INFO is mangled due to https://bugs.php.net/bug.php?id=31892
		// And also by Apache 2.x, double slashes are converted to single slashes.
		// So we will use REQUEST_URI if possible.
		$matches = [];
		if ( !empty( $_SERVER['REQUEST_URI'] ) ) {
			// Slurp out the path portion to examine...
			$url = $_SERVER['REQUEST_URI'];
			if ( !preg_match( '!^https?://!', $url ) ) {
				$url = 'http://unused' . $url;
			}
			Wikimedia\suppressWarnings();
			$a = parse_url( $url );
			Wikimedia\restoreWarnings();
			if ( $a ) {
				$path = $a['path'] ?? '';

				global $wgScript;
				if ( $path == $wgScript && $want !== 'all' ) {
					// Script inside a rewrite path?
					// Abort to keep from breaking...
					return $matches;
				}

				$router = new PathRouter;

				// Raw PATH_INFO style
				$router->add( "$wgScript/$1" );

				if ( isset( $_SERVER['SCRIPT_NAME'] )
					&& preg_match( '/\.php/', $_SERVER['SCRIPT_NAME'] )
				) {
					# Check for SCRIPT_NAME, we handle index.php explicitly
					# But we do have some other .php files such as img_auth.php
					# Don't let root article paths clober the parsing for them
					$router->add( $_SERVER['SCRIPT_NAME'] . "/$1" );
				}

				global $wgArticlePath;
				if ( $wgArticlePath ) {
					$router->add( $wgArticlePath );
				}

				global $wgActionPaths;
				if ( $wgActionPaths ) {
					$router->add( $wgActionPaths, [ 'action' => '$key' ] );
				}

				global $wgVariantArticlePath;
				if ( $wgVariantArticlePath ) {
					$router->add( $wgVariantArticlePath,
						[ 'variant' => '$2' ],
						[ '$2' => MediaWikiServices::getInstance()->getContentLanguage()->
						getVariants() ]
					);
				}

				Hooks::run( 'WebRequestPathInfoRouter', [ $router ] );

				$matches = $router->parse( $path );
			}
		} elseif ( $wgUsePathInfo ) {
			if ( isset( $_SERVER['ORIG_PATH_INFO'] ) && $_SERVER['ORIG_PATH_INFO'] != '' ) {
				// Mangled PATH_INFO
				// https://bugs.php.net/bug.php?id=31892
				// Also reported when ini_get('cgi.fix_pathinfo')==false
				$matches['title'] = substr( $_SERVER['ORIG_PATH_INFO'], 1 );

			} elseif ( isset( $_SERVER['PATH_INFO'] ) && $_SERVER['PATH_INFO'] != '' ) {
				// Regular old PATH_INFO yay
				$matches['title'] = substr( $_SERVER['PATH_INFO'], 1 );
			}
		}

		return $matches;
	}

	/**
	 * Work out an appropriate URL prefix containing scheme and host, based on
	 * information detected from $_SERVER
	 *
	 * @return string
	 */
	public static function detectServer() {
		global $wgAssumeProxiesUseDefaultProtocolPorts;

		$proto = self::detectProtocol();
		$stdPort = $proto === 'https' ? 443 : 80;

		$varNames = [ 'HTTP_HOST', 'SERVER_NAME', 'HOSTNAME', 'SERVER_ADDR' ];
		$host = 'localhost';
		$port = $stdPort;
		foreach ( $varNames as $varName ) {
			if ( !isset( $_SERVER[$varName] ) ) {
				continue;
			}

			$parts = IP::splitHostAndPort( $_SERVER[$varName] );
			if ( !$parts ) {
				// Invalid, do not use
				continue;
			}

			$host = $parts[0];
			if ( $wgAssumeProxiesUseDefaultProtocolPorts && isset( $_SERVER['HTTP_X_FORWARDED_PROTO'] ) ) {
				// T72021: Assume that upstream proxy is running on the default
				// port based on the protocol. We have no reliable way to determine
				// the actual port in use upstream.
				$port = $stdPort;
			} elseif ( $parts[1] === false ) {
				if ( isset( $_SERVER['SERVER_PORT'] ) ) {
					$port = $_SERVER['SERVER_PORT'];
				} // else leave it as $stdPort
			} else {
				$port = $parts[1];
			}
			break;
		}

		return $proto . '://' . IP::combineHostAndPort( $host, $port, $stdPort );
	}

	/**
	 * Detect the protocol from $_SERVER.
	 * This is for use prior to Setup.php, when no WebRequest object is available.
	 * At other times, use the non-static function getProtocol().
	 *
	 * @return string
	 */
	public static function detectProtocol() {
		if ( ( !empty( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] !== 'off' ) ||
			( isset( $_SERVER['HTTP_X_FORWARDED_PROTO'] ) &&
			$_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https' ) ) {
			return 'https';
		} else {
			return 'http';
		}
	}

	/**
	 * Get the number of seconds to have elapsed since request start,
	 * in fractional seconds, with microsecond resolution.
	 *
	 * @return float
	 * @since 1.25
	 */
	public function getElapsedTime() {
		return microtime( true ) - $this->requestTime;
	}

	/**
	 * Get the unique request ID.
	 * This is either the value of the UNIQUE_ID envvar (if present) or a
	 * randomly-generated 24-character string.
	 *
	 * @return string
	 * @since 1.27
	 */
	public static function getRequestId() {
		// This method is called from various error handlers and should be kept simple.

		if ( !self::$reqId ) {
			self::$reqId = $_SERVER['UNIQUE_ID'] ?? wfRandomString( 24 );
		}

		return self::$reqId;
	}

	/**
	 * Override the unique request ID. This is for sub-requests, such as jobs,
	 * that wish to use the same id but are not part of the same execution context.
	 *
	 * @param string $id
	 * @since 1.27
	 */
	public static function overrideRequestId( $id ) {
		self::$reqId = $id;
	}

	/**
	 * Get the current URL protocol (http or https)
	 * @return string
	 */
	public function getProtocol() {
		if ( $this->protocol === null ) {
			$this->protocol = self::detectProtocol();
		}
		return $this->protocol;
	}

	/**
	 * Check for title, action, and/or variant data in the URL
	 * and interpolate it into the GET variables.
	 * This should only be run after the content language is available,
	 * as we may need the list of language variants to determine
	 * available variant URLs.
	 */
	public function interpolateTitle() {
		// T18019: title interpolation on API queries is useless and sometimes harmful
		if ( defined( 'MW_API' ) ) {
			return;
		}

		$matches = self::getPathInfo( 'title' );
		foreach ( $matches as $key => $val ) {
			$this->data[$key] = $_GET[$key] = $_REQUEST[$key] = $val;
		}
	}

	/**
	 * URL rewriting function; tries to extract page title and,
	 * optionally, one other fixed parameter value from a URL path.
	 *
	 * @param string $path The URL path given from the client
	 * @param array $bases One or more URLs, optionally with $1 at the end
	 * @param string|bool $key If provided, the matching key in $bases will be
	 *    passed on as the value of this URL parameter
	 * @return array Array of URL variables to interpolate; empty if no match
	 */
	static function extractTitle( $path, $bases, $key = false ) {
		foreach ( (array)$bases as $keyValue => $base ) {
			// Find the part after $wgArticlePath
			$base = str_replace( '$1', '', $base );
			$baseLen = strlen( $base );
			if ( substr( $path, 0, $baseLen ) == $base ) {
				$raw = substr( $path, $baseLen );
				if ( $raw !== '' ) {
					$matches = [ 'title' => rawurldecode( $raw ) ];
					if ( $key ) {
						$matches[$key] = $keyValue;
					}
					return $matches;
				}
			}
		}
		return [];
	}

	/**
	 * Recursively normalizes UTF-8 strings in the given array.
	 *
	 * @param string|array $data
	 * @return array|string Cleaned-up version of the given
	 * @private
	 */
	public function normalizeUnicode( $data ) {
		if ( is_array( $data ) ) {
			foreach ( $data as $key => $val ) {
				$data[$key] = $this->normalizeUnicode( $val );
			}
		} else {
			$contLang = MediaWikiServices::getInstance()->getContentLanguage();
			$data = $contLang ? $contLang->normalize( $data ) :
				UtfNormal\Validator::cleanUp( $data );
		}
		return $data;
	}

	/**
	 * Fetch a value from the given array or return $default if it's not set.
	 *
	 * @param array $arr
	 * @param string $name
	 * @param mixed $default
	 * @return mixed
	 */
	private function getGPCVal( $arr, $name, $default ) {
		# PHP is so nice to not touch input data, except sometimes:
		# https://secure.php.net/variables.external#language.variables.external.dot-in-names
		# Work around PHP *feature* to avoid *bugs* elsewhere.
		$name = strtr( $name, '.', '_' );
		if ( isset( $arr[$name] ) ) {
			$data = $arr[$name];
			if ( isset( $_GET[$name] ) && !is_array( $data ) ) {
				# Check for alternate/legacy character encoding.
				$contLang = MediaWikiServices::getInstance()->getContentLanguage();
				if ( $contLang ) {
					$data = $contLang->checkTitleEncoding( $data );
				}
			}
			$data = $this->normalizeUnicode( $data );
			return $data;
		} else {
			return $default;
		}
	}

	/**
	 * Fetch a scalar from the input without normalization, or return $default
	 * if it's not set.
	 *
	 * Unlike self::getVal(), this does not perform any normalization on the
	 * input value.
	 *
	 * @since 1.28
	 * @param string $name
	 * @param string|null $default
	 * @return string|null
	 */
	public function getRawVal( $name, $default = null ) {
		$name = strtr( $name, '.', '_' ); // See comment in self::getGPCVal()
		if ( isset( $this->data[$name] ) && !is_array( $this->data[$name] ) ) {
			$val = $this->data[$name];
		} else {
			$val = $default;
		}
		if ( is_null( $val ) ) {
			return $val;
		} else {
			return (string)$val;
		}
	}

	/**
	 * Fetch a scalar from the input or return $default if it's not set.
	 * Returns a string. Arrays are discarded. Useful for
	 * non-freeform text inputs (e.g. predefined internal text keys
	 * selected by a drop-down menu). For freeform input, see getText().
	 *
	 * @param string $name
	 * @param string|null $default Optional default (or null)
	 * @return string|null
	 */
	public function getVal( $name, $default = null ) {
		$val = $this->getGPCVal( $this->data, $name, $default );
		if ( is_array( $val ) ) {
			$val = $default;
		}
		if ( is_null( $val ) ) {
			return $val;
		} else {
			return (string)$val;
		}
	}

	/**
	 * Set an arbitrary value into our get/post data.
	 *
	 * @param string $key Key name to use
	 * @param mixed $value Value to set
	 * @return mixed Old value if one was present, null otherwise
	 */
	public function setVal( $key, $value ) {
		$ret = $this->data[$key] ?? null;
		$this->data[$key] = $value;
		return $ret;
	}

	/**
	 * Unset an arbitrary value from our get/post data.
	 *
	 * @param string $key Key name to use
	 * @return mixed Old value if one was present, null otherwise
	 */
	public function unsetVal( $key ) {
		if ( !isset( $this->data[$key] ) ) {
			$ret = null;
		} else {
			$ret = $this->data[$key];
			unset( $this->data[$key] );
		}
		return $ret;
	}

	/**
	 * Fetch an array from the input or return $default if it's not set.
	 * If source was scalar, will return an array with a single element.
	 * If no source and no default, returns null.
	 *
	 * @param string $name
	 * @param array|null $default Optional default (or null)
	 * @return array|null
	 */
	public function getArray( $name, $default = null ) {
		$val = $this->getGPCVal( $this->data, $name, $default );
		if ( is_null( $val ) ) {
			return null;
		} else {
			return (array)$val;
		}
	}

	/**
	 * Fetch an array of integers, or return $default if it's not set.
	 * If source was scalar, will return an array with a single element.
	 * If no source and no default, returns null.
	 * If an array is returned, contents are guaranteed to be integers.
	 *
	 * @param string $name
	 * @param array|null $default Option default (or null)
	 * @return array Array of ints
	 */
	public function getIntArray( $name, $default = null ) {
		$val = $this->getArray( $name, $default );
		if ( is_array( $val ) ) {
			$val = array_map( 'intval', $val );
		}
		return $val;
	}

	/**
	 * Fetch an integer value from the input or return $default if not set.
	 * Guaranteed to return an integer; non-numeric input will typically
	 * return 0.
	 *
	 * @param string $name
	 * @param int $default
	 * @return int
	 */
	public function getInt( $name, $default = 0 ) {
		return intval( $this->getRawVal( $name, $default ) );
	}

	/**
	 * Fetch an integer value from the input or return null if empty.
	 * Guaranteed to return an integer or null; non-numeric input will
	 * typically return null.
	 *
	 * @param string $name
	 * @return int|null
	 */
	public function getIntOrNull( $name ) {
		$val = $this->getRawVal( $name );
		return is_numeric( $val )
			? intval( $val )
			: null;
	}

	/**
	 * Fetch a floating point value from the input or return $default if not set.
	 * Guaranteed to return a float; non-numeric input will typically
	 * return 0.
	 *
	 * @since 1.23
	 * @param string $name
	 * @param float $default
	 * @return float
	 */
	public function getFloat( $name, $default = 0.0 ) {
		return floatval( $this->getRawVal( $name, $default ) );
	}

	/**
	 * Fetch a boolean value from the input or return $default if not set.
	 * Guaranteed to return true or false, with normal PHP semantics for
	 * boolean interpretation of strings.
	 *
	 * @param string $name
	 * @param bool $default
	 * @return bool
	 */
	public function getBool( $name, $default = false ) {
		return (bool)$this->getRawVal( $name, $default );
	}

	/**
	 * Fetch a boolean value from the input or return $default if not set.
	 * Unlike getBool, the string "false" will result in boolean false, which is
	 * useful when interpreting information sent from JavaScript.
	 *
	 * @param string $name
	 * @param bool $default
	 * @return bool
	 */
	public function getFuzzyBool( $name, $default = false ) {
		return $this->getBool( $name, $default )
			&& strcasecmp( $this->getRawVal( $name ), 'false' ) !== 0;
	}

	/**
	 * Return true if the named value is set in the input, whatever that
	 * value is (even "0"). Return false if the named value is not set.
	 * Example use is checking for the presence of check boxes in forms.
	 *
	 * @param string $name
	 * @return bool
	 */
	public function getCheck( $name ) {
		# Checkboxes and buttons are only present when clicked
		# Presence connotes truth, absence false
		return $this->getRawVal( $name, null ) !== null;
	}

	/**
	 * Fetch a text string from the given array or return $default if it's not
	 * set. Carriage returns are stripped from the text. This should generally
	 * be used for form "<textarea>" and "<input>" fields, and for
	 * user-supplied freeform text input.
	 *
	 * @param string $name
	 * @param string $default Optional
	 * @return string
	 */
	public function getText( $name, $default = '' ) {
		$val = $this->getVal( $name, $default );
		return str_replace( "\r\n", "\n", $val );
	}

	/**
	 * Extracts the given named values into an array.
	 * If no arguments are given, returns all input values.
	 * No transformation is performed on the values.
	 *
	 * @return array
	 */
	public function getValues() {
		$names = func_get_args();
		if ( count( $names ) == 0 ) {
			$names = array_keys( $this->data );
		}

		$retVal = [];
		foreach ( $names as $name ) {
			$value = $this->getGPCVal( $this->data, $name, null );
			if ( !is_null( $value ) ) {
				$retVal[$name] = $value;
			}
		}
		return $retVal;
	}

	/**
	 * Returns the names of all input values excluding those in $exclude.
	 *
	 * @param array $exclude
	 * @return array
	 */
	public function getValueNames( $exclude = [] ) {
		return array_diff( array_keys( $this->getValues() ), $exclude );
	}

	/**
	 * Get the values passed in the query string.
	 * No transformation is performed on the values.
	 *
	 * @codeCoverageIgnore
	 * @return array
	 */
	public function getQueryValues() {
		return $_GET;
	}

	/**
	 * Get the values passed via POST.
	 * No transformation is performed on the values.
	 *
	 * @since 1.32
	 * @codeCoverageIgnore
	 * @return array
	 */
	public function getPostValues() {
		return $_POST;
	}

	/**
	 * Return the contents of the Query with no decoding. Use when you need to
	 * know exactly what was sent, e.g. for an OAuth signature over the elements.
	 *
	 * @codeCoverageIgnore
	 * @return string
	 */
	public function getRawQueryString() {
		return $_SERVER['QUERY_STRING'];
	}

	/**
	 * Return the contents of the POST with no decoding. Use when you need to
	 * know exactly what was sent, e.g. for an OAuth signature over the elements.
	 *
	 * @return string
	 */
	public function getRawPostString() {
		if ( !$this->wasPosted() ) {
			return '';
		}
		return $this->getRawInput();
	}

	/**
	 * Return the raw request body, with no processing. Cached since some methods
	 * disallow reading the stream more than once. As stated in the php docs, this
	 * does not work with enctype="multipart/form-data".
	 *
	 * @return string
	 */
	public function getRawInput() {
		static $input = null;
		if ( $input === null ) {
			$input = file_get_contents( 'php://input' );
		}
		return $input;
	}

	/**
	 * Get the HTTP method used for this request.
	 *
	 * @return string
	 */
	public function getMethod() {
		return $_SERVER['REQUEST_METHOD'] ?? 'GET';
	}

	/**
	 * Returns true if the present request was reached by a POST operation,
	 * false otherwise (GET, HEAD, or command-line).
	 *
	 * Note that values retrieved by the object may come from the
	 * GET URL etc even on a POST request.
	 *
	 * @return bool
	 */
	public function wasPosted() {
		return $this->getMethod() == 'POST';
	}

	/**
	 * Return the session for this request
	 *
	 * This might unpersist an existing session if it was invalid.
	 *
	 * @since 1.27
	 * @note For performance, keep the session locally if you will be making
	 *  much use of it instead of calling this method repeatedly.
	 * @return Session
	 */
	public function getSession() {
		if ( $this->sessionId !== null ) {
			$session = SessionManager::singleton()->getSessionById( (string)$this->sessionId, true, $this );
			if ( $session ) {
				return $session;
			}
		}

		$session = SessionManager::singleton()->getSessionForRequest( $this );
		$this->sessionId = $session->getSessionId();
		return $session;
	}

	/**
	 * Set the session for this request
	 * @since 1.27
	 * @private For use by MediaWiki\Session classes only
	 * @param SessionId $sessionId
	 */
	public function setSessionId( SessionId $sessionId ) {
		$this->sessionId = $sessionId;
	}

	/**
	 * Get the session id for this request, if any
	 * @since 1.27
	 * @private For use by MediaWiki\Session classes only
	 * @return SessionId|null
	 */
	public function getSessionId() {
		return $this->sessionId;
	}

	/**
	 * Get a cookie from the $_COOKIE jar
	 *
	 * @param string $key The name of the cookie
	 * @param string|null $prefix A prefix to use for the cookie name, if not $wgCookiePrefix
	 * @param mixed|null $default What to return if the value isn't found
	 * @return mixed Cookie value or $default if the cookie not set
	 */
	public function getCookie( $key, $prefix = null, $default = null ) {
		if ( $prefix === null ) {
			global $wgCookiePrefix;
			$prefix = $wgCookiePrefix;
		}
		return $this->getGPCVal( $_COOKIE, $prefix . $key, $default );
	}

	/**
	 * Return the path and query string portion of the main request URI.
	 * This will be suitable for use as a relative link in HTML output.
	 *
	 * @throws MWException
	 * @return string
	 */
	public static function getGlobalRequestURL() {
		// This method is called on fatal errors; it should not depend on anything complex.

		if ( isset( $_SERVER['REQUEST_URI'] ) && strlen( $_SERVER['REQUEST_URI'] ) ) {
			$base = $_SERVER['REQUEST_URI'];
		} elseif ( isset( $_SERVER['HTTP_X_ORIGINAL_URL'] )
			&& strlen( $_SERVER['HTTP_X_ORIGINAL_URL'] )
		) {
			// Probably IIS; doesn't set REQUEST_URI
			$base = $_SERVER['HTTP_X_ORIGINAL_URL'];
		} elseif ( isset( $_SERVER['SCRIPT_NAME'] ) ) {
			$base = $_SERVER['SCRIPT_NAME'];
			if ( isset( $_SERVER['QUERY_STRING'] ) && $_SERVER['QUERY_STRING'] != '' ) {
				$base .= '?' . $_SERVER['QUERY_STRING'];
			}
		} else {
			// This shouldn't happen!
			throw new MWException( "Web server doesn't provide either " .
				"REQUEST_URI, HTTP_X_ORIGINAL_URL or SCRIPT_NAME. Report details " .
				"of your web server configuration to https://phabricator.wikimedia.org/" );
		}
		// User-agents should not send a fragment with the URI, but
		// if they do, and the web server passes it on to us, we
		// need to strip it or we get false-positive redirect loops
		// or weird output URLs
		$hash = strpos( $base, '#' );
		if ( $hash !== false ) {
			$base = substr( $base, 0, $hash );
		}

		if ( $base[0] == '/' ) {
			// More than one slash will look like it is protocol relative
			return preg_replace( '!^/+!', '/', $base );
		} else {
			// We may get paths with a host prepended; strip it.
			return preg_replace( '!^[^:]+://[^/]+/+!', '/', $base );
		}
	}

	/**
	 * Return the path and query string portion of the request URI.
	 * This will be suitable for use as a relative link in HTML output.
	 *
	 * @throws MWException
	 * @return string
	 */
	public function getRequestURL() {
		return self::getGlobalRequestURL();
	}

	/**
	 * Return the request URI with the canonical service and hostname, path,
	 * and query string. This will be suitable for use as an absolute link
	 * in HTML or other output.
	 *
	 * If $wgServer is protocol-relative, this will return a fully
	 * qualified URL with the protocol that was used for this request.
	 *
	 * @return string
	 */
	public function getFullRequestURL() {
		return wfGetServerUrl( PROTO_CURRENT ) . $this->getRequestURL();
	}

	/**
	 * @param string $key
	 * @param string $value
	 * @return string
	 */
	public function appendQueryValue( $key, $value ) {
		return $this->appendQueryArray( [ $key => $value ] );
	}

	/**
	 * Appends or replaces value of query variables.
	 *
	 * @param array $array Array of values to replace/add to query
	 * @return string
	 */
	public function appendQueryArray( $array ) {
		$newquery = $this->getQueryValues();
		unset( $newquery['title'] );
		$newquery = array_merge( $newquery, $array );

		return wfArrayToCgi( $newquery );
	}

	/**
	 * Check for limit and offset parameters on the input, and return sensible
	 * defaults if not given. The limit must be positive and is capped at 5000.
	 * Offset must be positive but is not capped.
	 *
	 * @param int $deflimit Limit to use if no input and the user hasn't set the option.
	 * @param string $optionname To specify an option other than rclimit to pull from.
	 * @return int[] First element is limit, second is offset
	 */
	public function getLimitOffset( $deflimit = 50, $optionname = 'rclimit' ) {
		global $wgUser;

		$limit = $this->getInt( 'limit', 0 );
		if ( $limit < 0 ) {
			$limit = 0;
		}
		if ( ( $limit == 0 ) && ( $optionname != '' ) ) {
			$limit = $wgUser->getIntOption( $optionname );
		}
		if ( $limit <= 0 ) {
			$limit = $deflimit;
		}
		if ( $limit > 5000 ) {
			$limit = 5000; # We have *some* limits...
		}

		$offset = $this->getInt( 'offset', 0 );
		if ( $offset < 0 ) {
			$offset = 0;
		}

		return [ $limit, $offset ];
	}

	/**
	 * Return the path to the temporary file where PHP has stored the upload.
	 *
	 * @param string $key
	 * @return string|null String or null if no such file.
	 */
	public function getFileTempname( $key ) {
		$file = new WebRequestUpload( $this, $key );
		return $file->getTempName();
	}

	/**
	 * Return the upload error or 0
	 *
	 * @param string $key
	 * @return int
	 */
	public function getUploadError( $key ) {
		$file = new WebRequestUpload( $this, $key );
		return $file->getError();
	}

	/**
	 * Return the original filename of the uploaded file, as reported by
	 * the submitting user agent. HTML-style character entities are
	 * interpreted and normalized to Unicode normalization form C, in part
	 * to deal with weird input from Safari with non-ASCII filenames.
	 *
	 * Other than this the name is not verified for being a safe filename.
	 *
	 * @param string $key
	 * @return string|null String or null if no such file.
	 */
	public function getFileName( $key ) {
		$file = new WebRequestUpload( $this, $key );
		return $file->getName();
	}

	/**
	 * Return a WebRequestUpload object corresponding to the key
	 *
	 * @param string $key
	 * @return WebRequestUpload
	 */
	public function getUpload( $key ) {
		return new WebRequestUpload( $this, $key );
	}

	/**
	 * Return a handle to WebResponse style object, for setting cookies,
	 * headers and other stuff, for Request being worked on.
	 *
	 * @return WebResponse
	 */
	public function response() {
		/* Lazy initialization of response object for this request */
		if ( !is_object( $this->response ) ) {
			$class = ( $this instanceof FauxRequest ) ? FauxResponse::class : WebResponse::class;
			$this->response = new $class();
		}
		return $this->response;
	}

	/**
	 * Initialise the header list
	 */
	protected function initHeaders() {
		if ( count( $this->headers ) ) {
			return;
		}

		$apacheHeaders = function_exists( 'apache_request_headers' ) ? apache_request_headers() : false;
		if ( $apacheHeaders ) {
			foreach ( $apacheHeaders as $tempName => $tempValue ) {
				$this->headers[strtoupper( $tempName )] = $tempValue;
			}
		} else {
			foreach ( $_SERVER as $name => $value ) {
				if ( substr( $name, 0, 5 ) === 'HTTP_' ) {
					$name = str_replace( '_', '-', substr( $name, 5 ) );
					$this->headers[$name] = $value;
				} elseif ( $name === 'CONTENT_LENGTH' ) {
					$this->headers['CONTENT-LENGTH'] = $value;
				}
			}
		}
	}

	/**
	 * Get an array containing all request headers
	 *
	 * @return array Mapping header name to its value
	 */
	public function getAllHeaders() {
		$this->initHeaders();
		return $this->headers;
	}

	/**
	 * Get a request header, or false if it isn't set.
	 *
	 * @param string $name Case-insensitive header name
	 * @param int $flags Bitwise combination of:
	 *   WebRequest::GETHEADER_LIST  Treat the header as a comma-separated list
	 *                               of values, as described in RFC 2616 § 4.2.
	 *                               (since 1.26).
	 * @return string|array|bool False if header is unset; otherwise the
	 *  header value(s) as either a string (the default) or an array, if
	 *  WebRequest::GETHEADER_LIST flag was set.
	 */
	public function getHeader( $name, $flags = 0 ) {
		$this->initHeaders();
		$name = strtoupper( $name );
		if ( !isset( $this->headers[$name] ) ) {
			return false;
		}
		$value = $this->headers[$name];
		if ( $flags & self::GETHEADER_LIST ) {
			$value = array_map( 'trim', explode( ',', $value ) );
		}
		return $value;
	}

	/**
	 * Get data from the session
	 *
	 * @note Prefer $this->getSession() instead if making multiple calls.
	 * @param string $key Name of key in the session
	 * @return mixed
	 */
	public function getSessionData( $key ) {
		return $this->getSession()->get( $key );
	}

	/**
	 * Set session data
	 *
	 * @note Prefer $this->getSession() instead if making multiple calls.
	 * @param string $key Name of key in the session
	 * @param mixed $data
	 */
	public function setSessionData( $key, $data ) {
		$this->getSession()->set( $key, $data );
	}

	/**
	 * Check if Internet Explorer will detect an incorrect cache extension in
	 * PATH_INFO or QUERY_STRING. If the request can't be allowed, show an error
	 * message or redirect to a safer URL. Returns true if the URL is OK, and
	 * false if an error message has been shown and the request should be aborted.
	 *
	 * @param array $extWhitelist
	 * @throws HttpError
	 * @return bool
	 */
	public function checkUrlExtension( $extWhitelist = [] ) {
		$extWhitelist[] = 'php';
		if ( IEUrlExtension::areServerVarsBad( $_SERVER, $extWhitelist ) ) {
			if ( !$this->wasPosted() ) {
				$newUrl = IEUrlExtension::fixUrlForIE6(
					$this->getFullRequestURL(), $extWhitelist );
				if ( $newUrl !== false ) {
					$this->doSecurityRedirect( $newUrl );
					return false;
				}
			}
			throw new HttpError( 403,
				'Invalid file extension found in the path info or query string.' );
		}
		return true;
	}

	/**
	 * Attempt to redirect to a URL with a QUERY_STRING that's not dangerous in
	 * IE 6. Returns true if it was successful, false otherwise.
	 *
	 * @param string $url
	 * @return bool
	 */
	protected function doSecurityRedirect( $url ) {
		header( 'Location: ' . $url );
		header( 'Content-Type: text/html' );
		$encUrl = htmlspecialchars( $url );
		echo <<<HTML
<!DOCTYPE html>
<html>
<head>
<title>Security redirect</title>
</head>
<body>
<h1>Security redirect</h1>
<p>
We can't serve non-HTML content from the URL you have requested, because
Internet Explorer would interpret it as an incorrect and potentially dangerous
content type.</p>
<p>Instead, please use <a href="$encUrl">this URL</a>, which is the same as the
URL you have requested, except that "&amp;*" is appended. This prevents Internet
Explorer from seeing a bogus file extension.
</p>
</body>
</html>
HTML;
		echo "\n";
		return true;
	}

	/**
	 * Parse the Accept-Language header sent by the client into an array
	 *
	 * @return array Array( languageCode => q-value ) sorted by q-value in
	 *   descending order then appearing time in the header in ascending order.
	 * May contain the "language" '*', which applies to languages other than those explicitly listed.
	 * This is aligned with rfc2616 section 14.4
	 * Preference for earlier languages appears in rfc3282 as an extension to HTTP/1.1.
	 */
	public function getAcceptLang() {
		// Modified version of code found at
		// http://www.thefutureoftheweb.com/blog/use-accept-language-header
		$acceptLang = $this->getHeader( 'Accept-Language' );
		if ( !$acceptLang ) {
			return [];
		}

		// Return the language codes in lower case
		$acceptLang = strtolower( $acceptLang );

		// Break up string into pieces (languages and q factors)
		$lang_parse = null;
		preg_match_all(
			'/([a-z]{1,8}(-[a-z]{1,8})*|\*)\s*(;\s*q\s*=\s*(1(\.0{0,3})?|0(\.[0-9]{0,3})?)?)?/',
			$acceptLang,
			$lang_parse
		);

		if ( !count( $lang_parse[1] ) ) {
			return [];
		}

		$langcodes = $lang_parse[1];
		$qvalues = $lang_parse[4];
		$indices = range( 0, count( $lang_parse[1] ) - 1 );

		// Set default q factor to 1
		foreach ( $indices as $index ) {
			if ( $qvalues[$index] === '' ) {
				$qvalues[$index] = 1;
			} elseif ( $qvalues[$index] == 0 ) {
				unset( $langcodes[$index], $qvalues[$index], $indices[$index] );
			}
		}

		// Sort list. First by $qvalues, then by order. Reorder $langcodes the same way
		array_multisort( $qvalues, SORT_DESC, SORT_NUMERIC, $indices, $langcodes );

		// Create a list like "en" => 0.8
		$langs = array_combine( $langcodes, $qvalues );

		return $langs;
	}

	/**
	 * Fetch the raw IP from the request
	 *
	 * @since 1.19
	 *
	 * @throws MWException
	 * @return string
	 */
	protected function getRawIP() {
		if ( !isset( $_SERVER['REMOTE_ADDR'] ) ) {
			return null;
		}

		if ( is_array( $_SERVER['REMOTE_ADDR'] ) || strpos( $_SERVER['REMOTE_ADDR'], ',' ) !== false ) {
			throw new MWException( __METHOD__
				. " : Could not determine the remote IP address due to multiple values." );
		} else {
			$ipchain = $_SERVER['REMOTE_ADDR'];
		}

		return IP::canonicalize( $ipchain );
	}

	/**
	 * Work out the IP address based on various globals
	 * For trusted proxies, use the XFF client IP (first of the chain)
	 *
	 * @since 1.19
	 *
	 * @throws MWException
	 * @return string
	 */
	public function getIP() {
		global $wgUsePrivateIPs;

		# Return cached result
		if ( $this->ip !== null ) {
			return $this->ip;
		}

		# collect the originating ips
		$ip = $this->getRawIP();
		if ( !$ip ) {
			throw new MWException( 'Unable to determine IP.' );
		}

		# Append XFF
		$forwardedFor = $this->getHeader( 'X-Forwarded-For' );
		if ( $forwardedFor !== false ) {
			$proxyLookup = MediaWikiServices::getInstance()->getProxyLookup();
			$isConfigured = $proxyLookup->isConfiguredProxy( $ip );
			$ipchain = array_map( 'trim', explode( ',', $forwardedFor ) );
			$ipchain = array_reverse( $ipchain );
			array_unshift( $ipchain, $ip );

			# Step through XFF list and find the last address in the list which is a
			# trusted server. Set $ip to the IP address given by that trusted server,
			# unless the address is not sensible (e.g. private). However, prefer private
			# IP addresses over proxy servers controlled by this site (more sensible).
			# Note that some XFF values might be "unknown" with Squid/Varnish.
			foreach ( $ipchain as $i => $curIP ) {
				$curIP = IP::sanitizeIP( IP::canonicalize( $curIP ) );
				if ( !$curIP || !isset( $ipchain[$i + 1] ) || $ipchain[$i + 1] === 'unknown'
					|| !$proxyLookup->isTrustedProxy( $curIP )
				) {
					break; // IP is not valid/trusted or does not point to anything
				}
				if (
					IP::isPublic( $ipchain[$i + 1] ) ||
					$wgUsePrivateIPs ||
					$proxyLookup->isConfiguredProxy( $curIP ) // T50919; treat IP as sane
				) {
					// Follow the next IP according to the proxy
					$nextIP = IP::canonicalize( $ipchain[$i + 1] );
					if ( !$nextIP && $isConfigured ) {
						// We have not yet made it past CDN/proxy servers of this site,
						// so either they are misconfigured or there is some IP spoofing.
						throw new MWException( "Invalid IP given in XFF '$forwardedFor'." );
					}
					$ip = $nextIP;
					// keep traversing the chain
					continue;
				}
				break;
			}
		}

		# Allow extensions to improve our guess
		Hooks::run( 'GetIP', [ &$ip ] );

		if ( !$ip ) {
			throw new MWException( "Unable to determine IP." );
		}

		wfDebug( "IP: $ip\n" );
		$this->ip = $ip;
		return $ip;
	}

	/**
	 * @param string $ip
	 * @return void
	 * @since 1.21
	 */
	public function setIP( $ip ) {
		$this->ip = $ip;
	}

	/**
	 * Check if this request uses a "safe" HTTP method
	 *
	 * Safe methods are verbs (e.g. GET/HEAD/OPTIONS) used for obtaining content. Such requests
	 * are not expected to mutate content, especially in ways attributable to the client. Verbs
	 * like POST and PUT are typical of non-safe requests which often change content.
	 *
	 * @return bool
	 * @see https://tools.ietf.org/html/rfc7231#section-4.2.1
	 * @see https://www.w3.org/Protocols/rfc2616/rfc2616-sec9.html
	 * @since 1.28
	 */
	public function hasSafeMethod() {
		if ( !isset( $_SERVER['REQUEST_METHOD'] ) ) {
			return false; // CLI mode
		}

		return in_array( $_SERVER['REQUEST_METHOD'], [ 'GET', 'HEAD', 'OPTIONS', 'TRACE' ] );
	}

	/**
	 * Whether this request should be identified as being "safe"
	 *
	 * This means that the client is not requesting any state changes and that database writes
	 * are not inherently required. Ideally, no visible updates would happen at all. If they
	 * must, then they should not be publically attributed to the end user.
	 *
	 * In more detail:
	 *   - Cache populations and refreshes MAY occur.
	 *   - Private user session updates and private server logging MAY occur.
	 *   - Updates to private viewing activity data MAY occur via DeferredUpdates.
	 *   - Other updates SHOULD NOT occur (e.g. modifying content assets).
	 *
	 * @return bool
	 * @see https://tools.ietf.org/html/rfc7231#section-4.2.1
	 * @see https://www.w3.org/Protocols/rfc2616/rfc2616-sec9.html
	 * @since 1.28
	 */
	public function isSafeRequest() {
		if ( $this->markedAsSafe && $this->wasPosted() ) {
			return true; // marked as a "safe" POST
		}

		return $this->hasSafeMethod();
	}

	/**
	 * Mark this request as identified as being nullipotent even if it is a POST request
	 *
	 * POST requests are often used due to the need for a client payload, even if the request
	 * is otherwise equivalent to a "safe method" request.
	 *
	 * @see https://tools.ietf.org/html/rfc7231#section-4.2.1
	 * @see https://www.w3.org/Protocols/rfc2616/rfc2616-sec9.html
	 * @since 1.28
	 */
	public function markAsSafeRequest() {
		$this->markedAsSafe = true;
	}
}
