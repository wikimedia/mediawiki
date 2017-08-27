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
use MediaWiki\MediaWikiServices;
use MWException;
use WANObjectCache;

/**
 * Service for storing and loading Content objects.
 *
 * @note This was written to act as a drop-in replacement for the corresponding
 *       static methods in Revision.
 */
class RevisionContentStore implements IDBAccessObject { // FIXME: implement nice narrow interfaces

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
	 * @param string|int $blobAddress
	 * @param int $queryFlags
	 * @return string|false
	 */
	public function getBlob( $blobAddress, $queryFlags = 0 ) {
		// No negative caching; negative hits on text rows may be due to corrupted replica DBs
		return $this->cache->getWithSetCallback(
			$this->cache->makeKey( 'revisiontext', 'textid', $blobAddress ),
			$this->getCacheTTL( $this->cache ),
			function () use ( $blobAddress, $queryFlags ) {
				return $this->fetchBlob( $blobAddress, $queryFlags );
			},
			[ 'pcGroup' => self::TEXT_CACHE_GROUP, 'pcTTL' => IExpiringStore::TTL_PROC_LONG ]
		);
	}

	private function fetchBlob( $blobAddress, $queryFlags ) {
		// FIXME: strip address prefix?!

		// Callers doing updates will pass in READ_LATEST as usual. Since the text/blob tables
		// do not normally get rows changed around, set READ_LATEST_IMMUTABLE in those cases.
		$queryFlags |= DBAccessObjectUtils::hasFlags( $queryFlags, self::READ_LATEST )
			? self::READ_LATEST_IMMUTABLE
			: 0;

		list( $index, $options, $fallbackIndex, $fallbackOptions ) =
			DBAccessObjectUtils::getDBOptions( $queryFlags );

		// Text data is immutable; check replica DBs first.
		$row = wfGetDB( $index )->selectRow(
			'text',
			[ 'old_text', 'old_flags' ],
			[ 'old_id' => $blobAddress ],
			__METHOD__,
			$options
		);

		// Fallback to DB_MASTER in some cases if the row was not found
		if ( !$row && $fallbackIndex !== null ) {
			// Use FOR UPDATE if it was used to fetch this revision. This avoids missing the row
			// due to REPEATABLE-READ. Also fallback to the master if READ_LATEST is provided.
			$row = wfGetDB( $fallbackIndex )->selectRow(
				'text',
				[ 'old_text', 'old_flags' ],
				[ 'old_id' => $blobAddress ],
				__METHOD__,
				$fallbackOptions
			);
		}

		if ( !$row ) {
			wfDebugLog( 'RevisionBlobStore', "No text row with ID '$blobAddress'." );
		}

		$blob = $this->getBlobFromRow( $row, 'old_text', 'old_flags', 'old_id' );
		if ( $row && $blob === false ) {
			wfDebugLog( 'RevisionBlobStore', "No blob for text row '$blobAddress'." );
		}

		return is_string( $blob ) ? $blob : false;
	}

	/**
	 * Get revision text associated with an old or archive row
	 *
	 * Both the flags and the text field must be included. Including the old_id
	 * field will activate cache usage as long as the $wiki parameter is not set.
	 *
	 * @param object $row The text or content row, as a raw object
	 *
	 * @param string $blobField
	 * @param string $flagsField
	 * @param string|null $addressField
	 *
	 * @return false|string Text the text requested or false on failure
	 */
	public function getBlobFromRow( $row, $blobField, $flagsField, $addressField = null ) {

		if ( isset( $row->$flagsField ) ) {
			$blobFlags = explode( ',', $row->$flagsField );
		} else {
			$blobFlags = [];
		}

		if ( isset( $row->$blobField ) ) {
			$blob = $row->$blobField;
		} else {
			return false;
		}

		// Use external methods for external objects, text in table is URL-only then
		if ( in_array( 'external', $blobFlags ) ) {
			$url = $blob;
			$parts = explode( '://', $url, 2 );
			if ( count( $parts ) == 1 || $parts[1] == '' ) {
				return false;
			}

			// FIXME: content of $addressField may or may not be prefixed!
			// FIXME: take flags from address?
			if ( $addressField && isset( $row->$addressField ) && $this->wikiId === false ) {
				// Make use of the wiki-local revision text cache
				$cache = MediaWikiServices::getInstance()->getMainWANObjectCache();
				// The cached value should be decompressed, so handle that and return here
				return $cache->getWithSetCallback(
					$cache->makeKey( 'revisiontext', 'textid', $row->$addressField ),
					$this->getCacheTTL( $cache ),
					function () use ( $url, $blobFlags ) {
						// No negative caching per Revision::loadText()
						$blob = ExternalStore::fetchFromURL( $url, [ 'wiki' => $this->wikiId ] );

						return $this->decompressRevisionData( $blob, $blobFlags );
					},
					[ 'pcGroup' => self::TEXT_CACHE_GROUP, 'pcTTL' => $cache::TTL_PROC_LONG ]
				);
			} else {
				$blob = ExternalStore::fetchFromURL( $url, [ 'wiki' => $this->wikiId ] );
			}
		}

		return $this->decompressRevisionData( $blob, $blobFlags );
	}

	/**
	 * If $wgCompressRevisions is enabled, we will compress data.
	 * The input string is modified in place.
	 * Return value is the flags field: contains 'gzip' if the
	 * data is compressed, and 'utf-8' if we're saving in UTF-8
	 * mode.
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
	 * Re-converts revision text according to it's flags.
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
	 * @param WANObjectCache $cache
	 * @return int
	 */
	private function getCacheTTL( WANObjectCache $cache ) {
		global $wgRevisionCacheExpiry; // FIXME: inject!

		if ( $cache->getQoS( $cache::ATTR_EMULATION ) <= $cache::QOS_EMULATION_SQL ) {
			// Do not cache RDBMs blobs in...the RDBMs store
			$ttl = $cache::TTL_UNCACHEABLE;
		} else {
			$ttl = $wgRevisionCacheExpiry ?: $cache::TTL_UNCACHEABLE;
		}

		return $ttl;
	}

	/**
	 * Loads a Content object given a SlotRecord object.
	 *
	 * This method does not call $slot->getContent(), and may be used as a callback
	 * called by $slot->getContent().
	 *
	 * @since 1.31
	 *
	 * @param SlotRecord $slot
	 *
	 * @throws MWException
	 * @throws \MWUnknownContentModelException
	 * @return Content
	 */
	public function loadSlotContent( SlotRecord $slot ) {
		if ( $slot->hasField( 'data' ) ) {
			$data = $slot->getStringField( 'data' );
		} else {
			$flags = $slot->hasField( 'data_flags' )
				? $slot->getStringField( 'data_flags' )
				: '';

			// FIXME: resolveBlobFlags (external, compress, etc)
			$data = $this->resolveBlobFlags( $slot->getAddress(), $flags );
		}

		// Unserialize content
		$handler = ContentHandler::getForModelID( $slot->getModel() );
		$format = $slot->hasField( 'cont_format' )
			? $slot->getStringField( 'cont_format' )
			: $handler->getDefaultFormat();

		$content = $handler->unserializeContent( $data, $format );
		return $content;
	}

	/**
	 * Get the base 36 SHA-1 value for a string of text
	 * @param string $blob
	 * @return string
	 */
	public static function base36Sha1( $blob ) {
		return \Wikimedia\base_convert( sha1( $blob ), 16, 36, 31 );
	}

}
