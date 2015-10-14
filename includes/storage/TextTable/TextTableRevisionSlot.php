<?php

namespace MediaWiki\Storage\TextTable;

use Content;
use ContentHandler;
use ExternalStore;
use IDBAccessObject;
use MediaWiki\Storage\RevisionContentException;
use MediaWiki\Storage\RevisionSlot;
use Revision;
use Title;

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
 * @since 1.27
 *
 * @file
 * @ingroup Storage
 *
 * @author Daniel Kinzler
 */

/**
 * A RevisionSlot implementation based on the `revision` and `text` tables.
 */
class TextTableRevisionSlot implements RevisionSlot {

	/**
	 * @var Title
	 */
	private $title;

	/**
	 * @var object
	 */
	private $revisionRow;

	/**
	 * @var object|null
	 */
	private $textRow;

	/**
	 * @var int
	 */
	private $queryFlags;

	/**
	 * @var string
	 */
	private $textFieldPrefix;

	/**
	 * @var string
	 */
	private $slotName;

	/**
	 * @var ContentHandler|null
	 */
	private $contentHandler = null;

	/**
	 * @var Content|null
	 */
	private $content = null;

	/**
	 * @param Title $title
	 * @param object|array $revisionRow
	 * @param object|array|null $textRow
	 * @param int $queryFlags
	 * @param string $textFieldPrefix
	 * @param string $slotName
	 */
	public function __construct( Title $title, $revisionRow, $textRow = null, $queryFlags = 0, $textFieldPrefix = 'old_', $slotName = 'main' ) {
		$this->title = $title;
		$this->revisionRow = (object)$revisionRow;
		$this->textRow = ( $textRow === null ? null : (object)$textRow );
		$this->queryFlags = $queryFlags;
		$this->textFieldPrefix = $textFieldPrefix;
		$this->slotName = $slotName;
	}

	/**
	 * @return Title
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * @return string
	 */
	public function getContentModel() {
		if ( !$this->revisionRow->rev_content_model ) {
			$title = $this->getTitle();
			if ( $title ) {
				$this->mContentModel = ContentHandler::getDefaultModelFor( $title );
			} else {
				$this->mContentModel = CONTENT_MODEL_WIKITEXT;
			}

			assert( !empty( $this->mContentModel ) );
		}

		return $this->revisionRow->rev_content_model;
	}

	/**
	 * @throws RevisionContentException
	 * @return string
	 */
	private function getContentFormat() {
		if ( empty( $this->revisionRow->rev_content_format ) ) {
			$handler = $this->getContentHandler();
			$this->revisionRow->rev_content_format = $handler->getDefaultFormat();
		}

		return $this->revisionRow->rev_content_format;
	}

	/**
	 * @return string
	 */
	public function getTouched() {
		return $this->revisionRow->rev_timestamp;
	}

	/**
	 * @return int
	 */
	public function getPageId() {
		return $this->revisionRow->rev_page;
	}

	/**
	 * @return int
	 */
	public function getRevisionId() {
		return $this->revisionRow->rev_id;
	}

	/**
	 * @return int
	 */
	public function getTextId() {
		return $this->revisionRow->rev_text_id;
	}

	/**
	 * This is not a deleted slot, so no restrictions apply.
	 *
	 * @return null
	 */
	public function getReadRestrictions() {
		$bitfield = $this->revisionRow->rev_deleted;

		if ( $bitfield & Revision::DELETED_TEXT ) { // aspect is deleted
			//FIXME: move the bitfield constants from Revision
			if ( $bitfield & Revision::DELETED_RESTRICTED ) {
				$permissions = array( 'suppressrevision', 'viewsuppressed' );
			} else {
				$permissions = array( 'deletedtext' );
			}

			return $permissions;
		} else {
			return null;
		}
	}

	/**
	 * Returns the content handler appropriate for this revision's content model.
	 *
	 * @throws RevisionContentException
	 * @return ContentHandler
	 */
	private function getContentHandler() {
		if ( !$this->contentHandler ) {
			$model = $this->getContentModel();
			$this->contentHandler = ContentHandler::getForModelID( $model );

			$format = $this->getContentFormat();

			if ( !$this->contentHandler->isSupportedFormat( $format ) ) {
				throw new RevisionContentException( "Oops, the content format $format is not supported for "
					. "this content model, $model", $this->getPageId(), $this->getRevisionId(), $this->getSlotName() );
			}
		}

		return $this->contentHandler;
	}

	/**
	 * @throws RevisionContentException
	 * @return Content
	 */
	public function getContent() {
		if ( $this->content === null )  {
			$blob = $this->loadBlob( );

			// Unserialize content
			$handler = $this->getContentHandler();
			$format = $this->getContentFormat();

			$this->content = $handler->unserializeContent( $blob, $format );
			return $this->content;
		} else {
			return $this->content->copy();
		}
	}

	/**
	 * @return string
	 */
	public function getSlotName() {
		return $this->slotName;
	}


	/**
	 * Lazy-load the revision's text.
	 * Currently hardcoded to the 'text' table storage engine.
	 *
	 * @todo factor this out into a blob storage layer
	 * @todo remove access to global state
	 *
	 * @return string|bool The revision's text, or false on failure
	 */
	private function loadBlob() {
		// Caching may be beneficial for massive use of external storage
		global $wgRevisionCacheExpiry, $wgMemc;

		$textId = $this->getTextId();
		$key = wfMemcKey( 'revisiontext', 'textid', $textId );
		if ( $wgRevisionCacheExpiry ) {
			$text = $wgMemc->get( $key );
			if ( is_string( $text ) ) {
				wfDebug( __METHOD__ . ": got id $textId from cache\n" );
				return $text;
			}
		}

		// If we kept data for lazy extraction, use it now...
		if ( $this->textRow !== null ) {
			$row = $this->textRow;
			$this->textRow = null;
		} else {
			$row = null;
		}

		if ( !$row ) {
			// Text data is immutable; check slaves first.
			$dbr = wfGetDB( DB_SLAVE );
			$row = $dbr->selectRow( 'text',
				array( 'old_text', 'old_flags' ),
				array( 'old_id' => $textId ),
				__METHOD__ );
		}

		// Fallback to the master in case of slave lag. Also use FOR UPDATE if it was
		// used to fetch this revision to avoid missing the row due to REPEATABLE-READ.
		$forUpdate = ( $this->queryFlags & IDBAccessObject::READ_LOCKING == IDBAccessObject::READ_LOCKING );
		if ( !$row && ( $forUpdate || wfGetLB()->getServerCount() > 1 ) ) {
			$dbw = wfGetDB( DB_MASTER );
			$row = $dbw->selectRow( 'text',
				array( 'old_text', 'old_flags' ),
				array( 'old_id' => $textId ),
				__METHOD__,
				$forUpdate ? array( 'FOR UPDATE' ) : array() );
		}

		if ( !$row ) {
			wfDebugLog( 'Revision', "No text row with ID '$textId' (revision {$this->getRevisionId()})." );
		}

		$text = self::getRevisionText( $row );
		if ( $row && $text === false ) {
			wfDebugLog( 'Revision', "No blob for text row '$textId' (revision {$this->getRevisionId()})." );
		}

		# No negative caching -- negative hits on text rows may be due to corrupted slave servers
		if ( $wgRevisionCacheExpiry && $text !== false ) {
			$wgMemc->set( $key, $text, $wgRevisionCacheExpiry );
		}

		return $text;
	}

	/**
	 * Get revision text associated with an old or archive row
	 * $row is usually an object from wfFetchRow(), both the flags and the text
	 * field must be included.
	 *
	 * @todo factor this out into a blob storage layer
	 *
	 * @param object $row The text data
	 * @param string $prefix Table prefix (default 'old_')
	 * @param string|bool $wiki The name of the wiki to load the revision text from
	 *   (same as the the wiki $row was loaded from) or false to indicate the local
	 *   wiki (this is the default). Otherwise, it must be a symbolic wiki database
	 *   identifier as understood by the LoadBalancer class.
	 * @return string Text the text requested or false on failure
	 */
	public static function getRevisionText( $row, $prefix = 'old_', $wiki = false ) {

		# Get data
		$textField = $prefix . 'text';
		$flagsField = $prefix . 'flags';

		if ( isset( $row->$flagsField ) ) {
			$flags = explode( ',', $row->$flagsField );
		} else {
			$flags = array();
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
			$text = ExternalStore::fetchFromURL( $url, array( 'wiki' => $wiki ) );
		}

		// If the text was fetched without an error, convert it
		if ( $text !== false ) {
			$text = self::decompressRevisionText( $text, $flags );
		}
		return $text;
	}

	/**
	 * Re-converts revision text according to it's flags.
	 *
	 * @todo factor this out into a blob storage layer
	 * @todo remove access to global state
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
