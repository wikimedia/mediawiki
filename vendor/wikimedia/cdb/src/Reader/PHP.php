<?php

namespace Cdb\Reader;

use Cdb\Exception;
use Cdb\Reader;
use Cdb\Util;

/**
 * This is a port of D.J. Bernstein's CDB to PHP. It's based on the copy that
 * appears in PHP 5.3.
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
 * CDB reader class
 */
class PHP extends Reader {

	/** @var string The file name of the CDB file. **/
	protected $fileName;

	/** @var string First 2048b of CDB file, containing the hash table. **/
	protected $hashTable;

	/** @var int Offset in file where value of found key starts. **/
	protected $dataPos;

	/** @var int Byte length of found key's value. **/
	protected $dataLen;

	/** @var int File position indicator when iterating over keys. **/
	protected $keyIterPos = 2048;

	/** @var string Read buffer for CDB file. **/
	protected $buf;

	/** @var int File offset where read buffer starts. **/
	protected $bufStart;

	/** @var int File handle position indicator **/
	protected $filePos = 2048;

	/**
	 * Constructor.
	 *
	 * @param string $fileName
	 * @throws Exception If CDB file cannot be opened or if it contains fewer
	 *   than 2048 bytes of data.
	 */
	public function __construct( $fileName ) {
		$this->fileName = $fileName;
		$this->handle = fopen( $fileName, 'rb' );
		if ( !$this->handle ) {
			throw new Exception( 'Unable to open CDB file "' . $this->fileName . '".' );
		}
		$this->hashTable = fread( $this->handle, 2048 );
		if ( strlen( $this->hashTable ) !== 2048 ) {
			throw new Exception( 'CDB file contains fewer than 2048 bytes of data.' );
		}
	}

	/**
	 * Close the handle on the CDB file.
	 */
	public function close() {
		if ( isset( $this->handle ) ) {
			fclose( $this->handle );
		}
		unset( $this->handle );
	}

	/**
	 * Get the value of a key.
	 *
	 * @param mixed $key
	 * @return bool|string The key's value or false if not found.
	 */
	public function get( $key ) {
		// strval is required
		if ( $this->find( strval( $key ) ) ) {
			return $this->read( $this->dataPos, $this->dataLen );
		}

		return false;
	}

	/**
	 * Read data from the CDB file.
	 *
	 * @throws Exception When attempting to read past the end of the file.
	 * @param int $start Start reading from this position.
	 * @param int $len Number of bytes to read.
	 * @return string Read data.
	 */
	protected function read( $start, $len ) {
		$end = $start + $len;

		// The first 2048 bytes are the lookup table, which is read into
		// memory on initialization.
		if ( $end <= 2048 ) {
			return substr( $this->hashTable, $start, $len );
		}

		// Read data from the internal buffer first.
		$bytes = '';
		if ( $this->buf && $start >= $this->bufStart ) {
			$bytes .= substr( $this->buf, $start - $this->bufStart, $len );
			$bytesRead = strlen( $bytes );
			$len -= $bytesRead;
			$start += $bytesRead;
		} else {
			$bytesRead = 0;
		}

		if ( !$len ) {
			return $bytes;
		}

		// Many reads are sequential, so the file position indicator may
		// already be in the right place, in which case we can avoid the
		// call to fseek().
		if ( $start !== $this->filePos ) {
			if ( fseek( $this->handle, $start ) === -1 ) {
				// This can easily happen if the internal pointers are incorrect
				throw new Exception(
					'Seek failed, file "' . $this->fileName . '" may be corrupted.' );
			}
		}

		$buf = fread( $this->handle, max( $len, 1024 ) );
		if ( $buf === false ) {
			$buf = '';
		}

		$bytes .= substr( $buf, 0, $len );
		if ( strlen( $bytes ) !== $len + $bytesRead ) {
			throw new Exception(
				'Read from CDB file failed, file "' . $this->fileName . '" may be corrupted.' );
		}

		$this->filePos = $end;
		$this->bufStart = $start;
		$this->buf = $buf;

		return $bytes;
	}

	/**
	 * Unpack an unsigned integer and throw an exception if it needs more than 31 bits.
	 *
	 * @param int $pos Position to read from.
	 * @throws Exception When the integer cannot be represented in 31 bits.
	 * @return int
	 */
	protected function readInt31( $pos = 0 ) {
		$uint31 = $this->readInt32( $pos );
		if ( $uint31 > 0x7fffffff ) {
			throw new Exception(
				'Error in CDB file "' . $this->fileName . '", integer too big.' );
		}

		return $uint31;
	}

	/**
	 * Unpack a 32-bit integer.
	 *
	 * @param int $pos
	 * @return int
	 */
	protected function readInt32( $pos = 0 ) {
		static $lookups;

		if ( !$lookups ) {
			$lookups = array();
			for ( $i = 1; $i < 256; $i++ ) {
				$lookups[ chr( $i ) ] = $i;
			}
		}

		$buf = $this->read( $pos, 4 );

		$rv = 0;

		if ( $buf[0] !== "\x0" ) {
			$rv = $lookups[ $buf[0] ];
		}
		if ( $buf[1] !== "\x0" ) {
			$rv |= ( $lookups[ $buf[1] ] << 8 );
		}
		if ( $buf[2] !== "\x0" ) {
			$rv |= ( $lookups[ $buf[2] ] << 16 );
		}
		if ( $buf[3] !== "\x0" ) {
			$rv |= ( $lookups[ $buf[3] ] << 24 );
		}

		return $rv;
	}

	/**
	 * Search the CDB file for a key.
	 *
	 * Sets `dataLen` and `dataPos` properties if successful.
	 *
	 * @param string $key
	 * @return bool Whether the key was found.
	 */
	protected function find( $key ) {
		$keyLen = strlen( $key );

		$u = Util::hash( $key );
		$upos = ( $u << 3 ) & 2047;
		$hashSlots = $this->readInt31( $upos + 4 );
		if ( !$hashSlots ) {
			return false;
		}
		$hashPos = $this->readInt31( $upos );
		$keyHash = $u;
		$u = Util::unsignedShiftRight( $u, 8 );
		$u = Util::unsignedMod( $u, $hashSlots );
		$u <<= 3;
		$keyPos = $hashPos + $u;

		for ( $i = 0; $i < $hashSlots; $i++ ) {
			$hash = $this->readInt32( $keyPos );
			$pos = $this->readInt31( $keyPos + 4 );
			if ( !$pos ) {
				return false;
			}
			$keyPos += 8;
			if ( $keyPos == $hashPos + ( $hashSlots << 3 ) ) {
				$keyPos = $hashPos;
			}
			if ( $hash === $keyHash ) {
				if ( $keyLen === $this->readInt31( $pos ) ) {
					$dataLen = $this->readInt31( $pos + 4 );
					$dataPos = $pos + 8 + $keyLen;
					$foundKey = $this->read( $pos + 8, $keyLen );
					if ( $foundKey === $key ) {
						// Found
						$this->dataLen = $dataLen;
						$this->dataPos = $dataPos;

						return true;
					}
				}
			}
		}

		return false;
	}

	/**
	 * Check if a key exists in the CDB file.
	 *
	 * @param string $key
	 * @return bool Whether the key exists.
	 */
	public function exists( $key ) {
		return $this->find( strval( $key ) );
	}

	/**
	 * Get the first key from the CDB file and reset the key iterator.
	 *
	 * @return string Key.
	 */
	public function firstkey() {
		$this->keyIterPos = 2048;
		return $this->nextkey();
	}

	/**
	 * Get the next key from the CDB file.
	 *
	 * @return string Key.
	 */
	public function nextkey() {
		$keyLen = $this->readInt31( $this->keyIterPos );
		$dataLen = $this->readInt31( $this->keyIterPos + 4 );
		$key = $this->read( $this->keyIterPos + 8, $keyLen );
		$this->keyIterPos += 8 + $keyLen + $dataLen;

		return $key;
	}
}
