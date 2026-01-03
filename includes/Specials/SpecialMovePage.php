<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Specials;

use MediaWiki\Actions\WatchAction;
use MediaWiki\Cache\LinkBatchFactory;
use MediaWiki\CommentStore\CommentStore;
use MediaWiki\Content\IContentHandlerFactory;
use MediaWiki\Exception\ErrorPageError;
use MediaWiki\Exception\PermissionsError;
use MediaWiki\Exception\ThrottledError;
use MediaWiki\FileRepo\RepoGroup;
use MediaWiki\Html\Html;
use MediaWiki\JobQueue\Jobs\DoubleRedirectJob;
use MediaWiki\Linker\Linker;
use MediaWiki\Logging\LogEventsList;
use MediaWiki\Logging\LogPage;
use MediaWiki\MainConfigNames;
use MediaWiki\Page\DeletePageFactory;
use MediaWiki\Page\MovePageFactory;
use MediaWiki\Page\WikiPageFactory;
use MediaWiki\Permissions\PermissionManager;
use MediaWiki\Permissions\PermissionStatus;
use MediaWiki\Permissions\RestrictionStore;
use MediaWiki\Search\SearchEngineFactory;
use MediaWiki\SpecialPage\UnlistedSpecialPage;
use MediaWiki\Status\Status;
use MediaWiki\Title\NamespaceInfo;
use MediaWiki\Title\Title;
use MediaWiki\Title\TitleArrayFromResult;
use MediaWiki\Title\TitleFactory;
use MediaWiki\User\Options\UserOptionsLookup;
use MediaWiki\User\User;
use MediaWiki\Watchlist\WatchedItemStore;
use MediaWiki\Watchlist\WatchlistManager;
use MediaWiki\Widget\ComplexTitleInputWidget;
use OOUI\ButtonInputWidget;
use OOUI\CheckboxInputWidget;
use OOUI\DropdownInputWidget;
use OOUI\FieldLayout;
use OOUI\FieldsetLayout;
use OOUI\FormLayout;
use OOUI\HtmlSnippet;
use OOUI\PanelLayout;
use OOUI\TextInputWidget;
use StatusValue;
use Wikimedia\ParamValidator\TypeDef\ExpiryDef;
use Wikimedia\Rdbms\IConnectionProvider;
use Wikimedia\Rdbms\IDBAccessObject;
use Wikimedia\Timestamp\TimestampFormat as TS;

/**
 * Implement Special:Movepage for changing page titles
 *
 * @ingroup SpecialPage
 */
class SpecialMovePage extends UnlistedSpecialPage {
	/** @var Title */
	protected $oldTitle = null;

	/** @var Title */
	protected $newTitle;

	/** @var string Text input */
	protected $reason;

	/** @var bool */
	protected $moveTalk;

	/** @var bool */
	protected $deleteAndMove;

	/** @var bool */
	protected $moveSubpages;

	/** @var bool */
	protected $fixRedirects;

	/** @var bool */
	protected $leaveRedirect;

	/** @var bool */
	protected $moveOverShared;

	private bool $moveOverProtection;

	/** @var bool */
	private $watch = false;

	private MovePageFactory $movePageFactory;
	private PermissionManager $permManager;
	private UserOptionsLookup $userOptionsLookup;
	private IConnectionProvider $dbProvider;
	private IContentHandlerFactory $contentHandlerFactory;
	private NamespaceInfo $nsInfo;
	private LinkBatchFactory $linkBatchFactory;
	private RepoGroup $repoGroup;
	private WikiPageFactory $wikiPageFactory;
	private SearchEngineFactory $searchEngineFactory;
	private WatchlistManager $watchlistManager;
	private WatchedItemStore $watchedItemStore;
	private RestrictionStore $restrictionStore;
	private TitleFactory $titleFactory;
	private DeletePageFactory $deletePageFactory;

	public function __construct(
		MovePageFactory $movePageFactory,
		PermissionManager $permManager,
		UserOptionsLookup $userOptionsLookup,
		IConnectionProvider $dbProvider,
		IContentHandlerFactory $contentHandlerFactory,
		NamespaceInfo $nsInfo,
		LinkBatchFactory $linkBatchFactory,
		RepoGroup $repoGroup,
		WikiPageFactory $wikiPageFactory,
		SearchEngineFactory $searchEngineFactory,
		WatchlistManager $watchlistManager,
		WatchedItemStore $watchedItemStore,
		RestrictionStore $restrictionStore,
		TitleFactory $titleFactory,
		DeletePageFactory $deletePageFactory
	) {
		parent::__construct( 'Movepage' );
		$this->movePageFactory = $movePageFactory;
		$this->permManager = $permManager;
		$this->userOptionsLookup = $userOptionsLookup;
		$this->dbProvider = $dbProvider;
		$this->contentHandlerFactory = $contentHandlerFactory;
		$this->nsInfo = $nsInfo;
		$this->linkBatchFactory = $linkBatchFactory;
		$this->repoGroup = $repoGroup;
		$this->wikiPageFactory = $wikiPageFactory;
		$this->searchEngineFactory = $searchEngineFactory;
		$this->watchlistManager = $watchlistManager;
		$this->watchedItemStore = $watchedItemStore;
		$this->restrictionStore = $restrictionStore;
		$this->titleFactory = $titleFactory;
		$this->deletePageFactory = $deletePageFactory;
	}

	/** @inheritDoc */
	public function doesWrites() {
		return true;
	}

	/** @inheritDoc */
	public function execute( $par ) {
		$this->useTransactionalTimeLimit();
		$this->checkReadOnly();
		$this->setHeaders();
		$this->outputHeader();

		$request = $this->getRequest();

		// Beware: The use of WebRequest::getText() is wanted! See T22365
		$target = $par ?? $request->getText( 'target' );
		$oldTitleText = $request->getText( 'wpOldTitle', $target );
		$this->oldTitle = Title::newFromText( $oldTitleText );

		if ( !$this->oldTitle ) {
			// Either oldTitle wasn't passed, or newFromText returned null
			throw new ErrorPageError( 'notargettitle', 'notargettext' );
		}
		$this->getOutput()->addBacklinkSubtitle( $this->oldTitle );
		// Various uses of Html::errorBox and Html::warningBox.
		$this->getOutput()->addModuleStyles( 'mediawiki.codex.messagebox.styles' );

		if ( !$this->oldTitle->exists() ) {
			throw new ErrorPageError( 'nopagetitle', 'nopagetext' );
		}

		$newTitleTextMain = $request->getText( 'wpNewTitleMain' );
		$newTitleTextNs = $request->getInt( 'wpNewTitleNs', $this->oldTitle->getNamespace() );
		// Backwards compatibility for forms submitting here from other sources
		// which is more common than it should be.
		$newTitleText_bc = $request->getText( 'wpNewTitle' );
		$this->newTitle = strlen( $newTitleText_bc ) > 0
			? Title::newFromText( $newTitleText_bc )
			: Title::makeTitleSafe( $newTitleTextNs, $newTitleTextMain );

		$user = $this->getUser();
		$isSubmit = $request->getRawVal( 'action' ) === 'submit' && $request->wasPosted();

		$reasonList = $request->getText( 'wpReasonList', 'other' );
		$reason = $request->getText( 'wpReason' );
		if ( $reasonList === 'other' ) {
			$this->reason = $reason;
		} elseif ( $reason !== '' ) {
			$this->reason = $reasonList . $this->msg( 'colon-separator' )->inContentLanguage()->text() . $reason;
		} else {
			$this->reason = $reasonList;
		}
		// Default to checked, but don't fill in true during submission (browsers only submit checked values)
		// TODO: Use HTMLForm to take care of this.
		$def = !$isSubmit;
		$this->moveTalk = $request->getBool( 'wpMovetalk', $def );
		$this->fixRedirects = $request->getBool( 'wpFixRedirects', $def );
		$this->leaveRedirect = $request->getBool( 'wpLeaveRedirect', $def );
		// T222953: Tick the "move subpages" box by default
		$this->moveSubpages = $request->getBool( 'wpMovesubpages', $def );
		$this->deleteAndMove = $request->getBool( 'wpDeleteAndMove' );
		$this->moveOverShared = $request->getBool( 'wpMoveOverSharedFile' );
		$this->moveOverProtection = $request->getBool( 'wpMoveOverProtection' );
		$this->watch = $request->getCheck( 'wpWatch' ) && $user->isRegistered();

		// Similar to other SpecialPage/Action classes, when tokens fail (likely due to reset or expiry),
		// do not show an error but show the form again for easy re-submit.
		if ( $isSubmit && $user->matchEditToken( $request->getVal( 'wpEditToken' ) ) ) {
			// Check rights
			$permStatus = $this->permManager->getPermissionStatus( 'move', $user, $this->oldTitle,
				PermissionManager::RIGOR_SECURE );
			// If the account is "hard" blocked, auto-block IP
			$user->scheduleSpreadBlock();
			if ( !$permStatus->isGood() ) {
				throw new PermissionsError( 'move', $permStatus );
			}
			$this->doSubmit();
		} else {
			// Avoid primary DB connection on form view (T283265)
			$permStatus = $this->permManager->getPermissionStatus( 'move', $user, $this->oldTitle,
				PermissionManager::RIGOR_FULL );
			if ( !$permStatus->isGood() ) {
				$user->scheduleSpreadBlock();
				throw new PermissionsError( 'move', $permStatus );
			}
			$this->showForm();
		}
	}

	/**
	 * Show the form
	 *
	 * @param ?StatusValue $status Form submission status.
	 *   If it is a PermissionStatus, a special message will be shown.
	 * @param ?StatusValue $talkStatus Status for an attempt to move the talk page
	 */
	private function showForm( ?StatusValue $status = null, ?StatusValue $talkStatus = null ) {
		$this->getSkin()->setRelevantTitle( $this->oldTitle );

		$out = $this->getOutput();
		$out->setPageTitleMsg( $this->msg( 'move-page' )->plaintextParams( $this->oldTitle->getPrefixedText() ) );
		$out->addModuleStyles( [
			'mediawiki.special',
			'mediawiki.interface.helpers.styles'
		] );
		$out->addModules( 'mediawiki.misc-authed-ooui' );
		$this->addHelpLink( 'Help:Moving a page' );

		$handler = $this->contentHandlerFactory
			->getContentHandler( $this->oldTitle->getContentModel() );
		$createRedirect = $handler->supportsRedirects() && !(
			// Do not create redirects for wikitext message overrides (T376399).
			// Maybe one day they will have a custom content model and this special case won't be needed.
			$this->oldTitle->getNamespace() === NS_MEDIAWIKI &&
			$this->oldTitle->getContentModel() === CONTENT_MODEL_WIKITEXT
		);

		if ( $this->getConfig()->get( MainConfigNames::FixDoubleRedirects ) ) {
			$out->addWikiMsg( 'movepagetext' );
		} else {
			$out->addWikiMsg( $createRedirect ?
				'movepagetext-noredirectfixer' :
				'movepagetext-noredirectsupport' );
		}

		if ( $this->oldTitle->getNamespace() === NS_USER && !$this->oldTitle->isSubpage() ) {
			$out->addHTML(
				Html::warningBox(
					$out->msg( 'moveuserpage-warning' )->parse(),
					'mw-moveuserpage-warning'
				)
			);
			// Deselect moveTalk unless it's explicitly given
			$this->moveTalk = $this->getRequest()->getBool( "wpMovetalk", false );
		} elseif ( $this->oldTitle->getNamespace() === NS_CATEGORY ) {
			$out->addHTML(
				Html::warningBox(
					$out->msg( 'movecategorypage-warning' )->parse(),
					'mw-movecategorypage-warning'
				)
			);
		}

		$deleteAndMove = [];
		$moveOverShared = false;

		$user = $this->getUser();
		$newTitle = $this->newTitle;
		$oldTalk = $this->oldTitle->getTalkPageIfDefined();

		if ( !$newTitle ) {
			# Show the current title as a default
			# when the form is first opened.
			$newTitle = $this->oldTitle;
		} elseif ( !$status ) {
			# If a title was supplied, probably from the move log revert
			# link, check for validity. We can then show some diagnostic
			# information and save a click.
			$mp = $this->movePageFactory->newMovePage( $this->oldTitle, $newTitle );
			$status = $mp->isValidMove();
			$status->merge( $mp->probablyCanMove( $this->getAuthority() ) );
			if ( $this->moveTalk ) {
				$newTalk = $newTitle->getTalkPageIfDefined();
				if ( $oldTalk && $newTalk ) {
					$mpTalk = $this->movePageFactory->newMovePage( $oldTalk, $newTalk );
					$talkStatus = $mpTalk->isValidMove();
					$talkStatus->merge( $mpTalk->probablyCanMove( $this->getAuthority() ) );
				}
			}
		}
		if ( !$status ) {
			// Caller (execute) is responsible for checking that you have permission to move the page somewhere
			$status = StatusValue::newGood();
		}
		if ( !$talkStatus ) {
			if ( $oldTalk ) {
				// If you don't have permission to move the talk page anywhere then complain about that now
				// rather than only after submitting the form to move the page
				$talkStatus = $this->permManager->getPermissionStatus( 'move', $user, $oldTalk,
					PermissionManager::RIGOR_QUICK );
			} else {
				// If there's no talk page to move (for example the old page is in a namespace with no talk page)
				// then this needs to be set to something ...
				$talkStatus = StatusValue::newGood();
			}
		}

		$oldTalk = $this->oldTitle->getTalkPageIfDefined();
		$oldTitleSubpages = $this->oldTitle->hasSubpages();
		$oldTitleTalkSubpages = $this->oldTitle->getTalkPageIfDefined()->hasSubpages();

		$canMoveSubpage = ( $oldTitleSubpages || $oldTitleTalkSubpages ) &&
			$this->permManager->quickUserCan(
				'move-subpages',
				$user,
				$this->oldTitle
			);
		# We also want to be able to move assoc. subpage talk-pages even if base page
		# has no associated talk page, so || with $oldTitleTalkSubpages.
		$considerTalk = !$this->oldTitle->isTalkPage() &&
			( $oldTalk->exists()
				|| ( $oldTitleTalkSubpages && $canMoveSubpage ) );

		if ( $this->getConfig()->get( MainConfigNames::FixDoubleRedirects ) ) {
			$queryBuilder = $this->dbProvider->getReplicaDatabase()->newSelectQueryBuilder()
				->select( '1' )
				->from( 'redirect' )
				->where( [ 'rd_namespace' => $this->oldTitle->getNamespace() ] )
				->andWhere( [ 'rd_title' => $this->oldTitle->getDBkey() ] )
				->andWhere( [ 'rd_interwiki' => '' ] );

			$hasRedirects = (bool)$queryBuilder->caller( __METHOD__ )->fetchField();
		} else {
			$hasRedirects = false;
		}

		$newTalkTitle = $newTitle->getTalkPageIfDefined();
		$talkOK = $talkStatus->isOK();
		$mainOK = $status->isOK();
		$talkIsArticle = $talkIsRedirect = $mainIsArticle = $mainIsRedirect = false;
		if ( count( $status->getMessages() ) == 1 ) {
			$mainIsArticle = $status->hasMessage( 'articleexists' )
				&& $this->permManager->quickUserCan( 'delete', $user, $newTitle );
			$mainIsRedirect = $status->hasMessage( 'redirectexists' ) && (
				// Any user that can delete normally can also delete a redirect here
				$this->permManager->quickUserCan( 'delete-redirect', $user, $newTitle ) ||
				$this->permManager->quickUserCan( 'delete', $user, $newTitle ) );
			if ( $status->hasMessage( 'file-exists-sharedrepo' )
				&& $this->permManager->userHasRight( $user, 'reupload-shared' )
			) {
				$out->addHTML(
					Html::warningBox(
						$out->msg( 'move-over-sharedrepo', $newTitle->getPrefixedText() )->parse()
					)
				);
				$moveOverShared = true;
				$status = StatusValue::newGood();
			}
		}
		if ( count( $talkStatus->getMessages() ) == 1 ) {
			$talkIsArticle = $talkStatus->hasMessage( 'articleexists' )
				&& $this->permManager->quickUserCan( 'delete', $user, $newTitle );
			$talkIsRedirect = $talkStatus->hasMessage( 'redirectexists' ) && (
				// Any user that can delete normally can also delete a redirect here
				$this->permManager->quickUserCan( 'delete-redirect', $user, $newTitle ) ||
				$this->permManager->quickUserCan( 'delete', $user, $newTitle ) );
			// Talk page is by definition not a file so can't be shared
		}
		$warning = null;
		// Case 1: Two pages need deletions of full history
		// Either both are articles or one is an article and one is a redirect
		if ( ( $talkIsArticle && $mainIsArticle ) ||
			 ( $talkIsArticle && $mainIsRedirect ) ||
			 ( $talkIsRedirect && $mainIsArticle )
		) {
			$warning = $out->msg( 'delete_and_move_text_2',
				$newTitle->getPrefixedText(),
				$newTalkTitle->getPrefixedText()
			);
			$deleteAndMove = [ $newTitle, $newTalkTitle ];
		// Case 2: Both need simple deletes
		} elseif ( $mainIsRedirect && $talkIsRedirect ) {
			$warning = $out->msg( 'delete_redirect_and_move_text_2',
				$newTitle->getPrefixedText(),
				$newTalkTitle->getPrefixedText()
			);
			$deleteAndMove = [ $newTitle, $newTalkTitle ];
		// Case 3: The main page needs a full delete, the talk doesn't exist
		// (or is a single-rev redirect to the source we can silently ignore)
		} elseif ( $mainIsArticle && $talkOK ) {
			$warning = $out->msg( 'delete_and_move_text', $newTitle->getPrefixedText() );
			$deleteAndMove = [ $newTitle ];
		// Case 4: The main page needs a simple delete, the talk doesn't exist
		} elseif ( $mainIsRedirect && $talkOK ) {
			$warning = $out->msg( 'delete_redirect_and_move_text', $newTitle->getPrefixedText() );
			$deleteAndMove = [ $newTitle ];
		// Cases 5 and 6: The same for the talk page
		} elseif ( $talkIsArticle && $mainOK ) {
			$warning = $out->msg( 'delete_and_move_text', $newTalkTitle->getPrefixedText() );
			$deleteAndMove = [ $newTalkTitle ];
		} elseif ( $talkIsRedirect && $mainOK ) {
			$warning = $out->msg( 'delete_redirect_and_move_text', $newTalkTitle->getPrefixedText() );
			$deleteAndMove = [ $newTalkTitle ];
		}
		if ( $warning ) {
			$out->addHTML( Html::warningBox( $warning->parse() ) );
		} else {
			$messages = $status->getMessages();
			if ( $messages ) {
				if ( $status instanceof PermissionStatus ) {
					$action_desc = $this->msg( 'action-move' )->plain();
					$errMsgHtml = $this->msg( 'permissionserrorstext-withaction',
						count( $messages ), $action_desc )->parseAsBlock();
				} else {
					$errMsgHtml = $this->msg( 'cannotmove', count( $messages ) )->parseAsBlock();
				}

				if ( count( $messages ) == 1 ) {
					$errMsgHtml .= $this->msg( $messages[0] )->parseAsBlock();
				} else {
					$errStr = [];

					foreach ( $messages as $msg ) {
						$errStr[] = $this->msg( $msg )->parse();
					}

					$errMsgHtml .= '<ul><li>' . implode( "</li>\n<li>", $errStr ) . "</li></ul>\n";
				}
				$out->addHTML( Html::errorBox( $errMsgHtml ) );
			}
			$talkMessages = $talkStatus->getMessages();
			if ( $talkMessages ) {
				// Can't use permissionerrorstext here since there's no specific action for moving the talk page
				$errMsgHtml = $this->msg( 'cannotmovetalk', count( $talkMessages ) )->parseAsBlock();

				if ( count( $talkMessages ) == 1 ) {
					$errMsgHtml .= $this->msg( $talkMessages[0] )->parseAsBlock();
				} else {
					$errStr = [];

					foreach ( $talkMessages as $msg ) {
						$errStr[] = $this->msg( $msg )->parse();
					}

					$errMsgHtml .= '<ul><li>' . implode( "</li>\n<li>", $errStr ) . "</li></ul>\n";
				}
				$errMsgHtml .= $this->msg( 'movetalk-unselect' )->parse();
				$out->addHTML( Html::errorBox( $errMsgHtml ) );
			}
		}

		if ( $this->restrictionStore->isProtected( $this->oldTitle, 'move' ) ) {
			# Is the title semi-protected?
			if ( $this->restrictionStore->isSemiProtected( $this->oldTitle, 'move' ) ) {
				$noticeMsg = 'semiprotectedpagemovewarning';
			} else {
				# Then it must be protected based on static groups (regular)
				$noticeMsg = 'protectedpagemovewarning';
			}
			LogEventsList::showLogExtract(
				$out,
				'protect',
				$this->oldTitle,
				'',
				[ 'lim' => 1, 'msgKey' => $noticeMsg ]
			);
		}
		// Intentionally don't check moveTalk here since this is in the form before you specify whether
		// to move the talk page
		if ( $talkOK && $oldTalk && $oldTalk->exists() && $this->restrictionStore->isProtected( $oldTalk, 'move' ) ) {
			# Is the title semi-protected?
			if ( $this->restrictionStore->isSemiProtected( $oldTalk, 'move' ) ) {
				$noticeMsg = 'semiprotectedtalkpagemovewarning';
			} else {
				# Then it must be protected based on static groups (regular)
				$noticeMsg = 'protectedtalkpagemovewarning';
			}
			LogEventsList::showLogExtract(
				$out,
				'protect',
				$oldTalk,
				'',
				[ 'lim' => 1, 'msgKey' => $noticeMsg ]
			);
		}

		// Length limit for wpReason and wpNewTitleMain is enforced in the
		// mediawiki.special.movePage module

		$immovableNamespaces = [];
		foreach ( $this->getLanguage()->getNamespaces() as $nsId => $_ ) {
			if ( !$this->nsInfo->isMovable( $nsId ) ) {
				$immovableNamespaces[] = $nsId;
			}
		}

		$moveOverProtection = false;
		if ( $this->newTitle ) {
			if ( $this->restrictionStore->isProtected( $this->newTitle, 'create' ) ) {
				# Is the title semi-protected?
				if ( $this->restrictionStore->isSemiProtected( $this->newTitle, 'create' ) ) {
					$noticeMsg = 'semiprotectedpagemovecreatewarning';
				} else {
					# Then it must be protected based on static groups (regular)
					$noticeMsg = 'protectedpagemovecreatewarning';
				}
				LogEventsList::showLogExtract(
					$out,
					'protect',
					$this->newTitle,
					'',
					[ 'lim' => 1, 'msgKey' => $noticeMsg ]
				);
				$moveOverProtection = true;
			}
			$newTalk = $newTitle->getTalkPageIfDefined();
			if ( $oldTalk && $oldTalk->exists() && $talkOK &&
				$newTalk && $this->restrictionStore->isProtected( $newTalk, 'create' )
			) {
				# Is the title semi-protected?
				if ( $this->restrictionStore->isSemiProtected( $newTalk, 'create' ) ) {
					$noticeMsg = 'semiprotectedpagemovetalkcreatewarning';
				} else {
					# Then it must be protected based on static groups (regular)
					$noticeMsg = 'protectedpagemovetalkcreatewarning';
				}
				LogEventsList::showLogExtract(
					$out,
					'protect',
					$newTalk,
					'',
					[ 'lim' => 1, 'msgKey' => $noticeMsg ]
				);
				$moveOverProtection = true;
			}
		}

		$out->enableOOUI();
		$fields = [];

		$fields[] = new FieldLayout(
			new ComplexTitleInputWidget( [
				'id' => 'wpNewTitle',
				'namespace' => [
					'id' => 'wpNewTitleNs',
					'name' => 'wpNewTitleNs',
					'value' => $newTitle->getNamespace(),
					'exclude' => $immovableNamespaces,
				],
				'title' => [
					'id' => 'wpNewTitleMain',
					'name' => 'wpNewTitleMain',
					'value' => $newTitle->getText(),
					// Inappropriate, since we're expecting the user to input a non-existent page's title
					'suggestions' => false,
				],
				'infusable' => true,
			] ),
			[
				'label' => $this->msg( 'newtitle' )->text(),
				'align' => 'top',
			]
		);

		$options = Html::listDropdownOptions(
			$this->msg( 'movepage-reason-dropdown' )
				->page( $this->oldTitle )
				->inContentLanguage()
				->text(),
			[ 'other' => $this->msg( 'movereasonotherlist' )->text() ]
		);
		$options = Html::listDropdownOptionsOoui( $options );

		$fields[] = new FieldLayout(
			new DropdownInputWidget( [
				'name' => 'wpReasonList',
				'inputId' => 'wpReasonList',
				'infusable' => true,
				'value' => $this->getRequest()->getText( 'wpReasonList', 'other' ),
				'options' => $options,
			] ),
			[
				'label' => $this->msg( 'movereason' )->text(),
				'align' => 'top',
			]
		);

		// HTML maxlength uses "UTF-16 code units", which means that characters outside BMP
		// (e.g. emojis) count for two each. This limit is overridden in JS to instead count
		// Unicode codepoints.
		$fields[] = new FieldLayout(
			new TextInputWidget( [
				'name' => 'wpReason',
				'id' => 'wpReason',
				'maxLength' => CommentStore::COMMENT_CHARACTER_LIMIT,
				'infusable' => true,
				'value' => $this->getRequest()->getText( 'wpReason' ),
			] ),
			[
				'label' => $this->msg( 'moveotherreason' )->text(),
				'align' => 'top',
			]
		);

		if ( $considerTalk ) {
			$fields[] = new FieldLayout(
				new CheckboxInputWidget( [
					'name' => 'wpMovetalk',
					'id' => 'wpMovetalk',
					'value' => '1',
					// It's intentional that this box is still visible and checked by default even if you don't have
					// permission to move the talk page; wanting to separate a base page from its talk page is so
					// unusual that you should have to explicitly uncheck the box to do so
					'selected' => $this->moveTalk,
				] ),
				[
					'label' => $this->msg( 'movetalk' )->text(),
					'help' => new HtmlSnippet( $this->msg( 'movepagetalktext' )->parseAsBlock() ),
					'helpInline' => true,
					'align' => 'inline',
					'id' => 'wpMovetalk-field',
				]
			);
		}

		if ( $this->permManager->userHasRight( $user, 'suppressredirect' ) ) {
			if ( $createRedirect ) {
				$isChecked = $this->leaveRedirect;
				$isDisabled = false;
			} else {
				$isChecked = false;
				$isDisabled = true;
			}
			$fields[] = new FieldLayout(
				new CheckboxInputWidget( [
					'name' => 'wpLeaveRedirect',
					'id' => 'wpLeaveRedirect',
					'value' => '1',
					'selected' => $isChecked,
					'disabled' => $isDisabled,
				] ),
				[
					'label' => $this->msg( 'move-leave-redirect' )->text(),
					'align' => 'inline',
				]
			);
		}

		if ( $hasRedirects ) {
			$fields[] = new FieldLayout(
				new CheckboxInputWidget( [
					'name' => 'wpFixRedirects',
					'id' => 'wpFixRedirects',
					'value' => '1',
					'selected' => $this->fixRedirects,
				] ),
				[
					'label' => $this->msg( 'fix-double-redirects' )->text(),
					'align' => 'inline',
				]
			);
		}

		if ( $canMoveSubpage ) {
			$maximumMovedPages = $this->getConfig()->get( MainConfigNames::MaximumMovedPages );
			$fields[] = new FieldLayout(
				new CheckboxInputWidget( [
					'name' => 'wpMovesubpages',
					'id' => 'wpMovesubpages',
					'value' => '1',
					'selected' => $this->moveSubpages,
				] ),
				[
					'label' => new HtmlSnippet(
						$this->msg(
							( $this->oldTitle->hasSubpages()
								? 'move-subpages'
								: 'move-talk-subpages' )
						)->numParams( $maximumMovedPages )->params( $maximumMovedPages )->parse()
					),
					'align' => 'inline',
				]
			);
		}

		# Don't allow watching if user is not logged in
		if ( $user->isRegistered() ) {
			$watchChecked = ( $this->watch || $this->userOptionsLookup->getBoolOption( $user, 'watchmoves' )
				|| $this->watchlistManager->isWatched( $user, $this->oldTitle ) );
			$fields[] = new FieldLayout(
				new CheckboxInputWidget( [
					'name' => 'wpWatch',
					'id' => 'watch', # ew
					'infusable' => true,
					'value' => '1',
					'selected' => $watchChecked,
				] ),
				[
					'label' => $this->msg( 'move-watch' )->text(),
					'align' => 'inline',
				]
			);

			# Add a dropdown for watchlist expiry times in the form, T261230
			if ( $this->getConfig()->get( MainConfigNames::WatchlistExpiry ) ) {
				$expiryOptions = WatchAction::getExpiryOptions(
					$this->getContext(),
					$this->watchedItemStore->getWatchedItem( $user, $this->oldTitle )
				);
				# Reformat the options to match what DropdownInputWidget wants.
				$options = [];
				foreach ( $expiryOptions['options'] as $label => $value ) {
					$options[] = [ 'data' => $value, 'label' => $label ];
				}

				$fields[] = new FieldLayout(
					new DropdownInputWidget( [
						'name' => 'wpWatchlistExpiry',
						'id' => 'wpWatchlistExpiry',
						'infusable' => true,
						'options' => $options,
					] ),
					[
						'label' => $this->msg( 'confirm-watch-label' )->text(),
						'id' => 'wpWatchlistExpiryLabel',
						'infusable' => true,
						'align' => 'inline',
					]
				);
			}
		}

		$hiddenFields = '';
		if ( $moveOverShared ) {
			$hiddenFields .= Html::hidden( 'wpMoveOverSharedFile', '1' );
		}

		if ( $deleteAndMove ) {
			// Suppress Phan false positives here - the array is either one or two elements, and is assigned above
			// so the count clearly distinguishes the two cases
			if ( count( $deleteAndMove ) == 2 ) {
				$msg = $this->msg( 'delete_and_move_confirm_2',
					// @phan-suppress-next-line PhanTypePossiblyInvalidDimOffset False positive
					$deleteAndMove[0]->getPrefixedText(),
					// @phan-suppress-next-line PhanTypePossiblyInvalidDimOffset False positive
					$deleteAndMove[1]->getPrefixedText()
				)->text();
			} else {
				// @phan-suppress-next-line PhanTypePossiblyInvalidDimOffset False positive
				$msg = $this->msg( 'delete_and_move_confirm', $deleteAndMove[0]->getPrefixedText() )->text();
			}
			$fields[] = new FieldLayout(
				new CheckboxInputWidget( [
					'name' => 'wpDeleteAndMove',
					'id' => 'wpDeleteAndMove',
					'value' => '1',
				] ),
				[
					'label' => $msg,
					'align' => 'inline',
				]
			);
		}
		if ( $moveOverProtection ) {
			$fields[] = new FieldLayout(
				new CheckboxInputWidget( [
					'name' => 'wpMoveOverProtection',
					'id' => 'wpMoveOverProtection',
					'value' => '1',
				] ),
				[
					'label' => $this->msg( 'move_over_protection_confirm' )->text(),
					'align' => 'inline',
				]
			);
		}

		$fields[] = new FieldLayout(
			new ButtonInputWidget( [
				'name' => 'wpMove',
				'value' => $this->msg( 'movepagebtn' )->text(),
				'label' => $this->msg( 'movepagebtn' )->text(),
				'flags' => [ 'primary', 'progressive' ],
				'type' => 'submit',
				'accessKey' => Linker::accesskey( 'move' ),
				'title' => Linker::titleAttrib( 'move' ),
			] ),
			[
				'align' => 'top',
			]
		);

		$fieldset = new FieldsetLayout( [
			'label' => $this->msg( 'move-page-legend' )->text(),
			'id' => 'mw-movepage-table',
			'items' => $fields,
		] );

		$form = new FormLayout( [
			'method' => 'post',
			'action' => $this->getPageTitle()->getLocalURL( 'action=submit' ),
			'id' => 'movepage',
		] );
		$form->appendContent(
			$fieldset,
			new HtmlSnippet(
				$hiddenFields .
				Html::hidden( 'wpOldTitle', $this->oldTitle->getPrefixedText() ) .
				Html::hidden( 'wpEditToken', $user->getEditToken() )
			)
		);

		$out->addHTML(
			( new PanelLayout( [
				'classes' => [ 'movepage-wrapper' ],
				'expanded' => false,
				'padded' => true,
				'framed' => true,
				'content' => $form,
			] ) )->toString()
		);
		if ( $this->getAuthority()->isAllowed( 'editinterface' ) ) {
			$link = $this->getLinkRenderer()->makeKnownLink(
				$this->msg( 'movepage-reason-dropdown' )->inContentLanguage()->getTitle(),
				$this->msg( 'movepage-edit-reasonlist' )->text(),
				[],
				[ 'action' => 'edit' ]
			);
			$out->addHTML( Html::rawElement( 'p', [ 'class' => 'mw-movepage-editreasons' ], $link ) );
		}

		$this->showLogFragment( $this->oldTitle );
		$this->showSubpages( $this->oldTitle );
	}

	private function vacateTitle( Title $title, User $user, Title $oldTitle ): StatusValue {
		if ( !$title->exists() ) {
			return StatusValue::newGood();
		}
		$redir2 = $title->isSingleRevRedirect();

		$permStatus = $this->permManager->getPermissionStatus(
			$redir2 ? 'delete-redirect' : 'delete',
			$user, $title
		);
		if ( !$permStatus->isGood() ) {
			if ( $redir2 ) {
				if ( !$this->permManager->userCan( 'delete', $user, $title ) ) {
					// Cannot delete-redirect, or delete normally
					return $permStatus;
				} else {
					// Cannot delete-redirect, but can delete normally,
					// so log as a normal deletion
					$redir2 = false;
				}
			} else {
				// Cannot delete normally
				return $permStatus;
			}
		}

		$page = $this->wikiPageFactory->newFromTitle( $title );
		$delPage = $this->deletePageFactory->newDeletePage( $page, $user );

		// Small safety margin to guard against concurrent edits
		if ( $delPage->isBatchedDelete( 5 ) ) {
			return StatusValue::newFatal( 'movepage-delete-first' );
		}

		$reason = $this->msg( 'delete_and_move_reason', $oldTitle->getPrefixedText() )->inContentLanguage()->text();

		// Delete an associated image if there is
		if ( $title->getNamespace() === NS_FILE ) {
			$file = $this->repoGroup->getLocalRepo()->newFile( $title );
			$file->load( IDBAccessObject::READ_LATEST );
			if ( $file->exists() ) {
				$file->deleteFile( $reason, $user, false );
			}
		}

		$deletionLog = $redir2 ? 'delete_redir2' : 'delete';
		$deleteStatus = $delPage
			->setLogSubtype( $deletionLog )
			// Should be redundant thanks to the isBatchedDelete check above.
			->forceImmediate( true )
			->deleteUnsafe( $reason );

		return $deleteStatus;
	}

	private function doSubmit() {
		$user = $this->getUser();

		if ( $user->pingLimiter( 'move' ) ) {
			throw new ThrottledError;
		}

		$ot = $this->oldTitle;
		$nt = $this->newTitle;
		$oldTalk = $ot->getTalkPageIfDefined();
		$newTalk = $nt->getTalkPageIfDefined();

		if ( $ot->isTalkPage() || $nt->isTalkPage() ) {
			$this->moveTalk = false;
		}

		# don't allow moving to pages with # in
		if ( !$nt || $nt->hasFragment() ) {
			$this->showForm( StatusValue::newFatal( 'badtitletext' ) );

			return;
		}

		# Show a warning if the target file exists on a shared repo
		if ( $nt->getNamespace() === NS_FILE
			&& !( $this->moveOverShared && $this->permManager->userHasRight( $user, 'reupload-shared' ) )
			&& !$this->repoGroup->getLocalRepo()->findFile( $nt )
			&& $this->repoGroup->findFile( $nt )
		) {
			$this->showForm( StatusValue::newFatal( 'file-exists-sharedrepo' ) );

			return;
		}

		# Show a warning if procted (showForm handles the warning )
		if ( !$this->moveOverProtection ) {
			if ( $this->restrictionStore->isProtected( $nt, 'create' ) ) {
				$this->showForm();
				return;
			}
			if ( $this->moveTalk && $this->restrictionStore->isProtected( $newTalk, 'create' ) ) {
				$this->showForm();
				return;
			}
		}

		$handler = $this->contentHandlerFactory->getContentHandler( $ot->getContentModel() );

		if ( !$handler->supportsRedirects() || (
			// Do not create redirects for wikitext message overrides (T376399).
			// Maybe one day they will have a custom content model and this special case won't be needed.
			$ot->getNamespace() === NS_MEDIAWIKI &&
			$ot->getContentModel() === CONTENT_MODEL_WIKITEXT
		) ) {
			$createRedirect = false;
		} elseif ( $this->permManager->userHasRight( $user, 'suppressredirect' ) ) {
			$createRedirect = $this->leaveRedirect;
		} else {
			$createRedirect = true;
		}

		// Check perms
		$mp = $this->movePageFactory->newMovePage( $ot, $nt );
		$permStatusMain = $mp->authorizeMove( $this->getAuthority(), $this->reason );
		$permStatusMain->merge( $mp->isValidMove() );

		$onlyMovingTalkSubpages = false;
		if ( $this->moveTalk ) {
			$mpTalk = $this->movePageFactory->newMovePage( $oldTalk, $newTalk );
			$permStatusTalk = $mpTalk->authorizeMove( $this->getAuthority(), $this->reason );
			$permStatusTalk->merge( $mpTalk->isValidMove() );
			// Per the definition of $considerTalk in showForm you might be trying to move
			// subpages of the talk even if the talk itself doesn't exist, so let that happen
			if ( !$permStatusTalk->isOK() &&
				 !$permStatusTalk->hasMessagesExcept( 'movepage-source-doesnt-exist' )
			) {
				$permStatusTalk->setOK( true );
				$onlyMovingTalkSubpages = true;
			}
		} else {
			$permStatusTalk = StatusValue::newGood();
			$mpTalk = null;
		}

		if ( $this->deleteAndMove ) {
			// This is done before the deletion (in order to minimize the impact of T265792)
			// so ignore "it already exists" checks (they will be repeated after the deletion)
			if ( $permStatusMain->hasMessagesExcept( 'redirectexists', 'articleexists' ) ||
				$permStatusTalk->hasMessagesExcept( 'redirectexists', 'articleexists' ) ) {
				$this->showForm( $permStatusMain, $permStatusTalk );
				return;
			}
			// If the code gets here, then it's passed all permission checks and the move should succeed
			// so start deleting things.
			// FIXME: This isn't atomic; it could delete things even if the move will later fail (T265792)
			// For example, if you manually specify deleteAndMove in the URL (the form UI won't show the checkbox)
			// and have `delete-redirect` and the main page is a single-revision redirect
			// but the talk page isn't it will delete the redirect and then fail, leaving it deleted
			$deleteStatus = $this->vacateTitle( $nt, $user, $ot );
			if ( !$deleteStatus->isGood() ) {
				$this->showForm( $deleteStatus );
				return;
			}
			if ( $this->moveTalk ) {
				$deleteStatus = $this->vacateTitle( $newTalk, $user, $oldTalk );
				if ( !$deleteStatus->isGood() ) {
					// Ideally we would specify that the subject page redirect was deleted
					// but see the FIXME above
					$this->showForm( StatusValue::newGood(), $deleteStatus );
					return;
				}
			}
		} elseif ( !$permStatusMain->isOK() || !$permStatusTalk->isOK() ) {
			// If we're not going to delete then bail on all errors
			$this->showForm( $permStatusMain, $permStatusTalk );
			return;
		}

		// Now we've confirmed you can do all of the moves you want and proceeding won't leave things inconsistent
		// so actually move the main page
		$mainStatus = $mp->moveIfAllowed( $this->getAuthority(), $this->reason, $createRedirect );
		if ( !$mainStatus->isOK() ) {
			$this->showForm( $mainStatus );
			return;
		}

		$fixRedirects = $this->fixRedirects && $this->getConfig()->get( MainConfigNames::FixDoubleRedirects );
		if ( $fixRedirects ) {
			DoubleRedirectJob::fixRedirects( 'move', $ot );
		}

		// Now try to move the talk page
		$maximumMovedPages = $this->getConfig()->get( MainConfigNames::MaximumMovedPages );

		$moveStatuses = [];
		$talkStatus = null;
		if ( $this->moveTalk && !$onlyMovingTalkSubpages ) {
			$talkStatus = $mpTalk->moveIfAllowed( $this->getAuthority(), $this->reason, $createRedirect );
			// moveIfAllowed returns a Status with an array as a value, however moveSubpages per-title statuses
			// have strings as values. Massage this status into the moveSubpages format so it fits in with
			// the later calls
			'@phan-var Status<string> $talkStatus';
			$talkStatus->value = $newTalk->getPrefixedText();
			$moveStatuses[$oldTalk->getPrefixedText()] = $talkStatus;
		}

		// Now try to move subpages if asked
		if ( $this->moveSubpages ) {
			if ( $this->permManager->userCan( 'move-subpages', $user, $ot ) ) {
				$mp->setMaximumMovedPages( $maximumMovedPages - count( $moveStatuses ) );
				$subpageStatus = $mp->moveSubpagesIfAllowed( $this->getAuthority(), $this->reason, $createRedirect );
				$moveStatuses = array_merge( $moveStatuses, $subpageStatus->value );
			}
			if ( $this->moveTalk && $maximumMovedPages > count( $moveStatuses ) &&
				 $this->permManager->userCan( 'move-subpages', $user, $oldTalk ) &&
				 ( $onlyMovingTalkSubpages || $talkStatus->isOK() )
			) {
				$mpTalk->setMaximumMovedPages( $maximumMovedPages - count( $moveStatuses ) );
				$talkSubStatus = $mpTalk->moveSubpagesIfAllowed(
					$this->getAuthority(), $this->reason, $createRedirect
				);
				$moveStatuses = array_merge( $moveStatuses, $talkSubStatus->value );
			}
		}

		// Now we've moved everything we're going to move, so post-process the output,
		// create the UI, and fix double redirects
		$out = $this->getOutput();
		$out->setPageTitleMsg( $this->msg( 'pagemovedsub' ) );

		$linkRenderer = $this->getLinkRenderer();
		$oldLink = $linkRenderer->makeLink(
			$ot,
			null,
			[ 'id' => 'movepage-oldlink' ],
			[ 'redirect' => 'no' ]
		);
		$newLink = $linkRenderer->makeKnownLink(
			$nt,
			null,
			[ 'id' => 'movepage-newlink' ]
		);
		$oldText = $ot->getPrefixedText();
		$newText = $nt->getPrefixedText();

		$out->addHTML( $this->msg( 'movepage-moved' )->rawParams( $oldLink,
			$newLink )->params( $oldText, $newText )->parseAsBlock() );
		$out->addWikiMsg( isset( $mainStatus->getValue()['redirectRevision'] ) ?
			'movepage-moved-redirect' :
			'movepage-moved-noredirect' );

		$this->getHookRunner()->onSpecialMovepageAfterMove( $this, $ot, $nt );

		$extraOutput = [];
		foreach ( $moveStatuses as $oldSubpage => $subpageStatus ) {
			if ( $subpageStatus->hasMessage( 'movepage-max-pages' ) ) {
				$extraOutput[] = $this->msg( 'movepage-max-pages' )
					->numParams( $maximumMovedPages )->escaped();
					continue;
			}
			$oldSubpage = Title::newFromText( $oldSubpage );
			$newSubpage = Title::newFromText( $subpageStatus->value );
			if ( $subpageStatus->isGood() ) {
				if ( $fixRedirects ) {
					DoubleRedirectJob::fixRedirects( 'move', $oldSubpage );
				}
				$oldLink = $linkRenderer->makeLink( $oldSubpage, null, [], [ 'redirect' => "no" ] );
				$newLink = $linkRenderer->makeKnownLink( $newSubpage );

				$extraOutput[] = $this->msg( 'movepage-page-moved' )->rawParams(
					$oldLink, $newLink
				)->escaped();
			} elseif ( $subpageStatus->hasMessage( 'articleexists' )
				|| $subpageStatus->hasMessage( 'redirectexists' )
			) {
				$link = $linkRenderer->makeKnownLink( $newSubpage );
				$extraOutput[] = $this->msg( 'movepage-page-exists' )->rawParams( $link )->escaped();
			} else {
				$oldLink = $linkRenderer->makeKnownLink( $oldSubpage );
				if ( $newSubpage ) {
					$newLink = $linkRenderer->makeLink( $newSubpage );
				} else {
					// It's not a valid title
					$newLink = htmlspecialchars( $subpageStatus->value );
				}
				$extraOutput[] = $this->msg( 'movepage-page-unmoved' )
						->rawParams( $oldLink, $newLink )->escaped();
			}
		}

		if ( $extraOutput !== [] ) {
			$out->addHTML( "<ul>\n<li>" . implode( "</li>\n<li>", $extraOutput ) . "</li>\n</ul>" );
		}

		# Deal with watches (we don't watch subpages)
		# Get text from expiry selection dropdown, T261230
		$expiry = $this->getRequest()->getText( 'wpWatchlistExpiry' );
		if ( $this->getConfig()->get( MainConfigNames::WatchlistExpiry ) && $expiry !== '' ) {
			$expiry = ExpiryDef::normalizeExpiry( $expiry, TS::ISO_8601 );
		} else {
			$expiry = null;
		}

		$this->watchlistManager->setWatch(
			$this->watch,
			$this->getAuthority(),
			$ot,
			$expiry
		);

		$this->watchlistManager->setWatch(
			$this->watch,
			$this->getAuthority(),
			$nt,
			$expiry
		);
	}

	private function showLogFragment( Title $title ) {
		$moveLogPage = new LogPage( 'move' );
		$out = $this->getOutput();
		$out->addHTML( Html::element( 'h2', [], $moveLogPage->getName()->text() ) );
		LogEventsList::showLogExtract( $out, 'move', $title );
	}

	/**
	 * Show subpages of the page being moved. Section is not shown if both current
	 * namespace does not support subpages and no talk subpages were found.
	 *
	 * @param Title $title Page being moved.
	 */
	private function showSubpages( $title ) {
		$maximumMovedPages = $this->getConfig()->get( MainConfigNames::MaximumMovedPages );
		$nsHasSubpages = $this->nsInfo->hasSubpages( $title->getNamespace() );
		$subpages = $title->getSubpages( $maximumMovedPages + 1 );
		$count = $subpages instanceof TitleArrayFromResult ? $subpages->count() : 0;

		$titleIsTalk = $title->isTalkPage();
		$subpagesTalk = $title->getTalkPage()->getSubpages( $maximumMovedPages + 1 );
		$countTalk = $subpagesTalk instanceof TitleArrayFromResult ? $subpagesTalk->count() : 0;
		$totalCount = $count + $countTalk;

		if ( !$nsHasSubpages && $countTalk == 0 ) {
			return;
		}

		$this->getOutput()->wrapWikiMsg(
			'== $1 ==',
			[ 'movesubpage', ( $titleIsTalk ? $count : $totalCount ) ]
		);

		if ( $nsHasSubpages ) {
			$this->showSubpagesList(
				$subpages, $count, 'movesubpagetext', 'movesubpagetext-truncated', true
			);
		}

		if ( !$titleIsTalk && $countTalk > 0 ) {
			$this->showSubpagesList(
				$subpagesTalk, $countTalk, 'movesubpagetalktext', 'movesubpagetalktext-truncated'
			);
		}
	}

	private function showSubpagesList(
		TitleArrayFromResult $subpages, int $pagecount, string $msg, string $truncatedMsg, bool $noSubpageMsg = false
	) {
		$out = $this->getOutput();

		# No subpages.
		if ( $pagecount == 0 && $noSubpageMsg ) {
			$out->addWikiMsg( 'movenosubpage' );
			return;
		}

		$maximumMovedPages = $this->getConfig()->get( MainConfigNames::MaximumMovedPages );

		if ( $pagecount > $maximumMovedPages ) {
			$subpages = $this->truncateSubpagesList( $subpages );
			$out->addWikiMsg( $truncatedMsg, $this->getLanguage()->formatNum( $maximumMovedPages ) );
		} else {
			$out->addWikiMsg( $msg, $this->getLanguage()->formatNum( $pagecount ) );
		}
		$out->addHTML( "<ul>\n" );

		$this->linkBatchFactory->newLinkBatch( $subpages )
			->setCaller( __METHOD__ )
			->execute();
		$linkRenderer = $this->getLinkRenderer();

		foreach ( $subpages as $subpage ) {
			$link = $linkRenderer->makeLink( $subpage );
			$out->addHTML( "<li>$link</li>\n" );
		}
		$out->addHTML( "</ul>\n" );
	}

	private function truncateSubpagesList( iterable $subpages ): array {
		$returnArray = [];
		foreach ( $subpages as $subpage ) {
			$returnArray[] = $subpage;
			if ( count( $returnArray ) >= $this->getConfig()->get( MainConfigNames::MaximumMovedPages ) ) {
				break;
			}
		}
		return $returnArray;
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

	/** @inheritDoc */
	protected function getGroupName() {
		return 'pagetools';
	}
}

/**
 * Retain the old class name for backwards compatibility.
 * @deprecated since 1.40
 */
class_alias( SpecialMovePage::class, 'MovePageForm' );
