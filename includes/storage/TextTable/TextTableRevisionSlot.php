<?php

namespace MediaWiki\Storage\TextTable;

use Content;
use ContentHandler;
use IDBAccessObject;
use MediaWiki\Storage\RevisionContentException;
use MediaWiki\Storage\RevisionSlot;
use ObjectCache;
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
	protected $revisionFieldPrefix;

	/**
	 * @var string
	 */
	protected $textFieldPrefix;

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
	 */
	public function __construct( Title $title, $revisionRow, $textRow = null, $queryFlags = 0 ) {
		$this->title = $title;
		$this->revisionRow = (object)$revisionRow;
		$this->textRow = ( $textRow === null ? null : (object)$textRow );
		$this->queryFlags = $queryFlags;

		$this->revisionFieldPrefix = 'rev_';
		$this->textFieldPrefix = 'old_';
	}

	/**
	 * @param string $name
	 *
	 * @return string|int|null
	 */
	private function getField( $name ) {
		$field = $this->revisionFieldPrefix . $name;
		return isset( $this->revisionRow->$field ) ? $this->revisionRow->$field : null;
	}

	/**
	 * @param string $name
	 * @param mixed $value
	 */
	private function setField( $name, $value ) {
		$field = $this->revisionFieldPrefix . $name;
		$this->revisionRow->$field = $value;
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
		$model = $this->getField( 'content_model' );

		if ( empty( $model ) ) {
			$title = $this->getTitle();
			if ( $title ) {
				$model = ContentHandler::getDefaultModelFor( $title );
			} else {
				$model = CONTENT_MODEL_WIKITEXT;
			}

			$this->setField( 'content_model', $model );
		}

		return $model;
	}

	/**
	 * @warning The serialization format is an internal detail of the storage layer. It should
	 * really not be exposed here. There should be no reason to call this method.
	 *
	 * @throws RevisionContentException
	 * @return string
	 */
	public function getContentFormat() {
		$format = $this->getField( 'content_format' );

		if ( empty( $format ) ) {
			$handler = $this->getContentHandler();
			$format = $handler->getDefaultFormat();
			$this->setField( 'content_format', $format );
		}

		return $format;
	}

	/**
	 * @return string
	 */
	public function getTouched() {
		return $this->getField( 'timestamp' );
	}

	/**
	 * @return int
	 */
	public function getPageId() {
		return $this->getField( 'page' );
	}

	/**
	 * @return int
	 */
	public function getRevisionId() {
		return $this->getField( 'id' );
	}

	/**
	 * @return int
	 */
	public function getTextId() {
		return $this->getField( 'text_id' );
	}

	/**
	 * Returns the slot content's hash.
	 *
	 * @note The cost of calling this method may vary widely, as implementations are free to
	 * calculate the hash on the fly, which may entail lazy-loading the content from storage.
	 *
	 * @return string The base36 SHA1 hash of the content.
	 */
	public function getSha1() {
		//FIXME: calculate if missing or empty
		return $this->getField( 'sha1' );
	}

	/**
	 * Returns the slot content's nominal size.
	 *
	 * @note The cost of calling this method may vary widely, as implementations are free to
	 * calculate the size on the fly, which may entail lazy-loading the content from storage.
	 *
	 * @see Content::getSize().
	 *
	 * @return int The content size in bogo-bytes.
	 */
	public function getSize() {
		//FIXME: calculate if missing or empty
		return $this->getField( 'len' );
	}

	/**
	 * This is not a deleted slot, so no restrictions apply.
	 *
	 * @return null|string[]
	 */
	public function getReadRestrictions() {
		$bitfield = intval( $this->getField( 'deleted' ) );

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
	public function getContentHandler() {
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
		if ( $this->content === null ) {
			$this->content = $this->getField( 'content' );
		}

		if ( $this->content === null ) {
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
		return 'main';
	}

	/**
	 * Lazy-load the revision's text.
	 * Currently hardcoded to the 'text' table storage engine.
	 *
	 * @todo factor this out into a blob storage layer
	 * @todo remove access to global state for caching
	 *
	 * @return string|bool The revision's text, or false on failure
	 */
	private function loadBlob() {
		// Caching may be beneficial for massive use of external storage
		global $wgRevisionCacheExpiry;

		$cache = ObjectCache::getMainWANInstance();
		$textId = $this->getTextId();
		$key = wfMemcKey( 'revisiontext', 'textid', $textId );
		if ( $wgRevisionCacheExpiry ) {
			$text = $cache->get( $key );
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
			TextTable::getTextRow( $textId, DB_SLAVE, __METHOD__ );
		}

		// Fallback to the master in case of slave lag. Also use FOR UPDATE if it was
		// used to fetch this revision to avoid missing the row due to REPEATABLE-READ.
		$forUpdate = ( $this->queryFlags & IDBAccessObject::READ_LOCKING == IDBAccessObject::READ_LOCKING );
		if ( !$row && ( $forUpdate || wfGetLB()->getServerCount() > 1 ) ) {
			TextTable::getTextRow(
				$textId,
				DB_SLAVE,
				__METHOD__,
				$forUpdate ? array( 'FOR UPDATE' ) : array()
			);
		}

		if ( !$row ) {
			wfDebugLog( 'Revision', "No text row with ID '$textId' (revision {$this->getRevisionId()})." );
		}

		$text = TextTable::getRevisionText( $row );
		if ( $row && $text === false ) {
			wfDebugLog( 'Revision', "No blob for text row '$textId' (revision {$this->getRevisionId()})." );
		}

		# No negative caching -- negative hits on text rows may be due to corrupted slave servers
		if ( $wgRevisionCacheExpiry && $text !== false ) {
			$cache->set( $key, $text, $wgRevisionCacheExpiry );
		}

		return $text;
	}

}
