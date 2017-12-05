<?php

namespace MediaWiki\ExternalStore;

use FormatJson;

/* The serialization format implemented here is as follows.
 *
 * Unsigned integers are encoded using a variable-length big-endian base-128
 * encoding, with the high bit of each non-terminal byte set.
 *
 * Strings are stored as a length integer followed by the bytes of the string.
 * There is no NUL terminator.
 *
 * The serialized string consists of:
 *  - Header bytes: 7F 4D 43 42 FF FE
 *  - (string) Type code. See MultiContentBlob::$knownTypes.
 *  - (int) Feature flags.
 *     - bit 0: If set, the data portion is compressed with gzdeflate.
 *     - bit 1: If set, the saved $data includes keys.
 *     - All other bits are reserved and must be 0.
 *  - Data portion, storing the values returned by MultiContentBlob::getData().
 *    Note there is no length indicator.
 *     - (string) If $metadata is null, the empty string is stored, otherwise
 *       $metadata is JSON-encoded.
 *     - (int) $data count.
 *     - (string[]) COUNT value strings if flags bit 1 is clear,
 *       or COUNT pairs of key string + value string if flags bit 1 is set.
 *  - 4-byte CRC32 checksum ('crc32b' to PHP's hash()) of all the above.
 */

/**
 * Base class for an ExternalStore multi-content blob
 * @since 1.32
 */
abstract class MultiContentBlob {

	/** Maximum total encoded blob size (database limit) */
	const MAX_SIZE = 4294967295;

	/** Map 'type' keys to blob classes. */
	private static $knownTypes = [
		'concat' => ConcatenatedMultiContentBlob::class,
		'xdiff' => XdiffMultiContentBlob::class,
	];

	// These are here to be overridden in unit testing.
	private static $useCompression = true;
	private static $maxSize = self::MAX_SIZE;

	/************************************************************************//**
	 * @name   Data encoding/decoding helpers
	 * @{
	 */

	/**
	 * @param int $n Positive integer
	 * @return string
	 */
	private static function encodeInt( $n ) {
		if ( $n < 0 || $n !== (int)$n ) {
			throw new \InvalidArgumentException( '$n must be a positive integer' );
		}

		$str = chr( $n & 0x7f );
		while ( $n > 0x7f ) {
			$n >>= 7;
			$str = chr( 0x80 | ( $n & 0x7f ) ) . $str;
		}
		return $str;
	}

	/**
	 * @param string $blob Blob to read from.
	 * @param int &$pos Position to start reading. Will be updated.
	 * @return int
	 */
	private static function consumeInt( $blob, &$pos ) {
		static $max = null;

		if ( $max === null ) {
			$max = ( PHP_INT_MAX >> 7 ) - ( ( PHP_INT_MAX & 0x7f ) === 0x7f ? 0 : 1 );
		}

		$n = 0;
		$l = strlen( $blob );
		do {
			if ( $l <= $pos ) {
				throw new \UnderflowException( 'Unexpected end of blob' );
			}
			if ( $n > $max ) {
				throw new \OverflowException( 'Encoded integer is too large' );
			}

			$c = ord( $blob[$pos++] );
			$n = ( $n << 7 ) | ( $c & 0x7f );
		} while ( $c & 0x80 );
		return $n;
	}

	/**
	 * @param string $str
	 * @return string
	 */
	private static function encodeStr( $str ) {
		return self::encodeInt( strlen( $str ) ) . $str;
	}

	/**
	 * @param string $blob Blob to read from.
	 * @param int &$pos Position to start reading. Will be updated.
	 * @return string
	 */
	private static function consumeStr( $blob, &$pos ) {
		$len = self::consumeInt( $blob, $pos );
		if ( $len === 0 ) {
			return '';
		}
		if ( strlen( $blob ) < $pos + $len ) {
			throw new \UnderflowException( 'Unexpected end of blob' );
		}
		$ret = (string)substr( $blob, $pos, $len );
		$pos += $len;
		return $ret;
	}

	/**@}*/

	/**
	 * Encode the blob to a string representation
	 * @return string
	 */
	final public function encode() {
		$blob = '';

		// Get data and metadata
		list( $data, $metadata ) = $this->getData();

		// Encode metadata
		$blob .= self::encodeStr(
			$metadata === null ? '' : FormatJson::encode( $metadata, false, FormatJson::ALL_OK )
		);

		// Encode data
		$keys = array_keys( $data );
		sort( $keys );
		$isAssoc = ( $keys !== array_keys( $keys ) );
		if ( !$isAssoc ) {
			// If it's not an associative array, make sure it gets stored in
			// the right order!
			ksort( $data );
		}
		$blob .= self::encodeInt( count( $data ) );
		foreach ( $data as $k => $v ) {
			if ( $isAssoc ) {
				$blob .= self::encodeStr( (string)$k );
			}
			$blob .= self::encodeStr( $v );
		}

		// Compress blob, if appropriate
		$isCompressed = false;
		if ( self::$useCompression && function_exists( 'gzdeflate' ) ) {
			$compressed = gzdeflate( $blob );
			if ( $compressed !== false && strlen( $compressed ) < strlen( $blob ) ) {
				$isCompressed = true;
				$blob = $compressed;
			}
		}

		// Determine type and flags field
		$types = array_flip( self::$knownTypes );
		if ( !isset( $types[static::class] ) ) {
			throw new \OutOfBoundsException(
				'Class ' . static::class . ' is not in ' . __CLASS__ . '::$knownTypes'
			);
		}
		$type = $types[static::class];
		$flags = ( $isCompressed ? 1 : 0 )
			| ( $isAssoc ? 2 : 0 );

		// Prepend type and flags
		$blob = "\x7fMCB\xff\xfe" . self::encodeStr( $type ) . self::encodeInt( $flags ) . $blob;

		// Calculate and append CRC
		$blob .= hash( 'crc32b', $blob, true );

		// Check max length and return
		if ( strlen( $blob ) > self::$maxSize ) {
			throw new \OverflowException(
				'Final blob for ' . static::class . ' must not exceed ' . self::$maxSize . ' bytes, got '
				. strlen( $blob )
			);
		}
		return $blob;
	}

	/**
	 * Decode the string representation of a MultiContentBlob
	 * @param string $blob
	 * @return MultiContentBlob
	 */
	final public static function decode( $blob ) {
		// Check header
		if ( strlen( $blob ) < 10 ) {
			throw new \UnderflowException( 'Unexpected end of blob' );
		}
		if ( substr( $blob, 0, 6 ) !== "\x7fMCB\xff\xfe" ) {
			throw new \UnexpectedValueException( 'Blob header is missing' );
		}

		// Check CRC
		$blobCrc = substr( $blob, -4 );
		$blob = (string)substr( $blob, 0, -4 );
		$crc = hash( 'crc32b', $blob, true );
		if ( $crc !== $blobCrc ) {
			throw new \UnexpectedValueException( 'Blob CRC check failed' );
		}

		// Get type
		$pos = 6; // past header
		$type = self::consumeStr( $blob, $pos );
		if ( !isset( self::$knownTypes[$type] ) ) {
			throw new \OutOfBoundsException( 'Unknown blob type "' . $type . '"' );
		}

		// Get flags
		$flags = self::consumeInt( $blob, $pos );
		$isCompressed = $flags & 1;
		$isAssoc = $flags & 2;
		if ( ( $flags & ~3 ) !== 0 ) {
			throw new \UnexpectedValueException( 'Unknown blob flags' );
		}

		// Decompress data blob, if so flagged
		if ( $isCompressed ) {
			$blob = gzinflate( substr( $blob, $pos ) );
			$pos = 0;
			if ( $blob === false ) {
				throw new \UnexpectedValueException( 'gzinflate failed' );
			}
		}

		// Extract metadata
		$metadataStr = self::consumeStr( $blob, $pos );
		if ( $metadataStr === '' ) {
			$metadata = null;
		} else {
			$status = FormatJson::parse( $metadataStr, FormatJson::FORCE_ASSOC );
			if ( !$status->isOk() ) {
				$err = $status->getErrors();
				throw new \UnexpectedValueException(
					'Blob metadata JSON decode failed: ' . $err[0]['message']
				);
			}
			$metadata = $status->getValue();
		}

		// Parse data
		$count = self::consumeInt( $blob, $pos );
		$data = [];
		for ( $i = 0; $i < $count; $i++ ) {
			$k = $isAssoc ? self::consumeStr( $blob, $pos ) : $i;
			$data[$k] = self::consumeStr( $blob, $pos );
		}

		// Sanity check
		if ( strlen( $blob ) !== $pos ) {
			throw new \UnexpectedValueException( 'Extra data found in blob' );
		}

		// Create MultiContentBlob for $type
		$class = self::$knownTypes[$type];
		$ret = new $class;
		$ret->setData( $data, $metadata );

		return $ret;
	}

	/**
	 * Check the header of a serialized MultiContentBlob
	 * @param string $blob
	 * @return string|bool Error string, or boolean true
	 */
	final public static function checkHeader( $blob ) {
		if ( strlen( $blob ) < 10 ) {
			return 'Too short';
		}
		if ( substr( $blob, 0, 6 ) !== "\x7fMCB\xff\xfe" ) {
			return 'Blob header is missing';
		}

		$pos = 6; // Past header
		try {
			$type = self::consumeStr( $blob, $pos );
		} catch ( \OverflowException $ex ) {
			return $ex->getMessage();
		} catch ( \UnderflowException $ex ) {
			return $ex->getMessage();
		}

		if ( !isset( self::$knownTypes[$type] ) ) {
			return 'Unknown blob type "' . $type . '"';
		}

		return true;
	}

	/**
	 * Get the current size of the data
	 * @return int
	 */
	abstract public function getSize();

	/**
	 * Get the current number of items
	 * @return int
	 */
	abstract public function getCount();

	/**
	 * Adds an item of text to the blob
	 * @param string $text
	 * @return string The key for getItem()
	 */
	abstract public function addItem( $text );

	/**
	 * Get item by key, or false if the key is not present
	 * @param string $key
	 * @return string|bool
	 */
	abstract public function getItem( $key );

	/**
	 * Get the data to be stored
	 * @return array [ string[] $data, array|null $metadata ]
	 *  - $data is expected to be an array of large strings representing the
	 *    actual content data.
	 *  - $metadata is any additional data that needs to be stored. It must be
	 *    serializable via JSON.
	 */
	abstract protected function getData();

	/**
	 * Restore saved data
	 *
	 * Called from MultiContentBlob::decode() to restore the blob state after
	 * decoding the string.
	 *
	 * @param string[] $data
	 * @param array|null $metadata
	 */
	abstract protected function setData( array $data, array $metadata = null );
}

/**
 * For really cool vim folding this needs to be at the end:
 * vim: foldmarker=@{,@} foldmethod=marker
 */
