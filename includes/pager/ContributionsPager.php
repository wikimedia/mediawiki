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
use HtmlArmor;
use InvalidArgumentException;
use MapCacheLRU;
use MediaWiki\Cache\LinkBatchFactory;
use MediaWiki\CommentFormatter\CommentFormatter;
use MediaWiki\Context\IContextSource;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\Html\Html;
use MediaWiki\Html\TemplateParser;
use MediaWiki\Linker\Linker;
use MediaWiki\Linker\LinkRenderer;
use MediaWiki\MainConfigNames;
use MediaWiki\Parser\Sanitizer;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\RevisionStore;
use MediaWiki\SpecialPage\SpecialPage;
use MediaWiki\Title\NamespaceInfo;
use MediaWiki\Title\Title;
use MediaWiki\User\UserFactory;
use MediaWiki\User\UserIdentity;
use MediaWiki\User\UserRigorOptions;
use stdClass;
use Wikimedia\Rdbms\FakeResultWrapper;
use Wikimedia\Rdbms\IResultWrapper;

/**
 * Pager for Special:Contributions
 * @ingroup Pager
 */
abstract class ContributionsPager extends RangeChronologicalPager {

	public $mGroupByDate = true;

	/**
	 * @var string[] Local cache for escaped messages
	 */
	private $messages;

	/**
	 * @var bool Get revisions from the archive table (if true) or the revision table (if false)
	 */
	protected $isArchive;

	/**
	 * @var string User name, or a string describing an IP address range
	 */
	protected $target;

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
	protected $targetUser;

	private TemplateParser $templateParser;
	private CommentFormatter $commentFormatter;
	private HookRunner $hookRunner;
	private LinkBatchFactory $linkBatchFactory;
	private NamespaceInfo $namespaceInfo;
	protected RevisionStore $revisionStore;

	/** @var string[] */
	private $formattedComments = [];

	/** @var RevisionRecord[] Cached revisions by ID */
	private $revisions = [];

	/** @var MapCacheLRU */
	private $tagsCache;

	/**
	 * Field names for various attributes. These may be overridden in a subclass,
	 * for example for getting revisions from the archive table.
	 */
	protected string $revisionIdField = 'rev_id';
	protected string $revisionParentIdField = 'rev_parent_id';
	protected string $revisionTimestampField = 'rev_timestamp';
	protected string $revisionLengthField = 'rev_len';
	protected string $revisionDeletedField = 'rev_deleted';
	protected string $revisionMinorField = 'rev_minor_edit';
	protected string $userNameField = 'rev_user_text';
	protected string $pageNamespaceField = 'page_namespace';
	protected string $pageTitleField = 'page_title';

	/**
	 * @param LinkRenderer $linkRenderer
	 * @param LinkBatchFactory $linkBatchFactory
	 * @param HookContainer $hookContainer
	 * @param RevisionStore $revisionStore
	 * @param NamespaceInfo $namespaceInfo
	 * @param CommentFormatter $commentFormatter
	 * @param UserFactory $userFactory
	 * @param IContextSource $context
	 * @param array $options
	 * @param UserIdentity|null $targetUser
	 */
	public function __construct(
		LinkRenderer $linkRenderer,
		LinkBatchFactory $linkBatchFactory,
		HookContainer $hookContainer,
		RevisionStore $revisionStore,
		NamespaceInfo $namespaceInfo,
		CommentFormatter $commentFormatter,
		UserFactory $userFactory,
		IContextSource $context,
		array $options,
		?UserIdentity $targetUser
	) {
		$this->isArchive = $options['isArchive'] ?? false;

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
			$this->targetUser = $userFactory->newFromName(
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

		parent::__construct( $context, $linkRenderer );

		$msgs = [
			'diff',
			'hist',
			'pipe-separator',
			'uctop',
			'changeslist-nocomment',
			'undeleteviewlink',
			'undeleteviewlink',
			'deletionlog',
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
		$this->linkBatchFactory = $linkBatchFactory;
		$this->hookRunner = new HookRunner( $hookContainer );
		$this->revisionStore = $revisionStore;
		$this->namespaceInfo = $namespaceInfo;
		$this->commentFormatter = $commentFormatter;
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
		$data = [ $dbr->newSelectQueryBuilder()
			->tables( is_array( $tables ) ? $tables : [ $tables ] )
			->fields( $fields )
			->conds( $conds )
			->caller( $fname )
			->options( $options )
			->joinConds( $join_conds )
			->setMaxExecutionTime( $this->getConfig()->get( MainConfigNames::MaxExecutionTimeForExpensiveQueries ) )
			->fetchResultSet() ];
		if ( !$this->revisionsOnly && !$this->isArchive ) {
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
	 * Get queryInfo for the main query selecting revisions, not including
	 * filtering on namespace, date, etc.
	 *
	 * @return array
	 */
	abstract protected function getRevisionQuery();

	public function getQueryInfo() {
		$queryInfo = $this->getRevisionQuery();

		if ( $this->deletedOnly ) {
			$queryInfo['conds'][] = $this->revisionDeletedField . ' != 0';
		}

		if ( $this->topOnly ) {
			$queryInfo['conds'][] = $this->revisionIdField . ' = page_latest';
		}

		if ( $this->newOnly ) {
			$queryInfo['conds'][] = $this->revisionParentIdField . ' = 0';
		}

		if ( $this->hideMinor ) {
			$queryInfo['conds'][] = $this->revisionMinorField . ' = 0';
		}

		$queryInfo['conds'] = array_merge( $queryInfo['conds'], $this->getNamespaceCond() );

		// Paranoia: avoid brute force searches (T19342)
		$dbr = $this->getDatabase();
		if ( !$this->getAuthority()->isAllowed( 'deletedhistory' ) ) {
			$queryInfo['conds'][] = $dbr->bitAnd(
				$this->revisionDeletedField, RevisionRecord::DELETED_USER
				) . ' = 0';
		} elseif ( !$this->getAuthority()->isAllowedAny( 'suppressrevision', 'viewsuppressed' ) ) {
			$queryInfo['conds'][] = $dbr->bitAnd(
				$this->revisionDeletedField, RevisionRecord::SUPPRESSED_USER
				) . ' != ' . RevisionRecord::SUPPRESSED_USER;
		}

		// $this->getIndexField() must be in the result rows, as reallyDoQuery() tries to access it.
		$indexField = $this->getIndexField();
		if ( $indexField !== $this->revisionTimestampField ) {
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

		if ( !$this->isArchive ) {
			$this->hookRunner->onContribsPager__getQueryInfo( $this, $queryInfo );
		}

		return $queryInfo;
	}

	protected function getNamespaceCond() {
		if ( $this->namespace !== '' ) {
			$dbr = $this->getDatabase();
			$namespaces = [ $this->namespace ];
			$eq_op = $this->nsInvert ? '!=' : '=';
			if ( $this->associated ) {
				$namespaces[] = $this->namespaceInfo->getAssociated( $this->namespace );
			}
			return [ $dbr->expr( $this->pageNamespaceField, $eq_op, $namespaces ) ];
		}

		return [];
	}

	/**
	 * @return false|string[]
	 */
	public function getTagFilter() {
		return $this->tagFilter;
	}

	/**
	 * @return bool
	 */
	public function getTagInvert() {
		return $this->tagInvert;
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

	protected function doBatchLookups() {
		# Do a link batch query
		$this->mResult->seek( 0 );
		$parentRevIds = [];
		$this->mParentLens = [];
		$revisions = [];
		$linkBatch = $this->linkBatchFactory->newLinkBatch();
		# Give some pointers to make (last) links
		foreach ( $this->mResult as $row ) {
			if ( isset( $row->{$this->revisionParentIdField} ) && $row->{$this->revisionParentIdField} ) {
				$parentRevIds[] = (int)$row->{$this->revisionParentIdField};
			}
			if ( $this->revisionStore->isRevisionRow( $row, $this->isArchive ? 'archive' : 'revision' ) ) {
				$this->mParentLens[(int)$row->{$this->revisionIdField}] = $row->{$this->revisionLengthField};
				if ( $this->target !== $row->{$this->userNameField} ) {
					// If the target does not match the author, batch the author's talk page
					$linkBatch->add( NS_USER_TALK, $row->{$this->userNameField} );
				}
				$linkBatch->add( $row->{$this->pageNamespaceField}, $row->{$this->pageTitleField} );
				$revisions[$row->{$this->revisionIdField}] = $this->createRevisionRecord( $row );
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
		if ( $row instanceof stdClass && isset( $row->{$this->revisionIdField} )
			&& isset( $this->revisions[$row->{$this->revisionIdField}] )
		) {
			return $this->revisions[$row->{$this->revisionIdField}];
		}

		if (
			$this->isArchive &&
			$this->revisionStore->isRevisionRow( $row, 'archive' )
		) {
			return $this->revisionStore->newRevisionFromArchiveRow( $row, 0, $title );
		}

		if (
			!$this->isArchive &&
			$this->revisionStore->isRevisionRow( $row )
		) {
			return $this->revisionStore->newRevisionFromRow( $row, 0, $title );
		}

		return null;
	}

	/**
	 * Create a revision record from a $row that models a revision.
	 *
	 * @param mixed $row
	 * @param Title|null $title
	 * @return RevisionRecord
	 */
	public function createRevisionRecord( $row, $title = null ) {
		if ( $this->isArchive ) {
			return $this->revisionStore->newRevisionFromArchiveRow( $row, 0, $title );
		}

		return $this->revisionStore->newRevisionFromRow( $row, 0, $title );
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
		if ( isset( $row->{$this->pageNamespaceField} ) && isset( $row->{$this->pageTitleField} ) ) {
			$page = Title::makeTitle( $row->{$this->pageNamespaceField}, $row->{$this->pageTitleField} );
		}

		// Flow overrides the ContribsPager::reallyDoQuery hook, causing this
		// function to be called with a special object for $row. It expects us
		// skip formatting so that the row can be formatted by the
		// ContributionsLineEnding hook below.
		// FIXME: have some better way for extensions to provide formatted rows.
		$revRecord = $this->tryCreatingRevisionRecord( $row, $page );
		if ( $revRecord && $page ) {
			$revRecord = $this->createRevisionRecord( $row, $page );
			$attribs['data-mw-revid'] = $revRecord->getId();

			$link = $linkRenderer->makeLink(
				$page,
				$page->getPrefixedText(),
				[ 'class' => 'mw-contributions-title' ],
				$page->isRedirect() ? [ 'redirect' => 'no' ] : []
			);
			# Mark current revisions
			$topmarktext = '';

			// Add links for seeing history, diff, etc.
			if ( $this->isArchive ) {
				// Add the same links as DeletedContribsPager::formatRevisionRow
				$undelete = SpecialPage::getTitleFor( 'Undelete' );
				if ( $this->getAuthority()->isAllowed( 'deletedtext' ) ) {
					$last = $linkRenderer->makeKnownLink(
						$undelete,
						new HtmlArmor( $this->messages['diff'] ),
						[],
						[
							'target' => $page->getPrefixedText(),
							'timestamp' => $revRecord->getTimestamp(),
							'diff' => 'prev'
						]
					);
				} else {
					$last = $this->messages['diff'];
				}

				$logs = SpecialPage::getTitleFor( 'Log' );
				$dellog = $linkRenderer->makeKnownLink(
					$logs,
					new HtmlArmor( $this->messages['deletionlog'] ),
					[],
					[
						'type' => 'delete',
						'page' => $page->getPrefixedText()
					]
				);

				$reviewlink = $linkRenderer->makeKnownLink(
					SpecialPage::getTitleFor( 'Undelete', $page->getPrefixedDBkey() ),
					new HtmlArmor( $this->messages['undeleteviewlink'] )
				);

				$diffHistLinks = Html::rawElement(
					'span',
					[ 'class' => 'mw-deletedcontribs-tools' ],
					$this->msg( 'parentheses' )->rawParams( $this->getLanguage()->pipeList(
						[ $last, $dellog, $reviewlink ] ) )->escaped()
				);

			} else {
				$pagerTools = new PagerTools(
					$revRecord,
					null,
					$row->{$this->revisionIdField} === $row->page_latest && !$row->page_is_new,
					$this->hookRunner,
					$page,
					$this->getContext(),
					$this->getLinkRenderer()
				);
				if ( $row->{$this->revisionIdField} === $row->page_latest ) {
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
							'oldid' => $row->{$this->revisionIdField},
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
			}

			if ( $row->{$this->revisionParentIdField} === null ) {
				// For some reason rev_parent_id isn't populated for this row.
				// Its rumoured this is true on wikipedia for some revisions (T36922).
				// Next best thing is to have the total number of bytes.
				$chardiff = ' <span class="mw-changeslist-separator"></span> ';
				$chardiff .= Linker::formatRevisionSize( $row->{$this->revisionLengthField} );
				$chardiff .= ' <span class="mw-changeslist-separator"></span> ';
			} else {
				$parentLen = 0;
				if ( isset( $this->mParentLens[$row->{$this->revisionParentIdField}] ) ) {
					$parentLen = $this->mParentLens[$row->{$this->revisionParentIdField}];
				}

				$chardiff = ' <span class="mw-changeslist-separator"></span> ';
				$chardiff .= ChangesList::showCharacterDifference(
					$parentLen,
					$row->{$this->revisionLengthField},
					$this->getContext()
				);
				$chardiff .= ' <span class="mw-changeslist-separator"></span> ';
			}

			$lang = $this->getLanguage();

			$comment = $this->formattedComments[$row->{$this->revisionIdField}];

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

			if ( !$this->isArchive ) {
				$this->hookRunner->onSpecialContributions__formatRow__flags(
					$this->getContext(), $row, $flags );
			}

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

		if ( !$this->isArchive ) {
			// Let extensions add data
			$this->hookRunner->onContributionsLineEnding( $this, $ret, $row, $classes, $attribs );
			$attribs = array_filter( $attribs,
				[ Sanitizer::class, 'isReservedDataAttribute' ],
				ARRAY_FILTER_USE_KEY
			);
		}

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

}
