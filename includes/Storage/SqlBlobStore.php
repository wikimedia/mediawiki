<?php
/**
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
 * Attribution notice: when this file was created, much of its content was taken
 * from the Revision.php file as present in release 1.30. Refer to the history
 * of that file for original authorship (that file was removed entirely in 1.37,
 * but its history can still be found in prior versions of MediaWiki).
 *
 * @file
 */

namespace MediaWiki\Storage;

use AppendIterator;
use ExternalStoreAccess;
use ExternalStoreException;
use HistoryBlobUtils;
use InvalidArgumentException;
use StatusValue;
use Wikimedia\Assert\Assert;
use Wikimedia\ObjectCache\BagOStuff;
use Wikimedia\ObjectCache\WANObjectCache;
use Wikimedia\Rdbms\DBAccessObjectUtils;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\IDBAccessObject;
use Wikimedia\Rdbms\ILoadBalancer;

/**
 * Service for storing and loading Content objects representing revision data blobs.
 *
 * @since 1.31
 *
 * @note This was written to act as a drop-in replacement for the corresponding
 *       static methods in the old Revision class (which was later removed in 1.37).
 */
class SqlBlobStore implements BlobStore {

	// Note: the name has been taken unchanged from the old Revision class.
	public const TEXT_CACHE_GROUP = 'revisiontext:10';

	/** @internal */
	public const DEFAULT_TTL = 7 * 24 * 3600; // 7 days

	/**
	 * @var ILoadBalancer
	 */
	private $dbLoadBalancer;

	/**
	 * @var ExternalStoreAccess
	 */
	private $extStoreAccess;

	/**
	 * @var WANObjectCache
	 */
	private $cache;

	/**
	 * @var string|bool DB domain ID of a wiki or false for the local one
	 */
	private $dbDomain;

	/**
	 * @var int
	 */
	private $cacheExpiry = self::DEFAULT_TTL;

	/**
	 * @var bool
	 */
	private $compressBlobs = false;

	/**
	 * @var string|false
	 */
	private $legacyEncoding = false;

	/**
	 * @var bool
	 */
	private $useExternalStore = false;

	/**
	 * @param ILoadBalancer $dbLoadBalancer A load balancer for acquiring database connections
	 * @param ExternalStoreAccess $extStoreAccess Access layer for external storage
	 * @param WANObjectCache $cache A cache manager for caching blobs. This can be the local
	 *        wiki's default instance even if $dbDomain refers to a different wiki, since
	 *        makeGlobalKey() is used to construct a key that allows cached blobs from the
	 *        same database to be re-used between wikis. For example, wiki A and wiki B will
	 *        use the same cache keys for blobs fetched from wiki C, regardless of the
	 *        wiki-specific default key space.
	 * @param bool|string $dbDomain The ID of the target wiki database. Use false for the local wiki.
	 */
	public function __construct(
		ILoadBalancer $dbLoadBalancer,
		ExternalStoreAccess $extStoreAccess,
		WANObjectCache $cache,
		$dbDomain = false
	) {
		$this->dbLoadBalancer = $dbLoadBalancer;
		$this->extStoreAccess = $extStoreAccess;
		$this->cache = $cache;
		$this->dbDomain = $dbDomain;
	}

	/**
	 * @return int Time for which blobs can be cached, in seconds
	 */
	public function getCacheExpiry() {
		return $this->cacheExpiry;
	}

	/**
	 * @param int $cacheExpiry Time for which blobs can be cached, in seconds
	 */
	public function setCacheExpiry( int $cacheExpiry ) {
		$this->cacheExpiry = $cacheExpiry;
	}

	/**
	 * @return bool Whether blobs should be compressed for storage
	 */
	public function getCompressBlobs() {
		return $this->compressBlobs;
	}

	/**
	 * @param bool $compressBlobs Whether blobs should be compressed for storage
	 */
	public function setCompressBlobs( $compressBlobs ) {
		$this->compressBlobs = $compressBlobs;
	}

	/**
	 * @return false|string The legacy encoding to assume for blobs that are not marked as utf8.
	 *         False means handling of legacy encoding is disabled, and utf8 assumed.
	 */
	public function getLegacyEncoding() {
		return $this->legacyEncoding;
	}

	/**
	 * Set the legacy encoding to assume for blobs that do not have the utf-8 flag set.
	 *
	 * @note The second parameter, Language $language, was removed in 1.34.
	 *
	 * @param string $legacyEncoding The legacy encoding to assume for blobs that are
	 *        not marked as utf8.
	 */
	public function setLegacyEncoding( string $legacyEncoding ) {
		$this->legacyEncoding = $legacyEncoding;
	}

	/**
	 * @return bool Whether to use the ExternalStore mechanism for storing blobs.
	 */
	public function getUseExternalStore() {
		return $this->useExternalStore;
	}

	/**
	 * @param bool $useExternalStore Whether to use the ExternalStore mechanism for storing blobs.
	 */
	public function setUseExternalStore( bool $useExternalStore ) {
		$this->useExternalStore = $useExternalStore;
	}

	/**
	 * @return ILoadBalancer
	 */
	private function getDBLoadBalancer() {
		return $this->dbLoadBalancer;
	}

	/**
	 * @param int $index A database index, like DB_PRIMARY or DB_REPLICA
	 *
	 * @return IDatabase
	 */
	private function getDBConnection( $index ) {
		$lb = $this->getDBLoadBalancer();
		return $lb->getConnection( $index, [], $this->dbDomain );
	}

	/**
	 * Stores an arbitrary blob of data and returns an address that can be used with
	 * getBlob() to retrieve the same blob of data,
	 *
	 * @param string $data
	 * @param array $hints An array of hints.
	 *
	 * @throws BlobAccessException
	 * @return string an address that can be used with getBlob() to retrieve the data.
	 */
	public function storeBlob( $data, $hints = [] ) {
		$flags = $this->compressData( $data );

		# Write to external storage if required
		if ( $this->useExternalStore ) {
			// Store and get the URL
			try {
				$data = $this->extStoreAccess->insert( $data, [ 'domain' => $this->dbDomain ] );
			} catch ( ExternalStoreException $e ) {
				throw new BlobAccessException( $e->getMessage(), 0, $e );
			}
			if ( !$data ) {
				throw new BlobAccessException( "Failed to store text to external storage" );
			}
			if ( $flags ) {
				return 'es:' . $data . '?flags=' . $flags;
			} else {
				return 'es:' . $data;
			}
		} else {
			$dbw = $this->getDBConnection( DB_PRIMARY );

			$dbw->newInsertQueryBuilder()
				->insertInto( 'text' )
				->row( [ 'old_text' => $data, 'old_flags' => $flags ] )
				->caller( __METHOD__ )->execute();

			$textId = $dbw->insertId();

			return self::makeAddressFromTextId( $textId );
		}
	}

	/**
	 * Retrieve a blob, given an address.
	 * Currently hardcoded to the 'text' table storage engine.
	 *
	 * MCR migration note: this replaced Revision::loadText
	 *
	 * @param string $blobAddress
	 * @param int $queryFlags
	 *
	 * @throws BlobAccessException
	 * @return string
	 */
	public function getBlob( $blobAddress, $queryFlags = 0 ) {
		Assert::parameterType( 'string', $blobAddress, '$blobAddress' );

		$error = null;
		$blob = $this->cache->getWithSetCallback(
			$this->getCacheKey( $blobAddress ),
			$this->getCacheTTL(),
			function ( $unused, &$ttl, &$setOpts ) use ( $blobAddress, $queryFlags, &$error ) {
				// Ignore $setOpts; blobs are immutable and negatives are not cached
				[ $result, $errors ] = $this->fetchBlobs( [ $blobAddress ], $queryFlags );
				// No negative caching; negative hits on text rows may be due to corrupted replica DBs
				$error = $errors[$blobAddress] ?? null;
				if ( $error ) {
					$ttl = WANObjectCache::TTL_UNCACHEABLE;
				}
				return $result[$blobAddress];
			},
			$this->getCacheOptions()
		);

		if ( $error ) {
			if ( $error[0] === 'badrevision' ) {
				throw new BadBlobException( $error[1] );
			} else {
				throw new BlobAccessException( $error[1] );
			}
		}

		Assert::postcondition( is_string( $blob ), 'Blob must not be null' );
		return $blob;
	}

	/**
	 * A batched version of BlobStore::getBlob.
	 *
	 * @param string[] $blobAddresses An array of blob addresses.
	 * @param int $queryFlags See IDBAccessObject.
	 * @throws BlobAccessException
	 * @return StatusValue A status with a map of blobAddress => binary blob data or null
	 *         if fetching the blob has failed. Fetch failures errors are the
	 *         warnings in the status object.
	 * @since 1.34
	 */
	public function getBlobBatch( $blobAddresses, $queryFlags = 0 ) {
		// FIXME: All caching has temporarily been removed in I94c6f9ba7b9caeeb due to T235188.
		//        Caching behavior should be restored by reverting I94c6f9ba7b9caeeb as soon as
		//        the root cause of T235188 has been resolved.

		[ $blobsByAddress, $errors ] = $this->fetchBlobs( $blobAddresses, $queryFlags );

		$blobsByAddress = array_map( static function ( $blob ) {
			return $blob === false ? null : $blob;
		}, $blobsByAddress );

		$result = StatusValue::newGood( $blobsByAddress );
		foreach ( $errors as $error ) {
			// @phan-suppress-next-line PhanParamTooFewUnpack
			$result->warning( ...$error );
		}
		return $result;
	}

	/**
	 * MCR migration note: this corresponded to Revision::fetchText
	 *
	 * @param string[] $blobAddresses
	 * @param int $queryFlags
	 *
	 * @throws BlobAccessException
	 * @return array [ $result, $errors ] A list with the following elements:
	 *   - The result: a map of blob addresses to successfully fetched blobs
	 *     or false if fetch failed
	 *   - Errors: a map of blob addresses to error information about the blob.
	 *     On success, the relevant key will be absent. Each error is a list of
	 *     parameters to be passed to StatusValue::warning().
	 */
	private function fetchBlobs( $blobAddresses, $queryFlags ) {
		$textIdToBlobAddress = [];
		$result = [];
		$errors = [];
		foreach ( $blobAddresses as $blobAddress ) {
			try {
				[ $schema, $id, $params ] = self::splitBlobAddress( $blobAddress );
			} catch ( InvalidArgumentException $ex ) {
				throw new BlobAccessException(
					$ex->getMessage() . '. Use findBadBlobs.php to remedy.',
					0,
					$ex
				);
			}

			if ( $schema === 'es' ) {
				if ( $params && isset( $params['flags'] ) ) {
					$blob = $this->expandBlob( $id, $params['flags'] . ',external', $blobAddress );
				} else {
					$blob = $this->expandBlob( $id, 'external', $blobAddress );
				}

				if ( $blob === false ) {
					$errors[$blobAddress] = [
						'internalerror',
						"Bad data in external store address $id. Use findBadBlobs.php to remedy."
					];
				}
				$result[$blobAddress] = $blob;
			} elseif ( $schema === 'bad' ) {
				// Database row was marked as "known bad"
				wfDebug(
					__METHOD__
					. ": loading known-bad content ($blobAddress), returning empty string"
				);
				$result[$blobAddress] = '';
				$errors[$blobAddress] = [
					'badrevision',
					'The content of this revision is missing or corrupted (bad schema)'
				];
			} elseif ( $schema === 'tt' ) {
				$textId = intval( $id );

				if ( $textId < 1 || $id !== (string)$textId ) {
					$errors[$blobAddress] = [
						'internalerror',
						"Bad blob address: $blobAddress. Use findBadBlobs.php to remedy."
					];
					$result[$blobAddress] = false;
				}

				$textIdToBlobAddress[$textId] = $blobAddress;
			} else {
				$errors[$blobAddress] = [
					'internalerror',
					"Unknown blob address schema: $schema. Use findBadBlobs.php to remedy."
				];
				$result[$blobAddress] = false;
			}
		}

		$textIds = array_keys( $textIdToBlobAddress );
		if ( !$textIds ) {
			return [ $result, $errors ];
		}
		// Callers doing updates will pass in READ_LATEST as usual. Since the text/blob tables
		// do not normally get rows changed around, set READ_LATEST_IMMUTABLE in those cases.
		$queryFlags |= DBAccessObjectUtils::hasFlags( $queryFlags, IDBAccessObject::READ_LATEST )
			? IDBAccessObject::READ_LATEST_IMMUTABLE
			: 0;
		[ $index, $options, $fallbackIndex, $fallbackOptions ] =
			self::getDBOptions( $queryFlags );
		// Text data is immutable; check replica DBs first.
		$dbConnection = $this->getDBConnection( $index );
		$rows = $dbConnection->newSelectQueryBuilder()
			->select( [ 'old_id', 'old_text', 'old_flags' ] )
			->from( 'text' )
			->where( [ 'old_id' => $textIds ] )
			->options( $options )
			->caller( __METHOD__ )->fetchResultSet();
		$numRows = $rows->numRows();

		// Fallback to DB_PRIMARY in some cases if not all the rows were found, using the appropriate
		// options, such as FOR UPDATE to avoid missing rows due to REPEATABLE-READ.
		if ( $numRows !== count( $textIds ) && $fallbackIndex !== null ) {
			$fetchedTextIds = [];
			foreach ( $rows as $row ) {
				$fetchedTextIds[] = $row->old_id;
			}
			$missingTextIds = array_diff( $textIds, $fetchedTextIds );
			$dbConnection = $this->getDBConnection( $fallbackIndex );
			$rowsFromFallback = $dbConnection->newSelectQueryBuilder()
				->select( [ 'old_id', 'old_text', 'old_flags' ] )
				->from( 'text' )
				->where( [ 'old_id' => $missingTextIds ] )
				->options( $fallbackOptions )
				->caller( __METHOD__ )->fetchResultSet();
			$appendIterator = new AppendIterator();
			$appendIterator->append( $rows );
			$appendIterator->append( $rowsFromFallback );
			$rows = $appendIterator;
		}

		foreach ( $rows as $row ) {
			$blobAddress = $textIdToBlobAddress[$row->old_id];
			$blob = false;
			if ( $row->old_text !== null ) {
				$blob = $this->expandBlob( $row->old_text, $row->old_flags, $blobAddress );
			}
			if ( $blob === false ) {
				$errors[$blobAddress] = [
					'internalerror',
					"Bad data in text row {$row->old_id}. Use findBadBlobs.php to remedy."
				];
			}
			$result[$blobAddress] = $blob;
		}

		// If we're still missing some of the rows, set errors for missing blobs.
		if ( count( $result ) !== count( $blobAddresses ) ) {
			foreach ( $blobAddresses as $blobAddress ) {
				if ( !isset( $result[$blobAddress ] ) ) {
					$errors[$blobAddress] = [
						'internalerror',
						"Unable to fetch blob at $blobAddress. Use findBadBlobs.php to remedy."
					];
					$result[$blobAddress] = false;
				}
			}
		}
		return [ $result, $errors ];
	}

	private static function getDBOptions( int $bitfield ): array {
		if ( DBAccessObjectUtils::hasFlags( $bitfield, IDBAccessObject::READ_LATEST_IMMUTABLE ) ) {
			$index = DB_REPLICA; // override READ_LATEST if set
			$fallbackIndex = DB_PRIMARY;
		} elseif ( DBAccessObjectUtils::hasFlags( $bitfield, IDBAccessObject::READ_LATEST ) ) {
			$index = DB_PRIMARY;
			$fallbackIndex = null;
		} else {
			$index = DB_REPLICA;
			$fallbackIndex = null;
		}

		$lockingOptions = [];
		if ( DBAccessObjectUtils::hasFlags( $bitfield, IDBAccessObject::READ_EXCLUSIVE ) ) {
			$lockingOptions[] = 'FOR UPDATE';
		} elseif ( DBAccessObjectUtils::hasFlags( $bitfield, IDBAccessObject::READ_LOCKING ) ) {
			$lockingOptions[] = 'LOCK IN SHARE MODE';
		}

		if ( $fallbackIndex !== null ) {
			$options = []; // locks on DB_REPLICA make no sense
			$fallbackOptions = $lockingOptions;
		} else {
			$options = $lockingOptions;
			$fallbackOptions = []; // no fallback
		}

		return [ $index, $options, $fallbackIndex, $fallbackOptions ];
	}

	/**
	 * Get a cache key for a given Blob address.
	 *
	 * The cache key is constructed in a way that allows cached blobs from the same database
	 * to be re-used between wikis. For example, wiki A and wiki B will use the same cache keys
	 * for blobs fetched from wiki C.
	 *
	 * @param string $blobAddress
	 * @return string
	 */
	private function getCacheKey( $blobAddress ) {
		return $this->cache->makeGlobalKey(
			'SqlBlobStore-blob',
			$this->dbLoadBalancer->resolveDomainID( $this->dbDomain ),
			$blobAddress
		);
	}

	/**
	 * Get the cache key options for a given Blob
	 *
	 * @return array<string,mixed>
	 */
	private function getCacheOptions() {
		return [
			'pcGroup' => self::TEXT_CACHE_GROUP,
			'pcTTL' => WANObjectCache::TTL_PROC_LONG,
			'segmentable' => true
		];
	}

	/**
	 * Expand a raw data blob according to the flags given.
	 *
	 * MCR migration note: this replaced Revision::getRevisionText
	 *
	 * @note direct use is deprecated, use getBlob() or SlotRecord::getContent() instead.
	 * @todo make this private, there should be no need to use this method outside this class.
	 *
	 * @param string $raw The raw blob data, to be processed according to $flags.
	 *        May be the blob itself, or the blob compressed, or just the address
	 *        of the actual blob, depending on $flags.
	 * @param string|string[] $flags Blob flags, such as 'external' or 'gzip'.
	 *   Note that not including 'utf-8' in $flags will cause the data to be decoded
	 *   according to the legacy encoding specified via setLegacyEncoding.
	 * @param string|null $blobAddress A blob address for use in the cache key. If not given,
	 *   caching is disabled.
	 *
	 * @return false|string The expanded blob or false on failure
	 * @throws BlobAccessException
	 */
	public function expandBlob( $raw, $flags, $blobAddress = null ) {
		if ( is_string( $flags ) ) {
			$flags = self::explodeFlags( $flags );
		}
		if ( in_array( 'error', $flags ) ) {
			throw new BadBlobException(
				"The content of this revision is missing or corrupted (error flag)"
			);
		}

		// Use external methods for external objects, text in table is URL-only then
		if ( in_array( 'external', $flags ) ) {
			$url = $raw;
			$parts = explode( '://', $url, 2 );
			if ( count( $parts ) == 1 || $parts[1] == '' ) {
				return false;
			}

			if ( $blobAddress ) {
				// The cached value should be decompressed, so handle that and return here.
				return $this->cache->getWithSetCallback(
					$this->getCacheKey( $blobAddress ),
					$this->getCacheTTL(),
					function () use ( $url, $flags, $blobAddress ) {
						// Ignore $setOpts; blobs are immutable and negatives are not cached
						$blob = $this->extStoreAccess
							->fetchFromURL( $url, [ 'domain' => $this->dbDomain ] );

						return $blob === false ? false : $this->decompressData( $blob, $flags, $blobAddress );
					},
					$this->getCacheOptions()
				);
			} else {
				$blob = $this->extStoreAccess->fetchFromURL( $url, [ 'domain' => $this->dbDomain ] );
				return $blob === false ? false : $this->decompressData( $blob, $flags, $blobAddress );
			}
		} else {
			return $this->decompressData( $raw, $flags, $blobAddress );
		}
	}

	/**
	 * If $wgCompressRevisions is enabled, we will compress data.
	 * The input string is modified in place.
	 * Return value is the flags field: contains 'gzip' if the
	 * data is compressed, and 'utf-8' if we're saving in UTF-8
	 * mode.
	 *
	 * MCR migration note: this replaced Revision::compressRevisionText
	 *
	 * @note direct use is deprecated!
	 * @todo make this private, there should be no need to use this method outside this class.
	 *
	 * @param string &$blob
	 *
	 * @return string
	 */
	public function compressData( &$blob ) {
		$blobFlags = [];

		// Revisions not marked as UTF-8 will have legacy decoding applied by decompressData().
		// XXX: if $this->legacyEncoding is not set, we could skip this. That would however be
		// risky, since $this->legacyEncoding being set in the future would lead to data corruption.
		$blobFlags[] = 'utf-8';

		if ( $this->compressBlobs ) {
			if ( function_exists( 'gzdeflate' ) ) {
				$deflated = gzdeflate( $blob );

				if ( $deflated === false ) {
					wfLogWarning( __METHOD__ . ': gzdeflate() failed' );
				} else {
					$blob = $deflated;
					$blobFlags[] = 'gzip';
				}
			} else {
				wfDebug( __METHOD__ . " -- no zlib support, not compressing" );
			}
		}
		return implode( ',', $blobFlags );
	}

	/**
	 * Re-converts revision text according to its flags.
	 *
	 * MCR migration note: this replaced Revision::decompressRevisionText
	 *
	 * @note direct use is deprecated, use getBlob() or SlotRecord::getContent() instead.
	 * @todo make this private, there should be no need to use this method outside this class.
	 *
	 * @param string $blob Blob in compressed/encoded form.
	 * @param array $blobFlags Compression flags, such as 'gzip'.
	 *   Note that not including 'utf-8' in $blobFlags will cause the data to be decoded
	 *   according to the legacy encoding specified via setLegacyEncoding.
	 * @param string|null $blobAddress Used for log message
	 *
	 * @return string|false Decompressed text, or false on failure
	 */
	public function decompressData( string $blob, array $blobFlags, ?string $blobAddress = null ) {
		if ( in_array( 'error', $blobFlags ) ) {
			// Error row, return false
			return false;
		}

		// Deal with optional compression of archived pages.
		// This can be done periodically via maintenance/compressOld.php, and
		// as pages are saved if $wgCompressRevisions is set.
		if ( in_array( 'gzip', $blobFlags ) ) {
			// Silence native warning in favour of more detailed warning (T380347)
			// phpcs:ignore Generic.PHP.NoSilencedErrors.Discouraged
			$blob = @gzinflate( $blob );
			if ( $blob === false ) {
				wfWarn( __METHOD__ . ': gzinflate() failed' .
					( $blobAddress ? ' (at blob address ' . $blobAddress . ')' : '' ) );
				return false;
			}
		}

		if ( in_array( 'object', $blobFlags ) ) {
			# Generic compressed storage
			$obj = HistoryBlobUtils::unserialize( $blob );
			if ( !$obj ) {
				// Invalid object
				return false;
			}
			$blob = $obj->getText();
		}

		// Needed to support old revisions from before MW 1.5.
		if ( $blob !== false && $this->legacyEncoding
			&& !in_array( 'utf-8', $blobFlags ) && !in_array( 'utf8', $blobFlags )
		) {
			// - Old revisions kept around in a legacy encoding?
			//   Upconvert on demand.
			// - "utf8" checked for compatibility with some broken
			//   conversion scripts 2008-12-30.
			// - Even with "//IGNORE" iconv can whine about illegal characters in
			//   *input* string. We just ignore those too.
			//   Ref https://bugs.php.net/bug.php?id=37166
			//   Ref https://phabricator.wikimedia.org/T18885
			//
			// phpcs:ignore Generic.PHP.NoSilencedErrors.Discouraged
			$blob = @iconv( $this->legacyEncoding, 'UTF-8//IGNORE', $blob );
		}

		return $blob;
	}

	/**
	 * Get the text cache TTL
	 *
	 * MCR migration note: this replaced Revision::getCacheTTL
	 *
	 * @return int
	 */
	private function getCacheTTL() {
		$cache = $this->cache;

		if ( $cache->getQoS( BagOStuff::ATTR_DURABILITY ) >= BagOStuff::QOS_DURABILITY_RDBMS ) {
			// Do not cache RDBMs blobs in...the RDBMs store
			$ttl = $cache::TTL_UNCACHEABLE;
		} else {
			$ttl = $this->cacheExpiry ?: $cache::TTL_UNCACHEABLE;
		}

		return $ttl;
	}

	/**
	 * Returns an ID corresponding to the old_id field in the text table, corresponding
	 * to the given $address.
	 *
	 * Currently, $address must start with 'tt:' followed by a decimal integer representing
	 * the old_id; if $address does not start with 'tt:', null is returned. However,
	 * the implementation may change to insert rows into the text table on the fly.
	 * This implies that this method cannot be static.
	 *
	 * @note This method exists for use with the text table based storage schema.
	 * It should not be assumed that is will function with all future kinds of content addresses.
	 *
	 * @deprecated since 1.31, so don't assume that all blob addresses refer to a row in the text
	 * table. This method should become private once the relevant refactoring in WikiPage is
	 * complete.
	 *
	 * @param string $address
	 *
	 * @return int|null
	 */
	public function getTextIdFromAddress( $address ) {
		[ $schema, $id, ] = self::splitBlobAddress( $address );

		if ( $schema !== 'tt' ) {
			return null;
		}

		$textId = intval( $id );

		if ( !$textId || $id !== (string)$textId ) {
			throw new InvalidArgumentException( "Malformed text_id: $id" );
		}

		return $textId;
	}

	/**
	 * Returns an address referring to content stored in the text table row with the given ID.
	 * The address schema for blobs stored in the text table is "tt:" followed by an integer
	 * that corresponds to a value of the old_id field.
	 *
	 * @internal
	 * @note This method should not be used by regular application logic. It is public so
	 *       maintenance scripts can use it for bulk operations on the text table.
	 *
	 * @param int $id
	 *
	 * @return string
	 */
	public static function makeAddressFromTextId( $id ) {
		return 'tt:' . $id;
	}

	/**
	 * Split a comma-separated old_flags value into its constituent parts
	 *
	 * @param string $flagsString
	 * @return array
	 */
	public static function explodeFlags( string $flagsString ) {
		return $flagsString === '' ? [] : explode( ',', $flagsString );
	}

	/**
	 * Splits a blob address into three parts: the schema, the ID, and parameters/flags.
	 *
	 * @since 1.33
	 *
	 * @param string $address
	 *
	 * @return array [ $schema, $id, $parameters ], with $parameters being an assoc array.
	 */
	public static function splitBlobAddress( $address ) {
		if ( !preg_match( '/^([-+.\w]+):([^\s?]+)(\?([^\s]*))?$/', $address, $m ) ) {
			throw new InvalidArgumentException( "Bad blob address: $address" );
		}

		$schema = strtolower( $m[1] );
		$id = $m[2];
		$parameters = wfCgiToArray( $m[4] ?? '' );

		return [ $schema, $id, $parameters ];
	}

	/** @inheritDoc */
	public function isReadOnly() {
		if ( $this->useExternalStore && $this->extStoreAccess->isReadOnly() ) {
			return true;
		}

		return ( $this->getDBLoadBalancer()->getReadOnlyReason() !== false );
	}
}
