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
 * @file
 */

namespace MediaWiki\Storage;

use DBAccessObjectUtils;
use ExternalStore;
use IDBAccessObject;
use IExpiringStore;
use Language;
use MWException;
use WANObjectCache;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\LoadBalancer;

/**
 * Service for storing and loading Content objects.
 *
 * @todo FIXME: make BlobStore an interface. Call this SqlBlobStore or something.
 * @todo FIXME: add method for storing blobs! Take code from RevisionStore::insertRevisionOn
 *
 * @since 1.31
 *
 * @note This was written to act as a drop-in replacement for the corresponding
 *       static methods in Revision.
 */
class BlobStore implements IDBAccessObject, BlobLookup {

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
	private $revisionCacheExpiry = 604800; // 7 days

	/**
	 * @var bool
	 */
	private $compressRevisions = false;

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
	 * @param LoadBalancer $dbLoadBalancer
	 * @param WANObjectCache $cache
	 * @param bool|string $wikiId
	 */
	public function __construct( LoadBalancer $dbLoadBalancer, WANObjectCache $cache, $wikiId = false ) {
		$this->dbLoadBalancer = $dbLoadBalancer;
		$this->cache = $cache;
		$this->wikiId = $wikiId;
	}

	/**
	 * @return int
	 */
	public function getRevisionCacheExpiry() {
		return $this->revisionCacheExpiry;
	}

	/**
	 * @param int $revisionCacheExpiry
	 */
	public function setRevisionCacheExpiry( $revisionCacheExpiry ) {
		$this->revisionCacheExpiry = $revisionCacheExpiry;
	}

	/**
	 * @return boolean
	 */
	public function getCompressRevisions() {
		return $this->compressRevisions;
	}

	/**
	 * @param boolean $compressRevisions
	 */
	public function setCompressRevisions( $compressRevisions ) {
		$this->compressRevisions = $compressRevisions;
	}

	/**
	 * @return false|string
	 */
	public function getLegacyEncoding() {
		return $this->legacyEncoding;
	}

	/**
	 * @return Language|null
	 */
	public function getLegacyEncodingConversionLang() {
		return $this->legacyEncodingConversionLang;
	}

	/**
	 * @param string $legacyEncoding
	 * @param Language $language
	 */
	public function setLegacyEncoding( $legacyEncoding, Language $language ) {
		$this->legacyEncoding = $legacyEncoding;
		$this->legacyEncodingConversionLang = $language;
	}

	/**
	 * @return boolean
	 */
	public function getUseExternalStore() {
		return $this->useExternalStore;
	}

	/**
	 * @param boolean $useExternalStore
	 */
	public function setUseExternalStore( $useExternalStore ) {
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
	 * @param string $data
	 * @param array $hints An array of hints. // FIXME: specify hint keys
	 *
	 * @return string an address that can be used with getBlob() to retrieve the data.
	 */
	public function storeBlob( $data, $hints = [] ) {
		$flags = $this->compressRevisionData( $data );

		# Write to external storage if required
		if ( $this->useExternalStore ) {
			// Store and get the URL
			$data = ExternalStore::insertToDefault( $data );
			if ( !$data ) {
				throw new MWException( "Unable to store text to external storage" );
			}
			if ( $flags ) {
				$flags .= ',';
			}
			$flags .= 'external';

			// TODO: we could also return an address for the external store directly here!
		}

		$dbw = $this->getDBConnection( DB_REPLICA );

		$old_id = $dbw->nextSequenceValue( 'text_old_id_seq' );
		$dbw->insert( 'text',
			[
				'old_id' => $old_id,
				'old_text' => $data,
				'old_flags' => $flags,
			], __METHOD__
		);

		$textId = $dbw->insertId();

		return 'tt:' . $textId;
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
	 * @return string|false
	 */
	public function getBlob( $blobAddress, $queryFlags = 0 ) {
		if ( is_int( $blobAddress ) ) {
			wfWarn( __METHOD__ . ' called with int argument: ' . $blobAddress );
			$blobAddress = "tt:$blobAddress";
		}

		// No negative caching; negative hits on text rows may be due to corrupted replica DBs
		return $this->cache->getWithSetCallback(
			$this->cache->makeKey( 'revisiontext', 'textid', $blobAddress ),
			$this->getCacheTTL(),
			function () use ( $blobAddress, $queryFlags ) {
				return $this->fetchBlob( $blobAddress, $queryFlags );
			},
			[ 'pcGroup' => self::TEXT_CACHE_GROUP, 'pcTTL' => IExpiringStore::TTL_PROC_LONG ]
		);
	}

	/**
	 * MCR migration note: this corresponds to Revision::fetchText
	 *
	 * @param string $blobAddress
	 * @param int $queryFlags
	 *
	 * @throws MWException
	 * @return string|false
	 */
	private function fetchBlob( $blobAddress, $queryFlags ) {
		list( $schema, $id, ) = self::splitBlobAddress( $blobAddress );

		//TODO: MCR: also support 'ex' schema with ExternalStore URLs, plus flags encoded in the URL!
		//TODO: MCR: also support 'ar' schema for content blobs in old style archive rows!
		if ( $schema === 'tt' ) {
			$textId = intval( $id );
		} else {
			// XXX: change to better exceptions! That makes migration more difficult, though.
			throw new MWException( "Unknown blob address schema: $schema" );
		}

		if ( !$textId ) {
			// XXX: change to better exceptions! That makes migration more difficult, though.
			throw new MWException( "Bad blob address: $blobAddress" );
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

		if ( !is_string( $row ) ) {
			wfDebugLog( 'RevisionBlobStore', "No text row with ID '$blobAddress'." );
			return false;
		}

		// XXX: we may want to use $blobAddress as the cache key, instead of $row['old_id']
		$blob = $this->expandBlob( $row['old_text'], $row['old_flags'], $row['old_id'] );

		if ( $row && $blob === false ) {
			wfDebugLog( 'RevisionBlobStore', "No blob for text row '$blobAddress'." );
		}

		return is_string( $blob ) ? $blob : false;
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

			if ( $cacheKey ) {
				// Make use of the wiki-local revision text cache.
				// The cached value should be decompressed, so handle that and return here.
				// NOTE: we rely on $this->cache being the right cache for $this->wikiId!
				return $this->cache->getWithSetCallback(
					$this->cache->makeKey( 'revisiontext', 'textid', $cacheKey ),
					$this->getCacheTTL(),
					function () use ( $url, $flags ) {
						// No negative caching per BlobStore::getBlob()
						$blob = ExternalStore::fetchFromURL( $url, [ 'wiki' => $this->wikiId ] );

						return $this->decompressRevisionData( $blob, $flags );
					},
					[ 'pcGroup' => self::TEXT_CACHE_GROUP, 'pcTTL' => WANObjectCache::TTL_PROC_LONG ]
				);
			} else {
				$blob = ExternalStore::fetchFromURL( $url, [ 'wiki' => $this->wikiId ] );
			}
		} else {
			$blob = $raw;
		}

		return $this->decompressRevisionData( $blob, $flags );
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
	public function compressRevisionData( &$blob ) {
		$blobFlags = [];

		# Revisions not marked this way will be converted
		# on load if $wgLegacyCharset is set in the future.
		$blobFlags[] = 'utf-8';

		if ( $this->compressRevisions ) {
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
	 * @param array $blobFlags Compression flags
	 *
	 * @return string|bool Decompressed text, or false on failure
	 */
	public function decompressRevisionData( $blob, $blobFlags ) {
		if ( $blob === false ) {
			// Text failed to be fetched; nothing to do
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

		// XXX: do we still need this? This is for compat with MW 1.4...
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
		if ( $this->cache->getQoS( WANObjectCache::ATTR_EMULATION ) <= WANObjectCache::QOS_EMULATION_SQL ) {
			// Do not cache RDBMs blobs in...the RDBMs store
			$ttl = WANObjectCache::TTL_UNCACHEABLE;
		} else {
			$ttl = $this->revisionCacheExpiry ?: WANObjectCache::TTL_UNCACHEABLE;
		}

		return $ttl;
	}

	/**
	 * Splits a blob address into three parts: the schema, the ID, and flags.
	 *
	 * @todo make this private, there should be no need to use this method outside this class.
	 *
	 * @param $address
	 *
	 * @throws MWException
	 * @return array [ $schema, $id, $flags ]
	 */
	public static function splitBlobAddress( $address ) {
		if ( !preg_match( '^(\w+):(\w+)(\?(.*))$', $address, $m ) ) {
			throw new MWException( "Bad blob address: $address" );
		}

		$schema = strtolower( $m[1] );
		$id = $m[2];
		$flags = explode( '&', $m[4] );

		return [ $schema, $id, $flags ];
	}

}
