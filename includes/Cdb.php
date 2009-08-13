<?php

/**
 * Read from a CDB file.
 * Native and pure PHP implementations are provided.
 * http://cr.yp.to/cdb.html
 */
abstract class CdbReader {
	/**
	 * Open a file and return a subclass instance
	 */
	public static function open( $fileName ) {
		if ( self::haveExtension() ) {
			return new CdbReader_DBA( $fileName );
		} else {
			wfDebug( "Warning: no dba extension found, using emulation.\n" );
			return new CdbReader_PHP( $fileName );
		}
	}

	/**
	 * Returns true if the native extension is available
	 */
	public static function haveExtension() {
		if ( !function_exists( 'dba_handlers' ) ) {
			return false;
		}
		$handlers = dba_handlers();
		if ( !in_array( 'cdb', $handlers ) || !in_array( 'cdb_make', $handlers ) ) {
			return false;
		}
		return true;
	}

	/**
	 * Construct the object and open the file
	 */
	abstract function __construct( $fileName );

	/**
	 * Close the file. Optional, you can just let the variable go out of scope.
	 */
	abstract function close();

	/**
	 * Get a value with a given key. Only string values are supported.
	 */
	abstract public function get( $key );
}

/**
 * Write to a CDB file.
 * Native and pure PHP implementations are provided.
 */
abstract class CdbWriter {
	/**
	 * Open a writer and return a subclass instance.
	 * The user must have write access to the directory, for temporary file creation.
	 */
	public static function open( $fileName ) {
		if ( CdbReader::haveExtension() ) {
			return new CdbWriter_DBA( $fileName );
		} else {
			wfDebug( "Warning: no dba extension found, using emulation.\n" );
			return new CdbWriter_PHP( $fileName );
		}
	}

	/**
	 * Create the object and open the file
	 */
	abstract function __construct( $fileName );

	/**
	 * Set a key to a given value. The value will be converted to string.
	 */
	abstract public function set( $key, $value );

	/**
	 * Close the writer object. You should call this function before the object
	 * goes out of scope, to write out the final hashtables.
	 */
	abstract public function close();
}


/**
 * Reader class which uses the DBA extension
 */
class CdbReader_DBA {
	var $handle;

	function __construct( $fileName ) {
		$this->handle = dba_open( $fileName, 'r-', 'cdb' );
		if ( !$this->handle ) {
			throw new MWException( 'Unable to open DB file "' . $fileName . '"' );
		}
	}

	function close() {
		if( isset($this->handle) )
			dba_close( $this->handle );
		unset( $this->handle );
	}

	function get( $key ) {
		return dba_fetch( $key, $this->handle );
	}
}


/**
 * Writer class which uses the DBA extension
 */
class CdbWriter_DBA {
	var $handle, $realFileName, $tmpFileName;

	function __construct( $fileName ) {
		$this->realFileName = $fileName;
		$this->tmpFileName = $fileName . '.tmp.' . mt_rand( 0, 0x7fffffff );
		$this->handle = dba_open( $this->tmpFileName, 'n', 'cdb_make' );
		if ( !$this->handle ) {
			throw new MWException( 'Unable to open DB file for write "' . $fileName . '"' );
		}
	}

	function set( $key, $value ) {
		return dba_insert( $key, $value, $this->handle );
	}

	function close() {
		if( isset($this->handle) )
			dba_close( $this->handle );
		if ( wfIsWindows() ) {
			unlink( $this->realFileName );
		}
		if ( !rename( $this->tmpFileName, $this->realFileName ) ) {
			throw new MWException( 'Unable to move the new CDB file into place.' );
		}
		unset( $this->handle );
	}

	function __destruct() {
		if ( isset( $this->handle ) ) {
			$this->close();
		}
	}
}

