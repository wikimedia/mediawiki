<?php

/**
 * Class to implement stub globals, which are globals that delay loading the
 * their associated module code by deferring initialisation until the first 
 * method call. 
 *
 * Note on unstub loops: 
 *
 * Unstub loops (infinite recursion) sometimes occur when a constructor calls 
 * another function, and the other function calls some method of the stub. The 
 * best way to avoid this is to make constructors as lightweight as possible,
 * deferring any initialisation which depends on other modules. As a last 
 * resort, you can use StubObject::isRealObject() to break the loop, but as a 
 * general rule, the stub object mechanism should be transparent, and code 
 * which refers to it should be kept to a minimum.
 */
class StubObject {
	var $mGlobal, $mClass, $mParams;
	function __construct( $global = null, $class = null, $params = array() ) {
		$this->mGlobal = $global;
		$this->mClass = $class;
		$this->mParams = $params;
	}

	static function isRealObject( $obj ) {
		return is_object( $obj ) && !($obj instanceof StubObject);
	}

	function _call( $name, $args ) {
		$this->_unstub( $name, 5 );
		return call_user_func_array( array( $GLOBALS[$this->mGlobal], $name ), $args );
	}

	function _newObject() {
		return wfCreateObject( $this->mClass, $this->mParams );
	}

	function __call( $name, $args ) {
		return $this->_call( $name, $args );
	}

	/**
	 * This is public, for the convenience of external callers wishing to access 
	 * properties, e.g. eval.php
	 */
	function _unstub( $name = '_unstub', $level = 2 ) {
		static $recursionLevel = 0;
		if ( get_class( $GLOBALS[$this->mGlobal] ) != $this->mClass ) {
			$fname = __METHOD__.'-'.$this->mGlobal;
			wfProfileIn( $fname );
			$caller = wfGetCaller( $level );
			if ( ++$recursionLevel > 2 ) {
				throw new MWException( "Unstub loop detected on call of \${$this->mGlobal}->$name from $caller\n" );
			}
			wfDebug( "Unstubbing \${$this->mGlobal} on call of \${$this->mGlobal}->$name from $caller\n" );
			$GLOBALS[$this->mGlobal] = $this->_newObject();
			--$recursionLevel;
			wfProfileOut( $fname );
		}
	}
}

class StubContLang extends StubObject {
	function __construct() {
		parent::__construct( 'wgContLang' );
	}

	function __call( $name, $args ) {
		return StubObject::_call( $name, $args );
	}

	function _newObject() {
		global $wgContLanguageCode;
		$obj = Language::factory( $wgContLanguageCode );
		$obj->initEncoding();
		$obj->initContLang();
		return $obj;
	}
}
class StubUserLang extends StubObject {
	function __construct() {
		parent::__construct( 'wgLang' );
	}

	function __call( $name, $args ) {
		return $this->_call( $name, $args );
	}

	function _newObject() {
		global $wgContLanguageCode, $wgRequest, $wgUser, $wgContLang;
		$code = $wgRequest->getVal('uselang', $wgUser->getOption('language') );

		// if variant is explicitely selected, use it instead the one from wgUser
		// see bug #7605
		if($wgContLang->hasVariants()){
			$variant = $wgContLang->getPreferredVariant();
			if($variant != $wgContLanguageCode)
				$code = $variant;
		}	 

		# Validate $code
		if( empty( $code ) || !preg_match( '/^[a-z-]+$/', $code ) ) {
			wfDebug( "Invalid user language code\n" );
			$code = $wgContLanguageCode;
		}

		if( $code == $wgContLanguageCode ) {
			return $wgContLang;
		} else {
			$obj = Language::factory( $code );
			return $obj;
		}
	}
}
class StubUser extends StubObject {
	function __construct() {
		parent::__construct( 'wgUser' );
	}

	function __call( $name, $args ) {
		return $this->_call( $name, $args );
	}
	
	function _newObject() {
		global $wgCommandLineMode;
		if( $wgCommandLineMode ) {
			$user = new User;
		} else {
			$user = User::newFromSession();
			wfRunHooks('AutoAuthenticate',array(&$user));
		}
		return $user;
	}
}


