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

namespace MediaWiki\Specials;

use MediaWiki\Cache\LinkBatch;
use MediaWiki\Cache\LinkBatchFactory;
use MediaWiki\ChangeTags\ChangeTags;
use MediaWiki\CommentFormatter\CommentFormatter;
use MediaWiki\CommentStore\CommentStore;
use MediaWiki\Content\IContentHandlerFactory;
use MediaWiki\Content\TextContent;
use MediaWiki\Context\DerivativeContext;
use MediaWiki\Exception\ErrorPageError;
use MediaWiki\Exception\PermissionsError;
use MediaWiki\Exception\UserBlockedError;
use MediaWiki\FileRepo\File\ArchivedFile;
use MediaWiki\FileRepo\File\File;
use MediaWiki\FileRepo\LocalRepo;
use MediaWiki\FileRepo\RepoGroup;
use MediaWiki\Html\Html;
use MediaWiki\Linker\Linker;
use MediaWiki\Linker\LinkTarget;
use MediaWiki\Logging\LogEventsList;
use MediaWiki\Logging\LogPage;
use MediaWiki\MainConfigNames;
use MediaWiki\Message\Message;
use MediaWiki\Page\PageArchive;
use MediaWiki\Page\UndeletePage;
use MediaWiki\Page\UndeletePageFactory;
use MediaWiki\Page\WikiPageFactory;
use MediaWiki\Parser\ParserOptions;
use MediaWiki\Permissions\PermissionManager;
use MediaWiki\RecentChanges\ChangesList;
use MediaWiki\Revision\ArchivedRevisionLookup;
use MediaWiki\Revision\RevisionAccessException;
use MediaWiki\Revision\RevisionArchiveRecord;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\RevisionRenderer;
use MediaWiki\Revision\RevisionStore;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\SpecialPage\SpecialPage;
use MediaWiki\Storage\NameTableAccessException;
use MediaWiki\Storage\NameTableStore;
use MediaWiki\Title\Title;
use MediaWiki\User\Options\UserOptionsLookup;
use MediaWiki\User\User;
use MediaWiki\User\UserIdentityValue;
use MediaWiki\Watchlist\WatchlistManager;
use OOUI\ActionFieldLayout;
use OOUI\ButtonInputWidget;
use OOUI\CheckboxInputWidget;
use OOUI\DropdownInputWidget;
use OOUI\FieldLayout;
use OOUI\FieldsetLayout;
use OOUI\FormLayout;
use OOUI\HorizontalLayout;
use OOUI\HtmlSnippet;
use OOUI\Layout;
use OOUI\PanelLayout;
use OOUI\TextInputWidget;
use OOUI\Widget;
use SearchEngineFactory;
use Wikimedia\Rdbms\IConnectionProvider;
use Wikimedia\Rdbms\IDBAccessObject;
use Wikimedia\Rdbms\IResultWrapper;

/**
 * Special page allowing users with the appropriate permissions to view
 * and restore deleted content.
 *
 * @ingroup SpecialPage
 */
class SpecialUndelete extends SpecialPage {

	/**
	 * Limit of revisions (Page history) to display.
	 * (If there are more items to display - "Load more" button will appear).
	 */
	private const REVISION_HISTORY_LIMIT = 500;

	/** @var string|null */
	private $mAction;
	/** @var string */
	private $mTarget;
	/** @var string */
	private $mTimestamp;
	/** @var bool */
	private $mRestore;
	/** @var bool */
	private $mRevdel;
	/** @var bool */
	private $mInvert;
	/** @var string */
	private $mFilename;
	/** @var string[] */
	private $mTargetTimestamp = [];
	/** @var bool */
	private $mAllowed;
	/** @var bool */
	private $mCanView;
	/** @var string */
	private $mComment = '';
	/** @var string */
	private $mToken;
	/** @var bool|null */
	private $mPreview;
	/** @var bool|null */
	private $mDiff;
	/** @var bool|null */
	private $mDiffOnly;
	/** @var bool|null */
	private $mUnsuppress;
	/** @var int[] */
	private $mFileVersions = [];
	/** @var bool|null */
	private $mUndeleteTalk;
	/** @var string|null Timestamp at which to start a "load more" request (open interval) */
	private $mHistoryOffset;

	/** @var Title|null */
	private $mTargetObj;
	/**
	 * @var string Search prefix
	 */
	private $mSearchPrefix;

	private PermissionManager $permissionManager;
	private RevisionStore $revisionStore;
	private RevisionRenderer $revisionRenderer;
	private IContentHandlerFactory $contentHandlerFactory;
	private NameTableStore $changeTagDefStore;
	private LinkBatchFactory $linkBatchFactory;
	private LocalRepo $localRepo;
	private IConnectionProvider $dbProvider;
	private UserOptionsLookup $userOptionsLookup;
	private WikiPageFactory $wikiPageFactory;
	private SearchEngineFactory $searchEngineFactory;
	private UndeletePageFactory $undeletePageFactory;
	private ArchivedRevisionLookup $archivedRevisionLookup;
	private CommentFormatter $commentFormatter;
	private WatchlistManager $watchlistManager;

	public function __construct(
		PermissionManager $permissionManager,
		RevisionStore $revisionStore,
		RevisionRenderer $revisionRenderer,
		IContentHandlerFactory $contentHandlerFactory,
		NameTableStore $changeTagDefStore,
		LinkBatchFactory $linkBatchFactory,
		RepoGroup $repoGroup,
		IConnectionProvider $dbProvider,
		UserOptionsLookup $userOptionsLookup,
		WikiPageFactory $wikiPageFactory,
		SearchEngineFactory $searchEngineFactory,
		UndeletePageFactory $undeletePageFactory,
		ArchivedRevisionLookup $archivedRevisionLookup,
		CommentFormatter $commentFormatter,
		WatchlistManager $watchlistManager
	) {
		parent::__construct( 'Undelete', 'deletedhistory' );
		$this->permissionManager = $permissionManager;
		$this->revisionStore = $revisionStore;
		$this->revisionRenderer = $revisionRenderer;
		$this->contentHandlerFactory = $contentHandlerFactory;
		$this->changeTagDefStore = $changeTagDefStore;
		$this->linkBatchFactory = $linkBatchFactory;
		$this->localRepo = $repoGroup->getLocalRepo();
		$this->dbProvider = $dbProvider;
		$this->userOptionsLookup = $userOptionsLookup;
		$this->wikiPageFactory = $wikiPageFactory;
		$this->searchEngineFactory = $searchEngineFactory;
		$this->undeletePageFactory = $undeletePageFactory;
		$this->archivedRevisionLookup = $archivedRevisionLookup;
		$this->commentFormatter = $commentFormatter;
		$this->watchlistManager = $watchlistManager;
	}

	public function doesWrites() {
		return true;
	}

	private function loadRequest( ?string $par ) {
		$request = $this->getRequest();
		$user = $this->getUser();

		$this->mAction = $request->getRawVal( 'action' );
		if ( $par !== null && $par !== '' ) {
			$this->mTarget = $par;
		} else {
			$this->mTarget = $request->getVal( 'target' );
		}

		$this->mTargetObj = null;

		if ( $this->mTarget !== null && $this->mTarget !== '' ) {
			$this->mTargetObj = Title::newFromText( $this->mTarget );
		}

		$this->mSearchPrefix = $request->getText( 'prefix' );
		$time = $request->getVal( 'timestamp' );
		$this->mTimestamp = $time ? wfTimestamp( TS_MW, $time ) : '';
		$this->mFilename = $request->getVal( 'file' );

		$posted = $request->wasPosted() &&
			$user->matchEditToken( $request->getVal( 'wpEditToken' ) );
		$this->mRestore = $request->getCheck( 'restore' ) && $posted;
		$this->mRevdel = $request->getCheck( 'revdel' ) && $posted;
		$this->mInvert = $request->getCheck( 'invert' ) && $posted;
		$this->mPreview = $request->getCheck( 'preview' ) && $posted;
		$this->mDiff = $request->getCheck( 'diff' );
		$this->mDiffOnly = $request->getBool( 'diffonly',
			$this->userOptionsLookup->getOption( $this->getUser(), 'diffonly' ) );
		$commentList = $request->getText( 'wpCommentList', 'other' );
		$comment = $request->getText( 'wpComment' );
		if ( $commentList === 'other' ) {
			$this->mComment = $comment;
		} elseif ( $comment !== '' ) {
			$this->mComment = $commentList . $this->msg( 'colon-separator' )->inContentLanguage()->text() . $comment;
		} else {
			$this->mComment = $commentList;
		}
		$this->mUnsuppress = $request->getVal( 'wpUnsuppress' ) &&
			$this->permissionManager->userHasRight( $user, 'suppressrevision' );
		$this->mToken = $request->getVal( 'token' );
		$this->mUndeleteTalk = $request->getCheck( 'undeletetalk' );
		$this->mHistoryOffset = $request->getVal( 'historyoffset' );

		if ( $this->isAllowed( 'undelete' ) ) {
			$this->mAllowed = true; // user can restore
			$this->mCanView = true; // user can view content
		} elseif ( $this->isAllowed( 'deletedtext' ) ) {
			$this->mAllowed = false; // user cannot restore
			$this->mCanView = true; // user can view content
			$this->mRestore = false;
		} else { // user can only view the list of revisions
			$this->mAllowed = false;
			$this->mCanView = false;
			$this->mTimestamp = '';
			$this->mRestore = false;
		}

		if ( $this->mRestore || $this->mInvert ) {
			$timestamps = [];
			$this->mFileVersions = [];
			foreach ( $request->getValues() as $key => $val ) {
				$matches = [];
				if ( preg_match( '/^ts(\d{14})$/', $key, $matches ) ) {
					$timestamps[] = $matches[1];
				}

				if ( preg_match( '/^fileid(\d+)$/', $key, $matches ) ) {
					$this->mFileVersions[] = intval( $matches[1] );
				}
			}
			rsort( $timestamps );
			$this->mTargetTimestamp = $timestamps;
		}
	}

	/**
	 * Checks whether a user is allowed the permission for the
	 * specific title if one is set.
	 *
	 * @param string $permission
	 * @param User|null $user
	 * @return bool
	 */
	protected function isAllowed( $permission, ?User $user = null ) {
		$user ??= $this->getUser();
		$block = $user->getBlock();

		if ( $this->mTargetObj !== null ) {
			return $this->permissionManager->userCan( $permission, $user, $this->mTargetObj );
		} else {
			$hasRight = $this->permissionManager->userHasRight( $user, $permission );
			$sitewideBlock = $block && $block->isSitewide();
			return $permission === 'undelete' ? ( $hasRight && !$sitewideBlock ) : $hasRight;
		}
	}

	public function userCanExecute( User $user ) {
		return $this->isAllowed( $this->mRestriction, $user );
	}

	/**
	 * @inheritDoc
	 */
	public function checkPermissions() {
		$user = $this->getUser();

		// First check if user has the right to use this page. If not,
		// show a permissions error whether they are blocked or not.
		if ( !parent::userCanExecute( $user ) ) {
			$this->displayRestrictionError();
		}

		// If a user has the right to use this page, but is blocked from
		// the target, show a block error.
		if (
			$this->mTargetObj && $this->permissionManager->isBlockedFrom( $user, $this->mTargetObj ) ) {
			// @phan-suppress-next-line PhanTypeMismatchArgumentNullable Block is checked and not null
			throw new UserBlockedError( $user->getBlock() );
		}

		// Finally, do the comprehensive permission check via isAllowed.
		if ( !$this->userCanExecute( $user ) ) {
			$this->displayRestrictionError();
		}
	}

	public function execute( $par ) {
		$this->useTransactionalTimeLimit();

		$user = $this->getUser();

		$this->setHeaders();
		$this->outputHeader();
		$this->addHelpLink( 'Help:Deletion_and_undeletion' );

		$this->loadRequest( $par );
		$this->checkPermissions(); // Needs to be after mTargetObj is set

		$out = $this->getOutput();
		// This page uses Html::warningBox and Html::errorBox
		$out->addModuleStyles( 'mediawiki.codex.messagebox.styles' );

		if ( $this->mTargetObj === null ) {
			$out->addWikiMsg( 'undelete-header' );

			# Not all users can just browse every deleted page from the list
			if ( $this->permissionManager->userHasRight( $user, 'browsearchive' ) ) {
				$this->showSearchForm();
			}

			return;
		}

		$this->addHelpLink( 'Help:Undelete' );
		if ( $this->mAllowed ) {
			$out->setPageTitleMsg( $this->msg( 'undeletepage' ) );
		} else {
			$out->setPageTitleMsg( $this->msg( 'viewdeletedpage' ) );
		}

		$this->getSkin()->setRelevantTitle( $this->mTargetObj );

		if ( $this->mTimestamp !== '' ) {
			$this->showRevision( $this->mTimestamp );
		} elseif ( $this->mFilename !== null && $this->mTargetObj->inNamespace( NS_FILE ) ) {
			$file = new ArchivedFile( $this->mTargetObj, 0, $this->mFilename );
			// Check if user is allowed to see this file
			if ( !$file->exists() ) {
				$out->addWikiMsg( 'filedelete-nofile', $this->mFilename );
			} elseif ( !$file->userCan( File::DELETED_FILE, $user ) ) {
				if ( $file->isDeleted( File::DELETED_RESTRICTED ) ) {
					throw new PermissionsError( 'suppressrevision' );
				} else {
					throw new PermissionsError( 'deletedtext' );
				}
			} elseif ( !$user->matchEditToken( $this->mToken, $this->mFilename ) ) {
				$this->showFileConfirmationForm( $this->mFilename );
			} else {
				$this->showFile( $this->mFilename );
			}
		} elseif ( $this->mAction === 'submit' ) {
			if ( $this->mRestore ) {
				$this->undelete();
			} elseif ( $this->mRevdel ) {
				$this->redirectToRevDel();
			}
		} elseif ( $this->mAction === 'render' ) {
			$this->showMoreHistory();
		} else {
			$this->showHistory();
		}
	}

	/**
	 * Convert submitted form data to format expected by RevisionDelete and
	 * redirect the request
	 */
	private function redirectToRevDel() {
		$revisions = [];

		foreach ( $this->getRequest()->getValues() as $key => $val ) {
			$matches = [];
			if ( preg_match( "/^ts(\d{14})$/", $key, $matches ) ) {
				$revisionRecord = $this->archivedRevisionLookup
					->getRevisionRecordByTimestamp( $this->mTargetObj, $matches[1] );
				if ( $revisionRecord ) {
					// Can return null
					$revisions[ $revisionRecord->getId() ] = 1;
				}
			}
		}

		$query = [
			'type' => 'revision',
			'ids' => $revisions,
			'target' => $this->mTargetObj->getPrefixedText()
		];
		$url = SpecialPage::getTitleFor( 'Revisiondelete' )->getFullURL( $query );
		$this->getOutput()->redirect( $url );
	}

	private function showSearchForm() {
		$out = $this->getOutput();
		$out->setPageTitleMsg( $this->msg( 'undelete-search-title' ) );
		$fuzzySearch = $this->getRequest()->getVal( 'fuzzy', '1' );

		$out->enableOOUI();

		$fields = [];
		$fields[] = new ActionFieldLayout(
			new TextInputWidget( [
				'name' => 'prefix',
				'inputId' => 'prefix',
				'infusable' => true,
				'value' => $this->mSearchPrefix,
				'autofocus' => true,
			] ),
			new ButtonInputWidget( [
				'label' => $this->msg( 'undelete-search-submit' )->text(),
				'flags' => [ 'primary', 'progressive' ],
				'inputId' => 'searchUndelete',
				'type' => 'submit',
			] ),
			[
				'label' => new HtmlSnippet(
					$this->msg(
						$fuzzySearch ? 'undelete-search-full' : 'undelete-search-prefix'
					)->parse()
				),
				'align' => 'left',
			]
		);

		$fieldset = new FieldsetLayout( [
			'label' => $this->msg( 'undelete-search-box' )->text(),
			'items' => $fields,
		] );

		$form = new FormLayout( [
			'method' => 'get',
			'action' => wfScript(),
		] );

		$form->appendContent(
			$fieldset,
			new HtmlSnippet(
				Html::hidden( 'title', $this->getPageTitle()->getPrefixedDBkey() ) .
				Html::hidden( 'fuzzy', $fuzzySearch )
			)
		);

		$out->addHTML(
			new PanelLayout( [
				'expanded' => false,
				'padded' => true,
				'framed' => true,
				'content' => $form,
			] )
		);

		# List undeletable articles
		if ( $this->mSearchPrefix ) {
			// For now, we enable search engine match only when specifically asked to
			// by using fuzzy=1 parameter.
			if ( $fuzzySearch ) {
				$result = PageArchive::listPagesBySearch( $this->mSearchPrefix );
			} else {
				$result = PageArchive::listPagesByPrefix( $this->mSearchPrefix );
			}
			$this->showList( $result );
		}
	}

	/**
	 * Generic list of deleted pages
	 *
	 * @param IResultWrapper $result
	 * @return bool
	 */
	private function showList( $result ) {
		$out = $this->getOutput();

		if ( $result->numRows() == 0 ) {
			$out->addWikiMsg( 'undelete-no-results' );

			return false;
		}

		$out->addWikiMsg( 'undeletepagetext', $this->getLanguage()->formatNum( $result->numRows() ) );

		$linkRenderer = $this->getLinkRenderer();
		$undelete = $this->getPageTitle();
		$out->addHTML( "<ul id='undeleteResultsList'>\n" );
		foreach ( $result as $row ) {
			$title = Title::makeTitleSafe( $row->ar_namespace, $row->ar_title );
			if ( $title !== null ) {
				$item = $linkRenderer->makeKnownLink(
					$undelete,
					$title->getPrefixedText(),
					[],
					[ 'target' => $title->getPrefixedText() ]
				);
			} else {
				// The title is no longer valid, show as text
				$item = Html::element(
					'span',
					[ 'class' => 'mw-invalidtitle' ],
					Linker::getInvalidTitleDescription(
						$this->getContext(),
						$row->ar_namespace,
						$row->ar_title
					)
				);
			}
			$revs = $this->msg( 'undeleterevisions' )->numParams( $row->count )->parse();
			$out->addHTML(
				Html::rawElement(
					'li',
					[ 'class' => 'undeleteResult' ],
					$item . $this->msg( 'word-separator' )->escaped() .
						$this->msg( 'parentheses' )->rawParams( $revs )->escaped()
				)
			);
		}
		$result->free();
		$out->addHTML( "</ul>\n" );

		return true;
	}

	private function showRevision( string $timestamp ) {
		if ( !preg_match( '/[0-9]{14}/', $timestamp ) ) {
			return;
		}
		$out = $this->getOutput();
		$out->addModuleStyles( 'mediawiki.interface.helpers.styles' );

		// When viewing a specific revision, add a subtitle link back to the overall
		// history, see T284114
		$listLink = $this->getLinkRenderer()->makeKnownLink(
			$this->getPageTitle(),
			$this->msg( 'undelete-back-to-list' )->text(),
			[],
			[ 'target' => $this->mTargetObj->getPrefixedText() ]
		);
		// same < arrow as with subpages
		$subtitle = "&lt; $listLink";
		$out->setSubtitle( $subtitle );

		$archive = new PageArchive( $this->mTargetObj );
		// FIXME: This hook must be deprecated, passing PageArchive by ref is awful.
		if ( !$this->getHookRunner()->onUndeleteForm__showRevision(
			$archive, $this->mTargetObj )
		) {
			return;
		}
		$revRecord = $this->archivedRevisionLookup->getRevisionRecordByTimestamp( $this->mTargetObj, $timestamp );

		$user = $this->getUser();

		if ( !$revRecord ) {
			$out->addWikiMsg( 'undeleterevision-missing' );
			return;
		}

		if ( $revRecord->isDeleted( RevisionRecord::DELETED_TEXT ) ) {
			// Used in wikilinks, should not contain whitespaces
			$titleText = $this->mTargetObj->getPrefixedDBkey();
			if ( !$revRecord->userCan( RevisionRecord::DELETED_TEXT, $this->getAuthority() ) ) {
				$msg = $revRecord->isDeleted( RevisionRecord::DELETED_RESTRICTED )
					? [ 'rev-suppressed-text-permission', $titleText ]
					: [ 'rev-deleted-text-permission', $titleText ];
				$out->addHTML(
					Html::warningBox(
						$this->msg( $msg[0], $msg[1] )->parse(),
						'plainlinks'
					)
				);
				return;
			}

			$msg = $revRecord->isDeleted( RevisionRecord::DELETED_RESTRICTED )
				? [ 'rev-suppressed-text-view', $titleText ]
				: [ 'rev-deleted-text-view', $titleText ];
			$out->addHTML(
				Html::warningBox(
					$this->msg( $msg[0], $msg[1] )->parse(),
					'plainlinks'
				)
			);
			// and we are allowed to see...
		}

		if ( $this->mDiff ) {
			$previousRevRecord = $this->archivedRevisionLookup
				->getPreviousRevisionRecord( $this->mTargetObj, $timestamp );
			if ( $previousRevRecord ) {
				$this->showDiff( $previousRevRecord, $revRecord );
				if ( $this->mDiffOnly ) {
					return;
				}

				$out->addHTML( '<hr />' );
			} else {
				$out->addWikiMsg( 'undelete-nodiff' );
			}
		}

		$link = $this->getLinkRenderer()->makeKnownLink(
			$this->getPageTitle( $this->mTargetObj->getPrefixedDBkey() ),
			$this->mTargetObj->getPrefixedText()
		);

		$lang = $this->getLanguage();

		// date and time are separate parameters to facilitate localisation.
		// $time is kept for backward compat reasons.
		$time = $lang->userTimeAndDate( $timestamp, $user );
		$d = $lang->userDate( $timestamp, $user );
		$t = $lang->userTime( $timestamp, $user );
		$userLink = Linker::revUserTools( $revRecord );

		try {
			$content = $revRecord->getContent(
				SlotRecord::MAIN,
				RevisionRecord::FOR_THIS_USER,
				$user
			);
		} catch ( RevisionAccessException $e ) {
			$content = null;
		}

		// TODO: MCR: this will have to become something like $hasTextSlots and $hasNonTextSlots
		$isText = ( $content instanceof TextContent );

		$undeleteRevisionContent = '';
		// Revision delete links
		if ( !$this->mDiff ) {
			$revdel = Linker::getRevDeleteLink(
				$user,
				$revRecord,
				$this->mTargetObj
			);
			if ( $revdel ) {
				$undeleteRevisionContent = $revdel . ' ';
			}
		}

		$undeleteRevisionContent .= $out->msg(
			'undelete-revision',
			Message::rawParam( $link ),
			$time,
			Message::rawParam( $userLink ),
			$d,
			$t
		)->parseAsBlock();

		if ( $this->mPreview || $isText ) {
			$out->addHTML(
				Html::warningBox(
					$undeleteRevisionContent,
					'mw-undelete-revision'
				)
			);
		} else {
			$out->addHTML(
				Html::rawElement(
					'div',
					[ 'class' => 'mw-undelete-revision', ],
					$undeleteRevisionContent
				)
			);
		}

		if ( $this->mPreview || !$isText ) {
			// NOTE: non-text content has no source view, so always use rendered preview

			$popts = ParserOptions::newFromContext( $this->getContext() );

			try {
				$rendered = $this->revisionRenderer->getRenderedRevision(
					$revRecord,
					$popts,
					$user,
					[ 'audience' => RevisionRecord::FOR_THIS_USER, 'causeAction' => 'undelete-preview' ]
				);

				// Fail hard if the audience check fails, since we already checked
				// at the beginning of this method.
				$pout = $rendered->getRevisionParserOutput();

				$out->addParserOutput( $pout, $popts, [
					'enableSectionEditLinks' => false,
				] );
			} catch ( RevisionAccessException $e ) {
			}
		}

		$out->enableOOUI();
		$buttonFields = [];

		if ( $isText ) {
			'@phan-var TextContent $content';
			// TODO: MCR: make this work for multiple slots
			// source view for textual content
			$sourceView = Html::element( 'textarea', [
				'readonly' => 'readonly',
				'cols' => 80,
				'rows' => 25
			], $content->getText() . "\n" );

			$buttonFields[] = new ButtonInputWidget( [
				'type' => 'submit',
				'name' => 'preview',
				'label' => $this->msg( 'showpreview' )->text()
			] );
		} else {
			$sourceView = '';
		}

		$buttonFields[] = new ButtonInputWidget( [
			'name' => 'diff',
			'type' => 'submit',
			'label' => $this->msg( 'showdiff' )->text()
		] );

		$out->addHTML(
			$sourceView .
				Html::openElement( 'div', [
					'style' => 'clear: both' ] ) .
				Html::openElement( 'form', [
					'method' => 'post',
					'action' => $this->getPageTitle()->getLocalURL( [ 'action' => 'submit' ] ) ] ) .
				Html::element( 'input', [
					'type' => 'hidden',
					'name' => 'target',
					'value' => $this->mTargetObj->getPrefixedDBkey() ] ) .
				Html::element( 'input', [
					'type' => 'hidden',
					'name' => 'timestamp',
					'value' => $timestamp ] ) .
				Html::element( 'input', [
					'type' => 'hidden',
					'name' => 'wpEditToken',
					'value' => $user->getEditToken() ] ) .
				new FieldLayout(
					new Widget( [
						'content' => new HorizontalLayout( [
							'items' => $buttonFields
						] )
					] )
				) .
				Html::closeElement( 'form' ) .
				Html::closeElement( 'div' )
		);
	}

	/**
	 * Build a diff display between this and the previous either deleted
	 * or non-deleted edit.
	 *
	 * @param RevisionRecord $previousRevRecord
	 * @param RevisionRecord $currentRevRecord
	 */
	private function showDiff(
		RevisionRecord $previousRevRecord,
		RevisionRecord $currentRevRecord
	) {
		$currentTitle = Title::newFromPageIdentity( $currentRevRecord->getPage() );

		$diffContext = new DerivativeContext( $this->getContext() );
		$diffContext->setTitle( $currentTitle );
		$diffContext->setWikiPage( $this->wikiPageFactory->newFromTitle( $currentTitle ) );

		$contentModel = $currentRevRecord->getSlot(
			SlotRecord::MAIN,
			RevisionRecord::RAW
		)->getModel();

		$diffEngine = $this->contentHandlerFactory->getContentHandler( $contentModel )
			->createDifferenceEngine( $diffContext );

		$diffEngine->setRevisions( $previousRevRecord, $currentRevRecord );
		$diffEngine->showDiffStyle();
		$formattedDiff = $diffEngine->getDiff(
			$this->diffHeader( $previousRevRecord, 'o' ),
			$this->diffHeader( $currentRevRecord, 'n' )
		);

		if ( $formattedDiff === false ) {
			if ( $diffEngine->hasSuppressedRevision() ) {
				$error = 'rev-suppressed-no-diff';
			} elseif ( $diffEngine->hasDeletedRevision() ) {
				$error = 'rev-deleted-no-diff';
			} else {
				// Something else went wrong when loading the diff - at least explain that something was wrong ...
				$error = 'undelete-error-loading-diff';
			}
			$this->getOutput()->addHTML( $this->msg( $error )->parse() );
		} else {
			$this->getOutput()->addHTML( "<div>$formattedDiff</div>\n" );
		}
	}

	/**
	 * @param RevisionRecord $revRecord
	 * @param string $prefix
	 * @return string
	 */
	private function diffHeader( RevisionRecord $revRecord, $prefix ) {
		if ( $revRecord instanceof RevisionArchiveRecord ) {
			// Revision in the archive table, only viewable via this special page
			$targetPage = $this->getPageTitle();
			$targetQuery = [
				'target' => $this->mTargetObj->getPrefixedText(),
				'timestamp' => wfTimestamp( TS_MW, $revRecord->getTimestamp() )
			];
		} else {
			// Revision in the revision table, viewable by oldid
			$targetPage = $revRecord->getPageAsLinkTarget();
			$targetQuery = [ 'oldid' => $revRecord->getId() ];
		}

		// Add show/hide deletion links if available
		$user = $this->getUser();
		$lang = $this->getLanguage();
		$rdel = Linker::getRevDeleteLink( $user, $revRecord, $this->mTargetObj );

		if ( $rdel ) {
			$rdel = " $rdel";
		}

		$minor = $revRecord->isMinor() ? ChangesList::flag( 'minor' ) : '';

		$dbr = $this->dbProvider->getReplicaDatabase();
		$tagIds = $dbr->newSelectQueryBuilder()
			->select( 'ct_tag_id' )
			->from( 'change_tag' )
			->where( [ 'ct_rev_id' => $revRecord->getId() ] )
			->caller( __METHOD__ )->fetchFieldValues();
		$tags = [];
		foreach ( $tagIds as $tagId ) {
			try {
				$tags[] = $this->changeTagDefStore->getName( (int)$tagId );
			} catch ( NameTableAccessException $exception ) {
				continue;
			}
		}
		$tags = implode( ',', $tags );
		$tagSummary = ChangeTags::formatSummaryRow( $tags, 'deleteddiff', $this->getContext() );
		$asof = $this->getLinkRenderer()->makeLink(
			$targetPage,
			$this->msg(
				'revisionasof',
				$lang->userTimeAndDate( $revRecord->getTimestamp(), $user ),
				$lang->userDate( $revRecord->getTimestamp(), $user ),
				$lang->userTime( $revRecord->getTimestamp(), $user )
			)->text(),
			[],
			$targetQuery
		);
		if ( $revRecord->isDeleted( RevisionRecord::DELETED_TEXT ) ) {
			$asof = Html::rawElement(
				'span',
				[ 'class' => Linker::getRevisionDeletedClass( $revRecord ) ],
				$asof
			);
		}

		// FIXME This is reimplementing DifferenceEngine#getRevisionHeader
		// and partially #showDiffPage, but worse
		return '<div id="mw-diff-' . $prefix . 'title1"><strong>' .
			$asof .
			'</strong></div>' .
			'<div id="mw-diff-' . $prefix . 'title2">' .
			Linker::revUserTools( $revRecord ) . '<br />' .
			'</div>' .
			'<div id="mw-diff-' . $prefix . 'title3">' .
			$minor . $this->commentFormatter->formatRevision( $revRecord, $user ) . $rdel . '<br />' .
			'</div>' .
			'<div id="mw-diff-' . $prefix . 'title5">' .
			$tagSummary[0] . '<br />' .
			'</div>';
	}

	/**
	 * Show a form confirming whether a tokenless user really wants to see a file
	 * @param string $key
	 */
	private function showFileConfirmationForm( $key ) {
		$out = $this->getOutput();
		$lang = $this->getLanguage();
		$user = $this->getUser();
		$file = new ArchivedFile( $this->mTargetObj, 0, $this->mFilename );
		$out->addWikiMsg( 'undelete-show-file-confirm',
			$this->mTargetObj->getText(),
			$lang->userDate( $file->getTimestamp(), $user ),
			$lang->userTime( $file->getTimestamp(), $user ) );
		$out->addHTML(
			Html::rawElement( 'form', [
					'method' => 'POST',
					'action' => $this->getPageTitle()->getLocalURL( [
						'target' => $this->mTarget,
						'file' => $key,
						'token' => $user->getEditToken( $key ),
					] ),
				],
				Html::submitButton( $this->msg( 'undelete-show-file-submit' )->text() )
			)
		);
	}

	/**
	 * Show a deleted file version requested by the visitor.
	 * @param string $key
	 */
	private function showFile( $key ) {
		$this->getOutput()->disable();

		# We mustn't allow the output to be CDN cached, otherwise
		# if an admin previews a deleted image, and it's cached, then
		# a user without appropriate permissions can toddle off and
		# nab the image, and CDN will serve it
		$response = $this->getRequest()->response();
		$response->header( 'Expires: ' . gmdate( 'D, d M Y H:i:s', 0 ) . ' GMT' );
		$response->header( 'Cache-Control: no-cache, no-store, max-age=0, must-revalidate' );

		$path = $this->localRepo->getZonePath( 'deleted' ) . '/' . $this->localRepo->getDeletedHashPath( $key ) . $key;
		$this->localRepo->streamFileWithStatus( $path );
	}

	private function addRevisionsToBatch( LinkBatch $batch, IResultWrapper $revisions ) {
		foreach ( $revisions as $row ) {
			$batch->addUser( new UserIdentityValue( (int)$row->ar_user, $row->ar_user_text ) );
		}
	}

	private function addFilesToBatch( LinkBatch $batch, IResultWrapper $files ) {
		foreach ( $files as $row ) {
			$batch->add( NS_USER, $row->fa_user_text );
			$batch->add( NS_USER_TALK, $row->fa_user_text );
		}
	}

	/**
	 * Handle XHR "show more history" requests (T249977)
	 */
	protected function showMoreHistory() {
		$out = $this->getOutput();
		$out->setArticleBodyOnly( true );
		$dbr = $this->dbProvider->getReplicaDatabase();
		if ( $this->mHistoryOffset ) {
			$extraConds = [ $dbr->expr( 'ar_timestamp', '<', $dbr->timestamp( $this->mHistoryOffset ) ) ];
		} else {
			$extraConds = [];
		}
		$revisions = $this->archivedRevisionLookup->listRevisions(
			$this->mTargetObj,
			$extraConds,
			self::REVISION_HISTORY_LIMIT + 1
		);
		$batch = $this->linkBatchFactory->newLinkBatch();
		$this->addRevisionsToBatch( $batch, $revisions );
		$batch->execute();
		$out->addHTML( $this->formatRevisionHistory( $revisions ) );

		if ( $revisions->numRows() > self::REVISION_HISTORY_LIMIT ) {
			// Indicate to JS that the "show more" button should remain active
			$out->setStatusCode( 206 );
		}
	}

	/**
	 * Generate the <ul> element representing a list of deleted revisions
	 *
	 * @param IResultWrapper $revisions
	 * @return string
	 */
	protected function formatRevisionHistory( IResultWrapper $revisions ) {
		$history = Html::openElement( 'ul', [ 'class' => 'mw-undelete-revlist' ] );

		// Exclude the last data row if there is more data than history limit amount
		$numRevisions = $revisions->numRows();
		$displayCount = min( $numRevisions, self::REVISION_HISTORY_LIMIT );
		$firstRev = $this->revisionStore->getFirstRevision( $this->mTargetObj );
		$earliestLiveTime = $firstRev ? $firstRev->getTimestamp() : null;

		$revisions->rewind();
		for ( $i = 0; $i < $displayCount; $i++ ) {
			$row = $revisions->fetchObject();
			// The $remaining parameter controls diff links and so must
			// include the undisplayed row beyond the display limit.
			$history .= $this->formatRevisionRow( $row, $earliestLiveTime, $numRevisions - $i );
		}
		$history .= Html::closeElement( 'ul' );
		return $history;
	}

	protected function showHistory() {
		$this->checkReadOnly();

		$out = $this->getOutput();
		if ( $this->mAllowed ) {
			$out->addModules( 'mediawiki.misc-authed-ooui' );
			$out->addModuleStyles( 'mediawiki.special' );
		}
		$out->addModuleStyles( 'mediawiki.interface.helpers.styles' );
		$out->wrapWikiMsg(
			"<div class='mw-undelete-pagetitle'>\n$1\n</div>\n",
			[ 'undeletepagetitle', wfEscapeWikiText( $this->mTargetObj->getPrefixedText() ) ]
		);

		$archive = new PageArchive( $this->mTargetObj );
		// FIXME: This hook must be deprecated, passing PageArchive by ref is awful.
		$this->getHookRunner()->onUndeleteForm__showHistory( $archive, $this->mTargetObj );

		$out->addHTML( Html::openElement( 'div', [ 'class' => 'mw-undelete-history' ] ) );
		if ( $this->mAllowed ) {
			$out->addWikiMsg( 'undeletehistory' );
			$out->addWikiMsg( 'undeleterevdel' );
		} else {
			$out->addWikiMsg( 'undeletehistorynoadmin' );
		}
		$out->addHTML( Html::closeElement( 'div' ) );

		# List all stored revisions
		$revisions = $this->archivedRevisionLookup->listRevisions(
			$this->mTargetObj,
			[],
			self::REVISION_HISTORY_LIMIT + 1
		);
		$files = $archive->listFiles();
		$numRevisions = $revisions->numRows();
		$showLoadMore = $numRevisions > self::REVISION_HISTORY_LIMIT;
		$haveRevisions = $numRevisions > 0;
		$haveFiles = $files && $files->numRows() > 0;

		# Batch existence check on user and talk pages
		if ( $haveRevisions || $haveFiles ) {
			$batch = $this->linkBatchFactory->newLinkBatch();
			$this->addRevisionsToBatch( $batch, $revisions );
			if ( $haveFiles ) {
				// @phan-suppress-next-line PhanTypeMismatchArgumentNullable -- $files is non-null
				$this->addFilesToBatch( $batch, $files );
			}
			$batch->execute();
		}

		if ( $this->mAllowed ) {
			$out->enableOOUI();

			$action = $this->getPageTitle()->getLocalURL( [ 'action' => 'submit' ] );
			# Start the form here
			$form = new FormLayout( [
				'method' => 'post',
				'action' => $action,
				'id' => 'undelete',
			] );
		}

		# Show relevant lines from the deletion log:
		$deleteLogPage = new LogPage( 'delete' );
		$out->addHTML( Html::element( 'h2', [], $deleteLogPage->getName()->text() ) . "\n" );
		LogEventsList::showLogExtract( $out, 'delete', $this->mTargetObj );
		# Show relevant lines from the suppression log:
		$suppressLogPage = new LogPage( 'suppress' );
		if ( $this->permissionManager->userHasRight( $this->getUser(), 'suppressionlog' ) ) {
			$out->addHTML( Html::element( 'h2', [], $suppressLogPage->getName()->text() ) . "\n" );
			LogEventsList::showLogExtract( $out, 'suppress', $this->mTargetObj );
		}

		if ( $this->mAllowed && ( $haveRevisions || $haveFiles ) ) {
			$unsuppressAllowed = $this->permissionManager->userHasRight( $this->getUser(), 'suppressrevision' );
			$fields = [];
			$fields[] = new Layout( [
				'content' => new HtmlSnippet( $this->msg( 'undeleteextrahelp' )->parseAsBlock() )
			] );

			$dropdownComment = $this->msg( 'undelete-comment-dropdown' )
				->page( $this->mTargetObj )->inContentLanguage()->text();
			// Add additional specific reasons for unsuppress
			if ( $unsuppressAllowed ) {
				$dropdownComment .= "\n" . $this->msg( 'undelete-comment-dropdown-unsuppress' )
					->page( $this->mTargetObj )->inContentLanguage()->text();
			}
			$options = Html::listDropdownOptions(
				$dropdownComment,
				[ 'other' => $this->msg( 'undeletecommentotherlist' )->text() ]
			);
			$options = Html::listDropdownOptionsOoui( $options );

			$fields[] = new FieldLayout(
				new DropdownInputWidget( [
					'name' => 'wpCommentList',
					'inputId' => 'wpCommentList',
					'infusable' => true,
					'value' => $this->getRequest()->getText( 'wpCommentList', 'other' ),
					'options' => $options,
				] ),
				[
					'label' => $this->msg( 'undeletecomment' )->text(),
					'align' => 'top',
				]
			);

			$fields[] = new FieldLayout(
				new TextInputWidget( [
					'name' => 'wpComment',
					'inputId' => 'wpComment',
					'infusable' => true,
					'value' => $this->getRequest()->getText( 'wpComment' ),
					'autofocus' => true,
					// HTML maxlength uses "UTF-16 code units", which means that characters outside BMP
					// (e.g. emojis) count for two each. This limit is overridden in JS to instead count
					// Unicode codepoints.
					'maxLength' => CommentStore::COMMENT_CHARACTER_LIMIT,
				] ),
				[
					'label' => $this->msg( 'undeleteothercomment' )->text(),
					'align' => 'top',
				]
			);

			if ( $this->getUser()->isRegistered() ) {
				$checkWatch = $this->watchlistManager->isWatched( $this->getUser(), $this->mTargetObj )
					|| $this->getRequest()->getText( 'wpWatch' );
				$fields[] = new FieldLayout(
					new CheckboxInputWidget( [
						'name' => 'wpWatch',
						'inputId' => 'mw-undelete-watch',
						'value' => '1',
						'selected' => $checkWatch,
					] ),
					[
						'label' => $this->msg( 'watchthis' )->text(),
						'align' => 'inline',
					]
				);
			}

			if ( $unsuppressAllowed ) {
				$fields[] = new FieldLayout(
					new CheckboxInputWidget( [
						'name' => 'wpUnsuppress',
						'inputId' => 'mw-undelete-unsuppress',
						'value' => '1',
					] ),
					[
						'label' => $this->msg( 'revdelete-unsuppress' )->text(),
						'align' => 'inline',
					]
				);
			}

			$undelPage = $this->undeletePageFactory->newUndeletePage(
				$this->wikiPageFactory->newFromTitle( $this->mTargetObj ),
				$this->getContext()->getAuthority()
			);
			if ( $undelPage->canProbablyUndeleteAssociatedTalk()->isGood() ) {
				$fields[] = new FieldLayout(
					new CheckboxInputWidget( [
						'name' => 'undeletetalk',
						'inputId' => 'mw-undelete-undeletetalk',
						'selected' => false,
					] ),
					[
						'label' => $this->msg( 'undelete-undeletetalk' )->text(),
						'align' => 'inline',
					]
				);
			}

			$fields[] = new FieldLayout(
				new Widget( [
					'content' => new HorizontalLayout( [
						'items' => [
							new ButtonInputWidget( [
								'name' => 'restore',
								'inputId' => 'mw-undelete-submit',
								'value' => '1',
								'label' => $this->msg( 'undeletebtn' )->text(),
								'flags' => [ 'primary', 'progressive' ],
								'type' => 'submit',
							] ),
							new ButtonInputWidget( [
								'name' => 'invert',
								'inputId' => 'mw-undelete-invert',
								'value' => '1',
								'label' => $this->msg( 'undeleteinvert' )->text()
							] ),
						]
					] )
				] )
			);

			$fieldset = new FieldsetLayout( [
				'label' => $this->msg( 'undelete-fieldset-title' )->text(),
				'id' => 'mw-undelete-table',
				'items' => $fields,
			] );

			$link = '';
			if ( $this->getAuthority()->isAllowed( 'editinterface' ) ) {
				if ( $unsuppressAllowed ) {
					$link .= $this->getLinkRenderer()->makeKnownLink(
						$this->msg( 'undelete-comment-dropdown-unsuppress' )->inContentLanguage()->getTitle(),
						$this->msg( 'undelete-edit-commentlist-unsuppress' )->text(),
						[],
						[ 'action' => 'edit' ]
					);
					$link .= $this->msg( 'pipe-separator' )->escaped();
				}
				$link .= $this->getLinkRenderer()->makeKnownLink(
					$this->msg( 'undelete-comment-dropdown' )->inContentLanguage()->getTitle(),
					$this->msg( 'undelete-edit-commentlist' )->text(),
					[],
					[ 'action' => 'edit' ]
				);

				$link = Html::rawElement( 'p', [ 'class' => 'mw-undelete-editcomments' ], $link );
			}

			// @phan-suppress-next-line PhanPossiblyUndeclaredVariable form is set, when used here
			$form->appendContent(
				new PanelLayout( [
					'expanded' => false,
					'padded' => true,
					'framed' => true,
					'content' => $fieldset,
				] ),
				new HtmlSnippet(
					$link .
					Html::hidden( 'target', $this->mTarget ) .
					Html::hidden( 'wpEditToken', $this->getUser()->getEditToken() )
				)
			);
		}

		$history = '';
		$history .= Html::element( 'h2', [], $this->msg( 'history' )->text() ) . "\n";

		if ( $haveRevisions ) {
			# Show the page's stored (deleted) history

			if ( $this->mAllowed && $this->permissionManager->userHasRight( $this->getUser(), 'deleterevision' ) ) {
				$history .= Html::element(
					'button',
					[
						'name' => 'revdel',
						'type' => 'submit',
						'class' => [ 'deleterevision-log-submit', 'mw-log-deleterevision-button' ]
					],
					$this->msg( 'showhideselectedversions' )->text()
				) . "\n";
			}

			$history .= $this->formatRevisionHistory( $revisions );

			if ( $showLoadMore ) {
				$history .=
					Html::openElement( 'div' ) .
					Html::element(
						'span',
						[ 'id' => 'mw-load-more-revisions' ],
						$this->msg( 'undelete-load-more-revisions' )->text()
					) .
					Html::closeElement( 'div' ) .
					"\n";
			}
		} else {
			$out->addWikiMsg( 'nohistory' );
		}

		if ( $haveFiles ) {
			$history .= Html::element( 'h2', [], $this->msg( 'filehist' )->text() ) . "\n";
			$history .= Html::openElement( 'ul', [ 'class' => 'mw-undelete-revlist' ] );
			foreach ( $files as $row ) {
				$history .= $this->formatFileRow( $row );
			}
			$files->free();
			$history .= Html::closeElement( 'ul' );
		}

		if ( $this->mAllowed ) {
			# Slip in the hidden controls here
			$misc = Html::hidden( 'target', $this->mTarget );
			$misc .= Html::hidden( 'wpEditToken', $this->getUser()->getEditToken() );
			$history .= $misc;

			// @phan-suppress-next-line PhanPossiblyUndeclaredVariable form is set, when used here
			$form->appendContent( new HtmlSnippet( $history ) );
			// @phan-suppress-next-line PhanPossiblyUndeclaredVariable form is set, when used here
			$out->addHTML( (string)$form );
		} else {
			$out->addHTML( $history );
		}

		return true;
	}

	protected function formatRevisionRow( $row, $earliestLiveTime, $remaining ) {
		$revRecord = $this->revisionStore->newRevisionFromArchiveRow(
				$row,
				IDBAccessObject::READ_NORMAL,
				$this->mTargetObj
			);

		$revTextSize = '';
		$ts = wfTimestamp( TS_MW, $row->ar_timestamp );
		// Build checkboxen...
		if ( $this->mAllowed ) {
			if ( $this->mInvert ) {
				if ( in_array( $ts, $this->mTargetTimestamp ) ) {
					$checkBox = Html::check( "ts$ts" );
				} else {
					$checkBox = Html::check( "ts$ts", true );
				}
			} else {
				$checkBox = Html::check( "ts$ts" );
			}
		} else {
			$checkBox = '';
		}

		// Build page & diff links...
		$user = $this->getUser();
		if ( $this->mCanView ) {
			$titleObj = $this->getPageTitle();
			# Last link
			if ( !$revRecord->userCan( RevisionRecord::DELETED_TEXT, $this->getAuthority() ) ) {
				$pageLink = htmlspecialchars( $this->getLanguage()->userTimeAndDate( $ts, $user ) );
				$last = $this->msg( 'diff' )->escaped();
			} elseif ( $remaining > 0 || ( $earliestLiveTime && $ts > $earliestLiveTime ) ) {
				$pageLink = $this->getPageLink( $revRecord, $titleObj, $ts );
				$last = $this->getLinkRenderer()->makeKnownLink(
					$titleObj,
					$this->msg( 'diff' )->text(),
					[],
					[
						'target' => $this->mTargetObj->getPrefixedText(),
						'timestamp' => $ts,
						'diff' => 'prev'
					]
				);
			} else {
				$pageLink = $this->getPageLink( $revRecord, $titleObj, $ts );
				$last = $this->msg( 'diff' )->escaped();
			}
		} else {
			$pageLink = htmlspecialchars( $this->getLanguage()->userTimeAndDate( $ts, $user ) );
			$last = $this->msg( 'diff' )->escaped();
		}

		// User links
		$userLink = Linker::revUserTools( $revRecord );

		// Minor edit
		$minor = $revRecord->isMinor() ? ChangesList::flag( 'minor' ) : '';

		// Revision text size
		$size = $row->ar_len;
		if ( $size !== null ) {
			$revTextSize = Linker::formatRevisionSize( $size );
		}

		// Edit summary
		$comment = $this->commentFormatter->formatRevision( $revRecord, $user );

		// Tags
		$attribs = [];
		[ $tagSummary, $classes ] = ChangeTags::formatSummaryRow(
			$row->ts_tags,
			'deletedhistory',
			$this->getContext()
		);
		$attribs['class'] = $classes;

		$revisionRow = $this->msg( 'undelete-revision-row2' )
			->rawParams(
				$checkBox,
				$last,
				$pageLink,
				$userLink,
				$minor,
				$revTextSize,
				$comment,
				$tagSummary
			)
			->escaped();

		return Html::rawElement( 'li', $attribs, $revisionRow ) . "\n";
	}

	private function formatFileRow( \stdClass $row ): string {
		$file = ArchivedFile::newFromRow( $row );
		$ts = wfTimestamp( TS_MW, $row->fa_timestamp );
		$user = $this->getUser();

		$checkBox = '';
		if ( $this->mCanView && $row->fa_storage_key ) {
			if ( $this->mAllowed ) {
				$checkBox = Html::check( 'fileid' . $row->fa_id );
			}
			$key = urlencode( $row->fa_storage_key );
			$pageLink = $this->getFileLink( $file, $this->getPageTitle(), $ts, $key );
		} else {
			$pageLink = htmlspecialchars( $this->getLanguage()->userTimeAndDate( $ts, $user ) );
		}
		$userLink = $this->getFileUser( $file );
		$data = $this->msg( 'widthheight' )->numParams( $row->fa_width, $row->fa_height )->text();
		$bytes = $this->msg( 'parentheses' )
			->plaintextParams( $this->msg( 'nbytes' )->numParams( $row->fa_size )->text() )
			->plain();
		$data = htmlspecialchars( $data . ' ' . $bytes );
		$comment = $this->getFileComment( $file );

		// Add show/hide deletion links if available
		$canHide = $this->isAllowed( 'deleterevision' );
		if ( $canHide || ( $file->getVisibility() && $this->isAllowed( 'deletedhistory' ) ) ) {
			if ( !$file->userCan( File::DELETED_RESTRICTED, $user ) ) {
				// Revision was hidden from sysops
				$revdlink = Linker::revDeleteLinkDisabled( $canHide );
			} else {
				$query = [
					'type' => 'filearchive',
					'target' => $this->mTargetObj->getPrefixedDBkey(),
					'ids' => $row->fa_id
				];
				$revdlink = Linker::revDeleteLink( $query,
					$file->isDeleted( File::DELETED_RESTRICTED ), $canHide );
			}
		} else {
			$revdlink = '';
		}

		return "<li>$checkBox $revdlink $pageLink . . $userLink $data $comment</li>\n";
	}

	/**
	 * Fetch revision text link if it's available to all users
	 *
	 * @param RevisionRecord $revRecord
	 * @param LinkTarget $target
	 * @param string $ts Timestamp
	 * @return string
	 */
	private function getPageLink( RevisionRecord $revRecord, LinkTarget $target, $ts ) {
		$user = $this->getUser();
		$time = $this->getLanguage()->userTimeAndDate( $ts, $user );

		if ( !$revRecord->userCan( RevisionRecord::DELETED_TEXT, $this->getAuthority() ) ) {
			// TODO The condition cannot be true when the function is called
			return Html::element(
				'span',
				[ 'class' => 'history-deleted' ],
				$time
			);
		}

		$link = $this->getLinkRenderer()->makeKnownLink(
			$target,
			$time,
			[],
			[
				'target' => $this->mTargetObj->getPrefixedText(),
				'timestamp' => $ts
			]
		);

		if ( $revRecord->isDeleted( RevisionRecord::DELETED_TEXT ) ) {
			$class = Linker::getRevisionDeletedClass( $revRecord );
			$link = '<span class="' . $class . '">' . $link . '</span>';
		}

		return $link;
	}

	/**
	 * Fetch image view link if it's available to all users
	 *
	 * @param File|ArchivedFile $file
	 * @param LinkTarget $target
	 * @param string $ts A timestamp
	 * @param string $key A storage key
	 *
	 * @return string HTML fragment
	 */
	private function getFileLink( $file, LinkTarget $target, $ts, $key ) {
		$user = $this->getUser();
		$time = $this->getLanguage()->userTimeAndDate( $ts, $user );

		if ( !$file->userCan( File::DELETED_FILE, $user ) ) {
			return Html::element(
				'span',
				[ 'class' => 'history-deleted' ],
				$time
			);
		}

		if ( $file->exists() ) {
			$link = $this->getLinkRenderer()->makeKnownLink(
				$target,
				$time,
				[],
				[
					'target' => $this->mTargetObj->getPrefixedText(),
					'file' => $key,
					'token' => $user->getEditToken( $key )
				]
			);
		} else {
			$link = htmlspecialchars( $time );
		}

		if ( $file->isDeleted( File::DELETED_FILE ) ) {
			$link = '<span class="history-deleted">' . $link . '</span>';
		}

		return $link;
	}

	/**
	 * Fetch file's user id if it's available to this user
	 *
	 * @param File|ArchivedFile $file
	 * @return string HTML fragment
	 */
	private function getFileUser( $file ) {
		$uploader = $file->getUploader( File::FOR_THIS_USER, $this->getAuthority() );
		if ( !$uploader ) {
			return Html::rawElement(
				'span',
				[ 'class' => 'history-deleted' ],
				$this->msg( 'rev-deleted-user' )->escaped()
			);
		}

		$link = Linker::userLink( $uploader->getId(), $uploader->getName() ) .
			Linker::userToolLinks( $uploader->getId(), $uploader->getName() );

		if ( $file->isDeleted( File::DELETED_USER ) ) {
			$link = Html::rawElement(
				'span',
				[ 'class' => 'history-deleted' ],
				$link
			);
		}

		return $link;
	}

	/**
	 * Fetch file upload comment if it's available to this user
	 *
	 * @param File|ArchivedFile $file
	 * @return string HTML fragment
	 */
	private function getFileComment( $file ) {
		if ( !$file->userCan( File::DELETED_COMMENT, $this->getAuthority() ) ) {
			return Html::rawElement(
				'span',
				[ 'class' => 'history-deleted' ],
				Html::rawElement(
					'span',
					[ 'class' => 'comment' ],
					$this->msg( 'rev-deleted-comment' )->escaped()
				)
			);
		}

		$comment = $file->getDescription( File::FOR_THIS_USER, $this->getAuthority() );
		$link = $this->commentFormatter->formatBlock( $comment );

		if ( $file->isDeleted( File::DELETED_COMMENT ) ) {
			$link = Html::rawElement(
				'span',
				[ 'class' => 'history-deleted' ],
				$link
			);
		}

		return $link;
	}

	private function undelete() {
		if ( $this->getConfig()->get( MainConfigNames::UploadMaintenance )
			&& $this->mTargetObj->getNamespace() === NS_FILE
		) {
			throw new ErrorPageError( 'undelete-error', 'filedelete-maintenance' );
		}

		$this->checkReadOnly();

		$out = $this->getOutput();
		$undeletePage = $this->undeletePageFactory->newUndeletePage(
			$this->wikiPageFactory->newFromTitle( $this->mTargetObj ),
			$this->getAuthority()
		);
		if ( $this->mUndeleteTalk && $undeletePage->canProbablyUndeleteAssociatedTalk()->isGood() ) {
			$undeletePage->setUndeleteAssociatedTalk( true );
		}
		$status = $undeletePage
			->setUndeleteOnlyTimestamps( $this->mTargetTimestamp )
			->setUndeleteOnlyFileVersions( $this->mFileVersions )
			->setUnsuppress( $this->mUnsuppress )
			// TODO This is currently duplicating some permission checks, but we do need it (T305680)
			->undeleteIfAllowed( $this->mComment );

		if ( !$status->isGood() ) {
			$out->setPageTitleMsg( $this->msg( 'undelete-error' ) );
			foreach ( $status->getMessages() as $msg ) {
				$out->addHTML( Html::errorBox(
					$this->msg( $msg )->parse()
				) );
			}
			return;
		}

		$restoredRevs = $status->getValue()[UndeletePage::REVISIONS_RESTORED];
		$restoredFiles = $status->getValue()[UndeletePage::FILES_RESTORED];

		if ( $restoredRevs === 0 && $restoredFiles === 0 ) {
			// TODO Should use a different message here
			$out->setPageTitleMsg( $this->msg( 'undelete-error' ) );
		} else {
			if ( $status->getValue()[UndeletePage::FILES_RESTORED] !== 0 ) {
				$this->getHookRunner()->onFileUndeleteComplete(
					$this->mTargetObj, $this->mFileVersions, $this->getUser(), $this->mComment );
			}

			$link = $this->getLinkRenderer()->makeKnownLink( $this->mTargetObj );
			$out->addWikiMsg( 'undeletedpage', Message::rawParam( $link ) );

			$this->watchlistManager->setWatch(
				$this->getRequest()->getCheck( 'wpWatch' ),
				$this->getAuthority(),
				$this->mTargetObj
			);
		}
	}

	/**
	 * Return an array of subpages beginning with $search that this special page will accept.
	 *
	 * @param string $search Prefix to search for
	 * @param int $limit Maximum number of results to return (usually 10)
	 * @param int $offset Number of results to skip (usually 0)
	 * @return string[] Matching subpages
	 */
	public function prefixSearchSubpages( $search, $limit, $offset ) {
		return $this->prefixSearchString( $search, $limit, $offset, $this->searchEngineFactory );
	}

	protected function getGroupName() {
		return 'pagetools';
	}
}

/**
 * Retain the old class name for backwards compatibility.
 * @deprecated since 1.41
 */
class_alias( SpecialUndelete::class, 'SpecialUndelete' );
