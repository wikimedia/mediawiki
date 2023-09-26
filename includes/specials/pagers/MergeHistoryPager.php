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

use MediaWiki\Cache\LinkBatchFactory;
use MediaWiki\ChangeTags\ChangeTags;
use MediaWiki\ChangeTags\ChangeTagsStore;
use MediaWiki\CommentFormatter\CommentFormatter;
use MediaWiki\Context\IContextSource;
use MediaWiki\Html\Html;
use MediaWiki\Linker\Linker;
use MediaWiki\Linker\LinkRenderer;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\RevisionStore;
use MediaWiki\User\UserIdentityValue;
use Wikimedia\Rdbms\IConnectionProvider;

/**
 * @ingroup Pager
 */
class MergeHistoryPager extends ReverseChronologicalPager {

	/** @inheritDoc */
	public $mGroupByDate = true;

	public array $mConds;
	private int $articleID;
	private string $maxTimestamp;
	private int $maxRevId;
	private string $mergePointTimestamp;

	/** @var int[] */
	public array $prevId;

	private LinkBatchFactory $linkBatchFactory;
	private RevisionStore $revisionStore;
	private CommentFormatter $commentFormatter;
	private ChangeTagsStore $changeTagsStore;

	public function __construct(
		IContextSource $context,
		LinkRenderer $linkRenderer,
		LinkBatchFactory $linkBatchFactory,
		IConnectionProvider $dbProvider,
		RevisionStore $revisionStore,
		CommentFormatter $commentFormatter,
		ChangeTagsStore $changeTagsStore,
		$conds,
		PageIdentity $source,
		PageIdentity $dest,
		string $mergePointTimestamp
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
		$this->maxRevId = (int)$maxRevId;
		$this->mergePointTimestamp = $mergePointTimestamp;

		// Set database before parent constructor to avoid setting it there
		$this->mDb = $dbr;
		parent::__construct( $context, $linkRenderer );
		$this->linkBatchFactory = $linkBatchFactory;
		$this->revisionStore = $revisionStore;
		$this->commentFormatter = $commentFormatter;
		$this->changeTagsStore = $changeTagsStore;
	}

	protected function doBatchLookups() {
		# Do a link batch query
		$this->mResult->seek( 0 );
		$batch = $this->linkBatchFactory->newLinkBatch()->setCaller( __METHOD__ );
		# Give some pointers to make (last) links
		$this->prevId = [];
		$rev_id = null;
		foreach ( $this->mResult as $row ) {
			$batch->addUser( new UserIdentityValue( (int)$row->rev_user, $row->rev_user_text ) );

			if ( $rev_id !== null ) {
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
		$checkBox = Html::radio(
			'mergepoint',
			$this->mergePointTimestamp === $ts || $this->mergePointTimestamp === $tsWithId,
			[ 'value' => $tsWithId ]
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
		$this->changeTagsStore->modifyDisplayQueryBuilder( $queryBuilder, 'revision' );

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
