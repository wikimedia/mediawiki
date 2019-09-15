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

/**
 * @ingroup Pager
 */
use MediaWiki\Linker\LinkRenderer;
use MediaWiki\MediaWikiServices;
use MediaWiki\Revision\RevisionRecord;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\IResultWrapper;
use Wikimedia\Rdbms\FakeResultWrapper;

class DeletedContribsPager extends IndexPager {

	/**
	 * @var bool Default direction for pager
	 */
	public $mDefaultDirection = IndexPager::DIR_DESCENDING;

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

	/**
	 * @var IDatabase
	 */
	public $mDb;

	/**
	 * @var string Navigation bar with paging links.
	 */
	protected $mNavigationBar;

	public function __construct( IContextSource $context, $target, $namespace = false,
		LinkRenderer $linkRenderer
	) {
		parent::__construct( $context, $linkRenderer );
		$msgs = [ 'deletionlog', 'undeleteviewlink', 'diff' ];
		foreach ( $msgs as $msg ) {
			$this->messages[$msg] = $this->msg( $msg )->text();
		}
		$this->target = $target;
		$this->namespace = $namespace;
		$this->mDb = wfGetDB( DB_REPLICA, 'contributions' );
	}

	function getDefaultQuery() {
		$query = parent::getDefaultQuery();
		$query['target'] = $this->target;

		return $query;
	}

	function getQueryInfo() {
		$userCond = [
			// ->getJoin() below takes care of any joins needed
			ActorMigration::newMigration()->getWhere(
				wfGetDB( DB_REPLICA ), 'ar_user', User::newFromName( $this->target, false ), false
			)['conds']
		];
		$conds = array_merge( $userCond, $this->getNamespaceCond() );
		$user = $this->getUser();
		$permissionManager = MediaWikiServices::getInstance()->getPermissionManager();
		// Paranoia: avoid brute force searches (T19792)
		if ( !$permissionManager->userHasRight( $user, 'deletedhistory' ) ) {
			$conds[] = $this->mDb->bitAnd( 'ar_deleted', RevisionRecord::DELETED_USER ) . ' = 0';
		} elseif ( !$permissionManager->userHasAnyRight( $user, 'suppressrevision', 'viewsuppressed' ) ) {
			$conds[] = $this->mDb->bitAnd( 'ar_deleted', RevisionRecord::SUPPRESSED_USER ) .
				' != ' . RevisionRecord::SUPPRESSED_USER;
		}

		$commentQuery = CommentStore::getStore()->getJoin( 'ar_comment' );
		$actorQuery = ActorMigration::newMigration()->getJoin( 'ar_user' );

		return [
			'tables' => [ 'archive' ] + $commentQuery['tables'] + $actorQuery['tables'],
			'fields' => [
				'ar_rev_id', 'ar_namespace', 'ar_title', 'ar_timestamp',
				'ar_minor_edit', 'ar_deleted'
			] + $commentQuery['fields'] + $actorQuery['fields'],
			'conds' => $conds,
			'options' => [],
			'join_conds' => $commentQuery['joins'] + $actorQuery['joins'],
		];
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
	function reallyDoQuery( $offset, $limit, $order ) {
		$data = [ parent::reallyDoQuery( $offset, $limit, $order ) ];

		// This hook will allow extensions to add in additional queries, nearly
		// identical to ContribsPager::reallyDoQuery.
		Hooks::run(
			'DeletedContribsPager::reallyDoQuery',
			[ &$data, $this, $offset, $limit, $order ]
		);

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

	function getIndexField() {
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

	protected function getStartBody() {
		return "<ul>\n";
	}

	protected function getEndBody() {
		return "</ul>\n";
	}

	function getNavigationBar() {
		if ( isset( $this->mNavigationBar ) ) {
			return $this->mNavigationBar;
		}

		$linkTexts = [
			'prev' => $this->msg( 'pager-newer-n' )->numParams( $this->mLimit )->escaped(),
			'next' => $this->msg( 'pager-older-n' )->numParams( $this->mLimit )->escaped(),
			'first' => $this->msg( 'histlast' )->escaped(),
			'last' => $this->msg( 'histfirst' )->escaped()
		];

		$pagingLinks = $this->getPagingLinks( $linkTexts );
		$limitLinks = $this->getLimitLinks();
		$lang = $this->getLanguage();
		$limits = $lang->pipeList( $limitLinks );

		$firstLast = $lang->pipeList( [ $pagingLinks['first'], $pagingLinks['last'] ] );
		$firstLast = $this->msg( 'parentheses' )->rawParams( $firstLast )->escaped();
		$prevNext = $this->msg( 'viewprevnext' )
			->rawParams(
				$pagingLinks['prev'],
				$pagingLinks['next'],
				$limits
			)->escaped();
		$separator = $this->msg( 'word-separator' )->escaped();
		$this->mNavigationBar = $firstLast . $separator . $prevNext;

		return $this->mNavigationBar;
	}

	function getNamespaceCond() {
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
	function formatRow( $row ) {
		$ret = '';
		$classes = [];
		$attribs = [];

		/*
		 * There may be more than just revision rows. To make sure that we'll only be processing
		 * revisions here, let's _try_ to build a revision out of our row (without displaying
		 * notices though) and then trying to grab data from the built object. If we succeed,
		 * we're definitely dealing with revision data and we may proceed, if not, we'll leave it
		 * to extensions to subscribe to the hook to parse the row.
		 */
		Wikimedia\suppressWarnings();
		try {
			$rev = Revision::newFromArchiveRow( $row );
			$validRevision = (bool)$rev->getId();
		} catch ( Exception $e ) {
			$validRevision = false;
		}
		Wikimedia\restoreWarnings();

		if ( $validRevision ) {
			$attribs['data-mw-revid'] = $rev->getId();
			$ret = $this->formatRevisionRow( $row );
		}

		// Let extensions add data
		Hooks::run( 'DeletedContributionsLineEnding', [ $this, &$ret, $row, &$classes, &$attribs ] );
		$attribs = array_filter( $attribs,
			[ Sanitizer::class, 'isReservedDataAttribute' ],
			ARRAY_FILTER_USE_KEY
		);

		if ( $classes === [] && $attribs === [] && $ret === '' ) {
			wfDebug( "Dropping Special:DeletedContribution row that could not be formatted\n" );
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
	 * @return string
	 */
	function formatRevisionRow( $row ) {
		$page = Title::makeTitle( $row->ar_namespace, $row->ar_title );

		$linkRenderer = $this->getLinkRenderer();

		$rev = new Revision( [
			'title' => $page,
			'id' => $row->ar_rev_id,
			'comment' => CommentStore::getStore()->getComment( 'ar_comment', $row )->text,
			'user' => $row->ar_user,
			'user_text' => $row->ar_user_text,
			'actor' => $row->ar_actor ?? null,
			'timestamp' => $row->ar_timestamp,
			'minor_edit' => $row->ar_minor_edit,
			'deleted' => $row->ar_deleted,
		] );

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
		$permissionManager = MediaWikiServices::getInstance()->getPermissionManager();

		if ( $permissionManager->userHasRight( $user, 'deletedtext' ) ) {
			$last = $linkRenderer->makeKnownLink(
				$undelete,
				$this->messages['diff'],
				[],
				[
					'target' => $page->getPrefixedText(),
					'timestamp' => $rev->getTimestamp(),
					'diff' => 'prev'
				]
			);
		} else {
			$last = htmlspecialchars( $this->messages['diff'] );
		}

		$comment = Linker::revComment( $rev );
		$date = $this->getLanguage()->userTimeAndDate( $rev->getTimestamp(), $user );

		if ( !$permissionManager->userHasRight( $user, 'undelete' ) ||
			 !$rev->userCan( RevisionRecord::DELETED_TEXT, $user )
		) {
			$link = htmlspecialchars( $date ); // unusable link
		} else {
			$link = $linkRenderer->makeKnownLink(
				$undelete,
				$date,
				[ 'class' => 'mw-changeslist-date' ],
				[
					'target' => $page->getPrefixedText(),
					'timestamp' => $rev->getTimestamp()
				]
			);
		}
		// Style deleted items
		if ( $rev->isDeleted( RevisionRecord::DELETED_TEXT ) ) {
			$link = '<span class="history-deleted">' . $link . '</span>';
		}

		$pagelink = $linkRenderer->makeLink(
			$page,
			null,
			[ 'class' => 'mw-changeslist-title' ]
		);

		if ( $rev->isMinor() ) {
			$mflag = ChangesList::flag( 'minor' );
		} else {
			$mflag = '';
		}

		// Revision delete link
		$del = Linker::getRevDeleteLink( $user, $rev, $page );
		if ( $del ) {
			$del .= ' ';
		}

		$tools = Html::rawElement(
			'span',
			[ 'class' => 'mw-deletedcontribs-tools' ],
			$this->msg( 'parentheses' )->rawParams( $this->getLanguage()->pipeList(
				[ $last, $dellog, $reviewlink ] ) )->escaped()
		);

		$separator = '<span class="mw-changeslist-separator">. .</span>';
		$ret = "{$del}{$link} {$tools} {$separator} {$mflag} {$pagelink} {$comment}";

		# Denote if username is redacted for this edit
		if ( $rev->isDeleted( RevisionRecord::DELETED_USER ) ) {
			$ret .= " <strong>" . $this->msg( 'rev-deleted-user-contribs' )->escaped() . "</strong>";
		}

		return $ret;
	}

	/**
	 * Get the Database object in use
	 *
	 * @return IDatabase
	 */
	public function getDatabase() {
		return $this->mDb;
	}
}
