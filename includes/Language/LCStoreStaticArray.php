<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Language;

use RuntimeException;
use Wikimedia\StaticArrayWriter;

/**
 * Localisation cache storage based on PHP files and static arrays.
 * This is meant to leverage the PHP opcache and may require increasing
 * memory sizes on a production site that handles many languages routinely.
 *
 * These `php.ini` settings are sufficient for some local testing, but
 * would be rather too small for Wikimedia production which has many
 * localised extensions installed.
 *
 * - `opcache.memory_consumption=1024`
 * - `opcache.interned_strings_buffer=256`
 *
 *
 * As of MediaWiki 1.46, this cache backend does two important things
 * differently from the CDB backend:
 *
 * - Late fallback: tells LocalisationCache to do late fallback/merge
 *   logic at read time, reducing redundant data in the `.l10n.php` files
 *   for a slight, hard to measure runtime cost in looking up a few more
 *   arrays.
 *
 *   This gives a massive reduction in both disk usage and opcode cache
 *   usage versus pre-merging all the fallbacks: strings are already
 *   deduplicated in the opcache's string interning but const array
 *   hashmap data is not!
 *
 * - Re-assembly of split keys: the `messages` array is reconstructed
 *   to avoid duplicating the prefix 'messages:' thousands of times and
 *   make the key list cheap to load.
 *
 * Additionally, LCStoreStaticArray silently drops the 'preload' and
 * 'preloadMessages' data duplication in each language, as it's cheap
 * to query the messages individually.
 *
 * The resulting cache files are about ~80% smaller than under 1.45
 * due to the drop in redundant data, and compress very well for delta
 * copies.
 *
 * Older cached data from before 1.46 is backwards-compatible on read.
 *
 * @since 1.26
 * @ingroup Language
 */
class LCStoreStaticArray implements LCStore {

	/** Individual message subkeys have this prefix in the raw queries */
	private const MESSAGES_PREFIX = 'messages:';

	/** @var string|null Current language code. */
	private $currentLang = null;

	/** @var array Localisation data. */
	private $data = [];

	/** @var string|null File name. */
	private $fname = null;

	/** @var string Directory for cache files. */
	private $directory;

	public function __construct( array $conf = [] ) {
		$this->directory = $conf['directory'];
	}

	/** @inheritDoc */
	public function startWrite( $code ) {
		if ( !is_dir( $this->directory ) && !wfMkdirParents( $this->directory, null, __METHOD__ ) ) {
			throw new RuntimeException( "Unable to create the localisation store " .
				"directory \"{$this->directory}\"" );
		}
		$this->currentLang = $code;
		$this->fname = $this->directory . '/' . $code . '.l10n.php';
		$this->data[$code] = [];
		if ( is_file( $this->fname ) ) {
			$this->data[$code] = require $this->fname;
		}
	}

	/** @inheritDoc */
	public function set( $key, $value ) {
		if ( $key === 'list' ) {
			// We will recreate this on read from the actual keys
			unset( $value['messages'] );
		}
		if ( $key === 'preload' || $key === 'preloadedMessages' ) {
			// We don't need this, it's cheap to query the cache.
			$value = [];
		}
		$encoded = self::encode( $value );
		$data =& $this->data[$this->currentLang];
		if ( str_starts_with( $key, self::MESSAGES_PREFIX ) ) {
			$message = substr( $key, strlen( self::MESSAGES_PREFIX ) );
			$data['messages'][$message] = $encoded;
		} else {
			$data[$key] = $encoded;
		}
	}

	/**
	 * Determine whether this array contains only scalar values.
	 *
	 * @param array $arr
	 * @return bool
	 */
	private static function isValueArray( array $arr ) {
		foreach ( $arr as $value ) {
			if ( is_scalar( $value )
				|| $value === null
				|| ( is_array( $value ) && self::isValueArray( $value ) )
			) {
				continue;
			}
			return false;
		}
		return true;
	}

	/**
	 * Encodes a value into an array format
	 *
	 * @param mixed $value
	 * @return array|mixed
	 * @throws RuntimeException
	 */
	public static function encode( $value ) {
		if ( is_array( $value ) && self::isValueArray( $value ) ) {
			// Type: scalar [v]alue.
			// Optimization: Write large arrays as one value to avoid recursive decoding cost.
			return [ 'v', $value ];
		}
		if ( is_array( $value ) || is_object( $value ) ) {
			// Type: [s]serialized.
			// Optimization: Avoid recursive decoding cost. Write arrays with an objects
			// as one serialised value.
			return [ 's', serialize( $value ) ];
		}
		if ( is_scalar( $value ) || $value === null ) {
			// Optimization: Reduce file size by not wrapping scalar values.
			return $value;
		}

		throw new RuntimeException( 'Cannot encode ' . var_export( $value, true ) );
	}

	/**
	 * Decode something that was encoded with 'encode'
	 *
	 * @param mixed $encoded
	 * @return array|mixed
	 * @throws RuntimeException
	 */
	public static function decode( $encoded ) {
		if ( !is_array( $encoded ) ) {
			// Unwrapped scalar value
			return $encoded;
		}

		[ $type, $data ] = $encoded;

		switch ( $type ) {
			case 'v':
				// Value array (1.35+) or unwrapped scalar value (1.32 and earlier)
				return $data;
			case 's':
				return unserialize( $data );
			case 'a':
				// Support: MediaWiki 1.34 and earlier (older file format)
				return array_map( self::decode( ... ), $data );
			default:
				throw new RuntimeException(
					'Unable to decode ' . var_export( $encoded, true ) );
		}
	}

	public function finishWrite() {
		$this->atomicWrite( $this->fname, $this->data[$this->currentLang] );

		// Release the data to manage the memory in rebuildLocalisationCache
		unset( $this->data[$this->currentLang] );
		$this->currentLang = null;
		$this->fname = null;
	}

	protected function atomicWrite( string $fileName, array $data ): void {
		$writer = new StaticArrayWriter();
		$out = $writer->create(
			$data,
			'Generated by LCStoreStaticArray.php -- do not edit!'
		);
		// Don't just write to the file, since concurrent requests may see a partial file (T304515).
		// Write to a file in the same filesystem so that it can be atomically moved.
		$tmpFileName = "$fileName.tmp." . getmypid() . '.' . mt_rand();
		file_put_contents( $tmpFileName, $out );

		rename( $tmpFileName, $fileName );
	}

	protected function getMessageKey( string $key ): ?string {
		if ( str_starts_with( $key, self::MESSAGES_PREFIX ) ) {
			return substr( $key, strlen( self::MESSAGES_PREFIX ) );
		}
		return null;
	}

	/** @inheritDoc */
	public function get( $code, $key ) {
		if ( !$this->loadLanguage( $code ) ) {
			return null;
		}
		$data =& $this->data[$code];

		$value = $data[$key] ?? null;

		if ( $value === null ) {
			// Reassembled split keys are new in 1.46, reducing redundancy in the files.
			if ( str_starts_with( $key, self::MESSAGES_PREFIX ) ) {
				$message = $this->getMessageKey( $key );
				$value = $data['messages'][$message] ?? null;
			}
		}

		$value = $this->decode( $value );

		if ( $key === 'list' ) {
			if ( !isset( $value['messages'] ) ) {
				// No need to store per-language, we have the list
				$value['messages'] = array_keys( $data['messages'] ?? [] );
			}
		}

		return $value;
	}

	private function loadLanguage( string $code ): bool {
		if ( !array_key_exists( $code, $this->data ) ) {
			$fname = $this->directory . '/' . $code . '.l10n.php';
			if ( !is_file( $fname ) ) {
				return false;
			}
			$data = require $fname;
			if ( !is_array( $data ) ) {
				return false;
			}
			$this->data[$code] = $data;
		}
		return true;
	}

	/** @inheritDoc */
	public function lateFallback(): bool {
		return true;
	}
}

/** @deprecated class alias since 1.46 */
class_alias( LCStoreStaticArray::class, 'LCStoreStaticArray' );
