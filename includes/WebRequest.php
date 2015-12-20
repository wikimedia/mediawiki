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

/**
 * The WebRequest class encapsulates getting at data passed in the
 * URL or via a POSTed form, handling remove of "magic quotes" slashes,
 * stripping illegal input characters and normalizing Unicode sequences.
 *
 * Usually this is used via a global singleton, $wgRequest. You should
 * not create a second WebRequest object; make a FauxRequest object if
 * you want to pass arbitrary data to some function in place of the web
 * input.
 *
 * @ingroup HTTP
 */
class WebRequest {
	protected $data, $headers = array();

	/**
	 * Lazy-init response object
	 * @var WebResponse
	 */
	private $response;

	/**
	 * Cached client IP address
	 * @var String
	 */
	private $ip;

	/**
	 * Cached URL protocol
	 * @var string
	 */
	protected $protocol;

	public function __construct() {
		/// @todo FIXME: This preemptive de-quoting can interfere with other web libraries
		///        and increases our memory footprint. It would be cleaner to do on
		///        demand; but currently we have no wrapper for $_SERVER etc.
		$this->checkMagicQuotes();

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
	 * @return Array: Any query arguments found in path matches.
	 */
	public static function getPathInfo( $want = 'all' ) {
		global $wgUsePathInfo;
		// PATH_INFO is mangled due to http://bugs.php.net/bug.php?id=31892
		// And also by Apache 2.x, double slashes are converted to single slashes.
		// So we will use REQUEST_URI if possible.
		$matches = array();
		if ( !empty( $_SERVER['REQUEST_URI'] ) ) {
			// Slurp out the path portion to examine...
			$url = $_SERVER['REQUEST_URI'];
			if ( !preg_match( '!^https?://!', $url ) ) {
				$url = 'http://unused' . $url;
			}
			wfSuppressWarnings();
			$a = parse_url( $url );
			wfRestoreWarnings();
			if ( $a ) {
				$path = isset( $a['path'] ) ? $a['path'] : '';

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
					&& preg_match( '/\.php5?/', $_SERVER['SCRIPT_NAME'] )
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
					$router->add( $wgActionPaths, array( 'action' => '$key' ) );
				}

				global $wgVariantArticlePath, $wgContLang;
				if ( $wgVariantArticlePath ) {
					$router->add( $wgVariantArticlePath,
						array( 'variant' => '$2' ),
						array( '$2' => $wgContLang->getVariants() )
					);
				}

				wfRunHooks( 'WebRequestPathInfoRouter', array( $router ) );

				$matches = $router->parse( $path );
			}
		} elseif ( $wgUsePathInfo ) {
			if ( isset( $_SERVER['ORIG_PATH_INFO'] ) && $_SERVER['ORIG_PATH_INFO'] != '' ) {
				// Mangled PATH_INFO
				// http://bugs.php.net/bug.php?id=31892
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
		$proto = self::detectProtocol();
		$stdPort = $proto === 'https' ? 443 : 80;

		$varNames = array( 'HTTP_HOST', 'SERVER_NAME', 'HOSTNAME', 'SERVER_ADDR' );
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
			if ( $parts[1] === false ) {
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
	 * @return array
	 */
	public static function detectProtocol() {
		if ( ( isset( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] == 'on' ) ||
			( isset( $_SERVER['HTTP_X_FORWARDED_PROTO'] ) &&
			$_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' ) ) {
			return 'https';
		} else {
			return 'http';
		}
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
	 * This should only be run after $wgContLang is available,
	 * as we may need the list of language variants to determine
	 * available variant URLs.
	 */
	public function interpolateTitle() {
		// bug 16019: title interpolation on API queries is useless and sometimes harmful
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
	 * @param string $path the URL path given from the client
	 * @param array $bases one or more URLs, optionally with $1 at the end
	 * @param string $key if provided, the matching key in $bases will be
	 *             passed on as the value of this URL parameter
	 * @return array of URL variables to interpolate; empty if no match
	 */
	static function extractTitle( $path, $bases, $key = false ) {
		foreach ( (array)$bases as $keyValue => $base ) {
			// Find the part after $wgArticlePath
			$base = str_replace( '$1', '', $base );
			$baseLen = strlen( $base );
			if ( substr( $path, 0, $baseLen ) == $base ) {
				$raw = substr( $path, $baseLen );
				if ( $raw !== '' ) {
					$matches = array( 'title' => rawurldecode( $raw ) );
					if ( $key ) {
						$matches[$key] = $keyValue;
					}
					return $matches;
				}
			}
		}
		return array();
	}

	/**
	 * Recursively strips slashes from the given array;
	 * used for undoing the evil that is magic_quotes_gpc.
	 *
	 * @param array $arr will be modified
	 * @param bool $topLevel Specifies if the array passed is from the top
	 * level of the source. In PHP5 magic_quotes only escapes the first level
	 * of keys that belong to an array.
	 * @return array the original array
	 * @see http://www.php.net/manual/en/function.get-magic-quotes-gpc.php#49612
	 */
	private function &fix_magic_quotes( &$arr, $topLevel = true ) {
		$clean = array();
		foreach ( $arr as $key => $val ) {
			if ( is_array( $val ) ) {
				$cleanKey = $topLevel ? stripslashes( $key ) : $key;
				$clean[$cleanKey] = $this->fix_magic_quotes( $arr[$key], false );
			} else {
				$cleanKey = stripslashes( $key );
				$clean[$cleanKey] = stripslashes( $val );
			}
		}
		$arr = $clean;
		return $arr;
	}

	/**
	 * If magic_quotes_gpc option is on, run the global arrays
	 * through fix_magic_quotes to strip out the stupid slashes.
	 * WARNING: This should only be done once! Running a second
	 * time could damage the values.
	 */
	private function checkMagicQuotes() {
		$mustFixQuotes = function_exists( 'get_magic_quotes_gpc' )
			&& get_magic_quotes_gpc();
		if ( $mustFixQuotes ) {
			$this->fix_magic_quotes( $_COOKIE );
			$this->fix_magic_quotes( $_ENV );
			$this->fix_magic_quotes( $_GET );
			$this->fix_magic_quotes( $_POST );
			$this->fix_magic_quotes( $_REQUEST );
			$this->fix_magic_quotes( $_SERVER );
		}
	}

	/**
	 * Recursively normalizes UTF-8 strings in the given array.
	 *
	 * @param $data string|array
	 * @return array|string cleaned-up version of the given
	 * @private
	 */
	function normalizeUnicode( $data ) {
		if ( is_array( $data ) ) {
			foreach ( $data as $key => $val ) {
				$data[$key] = $this->normalizeUnicode( $val );
			}
		} else {
			global $wgContLang;
			$data = isset( $wgContLang ) ? $wgContLang->normalize( $data ) : UtfNormal::cleanUp( $data );
		}
		return $data;
	}

	/**
	 * Fetch a value from the given array or return $default if it's not set.
	 *
	 * @param $arr Array
	 * @param $name String
	 * @param $default Mixed
	 * @return mixed
	 */
	private function getGPCVal( $arr, $name, $default ) {
		# PHP is so nice to not touch input data, except sometimes:
		# http://us2.php.net/variables.external#language.variables.external.dot-in-names
		# Work around PHP *feature* to avoid *bugs* elsewhere.
		$name = strtr( $name, '.', '_' );
		if ( isset( $arr[$name] ) ) {
			global $wgContLang;
			$data = $arr[$name];
			if ( isset( $_GET[$name] ) && !is_array( $data ) ) {
				# Check for alternate/legacy character encoding.
				if ( isset( $wgContLang ) ) {
					$data = $wgContLang->checkTitleEncoding( $data );
				}
			}
			$data = $this->normalizeUnicode( $data );
			return $data;
		} else {
			return $default;
		}
	}

	/**
	 * Fetch a scalar from the input or return $default if it's not set.
	 * Returns a string. Arrays are discarded. Useful for
	 * non-freeform text inputs (e.g. predefined internal text keys
	 * selected by a drop-down menu). For freeform input, see getText().
	 *
	 * @param $name String
	 * @param string $default optional default (or NULL)
	 * @return String
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
	 * @param string $key key name to use
	 * @param $value Mixed: value to set
	 * @return Mixed: old value if one was present, null otherwise
	 */
	public function setVal( $key, $value ) {
		$ret = isset( $this->data[$key] ) ? $this->data[$key] : null;
		$this->data[$key] = $value;
		return $ret;
	}

	/**
	 * Unset an arbitrary value from our get/post data.
	 *
	 * @param string $key key name to use
	 * @return Mixed: old value if one was present, null otherwise
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
	 * If no source and no default, returns NULL.
	 *
	 * @param $name String
	 * @param array $default optional default (or NULL)
	 * @return Array
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
	 * If no source and no default, returns NULL.
	 * If an array is returned, contents are guaranteed to be integers.
	 *
	 * @param $name String
	 * @param array $default option default (or NULL)
	 * @return Array of ints
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
	 * @param $name String
	 * @param $default Integer
	 * @return Integer
	 */
	public function getInt( $name, $default = 0 ) {
		return intval( $this->getVal( $name, $default ) );
	}

	/**
	 * Fetch an integer value from the input or return null if empty.
	 * Guaranteed to return an integer or null; non-numeric input will
	 * typically return null.
	 *
	 * @param $name String
	 * @return Integer
	 */
	public function getIntOrNull( $name ) {
		$val = $this->getVal( $name );
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
	 * @param $name String
	 * @param $default Float
	 * @return Float
	 */
	public function getFloat( $name, $default = 0 ) {
		return floatval( $this->getVal( $name, $default ) );
	}

	/**
	 * Fetch a boolean value from the input or return $default if not set.
	 * Guaranteed to return true or false, with normal PHP semantics for
	 * boolean interpretation of strings.
	 *
	 * @param $name String
	 * @param $default Boolean
	 * @return Boolean
	 */
	public function getBool( $name, $default = false ) {
		return (bool)$this->getVal( $name, $default );
	}

	/**
	 * Fetch a boolean value from the input or return $default if not set.
	 * Unlike getBool, the string "false" will result in boolean false, which is
	 * useful when interpreting information sent from JavaScript.
	 *
	 * @param $name String
	 * @param $default Boolean
	 * @return Boolean
	 */
	public function getFuzzyBool( $name, $default = false ) {
		return $this->getBool( $name, $default ) && strcasecmp( $this->getVal( $name ), 'false' ) !== 0;
	}

	/**
	 * Return true if the named value is set in the input, whatever that
	 * value is (even "0"). Return false if the named value is not set.
	 * Example use is checking for the presence of check boxes in forms.
	 *
	 * @param $name String
	 * @return Boolean
	 */
	public function getCheck( $name ) {
		# Checkboxes and buttons are only present when clicked
		# Presence connotes truth, absence false
		return $this->getVal( $name, null ) !== null;
	}

	/**
	 * Fetch a text string from the given array or return $default if it's not
	 * set. Carriage returns are stripped from the text, and with some language
	 * modules there is an input transliteration applied. This should generally
	 * be used for form "<textarea>" and "<input>" fields. Used for
	 * user-supplied freeform text input (for which input transformations may
	 * be required - e.g.  Esperanto x-coding).
	 *
	 * @param $name String
	 * @param string $default optional
	 * @return String
	 */
	public function getText( $name, $default = '' ) {
		global $wgContLang;
		$val = $this->getVal( $name, $default );
		return str_replace( "\r\n", "\n",
			$wgContLang->recodeInput( $val ) );
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

		$retVal = array();
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
	 * @param $exclude Array
	 * @return array
	 */
	public function getValueNames( $exclude = array() ) {
		return array_diff( array_keys( $this->getValues() ), $exclude );
	}

	/**
	 * Get the values passed in the query string.
	 * No transformation is performed on the values.
	 *
	 * @return Array
	 */
	public function getQueryValues() {
		return $_GET;
	}

	/**
	 * Return the contents of the Query with no decoding. Use when you need to
	 * know exactly what was sent, e.g. for an OAuth signature over the elements.
	 *
	 * @return String
	 */
	public function getRawQueryString() {
		return $_SERVER['QUERY_STRING'];
	}

	/**
	 * Return the contents of the POST with no decoding. Use when you need to
	 * know exactly what was sent, e.g. for an OAuth signature over the elements.
	 *
	 * @return String
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
	 * @return String
	 */
	public function getRawInput() {
		static $input = false;
		if ( $input === false ) {
			$input = file_get_contents( 'php://input' );
		}
		return $input;
	}

	/**
	 * Get the HTTP method used for this request.
	 *
	 * @return String
	 */
	public function getMethod() {
		return isset( $_SERVER['REQUEST_METHOD'] ) ? $_SERVER['REQUEST_METHOD'] : 'GET';
	}

	/**
	 * Returns true if the present request was reached by a POST operation,
	 * false otherwise (GET, HEAD, or command-line).
	 *
	 * Note that values retrieved by the object may come from the
	 * GET URL etc even on a POST request.
	 *
	 * @return Boolean
	 */
	public function wasPosted() {
		return $this->getMethod() == 'POST';
	}

	/**
	 * Returns true if there is a session cookie set.
	 * This does not necessarily mean that the user is logged in!
	 *
	 * If you want to check for an open session, use session_id()
	 * instead; that will also tell you if the session was opened
	 * during the current request (in which case the cookie will
	 * be sent back to the client at the end of the script run).
	 *
	 * @return Boolean
	 */
	public function checkSessionCookie() {
		return isset( $_COOKIE[session_name()] );
	}

	/**
	 * Get a cookie from the $_COOKIE jar
	 *
	 * @param string $key the name of the cookie
	 * @param string $prefix a prefix to use for the cookie name, if not $wgCookiePrefix
	 * @param $default Mixed: what to return if the value isn't found
	 * @return Mixed: cookie value or $default if the cookie not set
	 */
	public function getCookie( $key, $prefix = null, $default = null ) {
		if ( $prefix === null ) {
			global $wgCookiePrefix;
			$prefix = $wgCookiePrefix;
		}
		return $this->getGPCVal( $_COOKIE, $prefix . $key, $default );
	}

	/**
	 * Return the path and query string portion of the request URI.
	 * This will be suitable for use as a relative link in HTML output.
	 *
	 * @throws MWException
	 * @return String
	 */
	public function getRequestURL() {
		if ( isset( $_SERVER['REQUEST_URI'] ) && strlen( $_SERVER['REQUEST_URI'] ) ) {
			$base = $_SERVER['REQUEST_URI'];
		} elseif ( isset( $_SERVER['HTTP_X_ORIGINAL_URL'] ) && strlen( $_SERVER['HTTP_X_ORIGINAL_URL'] ) ) {
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
				"of your web server configuration to http://bugzilla.wikimedia.org/" );
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
	 * Return the request URI with the canonical service and hostname, path,
	 * and query string. This will be suitable for use as an absolute link
	 * in HTML or other output.
	 *
	 * If $wgServer is protocol-relative, this will return a fully
	 * qualified URL with the protocol that was used for this request.
	 *
	 * @return String
	 */
	public function getFullRequestURL() {
		return wfExpandUrl( $this->getRequestURL(), PROTO_CURRENT );
	}

	/**
	 * Take an arbitrary query and rewrite the present URL to include it
	 * @param string $query query string fragment; do not include initial '?'
	 *
	 * @return String
	 */
	public function appendQuery( $query ) {
		return $this->appendQueryArray( wfCgiToArray( $query ) );
	}

	/**
	 * HTML-safe version of appendQuery().
	 * @deprecated: Deprecated in 1.20, warnings in 1.21, remove in 1.22.
	 *
	 * @param string $query query string fragment; do not include initial '?'
	 * @return String
	 */
	public function escapeAppendQuery( $query ) {
		return htmlspecialchars( $this->appendQuery( $query ) );
	}

	/**
	 * @param $key
	 * @param $value
	 * @param $onlyquery bool
	 * @return String
	 */
	public function appendQueryValue( $key, $value, $onlyquery = false ) {
		return $this->appendQueryArray( array( $key => $value ), $onlyquery );
	}

	/**
	 * Appends or replaces value of query variables.
	 *
	 * @param array $array of values to replace/add to query
	 * @param bool $onlyquery whether to only return the query string and not
	 *                   the complete URL
	 * @return String
	 */
	public function appendQueryArray( $array, $onlyquery = false ) {
		global $wgTitle;
		$newquery = $this->getQueryValues();
		unset( $newquery['title'] );
		$newquery = array_merge( $newquery, $array );
		$query = wfArrayToCgi( $newquery );
		return $onlyquery ? $query : $wgTitle->getLocalURL( $query );
	}

	/**
	 * Check for limit and offset parameters on the input, and return sensible
	 * defaults if not given. The limit must be positive and is capped at 5000.
	 * Offset must be positive but is not capped.
	 *
	 * @param $deflimit Integer: limit to use if no input and the user hasn't set the option.
	 * @param string $optionname to specify an option other than rclimit to pull from.
	 * @return array first element is limit, second is offset
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

		return array( $limit, $offset );
	}

	/**
	 * Return the path to the temporary file where PHP has stored the upload.
	 *
	 * @param $key String:
	 * @return string or NULL if no such file.
	 */
	public function getFileTempname( $key ) {
		$file = new WebRequestUpload( $this, $key );
		return $file->getTempName();
	}

	/**
	 * Return the upload error or 0
	 *
	 * @param $key String:
	 * @return integer
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
	 * @param $key String:
	 * @return string or NULL if no such file.
	 */
	public function getFileName( $key ) {
		$file = new WebRequestUpload( $this, $key );
		return $file->getName();
	}

	/**
	 * Return a WebRequestUpload object corresponding to the key
	 *
	 * @param $key string
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
			$class = ( $this instanceof FauxRequest ) ? 'FauxResponse' : 'WebResponse';
			$this->response = new $class();
		}
		return $this->response;
	}

	/**
	 * Initialise the header list
	 */
	private function initHeaders() {
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
	 * @return Array mapping header name to its value
	 */
	public function getAllHeaders() {
		$this->initHeaders();
		return $this->headers;
	}

	/**
	 * Get a request header, or false if it isn't set
	 * @param string $name case-insensitive header name
	 *
	 * @return string|bool False on failure
	 */
	public function getHeader( $name ) {
		$this->initHeaders();
		$name = strtoupper( $name );
		if ( isset( $this->headers[$name] ) ) {
			return $this->headers[$name];
		} else {
			return false;
		}
	}

	/**
	 * Get data from $_SESSION
	 *
	 * @param string $key name of key in $_SESSION
	 * @return Mixed
	 */
	public function getSessionData( $key ) {
		if ( !isset( $_SESSION[$key] ) ) {
			return null;
		}
		return $_SESSION[$key];
	}

	/**
	 * Set session data
	 *
	 * @param string $key name of key in $_SESSION
	 * @param $data Mixed
	 */
	public function setSessionData( $key, $data ) {
		$_SESSION[$key] = $data;
	}

	/**
	 * Check if Internet Explorer will detect an incorrect cache extension in
	 * PATH_INFO or QUERY_STRING. If the request can't be allowed, show an error
	 * message or redirect to a safer URL. Returns true if the URL is OK, and
	 * false if an error message has been shown and the request should be aborted.
	 *
	 * @param $extWhitelist array
	 * @throws HttpError
	 * @return bool
	 */
	public function checkUrlExtension( $extWhitelist = array() ) {
		global $wgScriptExtension;
		$extWhitelist[] = ltrim( $wgScriptExtension, '.' );
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
	 * @param $url string
	 * @return bool
	 */
	protected function doSecurityRedirect( $url ) {
		header( 'Location: ' . $url );
		header( 'Content-Type: text/html' );
		$encUrl = htmlspecialchars( $url );
		echo <<<HTML
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
<p>Instead, please use <a href="$encUrl">this URL</a>, which is the same as the URL you have requested, except that
"&amp;*" is appended. This prevents Internet Explorer from seeing a bogus file
extension.
</p>
</body>
</html>
HTML;
		echo "\n";
		return true;
	}

	/**
	 * Parse the Accept-Language header sent by the client into an array
	 * @return array array( languageCode => q-value ) sorted by q-value in descending order then
	 *                                                appearing time in the header in ascending order.
	 * May contain the "language" '*', which applies to languages other than those explicitly listed.
	 * This is aligned with rfc2616 section 14.4
	 * Preference for earlier languages appears in rfc3282 as an extension to HTTP/1.1.
	 */
	public function getAcceptLang() {
		// Modified version of code found at http://www.thefutureoftheweb.com/blog/use-accept-language-header
		$acceptLang = $this->getHeader( 'Accept-Language' );
		if ( !$acceptLang ) {
			return array();
		}

		// Return the language codes in lower case
		$acceptLang = strtolower( $acceptLang );

		// Break up string into pieces (languages and q factors)
		$lang_parse = null;
		preg_match_all( '/([a-z]{1,8}(-[a-z]{1,8})*|\*)\s*(;\s*q\s*=\s*(1(\.0{0,3})?|0(\.[0-9]{0,3})?)?)?/',
			$acceptLang, $lang_parse );

		if ( !count( $lang_parse[1] ) ) {
			return array();
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
	 * @return String
	 */
	protected function getRawIP() {
		if ( !isset( $_SERVER['REMOTE_ADDR'] ) ) {
			return null;
		}

		if ( is_array( $_SERVER['REMOTE_ADDR'] ) || strpos( $_SERVER['REMOTE_ADDR'], ',' ) !== false ) {
			throw new MWException( __METHOD__ . " : Could not determine the remote IP address due to multiple values." );
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

		# Append XFF
		$forwardedFor = $this->getHeader( 'X-Forwarded-For' );
		if ( $forwardedFor !== false ) {
			$ipchain = array_map( 'trim', explode( ',', $forwardedFor ) );
			$ipchain = array_reverse( $ipchain );
			if ( $ip ) {
				array_unshift( $ipchain, $ip );
			}

			# Step through XFF list and find the last address in the list which is a
			# trusted server. Set $ip to the IP address given by that trusted server,
			# unless the address is not sensible (e.g. private). However, prefer private
			# IP addresses over proxy servers controlled by this site (more sensible).
			foreach ( $ipchain as $i => $curIP ) {
				// ignore 'unknown' value from Squid when 'forwarded_for off' and try next
				if ( $curIP === 'unknown' ) {
					continue;
				}
				$curIP = IP::sanitizeIP( IP::canonicalize( $curIP ) );
				if ( wfIsTrustedProxy( $curIP ) && isset( $ipchain[$i + 1] ) ) {
					if ( wfIsConfiguredProxy( $curIP ) || // bug 48919; treat IP as sane
						IP::isPublic( $ipchain[$i + 1] ) ||
						$wgUsePrivateIPs
					) {
						$nextIP = IP::canonicalize( $ipchain[$i + 1] );
						if ( !$nextIP && wfIsConfiguredProxy( $ip ) ) {
							// We have not yet made it past CDN/proxy servers of this site,
							// so either they are misconfigured or there is some IP spoofing.
							throw new MWException( "Invalid IP given in XFF '$forwardedFor'." );
						}
						$ip = $nextIP;
						continue;
					}
				}
				break;
			}
		}

		# Allow extensions to improve our guess
		wfRunHooks( 'GetIP', array( &$ip ) );

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
}

/**
 * Object to access the $_FILES array
 */
class WebRequestUpload {
	protected $request;
	protected $doesExist;
	protected $fileInfo;

	/**
	 * Constructor. Should only be called by WebRequest
	 *
	 * @param $request WebRequest The associated request
	 * @param string $key Key in $_FILES array (name of form field)
	 */
	public function __construct( $request, $key ) {
		$this->request = $request;
		$this->doesExist = isset( $_FILES[$key] );
		if ( $this->doesExist ) {
			$this->fileInfo = $_FILES[$key];
		}
	}

	/**
	 * Return whether a file with this name was uploaded.
	 *
	 * @return bool
	 */
	public function exists() {
		return $this->doesExist;
	}

	/**
	 * Return the original filename of the uploaded file
	 *
	 * @return mixed Filename or null if non-existent
	 */
	public function getName() {
		if ( !$this->exists() ) {
			return null;
		}

		global $wgContLang;
		$name = $this->fileInfo['name'];

		# Safari sends filenames in HTML-encoded Unicode form D...
		# Horrid and evil! Let's try to make some kind of sense of it.
		$name = Sanitizer::decodeCharReferences( $name );
		$name = $wgContLang->normalize( $name );
		wfDebug( __METHOD__ . ": {$this->fileInfo['name']} normalized to '$name'\n" );
		return $name;
	}

	/**
	 * Return the file size of the uploaded file
	 *
	 * @return int File size or zero if non-existent
	 */
	public function getSize() {
		if ( !$this->exists() ) {
			return 0;
		}

		return $this->fileInfo['size'];
	}

	/**
	 * Return the path to the temporary file
	 *
	 * @return mixed Path or null if non-existent
	 */
	public function getTempName() {
		if ( !$this->exists() ) {
			return null;
		}

		return $this->fileInfo['tmp_name'];
	}

	/**
	 * Return the upload error. See link for explanation
	 * http://www.php.net/manual/en/features.file-upload.errors.php
	 *
	 * @return int One of the UPLOAD_ constants, 0 if non-existent
	 */
	public function getError() {
		if ( !$this->exists() ) {
			return 0; # UPLOAD_ERR_OK
		}

		return $this->fileInfo['error'];
	}

	/**
	 * Returns whether this upload failed because of overflow of a maximum set
	 * in php.ini
	 *
	 * @return bool
	 */
	public function isIniSizeOverflow() {
		if ( $this->getError() == UPLOAD_ERR_INI_SIZE ) {
			# PHP indicated that upload_max_filesize is exceeded
			return true;
		}

		$contentLength = $this->request->getHeader( 'CONTENT_LENGTH' );
		if ( $contentLength > wfShorthandToInteger( ini_get( 'post_max_size' ) ) ) {
			# post_max_size is exceeded
			return true;
		}

		return false;
	}
}

/**
 * WebRequest clone which takes values from a provided array.
 *
 * @ingroup HTTP
 */
class FauxRequest extends WebRequest {
	private $wasPosted = false;
	private $session = array();
	private $requestUrl;

	/**
	 * @param array $data of *non*-urlencoded key => value pairs, the
	 *   fake GET/POST values
	 * @param bool $wasPosted whether to treat the data as POST
	 * @param $session Mixed: session array or null
	 * @param string $protocol 'http' or 'https'
	 * @throws MWException
	 */
	public function __construct( $data = array(), $wasPosted = false, $session = null, $protocol = 'http' ) {
		if ( is_array( $data ) ) {
			$this->data = $data;
		} else {
			throw new MWException( "FauxRequest() got bogus data" );
		}
		$this->wasPosted = $wasPosted;
		if ( $session ) {
			$this->session = $session;
		}
		$this->protocol = $protocol;
	}

	/**
	 * @param $method string
	 * @throws MWException
	 */
	private function notImplemented( $method ) {
		throw new MWException( "{$method}() not implemented" );
	}

	/**
	 * @param $name string
	 * @param $default string
	 * @return string
	 */
	public function getText( $name, $default = '' ) {
		# Override; don't recode since we're using internal data
		return (string)$this->getVal( $name, $default );
	}

	/**
	 * @return Array
	 */
	public function getValues() {
		return $this->data;
	}

	/**
	 * @return array
	 */
	public function getQueryValues() {
		if ( $this->wasPosted ) {
			return array();
		} else {
			return $this->data;
		}
	}

	public function getMethod() {
		return $this->wasPosted ? 'POST' : 'GET';
	}

	/**
	 * @return bool
	 */
	public function wasPosted() {
		return $this->wasPosted;
	}

	public function getCookie( $key, $prefix = null, $default = null ) {
		return $default;
	}

	public function checkSessionCookie() {
		return false;
	}

	public function setRequestURL( $url ) {
		$this->requestUrl = $url;
	}

	public function getRequestURL() {
		if ( $this->requestUrl === null ) {
			throw new MWException( 'Request URL not set' );
		}
		return $this->requestUrl;
	}

	public function getProtocol() {
		return $this->protocol;
	}

	/**
	 * @param string $name The name of the header to get (case insensitive).
	 * @return bool|string
	 */
	public function getHeader( $name ) {
		$name = strtoupper( $name );
		return isset( $this->headers[$name] ) ? $this->headers[$name] : false;
	}

	/**
	 * @param $name string
	 * @param $val string
	 */
	public function setHeader( $name, $val ) {
		$name = strtoupper( $name );
		$this->headers[$name] = $val;
	}

	/**
	 * @param $key
	 * @return mixed
	 */
	public function getSessionData( $key ) {
		if ( isset( $this->session[$key] ) ) {
			return $this->session[$key];
		}
		return null;
	}

	/**
	 * @param $key
	 * @param $data
	 */
	public function setSessionData( $key, $data ) {
		$this->session[$key] = $data;
	}

	/**
	 * @return array|Mixed|null
	 */
	public function getSessionArray() {
		return $this->session;
	}

	/**
	 * FauxRequests shouldn't depend on raw request data (but that could be implemented here)
	 * @return String
	 */
	public function getRawQueryString() {
		return '';
	}

	/**
	 * FauxRequests shouldn't depend on raw request data (but that could be implemented here)
	 * @return String
	 */
	public function getRawPostString() {
		return '';
	}

	/**
	 * FauxRequests shouldn't depend on raw request data (but that could be implemented here)
	 * @return String
	 */
	public function getRawInput() {
		return '';
	}

	/**
	 * @param array $extWhitelist
	 * @return bool
	 */
	public function checkUrlExtension( $extWhitelist = array() ) {
		return true;
	}

	/**
	 * @return string
	 */
	protected function getRawIP() {
		return '127.0.0.1';
	}
}

/**
 * Similar to FauxRequest, but only fakes URL parameters and method
 * (POST or GET) and use the base request for the remaining stuff
 * (cookies, session and headers).
 *
 * @ingroup HTTP
 * @since 1.19
 */
class DerivativeRequest extends FauxRequest {
	private $base;

	/**
	 * @param WebRequest $base
	 * @param array $data Array of *non*-urlencoded key => value pairs, the
	 *   fake GET/POST values
	 * @param bool $wasPosted Whether to treat the data as POST
	 */
	public function __construct( WebRequest $base, $data, $wasPosted = false ) {
		$this->base = $base;
		parent::__construct( $data, $wasPosted );
	}

	public function getCookie( $key, $prefix = null, $default = null ) {
		return $this->base->getCookie( $key, $prefix, $default );
	}

	public function checkSessionCookie() {
		return $this->base->checkSessionCookie();
	}

	public function getHeader( $name ) {
		return $this->base->getHeader( $name );
	}

	public function getAllHeaders() {
		return $this->base->getAllHeaders();
	}

	public function getSessionData( $key ) {
		return $this->base->getSessionData( $key );
	}

	public function setSessionData( $key, $data ) {
		$this->base->setSessionData( $key, $data );
	}

	public function getAcceptLang() {
		return $this->base->getAcceptLang();
	}

	public function getIP() {
		return $this->base->getIP();
	}

	public function getProtocol() {
		return $this->base->getProtocol();
	}
}
