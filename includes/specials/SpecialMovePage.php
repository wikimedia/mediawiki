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
use MediaWiki\Logging\LogEventsList;
use MediaWiki\Logging\LogPage;
use MediaWiki\MainConfigNames;
use MediaWiki\Page\DeletePageFactory;
use MediaWiki\Page\MovePageFactory;
use MediaWiki\Page\WikiPageFactory;
use MediaWiki\Permissions\PermissionManager;
use MediaWiki\Permissions\PermissionStatus;
use MediaWiki\Permissions\RestrictionStore;
use MediaWiki\SpecialPage\UnlistedSpecialPage;
use MediaWiki\Title\NamespaceInfo;
use MediaWiki\Title\Title;
use MediaWiki\Title\TitleArrayFromResult;
use MediaWiki\Title\TitleFactory;
use MediaWiki\User\Options\UserOptionsLookup;
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
use SearchEngineFactory;
use StatusValue;
use Wikimedia\ParamValidator\TypeDef\ExpiryDef;
use Wikimedia\Rdbms\IConnectionProvider;
use Wikimedia\Rdbms\IDBAccessObject;
use Wikimedia\Rdbms\IExpression;
use Wikimedia\Rdbms\LikeValue;
use Wikimedia\StringUtils\StringUtils;

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

	public function doesWrites() {
		return true;
	}

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
	 */
	private function showForm( ?StatusValue $status = null ) {
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
		} elseif ( $this->oldTitle->getNamespace() === NS_CATEGORY ) {
			$out->addHTML(
				Html::warningBox(
					$out->msg( 'movecategorypage-warning' )->parse(),
					'mw-movecategorypage-warning'
				)
			);
		}

		$deleteAndMove = false;
		$moveOverShared = false;

		$user = $this->getUser();
		$newTitle = $this->newTitle;

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
		}
		if ( !$status ) {
			$status = StatusValue::newGood();
		}

		if ( count( $status->getMessages() ) == 1 ) {
			if ( $status->hasMessage( 'articleexists' )
				&& $this->permManager->quickUserCan( 'delete', $user, $newTitle )
			) {
				$out->addHTML(
					Html::warningBox(
						$out->msg( 'delete_and_move_text', $newTitle->getPrefixedText() )->parse()
					)
				);
				$deleteAndMove = true;
				$status = StatusValue::newGood();
			} elseif ( $status->hasMessage( 'redirectexists' ) && (
				// Any user that can delete normally can also delete a redirect here
				$this->permManager->quickUserCan( 'delete-redirect', $user, $newTitle ) ||
				$this->permManager->quickUserCan( 'delete', $user, $newTitle ) )
			) {
				$out->addHTML(
					Html::warningBox(
						$out->msg( 'delete_redirect_and_move_text', $newTitle->getPrefixedText() )->parse()
					)
				);
				$deleteAndMove = true;
				$status = StatusValue::newGood();
			} elseif ( $status->hasMessage( 'file-exists-sharedrepo' )
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

		// Length limit for wpReason and wpNewTitleMain is enforced in the
		// mediawiki.special.movePage module

		$immovableNamespaces = [];
		foreach ( $this->getLanguage()->getNamespaces() as $nsId => $_ ) {
			if ( !$this->nsInfo->isMovable( $nsId ) ) {
				$immovableNamespaces[] = $nsId;
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
			$fields[] = new FieldLayout(
				new CheckboxInputWidget( [
					'name' => 'wpDeleteAndMove',
					'id' => 'wpDeleteAndMove',
					'value' => '1',
				] ),
				[
					'label' => $this->msg( 'delete_and_move_confirm', $newTitle->getPrefixedText() )->text(),
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
			new PanelLayout( [
				'classes' => [ 'movepage-wrapper' ],
				'expanded' => false,
				'padded' => true,
				'framed' => true,
				'content' => $form,
			] )
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

	private function doSubmit() {
		$user = $this->getUser();

		if ( $user->pingLimiter( 'move' ) ) {
			throw new ThrottledError;
		}

		$ot = $this->oldTitle;
		$nt = $this->newTitle;

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

		# Delete to make way if requested
		if ( $this->deleteAndMove ) {
			$redir2 = $nt->isSingleRevRedirect();

			$permStatus = $this->permManager->getPermissionStatus(
				$redir2 ? 'delete-redirect' : 'delete',
				$user, $nt
			);
			if ( !$permStatus->isGood() ) {
				if ( $redir2 ) {
					if ( !$this->permManager->userCan( 'delete', $user, $nt ) ) {
						// Cannot delete-redirect, or delete normally
						$this->showForm( $permStatus );
						return;
					} else {
						// Cannot delete-redirect, but can delete normally,
						// so log as a normal deletion
						$redir2 = false;
					}
				} else {
					// Cannot delete normally
					$this->showForm( $permStatus );
					return;
				}
			}

			$page = $this->wikiPageFactory->newFromTitle( $nt );
			$delPage = $this->deletePageFactory->newDeletePage( $page, $user );

			// Small safety margin to guard against concurrent edits
			if ( $delPage->isBatchedDelete( 5 ) ) {
				$this->showForm( StatusValue::newFatal( 'movepage-delete-first' ) );

				return;
			}

			$reason = $this->msg( 'delete_and_move_reason', $ot )->inContentLanguage()->text();

			// Delete an associated image if there is
			if ( $nt->getNamespace() === NS_FILE ) {
				$file = $this->repoGroup->getLocalRepo()->newFile( $nt );
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

			if ( !$deleteStatus->isGood() ) {
				$this->showForm( $deleteStatus );

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

		# Do the actual move.
		$mp = $this->movePageFactory->newMovePage( $ot, $nt );

		if ( $ot->isTalkPage() || $nt->isTalkPage() ) {
			$this->moveTalk = false;
		}
		if ( $this->moveSubpages ) {
			$this->moveSubpages = $this->permManager->userCan( 'move-subpages', $user, $ot );
		}

		# check whether the requested actions are permitted / possible
		$permStatus = $mp->authorizeMove( $this->getAuthority(), $this->reason );
		if ( !$permStatus->isOK() ) {
			$this->showForm( $permStatus );
			return;
		}
		$status = $mp->moveIfAllowed( $this->getAuthority(), $this->reason, $createRedirect );
		if ( !$status->isOK() ) {
			$this->showForm( $status );
			return;
		}

		if ( $this->getConfig()->get( MainConfigNames::FixDoubleRedirects ) && $this->fixRedirects ) {
			DoubleRedirectJob::fixRedirects( 'move', $ot );
		}

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

		if ( $status->getValue()['redirectRevision'] !== null ) {
			$msgName = 'movepage-moved-redirect';
		} else {
			$msgName = 'movepage-moved-noredirect';
		}

		$out->addHTML( $this->msg( 'movepage-moved' )->rawParams( $oldLink,
			$newLink )->params( $oldText, $newText )->parseAsBlock() );
		$out->addWikiMsg( $msgName );

		$this->getHookRunner()->onSpecialMovepageAfterMove( $this, $ot, $nt );

		/*
		 * Now we move extra pages we've been asked to move: subpages and talk
		 * pages.
		 *
		 * First, make a list of id's.  This might be marginally less efficient
		 * than a more direct method, but this is not a highly performance-cri-
		 * tical code path and readable code is more important here.
		 *
		 * If the target namespace doesn't allow subpages, moving with subpages
		 * would mean that you couldn't move them back in one operation, which
		 * is bad.
		 * @todo FIXME: A specific error message should be given in this case.
		 */

		// @todo FIXME: Use MovePage::moveSubpages() here
		$dbr = $this->dbProvider->getReplicaDatabase();
		if ( $this->moveSubpages && (
			$this->nsInfo->hasSubpages( $nt->getNamespace() ) || (
				$this->moveTalk
					&& $this->nsInfo->hasSubpages( $nt->getTalkPage()->getNamespace() )
			)
		) ) {
			$conds = [
				$dbr->expr(
					'page_title',
					IExpression::LIKE,
					new LikeValue( $ot->getDBkey() . '/', $dbr->anyString() )
				)->or( 'page_title', '=', $ot->getDBkey() )
			];
			$conds['page_namespace'] = [];
			if ( $this->nsInfo->hasSubpages( $nt->getNamespace() ) ) {
				$conds['page_namespace'][] = $ot->getNamespace();
			}
			if ( $this->moveTalk &&
				$this->nsInfo->hasSubpages( $nt->getTalkPage()->getNamespace() )
			) {
				$conds['page_namespace'][] = $ot->getTalkPage()->getNamespace();
			}
		} elseif ( $this->moveTalk ) {
			$conds = [
				'page_namespace' => $ot->getTalkPage()->getNamespace(),
				'page_title' => $ot->getDBkey()
			];
		} else {
			# Skip the query
			$conds = null;
		}

		$extraPages = [];
		if ( $conds !== null ) {
			$extraPages = $this->titleFactory->newTitleArrayFromResult(
				$dbr->newSelectQueryBuilder()
					->select( [ 'page_id', 'page_namespace', 'page_title' ] )
					->from( 'page' )
					->where( $conds )
					->caller( __METHOD__ )->fetchResultSet()
			);
		}

		$extraOutput = [];
		$count = 1;
		foreach ( $extraPages as $oldSubpage ) {
			if ( $ot->equals( $oldSubpage ) || $nt->equals( $oldSubpage ) ) {
				# Already did this one.
				continue;
			}

			$newPageName = preg_replace(
				'#^' . preg_quote( $ot->getDBkey(), '#' ) . '#',
				StringUtils::escapeRegexReplacement( $nt->getDBkey() ), # T23234
				$oldSubpage->getDBkey()
			);

			if ( $oldSubpage->isSubpage() && ( $ot->isTalkPage() xor $nt->isTalkPage() ) ) {
				// Moving a subpage from a subject namespace to a talk namespace or vice-versa
				$newNs = $nt->getNamespace();
			} elseif ( $oldSubpage->isTalkPage() ) {
				$newNs = $nt->getTalkPage()->getNamespace();
			} else {
				$newNs = $nt->getSubjectPage()->getNamespace();
			}

			# T16385: we need makeTitleSafe because the new page names may
			# be longer than 255 characters.
			$newSubpage = Title::makeTitleSafe( $newNs, $newPageName );
			if ( !$newSubpage ) {
				$oldLink = $linkRenderer->makeKnownLink( $oldSubpage );
				$extraOutput[] = $this->msg( 'movepage-page-unmoved' )->rawParams( $oldLink )
					->params( Title::makeName( $newNs, $newPageName ) )->escaped();
				continue;
			}

			$mp = $this->movePageFactory->newMovePage( $oldSubpage, $newSubpage );
			# This was copy-pasted from Renameuser, bleh.
			if ( $newSubpage->exists() && !$mp->isValidMove()->isOK() ) {
				$link = $linkRenderer->makeKnownLink( $newSubpage );
				$extraOutput[] = $this->msg( 'movepage-page-exists' )->rawParams( $link )->escaped();
			} else {
				$status = $mp->moveIfAllowed( $this->getAuthority(), $this->reason, $createRedirect );

				if ( $status->isOK() ) {
					if ( $this->fixRedirects ) {
						DoubleRedirectJob::fixRedirects( 'move', $oldSubpage );
					}
					$oldLink = $linkRenderer->makeLink(
						$oldSubpage,
						null,
						[],
						[ 'redirect' => 'no' ]
					);

					$newLink = $linkRenderer->makeKnownLink( $newSubpage );
					$extraOutput[] = $this->msg( 'movepage-page-moved' )
						->rawParams( $oldLink, $newLink )->escaped();
					++$count;

					$maximumMovedPages =
						$this->getConfig()->get( MainConfigNames::MaximumMovedPages );
					if ( $count >= $maximumMovedPages ) {
						$extraOutput[] = $this->msg( 'movepage-max-pages' )
							->numParams( $maximumMovedPages )->escaped();
						break;
					}
				} else {
					$oldLink = $linkRenderer->makeKnownLink( $oldSubpage );
					$newLink = $linkRenderer->makeLink( $newSubpage );
					$extraOutput[] = $this->msg( 'movepage-page-unmoved' )
						->rawParams( $oldLink, $newLink )->escaped();
				}
			}
		}

		if ( $extraOutput !== [] ) {
			$out->addHTML( "<ul>\n<li>" . implode( "</li>\n<li>", $extraOutput ) . "</li>\n</ul>" );
		}

		# Deal with watches (we don't watch subpages)
		# Get text from expiry selection dropdown, T261230
		$expiry = $this->getRequest()->getText( 'wpWatchlistExpiry' );
		if ( $this->getConfig()->get( MainConfigNames::WatchlistExpiry ) && $expiry !== '' ) {
			$expiry = ExpiryDef::normalizeExpiry( $expiry, TS_ISO_8601 );
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

	protected function getGroupName() {
		return 'pagetools';
	}
}

/**
 * Retain the old class name for backwards compatibility.
 * @deprecated since 1.40
 */
class_alias( SpecialMovePage::class, 'MovePageForm' );
