<?php
/**
 * This is a port of D.J. Bernstein's CDB to PHP. It's based on the copy that
 * appears in PHP 5.3. Changes are:
 *    * Error returns replaced with exceptions
 *    * Exception thrown if sizes or offsets are between 2GB and 4GB
 *    * Some variables renamed
 *
 * @file
 */

/**
 * Common functions for readers and writers
 */
class CdbFunctions {
	/**
	 * Take a modulo of a signed integer as if it were an unsigned integer.
	 * $b must be less than 0x40000000 and greater than 0
	 *
	 * @param $a
	 * @param $b
	 *
	 * @return int
	 */
	public static function unsignedMod( $a, $b ) {
		if ( $a & 0x80000000 ) {
			$m = ( $a & 0x7fffffff ) % $b + 2 * ( 0x40000000 % $b );
			return $m % $b;
		} else {
			return $a % $b;
		}
	}

	/**
	 * Shift a signed integer right as if it were unsigned
	 * @param $a
	 * @param $b
	 * @return int
	 */
	public static function unsignedShiftRight( $a, $b ) {
		if ( $b == 0 ) {
			return $a;
		}
		if ( $a & 0x80000000 ) {
			return ( ( $a & 0x7fffffff ) >> $b ) | ( 0x40000000 >> ( $b - 1 ) );
		} else {
			return $a >> $b;
		}
	}

	/**
	 * The CDB hash function.
	 *
	 * @param $s
	 *
	 * @return
	 */
	public static function hash( $s ) {
		$h = 5381;
		for ( $i = 0; $i < strlen( $s ); $i++ ) {
			$h5 = ($h << 5) & 0xffffffff;
			// Do a 32-bit sum
			// Inlined here for speed
			$sum = ($h & 0x3fffffff) + ($h5 & 0x3fffffff);
			$h =
				(
					( $sum & 0x40000000 ? 1 : 0 )
					+ ( $h & 0x80000000 ? 2 : 0 )
					+ ( $h & 0x40000000 ? 1 : 0 )
					+ ( $h5 & 0x80000000 ? 2 : 0 )
					+ ( $h5 & 0x40000000 ? 1 : 0 )
				) << 30
				| ( $sum & 0x3fffffff );
			$h ^= ord( $s[$i] );
			$h &= 0xffffffff;
		}
		return $h;
	}
}

/**
 * CDB reader class
 */
class CdbReader_PHP extends CdbReader {
	/** The filename */
	var $fileName;

	/** The file handle */
	var $handle;

	/* number of hash slots searched under this key */
	var $loop;

	/* initialized if loop is nonzero */
	var $khash;

	/* initialized if loop is nonzero */
	var $kpos;

	/* initialized if loop is nonzero */
	var $hpos;

	/* initialized if loop is nonzero */
	var $hslots;

	/* initialized if findNext() returns true */
	var $dpos;

	/* initialized if cdb_findnext() returns 1 */
	var $dlen;

	/**
	 * @param $fileName string
	 */
	function __construct( $fileName ) {
		$this->fileName = $fileName;
		$this->handle = fopen( $fileName, 'rb' );
		if ( !$this->handle ) {
			throw new MWException( 'Unable to open CDB file "' . $this->fileName . '".' );
		}
		$this->findStart();
	}

	function close() {
		if( isset( $this->handle ) ) {
			fclose( $this->handle );
		}
		unset( $this->handle );
	}

	/**
	 * @param $key
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
	 * @param $key
	 * @param $pos
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
	 * @throws MWException
	 * @param $length
	 * @param $pos
	 * @return string
	 */
	protected function read( $length, $pos ) {
		if ( fseek( $this->handle, $pos ) == -1 ) {
			// This can easily happen if the internal pointers are incorrect
			throw new MWException( 
				'Seek failed, file "' . $this->fileName . '" may be corrupted.' );
		}

		if ( $length == 0 ) {
			return '';
		}

		$buf = fread( $this->handle, $length );
		if ( $buf === false || strlen( $buf ) !== $length ) {
			throw new MWException(
				'Read from CDB file failed, file "' . $this->fileName . '" may be corrupted.' );
		}
		return $buf;
	}

	/**
	 * Unpack an unsigned integer and throw an exception if it needs more than 31 bits
	 * @param $s
	 * @return
	 */
	protected function unpack31( $s ) {
		$data = unpack( 'V', $s );
		if ( $data[1] > 0x7fffffff ) {
			throw new MWException( 
				'Error in CDB file "' . $this->fileName . '", integer too big.' );
		}
		return $data[1];
	}

	/**
	 * Unpack a 32-bit signed integer
	 * @param $s
	 * @return int
	 */
	protected function unpackSigned( $s ) {
		$data = unpack( 'va/vb', $s );
		return $data['a'] | ( $data['b'] << 16 );
	}

	/**
	 * @param $key
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
	 * @param $key
	 * @return bool
	 */
	protected function find( $key ) {
		$this->findStart();
		return $this->findNext( $key );
	}
}

/**
 * CDB writer class
 */
class CdbWriter_PHP extends CdbWriter {
	var $handle, $realFileName, $tmpFileName;

	var $hplist;
	var $numentries, $pos;

	/**
	 * @param $fileName string
	 */
	function __construct( $fileName ) {
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

	function __destruct() {
		if ( isset( $this->handle ) ) {
			$this->close();
		}
	}

	/**
	 * @param $key
	 * @param $value
	 * @return
	 */
	public function set( $key, $value ) {
		if ( strval( $key ) === '' ) {
			// DBA cross-check hack
			return;
		}
		$this->addbegin( strlen( $key ), strlen( $value ) );
		$this->write( $key );
		$this->write( $value );
		$this->addend( strlen( $key ), strlen( $value ), CdbFunctions::hash( $key ) );
	}

	/**
	 * @throws MWException
	 */
	public function close() {
		$this->finish();
		if( isset($this->handle) ) {
			fclose( $this->handle );
		}
		if ( wfIsWindows() && file_exists($this->realFileName) ) {
			unlink( $this->realFileName );
		}
		if ( !rename( $this->tmpFileName, $this->realFileName ) ) {
			$this->throwException( 'Unable to move the new CDB file into place.' );
		}
		unset( $this->handle );
	}

	/**
	 * @throws MWException
	 * @param $buf
	 */
	protected function write( $buf ) {
		$len = fwrite( $this->handle, $buf );
		if ( $len !== strlen( $buf ) ) {
			$this->throwException( 'Error writing to CDB file "'.$this->tmpFileName.'".' );
		}
	}

	/**
	 * @throws MWException
	 * @param $len
	 */
	protected function posplus( $len ) {
		$newpos = $this->pos + $len;
		if ( $newpos > 0x7fffffff ) {
			$this->throwException(
				'A value in the CDB file "'.$this->tmpFileName.'" is too large.' );
		}
		$this->pos = $newpos;
	}

	/**
	 * @param $keylen
	 * @param $datalen
	 * @param $h
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
	 * @throws MWException
	 * @param $keylen
	 * @param $datalen
	 */
	protected function addbegin( $keylen, $datalen ) {
		if ( $keylen > 0x7fffffff ) {
			$this->throwException( 'Key length too long in file "'.$this->tmpFileName.'".' );
		}
		if ( $datalen > 0x7fffffff ) {
			$this->throwException( 'Data length too long in file "'.$this->tmpFileName.'".' );
		}
		$buf = pack( 'VV', $keylen, $datalen );
		$this->write( $buf );
	}

	/**
	 * @throws MWException
	 */
	protected function finish() {
		// Hack for DBA cross-check
		$this->hplist = array_reverse( $this->hplist );

		// Calculate the number of items that will be in each hashtable
		$counts = array_fill( 0, 256, 0 );
		foreach ( $this->hplist as $item ) {
			++ $counts[ 255 & $item['h'] ];
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
				$where = CdbFunctions::unsignedMod(
					CdbFunctions::unsignedShiftRight( $hp['h'], 8 ), $len );
				while ( $hashtable[$where]['p'] )
					if ( ++$where == $len )
						$where = 0;
				$hashtable[$where] = $hp;
			}

			// Write the hashtable
			for ( $u = 0; $u < $len; ++$u ) {
				$buf = pack( 'vvV',
					$hashtable[$u]['h'] & 0xffff,
					CdbFunctions::unsignedShiftRight( $hashtable[$u]['h'], 16 ),
					$hashtable[$u]['p'] );
				$this->write( $buf );
				$this->posplus( 8 );
			}
		}

		// Write the pointer array at the start of the file
		rewind( $this->handle );
		if ( ftell( $this->handle ) != 0 ) {
			$this->throwException( 'Error rewinding to start of file "'.$this->tmpFileName.'".' );
		}
		$this->write( $final );
	}

	/**
	 * Clean up the temp file and throw an exception
	 * 
	 * @param $msg string
	 * @throws MWException
	 */
	protected function throwException( $msg ) {
		if ( $this->handle ) {
			fclose( $this->handle );
			unlink( $this->tmpFileName );
		}
		throw new MWException( $msg );
	}
}
