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

use DBAccessObjectUtils;
use ExternalStore;
use IDBAccessObject;
use IExpiringStore;
use MediaWiki\MediaWikiServices;
use WANObjectCache;

/**
 * Service for storing and loading Content objects.
 *
 * @note This was written to act as a drop-in replacement for the corresponding
 *       static methods in Revision.
 */
class RevisionContentStore implements IDBAccessObject {

	const TEXT_CACHE_GROUP = 'revisiontext:10';

	/**
	 * @var WANObjectCache
	 */
	private $cache;

	/**
	 * RevisionBlobStore constructor.
	 *
	 * @param WANObjectCache $cache
	 */
	public function __construct( WANObjectCache $cache ) {
		$this->cache = $cache;
	}

	public function loadText( $textAddress, $flags ) {
		// No negative caching; negative hits on text rows may be due to corrupted replica DBs
		return $this->cache->getWithSetCallback(
			$this->cache->makeKey( 'revisiontext', 'textid', $textAddress ),
			self::getCacheTTL( $this->cache ),
			function () use ( $textAddress, $flags ) {
				return $this->fetchText( $textAddress, $flags );
			},
			[ 'pcGroup' => self::TEXT_CACHE_GROUP, 'pcTTL' => IExpiringStore::TTL_PROC_LONG ]
		);
	}

	private function fetchText( $textAddress, $flags ) {
		// Callers doing updates will pass in READ_LATEST as usual. Since the text/blob tables
		// do not normally get rows changed around, set READ_LATEST_IMMUTABLE in those cases.
		$flags |= DBAccessObjectUtils::hasFlags( $flags, self::READ_LATEST )
			? self::READ_LATEST_IMMUTABLE
			: 0;

		list( $index, $options, $fallbackIndex, $fallbackOptions ) =
			DBAccessObjectUtils::getDBOptions( $flags );

		// Text data is immutable; check replica DBs first.
		$row = wfGetDB( $index )->selectRow(
			'text',
			[ 'old_text', 'old_flags' ],
			[ 'old_id' => $textAddress ],
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
				[ 'old_id' => $textAddress ],
				__METHOD__,
				$fallbackOptions
			);
		}

		if ( !$row ) {
			wfDebugLog( 'RevisionBlobStore', "No text row with ID '$textAddress'." );
		}

		$text = $this->getRevisionText( $row );
		if ( $row && $text === false ) {
			wfDebugLog( 'RevisionBlobStore', "No blob for text row '$textAddress'." );
		}

		return is_string( $text ) ? $text : false;
	}

	/**
	 * Get revision text associated with an old or archive row
	 *
	 * Both the flags and the text field must be included. Including the old_id
	 * field will activate cache usage as long as the $wiki parameter is not set.
	 *
	 * @param object $row The text data row, as a raw object
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


	/**
	 * Fetch revision content if it's available to the specified audience.
	 * If the specified audience does not have the ability to view this
	 * revision, null will be returned.
	 *
	 * @param int $audience One of:
	 *   RevisionRecord::FOR_PUBLIC       to be displayed to all users
	 *   RevisionRecord::FOR_THIS_USER    to be displayed to $wgUser
	 *   RevisionRecord::RAW              get the text regardless of permissions
	 * @param User $user User object to check for, only if FOR_THIS_USER is passed
	 *   to the $audience parameter
	 * @since 1.21
	 * @return Content|null
	 */
	public function getContent( $audience = self::FOR_PUBLIC, User $user = null ) {
		if ( $audience == self::FOR_PUBLIC && $this->isDeleted( self::DELETED_TEXT ) ) {
			return null;
		} elseif ( $audience == self::FOR_THIS_USER && !$this->userCan( self::DELETED_TEXT, $user ) ) {
			return null;
		} else {
			return $this->getContentInternal();
		}
	}

	/**
	 * Get original serialized data (without checking view restrictions)
	 *
	 * @since 1.21
	 * @return string
	 */
	public function getSerializedData() {
		if ( $this->mText === null ) {
			// RevisionRecord is immutable. Load on demand.
			$this->mText = $this->loadText();
		}

		return $this->mText;
	}

	/**
	 * Gets the content object for the revision (or null on failure).
	 *
	 * Note that for mutable Content objects, each call to this method will return a
	 * fresh clone.
	 *
	 * @since 1.21
	 * @return Content|null The RevisionRecord's content, or null on failure.
	 */
	protected function getContentInternal() {
		if ( $this->mContent === null ) {
			$text = $this->getSerializedData();

			if ( $text !== null && $text !== false ) {
				// Unserialize content
				$handler = $this->getContentHandler();
				$format = $this->getContentFormat();

				$this->mContent = $handler->unserializeContent( $text, $format );
			}
		}

		// NOTE: copy() will return $this for immutable content objects
		return $this->mContent ? $this->mContent->copy() : null;
	}

	/**
	 * Returns the content model for this revision.
	 *
	 * If no content model was stored in the database, the default content model for the title is
	 * used to determine the content model to use. If no title is know, CONTENT_MODEL_WIKITEXT
	 * is used as a last resort.
	 *
	 * @return string The content model id associated with this revision,
	 *     see the CONTENT_MODEL_XXX constants.
	 */
	public function getContentModel() {
		if ( !$this->mContentModel ) {
			$title = $this->getTitle();
			if ( $title ) {
				$this->mContentModel = ContentHandler::getDefaultModelFor( $title );
			} else {
				$this->mContentModel = CONTENT_MODEL_WIKITEXT;
			}

			assert( !empty( $this->mContentModel ) );
		}

		return $this->mContentModel;
	}

	/**
	 * Returns the content format for this revision.
	 *
	 * If no content format was stored in the database, the default format for this
	 * revision's content model is returned.
	 *
	 * @return string The content format id associated with this revision,
	 *     see the CONTENT_FORMAT_XXX constants.
	 */
	public function getContentFormat() {
		if ( !$this->mContentFormat ) {
			$handler = $this->getContentHandler();
			$this->mContentFormat = $handler->getDefaultFormat();

			assert( !empty( $this->mContentFormat ) );
		}

		return $this->mContentFormat;
	}

	/**
	 * Returns the content handler appropriate for this revision's content model.
	 *
	 * @throws MWException
	 * @return ContentHandler
	 */
	public function getContentHandler() {
		if ( !$this->mContentHandler ) {
			$model = $this->getContentModel();
			$this->mContentHandler = ContentHandler::getForModelID( $model );

			$format = $this->getContentFormat();

			if ( !$this->mContentHandler->isSupportedFormat( $format ) ) {
				throw new MWException( "Oops, the content format $format is not supported for "
				                       . "this content model, $model" );
			}
		}

		return $this->mContentHandler;
	}


}
