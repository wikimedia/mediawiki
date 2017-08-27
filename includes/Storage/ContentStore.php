<?php

namespace MediaWiki\Storage;

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

use Content;
use ContentHandler;
use DBAccessObjectUtils;
use ExternalStore;
use IDBAccessObject;
use IExpiringStore;
use MWException;
use WANObjectCache;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\LoadBalancer;

/**
 * Service for storing and loading Content objects.
 *
 * @since 1.30
 *
 * @note This was written to act as a drop-in replacement for the corresponding
 *       static methods in Revision.
 */
class ContentStore implements IDBAccessObject, BlobLookup {

	const TEXT_CACHE_GROUP = 'revisiontext:10';

	/**
	 * @var WANObjectCache
	 */
	private $cache;

	/**
	 * @var bool|string Wiki ID
	 */
	private $wikiId;

	/**
	 * RevisionBlobStore constructor.
	 *
	 * @param WANObjectCache $cache
	 * @param bool|string $wikiId
	 */
	public function __construct( WANObjectCache $cache, $wikiId = false ) {
		$this->cache = $cache;
		$this->wikiId = $wikiId;
	}

	/**
	 * @return LoadBalancer
	 */
	private function getDBLoadBalancer() {
		return wfGetLB( $this->wikiId ); // FIXME: inject!
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
	 * Retrieve a blob, given an address.
	 * Currently hardcoded to the 'text' table storage engine.
	 *
	 * MCR migration note: this replaces Revision::loadText
	 *
	 * @param string|int $blobAddress
	 * @param int $queryFlags
	 * @return string|false
	 */
	public function getBlob( $blobAddress, $queryFlags = 0 ) {
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
	 *
	 * MCR migration note: this corresponds to Revision::fetchText
	 *
	 * @param string|int $blobAddress
	 * @param int $queryFlags
	 *
	 * @throws MWException
	 * @return string|false
	 */
	private function fetchBlob( $blobAddress, $queryFlags ) {
		$textId = intval( $blobAddress );
		if ( !$textId ) {
			list( $schema, $id, ) = self::splitBlobAddress( $blobAddress );
			$textId = intval( $id );

			if ( $schema !== 'tt' ) {
				throw new MWException( "Unknown blob address schema: $schema" );
			}

			//TODO: support 'ex' schema with ExternalStore urls, plus flags!
		}

		if ( !$textId ) {
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

		// Fallback to DB_MASTER in some cases if the row was not found
		if ( !$row && $fallbackIndex !== null ) {
			// Use FOR UPDATE if it was used to fetch this revision. This avoids missing the row
			// due to REPEATABLE-READ. Also fallback to the master if READ_LATEST is provided.
			$row = $this->getDBConnection( $fallbackIndex )->selectRow(
				'text',
				[ 'old_text', 'old_flags' ],
				[ 'old_id' => $textId ],
				__METHOD__,
				$fallbackOptions
			);
		}

		if ( !$row ) {
			wfDebugLog( 'RevisionBlobStore', "No text row with ID '$blobAddress'." );
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

			// XXX: why does $this->wikiId have to be false?
			if ( $cacheKey && $this->wikiId === false ) {
				// Make use of the wiki-local revision text cache
				// The cached value should be decompressed, so handle that and return here
				return $this->cache->getWithSetCallback(
					$this->cache->makeKey( 'revisiontext', 'textid', $cacheKey ),
					$this->getCacheTTL( $cache ),
					function () use ( $url, $flags ) {
						// No negative caching per Revision::loadText()
						$blob = ExternalStore::fetchFromURL( $url, [ 'wiki' => $this->wikiId ] );

						return $this->decompressRevisionData( $blob, $flags );
					},
					[ 'pcGroup' => self::TEXT_CACHE_GROUP, 'pcTTL' => $cache::TTL_PROC_LONG ]
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
	 * @deprecated since 1.30. Direct usage is discouraged, use expandBlob() instead.
	 *
	 * @param mixed &$blob Reference to a text
	 * @return string
	 */
	public function compressRevisionData( &$blob ) {
		global $wgCompressRevisions; // FIXME: inject!
		$blobFlags = [];

		# Revisions not marked this way will be converted
		# on load if $wgLegacyCharset is set in the future.
		$blobFlags[] = 'utf-8';

		if ( $wgCompressRevisions ) {
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
	 * @deprecated since 1.30. Direct usage is discouraged, use expandBlob() instead.
	 *
	 * @param mixed $blob Reference to a text
	 * @param array $blobFlags Compression flags
	 * @return string|bool Decompressed text, or false on failure
	 */
	public function decompressRevisionData( $blob, $blobFlags ) {
		global $wgLegacyEncoding, $wgContLang; // FIXME: inject!

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

		if ( $blob !== false && $wgLegacyEncoding
			&& !in_array( 'utf-8', $blobFlags ) && !in_array( 'utf8', $blobFlags )
		) {
			# Old revisions kept around in a legacy encoding?
			# Upconvert on demand.
			# ("utf8" checked for compatibility with some broken
			#  conversion scripts 2008-12-30)
			$blob = $wgContLang->iconv( $wgLegacyEncoding, 'UTF-8', $blob );
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
		global $wgRevisionCacheExpiry; // FIXME: inject!

		if ( $this->cache->getQoS( WANObjectCache::ATTR_EMULATION ) <= WANObjectCache::QOS_EMULATION_SQL ) {
			// Do not cache RDBMs blobs in...the RDBMs store
			$ttl = WANObjectCache::TTL_UNCACHEABLE;
		} else {
			$ttl = $wgRevisionCacheExpiry ?: WANObjectCache::TTL_UNCACHEABLE;
		}

		return $ttl;
	}

	/**
	 * Loads a Content object given a SlotRecord object.
	 *
	 * This method does not call $slot->getContent(), and may be used as a callback
	 * called by $slot->getContent().
	 *
	 * MCR migration note: this roughly corresponds to Revision::getContentInternal
	 *
	 * @param SlotRecord $slot
	 *
	 * @throws MWException
	 * @throws \MWUnknownContentModelException
	 * @return Content
	 */
	public function loadSlotContent( SlotRecord $slot ) {
		if ( $slot->hasField( 'blob' ) ) {
			$data = $slot->getStringField( 'blob' );

			$flags = $slot->hasField( 'blob_flags' )
				? $slot->getStringField( 'blob_flags' )
				: '';

			$data = $this->expandBlob( $data, $flags, $slot->getAddress() );
		} else {
			$data = $this->getBlob( $slot->getAddress() );
		}

		// Unserialize content
		$handler = ContentHandler::getForModelID( $slot->getModel() );

		// NOTE: we probably want to always use the default format in the future,
		// or leave it to $handler->unserializeContent to auto-detect.
		$format = $slot->hasField( 'cont_format' )
			? $slot->getStringField( 'cont_format' )
			: $handler->getDefaultFormat();

		$content = $handler->unserializeContent( $data, $format );
		return $content;
	}

	/**
	 * Splits a blob address into three parts: the schema, the ID, and flags.
	 *
	 * @todo XXX: Move this to SlotRecord? RevisionContent?
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
