<?php
/**
 * @license GPL-2.0-or-later
 * @file
 * @ingroup Pager
 */

namespace MediaWiki\Pager;

use InvalidArgumentException;
use MediaWiki\Cache\LinkBatchFactory;
use MediaWiki\ChangeTags\ChangeTags;
use MediaWiki\CommentFormatter\CommentFormatter;
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
use MediaWiki\RecentChanges\ChangesList;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\RevisionStore;
use MediaWiki\SpecialPage\SpecialPage;
use MediaWiki\Title\NamespaceInfo;
use MediaWiki\Title\Title;
use MediaWiki\User\UserFactory;
use MediaWiki\User\UserIdentity;
use MediaWiki\User\UserRigorOptions;
use stdClass;
use Wikimedia\HtmlArmor\HtmlArmor;
use Wikimedia\MapCacheLRU\MapCacheLRU;
use Wikimedia\Rdbms\FakeResultWrapper;
use Wikimedia\Rdbms\IResultWrapper;

/**
 * Pager for Special:Contributions
 * @ingroup Pager
 */
abstract class ContributionsPager extends RangeChronologicalPager {

	/** @inheritDoc */
	public $mGroupByDate = true;

	/**
	 * @var string[] Local cache for escaped messages
	 */
	protected $messages;

	/**
	 * @var bool Get revisions from the archive table (if true) or the revision table (if false)
	 */
	protected $isArchive;

	/**
	 * @var bool Run hooks to allow extensions to modify the page
	 */
	protected $runHooks;

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
	private bool $tagInvert;

	/**
	 * @var bool Set to true to invert the namespace selection
	 */
	private bool $nsInvert;

	/**
	 * @var bool Set to true to show both the subject and talk namespace, no matter which got
	 *  selected
	 */
	private bool $associated;

	/**
	 * @var bool Set to true to show only deleted revisions
	 */
	private bool $deletedOnly;

	/**
	 * @var bool Set to true to show only latest (a.k.a. current) revisions
	 */
	private bool $topOnly;

	/**
	 * @var bool Set to true to show only new pages
	 */
	private bool $newOnly;

	/**
	 * @var bool Set to true to hide edits marked as minor by the user
	 */
	private bool $hideMinor;

	/**
	 * @var bool Set to true to only include mediawiki revisions.
	 * (restricts extensions from executing additional queries to include their own contributions)
	 */
	private bool $revisionsOnly;

	/** @var bool */
	private $preventClickjacking = false;

	protected ?Title $currentPage;
	protected ?RevisionRecord $currentRevRecord;

	/**
	 * @var array
	 */
	private $mParentLens;

	/** @var UserIdentity */
	protected $targetUser;

	/**
	 * Set to protected to allow subclasses access for overrides
	 */
	protected TemplateParser $templateParser;

	private CommentFormatter $commentFormatter;
	private HookRunner $hookRunner;
	private LinkBatchFactory $linkBatchFactory;
	protected NamespaceInfo $namespaceInfo;
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
		$this->runHooks = $options['runHooks'] ?? true;

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

	/** @inheritDoc */
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
		if ( !$this->revisionsOnly && $this->runHooks ) {
			// These hooks were moved from ContribsPager and DeletedContribsPager. For backwards
			// compatability, they keep the same names. But they should be run for any contributions
			// pager, otherwise the entries from extensions would be missing.
			$reallyDoQueryHook = $this->isArchive ?
				'onDeletedContribsPager__reallyDoQuery' :
				'onContribsPager__reallyDoQuery';
			// TODO: Range offsets are fairly important and all handlers should take care of it.
			// If this hook will be replaced (e.g. unified with the DeletedContribsPager one),
			// please consider passing [ $this->endOffset, $this->startOffset ] to it (T167577).
			$this->hookRunner->$reallyDoQueryHook( $data, $this, $offset, $limit, $order );
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
				$indexFieldValues = array_map(
					static fn ( $fieldName ) => $row->$fieldName,
					(array)$this->mIndexField
				);
				$result[implode( '-', $indexFieldValues ) . "-$index"] = $row;
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

	/** @inheritDoc */
	public function getQueryInfo() {
		$queryInfo = $this->getRevisionQuery();

		if ( $this->deletedOnly ) {
			$queryInfo['conds'][] = $this->revisionDeletedField . ' != 0';
		}

		if ( !$this->isArchive && $this->topOnly ) {
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

		// Index fields must be present in the result rows, as reallyDoQuery() tries to access them.
		$indexFields = array_diff(
			(array)$this->mIndexField,
			$queryInfo['fields']
		);

		foreach ( $indexFields as $indexField ) {
			// Skip if already added as an alias
			if ( !array_key_exists( $indexField, $queryInfo['fields'] ) ) {
				$queryInfo['fields'][] = $indexField;
			}
		}

		MediaWikiServices::getInstance()->getChangeTagsStore()->modifyDisplayQuery(
			$queryInfo['tables'],
			$queryInfo['fields'],
			$queryInfo['conds'],
			$queryInfo['join_conds'],
			$queryInfo['options'],
			$this->tagFilter,
			$this->tagInvert,
		);

		if ( !$this->isArchive && $this->runHooks ) {
			$this->hookRunner->onContribsPager__getQueryInfo( $this, $queryInfo );
		}

		return $queryInfo;
	}

	protected function getNamespaceCond(): array {
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

	/**
	 * Whether the pager has any filters applied, ignoring whether the target username / IP
	 * for this check.
	 *
	 * Used to determine whether the current search may produce results if the
	 * filters were changed for UI messages when no results are displayed.
	 *
	 * Currently does not support checking if filters added via the
	 * onSpecialContributions__getForm__filters hook are applied to the current query.
	 *
	 * @return bool
	 */
	public function hasAppliedFilters(): bool {
		return $this->startOffset || $this->endOffset || $this->namespace !== '' || $this->tagFilter ||
			$this->deletedOnly || $this->newOnly || $this->hideMinor || $this->topOnly;
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
			$revisionRecord = $this->tryCreatingRevisionRecord( $row );
			if ( !$revisionRecord ) {
				continue;
			}
			if ( isset( $row->{$this->revisionParentIdField} ) && $row->{$this->revisionParentIdField} ) {
				$parentRevIds[] = (int)$row->{$this->revisionParentIdField};
			}
			$this->mParentLens[(int)$row->{$this->revisionIdField}] = $row->{$this->revisionLengthField};
			if ( $this->target !== $row->{$this->userNameField} ) {
				// If the target does not match the author, batch the author's talk page
				$linkBatch->add( NS_USER_TALK, $row->{$this->userNameField} );
			}
			$linkBatch->add( $row->{$this->pageNamespaceField}, $row->{$this->pageTitleField} );
			$revisions[$row->{$this->revisionIdField}] = $this->createRevisionRecord( $row );
		}
		// Fetch rev_len/ar_len for revisions not already scanned above
		// TODO: is it possible to make this fully abstract?
		if ( $this->isArchive ) {
			$parentRevIds = array_diff( $parentRevIds, array_keys( $this->mParentLens ) );
			if ( $parentRevIds ) {
				$result = $this->revisionStore
					->newArchiveSelectQueryBuilder( $this->getDatabase() )
					->clearFields()
					->fields( [ $this->revisionIdField, $this->revisionLengthField ] )
					->where( [ $this->revisionIdField => $parentRevIds ] )
					->caller( __METHOD__ )
					->fetchResultSet();
				foreach ( $result as $row ) {
					$this->mParentLens[(int)$row->{$this->revisionIdField}] = $row->{$this->revisionLengthField};
				}
			}
		}
		$this->mParentLens += $this->revisionStore->getRevisionSizes(
			array_diff( $parentRevIds, array_keys( $this->mParentLens ) )
		);
		$linkBatch->execute();

		$revisionBatch = $this->commentFormatter->createRevisionBatch()
			->authority( $this->getAuthority() )
			->revisions( $revisions );

		if ( !$this->isArchive ) {
			// Only show public comments, because this page might be public
			$revisionBatch = $revisionBatch->hideIfDeleted();
		}

		$this->formattedComments = $revisionBatch->execute();

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
	 * @inheritDoc
	 */
	protected function getEmptyBody() {
		return $this->msg( 'nocontribs' )->parse();
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
	 * Populate the HTML attributes.
	 *
	 * @param mixed $row
	 * @param string[] &$attributes
	 */
	protected function populateAttributes( $row, &$attributes ) {
		$attributes['data-mw-revid'] = $this->currentRevRecord->getId();
	}

	/**
	 * Format a link to an article.
	 *
	 * @param mixed $row
	 * @return string
	 */
	protected function formatArticleLink( $row ) {
		if ( !$this->currentPage ) {
			return '';
		}
		$dir = $this->getLanguage()->getDir();
		return Html::rawElement( 'bdi', [ 'dir' => $dir ], $this->getLinkRenderer()->makeLink(
			$this->currentPage,
			$this->currentPage->getPrefixedText(),
			[ 'class' => 'mw-contributions-title' ],
			$this->currentPage->isRedirect() ? [ 'redirect' => 'no' ] : []
		) );
	}

	/**
	 * Format diff and history links.
	 *
	 * @param mixed $row
	 * @return string
	 */
	protected function formatDiffHistLinks( $row ) {
		if ( !$this->currentPage || !$this->currentRevRecord ) {
			return '';
		}
		if ( $this->isArchive ) {
			// Add the same links as DeletedContribsPager::formatRevisionRow
			$undelete = SpecialPage::getTitleFor( 'Undelete' );
			if ( $this->getAuthority()->isAllowed( 'deletedtext' ) ) {
				$last = $this->getLinkRenderer()->makeKnownLink(
					$undelete,
					new HtmlArmor( $this->messages['diff'] ),
					[],
					[
						'target' => $this->currentPage->getPrefixedText(),
						'timestamp' => $this->currentRevRecord->getTimestamp(),
						'diff' => 'prev'
					]
				);
			} else {
				$last = $this->messages['diff'];
			}

			$logs = SpecialPage::getTitleFor( 'Log' );
			$dellog = $this->getLinkRenderer()->makeKnownLink(
				$logs,
				new HtmlArmor( $this->messages['deletionlog'] ),
				[],
				[
					'type' => 'delete',
					'page' => $this->currentPage->getPrefixedText()
				]
			);

			$reviewlink = $this->getLinkRenderer()->makeKnownLink(
				SpecialPage::getTitleFor( 'Undelete', $this->currentPage->getPrefixedDBkey() ),
				new HtmlArmor( $this->messages['undeleteviewlink'] )
			);

			return Html::rawElement(
				'span',
				[ 'class' => 'mw-deletedcontribs-tools' ],
				$this->msg( 'parentheses' )->rawParams( $this->getLanguage()->pipeList(
					[ $last, $dellog, $reviewlink ] ) )->escaped()
			);
		} else {
			# Is there a visible previous revision?
			if ( $this->currentRevRecord->getParentId() !== 0 &&
				$this->currentRevRecord->userCan( RevisionRecord::DELETED_TEXT, $this->getAuthority() )
			) {
				$difftext = $this->getLinkRenderer()->makeKnownLink(
					$this->currentPage,
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
			$histlink = $this->getLinkRenderer()->makeKnownLink(
				$this->currentPage,
				new HtmlArmor( $this->messages['hist'] ),
				[ 'class' => 'mw-changeslist-history' ],
				[ 'action' => 'history' ]
			);

			// While it might be tempting to use a list here
			// this would result in clutter and slows down navigating the content
			// in assistive technology.
			// See https://phabricator.wikimedia.org/T205581#4734812
			return Html::rawElement( 'span',
				[ 'class' => 'mw-changeslist-links' ],
				// The spans are needed to ensure the dividing '|' elements are not
				// themselves styled as links.
				Html::rawElement( 'span', [], $difftext ) .
				' ' . // Space needed for separating two words.
				Html::rawElement( 'span', [], $histlink )
			);
		}
	}

	/**
	 * Format a date link.
	 *
	 * @param mixed $row
	 * @return string
	 */
	protected function formatDateLink( $row ) {
		if ( !$this->currentPage || !$this->currentRevRecord ) {
			return '';
		}
		if ( $this->isArchive ) {
			$date = $this->getLanguage()->userTimeAndDate(
				$this->currentRevRecord->getTimestamp(),
				$this->getUser()
			);

			if ( $this->getAuthority()->isAllowed( 'undelete' ) &&
				$this->currentRevRecord->userCan( RevisionRecord::DELETED_TEXT, $this->getAuthority() )
			) {
				$dateLink = $this->getLinkRenderer()->makeKnownLink(
					SpecialPage::getTitleFor( 'Undelete' ),
					$date,
					[ 'class' => 'mw-changeslist-date' ],
					[
						'target' => $this->currentPage->getPrefixedText(),
						'timestamp' => $this->currentRevRecord->getTimestamp()
					]
				);
			} else {
				$dateLink = htmlspecialchars( $date );
			}
			if ( $this->currentRevRecord->isDeleted( RevisionRecord::DELETED_TEXT ) ) {
				$class = Linker::getRevisionDeletedClass( $this->currentRevRecord );
				$dateLink = Html::rawElement(
					'span',
					[ 'class' => $class ],
					$dateLink
				);
			}
		} else {
			$dateLink = ChangesList::revDateLink(
				$this->currentRevRecord,
				$this->getAuthority(),
				$this->getLanguage(),
				$this->currentPage
			);
		}
		return $dateLink;
	}

	/**
	 * Format annotation and add extra class if a row represents a latest revision.
	 *
	 * @param mixed $row
	 * @param string[] &$classes
	 * @return string
	 */
	protected function formatTopMarkText( $row, &$classes ) {
		if ( !$this->currentPage || !$this->currentRevRecord ) {
			return '';
		}
		$topmarktext = '';
		if ( !$this->isArchive ) {
			$pagerTools = new PagerTools(
				$this->currentRevRecord,
				null,
				$row->{$this->revisionIdField} === $row->page_latest && !$row->page_is_new,
				$this->hookRunner,
				$this->currentPage,
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
		}
		return $topmarktext;
	}

	/**
	 * Format annotation to show the size of a diff.
	 *
	 * @param mixed $row
	 * @return string
	 */
	protected function formatCharDiff( $row ) {
		if ( $row->{$this->revisionParentIdField} === null ) {
			// For some reason rev_parent_id isn't populated for this row.
			// Its rumoured this is true on wikipedia for some revisions (T36922).
			// Next best thing is to have the total number of bytes.
			$chardiff = ' <span class="mw-changeslist-separator"></span> ';
			$chardiff .= Linker::formatRevisionSize( $row->{$this->revisionLengthField} );
			$chardiff .= ' <span class="mw-changeslist-separator"></span> ';
		} else {
			$parentLen = $this->getParentRevisionSize( $row );

			$chardiff = ' <span class="mw-changeslist-separator"></span> ';
			$chardiff .= ChangesList::showCharacterDifference(
				$parentLen,
				$row->{$this->revisionLengthField},
				$this->getContext()
			);
			$chardiff .= ' <span class="mw-changeslist-separator"></span> ';
		}
		return $chardiff;
	}

	/**
	 * Get the byte length of the parent revision of a given row.
	 * @param stdClass $row
	 * @return int
	 */
	protected function getParentRevisionSize( $row ): int {
		return $this->mParentLens[$row->{$this->revisionParentIdField}] ?? 0;
	}

	/**
	 * Format a comment for a revision.
	 *
	 * @param mixed $row
	 * @return string
	 */
	protected function formatComment( $row ) {
		$comment = $this->formattedComments[$row->{$this->revisionIdField}];

		if ( $comment === '' ) {
			$defaultComment = $this->messages['changeslist-nocomment'];
			$comment = "<span class=\"comment mw-comment-none\">$defaultComment</span>";
		}

		// Don't wrap result of this with <bdi> or any other element, see T377555
		return $comment;
	}

	/**
	 * Format a user link.
	 *
	 * @param mixed $row
	 * @return string
	 */
	protected function formatUserLink( $row ) {
		if ( !$this->currentRevRecord ) {
			return '';
		}
		$dir = $this->getLanguage()->getDir();

		// When the author is different from the target, always show user and user talk links
		$userlink = '';
		$revUser = $this->currentRevRecord->getUser();
		$revUserId = $revUser ? $revUser->getId() : 0;
		$revUserText = $revUser ? $revUser->getName() : '';
		if ( $this->target !== $revUserText ) {
			$userPageLink = Linker::userLink( $revUserId, $revUserText );
			$userTalkLink = Linker::userTalkLink( $revUserId, $revUserText );

			$userlink = ' <span class="mw-changeslist-separator"></span> ' .
				Html::rawElement( 'bdi', [ 'dir' => $dir ], $userPageLink ) .
				Linker::renderUserToolLinksArray( [ $userTalkLink ], false );
		}

		return $userlink;
	}

	/**
	 * @param mixed $row
	 * @return string[]
	 */
	protected function formatFlags( $row ) {
		if ( !$this->currentRevRecord ) {
			return [];
		}
		$flags = [];
		if ( $this->currentRevRecord->getParentId() === 0 ) {
			$flags[] = ChangesList::flag( 'newpage' );
		}

		if ( $this->currentRevRecord->isMinor() ) {
			$flags[] = ChangesList::flag( 'minor' );
		}
		return $flags;
	}

	/**
	 * Format link for changing visibility.
	 *
	 * @param mixed $row
	 * @return string
	 */
	protected function formatVisibilityLink( $row ) {
		if ( !$this->currentPage || !$this->currentRevRecord ) {
			return '';
		}
		$del = Linker::getRevDeleteLink(
			$this->getAuthority(),
			$this->currentRevRecord,
			$this->currentPage
		);
		if ( $del !== '' ) {
			$del .= ' ';
		}
		return $del;
	}

	/**
	 * @param mixed $row
	 * @param string[] &$classes
	 * @return string
	 */
	protected function formatTags( $row, &$classes ) {
		# Tags, if any. Save some time using a cache.
		[ $tagSummary, $newClasses ] = $this->tagsCache->getWithSetCallback(
			$this->tagsCache->makeKey(
				$row->ts_tags ?? '',
				$this->getUser()->getName(),
				$this->getLanguage()->getCode()
			),
			fn () => ChangeTags::formatSummaryRow(
				$row->ts_tags,
				null,
				$this->getContext()
			)
		);
		$classes = array_merge( $classes, $newClasses );
		return $tagSummary;
	}

	/**
	 * Check whether the revision author is deleted
	 *
	 * @param mixed $row
	 * @return bool
	 */
	public function revisionUserIsDeleted( $row ) {
		return $this->currentRevRecord->isDeleted( RevisionRecord::DELETED_USER );
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

		$this->currentPage = null;
		$this->currentRevRecord = null;

		// Create a title for the revision if possible
		// Rows from the hook may not include title information
		if ( isset( $row->{$this->pageNamespaceField} ) && isset( $row->{$this->pageTitleField} ) ) {
			$this->currentPage = Title::makeTitle( $row->{$this->pageNamespaceField}, $row->{$this->pageTitleField} );
		}

		// Flow overrides the ContribsPager::reallyDoQuery hook, causing this
		// function to be called with a special object for $row. It expects us
		// skip formatting so that the row can be formatted by the
		// ContributionsLineEnding hook below.
		// FIXME: have some better way for extensions to provide formatted rows.
		$this->currentRevRecord = $this->tryCreatingRevisionRecord( $row, $this->currentPage );
		if ( $this->revisionsOnly || ( $this->currentRevRecord && $this->currentPage ) ) {
			$this->populateAttributes( $row, $attribs );

			$templateParams = $this->getTemplateParams( $row, $classes );
			$ret = $this->getProcessedTemplate( $templateParams );
		}

		if ( $this->runHooks ) {
			// Let extensions add data
			$lineEndingsHook = $this->isArchive ?
				'onDeletedContributionsLineEnding' :
				'onContributionsLineEnding';
			$this->hookRunner->$lineEndingsHook( $this, $ret, $row, $classes, $attribs );
		}

		$attribs = array_filter( $attribs,
			[ Sanitizer::class, 'isReservedDataAttribute' ],
			ARRAY_FILTER_USE_KEY
		);

		// TODO: Handle exceptions in the catch block above.  Do any extensions rely on
		// receiving empty rows?

		if ( $classes === [] && $attribs === [] && $ret === '' ) {
			wfDebug( "Dropping ContributionsSpecialPage row that could not be formatted" );
			return "<!-- Could not format ContributionsSpecialPage row. -->\n";
		}
		$attribs['class'] = $classes;

		// FIXME: The signature of the ContributionsLineEnding hook makes it
		// very awkward to move this LI wrapper into the template.
		return Html::rawElement( 'li', $attribs, $ret ) . "\n";
	}

	/**
	 * Generate array of template parameters to pass to the template for rendering.
	 * Function can be overriden by classes to add/remove their own parameters.
	 *
	 * @since 1.43
	 *
	 * @param stdClass|mixed $row
	 * @param string[] &$classes
	 * @return mixed[]
	 */
	public function getTemplateParams( $row, &$classes ) {
		$link = $this->formatArticleLink( $row );
		$topmarktext = $this->formatTopMarkText( $row, $classes );
		$diffHistLinks = $this->formatDiffHistLinks( $row );
		$dateLink = $this->formatDateLink( $row );
		$chardiff = $this->formatCharDiff( $row );
		$comment = $this->formatComment( $row );
		$userlink = $this->formatUserLink( $row );
		$flags = $this->formatFlags( $row );
		$del = $this->formatVisibilityLink( $row );
		$tagSummary = $this->formatTags( $row, $classes );

		if ( !$this->isArchive && $this->runHooks ) {
			$this->hookRunner->onSpecialContributions__formatRow__flags(
				$this->getContext(), $row, $flags );
		}

		$templateParams = [
			'del' => $del,
			'timestamp' => $dateLink,
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
		if ( $this->revisionUserIsDeleted( $row ) ) {
			$templateParams['rev-deleted-user-contribs'] =
				$this->msg( 'rev-deleted-user-contribs' )->escaped();
		}

		return $templateParams;
	}

	/**
	 * Return the processed template. Function can be overriden by classes
	 * to provide their own template parser.
	 *
	 * @since 1.43
	 *
	 * @param string[] $templateParams
	 * @return string
	 */
	public function getProcessedTemplate( $templateParams ) {
		return $this->templateParser->processTemplate(
			'SpecialContributionsLine',
			$templateParams
		);
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
