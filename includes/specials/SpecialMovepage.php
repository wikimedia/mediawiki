<?php
/**
 * Implements Special:Movepage
 *
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
 * @ingroup SpecialPage
 */

/**
 * A special page that allows users to change page titles
 *
 * @ingroup SpecialPage
 */
class MovePageForm extends UnlistedSpecialPage {
	/** @var Title */
	protected $oldTitle = null;

	/** @var Title */
	protected $newTitle;

	/** @var string Text input */
	protected $reason;

	// Checks

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

	private $watch = false;

	public function __construct() {
		parent::__construct( 'Movepage' );
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
		$target = $par ?? $request->getVal( 'target' );

		// Yes, the use of getVal() and getText() is wanted, see T22365

		$oldTitleText = $request->getVal( 'wpOldTitle', $target );
		$this->oldTitle = Title::newFromText( $oldTitleText );

		if ( !$this->oldTitle ) {
			// Either oldTitle wasn't passed, or newFromText returned null
			throw new ErrorPageError( 'notargettitle', 'notargettext' );
		}
		if ( !$this->oldTitle->exists() ) {
			throw new ErrorPageError( 'nopagetitle', 'nopagetext' );
		}

		$newTitleTextMain = $request->getText( 'wpNewTitleMain' );
		$newTitleTextNs = $request->getInt( 'wpNewTitleNs', $this->oldTitle->getNamespace() );
		// Backwards compatibility for forms submitting here from other sources
		// which is more common than it should be..
		$newTitleText_bc = $request->getText( 'wpNewTitle' );
		$this->newTitle = strlen( $newTitleText_bc ) > 0
			? Title::newFromText( $newTitleText_bc )
			: Title::makeTitleSafe( $newTitleTextNs, $newTitleTextMain );

		$user = $this->getUser();

		# Check rights
		$permErrors = $this->oldTitle->getUserPermissionsErrors( 'move', $user );
		if ( count( $permErrors ) ) {
			// Auto-block user's IP if the account was "hard" blocked
			DeferredUpdates::addCallableUpdate( function () use ( $user ) {
				$user->spreadAnyEditBlock();
			} );
			throw new PermissionsError( 'move', $permErrors );
		}

		$def = !$request->wasPosted();

		$this->reason = $request->getText( 'wpReason' );
		$this->moveTalk = $request->getBool( 'wpMovetalk', $def );
		$this->fixRedirects = $request->getBool( 'wpFixRedirects', $def );
		$this->leaveRedirect = $request->getBool( 'wpLeaveRedirect', $def );
		$this->moveSubpages = $request->getBool( 'wpMovesubpages' );
		$this->deleteAndMove = $request->getBool( 'wpDeleteAndMove' );
		$this->moveOverShared = $request->getBool( 'wpMoveOverSharedFile' );
		$this->watch = $request->getCheck( 'wpWatch' ) && $user->isLoggedIn();

		if ( $request->getVal( 'action' ) == 'submit' && $request->wasPosted()
			&& $user->matchEditToken( $request->getVal( 'wpEditToken' ) )
		) {
			$this->doSubmit();
		} else {
			$this->showForm( [] );
		}
	}

	/**
	 * Show the form
	 *
	 * @param array $err Error messages. Each item is an error message.
	 *    It may either be a string message name or array message name and
	 *    parameters, like the second argument to OutputPage::wrapWikiMsg().
	 * @param bool $isPermError Whether the error message is about user permissions.
	 */
	function showForm( $err, $isPermError = false ) {
		$this->getSkin()->setRelevantTitle( $this->oldTitle );

		$out = $this->getOutput();
		$out->setPageTitle( $this->msg( 'move-page', $this->oldTitle->getPrefixedText() ) );
		$out->addModuleStyles( 'mediawiki.special' );
		$out->addModules( 'mediawiki.special.movePage' );
		$this->addHelpLink( 'Help:Moving a page' );

		$out->addWikiMsg( $this->getConfig()->get( 'FixDoubleRedirects' ) ?
			'movepagetext' :
			'movepagetext-noredirectfixer'
		);

		if ( $this->oldTitle->getNamespace() == NS_USER && !$this->oldTitle->isSubpage() ) {
			$out->wrapWikiMsg(
				"<div class=\"warningbox mw-moveuserpage-warning\">\n$1\n</div>",
				'moveuserpage-warning'
			);
		} elseif ( $this->oldTitle->getNamespace() == NS_CATEGORY ) {
			$out->wrapWikiMsg(
				"<div class=\"warningbox mw-movecategorypage-warning\">\n$1\n</div>",
				'movecategorypage-warning'
			);
		}

		$deleteAndMove = false;
		$moveOverShared = false;

		$newTitle = $this->newTitle;

		if ( !$newTitle ) {
			# Show the current title as a default
			# when the form is first opened.
			$newTitle = $this->oldTitle;
		} elseif ( !count( $err ) ) {
			# If a title was supplied, probably from the move log revert
			# link, check for validity. We can then show some diagnostic
			# information and save a click.
			$newerr = $this->oldTitle->isValidMoveOperation( $newTitle );
			if ( is_array( $newerr ) ) {
				$err = $newerr;
			}
		}

		$user = $this->getUser();

		if ( count( $err ) == 1 && isset( $err[0][0] ) && $err[0][0] == 'articleexists'
			&& $newTitle->quickUserCan( 'delete', $user )
		) {
			$out->wrapWikiMsg(
				"<div class='warningbox'>\n$1\n</div>\n",
				[ 'delete_and_move_text', $newTitle->getPrefixedText() ]
			);
			$deleteAndMove = true;
			$err = [];
		}

		if ( count( $err ) == 1 && isset( $err[0][0] ) && $err[0][0] == 'file-exists-sharedrepo'
			&& $user->isAllowed( 'reupload-shared' )
		) {
			$out->wrapWikiMsg(
				"<div class='warningbox'>\n$1\n</div>\n",
				[
					'move-over-sharedrepo',
					$newTitle->getPrefixedText()
				]
			);
			$moveOverShared = true;
			$err = [];
		}

		$oldTalk = $this->oldTitle->getTalkPage();
		$oldTitleSubpages = $this->oldTitle->hasSubpages();
		$oldTitleTalkSubpages = $this->oldTitle->getTalkPage()->hasSubpages();

		$canMoveSubpage = ( $oldTitleSubpages || $oldTitleTalkSubpages ) &&
			!count( $this->oldTitle->getUserPermissionsErrors( 'move-subpages', $user ) );

		# We also want to be able to move assoc. subpage talk-pages even if base page
		# has no associated talk page, so || with $oldTitleTalkSubpages.
		$considerTalk = !$this->oldTitle->isTalkPage() &&
			( $oldTalk->exists()
				|| ( $oldTitleTalkSubpages && $canMoveSubpage ) );

		$dbr = wfGetDB( DB_REPLICA );
		if ( $this->getConfig()->get( 'FixDoubleRedirects' ) ) {
			$hasRedirects = $dbr->selectField( 'redirect', '1',
				[
					'rd_namespace' => $this->oldTitle->getNamespace(),
					'rd_title' => $this->oldTitle->getDBkey(),
				], __METHOD__ );
		} else {
			$hasRedirects = false;
		}

		if ( count( $err ) ) {
			if ( $isPermError ) {
				$action_desc = $this->msg( 'action-move' )->plain();
				$errMsgHtml = $this->msg( 'permissionserrorstext-withaction',
					count( $err ), $action_desc )->parseAsBlock();
			} else {
				$errMsgHtml = $this->msg( 'cannotmove', count( $err ) )->parseAsBlock();
			}

			if ( count( $err ) == 1 ) {
				$errMsg = $err[0];
				$errMsgName = array_shift( $errMsg );

				if ( $errMsgName == 'hookaborted' ) {
					$errMsgHtml .= "<p>{$errMsg[0]}</p>\n";
				} else {
					$errMsgHtml .= $this->msg( $errMsgName, $errMsg )->parseAsBlock();
				}
			} else {
				$errStr = [];

				foreach ( $err as $errMsg ) {
					if ( $errMsg[0] == 'hookaborted' ) {
						$errStr[] = $errMsg[1];
					} else {
						$errMsgName = array_shift( $errMsg );
						$errStr[] = $this->msg( $errMsgName, $errMsg )->parse();
					}
				}

				$errMsgHtml .= '<ul><li>' . implode( "</li>\n<li>", $errStr ) . "</li></ul>\n";
			}
			$out->addHTML( Html::errorBox( $errMsgHtml ) );
		}

		if ( $this->oldTitle->isProtected( 'move' ) ) {
			# Is the title semi-protected?
			if ( $this->oldTitle->isSemiProtected( 'move' ) ) {
				$noticeMsg = 'semiprotectedpagemovewarning';
				$classes[] = 'mw-textarea-sprotected';
			} else {
				# Then it must be protected based on static groups (regular)
				$noticeMsg = 'protectedpagemovewarning';
				$classes[] = 'mw-textarea-protected';
			}
			$out->addHTML( "<div class='mw-warning-with-logexcerpt'>\n" );
			$out->addWikiMsg( $noticeMsg );
			LogEventsList::showLogExtract(
				$out,
				'protect',
				$this->oldTitle,
				'',
				[ 'lim' => 1 ]
			);
			$out->addHTML( "</div>\n" );
		}

		// Length limit for wpReason and wpNewTitleMain is enforced in the
		// mediawiki.special.movePage module

		$immovableNamespaces = [];
		foreach ( array_keys( $this->getLanguage()->getNamespaces() ) as $nsId ) {
			if ( !MWNamespace::isMovable( $nsId ) ) {
				$immovableNamespaces[] = $nsId;
			}
		}

		$handler = ContentHandler::getForTitle( $this->oldTitle );

		$out->enableOOUI();
		$fields = [];

		$fields[] = new OOUI\FieldLayout(
			new MediaWiki\Widget\ComplexTitleInputWidget( [
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

		// HTML maxlength uses "UTF-16 code units", which means that characters outside BMP
		// (e.g. emojis) count for two each. This limit is overridden in JS to instead count
		// Unicode codepoints.
		$fields[] = new OOUI\FieldLayout(
			new OOUI\TextInputWidget( [
				'name' => 'wpReason',
				'id' => 'wpReason',
				'maxLength' => CommentStore::COMMENT_CHARACTER_LIMIT,
				'infusable' => true,
				'value' => $this->reason,
			] ),
			[
				'label' => $this->msg( 'movereason' )->text(),
				'align' => 'top',
			]
		);

		if ( $considerTalk ) {
			$fields[] = new OOUI\FieldLayout(
				new OOUI\CheckboxInputWidget( [
					'name' => 'wpMovetalk',
					'id' => 'wpMovetalk',
					'value' => '1',
					'selected' => $this->moveTalk,
				] ),
				[
					'label' => $this->msg( 'movetalk' )->text(),
					'help' => new OOUI\HtmlSnippet( $this->msg( 'movepagetalktext' )->parseAsBlock() ),
					'helpInline' => true,
					'align' => 'inline',
					'id' => 'wpMovetalk-field',
				]
			);
		}

		if ( $user->isAllowed( 'suppressredirect' ) ) {
			if ( $handler->supportsRedirects() ) {
				$isChecked = $this->leaveRedirect;
				$isDisabled = false;
			} else {
				$isChecked = false;
				$isDisabled = true;
			}
			$fields[] = new OOUI\FieldLayout(
				new OOUI\CheckboxInputWidget( [
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
			$fields[] = new OOUI\FieldLayout(
				new OOUI\CheckboxInputWidget( [
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
			$maximumMovedPages = $this->getConfig()->get( 'MaximumMovedPages' );
			$fields[] = new OOUI\FieldLayout(
				new OOUI\CheckboxInputWidget( [
					'name' => 'wpMovesubpages',
					'id' => 'wpMovesubpages',
					'value' => '1',
					# Don't check the box if we only have talk subpages to
					# move and we aren't moving the talk page.
					'selected' => $this->moveSubpages && ( $this->oldTitle->hasSubpages() || $this->moveTalk ),
				] ),
				[
					'label' => new OOUI\HtmlSnippet(
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
		if ( $user->isLoggedIn() ) {
			$watchChecked = $user->isLoggedIn() && ( $this->watch || $user->getBoolOption( 'watchmoves' )
				|| $user->isWatched( $this->oldTitle ) );
			$fields[] = new OOUI\FieldLayout(
				new OOUI\CheckboxInputWidget( [
					'name' => 'wpWatch',
					'id' => 'watch', # ew
					'value' => '1',
					'selected' => $watchChecked,
				] ),
				[
					'label' => $this->msg( 'move-watch' )->text(),
					'align' => 'inline',
				]
			);
		}

		$hiddenFields = '';
		if ( $moveOverShared ) {
			$hiddenFields .= Html::hidden( 'wpMoveOverSharedFile', '1' );
		}

		if ( $deleteAndMove ) {
			$fields[] = new OOUI\FieldLayout(
				new OOUI\CheckboxInputWidget( [
					'name' => 'wpDeleteAndMove',
					'id' => 'wpDeleteAndMove',
					'value' => '1',
				] ),
				[
					'label' => $this->msg( 'delete_and_move_confirm' )->text(),
					'align' => 'inline',
				]
			);
		}

		$fields[] = new OOUI\FieldLayout(
			new OOUI\ButtonInputWidget( [
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

		$fieldset = new OOUI\FieldsetLayout( [
			'label' => $this->msg( 'move-page-legend' )->text(),
			'id' => 'mw-movepage-table',
			'items' => $fields,
		] );

		$form = new OOUI\FormLayout( [
			'method' => 'post',
			'action' => $this->getPageTitle()->getLocalURL( 'action=submit' ),
			'id' => 'movepage',
		] );
		$form->appendContent(
			$fieldset,
			new OOUI\HtmlSnippet(
				$hiddenFields .
				Html::hidden( 'wpOldTitle', $this->oldTitle->getPrefixedText() ) .
				Html::hidden( 'wpEditToken', $user->getEditToken() )
			)
		);

		$out->addHTML(
			new OOUI\PanelLayout( [
				'classes' => [ 'movepage-wrapper' ],
				'expanded' => false,
				'padded' => true,
				'framed' => true,
				'content' => $form,
			] )
		);

		$this->showLogFragment( $this->oldTitle );
		$this->showSubpages( $this->oldTitle );
	}

	function doSubmit() {
		$user = $this->getUser();

		if ( $user->pingLimiter( 'move' ) ) {
			throw new ThrottledError;
		}

		$ot = $this->oldTitle;
		$nt = $this->newTitle;

		# don't allow moving to pages with # in
		if ( !$nt || $nt->hasFragment() ) {
			$this->showForm( [ [ 'badtitletext' ] ] );

			return;
		}

		# Show a warning if the target file exists on a shared repo
		if ( $nt->getNamespace() == NS_FILE
			&& !( $this->moveOverShared && $user->isAllowed( 'reupload-shared' ) )
			&& !RepoGroup::singleton()->getLocalRepo()->findFile( $nt )
			&& wfFindFile( $nt )
		) {
			$this->showForm( [ [ 'file-exists-sharedrepo' ] ] );

			return;
		}

		# Delete to make way if requested
		if ( $this->deleteAndMove ) {
			$permErrors = $nt->getUserPermissionsErrors( 'delete', $user );
			if ( count( $permErrors ) ) {
				# Only show the first error
				$this->showForm( $permErrors, true );

				return;
			}

			$page = WikiPage::factory( $nt );

			// Small safety margin to guard against concurrent edits
			if ( $page->isBatchedDelete( 5 ) ) {
				$this->showForm( [ [ 'movepage-delete-first' ] ] );

				return;
			}

			$reason = $this->msg( 'delete_and_move_reason', $ot )->inContentLanguage()->text();

			// Delete an associated image if there is
			if ( $nt->getNamespace() == NS_FILE ) {
				$file = wfLocalFile( $nt );
				$file->load( File::READ_LATEST );
				if ( $file->exists() ) {
					$file->delete( $reason, false, $user );
				}
			}

			$error = ''; // passed by ref
			$deleteStatus = $page->doDeleteArticleReal( $reason, false, 0, true, $error, $user );
			if ( !$deleteStatus->isGood() ) {
				$this->showForm( $deleteStatus->getErrorsArray() );

				return;
			}
		}

		$handler = ContentHandler::getForTitle( $ot );

		if ( !$handler->supportsRedirects() ) {
			$createRedirect = false;
		} elseif ( $user->isAllowed( 'suppressredirect' ) ) {
			$createRedirect = $this->leaveRedirect;
		} else {
			$createRedirect = true;
		}

		# Do the actual move.
		$mp = new MovePage( $ot, $nt );
		$valid = $mp->isValidMove();
		if ( !$valid->isOK() ) {
			$this->showForm( $valid->getErrorsArray() );
			return;
		}

		$permStatus = $mp->checkPermissions( $user, $this->reason );
		if ( !$permStatus->isOK() ) {
			$this->showForm( $permStatus->getErrorsArray(), true );
			return;
		}

		$status = $mp->move( $user, $this->reason, $createRedirect );
		if ( !$status->isOK() ) {
			$this->showForm( $status->getErrorsArray() );
			return;
		}

		if ( $this->getConfig()->get( 'FixDoubleRedirects' ) && $this->fixRedirects ) {
			DoubleRedirectJob::fixRedirects( 'move', $ot, $nt );
		}

		$out = $this->getOutput();
		$out->setPageTitle( $this->msg( 'pagemovedsub' ) );

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

		if ( $ot->exists() ) {
			// NOTE: we assume that if the old title exists, it's because it was re-created as
			// a redirect to the new title. This is not safe, but what we did before was
			// even worse: we just determined whether a redirect should have been created,
			// and reported that it was created if it should have, without any checks.
			// Also note that isRedirect() is unreliable because of T39209.
			$msgName = 'movepage-moved-redirect';
		} else {
			$msgName = 'movepage-moved-noredirect';
		}

		$out->addHTML( $this->msg( 'movepage-moved' )->rawParams( $oldLink,
			$newLink )->params( $oldText, $newText )->parseAsBlock() );
		$out->addWikiMsg( $msgName );

		// Avoid PHP 7.1 warning from passing $this by reference
		$movePage = $this;
		Hooks::run( 'SpecialMovepageAfterMove', [ &$movePage, &$ot, &$nt ] );

		# Now we move extra pages we've been asked to move: subpages and talk
		# pages.  First, if the old page or the new page is a talk page, we
		# can't move any talk pages: cancel that.
		if ( $ot->isTalkPage() || $nt->isTalkPage() ) {
			$this->moveTalk = false;
		}

		if ( count( $ot->getUserPermissionsErrors( 'move-subpages', $user ) ) ) {
			$this->moveSubpages = false;
		}

		/**
		 * Next make a list of id's.  This might be marginally less efficient
		 * than a more direct method, but this is not a highly performance-cri-
		 * tical code path and readable code is more important here.
		 *
		 * If the target namespace doesn't allow subpages, moving with subpages
		 * would mean that you couldn't move them back in one operation, which
		 * is bad.
		 * @todo FIXME: A specific error message should be given in this case.
		 */

		// @todo FIXME: Use Title::moveSubpages() here
		$dbr = wfGetDB( DB_MASTER );
		if ( $this->moveSubpages && (
			MWNamespace::hasSubpages( $nt->getNamespace() ) || (
				$this->moveTalk
					&& MWNamespace::hasSubpages( $nt->getTalkPage()->getNamespace() )
			)
		) ) {
			$conds = [
				'page_title' . $dbr->buildLike( $ot->getDBkey() . '/', $dbr->anyString() )
					. ' OR page_title = ' . $dbr->addQuotes( $ot->getDBkey() )
			];
			$conds['page_namespace'] = [];
			if ( MWNamespace::hasSubpages( $nt->getNamespace() ) ) {
				$conds['page_namespace'][] = $ot->getNamespace();
			}
			if ( $this->moveTalk &&
				MWNamespace::hasSubpages( $nt->getTalkPage()->getNamespace() )
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
		if ( !is_null( $conds ) ) {
			$extraPages = TitleArray::newFromResult(
				$dbr->select( 'page',
					[ 'page_id', 'page_namespace', 'page_title' ],
					$conds,
					__METHOD__
				)
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

			# This was copy-pasted from Renameuser, bleh.
			if ( $newSubpage->exists() && !$oldSubpage->isValidMoveTarget( $newSubpage ) ) {
				$link = $linkRenderer->makeKnownLink( $newSubpage );
				$extraOutput[] = $this->msg( 'movepage-page-exists' )->rawParams( $link )->escaped();
			} else {
				$success = $oldSubpage->moveTo( $newSubpage, true, $this->reason, $createRedirect );

				if ( $success === true ) {
					if ( $this->fixRedirects ) {
						DoubleRedirectJob::fixRedirects( 'move', $oldSubpage, $newSubpage );
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

					$maximumMovedPages = $this->getConfig()->get( 'MaximumMovedPages' );
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
		WatchAction::doWatchOrUnwatch( $this->watch, $ot, $user );
		WatchAction::doWatchOrUnwatch( $this->watch, $nt, $user );
	}

	function showLogFragment( $title ) {
		$moveLogPage = new LogPage( 'move' );
		$out = $this->getOutput();
		$out->addHTML( Xml::element( 'h2', null, $moveLogPage->getName()->text() ) );
		LogEventsList::showLogExtract( $out, 'move', $title );
	}

	/**
	 * Show subpages of the page being moved. Section is not shown if both current
	 * namespace does not support subpages and no talk subpages were found.
	 *
	 * @param Title $title Page being moved.
	 */
	function showSubpages( $title ) {
		$nsHasSubpages = MWNamespace::hasSubpages( $title->getNamespace() );
		$subpages = $title->getSubpages();
		$count = $subpages instanceof TitleArray ? $subpages->count() : 0;

		$titleIsTalk = $title->isTalkPage();
		$subpagesTalk = $title->getTalkPage()->getSubpages();
		$countTalk = $subpagesTalk instanceof TitleArray ? $subpagesTalk->count() : 0;
		$totalCount = $count + $countTalk;

		if ( !$nsHasSubpages && $countTalk == 0 ) {
			return;
		}

		$this->getOutput()->wrapWikiMsg(
			'== $1 ==',
			[ 'movesubpage', ( $titleIsTalk ? $count : $totalCount ) ]
		);

		if ( $nsHasSubpages ) {
			$this->showSubpagesList( $subpages, $count, 'movesubpagetext', true );
		}

		if ( !$titleIsTalk && $countTalk > 0 ) {
			$this->showSubpagesList( $subpagesTalk, $countTalk, 'movesubpagetalktext' );
		}
	}

	function showSubpagesList( $subpages, $pagecount, $wikiMsg, $noSubpageMsg = false ) {
		$out = $this->getOutput();

		# No subpages.
		if ( $pagecount == 0 && $noSubpageMsg ) {
			$out->addWikiMsg( 'movenosubpage' );
			return;
		}

		$out->addWikiMsg( $wikiMsg, $this->getLanguage()->formatNum( $pagecount ) );
		$out->addHTML( "<ul>\n" );

		$linkBatch = new LinkBatch( $subpages );
		$linkBatch->setCaller( __METHOD__ );
		$linkBatch->execute();
		$linkRenderer = $this->getLinkRenderer();

		foreach ( $subpages as $subpage ) {
			$link = $linkRenderer->makeLink( $subpage );
			$out->addHTML( "<li>$link</li>\n" );
		}
		$out->addHTML( "</ul>\n" );
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
		return $this->prefixSearchString( $search, $limit, $offset );
	}

	protected function getGroupName() {
		return 'pagetools';
	}
}
