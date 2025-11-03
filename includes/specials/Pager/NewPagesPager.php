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
use MediaWiki\CommentFormatter\RowCommentFormatter;
use MediaWiki\Content\IContentHandlerFactory;
use MediaWiki\Context\IContextSource;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\Html\FormOptions;
use MediaWiki\Html\Html;
use MediaWiki\Linker\Linker;
use MediaWiki\Linker\LinkRenderer;
use MediaWiki\Parser\Sanitizer;
use MediaWiki\Permissions\GroupPermissionsLookup;
use MediaWiki\RecentChanges\ChangesList;
use MediaWiki\RecentChanges\RecentChange;
use MediaWiki\Revision\MutableRevisionRecord;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Title\NamespaceInfo;
use MediaWiki\Title\Title;
use MediaWiki\User\TempUser\TempUserConfig;
use MediaWiki\User\UserIdentityValue;
use stdClass;
use Wikimedia\MapCacheLRU\MapCacheLRU;
use Wikimedia\Rdbms\IExpression;

/**
 * @internal For use by SpecialNewPages
 * @ingroup RecentChanges
 * @ingroup Pager
 */
class NewPagesPager extends ReverseChronologicalPager {

	protected FormOptions $opts;
	protected MapCacheLRU $tagsCache;

	/** @var string[] */
	private array $formattedComments = [];
	/** @var bool Whether to group items by date by default this is disabled, but eventually the intention
	 * should be to default to true once all pages have been transitioned to support date grouping.
	 */
	public $mGroupByDate = true;

	private GroupPermissionsLookup $groupPermissionsLookup;
	private HookRunner $hookRunner;
	private LinkBatchFactory $linkBatchFactory;
	private NamespaceInfo $namespaceInfo;
	private ChangeTagsStore $changeTagsStore;
	private RowCommentFormatter $rowCommentFormatter;
	private IContentHandlerFactory $contentHandlerFactory;
	private TempUserConfig $tempUserConfig;

	public function __construct(
		IContextSource $context,
		LinkRenderer $linkRenderer,
		GroupPermissionsLookup $groupPermissionsLookup,
		HookContainer $hookContainer,
		LinkBatchFactory $linkBatchFactory,
		NamespaceInfo $namespaceInfo,
		ChangeTagsStore $changeTagsStore,
		RowCommentFormatter $rowCommentFormatter,
		IContentHandlerFactory $contentHandlerFactory,
		TempUserConfig $tempUserConfig,
		FormOptions $opts
	) {
		parent::__construct( $context, $linkRenderer );
		$this->groupPermissionsLookup = $groupPermissionsLookup;
		$this->hookRunner = new HookRunner( $hookContainer );
		$this->linkBatchFactory = $linkBatchFactory;
		$this->namespaceInfo = $namespaceInfo;
		$this->changeTagsStore = $changeTagsStore;
		$this->rowCommentFormatter = $rowCommentFormatter;
		$this->contentHandlerFactory = $contentHandlerFactory;
		$this->tempUserConfig = $tempUserConfig;
		$this->opts = $opts;
		$this->tagsCache = new MapCacheLRU( 50 );
	}

	/** @inheritDoc */
	public function getQueryInfo() {
		$conds = [];
		$conds['rc_source'] = RecentChange::SRC_NEW;

		$username = $this->opts->getValue( 'username' );
		$user = Title::makeTitleSafe( NS_USER, $username );

		$size = abs( intval( $this->opts->getValue( 'size' ) ) );
		if ( $size > 0 ) {
			$db = $this->getDatabase();
			if ( $this->opts->getValue( 'size-mode' ) === 'max' ) {
				$conds[] = $db->expr( 'page_len', '<=', $size );
			} else {
				$conds[] = $db->expr( 'page_len', '>=', $size );
			}
		}

		if ( $user ) {
			$conds['actor_name'] = $user->getText();
			$joinFlags = 0;
		} elseif ( $this->opts->getValue( 'hideliu' ) ) {
			// Only include anonymous users if the 'hideliu' option has been provided.
			$anonOnlyExpr = $this->getDatabase()->expr( 'actor_user', '=', null );
			if ( $this->tempUserConfig->isKnown() ) {
				$anonOnlyExpr = $anonOnlyExpr->orExpr( $this->tempUserConfig->getMatchCondition(
					$this->getDatabase(), 'actor_name', IExpression::LIKE
				) );
			}
			$conds[] = $anonOnlyExpr;
			$joinFlags = 0;
		} else {
			$joinFlags = RecentChange::STRAIGHT_JOIN_ACTOR;
		}

		$conds = array_merge( $conds, $this->getNamespaceCond() );

		# If this user cannot see patrolled edits or they are off, don't do dumb queries!
		if ( $this->opts->getValue( 'hidepatrolled' ) && $this->getUser()->useNPPatrol() ) {
			$conds['rc_patrolled'] = RecentChange::PRC_UNPATROLLED;
		}

		if ( $this->opts->getValue( 'hidebots' ) ) {
			$conds['rc_bot'] = 0;
		}

		if ( $this->opts->getValue( 'hideredirs' ) ) {
			$conds['page_is_redirect'] = 0;
		}

		// Allow changes to the New Pages query
		$rcQuery = RecentChange::getQueryInfo( $joinFlags );
		$tables = array_merge( $rcQuery['tables'], [ 'page' ] );
		$fields = array_merge( $rcQuery['fields'], [
			'length' => 'page_len', 'rev_id' => 'page_latest', 'page_namespace', 'page_title',
			'page_content_model',
		] );
		$join_conds = [ 'page' => [ 'JOIN', 'page_id=rc_cur_id' ] ] + $rcQuery['joins'];

		$this->hookRunner->onSpecialNewpagesConditions(
			$this, $this->opts, $conds, $tables, $fields, $join_conds );

		$info = [
			'tables' => $tables,
			'fields' => $fields,
			'conds' => $conds,
			'options' => [],
			'join_conds' => $join_conds
		];

		// Modify query for tags
		$this->changeTagsStore->modifyDisplayQuery(
			$info['tables'],
			$info['fields'],
			$info['conds'],
			$info['join_conds'],
			$info['options'],
			$this->opts['tagfilter'],
			$this->opts['tagInvert']
		);

		return $info;
	}

	private function getNamespaceCond(): array {
		$namespace = $this->opts->getValue( 'namespace' );
		if ( $namespace === 'all' || $namespace === '' ) {
			return [];
		}

		$namespace = intval( $namespace );
		if ( $namespace < NS_MAIN ) {
			// Negative namespaces are invalid
			return [];
		}

		$invert = $this->opts->getValue( 'invert' );
		$associated = $this->opts->getValue( 'associated' );

		$eq_op = $invert ? '!=' : '=';
		$dbr = $this->getDatabase();
		$namespaces = [ $namespace ];
		if ( $associated ) {
			$namespaces[] = $this->namespaceInfo->getAssociated( $namespace );
		}

		return [ $dbr->expr( 'rc_namespace', $eq_op, $namespaces ) ];
	}

	/** @inheritDoc */
	public function getIndexField() {
		return [ [ 'rc_timestamp', 'rc_id' ] ];
	}

	/** @inheritDoc */
	public function formatRow( $row ) {
		$title = Title::newFromRow( $row );

		// Revision deletion works on revisions,
		// so cast our recent change row to a revision row.
		$revRecord = $this->revisionFromRcResult( $row, $title );

		$classes = [];
		$attribs = [ 'data-mw-revid' => $row->rc_this_oldid ];

		$lang = $this->getLanguage();
		$time = ChangesList::revDateLink( $revRecord, $this->getUser(), $lang, null, 'mw-newpages-time' );

		$linkRenderer = $this->getLinkRenderer();

		$query = $title->isRedirect() ? [ 'redirect' => 'no' ] : [];

		$plink = Html::rawElement( 'bdi', [ 'dir' => $lang->getDir() ], $linkRenderer->makeKnownLink(
			$title,
			null,
			[ 'class' => 'mw-newpages-pagename' ],
			$query
		) );
		$linkArr = [];
		$linkArr[] = $linkRenderer->makeKnownLink(
			$title,
			$this->msg( 'hist' )->text(),
			[ 'class' => 'mw-newpages-history' ],
			[ 'action' => 'history' ]
		);
		if ( $this->contentHandlerFactory->getContentHandler( $title->getContentModel() )
			->supportsDirectEditing()
		) {
			$linkArr[] = $linkRenderer->makeKnownLink(
				$title,
				$this->msg( 'editlink' )->text(),
				[ 'class' => 'mw-newpages-edit' ],
				[ 'action' => 'edit' ]
			);
		}
		$links = $this->msg( 'parentheses' )->rawParams( $this->getLanguage()
			->pipeList( $linkArr ) )->escaped();

		$length = Html::rawElement(
			'span',
			[ 'class' => 'mw-newpages-length' ],
			$this->msg( 'brackets' )->rawParams(
				$this->msg( 'nbytes' )->numParams( $row->length )->escaped()
			)->escaped()
		);

		$ulink = Linker::revUserTools( $revRecord );
		$rc = RecentChange::newFromRow( $row );
		if ( ChangesList::userCan( $rc, RevisionRecord::DELETED_COMMENT, $this->getAuthority() ) ) {
			$comment = $this->formattedComments[$rc->mAttribs['rc_id']];
		} else {
			$comment = '<span class="comment">' . $this->msg( 'rev-deleted-comment' )->escaped() . '</span>';
		}
		if ( ChangesList::isDeleted( $rc, RevisionRecord::DELETED_COMMENT ) ) {
			$deletedClass = 'history-deleted';
			if ( ChangesList::isDeleted( $rc, RevisionRecord::DELETED_RESTRICTED ) ) {
				$deletedClass .= ' mw-history-suppressed';
			}
			$comment = '<span class="' . $deletedClass . ' comment">' . $comment . '</span>';
		}

		if ( $this->getUser()->useNPPatrol() && !$row->rc_patrolled ) {
			$classes[] = 'not-patrolled';
		}

		# Add a class for zero byte pages
		if ( $row->length == 0 ) {
			$classes[] = 'mw-newpages-zero-byte-page';
		}

		# Tags, if any.
		if ( isset( $row->ts_tags ) ) {
			[ $tagDisplay, $newClasses ] = $this->tagsCache->getWithSetCallback(
				$this->tagsCache->makeKey(
					$row->ts_tags,
					$this->getUser()->getName(),
					$lang->getCode()
				),
				fn () => ChangeTags::formatSummaryRow(
					$row->ts_tags,
					'newpages',
					$this->getContext()
				)
			);
			$classes = array_merge( $classes, $newClasses );
		} else {
			$tagDisplay = '';
		}

		# Display the old title if the namespace/title has been changed
		$oldTitleText = '';
		$oldTitle = Title::makeTitle( $row->rc_namespace, $row->rc_title );

		if ( !$title->equals( $oldTitle ) ) {
			$oldTitleText = $oldTitle->getPrefixedText();
			$oldTitleText = Html::rawElement(
				'span',
				[ 'class' => 'mw-newpages-oldtitle' ],
				$this->msg( 'rc-old-title' )->params( $oldTitleText )->escaped()
			);
		}

		$ret = "{$time} {$plink} {$links} {$length} {$ulink} {$comment} "
			. "{$tagDisplay} {$oldTitleText}";

		// Let extensions add data
		$this->hookRunner->onNewPagesLineEnding(
			$this, $ret, $row, $classes, $attribs );
		$attribs = array_filter( $attribs,
			[ Sanitizer::class, 'isReservedDataAttribute' ],
			ARRAY_FILTER_USE_KEY
		);

		if ( $classes ) {
			$attribs['class'] = $classes;
		}

		return Html::rawElement( 'li', $attribs, $ret ) . "\n";
	}

	/**
	 * @param stdClass $result Result row from recent changes
	 * @param Title $title
	 * @return RevisionRecord
	 */
	protected function revisionFromRcResult( stdClass $result, Title $title ): RevisionRecord {
		$revRecord = new MutableRevisionRecord( $title );
		$revRecord->setTimestamp( $result->rc_timestamp );
		$revRecord->setId( $result->rc_this_oldid );
		$revRecord->setVisibility( (int)$result->rc_deleted );

		$user = new UserIdentityValue(
			(int)$result->rc_user,
			$result->rc_user_text
		);
		$revRecord->setUser( $user );

		return $revRecord;
	}

	protected function doBatchLookups() {
		$linkBatch = $this->linkBatchFactory->newLinkBatch();
		foreach ( $this->mResult as $row ) {
			$linkBatch->addUser( new UserIdentityValue( (int)$row->rc_user, $row->rc_user_text ) );
			$linkBatch->add( $row->page_namespace, $row->page_title );
		}
		$linkBatch->execute();

		$this->formattedComments = $this->rowCommentFormatter->formatRows(
			$this->mResult, 'rc_comment', 'page_namespace', 'page_title', 'rc_id', true
		);
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
}

/**
 * Retain the old class name for backwards compatibility.
 * @deprecated since 1.41
 */
class_alias( NewPagesPager::class, 'NewPagesPager' );
