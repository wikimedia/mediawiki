<?php
/**
 * This is a port of D.J. Bernstein's CDB to PHP. It's based on the copy that
 * appears in PHP 5.3. Changes are:
 *    * Error returns replaced with exceptions
 *    * Exception thrown if sizes or offsets are between 2GB and 4GB
 *    * Some variables renamed
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
class CdbReaderPHP extends CdbReader {
	/** The filename */
	protected $fileName;

	/* number of hash slots searched under this key */
	protected $loop;

	/* initialized if loop is nonzero */
	protected $khash;

	/* initialized if loop is nonzero */
	protected $kpos;

	/* initialized if loop is nonzero */
	protected $hpos;

	/* initialized if loop is nonzero */
	protected $hslots;

	/* initialized if findNext() returns true */
	protected $dpos;

	/* initialized if cdb_findnext() returns 1 */
	protected $dlen;

	/**
	 * @param string $fileName
	 * @throws CdbException
	 */
	public function __construct( $fileName ) {
		$this->fileName = $fileName;
		$this->handle = fopen( $fileName, 'rb' );
		if ( !$this->handle ) {
			throw new CdbException( 'Unable to open CDB file "' . $this->fileName . '".' );
		}
		$this->findStart();
	}

	public function close() {
		if ( isset( $this->handle ) ) {
			fclose( $this->handle );
		}
		unset( $this->handle );
	}

	/**
	 * @param mixed $key
	 * @return bool|string
	 */
	public function get( $key ) {
		// strval is required
		if ( $this->find( strval( $key ) ) ) {
			return $this->read( $this->dlen, $this->dpos );
		} else {
			return false;
		}
	}

	/**
	 * @param string $key
	 * @param int $pos
	 * @return bool
	 */
	protected function match( $key, $pos ) {
		$buf = $this->read( strlen( $key ), $pos );

		return $buf === $key;
	}

	protected function findStart() {
		$this->loop = 0;
	}

	/**
	 * @throws CdbException
	 * @param int $length
	 * @param int $pos
	 * @return string
	 */
	protected function read( $length, $pos ) {
		if ( fseek( $this->handle, $pos ) == -1 ) {
			// This can easily happen if the internal pointers are incorrect
			throw new CdbException(
				'Seek failed, file "' . $this->fileName . '" may be corrupted.' );
		}

		if ( $length == 0 ) {
			return '';
		}

		$buf = fread( $this->handle, $length );
		if ( $buf === false || strlen( $buf ) !== $length ) {
			throw new CdbException(
				'Read from CDB file failed, file "' . $this->fileName . '" may be corrupted.' );
		}

		return $buf;
	}

	/**
	 * Unpack an unsigned integer and throw an exception if it needs more than 31 bits
	 * @param string $s
	 * @throws CdbException
	 * @return mixed
	 */
	protected function unpack31( $s ) {
		$data = unpack( 'V', $s );
		if ( $data[1] > 0x7fffffff ) {
			throw new CdbException(
				'Error in CDB file "' . $this->fileName . '", integer too big.' );
		}

		return $data[1];
	}

	/**
	 * Unpack a 32-bit signed integer
	 * @param string $s
	 * @return int
	 */
	protected function unpackSigned( $s ) {
		$data = unpack( 'va/vb', $s );

		return $data['a'] | ( $data['b'] << 16 );
	}

	/**
	 * @param string $key
	 * @return bool
	 */
	protected function findNext( $key ) {
		if ( !$this->loop ) {
			$u = CdbFunctions::hash( $key );
			$buf = $this->read( 8, ( $u << 3 ) & 2047 );
			$this->hslots = $this->unpack31( substr( $buf, 4 ) );
			if ( !$this->hslots ) {
				return false;
			}
			$this->hpos = $this->unpack31( substr( $buf, 0, 4 ) );
			$this->khash = $u;
			$u = CdbFunctions::unsignedShiftRight( $u, 8 );
			$u = CdbFunctions::unsignedMod( $u, $this->hslots );
			$u <<= 3;
			$this->kpos = $this->hpos + $u;
		}

		while ( $this->loop < $this->hslots ) {
			$buf = $this->read( 8, $this->kpos );
			$pos = $this->unpack31( substr( $buf, 4 ) );
			if ( !$pos ) {
				return false;
			}
			$this->loop += 1;
			$this->kpos += 8;
			if ( $this->kpos == $this->hpos + ( $this->hslots << 3 ) ) {
				$this->kpos = $this->hpos;
			}
			$u = $this->unpackSigned( substr( $buf, 0, 4 ) );
			if ( $u === $this->khash ) {
				$buf = $this->read( 8, $pos );
				$keyLen = $this->unpack31( substr( $buf, 0, 4 ) );
				if ( $keyLen == strlen( $key ) && $this->match( $key, $pos + 8 ) ) {
					// Found
					$this->dlen = $this->unpack31( substr( $buf, 4 ) );
					$this->dpos = $pos + 8 + $keyLen;

					return true;
				}
			}
		}

		return false;
	}

	/**
	 * @param mixed $key
	 * @return bool
	 */
	protected function find( $key ) {
		$this->findStart();

		return $this->findNext( $key );
	}
}

