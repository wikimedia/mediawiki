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
 * @file
 */

use MediaWiki\MediaWikiServices;
use MediaWiki\Page\UndeletePage;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\User\UserIdentity;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\IResultWrapper;

/**
 * Used to show archived pages and eventually restore them.
 */
class PageArchive {

	/** @var Title */
	protected $title;

	/** @var Status|null */
	protected $fileStatus;

	/** @var Status|null */
	protected $revisionStatus;

	/**
	 * @param Title $title
	 */
	public function __construct( Title $title ) {
		$this->title = $title;
	}

	/**
	 * List deleted pages recorded in the archive matching the
	 * given term, using search engine archive.
	 * Returns result wrapper with (ar_namespace, ar_title, count) fields.
	 *
	 * @param string $term Search term
	 * @return IResultWrapper|bool
	 */
	public static function listPagesBySearch( $term ) {
		$title = Title::newFromText( $term );
		if ( $title ) {
			$ns = $title->getNamespace();
			$termMain = $title->getText();
			$termDb = $title->getDBkey();
		} else {
			// Prolly won't work too good
			// @todo handle bare namespace names cleanly?
			$ns = 0;
			$termMain = $termDb = $term;
		}

		// Try search engine first
		$engine = MediaWikiServices::getInstance()->newSearchEngine();
		$engine->setLimitOffset( 100 );
		$engine->setNamespaces( [ $ns ] );
		$results = $engine->searchArchiveTitle( $termMain );
		if ( !$results->isOK() ) {
			$results = [];
		} else {
			$results = $results->getValue();
		}

		if ( !$results ) {
			// Fall back to regular prefix search
			return self::listPagesByPrefix( $term );
		}

		$dbr = wfGetDB( DB_REPLICA );
		$condTitles = array_unique( array_map( static function ( Title $t ) {
			return $t->getDBkey();
		}, $results ) );
		$conds = [
			'ar_namespace' => $ns,
			$dbr->makeList( [ 'ar_title' => $condTitles ], LIST_OR ) . " OR ar_title " .
			$dbr->buildLike( $termDb, $dbr->anyString() )
		];

		return self::listPages( $dbr, $conds );
	}

	/**
	 * List deleted pages recorded in the archive table matching the
	 * given title prefix.
	 * Returns result wrapper with (ar_namespace, ar_title, count) fields.
	 *
	 * @param string $prefix Title prefix
	 * @return IResultWrapper|bool
	 */
	public static function listPagesByPrefix( $prefix ) {
		$dbr = wfGetDB( DB_REPLICA );

		$title = Title::newFromText( $prefix );
		if ( $title ) {
			$ns = $title->getNamespace();
			$prefix = $title->getDBkey();
		} else {
			// Prolly won't work too good
			// @todo handle bare namespace names cleanly?
			$ns = 0;
		}

		$conds = [
			'ar_namespace' => $ns,
			'ar_title' . $dbr->buildLike( $prefix, $dbr->anyString() ),
		];

		return self::listPages( $dbr, $conds );
	}

	/**
	 * @param IDatabase $dbr
	 * @param string|array $condition
	 * @return bool|IResultWrapper
	 */
	protected static function listPages( $dbr, $condition ) {
		return $dbr->select(
			[ 'archive' ],
			[
				'ar_namespace',
				'ar_title',
				'count' => 'COUNT(*)'
			],
			$condition,
			__METHOD__,
			[
				'GROUP BY' => [ 'ar_namespace', 'ar_title' ],
				'ORDER BY' => [ 'ar_namespace', 'ar_title' ],
				'LIMIT' => 100,
			]
		);
	}

	/**
	 * List the revisions of the given page. Returns result wrapper with
	 * various archive table fields.
	 *
	 * @deprecated since 1.38 Use ArchivedRevisionLookup::listRevisions
	 * @return IResultWrapper|bool
	 */
	public function listRevisions() {
		$lookup = MediaWikiServices::getInstance()->getArchivedRevisionLookup();
		return $lookup->listRevisions( $this->title );
	}

	/**
	 * List the deleted file revisions for this page, if it's a file page.
	 * Returns a result wrapper with various filearchive fields, or null
	 * if not a file page.
	 *
	 * @return IResultWrapper|null
	 * @todo Does this belong in Image for fuller encapsulation?
	 */
	public function listFiles() {
		if ( $this->title->getNamespace() !== NS_FILE ) {
			return null;
		}

		$loadBalancer = MediaWikiServices::getInstance()->getDBLoadBalancer();
		$dbr = $loadBalancer->getConnectionRef( DB_REPLICA );
		$fileQuery = ArchivedFile::getQueryInfo();
		return $dbr->select(
			$fileQuery['tables'],
			$fileQuery['fields'],
			[ 'fa_name' => $this->title->getDBkey() ],
			__METHOD__,
			[ 'ORDER BY' => 'fa_timestamp DESC' ],
			$fileQuery['joins']
		);
	}

	/**
	 * Return a RevisionRecord object containing data for the deleted revision.
	 *
	 * @internal only for use in SpecialUndelete
	 *
	 * @deprecated since 1.38 Use ArchivedRevisionLookup::getRevisionRecordByTimestamp
	 * @param string $timestamp
	 * @return RevisionRecord|null
	 */
	public function getRevisionRecordByTimestamp( $timestamp ) {
		$lookup = MediaWikiServices::getInstance()->getArchivedRevisionLookup();
		return $lookup->getRevisionRecordByTimestamp( $this->title, $timestamp );
	}

	/**
	 * Return the archived revision with the given ID.
	 *
	 * @since 1.35
	 * @deprecated since 1.38 Use ArchivedRevisionLookup::getArchivedRevisionRecord
	 *
	 * @param int $revId
	 * @return RevisionRecord|null
	 */
	public function getArchivedRevisionRecord( int $revId ) {
		$lookup = MediaWikiServices::getInstance()->getArchivedRevisionLookup();
		return $lookup->getArchivedRevisionRecord( $this->title, $revId );
	}

	/**
	 * Return the most-previous revision, either live or deleted, against
	 * the deleted revision given by timestamp.
	 *
	 * May produce unexpected results in case of history merges or other
	 * unusual time issues.
	 *
	 * @since 1.35
	 * @deprecated since 1.38 Use ArchivedRevisionLookup::getPreviousRevisionRecord
	 *
	 * @param string $timestamp
	 * @return RevisionRecord|null Null when there is no previous revision
	 */
	public function getPreviousRevisionRecord( string $timestamp ) {
		$lookup = MediaWikiServices::getInstance()->getArchivedRevisionLookup();
		return $lookup->getPreviousRevisionRecord( $this->title, $timestamp );
	}

	/**
	 * Returns the ID of the latest deleted revision.
	 *
	 * @deprecated since 1.38 Use ArchivedRevisionLookup::getLastRevisionId
	 * @return int|false The revision's ID, or false if there is no deleted revision.
	 */
	public function getLastRevisionId() {
		$lookup = MediaWikiServices::getInstance()->getArchivedRevisionLookup();
		return $lookup->getLastRevisionId( $this->title );
	}

	/**
	 * Quick check if any archived revisions are present for the page.
	 * This says nothing about whether the page currently exists in the page table or not.
	 *
	 * @deprecated since 1.38 Use ArchivedRevisionLookup::hasArchivedRevisions
	 * @return bool
	 */
	public function isDeleted() {
		$lookup = MediaWikiServices::getInstance()->getArchivedRevisionLookup();
		return $lookup->hasArchivedRevisions( $this->title );
	}

	/**
	 * Restore the given (or all) text and file revisions for the page.
	 * Once restored, the items will be removed from the archive tables.
	 * The deletion log will be updated with an undeletion notice.
	 *
	 * This also sets Status objects, $this->fileStatus and $this->revisionStatus
	 * (depending what operations are attempted).
	 *
	 * @since 1.35
	 * @deprecated since 1.38, use UndeletePage instead
	 *
	 * @param array $timestamps Pass an empty array to restore all revisions,
	 *   otherwise list the ones to undelete.
	 * @param UserIdentity $user
	 * @param string $comment
	 * @param array $fileVersions
	 * @param bool $unsuppress
	 * @param string|string[]|null $tags Change tags to add to log entry
	 *   ($user should be able to add the specified tags before this is called)
	 * @return array|bool [ number of file revisions restored, number of image revisions
	 *   restored, log message ] on success, false on failure.
	 */
	public function undeleteAsUser(
		$timestamps,
		UserIdentity $user,
		$comment = '',
		$fileVersions = [],
		$unsuppress = false,
		$tags = null
	) {
		$services = MediaWikiServices::getInstance();
		$page = $services->getWikiPageFactory()->newFromTitle( $this->title );
		$user = $services->getUserFactory()->newFromUserIdentity( $user );
		$up = $services->getUndeletePageFactory()->newUndeletePage( $page, $user );
		if ( is_string( $tags ) ) {
			$tags = [ $tags ];
		} elseif ( $tags === null ) {
			$tags = [];
		}
		$status = $up
			->setUndeleteOnlyTimestamps( $timestamps )
			->setUndeleteOnlyFileVersions( $fileVersions ?: [] )
			->setUnsuppress( $unsuppress )
			->setTags( $tags ?: [] )
			->undeleteUnsafe( $comment );
		// BC with old return format
		if ( $status->isGood() ) {
			$restoredRevs = $status->getValue()[UndeletePage::REVISIONS_RESTORED];
			$restoredFiles = $status->getValue()[UndeletePage::FILES_RESTORED];
			if ( $restoredRevs === 0 && $restoredFiles === 0 ) {
				$ret = false;
			} else {
				$ret = [ $restoredRevs, $restoredFiles, $comment ];
			}
		} else {
			$ret = false;
		}
		$this->fileStatus = $up->getFileStatus();
		$this->revisionStatus = $up->getRevisionStatus();
		return $ret;
	}

	/**
	 * @deprecated since 1.38 The entrypoints in UndeletePage return a StatusValue
	 * @return Status|null
	 */
	public function getFileStatus() {
		wfDeprecated( __METHOD__, '1.38' );
		return $this->fileStatus;
	}

	/**
	 * @deprecated since 1.38 The entrypoints in UndeletePage return a StatusValue
	 * @return Status|null
	 */
	public function getRevisionStatus() {
		wfDeprecated( __METHOD__, '1.38' );
		return $this->revisionStatus;
	}
}
