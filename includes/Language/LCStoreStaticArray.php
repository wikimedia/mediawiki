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
 *
 * This is meant to leverage the PHP opcache and may require increasing
 * opcache memory on production sites that load many extensions with
 * extra interface messages and/or serves requests with many different
 * languages (content languages or user languages).
 *
 * The below `php.ini` settings should suffice for local testing, but would
 * be too small for Wikimedia Foundation production, where more extension
 * messages and languages are loaded.
 *
 * ```
 * ; Default: 128M
 * opcache.memory_consumption=1024
 * ; Default: 8M
 * opcache.interned_strings_buffer=256
 * ```
 *
 * As of MediaWiki 1.46, this cache backend does two important things
 * differently from the CDB backend:
 *
 * - Late fallback: tells LocalisationCache to do late fallback/merge
 *   logic at read time, reducing redundant data in the `.l10n.php` files
 *   in exchange for a few more array lookups at runtime (immeasurable cost).
 *
 *   This gives a massive reduction in both disk usage and opcache memory
 *   usage versus pre-merging all the fallbacks: strings are already
 *   deduplicated in the opcache's string interning but const array
 *   hashmap data is not!
 *
 * - Drop redundant 'preload' and 'preloadMessages' data from the cache files,
 *   because regular reads in LCStoreStaticArray are so fast that we can query
 *   the messages individually without preloading.
 *
 * - Drop redundant 'message:' prefix from message keys. Instead store messages in a
 *   'messages' array and re-assemble the split key at read time to avoid duplicating
 *   the 'messages:' prefix thousands of times.
 *
 * - Drop redundant 'list' data. This is cheaply computed by reading the keys
 *   of the 'messages' array instead.
 *
 * The resulting cache files are about ~80% smaller than under MediaWiki 1.45
 * due to the drop in redundant data, and compress very well for delta
 * copies.
 *
 * Older caches from MediaWiki 1.45 and older are backwards-compatible on read.
 *
 * @since 1.26
 * @ingroup Language
 */
class LCStoreStaticArray extends LCStore {

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
		// Optimization: Omit from storage. Cheap to recreate in get() from the keys.
		if ( $key === 'list' ) {
			unset( $value['messages'] );
		}
		// Optimization: Omit from storage. Preload batch not needed for array reads.
		// Store an empty value instead of omitting the key, because
		// LocalisationCache::initLanguage must distinguish it from a missing file.
		if ( $key === 'preload' || $key === 'preloadedMessages' ) {
			$value = [];
		}
		$encoded = self::encode( $value );
		// Optimization: Split key to reduce redundancy and thus file size, since MediaWiki 1.46.
		if ( str_starts_with( $key, self::MESSAGES_PREFIX ) ) {
			$message = substr( $key, strlen( self::MESSAGES_PREFIX ) );
			$this->data[$this->currentLang]['messages'][$message] = $encoded;
		} else {
			$this->data[$this->currentLang][$key] = $encoded;
		}
	}

	/** @inheritDoc */
	public function setMessages( array $messages ): void {
		// Optimization: Avoid a set() call per message, and encode() for plain strings
		$data =& $this->data[$this->currentLang];
		foreach ( $messages as $key => $value ) {
			$data['messages'][$key] = is_string( $value ) ? $value : self::encode( $value );
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
	 * Decode an array that was encoded with 'encode'
	 *
	 * Scalar values are stored unencoded, so callers must check is_array() first.
	 *
	 * @param array $encoded
	 * @return array|mixed
	 * @throws RuntimeException
	 */
	public static function decode( array $encoded ) {
		[ $type, $data ] = $encoded;

		switch ( $type ) {
			case 'v':
				// Value array (1.35+) or unwrapped scalar value (1.32 and earlier)
				return $data;
			case 's':
				return unserialize( $data );
			case 'a':
				// Support: MediaWiki 1.34 and earlier (older file format)
				return array_map( static fn ( $value ) => is_array( $value ) ? self::decode( $value ) : $value, $data );
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
		$out = StaticArrayWriter::write(
			$data,
			'Generated by LCStoreStaticArray.php -- do not edit!',
			// Optimization: Speed up writing of ~150MB during a full RebuildLocalisationCache
			StaticArrayWriter::UNFORMATTED
		);
		// Don't just write to the file, since concurrent requests may see a partial file (T304515).
		// Write to a file in the same filesystem so that it can be atomically moved.
		$tmpFileName = "$fileName.tmp." . getmypid() . '.' . mt_rand();
		file_put_contents( $tmpFileName, $out );

		rename( $tmpFileName, $fileName );
	}

	/** @inheritDoc */
	public function get( $code, $key ) {
		// Optimization: Skip function call in hot path for every message lookup, and each fallback language
		$data = $this->data[$code] ?? null;
		if ( $data === null ) {
			if ( !$this->loadLanguage( $code ) ) {
				return null;
			}
			$data = $this->data[$code];
		}

		if ( str_starts_with( $key, self::MESSAGES_PREFIX ) ) {
			$messageKey = substr( $key, strlen( self::MESSAGES_PREFIX ) );
			$value = $data['messages'][$messageKey]
				// Support: MediaWiki 1.45 and earlier (message keys not yet split)
				?? $data[$key]
				?? null;
		} else {
			$value = $data[$key] ?? null;
			if ( $key === 'list' ) {
				$value = is_array( $value ) ? self::decode( $value ) : [];
				$value['messages'] ??= array_keys( $data['messages'] ?? [] );
				return $value;
			}
		}

		// Most values are unencoded scalars, see encode()
		return is_array( $value ) ? self::decode( $value ) : $value;
	}

	private function loadLanguage( string $code ): bool {
		if ( !array_key_exists( $code, $this->data ) ) {
			$fname = $this->directory . '/' . $code . '.l10n.php';
			if ( !is_file( $fname ) ) {
				return false;
			}
			$this->data[$code] = require $fname;
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
