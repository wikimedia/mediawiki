<?php

namespace MediaWiki\Storage;

/**
 * Controller for updating pages by adding revisions.
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
use DBAccessObjectUtils;
use \IDBAccessObject;
use MediaWiki\Linker\LinkTarget;
use MediaWiki\MediaWikiServices;
use MWException;
use RecentChange;
use Title;
use User;
use Wikimedia\Rdbms\Database;
use Wikimedia\Rdbms\FakeResultWrapper;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\ResultWrapper;

/**
 * Controller for updating pages by adding revisions.
 *
 * @note This was written to act as a drop-in replacement for the corresponding
 *       static methods in WikiPage and Revision.
 */
// FIXME: move stuff in this class back to RevisionStore for now
class PageUpdater implements IDBAccessObject {

	/**
	 * @var RevisionRecord
	 */
	private $revision;

	/**
	 * @var RevisionStore
	 */
	private $revisionStore;

	/**
	 * Insert a new revision into the database, returning the new revision ID
	 * number on success and dies horribly on failure.
	 *
	 * @param RevisionRecord $rev
	 * @param IDatabase $dbw (master connection)
	 *
	 * @throws MWException
	 * @throws UnexpectedValueException
	 * @return int
	 */
	public function insertOn( RevisionRecord $rev, $dbw, $flags = 0 ) {
		global $wgDefaultExternalStore, $wgContentHandlerUseDB;

		// We're inserting a new revision, so we have to use master anyway.
		// If it's a null revision, it may have references to rows that
		// are not in the replica yet (the text row).
		$flags |= self::READ_LATEST;

		!!!re-use page-row data if possible!

		// Not allowed to have rev_page equal to 0, false, etc.
		if ( !$this->mPage ) {
			$title = $this->getTitle();
			if ( $title instanceof Title ) {
				$titleText = ' for page ' . $title->getPrefixedText();
			} else {
				$titleText = '';
			}
			throw new MWException( "Cannot insert revision$titleText: page ID must be nonzero" );
		}

		$this->checkContentModel();

		$data = $this->mText;
		$flags = self::compressRevisionText( $data );

		# Write to external storage if required
		if ( $wgDefaultExternalStore ) {
			// Store and get the URL
			$data = ExternalStore::insertToDefault( $data );
			if ( !$data ) {
				throw new MWException( "Unable to store text to external storage" );
			}
			if ( $flags ) {
				$flags .= ',';
			}
			$flags .= 'external';
		}

		# Record the text (or external storage URL) to the text table
		if ( $this->mTextId === null ) {
			$old_id = $dbw->nextSequenceValue( 'text_old_id_seq' );
			$dbw->insert( 'text',
			              [
				              'old_id' => $old_id,
				              'old_text' => $data,
				              'old_flags' => $flags,
			              ], __METHOD__
			);
			$this->mTextId = $dbw->insertId();
		}

		if ( $this->mComment === null ) {
			$this->mComment = "";
		}

		# Record the edit in revisions
		$rev_id = $this->mId !== null
			? $this->mId
			: $dbw->nextSequenceValue( 'revision_rev_id_seq' );
		$row = [
			'rev_id'         => $rev_id,
			'rev_page'       => $this->mPage,
			'rev_text_id'    => $this->mTextId,
			'rev_comment'    => $this->mComment,
			'rev_minor_edit' => $this->mMinorEdit ? 1 : 0,
			'rev_user'       => $this->mUser,
			'rev_user_text'  => $this->mUserText,
			'rev_timestamp'  => $dbw->timestamp( $this->mTimestamp ),
			'rev_deleted'    => $this->mDeleted,
			'rev_len'        => $this->mSize,
			'rev_parent_id'  => $this->mParentId === null
				? $this->getPreviousRevisionId( $dbw )
				: $this->mParentId,
			'rev_sha1'       => $this->mSha1 === null
				? self::base36Sha1( $this->mText )
				: $this->mSha1,
		];

		if ( $wgContentHandlerUseDB ) {
			// NOTE: Store null for the default model and format, to save space.
			// XXX: Makes the DB sensitive to changed defaults.
			// Make this behavior optional? Only in miser mode?

			$model = $this->getContentModel();
			$format = $this->getContentFormat();

			$title = $this->getTitle();

			if ( $title === null ) {
				throw new MWException( "Insufficient information to determine the title of the "
				                       . "revision's page!" );
			}

			$defaultModel = ContentHandler::getDefaultModelFor( $title );
			$defaultFormat = ContentHandler::getForModelID( $defaultModel )->getDefaultFormat();

			$row['rev_content_model'] = ( $model === $defaultModel ) ? null : $model;
			$row['rev_content_format'] = ( $format === $defaultFormat ) ? null : $format;
		}

		$dbw->insert( 'revision', $row, __METHOD__ );

		if ( $this->mId === null ) {
			// Only if nextSequenceValue() was called
			$this->mId = $dbw->insertId();
		}

		// Assertion to try to catch T92046
		if ( (int)$this->mId === 0 ) {
			throw new UnexpectedValueException(
				'After insert, RevisionRecord mId is ' . var_export( $this->mId, 1 ) . ': ' .
				var_export( $row, 1 )
			);
		}

		// Avoid PHP 7.1 warning of passing $this by reference
		$revision = $this;
		Hooks::run( 'RevisionInsertComplete', [ &$revision, $data, $flags ] );

		return $this->mId;
	}

	/**
	 * Get previous revision Id for this page_id
	 * This is used to populate rev_parent_id on save
	 *
	 * @param IDatabase $db
	 * @return int
	 */
	private function getPreviousRevisionId( $db ) {
		if ( $this->mPage === null ) {
			return 0;
		}
		# Use page_latest if ID is not given
		if ( !$this->mId ) {
			$prevId = $db->selectField( 'page', 'page_latest',
			                            [ 'page_id' => $this->mPage ],
			                            __METHOD__ );
		} else {
			$prevId = $db->selectField( 'revision', 'rev_id',
			                            [ 'rev_page' => $this->mPage, 'rev_id < ' . $this->mId ],
			                            __METHOD__,
			                            [ 'ORDER BY' => 'rev_id DESC' ] );
		}
		return intval( $prevId );
	}

	/**
	 * @todo FIXME move to page updater
	 * @throws MWException
	 */
	private function checkContentModel() {
		global $wgContentHandlerUseDB;

		// Note: may return null for revisions that have not yet been inserted
		$title = $this->getTitle();

		$model = $this->getContentModel();
		$format = $this->getContentFormat();
		$handler = $this->getContentHandler();

		if ( !$handler->isSupportedFormat( $format ) ) {
			$t = $title->getPrefixedDBkey();

			throw new MWException( "Can't use format $format with content model $model on $t" );
		}

		if ( !$wgContentHandlerUseDB && $title ) {
			// if $wgContentHandlerUseDB is not set,
			// all revisions must use the default content model and format.

			$defaultModel = ContentHandler::getDefaultModelFor( $title );
			$defaultHandler = ContentHandler::getForModelID( $defaultModel );
			$defaultFormat = $defaultHandler->getDefaultFormat();

			if ( $this->getContentModel() != $defaultModel ) {
				$t = $title->getPrefixedDBkey();

				throw new MWException( "Can't save non-default content model with "
				                       . "\$wgContentHandlerUseDB disabled: model is $model, "
				                       . "default for $t is $defaultModel" );
			}

			if ( $this->getContentFormat() != $defaultFormat ) {
				$t = $title->getPrefixedDBkey();

				throw new MWException( "Can't use non-default content format with "
				                       . "\$wgContentHandlerUseDB disabled: format is $format, "
				                       . "default for $t is $defaultFormat" );
			}
		}

		$content = $this->getContent( self::RAW );
		$prefixedDBkey = $title->getPrefixedDBkey();
		$revId = $this->mId;

		if ( !$content ) {
			throw new MWException(
				"Content of revision $revId ($prefixedDBkey) could not be loaded for validation!"
			);
		}
		if ( !$content->isValid() ) {
			throw new MWException(
				"Content of revision $revId ($prefixedDBkey) is not valid! Content model is $model"
			);
		}
	}

	/**
	 * Create a new null-revision for insertion into a page's
	 * history. This will not re-save the text, but simply refer
	 * to the text from the previous version.
	 *
	 * Such revisions can for instance identify page rename
	 * operations and other such meta-modifications.
	 *
	 * @todo FIXME: move this to the page update interface!
	 *
	 * @param IDatabase $dbw
	 * @param int $pageId ID number of the page to read from
	 * @param string $summary RevisionRecord's summary
	 * @param bool $minor Whether the revision should be considered as minor
	 * @param User|null $user User object to use or null for $wgUser
	 * @return RevisionRecord|null RevisionRecord or null on error
	 */
	public function newNullRevision( $dbw, $pageId, $summary, $minor, $user = null ) {
		global $wgContentHandlerUseDB, $wgContLang;

		$fields = [ 'page_latest', 'page_namespace', 'page_title',
			'rev_text_id', 'rev_len', 'rev_sha1' ];

		if ( $wgContentHandlerUseDB ) {
			$fields[] = 'rev_content_model';
			$fields[] = 'rev_content_format';
		}

		$current = $dbw->selectRow(
			[ 'page', 'revision' ],
			$fields,
			[
				'page_id' => $pageId,
				'page_latest=rev_id',
			],
			__METHOD__,
			[ 'FOR UPDATE' ] // T51581
		);

		if ( $current ) {
			if ( !$user ) {
				global $wgUser;
				$user = $wgUser;
			}

			// Truncate for whole multibyte characters
			$summary = $wgContLang->truncate( $summary, 255 );

			$row = [
				'page'       => $pageId,
				'user_text'  => $user->getName(),
				'user'       => $user->getId(),
				'comment'    => $summary,
				'minor_edit' => $minor,
				'text_id'    => $current->rev_text_id,
				'parent_id'  => $current->page_latest,
				'len'        => $current->rev_len,
				'sha1'       => $current->rev_sha1
			];

			if ( $wgContentHandlerUseDB ) {
				$row['content_model'] = $current->rev_content_model;
				$row['content_format'] = $current->rev_content_format;
			}

			$row['title'] = Title::makeTitle( $current->page_namespace, $current->page_title );

			// TODO: audience wrapper!
			$revision = new LazyRevisionRecord( $row, $this->getSlotsFromLegacySchema( $row ) );
		} else {
			$revision = null;
		}

		return $revision;
	}

}
