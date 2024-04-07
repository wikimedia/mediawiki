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

use ChangesList;
use ChangeTags;
use DateTime;
use HtmlArmor;
use InvalidArgumentException;
use MapCacheLRU;
use MediaWiki\Cache\LinkBatchFactory;
use MediaWiki\CommentFormatter\CommentFormatter;
use MediaWiki\Config\Config;
use MediaWiki\Context\IContextSource;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\Html\Html;
use MediaWiki\Html\TemplateParser;
use MediaWiki\Linker\Linker;
use MediaWiki\Linker\LinkRenderer;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Parser\Sanitizer;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\RevisionStore;
use MediaWiki\Title\NamespaceInfo;
use MediaWiki\Title\Title;
use MediaWiki\User\UserIdentity;
use MediaWiki\User\UserRigorOptions;
use stdClass;
use Wikimedia\IPUtils;
use Wikimedia\Rdbms\FakeResultWrapper;
use Wikimedia\Rdbms\IConnectionProvider;
use Wikimedia\Rdbms\IReadableDatabase;
use Wikimedia\Rdbms\IResultWrapper;

/**
 * Pager for Special:Contributions
 * @ingroup Pager
 */
class ContribsPager extends RangeChronologicalPager {

	public $mGroupByDate = true;

	/**
	 * @var string[] Local cache for escaped messages
	 */
	private $messages;

	/**
	 * @var string User name, or a string describing an IP address range
	 */
	private $target;

	/**
	 * @var string|int A single namespace number, or an empty string for all namespaces
	 */
	private $namespace;

	/**
	 * @var string[]|false Name of tag to filter, or false to ignore tags
	 */
	private $tagFilter;

	/**
	 * @var bool Set to true to invert the tag selection
	 */
	private $tagInvert;

	/**
	 * @var bool Set to true to invert the namespace selection
	 */
	private $nsInvert;

	/**
	 * @var bool Set to true to show both the subject and talk namespace, no matter which got
	 *  selected
	 */
	private $associated;

	/**
	 * @var bool Set to true to show only deleted revisions
	 */
	private $deletedOnly;

	/**
	 * @var bool Set to true to show only latest (a.k.a. current) revisions
	 */
	private $topOnly;

	/**
	 * @var bool Set to true to show only new pages
	 */
	private $newOnly;

	/**
	 * @var bool Set to true to hide edits marked as minor by the user
	 */
	private $hideMinor;

	/**
	 * @var bool Set to true to only include mediawiki revisions.
	 * (restricts extensions from executing additional queries to include their own contributions)
	 */
	private $revisionsOnly;

	private $preventClickjacking = false;

	/**
	 * @var array
	 */
	private $mParentLens;

	/** @var UserIdentity */
	private $targetUser;

	private TemplateParser $templateParser;
	private CommentFormatter $commentFormatter;
	private HookRunner $hookRunner;
	private LinkBatchFactory $linkBatchFactory;
	private NamespaceInfo $namespaceInfo;
	private RevisionStore $revisionStore;

	/** @var string[] */
	private $formattedComments = [];

	/** @var RevisionRecord[] Cached revisions by ID */
	private $revisions = [];

	/** @var MapCacheLRU */
	private $tagsCache;

	/**
	 * FIXME List services first T266484 / T290405
	 * @param IContextSource $context
	 * @param array $options
	 * @param LinkRenderer|null $linkRenderer
	 * @param LinkBatchFactory|null $linkBatchFactory
	 * @param HookContainer|null $hookContainer
	 * @param IConnectionProvider|null $dbProvider
	 * @param RevisionStore|null $revisionStore
	 * @param NamespaceInfo|null $namespaceInfo
	 * @param UserIdentity|null $targetUser
	 * @param CommentFormatter|null $commentFormatter
	 */
	public function __construct(
		IContextSource $context,
		array $options,
		LinkRenderer $linkRenderer = null,
		LinkBatchFactory $linkBatchFactory = null,
		HookContainer $hookContainer = null,
		IConnectionProvider $dbProvider = null,
		RevisionStore $revisionStore = null,
		NamespaceInfo $namespaceInfo = null,
		UserIdentity $targetUser = null,
		CommentFormatter $commentFormatter = null
	) {
		// Class is used directly in extensions - T266484
		$services = MediaWikiServices::getInstance();
		$dbProvider ??= $services->getConnectionProvider();

		// Set ->target before calling parent::__construct() so
		// parent can call $this->getIndexField() and get the right result. Set
		// the rest too just to keep things simple.
		if ( $targetUser ) {
			$this->target = $options['target'] ?? $targetUser->getName();
			$this->targetUser = $targetUser;
		} else {
			// Use target option
			// It's possible for the target to be empty. This is used by
			// ContribsPagerTest and does not cause newFromName() to return
			// false. It's probably not used by any production code.
			$this->target = $options['target'] ?? '';
			// @phan-suppress-next-line PhanPossiblyNullTypeMismatchProperty RIGOR_NONE never returns null
			$this->targetUser = $services->getUserFactory()->newFromName(
				$this->target, UserRigorOptions::RIGOR_NONE
			);
			if ( !$this->targetUser ) {
				// This can happen if the target contained "#". Callers
				// typically pass user input through title normalization to
				// avoid it.
				throw new InvalidArgumentException( __METHOD__ . ': the user name is too ' .
					'broken to use even with validation disabled.' );
			}
		}

		$this->namespace = $options['namespace'] ?? '';
		$this->tagFilter = $options['tagfilter'] ?? false;
		$this->tagInvert = $options['tagInvert'] ?? false;
		$this->nsInvert = $options['nsInvert'] ?? false;
		$this->associated = $options['associated'] ?? false;

		$this->deletedOnly = !empty( $options['deletedOnly'] );
		$this->topOnly = !empty( $options['topOnly'] );
		$this->newOnly = !empty( $options['newOnly'] );
		$this->hideMinor = !empty( $options['hideMinor'] );
		$this->revisionsOnly = !empty( $options['revisionsOnly'] );

		parent::__construct( $context, $linkRenderer ?? $services->getLinkRenderer() );

		$msgs = [
			'diff',
			'hist',
			'pipe-separator',
			'uctop',
			'changeslist-nocomment',
		];

		foreach ( $msgs as $msg ) {
			$this->messages[$msg] = $this->msg( $msg )->escaped();
		}

		// Date filtering: use timestamp if available
		$startTimestamp = '';
		$endTimestamp = '';
		if ( isset( $options['start'] ) && $options['start'] ) {
			$startTimestamp = $options['start'] . ' 00:00:00';
		}
		if ( isset( $options['end'] ) && $options['end'] ) {
			$endTimestamp = $options['end'] . ' 23:59:59';
		}
		$this->getDateRangeCond( $startTimestamp, $endTimestamp );

		$this->templateParser = new TemplateParser();
		$this->linkBatchFactory = $linkBatchFactory ?? $services->getLinkBatchFactory();
		$this->hookRunner = new HookRunner( $hookContainer ?? $services->getHookContainer() );
		$this->revisionStore = $revisionStore ?? $services->getRevisionStore();
		$this->namespaceInfo = $namespaceInfo ?? $services->getNamespaceInfo();
		$this->commentFormatter = $commentFormatter ?? $services->getCommentFormatter();
		$this->tagsCache = new MapCacheLRU( 50 );
	}

	public function getDefaultQuery() {
		$query = parent::getDefaultQuery();
		$query['target'] = $this->target;

		return $query;
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
		[ $tables, $fields, $conds, $fname, $options, $join_conds ] = $this->buildQueryInfo(
			$offset,
			$limit,
			$order
		);

		$options['MAX_EXECUTION_TIME'] =
			$this->getConfig()->get( MainConfigNames::MaxExecutionTimeForExpensiveQueries );
		/*
		 * This hook will allow extensions to add in additional queries, so they can get their data
		 * in My Contributions as well. Extensions should append their results to the $data array.
		 *
		 * Extension queries have to implement the navbar requirement as well. They should
		 * - have a column aliased as $pager->getIndexField()
		 * - have LIMIT set
		 * - have a WHERE-clause that compares the $pager->getIndexField()-equivalent column to the offset
		 * - have the ORDER BY specified based upon the details provided by the navbar
		 *
		 * See includes/Pager.php buildQueryInfo() method on how to build LIMIT, WHERE & ORDER BY
		 *
		 * &$data: an array of results of all contribs queries
		 * $pager: the ContribsPager object hooked into
		 * $offset: see phpdoc above
		 * $limit: see phpdoc above
		 * $descending: see phpdoc above
		 */
		$dbr = $this->getDatabase();
		$data = [ $dbr->select(
			$tables, $fields, $conds, $fname, $options, $join_conds
		) ];
		if ( !$this->revisionsOnly ) {
			// TODO: Range offsets are fairly important and all handlers should take care of it.
			// If this hook will be replaced (e.g. unified with the DeletedContribsPager one),
			// please consider passing [ $this->endOffset, $this->startOffset ] to it (T167577).
			$this->hookRunner->onContribsPager__reallyDoQuery(
				$data, $this, $offset, $limit, $order );
		}

		$result = [];

		// loop all results and collect them in an array
		foreach ( $data as $query ) {
			foreach ( $query as $i => $row ) {
				// If the query results are in descending order, the indexes must also be in descending order
				$index = $order === self::QUERY_ASCENDING ? $i : $limit - 1 - $i;
				// Left-pad with zeroes, because these values will be sorted as strings
				$index = str_pad( (string)$index, strlen( (string)$limit ), '0', STR_PAD_LEFT );
				// use index column as key, allowing us to easily sort in PHP
				$result[$row->{$this->getIndexField()} . "-$index"] = $row;
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
	 * Return the table targeted for ordering and continuation
	 *
	 * See T200259 and T221380.
	 *
	 * @warning Keep this in sync with self::getQueryInfo()!
	 *
	 * @return string
	 */
	private function getTargetTable() {
		$dbr = $this->getDatabase();
		$ipRangeConds = $this->targetUser->isRegistered()
			? null : $this->getIpRangeConds( $dbr, $this->target );
		if ( $ipRangeConds ) {
			return 'ip_changes';
		}

		return 'revision';
	}

	public function getQueryInfo() {
		$revQuery = $this->revisionStore->getQueryInfo( [ 'page', 'user' ] );
		$queryInfo = [
			'tables' => $revQuery['tables'],
			'fields' => array_merge( $revQuery['fields'], [ 'page_is_new' ] ),
			'conds' => [],
			'options' => [],
			'join_conds' => $revQuery['joins'],
		];

		// WARNING: Keep this in sync with getTargetTable()!
		$dbr = $this->getDatabase();
		$ipRangeConds = !$this->targetUser->isRegistered() ? $this->getIpRangeConds( $dbr, $this->target ) : null;
		if ( $ipRangeConds ) {
			// Put ip_changes first (T284419)
			array_unshift( $queryInfo['tables'], 'ip_changes' );
			$queryInfo['join_conds']['revision'] = [
				'JOIN', [ 'rev_id = ipc_rev_id' ]
			];
			$queryInfo['conds'][] = $ipRangeConds;
		} else {
			$queryInfo['conds']['actor_name'] = $this->targetUser->getName();
			// Force the appropriate index to avoid bad query plans (T307295)
			$queryInfo['options']['USE INDEX']['revision'] = 'rev_actor_timestamp';
		}

		if ( $this->deletedOnly ) {
			$queryInfo['conds'][] = 'rev_deleted != 0';
		}

		if ( $this->topOnly ) {
			$queryInfo['conds'][] = 'rev_id = page_latest';
		}

		if ( $this->newOnly ) {
			$queryInfo['conds'][] = 'rev_parent_id = 0';
		}

		if ( $this->hideMinor ) {
			$queryInfo['conds'][] = 'rev_minor_edit = 0';
		}

		$queryInfo['conds'] = array_merge( $queryInfo['conds'], $this->getNamespaceCond() );

		// Paranoia: avoid brute force searches (T19342)
		if ( !$this->getAuthority()->isAllowed( 'deletedhistory' ) ) {
			$queryInfo['conds'][] = $dbr->bitAnd(
				'rev_deleted', RevisionRecord::DELETED_USER
				) . ' = 0';
		} elseif ( !$this->getAuthority()->isAllowedAny( 'suppressrevision', 'viewsuppressed' ) ) {
			$queryInfo['conds'][] = $dbr->bitAnd(
				'rev_deleted', RevisionRecord::SUPPRESSED_USER
				) . ' != ' . RevisionRecord::SUPPRESSED_USER;
		}

		// $this->getIndexField() must be in the result rows, as reallyDoQuery() tries to access it.
		$indexField = $this->getIndexField();
		if ( $indexField !== 'rev_timestamp' ) {
			$queryInfo['fields'][] = $indexField;
		}

		ChangeTags::modifyDisplayQuery(
			$queryInfo['tables'],
			$queryInfo['fields'],
			$queryInfo['conds'],
			$queryInfo['join_conds'],
			$queryInfo['options'],
			$this->tagFilter,
			$this->tagInvert,
		);

		$this->hookRunner->onContribsPager__getQueryInfo( $this, $queryInfo );

		return $queryInfo;
	}

	protected function getNamespaceCond() {
		if ( $this->namespace !== '' ) {
			$dbr = $this->getDatabase();
			$selectedNS = $dbr->addQuotes( $this->namespace );
			$eq_op = $this->nsInvert ? '!=' : '=';
			$bool_op = $this->nsInvert ? 'AND' : 'OR';

			if ( !$this->associated ) {
				return [ "page_namespace $eq_op $selectedNS" ];
			}

			$associatedNS = $dbr->addQuotes( $this->namespaceInfo->getAssociated( $this->namespace ) );

			return [
				"page_namespace $eq_op $selectedNS " .
				$bool_op .
				" page_namespace $eq_op $associatedNS"
			];
		}

		return [];
	}

	/**
	 * Get SQL conditions for an IP range, if applicable
	 * @param IReadableDatabase $db
	 * @param string $ip The IP address or CIDR
	 * @return string|false SQL for valid IP ranges, false if invalid
	 */
	private function getIpRangeConds( $db, $ip ) {
		// First make sure it is a valid range and they are not outside the CIDR limit
		if ( !self::isQueryableRange( $ip, $this->getConfig() ) ) {
			return false;
		}

		[ $start, $end ] = IPUtils::parseRange( $ip );

		return 'ipc_hex BETWEEN ' . $db->addQuotes( $start ) . ' AND ' . $db->addQuotes( $end );
	}

	/**
	 * Is the given IP a range and within the CIDR limit?
	 *
	 * @internal Public only for SpecialContributions
	 * @param string $ipRange
	 * @param Config $config
	 * @return bool True if it is valid
	 * @since 1.30
	 */
	public static function isQueryableRange( $ipRange, $config ) {
		$limits = $config->get( MainConfigNames::RangeContributionsCIDRLimit );

		$bits = IPUtils::parseCIDR( $ipRange )[1];
		if (
			( $bits === false ) ||
			( IPUtils::isIPv4( $ipRange ) && $bits < $limits['IPv4'] ) ||
			( IPUtils::isIPv6( $ipRange ) && $bits < $limits['IPv6'] )
		) {
			return false;
		}

		return true;
	}

	/**
	 * @return string
	 */
	public function getIndexField() {
		// The returned column is used for sorting and continuation, so we need to
		// make sure to use the right denormalized column depending on which table is
		// being targeted by the query to avoid bad query plans.
		// See T200259, T204669, T220991, and T221380.
		$target = $this->getTargetTable();
		switch ( $target ) {
			case 'revision':
				return 'rev_timestamp';
			case 'ip_changes':
				return 'ipc_rev_timestamp';
			default:
				wfWarn(
					__METHOD__ . ": Unknown value '$target' from " . static::class . '::getTargetTable()', 0
				);
				return 'rev_timestamp';
		}
	}

	/**
	 * @return false|string[]
	 */
	public function getTagFilter() {
		return $this->tagFilter;
	}

	/**
	 * @return string
	 */
	public function getTarget() {
		return $this->target;
	}

	/**
	 * @return bool
	 */
	public function isNewOnly() {
		return $this->newOnly;
	}

	/**
	 * @return int|string
	 */
	public function getNamespace() {
		return $this->namespace;
	}

	/**
	 * @return string[]
	 */
	protected function getExtraSortFields() {
		// The returned columns are used for sorting, so we need to make sure
		// to use the right denormalized column depending on which table is
		// being targeted by the query to avoid bad query plans.
		// See T200259, T204669, T220991, and T221380.
		$target = $this->getTargetTable();
		switch ( $target ) {
			case 'revision':
				return [ 'rev_id' ];
			case 'ip_changes':
				return [ 'ipc_rev_id' ];
			default:
				wfWarn(
					__METHOD__ . ": Unknown value '$target' from " . static::class . '::getTargetTable()', 0
				);
				return [ 'rev_id' ];
		}
	}

	protected function doBatchLookups() {
		# Do a link batch query
		$this->mResult->seek( 0 );
		$parentRevIds = [];
		$this->mParentLens = [];
		$revisions = [];
		$linkBatch = $this->linkBatchFactory->newLinkBatch();
		# Give some pointers to make (last) links
		foreach ( $this->mResult as $row ) {
			if ( isset( $row->rev_parent_id ) && $row->rev_parent_id ) {
				$parentRevIds[] = (int)$row->rev_parent_id;
			}
			if ( $this->revisionStore->isRevisionRow( $row ) ) {
				$this->mParentLens[(int)$row->rev_id] = $row->rev_len;
				if ( $this->target !== $row->rev_user_text ) {
					// If the target does not match the author, batch the author's talk page
					$linkBatch->add( NS_USER_TALK, $row->rev_user_text );
				}
				$linkBatch->add( $row->page_namespace, $row->page_title );
				$revisions[$row->rev_id] = $this->revisionStore->newRevisionFromRow( $row );
			}
		}
		# Fetch rev_len for revisions not already scanned above
		$this->mParentLens += $this->revisionStore->getRevisionSizes(
			array_diff( $parentRevIds, array_keys( $this->mParentLens ) )
		);
		$linkBatch->execute();

		$this->formattedComments = $this->commentFormatter->createRevisionBatch()
			->authority( $this->getAuthority() )
			->revisions( $revisions )
			->hideIfDeleted()
			->execute();

		# For performance, save the revision objects for later.
		# The array is indexed by rev_id. doBatchLookups() may be called
		# multiple times with different results, so merge the revisions array,
		# ignoring any duplicates.
		$this->revisions += $revisions;
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

	/**
	 * If the object looks like a revision row, or corresponds to a previously
	 * cached revision, return the RevisionRecord. Otherwise, return null.
	 *
	 * @since 1.35
	 *
	 * @param mixed $row
	 * @param Title|null $title
	 * @return RevisionRecord|null
	 */
	public function tryCreatingRevisionRecord( $row, $title = null ) {
		if ( $row instanceof stdClass && isset( $row->rev_id )
			&& isset( $this->revisions[$row->rev_id] )
		) {
			return $this->revisions[$row->rev_id];
		} elseif ( $this->revisionStore->isRevisionRow( $row ) ) {
			return $this->revisionStore->newRevisionFromRow( $row, 0, $title );
		} else {
			return null;
		}
	}

	/**
	 * Generates each row in the contributions list.
	 *
	 * Contributions which are marked "top" are currently on top of the history.
	 * For these contributions, a [rollback] link is shown for users with roll-
	 * back privileges. The rollback link restores the most recent version that
	 * was not written by the target user.
	 *
	 * @todo This would probably look a lot nicer in a table.
	 * @param stdClass|mixed $row
	 * @return string
	 */
	public function formatRow( $row ) {
		$ret = '';
		$classes = [];
		$attribs = [];

		$linkRenderer = $this->getLinkRenderer();

		$page = null;
		// Create a title for the revision if possible
		// Rows from the hook may not include title information
		if ( isset( $row->page_namespace ) && isset( $row->page_title ) ) {
			$page = Title::newFromRow( $row );
		}
		// Flow overrides the ContribsPager::reallyDoQuery hook, causing this
		// function to be called with a special object for $row. It expects us
		// skip formatting so that the row can be formatted by the
		// ContributionsLineEnding hook below.
		// FIXME: have some better way for extensions to provide formatted rows.
		$revRecord = $this->tryCreatingRevisionRecord( $row, $page );
		if ( $revRecord && $page ) {
			$revRecord = $this->revisionStore->newRevisionFromRow( $row, 0, $page );
			$attribs['data-mw-revid'] = $revRecord->getId();

			$link = $linkRenderer->makeLink(
				$page,
				$page->getPrefixedText(),
				[ 'class' => 'mw-contributions-title' ],
				$page->isRedirect() ? [ 'redirect' => 'no' ] : []
			);
			# Mark current revisions
			$topmarktext = '';

			$pagerTools = new PagerTools(
				$revRecord,
				null,
				$row->rev_id === $row->page_latest && !$row->page_is_new,
				$this->hookRunner,
				$page,
				$this->getContext(),
				$this->getLinkRenderer()
			);
			if ( $row->rev_id === $row->page_latest ) {
				$topmarktext .= '<span class="mw-uctop">' . $this->messages['uctop'] . '</span>';
				$classes[] = 'mw-contributions-current';
			}
			if ( $pagerTools->shouldPreventClickjacking() ) {
				$this->setPreventClickjacking( true );
			}
			$topmarktext .= $pagerTools->toHTML();
			# Is there a visible previous revision?
			if ( $revRecord->getParentId() !== 0 &&
				$revRecord->userCan( RevisionRecord::DELETED_TEXT, $this->getAuthority() )
			) {
				$difftext = $linkRenderer->makeKnownLink(
					$page,
					new HtmlArmor( $this->messages['diff'] ),
					[ 'class' => 'mw-changeslist-diff' ],
					[
						'diff' => 'prev',
						'oldid' => $row->rev_id
					]
				);
			} else {
				$difftext = $this->messages['diff'];
			}
			$histlink = $linkRenderer->makeKnownLink(
				$page,
				new HtmlArmor( $this->messages['hist'] ),
				[ 'class' => 'mw-changeslist-history' ],
				[ 'action' => 'history' ]
			);

			if ( $row->rev_parent_id === null ) {
				// For some reason rev_parent_id isn't populated for this row.
				// Its rumoured this is true on wikipedia for some revisions (T36922).
				// Next best thing is to have the total number of bytes.
				$chardiff = ' <span class="mw-changeslist-separator"></span> ';
				$chardiff .= Linker::formatRevisionSize( $row->rev_len );
				$chardiff .= ' <span class="mw-changeslist-separator"></span> ';
			} else {
				$parentLen = 0;
				if ( isset( $this->mParentLens[$row->rev_parent_id] ) ) {
					$parentLen = $this->mParentLens[$row->rev_parent_id];
				}

				$chardiff = ' <span class="mw-changeslist-separator"></span> ';
				$chardiff .= ChangesList::showCharacterDifference(
					$parentLen,
					$row->rev_len,
					$this->getContext()
				);
				$chardiff .= ' <span class="mw-changeslist-separator"></span> ';
			}

			$lang = $this->getLanguage();

			$comment = $this->formattedComments[$row->rev_id];

			if ( $comment === '' ) {
				$defaultComment = $this->messages['changeslist-nocomment'];
				$comment = "<span class=\"comment mw-comment-none\">$defaultComment</span>";
			}

			$comment = $lang->getDirMark() . $comment;

			$authority = $this->getAuthority();
			$d = ChangesList::revDateLink( $revRecord, $authority, $lang, $page );

			// When the author is different from the target, always show user and user talk links
			$userlink = '';
			$revUser = $revRecord->getUser();
			$revUserId = $revUser ? $revUser->getId() : 0;
			$revUserText = $revUser ? $revUser->getName() : '';
			if ( $this->target !== $revUserText ) {
				$userlink = ' <span class="mw-changeslist-separator"></span> '
					. $lang->getDirMark()
					. Linker::userLink( $revUserId, $revUserText );
				$userlink .= ' ' . $this->msg( 'parentheses' )->rawParams(
					Linker::userTalkLink( $revUserId, $revUserText ) )->escaped() . ' ';
			}

			$flags = [];
			if ( $revRecord->getParentId() === 0 ) {
				$flags[] = ChangesList::flag( 'newpage' );
			}

			if ( $revRecord->isMinor() ) {
				$flags[] = ChangesList::flag( 'minor' );
			}

			$del = Linker::getRevDeleteLink( $authority, $revRecord, $page );
			if ( $del !== '' ) {
				$del .= ' ';
			}

			// While it might be tempting to use a list here
			// this would result in clutter and slows down navigating the content
			// in assistive technology.
			// See https://phabricator.wikimedia.org/T205581#4734812
			$diffHistLinks = Html::rawElement( 'span',
				[ 'class' => 'mw-changeslist-links' ],
				// The spans are needed to ensure the dividing '|' elements are not
				// themselves styled as links.
				Html::rawElement( 'span', [], $difftext ) .
				' ' . // Space needed for separating two words.
				Html::rawElement( 'span', [], $histlink )
			);

			# Tags, if any. Save some time using a cache.
			[ $tagSummary, $newClasses ] = $this->tagsCache->getWithSetCallback(
				$this->tagsCache->makeKey(
					$row->ts_tags ?? '',
					$this->getUser()->getName(),
					$lang->getCode()
				),
				fn () => ChangeTags::formatSummaryRow(
					$row->ts_tags,
					null,
					$this->getContext()
				)
			);
			$classes = array_merge( $classes, $newClasses );

			$this->hookRunner->onSpecialContributions__formatRow__flags(
				$this->getContext(), $row, $flags );

			$templateParams = [
				'del' => $del,
				'timestamp' => $d,
				'diffHistLinks' => $diffHistLinks,
				'charDifference' => $chardiff,
				'flags' => $flags,
				'articleLink' => $link,
				'userlink' => $userlink,
				'logText' => $comment,
				'topmarktext' => $topmarktext,
				'tagSummary' => $tagSummary,
			];

			# Denote if username is redacted for this edit
			if ( $revRecord->isDeleted( RevisionRecord::DELETED_USER ) ) {
				$templateParams['rev-deleted-user-contribs'] =
					$this->msg( 'rev-deleted-user-contribs' )->escaped();
			}

			$ret = $this->templateParser->processTemplate(
				'SpecialContributionsLine',
				$templateParams
			);
		}

		// Let extensions add data
		$this->hookRunner->onContributionsLineEnding( $this, $ret, $row, $classes, $attribs );
		$attribs = array_filter( $attribs,
			[ Sanitizer::class, 'isReservedDataAttribute' ],
			ARRAY_FILTER_USE_KEY
		);

		// TODO: Handle exceptions in the catch block above.  Do any extensions rely on
		// receiving empty rows?

		if ( $classes === [] && $attribs === [] && $ret === '' ) {
			wfDebug( "Dropping Special:Contribution row that could not be formatted" );
			return "<!-- Could not format Special:Contribution row. -->\n";
		}
		$attribs['class'] = $classes;

		// FIXME: The signature of the ContributionsLineEnding hook makes it
		// very awkward to move this LI wrapper into the template.
		return Html::rawElement( 'li', $attribs, $ret ) . "\n";
	}

	/**
	 * Overwrite Pager function and return a helpful comment
	 * @return string
	 */
	protected function getSqlComment() {
		if ( $this->namespace || $this->deletedOnly ) {
			// potentially slow, see CR r58153
			return 'contributions page filtered for namespace or RevisionDeleted edits';
		} else {
			return 'contributions page unfiltered';
		}
	}

	/**
	 * @deprecated since 1.38, use ::setPreventClickjacking() instead
	 */
	protected function preventClickjacking() {
		$this->setPreventClickjacking( true );
	}

	/**
	 * @param bool $enable
	 * @since 1.38
	 */
	protected function setPreventClickjacking( bool $enable ) {
		$this->preventClickjacking = $enable;
	}

	/**
	 * @return bool
	 */
	public function getPreventClickjacking() {
		return $this->preventClickjacking;
	}

	/**
	 * Set up date filter options, given request data.
	 *
	 * @param array $opts Options array
	 * @return array Options array with processed start and end date filter options
	 */
	public static function processDateFilter( array $opts ) {
		$start = $opts['start'] ?? '';
		$end = $opts['end'] ?? '';
		$year = $opts['year'] ?? '';
		$month = $opts['month'] ?? '';

		if ( $start !== '' && $end !== '' && $start > $end ) {
			$temp = $start;
			$start = $end;
			$end = $temp;
		}

		// If year/month legacy filtering options are set, convert them to display the new stamp
		if ( $year !== '' || $month !== '' ) {
			// Reuse getDateCond logic, but subtract a day because
			// the endpoints of our date range appear inclusive
			// but the internal end offsets are always exclusive
			$legacyTimestamp = ReverseChronologicalPager::getOffsetDate( $year, $month );
			$legacyDateTime = new DateTime( $legacyTimestamp->getTimestamp( TS_ISO_8601 ) );
			$legacyDateTime = $legacyDateTime->modify( '-1 day' );

			// Clear the new timestamp range options if used and
			// replace with the converted legacy timestamp
			$start = '';
			$end = $legacyDateTime->format( 'Y-m-d' );
		}

		$opts['start'] = $start;
		$opts['end'] = $end;

		return $opts;
	}
}

/**
 * Retain the old class name for backwards compatibility.
 * @deprecated since 1.41
 */
class_alias( ContribsPager::class, 'ContribsPager' );
