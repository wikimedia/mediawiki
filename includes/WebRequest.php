<?php

# Hypothetically, we could use a WebRequest object to fake a
# self-contained request.

## Enable this to debug total elimination of register_globals
#define( "DEBUG_GLOBALS", 1 );

# Deal with importing all those nasssty globals and things
class WebRequest {
	function WebRequest() {
		if( defined('DEBUG_GLOBALS') ) error_reporting(E_ALL);

		$this->checkMagicQuotes();
		$this->checkRegisterGlobals();
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
		} elseif( defined('DEBUG_GLOBALS') ) {
			die("DEBUG_GLOBALS: turn on magic_quotes_gpc" );
		}
	}

	function checkRegisterGlobals() {
		if( ini_get( "register_globals" ) ) {
			if( defined( "DEBUG_GLOBALS" ) ) {
				die( "DEBUG_GLOBALS: Turn register_globals off!" );
			}
		} else {
			if( !defined( "DEBUG_GLOBALS" ) ) {
				# Insecure, but at least it'll run
				import_request_variables( "GPC" );
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
	
	function getText( $name, $default = "" ) {
		return $this->getGPCText( $_REQUEST, $name, $default );
	}
	
	function wasPosted() {
		return $_SERVER['REQUEST_METHOD'] == 'POST';
	}
	
	function checkSessionCookie() {
		return isset( $_COOKIE[ini_get("session.name")] );
	}
}

?>