<?php

namespace MediaWiki\Storage\Sql;

use DatabaseBase;
use DBError;
use ExternalStore;
use IDBAccessObject;
use LoadBalancer;
use MapCacheLRU;
use MediaWiki\Storage\BlobStore;
use MediaWiki\Storage\NotFoundException;
use MediaWiki\Storage\StorageException;
use WANObjectCache;
use Wikimedia\Assert\Assert;

/**
 * Storage service for storing and loading blobs, based on MediaWiki's legacy
 * storage system using the "text" table in the local database.
 *
 * This includes support for the built-in "external storage" mechanism.
 *
 * @todo refactor ExternalStore into a BlobStore implementation.
 * @todo factor caching into a wrapper.
 *
 * @license GPL 2+
 * @author Daniel Kinzler
 */
class TextTableBlobStore implements BlobStore {

	/**
	 * @var LoadBalancer
	 */
	private $dbLoadBalancer;

	/**
	 * @var bool|string|array
	 */
	private $externalStore = false;

	/**
	 * @var MapCacheLRU|null
	 */
	private $localCache = null;

	/**
	 * @var WANObjectCache|null
	 */
	private $wanCache = null;

	/**
	 * @var int seconds
	 */
	private $cacheExpiry = 0;

	/**
	 * @var int maximum number of cache entries
	 */
	private $cacheCapacity = 10;

	/**
	 * TextTableBlobStore constructor.
	 *
	 * @param LoadBalancer $dbLoadBalancer
	 */
	function __construct( LoadBalancer $dbLoadBalancer ) {
		$this->dbLoadBalancer = $dbLoadBalancer;
	}

	/**
	 * @return bool|string|array See $wgDefaultExternalStore
	 */
	public function getExternalStore() {
		return $this->externalStore;
	}

	/**
	 * @param bool|string|array $externalStore See $wgDefaultExternalStore
	 */
	public function setExternalStore( $externalStore ) {
		$this->externalStore = $externalStore;
	}

	/**
	 * @return MapCacheLRU
	 */
	private function getLocalCache() {
		if ( $this->localCache === null ) {
			$this->localCache = new MapCacheLRU( $this->cacheCapacity );
		}

		return $this->localCache;
	}

	/**
	 * @return WANObjectCache|null
	 */
	public function getWanCache() {
		return $this->wanCache;
	}

	/**
	 * @param WANObjectCache $wanCache
	 */
	public function setWanCache( WANObjectCache $wanCache ) {
		$this->wanCache = $wanCache;
	}

	/**
	 * @return int
	 */
	public function getCacheExpiry() {
		return $this->cacheExpiry;
	}

	/**
	 * Set the capacity for the WAN cache.
	 * Also enables in-process caching if set to a value greater than zero.
	 *
	 * @param int $cacheExpiry (use 0 to disable)
	 */
	public function setCacheExpiry( $cacheExpiry ) {
		Assert::parameterType( 'integer', $cacheExpiry, '$cacheExpiry' );
		Assert::parameter( $cacheExpiry >= 0, '$cacheExpiry', 'must be positive!' );

		$this->cacheExpiry = $cacheExpiry;
	}

	/**
	 * @return int
	 */
	public function getCacheCapacity() {
		return $this->cacheCapacity;
	}

	/**
	 * Set the capacity for the local cache.
	 * Local caching will only apply if the cache expiry is not 0.
	 *
	 * @param int $cacheCapacity (use 0 to disable)
	 */
	public function setCacheCapacity( $cacheCapacity ) {
		Assert::parameterType( 'integer', $cacheCapacity, '$cacheCapacity' );
		Assert::parameter( $cacheCapacity >= 0, '$cacheCapacity', 'must be positive!' );

		$this->cacheCapacity = $cacheCapacity;

		if ( $this->localCache ) {
			$oldCache = $this->localCache;
			$this->localCache = null;

			$newCache = $this->getLocalCache();

			foreach ( $oldCache->getAllKeys() as $key ) {
				$newCache->set( $key, $oldCache->get( $key ) );
			}
		}
	}

	/**
	 * @see BlobLookup::getHints
	 *
	 * The current implementation always returns an empty array.
	 *
	 * @param string $address
	 * @param string $fetchBlob
	 *
	 * @return array
	 */
	public function getHints( $address, $fetchBlob = 'no' ) {
		return [];
	}

	/**
	 * @param string $address A text table row ID
	 * @param int $queryFlags
	 *
	 *
	 * @return string if the requested data blob was not found
	 */
	public function loadData( $address, $queryFlags = 0 ) {
		// NOTE: the below was copied from Revision::loadText() with only slight modifications.

		$textId = (int)$address;

		// TODO: move caching into a decorator!
		$processCache = $this->getLocalCache();
		$wanCache = $this->getWanCache();

		$key = wfMemcKey( 'revisiontext', 'textid', $textId );

		// Caching may be beneficial for massive use of external storage
		if ( $this->cacheExpiry ) {
			if ( $processCache->has( $key ) ) {
				return $processCache->get( $key );
			}
			$text = $wanCache ? $wanCache->get( $key ) : null;
			if ( is_string( $text ) ) {
				$processCache->set( $key, $text );
				return $text;
			}
		}

		// Text data is immutable; check slaves first.
		try {
			$dbr = wfGetDB( DB_SLAVE );
			$row = $dbr->selectRow( 'text',
			                        [ 'old_text', 'old_flags' ],
			                        [ 'old_id' => $textId ],
			                        __METHOD__ );

			// Fallback to the master in case of slave lag. Also use FOR UPDATE if it was
			// used to fetch this revision to avoid missing the row due to REPEATABLE-READ.
			$forUpdate = ( $queryFlags & IDBAccessObject::READ_LOCKING == IDBAccessObject::READ_LOCKING );
			if ( !$row && ( $forUpdate || wfGetLB()->getServerCount() > 1 ) ) {
				$dbw = wfGetDB( DB_MASTER );
				$row = $dbw->selectRow( 'text',
				                        [ 'old_text', 'old_flags' ],
				                        [ 'old_id' => $textId ],
				                        __METHOD__,
				                        $forUpdate ? [ 'FOR UPDATE' ] : [] );
			}

			if ( !$row ) {
				throw new NotFoundException( "No text row with ID $address" );
			}

			$text = self::getRevisionText( $row );
			if ( $text === false ) {
				throw new StorageException( "Failed to decode blob from text row $address" );
			}

			// No negative caching -- negative hits on text rows may be due to corrupted slave servers
			if ( $this->cacheExpiry ) {
				$processCache->set( $key, $text );

				if ( $wanCache ) {
					$wanCache->set( $key, $text, $this->cacheExpiry );
				}
			}
		} catch ( DBError $ex ) {
			throw new StorageException( $ex->getMessage(), 0, $ex );
		}

		return $text;
	}

	/**
	 * Stores a binary data blob and returns an address for retrieving it later.
	 * Optionally, an address may provided to be replaced. Implementations may
	 * or may not remove the previous content, and may or may not return the
	 * same address again. Content addressable stores shall, by nature,
	 * ignore $replaceAddress completely.
	 *
	 * @param string $data binary data to store
	 * @param array $hints Currently unused.
	 *
	 * @return string if a storage level error occurred
	 *
	 */
	public function storeData( $data, $hints = [] ) {
		$dbw = $this->dbLoadBalancer->getConnection( DB_MASTER );

		$old_id = $this->insert( $dbw, $data );

		$this->dbLoadBalancer->reuseConnection( $dbw );
		return "$old_id";
	}

	/**
	 * Insert a new revision into the database, returning the new revision ID
	 * number on success and dies horribly on failure.
	 *
	 * @param DatabaseBase $dbw (master connection)
	 * @throws StorageException, DBError
	 * @return int
	 */
	private function insert( DatabaseBase $dbw, $data ) {
		$flags = self::compressRevisionText( $data );

		# Write to external storage if required
		if ( $this->externalStore ) {
			// Store and get the URL
			// TODO: replace static ExternalStore interface with a proper BlobStore implementation.
			$data = ExternalStore::insertWithFallback( (array)$this->externalStore, $data );
			if ( !$data ) {
				throw new StorageException( "Unable to store text to external storage" );
			}
			if ( $flags ) {
				$flags .= ',';
			}
			$flags .= 'external';
		}

		try {
			$old_id = $dbw->nextSequenceValue( 'text_old_id_seq' );
			$dbw->insert( 'text',
			              [
				              'old_id' => $old_id,
				              'old_text' => $data,
				              'old_flags' => $flags,
			              ], __METHOD__
			);

			$old_id = $dbw->insertId();
			return $old_id;
		} catch ( DBError $ex ) {
			throw new StorageException( $ex->getMessage(), 0, $ex );
		}
	}

	/**
	 * Get revision text associated with an old or archive row
	 * $row is usually an object from wfFetchRow(), both the flags and the text
	 * field must be included.
	 *
	 * @note: This is public for backward compatibility only. Avoid direct usage.
	 *
	 * @param \stdClass $row The text data
	 * @param string $prefix Table prefix (default 'old_')
	 * @param string|bool $wiki The name of the wiki to load the revision text from
	 *   (same as the the wiki $row was loaded from) or false to indicate the local
	 *   wiki (this is the default). Otherwise, it must be a symbolic wiki database
	 *   identifier as understood by the LoadBalancer class.
	 * @return string|bool Text the text requested or false on failure (or false on failure)
	 */
	public static function getRevisionText( $row, $prefix = 'old_', $wiki = false ) {

		# Get data
		$textField = $prefix . 'text';
		$flagsField = $prefix . 'flags';

		if ( isset( $row->$flagsField ) ) {
			$flags = explode( ',', $row->$flagsField );
		} else {
			$flags = [];
		}

		if ( isset( $row->$textField ) ) {
			$text = $row->$textField;
		} else {
			return false;
		}

		# Use external methods for external objects, text in table is URL-only then
		if ( in_array( 'external', $flags ) ) {
			$url = $text;
			$parts = explode( '://', $url, 2 );
			if ( count( $parts ) == 1 || $parts[1] == '' ) {
				return false;
			}
			$text = ExternalStore::fetchFromURL( $url, [ 'wiki' => $wiki ] );
		}

		// If the text was fetched without an error, convert it
		if ( $text !== false ) {
			$text = self::decompressRevisionText( $text, $flags );
		}
		return $text;
	}

	/**
	 * If $wgCompressRevisions is enabled, we will compress data.
	 * The input string is modified in place.
	 * Return value is the flags field: contains 'gzip' if the
	 * data is compressed, and 'utf-8' if we're saving in UTF-8
	 * mode.
	 *
	 * @note: This is public for backward compatibility only. Avoid direct usage.
	 *
	 * @param mixed $text Reference to a text
	 * @return string
	 */
	public static function compressRevisionText( &$text ) {
		global $wgCompressRevisions;
		$flags = [];

		# Revisions not marked this way will be converted
		# on load if $wgLegacyCharset is set in the future.
		$flags[] = 'utf-8';

		if ( $wgCompressRevisions ) {
			if ( function_exists( 'gzdeflate' ) ) {
				$deflated = gzdeflate( $text );

				if ( $deflated === false ) {
					wfLogWarning( __METHOD__ . ': gzdeflate() failed' );
				} else {
					$text = $deflated;
					$flags[] = 'gzip';
				}
			} else {
				wfDebug( __METHOD__ . " -- no zlib support, not compressing\n" );
			}
		}
		return implode( ',', $flags );
	}

	/**
	 * Re-converts revision text according to it's flags.
	 *
	 * @note: This is public for backward compatibility only. Avoid direct usage.
	 *
	 * @param mixed $text Reference to a text
	 * @param array $flags Compression flags
	 * @return string|bool Decompressed text, or false on failure
	 */
	public static function decompressRevisionText( $text, $flags ) {
		if ( in_array( 'gzip', $flags ) ) {
			# Deal with optional compression of archived pages.
			# This can be done periodically via maintenance/compressOld.php, and
			# as pages are saved if $wgCompressRevisions is set.
			$text = gzinflate( $text );

			if ( $text === false ) {
				wfLogWarning( __METHOD__ . ': gzinflate() failed' );
				return false;
			}
		}

		if ( in_array( 'object', $flags ) ) {
			# Generic compressed storage
			$obj = unserialize( $text );
			if ( !is_object( $obj ) ) {
				// Invalid object
				return false;
			}
			$text = $obj->getText();
		}

		global $wgLegacyEncoding;
		if ( $text !== false && $wgLegacyEncoding
			&& !in_array( 'utf-8', $flags ) && !in_array( 'utf8', $flags )
		) {
			# Old revisions kept around in a legacy encoding?
			# Upconvert on demand.
			# ("utf8" checked for compatibility with some broken
			#  conversion scripts 2008-12-30)
			global $wgContLang;
			$text = $wgContLang->iconv( $wgLegacyEncoding, 'UTF-8', $text );
		}

		return $text;
	}

}
