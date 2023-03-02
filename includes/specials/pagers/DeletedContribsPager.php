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

use MediaWiki\Cache\LinkBatchFactory;
use MediaWiki\CommentFormatter\CommentFormatter;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\Html\Html;
use MediaWiki\Linker\Linker;
use MediaWiki\Linker\LinkRenderer;
use MediaWiki\Revision\RevisionFactory;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Title\Title;
use Wikimedia\Rdbms\FakeResultWrapper;
use Wikimedia\Rdbms\ILoadBalancer;
use Wikimedia\Rdbms\IResultWrapper;

/**
 * @ingroup Pager
 */
class DeletedContribsPager extends ReverseChronologicalPager {

	public $mGroupByDate = true;

	/**
	 * @var string[] Local cache for escaped messages
	 */
	public $messages;

	/**
	 * @var string User name, or a string describing an IP address range
	 */
	public $target;

	/**
	 * @var string|int A single namespace number, or an empty string for all namespaces
	 */
	public $namespace = '';

	/** @var string[] */
	private $formattedComments = [];

	/** @var RevisionRecord[] Cached revisions by ID */
	private $revisions = [];

	/** @var HookRunner */
	private $hookRunner;

	/** @var RevisionFactory */
	private $revisionFactory;

	/** @var CommentFormatter */
	private $commentFormatter;

	/** @var LinkBatchFactory */
	private $linkBatchFactory;

	/**
	 * @param IContextSource $context
	 * @param HookContainer $hookContainer
	 * @param LinkRenderer $linkRenderer
	 * @param ILoadBalancer $loadBalancer
	 * @param RevisionFactory $revisionFactory
	 * @param CommentFormatter $commentFormatter
	 * @param LinkBatchFactory $linkBatchFactory
	 * @param string $target
	 * @param string|int $namespace
	 */
	public function __construct(
		IContextSource $context,
		HookContainer $hookContainer,
		LinkRenderer $linkRenderer,
		ILoadBalancer $loadBalancer,
		RevisionFactory $revisionFactory,
		CommentFormatter $commentFormatter,
		LinkBatchFactory $linkBatchFactory,
		$target,
		$namespace
	) {
		parent::__construct( $context, $linkRenderer );

		$msgs = [ 'deletionlog', 'undeleteviewlink', 'diff' ];
		foreach ( $msgs as $msg ) {
			$this->messages[$msg] = $this->msg( $msg )->text();
		}
		$this->target = $target;
		$this->namespace = $namespace;
		$this->hookRunner = new HookRunner( $hookContainer );
		$this->revisionFactory = $revisionFactory;
		$this->commentFormatter = $commentFormatter;
		$this->linkBatchFactory = $linkBatchFactory;
	}

	public function getDefaultQuery() {
		$query = parent::getDefaultQuery();
		$query['target'] = $this->target;

		return $query;
	}

	public function getQueryInfo() {
		$dbr = $this->getDatabase();
		$userCond = [ 'actor_name' => $this->target ];
		$conds = array_merge( $userCond, $this->getNamespaceCond() );
		// Paranoia: avoid brute force searches (T19792)
		if ( !$this->getAuthority()->isAllowed( 'deletedhistory' ) ) {
			$conds[] = $dbr->bitAnd( 'ar_deleted', RevisionRecord::DELETED_USER ) . ' = 0';
		} elseif ( !$this->getAuthority()->isAllowedAny( 'suppressrevision', 'viewsuppressed' ) ) {
			$conds[] = $dbr->bitAnd( 'ar_deleted', RevisionRecord::SUPPRESSED_USER ) .
				' != ' . RevisionRecord::SUPPRESSED_USER;
		}

		$queryInfo = $this->revisionFactory->getArchiveQueryInfo();
		$queryInfo['conds'] = $conds;
		$queryInfo['options'] = [];

		// rename the "joins" field to "join_conds" as expected by the base class.
		$queryInfo['join_conds'] = $queryInfo['joins'];
		unset( $queryInfo['joins'] );

		ChangeTags::modifyDisplayQuery(
			$queryInfo['tables'],
			$queryInfo['fields'],
			$queryInfo['conds'],
			$queryInfo['join_conds'],
			$queryInfo['options'],
			''
		);

		return $queryInfo;
	}

	protected function doBatchLookups() {
		// Do a link batch query
		$this->mResult->seek( 0 );
		$revisions = [];
		$linkBatch = $this->linkBatchFactory->newLinkBatch();
		// Give some pointers to make (last) links
		$revisionRows = [];
		foreach ( $this->mResult as $row ) {
			if ( $this->revisionFactory->isRevisionRow( $row, 'archive' ) ) {
				$revisionRows[] = $row;
				$linkBatch->add( $row->ar_namespace, $row->ar_title );
			}
		}
		// Cannot combine both loops, because RevisionFactory::newRevisionFromArchiveRow needs
		// the title information in LinkCache to avoid extra db queries
		$linkBatch->execute();

		foreach ( $revisionRows as $row ) {
			$revisions[$row->ar_rev_id] = $this->revisionFactory->newRevisionFromArchiveRow(
				$row,
				RevisionFactory::READ_NORMAL,
				Title::makeTitle( $row->ar_namespace, $row->ar_title )
			);
		}

		$this->formattedComments = $this->commentFormatter->createRevisionBatch()
			->authority( $this->getAuthority() )
			->revisions( $revisions )
			->execute();

		// For performance, save the revision objects for later.
		// The array is indexed by rev_id. doBatchLookups() may be called
		// multiple times with different results, so merge the revisions array,
		// ignoring any duplicates.
		$this->revisions += $revisions;
	}

	/**
	 * This method basically executes the exact same code as the parent class, though with
	 * a hook added, to allow extensions to add additional queries.
	 *
	 * @param string $offset Index offset, inclusive
	 * @param int $limit Exact query limit
	 * @param bool $order IndexPager::QUERY_ASCENDING or IndexPager::QUERY_DESCENDING
	 * @return IResultWrapper
	 */
	public function reallyDoQuery( $offset, $limit, $order ) {
		$data = [ parent::reallyDoQuery( $offset, $limit, $order ) ];

		// This hook will allow extensions to add in additional queries, nearly
		// identical to ContribsPager::reallyDoQuery.
		$this->hookRunner->onDeletedContribsPager__reallyDoQuery(
			$data, $this, $offset, $limit, $order );

		$result = [];

		// loop all results and collect them in an array
		foreach ( $data as $query ) {
			foreach ( $query as $i => $row ) {
				// use index column as key, allowing us to easily sort in PHP
				$result[$row->{$this->getIndexField()} . "-$i"] = $row;
			}
		}

		// sort results
		if ( $order === self::QUERY_ASCENDING ) {
			ksort( $result );
		} else {
			krsort( $result );
		}

		// enforce limit
		$result = array_slice( $result, 0, $limit );

		// get rid of array keys
		$result = array_values( $result );

		return new FakeResultWrapper( $result );
	}

	/**
	 * @return string[]
	 */
	protected function getExtraSortFields() {
		return [ 'ar_id' ];
	}

	public function getIndexField() {
		return 'ar_timestamp';
	}

	/**
	 * @return string
	 */
	public function getTarget() {
		return $this->target;
	}

	/**
	 * @return int|string
	 */
	public function getNamespace() {
		return $this->namespace;
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

	private function getNamespaceCond() {
		if ( $this->namespace !== '' ) {
			return [ 'ar_namespace' => (int)$this->namespace ];
		} else {
			return [];
		}
	}

	/**
	 * Generates each row in the contributions list.
	 *
	 * @todo This would probably look a lot nicer in a table.
	 * @param stdClass $row
	 * @return string
	 */
	public function formatRow( $row ) {
		$ret = '';
		$classes = [];
		$attribs = [];

		if ( $this->revisionFactory->isRevisionRow( $row, 'archive' ) ) {
			$attribs['data-mw-revid'] = $row->ar_rev_id;
			[ $ret, $classes ] = $this->formatRevisionRow( $row );
		}

		// Let extensions add data
		$this->hookRunner->onDeletedContributionsLineEnding(
			$this, $ret, $row, $classes, $attribs );
		$attribs = array_filter( $attribs,
			[ Sanitizer::class, 'isReservedDataAttribute' ],
			ARRAY_FILTER_USE_KEY
		);

		if ( $classes === [] && $attribs === [] && $ret === '' ) {
			wfDebug( "Dropping Special:DeletedContribution row that could not be formatted" );
			$ret = "<!-- Could not format Special:DeletedContribution row. -->\n";
		} else {
			$attribs['class'] = $classes;
			$ret = Html::rawElement( 'li', $attribs, $ret ) . "\n";
		}

		return $ret;
	}

	/**
	 * Generates each row in the contributions list for archive entries.
	 *
	 * Contributions which are marked "top" are currently on top of the history.
	 * For these contributions, a [rollback] link is shown for users with sysop
	 * privileges. The rollback link restores the most recent version that was not
	 * written by the target user.
	 *
	 * @todo This would probably look a lot nicer in a table.
	 * @param stdClass $row
	 * @return array
	 */
	private function formatRevisionRow( $row ) {
		$page = Title::makeTitle( $row->ar_namespace, $row->ar_title );

		$linkRenderer = $this->getLinkRenderer();

		$revRecord = $this->revisions[$row->ar_rev_id] ?? $this->revisionFactory->newRevisionFromArchiveRow(
				$row,
				RevisionFactory::READ_NORMAL,
				$page
			);

		$undelete = SpecialPage::getTitleFor( 'Undelete' );

		$logs = SpecialPage::getTitleFor( 'Log' );
		$dellog = $linkRenderer->makeKnownLink(
			$logs,
			$this->messages['deletionlog'],
			[],
			[
				'type' => 'delete',
				'page' => $page->getPrefixedText()
			]
		);

		$reviewlink = $linkRenderer->makeKnownLink(
			SpecialPage::getTitleFor( 'Undelete', $page->getPrefixedDBkey() ),
			$this->messages['undeleteviewlink']
		);

		$user = $this->getUser();

		if ( $this->getAuthority()->isAllowed( 'deletedtext' ) ) {
			$last = $linkRenderer->makeKnownLink(
				$undelete,
				$this->messages['diff'],
				[],
				[
					'target' => $page->getPrefixedText(),
					'timestamp' => $revRecord->getTimestamp(),
					'diff' => 'prev'
				]
			);
		} else {
			$last = htmlspecialchars( $this->messages['diff'] );
		}

		$comment = $row->ar_rev_id
			? $this->formattedComments[$row->ar_rev_id]
			: $this->commentFormatter->formatRevision( $revRecord, $user );
		$date = $this->getLanguage()->userTimeAndDate( $revRecord->getTimestamp(), $user );

		if ( !$this->getAuthority()->isAllowed( 'undelete' ) ||
			!$revRecord->userCan( RevisionRecord::DELETED_TEXT, $this->getAuthority() )
		) {
			$link = htmlspecialchars( $date ); // unusable link
		} else {
			$link = $linkRenderer->makeKnownLink(
				$undelete,
				$date,
				[ 'class' => 'mw-changeslist-date' ],
				[
					'target' => $page->getPrefixedText(),
					'timestamp' => $revRecord->getTimestamp()
				]
			);
		}
		// Style deleted items
		if ( $revRecord->isDeleted( RevisionRecord::DELETED_TEXT ) ) {
			$class = Linker::getRevisionDeletedClass( $revRecord );
			$link = '<span class="' . $class . '">' . $link . '</span>';
		}

		$pagelink = $linkRenderer->makeLink(
			$page,
			null,
			[ 'class' => 'mw-changeslist-title' ]
		);

		if ( $revRecord->isMinor() ) {
			$mflag = ChangesList::flag( 'minor' );
		} else {
			$mflag = '';
		}

		// Revision delete link
		$del = Linker::getRevDeleteLink( $user, $revRecord, $page );
		if ( $del ) {
			$del .= ' ';
		}

		$tools = Html::rawElement(
			'span',
			[ 'class' => 'mw-deletedcontribs-tools' ],
			$this->msg( 'parentheses' )->rawParams( $this->getLanguage()->pipeList(
				[ $last, $dellog, $reviewlink ] ) )->escaped()
		);

		// Tags, if any.
		[ $tagSummary, $classes ] = ChangeTags::formatSummaryRow(
			$row->ts_tags,
			'deletedcontributions',
			$this->getContext()
		);

		$separator = '<span class="mw-changeslist-separator">. .</span>';
		$ret = "{$del}{$link} {$tools} {$separator} {$mflag} {$pagelink} {$comment} {$tagSummary}";

		# Denote if username is redacted for this edit
		if ( $revRecord->isDeleted( RevisionRecord::DELETED_USER ) ) {
			$ret .= " <strong>" . $this->msg( 'rev-deleted-user-contribs' )->escaped() . "</strong>";
		}

		return [ $ret, $classes ];
	}
}
