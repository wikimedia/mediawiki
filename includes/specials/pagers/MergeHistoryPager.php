<?php
/**
 * @license GPL-2.0-or-later
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
	private string $mergePointTimestamp;
	private string $mergePointTimestampOld;

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
		array $conds,
		PageIdentity $source,
		PageIdentity $dest,
		string $mergePointTimestamp,
		string $mergePointTimestampOld
	) {
		$this->mConds = $conds;
		$this->articleID = $source->getId();

		$dbr = $dbProvider->getReplicaDatabase();
		$this->mergePointTimestamp = $mergePointTimestamp;
		$this->mergePointTimestampOld = $mergePointTimestampOld;

		// Set database before parent constructor to avoid setting it there
		$this->mDb = $dbr;
		parent::__construct( $context, $linkRenderer );
		$this->linkBatchFactory = $linkBatchFactory;
		$this->revisionStore = $revisionStore;
		$this->commentFormatter = $commentFormatter;
		$this->changeTagsStore = $changeTagsStore;
	}

	/** @inheritDoc */
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
		return "<section id='mw-mergehistory-list' class='mw-pager-body'>\n";
	}

	/**
	 * @inheritDoc
	 */
	protected function getEndBody() {
		return "</section>\n";
	}

	/** @inheritDoc */
	public function formatRow( $row ) {
		$revRecord = $this->revisionStore->newRevisionFromRow( $row );

		$linkRenderer = $this->getLinkRenderer();

		$stxt = '';
		$last = $this->msg( 'last' )->escaped();

		$ts = wfTimestamp( TS_MW, $row->rev_timestamp );
		$tsWithId = $ts . "|" . $row->rev_id;
		$oldCheckBox = Html::radio(
			'mergepointold',
			$this->mergePointTimestampOld === $tsWithId,
			[ 'value' => $tsWithId ]
		);
		$newCheckBox = Html::radio(
				'mergepoint',
				$this->mergePointTimestamp === $ts || $this->mergePointTimestamp === $tsWithId,
				[ 'value' => $tsWithId ]
		);
		$cbs = $oldCheckBox . $newCheckBox;

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
				->rawParams( $cbs, $last, $pageLink, $userLink, $stxt, $comment, $tagSummary )->escaped() );
	}

	/** @inheritDoc */
	public function getQueryInfo() {
		$dbr = $this->getDatabase();
		$queryBuilder = $this->revisionStore->newSelectQueryBuilder( $dbr )
			->joinComment()
			->joinPage()
			->joinUser()
			->where( $this->mConds )
			->andWhere( [
				'rev_page' => $this->articleID,
			] );
		$this->changeTagsStore->modifyDisplayQueryBuilder( $queryBuilder, 'revision' );

		return $queryBuilder->getQueryInfo( 'join_conds' );
	}

	/** @inheritDoc */
	public function getIndexField() {
		return [ [ 'rev_timestamp', 'rev_id' ] ];
	}
}

/**
 * Retain the old class name for backwards compatibility.
 * @deprecated since 1.41
 */
class_alias( MergeHistoryPager::class, 'MergeHistoryPager' );
