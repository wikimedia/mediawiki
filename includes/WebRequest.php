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
 * Hypothetically, we could use a WebRequest object to fake a
 * self-contained request (FauxRequest).
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
	
	function normalizeUnicode( &$arr ) {
		foreach( $arr as $key => $val ) {
			if( is_array( $val ) ) {
				$this->normalizeUnicode( $arr[$key ] );
			} else {
				$arr[$key] = UtfNormal::cleanUp( $val );
			}
		}
	}
	
	function getGPCVal( &$arr, $name, $default ) {
		if( isset( $arr[$name] ) ) {
			return $arr[$name];
		} else {
			return $default;
		}
	}
	
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
	
	function getVal( $name, $default = NULL ) {
		return $this->getGPCVal( $_REQUEST, $name, $default );
	}
	
	function getInt( $name, $default = 0 ) {
		return IntVal( $this->getVal( $name, $default ) );
	}
	
	function getBool( $name, $default = false ) {
		return $this->getVal( $name, $default ) ? true : false;
	}
	
	function getCheck( $name ) {
		# Checkboxes and buttons are only present when clicked
		# Presence connotes truth, abscense false
		$val = $this->getVal( $name, NULL );
		return isset( $val );
	}
	
	function getText( $name, $default = '' ) {
		return $this->getGPCText( $_REQUEST, $name, $default );
	}
	
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

	function wasPosted() {
		return $_SERVER['REQUEST_METHOD'] == 'POST';
	}
	
	function checkSessionCookie() {
		return isset( $_COOKIE[ini_get('session.name')] );
	}
	
	function getRequestURL() {
		return $_SERVER['REQUEST_URI'];
	}
	
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
	 * Get information on uploaded files
	 */
	function getFileTempname( $key ) {
		if( !isset( $_FILES[$key] ) ) {
			return NULL;
		}
		return $_FILES[$key]['tmp_name'];
	}
	
	function getFileSize( $key ) {
		if( !isset( $_FILES[$key] ) ) {
			return 0;
		}
		return $_FILES[$key]['size'];
	}
	
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
