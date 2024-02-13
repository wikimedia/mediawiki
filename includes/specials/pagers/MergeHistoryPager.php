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
 * @ingroup Pager
 */

namespace MediaWiki\Pager;

use ChangeTags;
use MediaWiki\Cache\LinkBatchFactory;
use MediaWiki\CommentFormatter\CommentFormatter;
use MediaWiki\Context\IContextSource;
use MediaWiki\Html\Html;
use MediaWiki\Linker\Linker;
use MediaWiki\Linker\LinkRenderer;
use MediaWiki\MediaWikiServices;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\RevisionStore;
use Wikimedia\Rdbms\IConnectionProvider;
use Xml;

/**
 * @ingroup Pager
 */
class MergeHistoryPager extends ReverseChronologicalPager {

	public $mGroupByDate = true;

	/** @var array */
	public $mConds;

	/** @var int */
	private $articleID;

	/** @var string */
	private $maxTimestamp;

	/** @var string */
	private $maxRevId;

	/** @var string */
	private $mergePointTimestamp;

	/** @var int[] */
	public $prevId;

	private LinkBatchFactory $linkBatchFactory;
	private RevisionStore $revisionStore;
	private CommentFormatter $commentFormatter;

	/**
	 * @param IContextSource $context
	 * @param LinkRenderer $linkRenderer
	 * @param LinkBatchFactory $linkBatchFactory
	 * @param IConnectionProvider $dbProvider
	 * @param RevisionStore $revisionStore
	 * @param CommentFormatter $commentFormatter
	 * @param array $conds
	 * @param PageIdentity $source
	 * @param PageIdentity $dest
	 * @param string $mergePointTimestamp
	 */
	public function __construct(
		IContextSource $context,
		LinkRenderer $linkRenderer,
		LinkBatchFactory $linkBatchFactory,
		IConnectionProvider $dbProvider,
		RevisionStore $revisionStore,
		CommentFormatter $commentFormatter,
		$conds,
		PageIdentity $source,
		PageIdentity $dest,
		$mergePointTimestamp
	) {
		$this->mConds = $conds;
		$this->articleID = $source->getId();

		$dbr = $dbProvider->getReplicaDatabase();
		$maxtimestamp = $dbr->newSelectQueryBuilder()
			->select( 'MIN(rev_timestamp)' )
			->from( 'revision' )
			->where( [ 'rev_page' => $dest->getId() ] )
			->caller( __METHOD__ )->fetchField();
		$maxRevId = $dbr->newSelectQueryBuilder()
			->select( "MIN(rev_id)" )
			->from( 'revision' )
			->where( [ 'rev_page' => $dest->getId() ] )
			->where( [ 'rev_timestamp' => $maxtimestamp ] )
			->caller( __METHOD__ )->fetchField();
		$this->maxTimestamp = $maxtimestamp;
		$this->maxRevId = $maxRevId;
		$this->mergePointTimestamp = $mergePointTimestamp;

		// Set database before parent constructor to avoid setting it there
		$this->mDb = $dbr;
		parent::__construct( $context, $linkRenderer );
		$this->linkBatchFactory = $linkBatchFactory;
		$this->revisionStore = $revisionStore;
		$this->commentFormatter = $commentFormatter;
	}

	protected function doBatchLookups() {
		# Do a link batch query
		$this->mResult->seek( 0 );
		$batch = $this->linkBatchFactory->newLinkBatch();
		# Give some pointers to make (last) links
		$this->prevId = [];
		$rev_id = null;
		foreach ( $this->mResult as $row ) {
			$batch->add( NS_USER, $row->rev_user_text );
			$batch->add( NS_USER_TALK, $row->rev_user_text );

			if ( isset( $rev_id ) ) {
				if ( $rev_id > $row->rev_id ) {
					$this->prevId[$rev_id] = $row->rev_id;
				} elseif ( $rev_id < $row->rev_id ) {
					$this->prevId[$row->rev_id] = $rev_id;
				}
			}

			$rev_id = $row->rev_id;
		}

		$batch->execute();
		$this->mResult->seek( 0 );
	}

	/**
	 * @inheritDoc
	 */
	protected function getStartBody() {
		return "<section class='mw-pager-body'>\n";
	}

	/**
	 * @inheritDoc
	 */
	protected function getEndBody() {
		return "</section>\n";
	}

	public function formatRow( $row ) {
		$revRecord = $this->revisionStore->newRevisionFromRow( $row );

		$linkRenderer = $this->getLinkRenderer();

		$stxt = '';
		$last = $this->msg( 'last' )->escaped();

		$ts = wfTimestamp( TS_MW, $row->rev_timestamp );
		$tsWithId = $ts . "|" . $row->rev_id;
		$checkBox = Xml::radio(
			'mergepoint', $tsWithId,
			$this->mergePointTimestamp === $ts || $this->mergePointTimestamp === $tsWithId
		);

		$user = $this->getUser();

		$pageLink = $linkRenderer->makeKnownLink(
			$revRecord->getPageAsLinkTarget(),
			$this->getLanguage()->userTimeAndDate( $ts, $user ),
			[],
			[ 'oldid' => $revRecord->getId() ]
		);
		if ( $revRecord->isDeleted( RevisionRecord::DELETED_TEXT ) ) {
			$class = Linker::getRevisionDeletedClass( $revRecord );
			$pageLink = '<span class=" ' . $class . '">' . $pageLink . '</span>';
		}

		# Last link
		if ( !$revRecord->userCan( RevisionRecord::DELETED_TEXT, $this->getAuthority() ) ) {
			$last = $this->msg( 'last' )->escaped();
		} elseif ( isset( $this->prevId[$row->rev_id] ) ) {
			$last = $linkRenderer->makeKnownLink(
				$revRecord->getPageAsLinkTarget(),
				$this->msg( 'last' )->text(),
				[],
				[
					'diff' => $row->rev_id,
					'oldid' => $this->prevId[$row->rev_id]
				]
			);
		}

		$userLink = Linker::revUserTools( $revRecord );

		$size = $row->rev_len;
		if ( $size !== null ) {
			$stxt = Linker::formatRevisionSize( $size );
		}
		$comment = $this->commentFormatter->formatRevision( $revRecord, $user );

		// Tags, if any.
		[ $tagSummary, $classes ] = ChangeTags::formatSummaryRow(
			$row->ts_tags,
			'mergehistory',
			$this->getContext()
		);

		return Html::rawElement( 'li', $classes,
			$this->msg( 'mergehistory-revisionrow' )
				->rawParams( $checkBox, $last, $pageLink, $userLink, $stxt, $comment, $tagSummary )->escaped() );
	}

	public function getQueryInfo() {
		$dbr = $this->getDatabase();
		$queryBuilder = $this->revisionStore->newSelectQueryBuilder( $dbr )
			->joinComment()
			->joinPage()
			->joinUser()
			->where( $this->mConds )
			->andWhere( [
				'rev_page' => $this->articleID,
				$dbr->buildComparison( "<",
					[
						"rev_timestamp" => $this->maxTimestamp,
						"rev_id" => $this->maxRevId
					]
				)
			] );
		MediaWikiServices::getInstance()->getChangeTagsStore()->modifyDisplayQueryBuilder( $queryBuilder, 'revision' );

		return $queryBuilder->getQueryInfo( 'join_conds' );
	}

	public function getIndexField() {
		return [ [ 'rev_timestamp', 'rev_id' ] ];
	}
}

/**
 * Retain the old class name for backwards compatibility.
 * @deprecated since 1.41
 */
class_alias( MergeHistoryPager::class, 'MergeHistoryPager' );
