<?php
/**
 * Deal with importing all those nasssty globals and things
 *
 * Copyright © 2003 Brion Vibber <brion@pobox.com>
 * http://www.mediawiki.org/
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
	 * Extract the PATH_INFO variable even when it isn't a reasonable
	 * value. On some large webhosts, PATH_INFO includes the script
	 * path as well as everything after it.
	 *
	 * @param $want string: If this is not 'all', then the function
	 * will return an empty array if it determines that the URL is
	 * inside a rewrite path.
	 *
	 * @return Array: 'title' key is the title of the article.
	 */
	static public function getPathInfo( $want = 'all' ) {
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
			$a = parse_url( $url );
			if( $a ) {
				$path = isset( $a['path'] ) ? $a['path'] : '';

				global $wgScript;
				if( $path == $wgScript && $want !== 'all' ) {
					// Script inside a rewrite path?
					// Abort to keep from breaking...
					return $matches;
				}
				// Raw PATH_INFO style
				$matches = self::extractTitle( $path, "$wgScript/$1" );

				global $wgArticlePath;
				if( !$matches && $wgArticlePath ) {
					$matches = self::extractTitle( $path, $wgArticlePath );
				}

				global $wgActionPaths;
				if( !$matches && $wgActionPaths ) {
					$matches = self::extractTitle( $path, $wgActionPaths, 'action' );
				}

				global $wgVariantArticlePath, $wgContLang;
				if( !$matches && $wgVariantArticlePath ) {
					$variantPaths = array();
					foreach( $wgContLang->getVariants() as $variant ) {
						$variantPaths[$variant] =
							str_replace( '$2', $variant, $wgVariantArticlePath );
					}
					$matches = self::extractTitle( $path, $variantPaths, 'variant' );
				}
			}
		} elseif ( isset( $_SERVER['ORIG_PATH_INFO'] ) && $_SERVER['ORIG_PATH_INFO'] != '' ) {
			// Mangled PATH_INFO
			// http://bugs.php.net/bug.php?id=31892
			// Also reported when ini_get('cgi.fix_pathinfo')==false
			$matches['title'] = substr( $_SERVER['ORIG_PATH_INFO'], 1 );

		} elseif ( isset( $_SERVER['PATH_INFO'] ) && ($_SERVER['PATH_INFO'] != '') ) {
			// Regular old PATH_INFO yay
			$matches['title'] = substr( $_SERVER['PATH_INFO'], 1 );
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
		if ( isset( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] == 'on') {
			$proto = 'https';
			$stdPort = 443;
		} else {
			$proto = 'http';
			$stdPort = 80;
		}

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
	 * Check for title, action, and/or variant data in the URL
	 * and interpolate it into the GET variables.
	 * This should only be run after $wgContLang is available,
	 * as we may need the list of language variants to determine
	 * available variant URLs.
	 */
	public function interpolateTitle() {
		global $wgUsePathInfo;

		// bug 16019: title interpolation on API queries is useless and sometimes harmful
		if ( defined( 'MW_API' ) ) {
			return;
		}

		if ( $wgUsePathInfo ) {
			$matches = self::getPathInfo( 'title' );
			foreach( $matches as $key => $val) {
				$this->data[$key] = $_GET[$key] = $_REQUEST[$key] = $val;
			}
		}
	}

	/**
	 * Internal URL rewriting function; tries to extract page title and,
	 * optionally, one other fixed parameter value from a URL path.
	 *
	 * @param $path string: the URL path given from the client
	 * @param $bases array: one or more URLs, optionally with $1 at the end
	 * @param $key string: if provided, the matching key in $bases will be
	 *             passed on as the value of this URL parameter
	 * @return array of URL variables to interpolate; empty if no match
	 */
	private static function extractTitle( $path, $bases, $key = false ) {
		foreach( (array)$bases as $keyValue => $base ) {
			// Find the part after $wgArticlePath
			$base = str_replace( '$1', '', $base );
			$baseLen = strlen( $base );
			if( substr( $path, 0, $baseLen ) == $base ) {
				$raw = substr( $path, $baseLen );
				if( $raw !== '' ) {
					$matches = array( 'title' => rawurldecode( $raw ) );
					if( $key ) {
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
	 * @param $arr array: will be modified
	 * @return array the original array
	 */
	private function &fix_magic_quotes( &$arr ) {
		foreach( $arr as $key => $val ) {
			if( is_array( $val ) ) {
				$this->fix_magic_quotes( $arr[$key] );
			} else {
				$arr[$key] = stripslashes( $val );
			}
		}
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
		if( $mustFixQuotes ) {
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
	 * @param $data string or array
	 * @return cleaned-up version of the given
	 * @private
	 */
	function normalizeUnicode( $data ) {
		if( is_array( $data ) ) {
			foreach( $data as $key => $val ) {
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
		if( isset( $arr[$name] ) ) {
			global $wgContLang;
			$data = $arr[$name];
			if( isset( $_GET[$name] ) && !is_array( $data ) ) {
				# Check for alternate/legacy character encoding.
				if( isset( $wgContLang ) ) {
					$data = $wgContLang->checkTitleEncoding( $data );
				}
			}
			$data = $this->normalizeUnicode( $data );
			return $data;
		} else {
			taint( $default );
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
	 * @param $default String: optional default (or NULL)
	 * @return String
	 */
	public function getVal( $name, $default = null ) {
		$val = $this->getGPCVal( $this->data, $name, $default );
		if( is_array( $val ) ) {
			$val = $default;
		}
		if( is_null( $val ) ) {
			return $val;
		} else {
			return (string)$val;
		}
	}

	/**
	 * Set an arbitrary value into our get/post data.
	 *
	 * @param $key String: key name to use
	 * @param $value Mixed: value to set
	 * @return Mixed: old value if one was present, null otherwise
	 */
	public function setVal( $key, $value ) {
		$ret = isset( $this->data[$key] ) ? $this->data[$key] : null;
		$this->data[$key] = $value;
		return $ret;
	}

	/**
	 * Fetch an array from the input or return $default if it's not set.
	 * If source was scalar, will return an array with a single element.
	 * If no source and no default, returns NULL.
	 *
	 * @param $name String
	 * @param $default Array: optional default (or NULL)
	 * @return Array
	 */
	public function getArray( $name, $default = null ) {
		$val = $this->getGPCVal( $this->data, $name, $default );
		if( is_null( $val ) ) {
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
	 * @param $default Array: option default (or NULL)
	 * @return Array of ints
	 */
	public function getIntArray( $name, $default = null ) {
		$val = $this->getArray( $name, $default );
		if( is_array( $val ) ) {
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
		# Presence connotes truth, abscense false
		$val = $this->getVal( $name, null );
		return isset( $val );
	}

	/**
	 * Fetch a text string from the given array or return $default if it's not
	 * set. Carriage returns are stripped from the text, and with some language
	 * modules there is an input transliteration applied. This should generally
	 * be used for form <textarea> and <input> fields. Used for user-supplied
	 * freeform text input (for which input transformations may be required - e.g.
	 * Esperanto x-coding).
	 *
	 * @param $name String
	 * @param $default String: optional
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
			$value = $this->getVal( $name );
			if ( !is_null( $value ) ) {
				$retVal[$name] = $value;
			}
		}
		return $retVal;
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
	 * Returns true if the present request was reached by a POST operation,
	 * false otherwise (GET, HEAD, or command-line).
	 *
	 * Note that values retrieved by the object may come from the
	 * GET URL etc even on a POST request.
	 *
	 * @return Boolean
	 */
	public function wasPosted() {
		return $_SERVER['REQUEST_METHOD'] == 'POST';
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
		return isset( $_COOKIE[ session_name() ] );
	}

	/**
	 * Get a cookie from the $_COOKIE jar
	 *
	 * @param $key String: the name of the cookie
	 * @param $prefix String: a prefix to use for the cookie name, if not $wgCookiePrefix
	 * @param $default Mixed: what to return if the value isn't found
	 * @return Mixed: cookie value or $default if the cookie not set
	 */
	public function getCookie( $key, $prefix = null, $default = null ) {
		if( $prefix === null ) {
			global $wgCookiePrefix;
			$prefix = $wgCookiePrefix;
		}
		return $this->getGPCVal( $_COOKIE, $prefix . $key , $default );
	}

	/**
	 * Return the path and query string portion of the request URI.
	 * This will be suitable for use as a relative link in HTML output.
	 *
	 * @return String
	 */
	public function getRequestURL() {
		if( isset( $_SERVER['REQUEST_URI'] ) && strlen( $_SERVER['REQUEST_URI'] ) ) {
			$base = $_SERVER['REQUEST_URI'];
		} elseif ( isset( $_SERVER['HTTP_X_ORIGINAL_URL'] ) && strlen( $_SERVER['HTTP_X_ORIGINAL_URL'] ) ) {
			// Probably IIS; doesn't set REQUEST_URI
			$base = $_SERVER['HTTP_X_ORIGINAL_URL'];
		} elseif( isset( $_SERVER['SCRIPT_NAME'] ) ) {
			$base = $_SERVER['SCRIPT_NAME'];
			if( isset( $_SERVER['QUERY_STRING'] ) && $_SERVER['QUERY_STRING'] != '' ) {
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
		if( $hash !== false ) {
			$base = substr( $base, 0, $hash );
		}
		if( $base[0] == '/' ) {
			return $base;
		} else {
			// We may get paths with a host prepended; strip it.
			return preg_replace( '!^[^:]+://[^/]+/!', '/', $base );
		}
	}

	/**
	 * Return the request URI with the canonical service and hostname, path,
	 * and query string. This will be suitable for use as an absolute link
	 * in HTML or other output.
	 *
	 * @return String
	 */
	public function getFullRequestURL() {
		global $wgServer;
		return $wgServer . $this->getRequestURL();
	}

	/**
	 * Take an arbitrary query and rewrite the present URL to include it
	 * @param $query String: query string fragment; do not include initial '?'
	 *
	 * @return String
	 */
	public function appendQuery( $query ) {
		return $this->appendQueryArray( wfCgiToArray( $query ) );
	}

	/**
	 * HTML-safe version of appendQuery().
	 *
	 * @param $query String: query string fragment; do not include initial '?'
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
	 * @param $array Array of values to replace/add to query
	 * @param $onlyquery Bool: whether to only return the query string and not
	 *                   the complete URL
	 * @return String
	 */
	public function appendQueryArray( $array, $onlyquery = false ) {
		global $wgTitle;
		$newquery = $this->getQueryValues();
		unset( $newquery['title'] );
		$newquery = array_merge( $newquery, $array );
		$query = wfArrayToCGI( $newquery );
		return $onlyquery ? $query : $wgTitle->getLocalURL( $query );
	}

	/**
	 * Check for limit and offset parameters on the input, and return sensible
	 * defaults if not given. The limit must be positive and is capped at 5000.
	 * Offset must be positive but is not capped.
	 *
	 * @param $deflimit Integer: limit to use if no input and the user hasn't set the option.
	 * @param $optionname String: to specify an option other than rclimit to pull from.
	 * @return array first element is limit, second is offset
	 */
	public function getLimitOffset( $deflimit = 50, $optionname = 'rclimit' ) {
		global $wgUser;

		$limit = $this->getInt( 'limit', 0 );
		if( $limit < 0 ) {
			$limit = 0;
		}
		if( ( $limit == 0 ) && ( $optionname != '' ) ) {
			$limit = (int)$wgUser->getOption( $optionname );
		}
		if( $limit <= 0 ) {
			$limit = $deflimit;
		}
		if( $limit > 5000 ) {
			$limit = 5000; # We have *some* limits...
		}

		$offset = $this->getInt( 'offset', 0 );
		if( $offset < 0 ) {
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
	 * Return the size of the upload, or 0.
	 *
	 * @deprecated since 1.17
	 * @param $key String:
	 * @return integer
	 */
	public function getFileSize( $key ) {
		$file = new WebRequestUpload( $this, $key );
		return $file->getSize();
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

		if ( function_exists( 'apache_request_headers' ) ) {
			foreach ( apache_request_headers() as $tempName => $tempValue ) {
				$this->headers[ strtoupper( $tempName ) ] = $tempValue;
			}
		} else {
			foreach ( $_SERVER as $name => $value ) {
				if ( substr( $name, 0, 5 ) === 'HTTP_' ) {
					$name = str_replace( '_', '-',  substr( $name, 5 ) );
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
	 * @param $name String: case-insensitive header name
	 *
	 * @return string|false
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
	 * @param $key String: name of key in $_SESSION
	 * @return Mixed
	 */
	public function getSessionData( $key ) {
		if( !isset( $_SESSION[$key] ) ) {
			return null;
		}
		return $_SESSION[$key];
	}

	/**
	 * Set session data
	 *
	 * @param $key String: name of key in $_SESSION
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
			wfHttpError( 403, 'Forbidden',
				'Invalid file extension found in the path info or query string.' );

			return false;
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
	 * Returns true if the PATH_INFO ends with an extension other than a script
	 * extension. This could confuse IE for scripts that send arbitrary data which
	 * is not HTML but may be detected as such.
	 *
	 * Various past attempts to use the URL to make this check have generally
	 * run up against the fact that CGI does not provide a standard method to
	 * determine the URL. PATH_INFO may be mangled (e.g. if cgi.fix_pathinfo=0),
	 * but only by prefixing it with the script name and maybe some other stuff,
	 * the extension is not mangled. So this should be a reasonably portable
	 * way to perform this security check.
	 *
	 * Also checks for anything that looks like a file extension at the end of
	 * QUERY_STRING, since IE 6 and earlier will use this to get the file type
	 * if there was no dot before the question mark (bug 28235).
	 *
	 * @deprecated Use checkUrlExtension().
	 */
	public function isPathInfoBad( $extWhitelist = array() ) {
		global $wgScriptExtension;
		$extWhitelist[] = ltrim( $wgScriptExtension, '.' );
		return IEUrlExtension::areServerVarsBad( $_SERVER, $extWhitelist );
	}

	/**
	 * Parse the Accept-Language header sent by the client into an array
	 * @return array( languageCode => q-value ) sorted by q-value in descending order
	 * May contain the "language" '*', which applies to languages other than those explicitly listed.
	 * This is aligned with rfc2616 section 14.4
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
		preg_match_all( '/([a-z]{1,8}(-[a-z]{1,8})?|\*)\s*(;\s*q\s*=\s*(1|0(\.[0-9]+)?)?)?/',
			$acceptLang, $lang_parse );

		if ( !count( $lang_parse[1] ) ) {
			return array();
		}

		// Create a list like "en" => 0.8
		$langs = array_combine( $lang_parse[1], $lang_parse[4] );
		// Set default q factor to 1
		foreach ( $langs as $lang => $val ) {
			if ( $val === '' ) {
				$langs[$lang] = 1;
			} elseif ( $val == 0 ) {
				unset($langs[$lang]);
			}
		}

		// Sort list
		arsort( $langs, SORT_NUMERIC );
		return $langs;
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
	 * @param $key string Key in $_FILES array (name of form field)
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

	/**
	 * @param $data Array of *non*-urlencoded key => value pairs, the
	 *   fake GET/POST values
	 * @param $wasPosted Bool: whether to treat the data as POST
	 * @param $session Mixed: session array or null
	 */
	public function __construct( $data, $wasPosted = false, $session = null ) {
		if( is_array( $data ) ) {
			$this->data = $data;
		} else {
			throw new MWException( "FauxRequest() got bogus data" );
		}
		$this->wasPosted = $wasPosted;
		if( $session )
			$this->session = $session;
	}

	private function notImplemented( $method ) {
		throw new MWException( "{$method}() not implemented" );
	}

	public function getText( $name, $default = '' ) {
		# Override; don't recode since we're using internal data
		return (string)$this->getVal( $name, $default );
	}

	public function getValues() {
		return $this->data;
	}

	public function getQueryValues() {
		if ( $this->wasPosted ) {
			return array();
		} else {
			return $this->data;
		}
	}

	public function wasPosted() {
		return $this->wasPosted;
	}

	public function checkSessionCookie() {
		return false;
	}

	public function getRequestURL() {
		$this->notImplemented( __METHOD__ );
	}

	public function getHeader( $name ) {
		return isset( $this->headers[$name] ) ? $this->headers[$name] : false;
	}

	public function setHeader( $name, $val ) {
		$this->headers[$name] = $val;
	}

	public function getSessionData( $key ) {
		if( isset( $this->session[$key] ) )
			return $this->session[$key];
	}

	public function setSessionData( $key, $data ) {
		$this->session[$key] = $data;
	}

	public function getSessionArray() {
		return $this->session;
	}

	public function isPathInfoBad( $extWhitelist = array() ) {
		return false;
	}

	public function checkUrlExtension( $extWhitelist = array() ) {
		return true;
	}
}
