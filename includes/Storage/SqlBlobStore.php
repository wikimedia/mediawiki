<?php
/**
 * Service for storing and loading data blobs representing revision content.
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
 * Attribution notice: when this file was created, much of its content was taken
 * from the Revision.php file as present in release 1.30. Refer to the history
 * of that file for original authorship.
 *
 * @file
 */

namespace MediaWiki\Storage;

use DBAccessObjectUtils;
use ExternalStore;
use IDBAccessObject;
use IExpiringStore;
use InvalidArgumentException;
use Language;
use MWException;
use WANObjectCache;
use Wikimedia\Assert\Assert;
use Wikimedia\Rdbms\Database;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\LoadBalancer;

/**
 * Service for storing and loading Content objects.
 *
 * @since 1.31
 *
 * @note This was written to act as a drop-in replacement for the corresponding
 *       static methods in Revision.
 */
class SqlBlobStore implements IDBAccessObject, BlobStore {

	// Note: the name has been taken unchanged from the Revision class.
	const TEXT_CACHE_GROUP = 'revisiontext:10';

	/**
	 * @var LoadBalancer
	 */
	private $dbLoadBalancer;

	/**
	 * @var WANObjectCache
	 */
	private $cache;

	/**
	 * @var bool|string Wiki ID
	 */
	private $wikiId;

	/**
	 * @var int
	 */
	private $cacheExpiry = 604800; // 7 days

	/**
	 * @var bool
	 */
	private $compressBlobs = false;

	/**
	 * @var bool|string
	 */
	private $legacyEncoding = false;

	/**
	 * @var Language|null
	 */
	private $legacyEncodingConversionLang = null;

	/**
	 * @var boolean
	 */
	private $useExternalStore = false;

	/**
	 * @param LoadBalancer $dbLoadBalancer A load balancer for acquiring database connections
	 * @param WANObjectCache $cache A cache manager for caching blobs
	 * @param bool|string $wikiId The ID of the target wiki database. Use false for the local wiki.
	 */
	public function __construct(
		LoadBalancer $dbLoadBalancer,
		WANObjectCache $cache,
		$wikiId = false
	) {
		$this->dbLoadBalancer = $dbLoadBalancer;
		$this->cache = $cache;
		$this->wikiId = $wikiId;
	}

	/**
	 * @return int time for which blobs can be cached, in seconds
	 */
	public function getCacheExpiry() {
		return $this->cacheExpiry;
	}

	/**
	 * @param int $cacheExpiry time for which blobs can be cached, in seconds
	 */
	public function setCacheExpiry( $cacheExpiry ) {
		Assert::parameterType( 'integer', $cacheExpiry, '$cacheExpiry' );

		$this->cacheExpiry = $cacheExpiry;
	}

	/**
	 * @return bool whether blobs should be compressed for storage
	 */
	public function getCompressBlobs() {
		return $this->compressBlobs;
	}

	/**
	 * @param bool $compressBlobs whether blobs should be compressed for storage
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
	 * @return Language|null The locale to use when decoding from a legacy encoding, or null
	 *         if handling of legacy encoding is disabled.
	 */
	public function getLegacyEncodingConversionLang() {
		return $this->legacyEncodingConversionLang;
	}

	/**
	 * @param string $legacyEncoding The legacy encoding to assume for blobs that are
	 *        not marked as utf8.
	 * @param Language $language The locale to use when decoding from a legacy encoding.
	 */
	public function setLegacyEncoding( $legacyEncoding, Language $language ) {
		Assert::parameterType( 'string', $legacyEncoding, '$legacyEncoding' );

		$this->legacyEncoding = $legacyEncoding;
		$this->legacyEncodingConversionLang = $language;
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
	public function setUseExternalStore( $useExternalStore ) {
		Assert::parameterType( 'boolean', $useExternalStore, '$useExternalStore' );

		$this->useExternalStore = $useExternalStore;
	}

	/**
	 * @return LoadBalancer
	 */
	private function getDBLoadBalancer() {
		return $this->dbLoadBalancer;
	}

	/**
	 * @param int $index A database index, like DB_MASTER or DB_REPLICA
	 *
	 * @return IDatabase
	 */
	private function getDBConnection( $index ) {
		$lb = $this->getDBLoadBalancer();
		return $lb->getConnection( $index, [], $this->wikiId );
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
		try {
			$flags = $this->compressData( $data );

			# Write to external storage if required
			if ( $this->useExternalStore ) {
				// Store and get the URL
				$data = ExternalStore::insertToDefault( $data );
				if ( !$data ) {
					throw new BlobAccessException( "Failed to store text to external storage" );
				}
				if ( $flags ) {
					$flags .= ',';
				}
				$flags .= 'external';

				// TODO: we could also return an address for the external store directly here.
				// That would mean bypassing the text table entirely when the external store is
				// used. We'll need to assess expected fallout before doing that.
			}

			$dbw = $this->getDBConnection( DB_MASTER );

			$old_id = $dbw->nextSequenceValue( 'text_old_id_seq' );
			$dbw->insert(
				'text',
				[
					'old_id' => $old_id,
					'old_text' => $data,
					'old_flags' => $flags,
				],
				__METHOD__
			);

			$textId = $dbw->insertId();

			return 'tt:' . $textId;
		} catch ( MWException $e ) {
			throw new BlobAccessException( $e->getMessage(), 0, $e );
		}
	}

	/**
	 * Retrieve a blob, given an address.
	 * Currently hardcoded to the 'text' table storage engine.
	 *
	 * MCR migration note: this replaces Revision::loadText
	 *
	 * @param string $blobAddress
	 * @param int $queryFlags
	 *
	 * @throws BlobAccessException
	 * @return string
	 */
	public function getBlob( $blobAddress, $queryFlags = 0 ) {
		Assert::parameterType( 'string', $blobAddress, '$blobAddress' );

		// No negative caching; negative hits on text rows may be due to corrupted replica DBs
		$blob = $this->cache->getWithSetCallback(
			// TODO: change key, since this is not necessarily revision text!
			$this->cache->makeKey( 'revisiontext', 'textid', $blobAddress ),
			$this->getCacheTTL(),
			function ( $unused, &$ttl, &$setOpts ) use ( $blobAddress, $queryFlags ) {
				list( $index ) = DBAccessObjectUtils::getDBOptions( $queryFlags );
				$setOpts += Database::getCacheSetOptions( $this->getDBConnection( $index ) );

				return $this->fetchBlob( $blobAddress, $queryFlags );
			},
			[ 'pcGroup' => self::TEXT_CACHE_GROUP, 'pcTTL' => IExpiringStore::TTL_PROC_LONG ]
		);

		if ( $blob === false ) {
			throw new BlobAccessException( 'Failed to load blob from address ' . $blobAddress );
		}

		return $blob;
	}

	/**
	 * MCR migration note: this corresponds to Revision::fetchText
	 *
	 * @param string $blobAddress
	 * @param int $queryFlags
	 *
	 * @throw BlobAccessException
	 * @return string|false
	 */
	private function fetchBlob( $blobAddress, $queryFlags ) {
		list( $schema, $id, ) = self::splitBlobAddress( $blobAddress );

		//TODO: MCR: also support 'ex' schema with ExternalStore URLs, plus flags encoded in the URL!
		if ( $schema === 'tt' ) {
			$textId = intval( $id );
		} else {
			// XXX: change to better exceptions! That makes migration more difficult, though.
			throw new BlobAccessException( "Unknown blob address schema: $schema" );
		}

		if ( !$textId || $id !== (string)$textId ) {
			// XXX: change to better exceptions! That makes migration more difficult, though.
			throw new BlobAccessException( "Bad blob address: $blobAddress" );
		}

		// Callers doing updates will pass in READ_LATEST as usual. Since the text/blob tables
		// do not normally get rows changed around, set READ_LATEST_IMMUTABLE in those cases.
		$queryFlags |= DBAccessObjectUtils::hasFlags( $queryFlags, self::READ_LATEST )
			? self::READ_LATEST_IMMUTABLE
			: 0;

		list( $index, $options, $fallbackIndex, $fallbackOptions ) =
			DBAccessObjectUtils::getDBOptions( $queryFlags );

		// Text data is immutable; check replica DBs first.
		$row = $this->getDBConnection( $index )->selectRow(
			'text',
			[ 'old_text', 'old_flags' ],
			[ 'old_id' => $textId ],
			__METHOD__,
			$options
		);

		// Fallback to DB_MASTER in some cases if the row was not found, using the appropriate
		// options, such as FOR UPDATE to avoid missing rows due to REPEATABLE-READ.
		if ( !$row && $fallbackIndex !== null ) {
			$row = $this->getDBConnection( $fallbackIndex )->selectRow(
				'text',
				[ 'old_text', 'old_flags' ],
				[ 'old_id' => $textId ],
				__METHOD__,
				$fallbackOptions
			);
		}

		if ( !$row ) {
			wfWarn( __METHOD__ . ": No text row with ID $textId." );
			return false;
		}

		$blob = $this->expandBlob( $row->old_text, $row->old_flags, $blobAddress );

		if ( $blob === false ) {
			wfWarn( __METHOD__ . ": Bad data in text row $textId." );
			return false;
		}

		return $blob;
	}

	/**
	 * Expand a raw data blob according to the flags given.
	 *
	 * MCR migration note: this replaces Revision::getRevisionText
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
	 * @param string|null $cacheKey May be used for caching if given
	 *
	 * @return false|string The expanded blob or false on failure
	 */
	public function expandBlob( $raw, $flags, $cacheKey = null ) {
		if ( is_string( $flags ) ) {
			$flags = explode( ',', $flags );
		}

		// Use external methods for external objects, text in table is URL-only then
		if ( in_array( 'external', $flags ) ) {
			$url = $raw;
			$parts = explode( '://', $url, 2 );
			if ( count( $parts ) == 1 || $parts[1] == '' ) {
				return false;
			}

			if ( $cacheKey && $this->wikiId === false ) {
				// Make use of the wiki-local revision text cache.
				// The cached value should be decompressed, so handle that and return here.
				// NOTE: we rely on $this->cache being the right cache for $this->wikiId!
				return $this->cache->getWithSetCallback(
					// TODO: change key, since this is not necessarily revision text!
					$this->cache->makeKey( 'revisiontext', 'textid', $cacheKey ),
					$this->getCacheTTL(),
					function () use ( $url, $flags ) {
						// No negative caching per BlobStore::getBlob()
						$blob = ExternalStore::fetchFromURL( $url, [ 'wiki' => $this->wikiId ] );

						return $this->decompressData( $blob, $flags );
					},
					[ 'pcGroup' => self::TEXT_CACHE_GROUP, 'pcTTL' => WANObjectCache::TTL_PROC_LONG ]
				);
			} else {
				$blob = ExternalStore::fetchFromURL( $url, [ 'wiki' => $this->wikiId ] );
				return $this->decompressData( $blob, $flags );
			}
		} else {
			return $this->decompressData( $raw, $flags );
		}
	}

	/**
	 * If $wgCompressRevisions is enabled, we will compress data.
	 * The input string is modified in place.
	 * Return value is the flags field: contains 'gzip' if the
	 * data is compressed, and 'utf-8' if we're saving in UTF-8
	 * mode.
	 *
	 * MCR migration note: this replaces Revision::compressRevisionText
	 *
	 * @note direct use is deprecated!
	 * @todo make this private, there should be no need to use this method outside this class.
	 *
	 * @param mixed &$blob Reference to a text
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
				wfDebug( __METHOD__ . " -- no zlib support, not compressing\n" );
			}
		}
		return implode( ',', $blobFlags );
	}

	/**
	 * Re-converts revision text according to its flags.
	 *
	 * MCR migration note: this replaces Revision::decompressRevisionText
	 *
	 * @note direct use is deprecated, use getBlob() or SlotRecord::getContent() instead.
	 * @todo make this private, there should be no need to use this method outside this class.
	 *
	 * @param mixed $blob Reference to a text
	 * @param array $blobFlags Compression flags, such as 'gzip'.
	 *   Note that not including 'utf-8' in $blobFlags will cause the data to be decoded
	 *   according to the legacy encoding specified via setLegacyEncoding.
	 *
	 * @return string|bool Decompressed text, or false on failure
	 */
	public function decompressData( $blob, array $blobFlags ) {
		if ( $blob === false ) {
			// Text failed to be fetched; nothing to do
			return false;
		}

		if ( in_array( 'error', $blobFlags ) ) {
			// Error row, return false
			return false;
		}

		if ( in_array( 'gzip', $blobFlags ) ) {
			# Deal with optional compression of archived pages.
			# This can be done periodically via maintenance/compressOld.php, and
			# as pages are saved if $wgCompressRevisions is set.
			$blob = gzinflate( $blob );

			if ( $blob === false ) {
				wfLogWarning( __METHOD__ . ': gzinflate() failed' );
				return false;
			}
		}

		if ( in_array( 'object', $blobFlags ) ) {
			# Generic compressed storage
			$obj = unserialize( $blob );
			if ( !is_object( $obj ) ) {
				// Invalid object
				return false;
			}
			$blob = $obj->getText();
		}

		// Needed to support old revisions left over from from the 1.4 / 1.5 migration.
		if ( $blob !== false && $this->legacyEncoding && $this->legacyEncodingConversionLang
			&& !in_array( 'utf-8', $blobFlags ) && !in_array( 'utf8', $blobFlags )
		) {
			# Old revisions kept around in a legacy encoding?
			# Upconvert on demand.
			# ("utf8" checked for compatibility with some broken
			#  conversion scripts 2008-12-30)
			$blob = $this->legacyEncodingConversionLang->iconv( $this->legacyEncoding, 'UTF-8', $blob );
		}

		return $blob;
	}

	/**
	 * Get the text cache TTL
	 *
	 * MCR migration note: this replaces Revision::getCacheTTL
	 *
	 * @return int
	 */
	private function getCacheTTL() {
		if ( $this->cache->getQoS( WANObjectCache::ATTR_EMULATION )
				<= WANObjectCache::QOS_EMULATION_SQL
		) {
			// Do not cache RDBMs blobs in...the RDBMs store
			$ttl = WANObjectCache::TTL_UNCACHEABLE;
		} else {
			$ttl = $this->cacheExpiry ?: WANObjectCache::TTL_UNCACHEABLE;
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
	 *
	 * @note This method exists for use with the text table based storage schema.
	 * It should not be assumed that is will function with all future kinds of content addresses.
	 *
	 * @deprecated since 1.31, so not assume that all blob addresses refer to a row in the text
	 * table. This method should become private once the relevant refactoring in WikiPage is
	 * complete.
	 *
	 * @param string $address
	 *
	 * @return int|null
	 */
	public function getTextIdFromAddress( $address ) {
		list( $schema, $id, ) = self::splitBlobAddress( $address );

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
	 * Splits a blob address into three parts: the schema, the ID, and parameters/flags.
	 *
	 * @param string $address
	 *
	 * @throws InvalidArgumentException
	 * @return array [ $schema, $id, $parameters ], with $parameters being an assoc array.
	 */
	private static function splitBlobAddress( $address ) {
		if ( !preg_match( '/^(\w+):(\w+)(\?(.*))?$/', $address, $m ) ) {
			throw new InvalidArgumentException( "Bad blob address: $address" );
		}

		$schema = strtolower( $m[1] );
		$id = $m[2];
		$parameters = isset( $m[4] ) ? wfCgiToArray( $m[4] ) : [];

		return [ $schema, $id, $parameters ];
	}

	public function isReadOnly() {
		if ( $this->useExternalStore && ExternalStore::defaultStoresAreReadOnly() ) {
			return true;
		}

		return ( $this->getDBLoadBalancer()->getReadOnlyReason() !== false );
	}
}
