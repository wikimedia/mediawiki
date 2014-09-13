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
 * Read from a CDB file.
 * Native and pure PHP implementations are provided.
 * http://cr.yp.to/cdb.html
 */
abstract class CdbReader {
	/**
	 * The file handle
	 */
	protected $handle;

	/**
	 * Open a file and return a subclass instance
	 *
	 * @param $fileName string
	 *
	 * @return CdbReader
	 */
	public static function open( $fileName ) {
		return self::haveExtension() ?
			new CdbReaderDBA( $fileName ) :
			new CdbReaderPHP( $fileName );
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
	 * Create the object and open the file
	 *
	 * @param $fileName string
	 */
	abstract public function __construct( $fileName );

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
abstract class CdbWriter {
	/**
	 * The file handle
	 */
	protected $handle;

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
		return CdbReader::haveExtension() ?
			new CdbWriterDBA( $fileName ) :
			new CdbWriterPHP( $fileName );
	}

	/**
	 * Create the object and open the file
	 *
	 * @param $fileName string
	 */
	abstract public function __construct( $fileName );

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
 * Exception for Cdb errors.
 * This explicitly doesn't subclass MWException to encourage reuse.
 */
class CdbException extends Exception {}
