<?php
/**
 * Native CDB file reader and writer.
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
 * Base class for all CDB operations. Doesn't do much other
 * than abstracting some error handling/debug stuff
 */
abstract class CdbHandle {
	/**
	 * The file handle
	 */
	protected $handle;

	/**
	 * Exception to throw on failures
	 * @var Exception
	 */
	protected static $exceptionClass = 'MWException';

	/**
	 * Something to call when we've got debug output
	 * @var string
	 */
	protected static $debugCallback = 'wfDebug';

	/**
	 * Create the object and open the file
	 *
	 * @param $fileName string
	 */
	abstract public function __construct( $fileName );

	/**
	 * Throw an exception!
	 *
	 * @param string $msg Error message
	 * @throws Exception
	 */
	protected static function throwException( $msg ) {
		$e = self::$exceptionClass;
		throw new $e( $msg );
	}

	/**
	 * Log a debug message
	 *
	 * @param string $msg Debug message
	 */
	protected static function debug( $msg ) {
		call_user_func_array( self::$debugCallback, array( $msg ) );
	}
}

/**
 * Read from a CDB file.
 * Native and pure PHP implementations are provided.
 * http://cr.yp.to/cdb.html
 */
abstract class CdbReader extends CdbHandle {
	/**
	 * Open a file and return a subclass instance
	 *
	 * @param $fileName string
	 *
	 * @return CdbReader
	 */
	public static function open( $fileName ) {
		if ( self::haveExtension() ) {
			return new CdbReaderDBA( $fileName );
		} else {
			self::debug( "Warning: no dba extension found, using emulation.\n" );
			return new CdbReaderPHP( $fileName );
		}
	}

	/**
	 * Returns true if the native extension is available
	 *
	 * @return bool
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
	 * Close the file. Optional, you can just let the variable go out of scope.
	 */
	abstract public function close();

	/**
	 * Get a value with a given key. Only string values are supported.
	 *
	 * @param $key string
	 */
	abstract public function get( $key );
}

/**
 * Write to a CDB file.
 * Native and pure PHP implementations are provided.
 */
abstract class CdbWriter extends CdbHandle {
	/**
	 * File we'll be writing to when we're done
	 * @var string
	 */
	protected $realFileName;

	/**
	 * File we write to temporarily until we're done
	 * @var string
	 */
	protected $tmpFileName;

	/**
	 * Open a writer and return a subclass instance.
	 * The user must have write access to the directory, for temporary file creation.
	 *
	 * @param $fileName string
	 *
	 * @return CdbWriterDBA|CdbWriterPHP
	 */
	public static function open( $fileName ) {
		if ( CdbReader::haveExtension() ) {
			return new CdbWriterDBA( $fileName );
		} else {
			self::debug( "Warning: no dba extension found, using emulation.\n" );
			return new CdbWriterPHP( $fileName );
		}
	}

	/**
	 * Set a key to a given value. The value will be converted to string.
	 * @param $key string
	 * @param $value string
	 */
	abstract public function set( $key, $value );

	/**
	 * Close the writer object. You should call this function before the object
	 * goes out of scope, to write out the final hashtables.
	 */
	abstract public function close();

	/**
	 * If the object goes out of scope, close it for sanity
	 */
	public function __destruct() {
		if ( isset( $this->handle ) ) {
			$this->close();
		}
	}

	/**
	 * Are we running on Windows?
	 */
	protected function isWindows() {
		return substr( php_uname(), 0, 7 ) == 'Windows';
	}
}

/**
 * Reader class which uses the DBA extension
 */
class CdbReaderDBA extends CdbReader {
	public function __construct( $fileName ) {
		$this->handle = dba_open( $fileName, 'r-', 'cdb' );
		if ( !$this->handle ) {
			self::throwException( 'Unable to open CDB file "' . $fileName . '"' );
		}
	}

	public function close() {
		if ( isset( $this->handle ) ) {
			dba_close( $this->handle );
		}
		unset( $this->handle );
	}

	public function get( $key ) {
		return dba_fetch( $key, $this->handle );
	}
}

/**
 * Writer class which uses the DBA extension
 */
class CdbWriterDBA extends CdbWriter {
	public function __construct( $fileName ) {
		$this->realFileName = $fileName;
		$this->tmpFileName = $fileName . '.tmp.' . mt_rand( 0, 0x7fffffff );
		$this->handle = dba_open( $this->tmpFileName, 'n', 'cdb_make' );
		if ( !$this->handle ) {
			self::throwException( 'Unable to open CDB file for write "' . $fileName . '"' );
		}
	}

	public function set( $key, $value ) {
		return dba_insert( $key, $value, $this->handle );
	}

	public function close() {
		if ( isset( $this->handle ) ) {
			dba_close( $this->handle );
		}
		if ( $this->isWindows() ) {
			unlink( $this->realFileName );
		}
		if ( !rename( $this->tmpFileName, $this->realFileName ) ) {
			self::throwException( 'Unable to move the new CDB file into place.' );
		}
		unset( $this->handle );
	}
}
