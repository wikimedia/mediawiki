<?php

namespace Cdb\Writer;

use Cdb\Exception;
use Cdb\Util;
use Cdb\Writer;

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
 * CDB writer class
 */
class PHP extends Writer {
	protected $hplist;

	protected $numentries;

	protected $pos;

	/**
	 * @param string $fileName
	 */
	public function __construct( $fileName ) {
		$this->realFileName = $fileName;
		$this->tmpFileName = $fileName . '.tmp.' . mt_rand( 0, 0x7fffffff );
		$this->handle = fopen( $this->tmpFileName, 'wb' );
		if ( !$this->handle ) {
			$this->throwException(
				'Unable to open CDB file "' . $this->tmpFileName . '" for write.' );
		}
		$this->hplist = array();
		$this->numentries = 0;
		$this->pos = 2048; // leaving space for the pointer array, 256 * 8
		if ( fseek( $this->handle, $this->pos ) == -1 ) {
			$this->throwException( 'fseek failed in file "' . $this->tmpFileName . '".' );
		}
	}

	/**
	 * @param string $key
	 * @param string $value
	 */
	public function set( $key, $value ) {
		if ( strval( $key ) === '' ) {
			// DBA cross-check hack
			return;
		}
		$this->addbegin( strlen( $key ), strlen( $value ) );
		$this->write( $key );
		$this->write( $value );
		$this->addend( strlen( $key ), strlen( $value ), Util::hash( $key ) );
	}

	/**
	 * @throws Exception
	 */
	public function close() {
		$this->finish();
		if ( isset( $this->handle ) ) {
			fclose( $this->handle );
		}
		if ( $this->isWindows() && file_exists( $this->realFileName ) ) {
			unlink( $this->realFileName );
		}
		if ( !rename( $this->tmpFileName, $this->realFileName ) ) {
			$this->throwException( 'Unable to move the new CDB file into place.' );
		}
		unset( $this->handle );
	}

	/**
	 * @throws Exception
	 * @param string $buf
	 */
	protected function write( $buf ) {
		$len = fwrite( $this->handle, $buf );
		if ( $len !== strlen( $buf ) ) {
			$this->throwException( 'Error writing to CDB file "' . $this->tmpFileName . '".' );
		}
	}

	/**
	 * @throws Exception
	 * @param int $len
	 */
	protected function posplus( $len ) {
		$newpos = $this->pos + $len;
		if ( $newpos > 0x7fffffff ) {
			$this->throwException(
				'A value in the CDB file "' . $this->tmpFileName . '" is too large.' );
		}
		$this->pos = $newpos;
	}

	/**
	 * @param int $keylen
	 * @param int $datalen
	 * @param int $h
	 */
	protected function addend( $keylen, $datalen, $h ) {
		$this->hplist[] = array(
			'h' => $h,
			'p' => $this->pos
		);

		$this->numentries++;
		$this->posplus( 8 );
		$this->posplus( $keylen );
		$this->posplus( $datalen );
	}

	/**
	 * @throws Exception
	 * @param int $keylen
	 * @param int $datalen
	 */
	protected function addbegin( $keylen, $datalen ) {
		if ( $keylen > 0x7fffffff ) {
			$this->throwException( 'Key length too long in file "' . $this->tmpFileName . '".' );
		}
		if ( $datalen > 0x7fffffff ) {
			$this->throwException( 'Data length too long in file "' . $this->tmpFileName . '".' );
		}
		$buf = pack( 'VV', $keylen, $datalen );
		$this->write( $buf );
	}

	/**
	 * @throws Exception
	 */
	protected function finish() {
		// Hack for DBA cross-check
		$this->hplist = array_reverse( $this->hplist );

		// Calculate the number of items that will be in each hashtable
		$counts = array_fill( 0, 256, 0 );
		foreach ( $this->hplist as $item ) {
			++$counts[255 & $item['h']];
		}

		// Fill in $starts with the *end* indexes
		$starts = array();
		$pos = 0;
		for ( $i = 0; $i < 256; ++$i ) {
			$pos += $counts[$i];
			$starts[$i] = $pos;
		}

		// Excessively clever and indulgent code to simultaneously fill $packedTables
		// with the packed hashtables, and adjust the elements of $starts
		// to actually point to the starts instead of the ends.
		$packedTables = array_fill( 0, $this->numentries, false );
		foreach ( $this->hplist as $item ) {
			$packedTables[--$starts[255 & $item['h']]] = $item;
		}

		$final = '';
		for ( $i = 0; $i < 256; ++$i ) {
			$count = $counts[$i];

			// The size of the hashtable will be double the item count.
			// The rest of the slots will be empty.
			$len = $count + $count;
			$final .= pack( 'VV', $this->pos, $len );

			$hashtable = array();
			for ( $u = 0; $u < $len; ++$u ) {
				$hashtable[$u] = array( 'h' => 0, 'p' => 0 );
			}

			// Fill the hashtable, using the next empty slot if the hashed slot
			// is taken.
			for ( $u = 0; $u < $count; ++$u ) {
				$hp = $packedTables[$starts[$i] + $u];
				$where = Util::unsignedMod(
					Util::unsignedShiftRight( $hp['h'], 8 ), $len );
				while ( $hashtable[$where]['p'] ) {
					if ( ++$where == $len ) {
						$where = 0;
					}
				}
				$hashtable[$where] = $hp;
			}

			// Write the hashtable
			for ( $u = 0; $u < $len; ++$u ) {
				$buf = pack( 'vvV',
					$hashtable[$u]['h'] & 0xffff,
					Util::unsignedShiftRight( $hashtable[$u]['h'], 16 ),
					$hashtable[$u]['p'] );
				$this->write( $buf );
				$this->posplus( 8 );
			}
		}

		// Write the pointer array at the start of the file
		rewind( $this->handle );
		if ( ftell( $this->handle ) != 0 ) {
			$this->throwException( 'Error rewinding to start of file "' . $this->tmpFileName . '".' );
		}
		$this->write( $final );
	}

	/**
	 * Clean up the temp file and throw an exception
	 *
	 * @param string $msg
	 * @throws Exception
	 */
	protected function throwException( $msg ) {
		if ( $this->handle ) {
			fclose( $this->handle );
			unlink( $this->tmpFileName );
		}
		throw new Exception( $msg );
	}
}
