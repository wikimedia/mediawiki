<?php
/**
 * Deal with importing all those nasssty globals and things
 */

# Copyright (C) 2003 Brion Vibber <brion@pobox.com>
# http://www.mediawiki.org/
#
# This program is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License, or
# (at your option) any later version.
#
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License along
# with this program; if not, write to the Free Software Foundation, Inc.,
# 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
# http://www.gnu.org/copyleft/gpl.html


/**
 * Some entry points may use this file without first enabling the
 * autoloader.
 */
if ( !function_exists( '__autoload' ) ) {
	require_once( dirname(__FILE__) . '/normal/UtfNormal.php' );
}

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
	private $_response;

	public function __construct() {
		/// @todo Fixme: this preemptive de-quoting can interfere with other web libraries
		///        and increases our memory footprint. It would be cleaner to do on
		///        demand; but currently we have no wrapper for $_SERVER etc.
		$this->checkMagicQuotes();

		// POST overrides GET data
		// We don't use $_REQUEST here to avoid interference from cookies...
		$this->data = $_POST + $_GET;
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

		if ( $wgUsePathInfo ) {
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
					if( $path == $wgScript ) {
						// Script inside a rewrite path?
						// Abort to keep from breaking...
						return;
					}
					// Raw PATH_INFO style
					$matches = $this->extractTitle( $path, "$wgScript/$1" );

					global $wgArticlePath;
					if( !$matches && $wgArticlePath ) {
						$matches = $this->extractTitle( $path, $wgArticlePath );
					}

					global $wgActionPaths;
					if( !$matches && $wgActionPaths ) {
						$matches = $this->extractTitle( $path, $wgActionPaths, 'action' );
					}

					global $wgVariantArticlePath, $wgContLang;
					if( !$matches && $wgVariantArticlePath ) {
						$variantPaths = array();
						foreach( $wgContLang->getVariants() as $variant ) {
							$variantPaths[$variant] =
								str_replace( '$2', $variant, $wgVariantArticlePath );
						}
						$matches = $this->extractTitle( $path, $variantPaths, 'variant' );
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
	private function extractTitle( $path, $bases, $key=false ) {
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
			$data = $wgContLang->normalize( $data );
		}
		return $data;
	}

	/**
	 * Fetch a value from the given array or return $default if it's not set.
	 *
	 * @param $arr array
	 * @param $name string
	 * @param $default mixed
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
	 * @param $name string
	 * @param $default string: optional default (or NULL)
	 * @return string
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
	 * Set an aribtrary value into our get/post data.
	 * @param $key string Key name to use
	 * @param $value mixed Value to set
	 * @return mixed old value if one was present, null otherwise
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
	 * @param $name string
	 * @param $default array: optional default (or NULL)
	 * @return array
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
	 * @param $name string
	 * @param $default array: option default (or NULL)
	 * @return array of ints
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
	 * @param $name string
	 * @param $default int
	 * @return int
	 */
	public function getInt( $name, $default = 0 ) {
		return intval( $this->getVal( $name, $default ) );
	}

	/**
	 * Fetch an integer value from the input or return null if empty.
	 * Guaranteed to return an integer or null; non-numeric input will
	 * typically return null.
	 * @param $name string
	 * @return int
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
	 * @param $name string
	 * @param $default bool
	 * @return bool
	 */
	public function getBool( $name, $default = false ) {
		return $this->getVal( $name, $default ) ? true : false;
	}

	/**
	 * Return true if the named value is set in the input, whatever that
	 * value is (even "0"). Return false if the named value is not set.
	 * Example use is checking for the presence of check boxes in forms.
	 * @param $name string
	 * @return bool
	 */
	public function getCheck( $name ) {
		# Checkboxes and buttons are only present when clicked
		# Presence connotes truth, abscense false
		$val = $this->getVal( $name, null );
		return isset( $val );
	}

	/**
	 * Fetch a text string from the given array or return $default if it's not
	 * set. \r is stripped from the text, and with some language modules there
	 * is an input transliteration applied. This should generally be used for
	 * form <textarea> and <input> fields. Used for user-supplied freeform text
	 * input (for which input transformations may be required - e.g. Esperanto
	 * x-coding).
	 *
	 * @param $name string
	 * @param $default string: optional
	 * @return string
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
	 * Returns true if the present request was reached by a POST operation,
	 * false otherwise (GET, HEAD, or command-line).
	 *
	 * Note that values retrieved by the object may come from the
	 * GET URL etc even on a POST request.
	 *
	 * @return bool
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
	 * @return bool
	 */
	public function checkSessionCookie() {
		return isset( $_COOKIE[session_name()] );
	}

	/**
	 * Return the path portion of the request URI.
	 * @return string
	 */
	public function getRequestURL() {
		if( isset( $_SERVER['REQUEST_URI']) && strlen($_SERVER['REQUEST_URI']) ) {
			$base = $_SERVER['REQUEST_URI'];
		} elseif( isset( $_SERVER['SCRIPT_NAME'] ) ) {
			// Probably IIS; doesn't set REQUEST_URI
			$base = $_SERVER['SCRIPT_NAME'];
			if( isset( $_SERVER['QUERY_STRING'] ) && $_SERVER['QUERY_STRING'] != '' ) {
				$base .= '?' . $_SERVER['QUERY_STRING'];
			}
		} else {
			// This shouldn't happen!
			throw new MWException( "Web server doesn't provide either " .
				"REQUEST_URI or SCRIPT_NAME. Report details of your " .
				"web server configuration to http://bugzilla.wikimedia.org/" );
		}
		// User-agents should not send a fragment with the URI, but
		// if they do, and the web server passes it on to us, we
		// need to strip it or we get false-positive redirect loops
		// or weird output URLs
		$hash = strpos( $base, '#' );
		if( $hash !== false ) {
			$base = substr( $base, 0, $hash );
		}
		if( $base{0} == '/' ) {
			return $base;
		} else {
			// We may get paths with a host prepended; strip it.
			return preg_replace( '!^[^:]+://[^/]+/!', '/', $base );
		}
	}

	/**
	 * Return the request URI with the canonical service and hostname.
	 * @return string
	 */
	public function getFullRequestURL() {
		global $wgServer;
		return $wgServer . $this->getRequestURL();
	}

	/**
	 * Take an arbitrary query and rewrite the present URL to include it
	 * @param $query String: query string fragment; do not include initial '?'
	 * @return string
	 */
	public function appendQuery( $query ) {
		global $wgTitle;
		$basequery = '';
		foreach( $_GET as $var => $val ) {
			if ( $var == 'title' )
				continue;
			if ( is_array( $val ) )
				/* This will happen given a request like
				 * http://en.wikipedia.org/w/index.php?title[]=Special:Userlogin&returnto[]=Main_Page
				 */
				continue;
			$basequery .= '&' . urlencode( $var ) . '=' . urlencode( $val );
		}
		$basequery .= '&' . $query;

		# Trim the extra &
		$basequery = substr( $basequery, 1 );
		return $wgTitle->getLocalURL( $basequery );
	}

	/**
	 * HTML-safe version of appendQuery().
	 * @param $query String: query string fragment; do not include initial '?'
	 * @return string
	 */
	public function escapeAppendQuery( $query ) {
		return htmlspecialchars( $this->appendQuery( $query ) );
	}

	public function appendQueryValue( $key, $value, $onlyquery = false ) {
		return $this->appendQueryArray( array( $key => $value ), $onlyquery );
	}

	/**
	 * Appends or replaces value of query variables.
	 * @param $array Array of values to replace/add to query
	 * @param $onlyquery Bool: whether to only return the query string and not
	 *                   the complete URL
	 * @return string
	 */
	public function appendQueryArray( $array, $onlyquery = false ) {
		global $wgTitle;
		$newquery = $_GET;
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
		if( $limit < 0 ) $limit = 0;
		if( ( $limit == 0 ) && ( $optionname != '' ) ) {
			$limit = (int)$wgUser->getOption( $optionname );
		}
		if( $limit <= 0 ) $limit = $deflimit;
		if( $limit > 5000 ) $limit = 5000; # We have *some* limits...

		$offset = $this->getInt( 'offset', 0 );
		if( $offset < 0 ) $offset = 0;

		return array( $limit, $offset );
	}

	/**
	 * Return the path to the temporary file where PHP has stored the upload.
	 * @param $key String:
	 * @return string or NULL if no such file.
	 */
	public function getFileTempname( $key ) {
		if( !isset( $_FILES[$key] ) ) {
			return null;
		}
		return $_FILES[$key]['tmp_name'];
	}

	/**
	 * Return the size of the upload, or 0.
	 * @param $key String:
	 * @return integer
	 */
	public function getFileSize( $key ) {
		if( !isset( $_FILES[$key] ) ) {
			return 0;
		}
		return $_FILES[$key]['size'];
	}

	/**
	 * Return the upload error or 0
	 * @param $key String:
	 * @return integer
	 */
	public function getUploadError( $key ) {
		if( !isset( $_FILES[$key] ) || !isset( $_FILES[$key]['error'] ) ) {
			return 0/*UPLOAD_ERR_OK*/;
		}
		return $_FILES[$key]['error'];
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
		global $wgContLang;
		if( !isset( $_FILES[$key] ) ) {
			return null;
		}
		$name = $_FILES[$key]['name'];

		# Safari sends filenames in HTML-encoded Unicode form D...
		# Horrid and evil! Let's try to make some kind of sense of it.
		$name = Sanitizer::decodeCharReferences( $name );
		$name = $wgContLang->normalize( $name );
		wfDebug( "WebRequest::getFileName() '" . $_FILES[$key]['name'] . "' normalized to '$name'\n" );
		return $name;
	}

	/**
	 * Return a handle to WebResponse style object, for setting cookies,
	 * headers and other stuff, for Request being worked on.
	 */
	public function response() {
		/* Lazy initialization of response object for this request */
		if ( !is_object( $this->_response ) ) {
			$class = ( $this instanceof FauxRequest ) ? 'FauxResponse' : 'WebResponse';
			$this->_response = new $class();
		}
		return $this->_response;
	}

	/**
	 * Get a request header, or false if it isn't set
	 * @param $name String: case-insensitive header name
	 */
	public function getHeader( $name ) {
		$name = strtoupper( $name );
		if ( function_exists( 'apache_request_headers' ) ) {
			if ( !$this->headers ) {
				foreach ( apache_request_headers() as $tempName => $tempValue ) {
					$this->headers[ strtoupper( $tempName ) ] = $tempValue;
				}
			}
			if ( isset( $this->headers[$name] ) ) {
				return $this->headers[$name];
			} else {
				return false;
			}
		} else {
			$name = 'HTTP_' . str_replace( '-', '_', $name );
			if ( isset( $_SERVER[$name] ) ) {
				return $_SERVER[$name];
			} else {
				return false;
			}
		}
	}

	/*
	 * Get data from $_SESSION
	 * @param $key String Name of key in $_SESSION
	 * @return mixed
	 */
	public function getSessionData( $key ) {
		if( !isset( $_SESSION[$key] ) )
			return null;
		return $_SESSION[$key];
	}

	/**
	 * Set session data
	 * @param $key String Name of key in $_SESSION
	 * @param $data mixed
	 */
	public function setSessionData( $key, $data ) {
		$_SESSION[$key] = $data;
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
	 */
	public function isPathInfoBad() {
		global $wgScriptExtension;

		if ( !isset( $_SERVER['PATH_INFO'] ) ) {
			return false;
		}
		$pi = $_SERVER['PATH_INFO'];
		$dotPos = strrpos( $pi, '.' );
		if ( $dotPos === false ) {
			return false;
		}
		$ext = substr( $pi, $dotPos );
		return !in_array( $ext, array( $wgScriptExtension, '.php', '.php5' ) );
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
	private $response;

	/**
	 * @param $data Array of *non*-urlencoded key => value pairs, the
	 *   fake GET/POST values
	 * @param $wasPosted Bool: whether to treat the data as POST
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

	public function wasPosted() {
		return $this->wasPosted;
	}

	public function checkSessionCookie() {
		return false;
	}

	public function getRequestURL() {
		$this->notImplemented( __METHOD__ );
	}

	public function appendQuery( $query ) {
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
		$this->notImplemented( __METHOD__ );
	}

	public function isPathInfoBad() {
		return false;
	}
}
