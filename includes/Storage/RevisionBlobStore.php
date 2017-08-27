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

use ExternalStore;
use IDBAccessObject;
use MediaWiki\MediaWikiServices;
use WANObjectCache;

/**
 * Service for storing and loading data blobs representing revision content.
 *
 * @note This was written to act as a drop-in replacement for the corresponding
 *       static methods in Revision.
 */
class RevisionBlobStore implements IDBAccessObject {

	/**
	 * Get revision text associated with an old or archive row
	 *
	 * Both the flags and the text field must be included. Including the old_id
	 * field will activate cache usage as long as the $wiki parameter is not set.
	 *
	 * @param stdClass $row The text data
	 * @param string $prefix Table prefix (default 'old_')
	 * @param string|bool $wiki The name of the wiki to load the revision text from
	 *   (same as the the wiki $row was loaded from) or false to indicate the local
	 *   wiki (this is the default). Otherwise, it must be a symbolic wiki database
	 *   identifier as understood by the LoadBalancer class.
	 * @return string|false Text the text requested or false on failure
	 */
	public function getRevisionText( $row, $prefix = 'old_', $wiki = false ) {
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

		// Use external methods for external objects, text in table is URL-only then
		if ( in_array( 'external', $flags ) ) {
			$url = $text;
			$parts = explode( '://', $url, 2 );
			if ( count( $parts ) == 1 || $parts[1] == '' ) {
				return false;
			}

			if ( isset( $row->old_id ) && $wiki === false ) {
				// Make use of the wiki-local revision text cache
				$cache = MediaWikiServices::getInstance()->getMainWANObjectCache();
				// The cached value should be decompressed, so handle that and return here
				return $cache->getWithSetCallback(
					$cache->makeKey( 'revisiontext', 'textid', $row->old_id ),
					self::getCacheTTL( $cache ),
					function () use ( $url, $wiki, $flags ) {
						// No negative caching per Revision::loadText()
						$text = ExternalStore::fetchFromURL( $url, [ 'wiki' => $wiki ] );

						return self::decompressRevisionText( $text, $flags );
					},
					[ 'pcGroup' => self::TEXT_CACHE_GROUP, 'pcTTL' => $cache::TTL_PROC_LONG ]
				);
			} else {
				$text = ExternalStore::fetchFromURL( $url, [ 'wiki' => $wiki ] );
			}
		}

		return self::decompressRevisionText( $text, $flags );
	}

	/**
	 * If $wgCompressRevisions is enabled, we will compress data.
	 * The input string is modified in place.
	 * Return value is the flags field: contains 'gzip' if the
	 * data is compressed, and 'utf-8' if we're saving in UTF-8
	 * mode.
	 *
	 * @param mixed &$text Reference to a text
	 * @return string
	 */
	public function compressRevisionText( &$text ) {
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
	 * @param mixed $text Reference to a text
	 * @param array $flags Compression flags
	 * @return string|bool Decompressed text, or false on failure
	 */
	public function decompressRevisionText( $text, $flags ) {
		global $wgLegacyEncoding, $wgContLang;

		if ( $text === false ) {
			// Text failed to be fetched; nothing to do
			return false;
		}

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

		if ( $text !== false && $wgLegacyEncoding
			&& !in_array( 'utf-8', $flags ) && !in_array( 'utf8', $flags )
		) {
			# Old revisions kept around in a legacy encoding?
			# Upconvert on demand.
			# ("utf8" checked for compatibility with some broken
			#  conversion scripts 2008-12-30)
			$text = $wgContLang->iconv( $wgLegacyEncoding, 'UTF-8', $text );
		}

		return $text;
	}

	/**
	 * Get the text cache TTL
	 *
	 * @param WANObjectCache $cache
	 * @return int
	 */
	private function getCacheTTL( WANObjectCache $cache ) {
		global $wgRevisionCacheExpiry;

		if ( $cache->getQoS( $cache::ATTR_EMULATION ) <= $cache::QOS_EMULATION_SQL ) {
			// Do not cache RDBMs blobs in...the RDBMs store
			$ttl = $cache::TTL_UNCACHEABLE;
		} else {
			$ttl = $wgRevisionCacheExpiry ?: $cache::TTL_UNCACHEABLE;
		}

		return $ttl;
	}

}
