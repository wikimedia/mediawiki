<?php

/**
 * Some functions that are useful during startup.
 */
class MWInit {
	static $compilerVersion;

	/**
	 * Get the version of HipHop used to compile, or false if MediaWiki was not
	 * compiled. This works by having our build script insert a special function
	 * into the compiled code.
	 */
	static function getCompilerVersion() {
		if ( self::$compilerVersion === null ) {
			if ( self::functionExists( 'wfHipHopCompilerVersion' ) ) {
				self::$compilerVersion = wfHipHopCompilerVersion();
			} else {
				self::$compilerVersion = false;
			}
		}
		return self::$compilerVersion;
	}

	/**
	 * Returns true if we are running under HipHop, whether in compiled or 
	 * interpreted mode.
	 */
	static function isHipHop() {
		return function_exists( 'hphp_thread_set_warmup_enabled' );
	}

	/**
	 * Get a fully-qualified path for a source file relative to $IP. Including
	 * such a path under HipHop will force the file to be interpreted. This is
	 * useful for configuration files. 
	 */
	static function interpretedPath( $file ) {
		global $IP;
		return "$IP/$file";
	}

	/**
	 * If we are running code compiled by HipHop, this will pass through the 
	 * input path, assumed to be relative to $IP. If the code is interpreted, 
	 * it will converted to a fully qualified path. It is necessary to use a 
	 * path which is relative to $IP in order to make HipHop use its compiled 
	 * code.
	 */
	static function compiledPath( $file ) {
		global $IP;

		if ( defined( 'MW_COMPILED' ) ) {
			return $file;
		} else {
			return "$IP/$file";
		}
	}

	/**
	 * Determine whether a class exists, using a method which works under HipHop.
	 *
	 * Note that it's not possible to implement this with any variant of 
	 * class_exists(), because class_exists() returns false for classes which 
	 * are compiled in. 
	 *
	 * Calling class_exists() on a literal string causes the class to be made 
	 * "volatile", which means (as of March 2011) that the class is broken and 
	 * can't be used at all. So don't do that. See 
	 * https://github.com/facebook/hiphop-php/issues/314
	 */
	static function classExists( $class ) {
		try {
			$r = new ReflectionClass( $class );
		} catch( ReflectionException $r ) {
			$r = false;
		}
		return $r !== false;
	}

	/**
	 * Determine whether a function exists, using a method which works under 
	 * HipHop.
	 */
	static function functionExists( $function ) {
		try {
			$r = new ReflectionFunction( $function );
		} catch( ReflectionException $r ) {
			$r = false;
		}
		return $r !== false;
	}
}
