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
 */
class WebRequest {
	function __construct() {
		$this->checkMagicQuotes();
		global $wgUsePathInfo;
		if ( $wgUsePathInfo ) {
			// PATH_INFO is mangled due to http://bugs.php.net/bug.php?id=31892
			// And also by Apache 2.x, double slashes are converted to single slashes.
			// So we will use REQUEST_URI if possible.
			$title = '';
			if ( !empty( $_SERVER['REQUEST_URI'] ) ) {
				global $wgArticlePath, $wgActionPaths;
				$paths["view"] = $wgArticlePath;
				$paths = array_merge( $paths, $wgActionPaths );
				$title = $this->extractActionPaths( $paths );
			} elseif ( isset( $_SERVER['ORIG_PATH_INFO'] ) && $_SERVER['ORIG_PATH_INFO'] != '' ) {
				# Mangled PATH_INFO
				# http://bugs.php.net/bug.php?id=31892
				# Also reported when ini_get('cgi.fix_pathinfo')==false
				$title = substr( $_SERVER['ORIG_PATH_INFO'], 1 );
			} elseif ( isset( $_SERVER['PATH_INFO'] ) && ($_SERVER['PATH_INFO'] != '') && $wgUsePathInfo ) {
				$title = substr( $_SERVER['PATH_INFO'], 1 );
			}
			if ( strval( $title ) != '' ) {
				$_GET['title'] = $_REQUEST['title'] = $title;
			}
		}
	}
	
	private function extractActionPaths( $paths ) {
		$url = $_SERVER['REQUEST_URI'];
		if ( !preg_match( '!^https?://!', $url ) ) {
			$url = 'http://unused' . $url;
		}
		$a = parse_url( $url );
		foreach( $paths as $action => $path ) {
			// Find the part after $wgArticlePath
			$base = str_replace( '$1', '', $path );
			if ( $a && substr( $a['path'], 0, strlen( $base ) ) == $base ) {
				return urldecode( substr( $a['path'], strlen( $base ) ) );
			}
		}
	}
	
	private $_response;

	/**
	 * Recursively strips slashes from the given array;
	 * used for undoing the evil that is magic_quotes_gpc.
	 * @param array &$arr will be modified
	 * @return array the original array
	 * @private
	 */
	function &fix_magic_quotes( &$arr ) {
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
	 * @private
	 */
	function checkMagicQuotes() {
		if ( get_magic_quotes_gpc() ) {
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
	 * @param array $data string or array
	 * @return cleaned-up version of the given
	 * @private
	 */
	function normalizeUnicode( $data ) {
		if( is_array( $data ) ) {
			foreach( $data as $key => $val ) {
				$data[$key] = $this->normalizeUnicode( $val );
			}
		} else {
			$data = UtfNormal::cleanUp( $data );
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
	 * @private
	 */
	function getGPCVal( $arr, $name, $default ) {
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
			return $default;
		}
	}

	/**
	 * Fetch a scalar from the input or return $default if it's not set.
	 * Returns a string. Arrays are discarded. Useful for 
	 * non-freeform text inputs (e.g. predefined internal text keys 
	 * selected by a drop-down menu). For freeform input, see getText().
	 *
	 * @param string $name
	 * @param string $default optional default (or NULL)
	 * @return string
	 */
	function getVal( $name, $default = NULL ) {
		$val = $this->getGPCVal( $_REQUEST, $name, $default );
		if( is_array( $val ) ) {
			$val = $default;
		}
		if( is_null( $val ) ) {
			return null;
		} else {
			return (string)$val;
		}
	}

	/**
	 * Fetch an array from the input or return $default if it's not set.
	 * If source was scalar, will return an array with a single element.
	 * If no source and no default, returns NULL.
	 *
	 * @param string $name
	 * @param array $default optional default (or NULL)
	 * @return array
	 */
	function getArray( $name, $default = NULL ) {
		$val = $this->getGPCVal( $_REQUEST, $name, $default );
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
	 * @param string $name
	 * @param array $default option default (or NULL)
	 * @return array of ints
	 */
	function getIntArray( $name, $default = NULL ) {
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
	 * @param string $name
	 * @param int $default
	 * @return int
	 */
	function getInt( $name, $default = 0 ) {
		return intval( $this->getVal( $name, $default ) );
	}

	/**
	 * Fetch an integer value from the input or return null if empty.
	 * Guaranteed to return an integer or null; non-numeric input will
	 * typically return null.
	 * @param string $name
	 * @return int
	 */
	function getIntOrNull( $name ) {
		$val = $this->getVal( $name );
		return is_numeric( $val )
			? intval( $val )
			: null;
	}

	/**
	 * Fetch a boolean value from the input or return $default if not set.
	 * Guaranteed to return true or false, with normal PHP semantics for
	 * boolean interpretation of strings.
	 * @param string $name
	 * @param bool $default
	 * @return bool
	 */
	function getBool( $name, $default = false ) {
		return $this->getVal( $name, $default ) ? true : false;
	}

	/**
	 * Return true if the named value is set in the input, whatever that
	 * value is (even "0"). Return false if the named value is not set.
	 * Example use is checking for the presence of check boxes in forms.
	 * @param string $name
	 * @return bool
	 */
	function getCheck( $name ) {
		# Checkboxes and buttons are only present when clicked
		# Presence connotes truth, abscense false
		$val = $this->getVal( $name, NULL );
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
	 * @param string $name
	 * @param string $default optional
	 * @return string
	 */
	function getText( $name, $default = '' ) {
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
	function getValues() {
		$names = func_get_args();
		if ( count( $names ) == 0 ) {
			$names = array_keys( $_REQUEST );
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
	function wasPosted() {
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
	function checkSessionCookie() {
		return isset( $_COOKIE[session_name()] );
	}

	/**
	 * Return the path portion of the request URI.
	 * @return string
	 */
	function getRequestURL() {
		if( isset( $_SERVER['REQUEST_URI'] ) ) {
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
	function getFullRequestURL() {
		global $wgServer;
		return $wgServer . $this->getRequestURL();
	}

	/**
	 * Take an arbitrary query and rewrite the present URL to include it
	 * @param $query String: query string fragment; do not include initial '?'
	 * @return string
	 */
	function appendQuery( $query ) {
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
	function escapeAppendQuery( $query ) {
		return htmlspecialchars( $this->appendQuery( $query ) );
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
	function getLimitOffset( $deflimit = 50, $optionname = 'rclimit' ) {
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
	function getFileTempname( $key ) {
		if( !isset( $_FILES[$key] ) ) {
			return NULL;
		}
		return $_FILES[$key]['tmp_name'];
	}

	/**
	 * Return the size of the upload, or 0.
	 * @param $key String:
	 * @return integer
	 */
	function getFileSize( $key ) {
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
	function getUploadError( $key ) {
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
	function getFileName( $key ) {
		if( !isset( $_FILES[$key] ) ) {
			return NULL;
		}
		$name = $_FILES[$key]['name'];

		# Safari sends filenames in HTML-encoded Unicode form D...
		# Horrid and evil! Let's try to make some kind of sense of it.
		$name = Sanitizer::decodeCharReferences( $name );
		$name = UtfNormal::cleanUp( $name );
		wfDebug( "WebRequest::getFileName() '" . $_FILES[$key]['name'] . "' normalized to '$name'\n" );
		return $name;
	}
	
	/**
	 * Return a handle to WebResponse style object, for setting cookies, 
	 * headers and other stuff, for Request being worked on.
	 */
	function response() {
		/* Lazy initialization of response object for this request */
		if (!is_object($this->_response)) {
			$this->_response = new WebResponse;
		} 
		return $this->_response;
	}
	
}

/**
 * WebRequest clone which takes values from a provided array.
 *
 */
class FauxRequest extends WebRequest {
	var $data = null;
	var $wasPosted = false;

	function FauxRequest( $data, $wasPosted = false ) {
		if( is_array( $data ) ) {
			$this->data = $data;
		} else {
			throw new MWException( "FauxRequest() got bogus data" );
		}
		$this->wasPosted = $wasPosted;
	}

	function getVal( $name, $default = NULL ) {
		return $this->getGPCVal( $this->data, $name, $default );
	}

	function getText( $name, $default = '' ) {
		# Override; don't recode since we're using internal data
		return $this->getVal( $name, $default );
	}

	function getValues() {
		return $this->data;
	}

	function wasPosted() {
		return $this->wasPosted;
	}

	function checkSessionCookie() {
		return false;
	}

	function getRequestURL() {
		throw new MWException( 'FauxRequest::getRequestURL() not implemented' );
	}

	function appendQuery( $query ) {
		throw new MWException( 'FauxRequest::appendQuery() not implemented' );
	}

}

?>
