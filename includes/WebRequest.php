<?php
/**
 * Deal with importing all those nasssty globals and things
 * @package MediaWiki
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
# 59 Temple Place - Suite 330, Boston, MA 02111-1307, USA.
# http://www.gnu.org/copyleft/gpl.html

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
 * @package MediaWiki
 */
class WebRequest {
	function WebRequest() {
		$this->checkMagicQuotes();
		global $wgUsePathInfo;
		if( isset( $_SERVER['PATH_INFO'] ) && $wgUsePathInfo ) {
			# Stuff it!
			$_REQUEST['title'] = substr( $_SERVER['PATH_INFO'], 1 );
		}
		global $wgUseLatin1;
		if( !$wgUseLatin1 ) {
			require_once( 'normal/UtfNormal.php' );
			wfProfileIn( 'WebRequest:normalizeUnicode-fix' );
			$this->normalizeUnicode( $_REQUEST );
			wfProfileOut( 'WebRequest:normalizeUnicode-fix' );
		}
	}

	/**
	 * Recursively strips slashes from the given array;
	 * used for undoing the evil that is magic_quotes_gpc.
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
	 * through fix_magic_quotes to strip out the stupid dlashes.
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
	 * @private
	 */
	function normalizeUnicode( &$arr ) {
		foreach( $arr as $key => $val ) {
			if( is_array( $val ) ) {
				$this->normalizeUnicode( $arr[$key ] );
			} else {
				$arr[$key] = UtfNormal::cleanUp( $val );
			}
		}
	}
	
	/**
	 * Fetch a value from the given array or return $default if it's not set.
	 * @private
	 */
	function getGPCVal( &$arr, $name, $default ) {
		if( isset( $arr[$name] ) ) {
			return $arr[$name];
		} else {
			return $default;
		}
	}
	
	/**
	 * Fetch a value from the given array or return $default if it's not set.
	 * \r is stripped from the text, and with some language modules there is
	 * an input transliteration applied.
	 * @private
	 */
	function getGPCText( &$arr, $name, $default ) {
		# Text fields may be in an alternate encoding which we should check.
		# Also, strip CRLF line endings down to LF to achieve consistency.
		global $wgLang;
		if( isset( $arr[$name] ) ) {
			return str_replace( "\r\n", "\n", $wgLang->recodeInput( $arr[$name] ) );
		} else {
			return $default;
		}
	}
	
	/**
	 * Fetch a value from the input or return $default if it's not set.
	 * Value may be of any type -- even an array -- and is not altered.
	 */
	function getVal( $name, $default = NULL ) {
		return $this->getGPCVal( $_REQUEST, $name, $default );
	}
	
	/**
	 * Fetch an integer value from the input or return $default if not set.
	 * Guaranteed to return an integer; non-integer input will typically
	 * return 0.
	 */
	function getInt( $name, $default = 0 ) {
		return IntVal( $this->getVal( $name, $default ) );
	}
	
	/**
	 * Fetch a boolean value from the input or return $default if not set.
	 * Guaranteed to return true or false, with normal PHP semantics for
	 * boolean interpretation of strings.
	 */
	function getBool( $name, $default = false ) {
		return $this->getVal( $name, $default ) ? true : false;
	}
	
	/**
	 * Return true if the named value is set in the input, whatever that
	 * value is (even "0"). Return false if the named value is not set.
	 * Example use is checking for the presence of check boxes in forms.
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
	 * form <textarea> and <input> fields.
	 */
	function getText( $name, $default = '' ) {
		return $this->getGPCText( $_REQUEST, $name, $default );
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
	 */
	function wasPosted() {
		return $_SERVER['REQUEST_METHOD'] == 'POST';
	}
	
	/**
	 * Returns true if there is a session cookie set.
	 * This does not necessarily mean that the user is logged in!
	 */
	function checkSessionCookie() {
		return isset( $_COOKIE[ini_get('session.name')] );
	}
	
	/**
	 * Return the path portion of the request URI.
	 */
	function getRequestURL() {
		return $_SERVER['REQUEST_URI'];
	}
	
	/**
	 * Return the request URI with the canonical service and hostname.
	 */
	function getFullRequestURL() {
		global $wgServer;
		return $wgServer . $this->getRequestURL();
	}
	
	/**
	 * Take an arbitrary query and rewrite the present URL to include it
	 */
	function appendQuery( $query ) {
		global $wgTitle;
		$basequery = '';
		foreach( $_GET as $var => $val ) {
			if( $var == 'title' ) continue;
			$basequery .= '&' . urlencode( $var ) . '=' . urlencode( $val );
		}
		$basequery .= '&' . $query;
		
		# Trim the extra &
		$basequery = substr( $basequery, 1 );
		return $wgTitle->getLocalURL( $basequery );
	}
	
	/**
	 * HTML-safe version of appendQuery().
	 */
	function escapeAppendQuery( $query ) {
		return htmlspecialchars( $this->appendQuery( $query ) );
	}
	
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
	 * Returns NULL if no such thing.
	 */
	function getFileTempname( $key ) {
		if( !isset( $_FILES[$key] ) ) {
			return NULL;
		}
		return $_FILES[$key]['tmp_name'];
	}
	
	/**
	 * Return the size of the upload, or 0.
	 */
	function getFileSize( $key ) {
		if( !isset( $_FILES[$key] ) ) {
			return 0;
		}
		return $_FILES[$key]['size'];
	}
	
	/**
	 * Return the original filename of the uploaded file, as reported by
	 * the submitting user agent. HTML-style character entities are
	 * interpreted and normalized to Unicode normalization form C, in part
	 * to deal with weird input from Safari with non-ASCII filenames.
	 *
	 * Other than this the name is not verified for being a safe filename.
	 */
	function getFileName( $key ) {
		if( !isset( $_FILES[$key] ) ) {
			return NULL;
		}
		$name = $_FILES[$key]['name'];
		
		# Safari sends filenames in HTML-encoded Unicode form D...
		# Horrid and evil! Let's try to make some kind of sense of it.
		global $wgUseLatin1;
		if( $wgUseLatin1 ) {
			$name = utf8_encode( $name );
		}
		$name = wfMungeToUtf8( $name );
		$name = UtfNormal::cleanUp( $name );
		if( $wgUseLatin1 ) {
			$name = utf8_decode( $name );
		}
		wfDebug( "WebRequest::getFileName() '" . $_FILES[$key]['name'] . "' normalized to '$name'\n" );
		return $name;
	}
}

/**
 * WebRequest clone which takes values from a provided array.
 *
 * @package MediaWiki
 */
class FauxRequest extends WebRequest {
	var $data = null;
	var $wasPosted = false;
	
	function WebRequest( $data, $wasPosted = false ) {
		if( is_array( $data ) ) {
			$this->data = $data;
		} else {
			wfDebugDieBacktrace( "FauxReqeust() got bogus data" );
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
		wfDebugDieBacktrace( 'FauxRequest::getRequestURL() not implemented' );
	}
	
	function appendQuery( $query ) {
		wfDebugDieBacktrace( 'FauxRequest::appendQuery() not implemented' );
	}
	
}

?>
