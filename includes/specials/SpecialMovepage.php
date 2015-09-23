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

	public function execute( $par ) {
		$this->useTransactionalTimeLimit();

		$this->checkReadOnly();

		$this->setHeaders();
		$this->outputHeader();

		$request = $this->getRequest();
		$target = !is_null( $par ) ? $par : $request->getVal( 'target' );

		// Yes, the use of getVal() and getText() is wanted, see bug 20365

		$oldTitleText = $request->getVal( 'wpOldTitle', $target );
		if ( is_string( $oldTitleText ) ) {
			$this->oldTitle = Title::newFromText( $oldTitleText );
		}

		if ( $this->oldTitle === null ) {
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
			DeferredUpdates::addCallableUpdate( function() use ( $user ) {
				$user->spreadAnyEditBlock();
			} );
			throw new PermissionsError( 'move', $permErrors );
		}

		$def = !$request->wasPosted();

		$this->reason = $request->getText( 'wpReason' );
		$this->moveTalk = $request->getBool( 'wpMovetalk', $def );
		$this->fixRedirects = $request->getBool( 'wpFixRedirects', $def );
		$this->leaveRedirect = $request->getBool( 'wpLeaveRedirect', $def );
		$this->moveSubpages = $request->getBool( 'wpMovesubpages', false );
		$this->deleteAndMove = $request->getBool( 'wpDeleteAndMove' ) && $request->getBool( 'wpConfirm' );
		$this->moveOverShared = $request->getBool( 'wpMoveOverSharedFile', false );
		$this->watch = $request->getCheck( 'wpWatch' ) && $user->isLoggedIn();

		if ( 'submit' == $request->getVal( 'action' ) && $request->wasPosted()
			&& $user->matchEditToken( $request->getVal( 'wpEditToken' ) )
		) {
			$this->doSubmit();
		} else {
			$this->showForm( array() );
		}
	}

	/**
	 * Show the form
	 *
	 * @param array $err Error messages. Each item is an error message.
	 *    It may either be a string message name or array message name and
	 *    parameters, like the second argument to OutputPage::wrapWikiMsg().
	 */
	function showForm( $err ) {
		global $wgContLang;

		$this->getSkin()->setRelevantTitle( $this->oldTitle );

		$oldTitleLink = Linker::link( $this->oldTitle );

		$out = $this->getOutput();
		$out->setPageTitle( $this->msg( 'move-page', $this->oldTitle->getPrefixedText() ) );
		$out->addModules( 'mediawiki.special.movePage' );
		$out->addModuleStyles( 'mediawiki.special.movePage.styles' );
		$this->addHelpLink( 'Help:Moving a page' );

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
			$out->addWikiMsg( 'delete_and_move_text', $newTitle->getPrefixedText() );
			$movepagebtn = $this->msg( 'delete_and_move' )->text();
			$submitVar = 'wpDeleteAndMove';
			$confirm = true;
			$err = array();
		} else {
			if ( $this->oldTitle->getNamespace() == NS_USER && !$this->oldTitle->isSubpage() ) {
				$out->wrapWikiMsg(
					"<div class=\"error mw-moveuserpage-warning\">\n$1\n</div>",
					'moveuserpage-warning'
				);
			} elseif ( $this->oldTitle->getNamespace() == NS_CATEGORY ) {
				$out->wrapWikiMsg(
					"<div class=\"error mw-movecategorypage-warning\">\n$1\n</div>",
					'movecategorypage-warning'
				);
			}

			$out->addWikiMsg( $this->getConfig()->get( 'FixDoubleRedirects' ) ?
				'movepagetext' :
				'movepagetext-noredirectfixer'
			);
			$movepagebtn = $this->msg( 'movepagebtn' )->text();
			$submitVar = 'wpMove';
			$confirm = false;
		}

		if ( count( $err ) == 1 && isset( $err[0][0] ) && $err[0][0] == 'file-exists-sharedrepo'
			&& $user->isAllowed( 'reupload-shared' )
		) {
			$out->addWikiMsg( 'move-over-sharedrepo', $newTitle->getPrefixedText() );
			$submitVar = 'wpMoveOverSharedFile';
			$err = array();
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

		$dbr = wfGetDB( DB_SLAVE );
		if ( $this->getConfig()->get( 'FixDoubleRedirects' ) ) {
			$hasRedirects = $dbr->selectField( 'redirect', '1',
				array(
					'rd_namespace' => $this->oldTitle->getNamespace(),
					'rd_title' => $this->oldTitle->getDBkey(),
				), __METHOD__ );
		} else {
			$hasRedirects = false;
		}

		if ( $considerTalk ) {
			$out->addWikiMsg( 'movepagetalktext' );
		}

		if ( count( $err ) ) {
			$out->addHTML( "<div class='error'>\n" );
			$action_desc = $this->msg( 'action-move' )->plain();
			$out->addWikiMsg( 'permissionserrorstext-withaction', count( $err ), $action_desc );

			if ( count( $err ) == 1 ) {
				$errMsg = $err[0];
				$errMsgName = array_shift( $errMsg );

				if ( $errMsgName == 'hookaborted' ) {
					$out->addHTML( "<p>{$errMsg[0]}</p>\n" );
				} else {
					$out->addWikiMsgArray( $errMsgName, $errMsg );
				}
			} else {
				$errStr = array();

				foreach ( $err as $errMsg ) {
					if ( $errMsg[0] == 'hookaborted' ) {
						$errStr[] = $errMsg[1];
					} else {
						$errMsgName = array_shift( $errMsg );
						$errStr[] = $this->msg( $errMsgName, $errMsg )->parse();
					}
				}

				$out->addHTML( '<ul><li>' . implode( "</li>\n<li>", $errStr ) . "</li></ul>\n" );
			}
			$out->addHTML( "</div>\n" );
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
				array( 'lim' => 1 )
			);
			$out->addHTML( "</div>\n" );
		}

		// Byte limit (not string length limit) for wpReason and wpNewTitleMain
		// is enforced in the mediawiki.special.movePage module

		$immovableNamespaces = array();
		foreach ( array_keys( $this->getLanguage()->getNamespaces() ) as $nsId ) {
			if ( !MWNamespace::isMovable( $nsId ) ) {
				$immovableNamespaces[] = $nsId;
			}
		}

		$handler = ContentHandler::getForTitle( $this->oldTitle );

		$out->enableOOUI();
		$fields = array();

		$fields[] = new OOUI\FieldLayout(
			new OOUI\LabelWidget( array(
				'label' => new OOUI\HtmlSnippet( "<strong>$oldTitleLink</strong>" )
			) ),
			array(
				'label' => $this->msg( 'movearticle' )->text(),
				'align' => 'top',
			)
		);

		$fields[] = new OOUI\FieldLayout(
			new MediaWiki\Widget\ComplexTitleInputWidget( array(
				'id' => 'wpNewTitle',
				'namespace' => array(
					'id' => 'wpNewTitleNs',
					'name' => 'wpNewTitleNs',
					'value' => $newTitle->getNamespace(),
					'exclude' => $immovableNamespaces,
				),
				'title' => array(
					'id' => 'wpNewTitleMain',
					'name' => 'wpNewTitleMain',
					'value' => $wgContLang->recodeForEdit( $newTitle->getText() ),
					// Inappropriate, since we're expecting the user to input a non-existent page's title
					'suggestions' => false,
				),
				'infusable' => true,
			) ),
			array(
				'label' => $this->msg( 'newtitle' )->text(),
				'align' => 'top',
			)
		);

		$fields[] = new OOUI\FieldLayout(
			new OOUI\TextInputWidget( array(
				'name' => 'wpReason',
				'id' => 'wpReason',
				'maxLength' => 200,
				'infusable' => true,
			) ),
			array(
				'label' => $this->msg( 'movereason' )->text(),
				'align' => 'top',
			)
		);

		if ( $considerTalk ) {
			$fields[] = new OOUI\FieldLayout(
				new OOUI\CheckboxInputWidget( array(
					'name' => 'wpMovetalk',
					'id' => 'wpMovetalk',
					'value' => '1',
					'selected' => $this->moveTalk,
				) ),
				array(
					'label' => $this->msg( 'movetalk' )->text(),
					'align' => 'inline',
				)
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
				new OOUI\CheckboxInputWidget( array(
					'name' => 'wpLeaveRedirect',
					'id' => 'wpLeaveRedirect',
					'value' => '1',
					'selected' => $isChecked,
					'disabled' => $isDisabled,
				) ),
				array(
					'label' => $this->msg( 'move-leave-redirect' )->text(),
					'align' => 'inline',
				)
			);
		}

		if ( $hasRedirects ) {
			$fields[] = new OOUI\FieldLayout(
				new OOUI\CheckboxInputWidget( array(
					'name' => 'wpFixRedirects',
					'id' => 'wpFixRedirects',
					'value' => '1',
					'selected' => $this->fixRedirects,
				) ),
				array(
					'label' => $this->msg( 'fix-double-redirects' )->text(),
					'align' => 'inline',
				)
			);
		}

		if ( $canMoveSubpage ) {
			$maximumMovedPages = $this->getConfig()->get( 'MaximumMovedPages' );
			$fields[] = new OOUI\FieldLayout(
				new OOUI\CheckboxInputWidget( array(
					'name' => 'wpMovesubpages',
					'id' => 'wpMovesubpages',
					'value' => '1',
					# Don't check the box if we only have talk subpages to
					# move and we aren't moving the talk page.
					'selected' => $this->moveSubpages && ( $this->oldTitle->hasSubpages() || $this->moveTalk ),
				) ),
				array(
					'label' => new OOUI\HtmlSnippet(
						$this->msg(
							( $this->oldTitle->hasSubpages()
								? 'move-subpages'
								: 'move-talk-subpages' )
						)->numParams( $maximumMovedPages )->params( $maximumMovedPages )->parse()
					),
					'align' => 'inline',
				)
			);
		}

		# Don't allow watching if user is not logged in
		if ( $user->isLoggedIn() ) {
			$watchChecked = $user->isLoggedIn() && ( $this->watch || $user->getBoolOption( 'watchmoves' )
				|| $user->isWatched( $this->oldTitle ) );
			$fields[] = new OOUI\FieldLayout(
				new OOUI\CheckboxInputWidget( array(
					'name' => 'wpWatch',
					'id' => 'watch', # ew
					'value' => '1',
					'selected' => $watchChecked,
				) ),
				array(
					'label' => $this->msg( 'move-watch' )->text(),
					'align' => 'inline',
				)
			);
		}

		if ( $confirm ) {
			$watchChecked = $user->isLoggedIn() && ( $this->watch || $user->getBoolOption( 'watchmoves' )
				|| $user->isWatched( $this->oldTitle ) );
			$fields[] = new OOUI\FieldLayout(
				new OOUI\CheckboxInputWidget( array(
					'name' => 'wpConfirm',
					'id' => 'wpConfirm',
					'value' => '1',
				) ),
				array(
					'label' => $this->msg( 'delete_and_move_confirm' )->text(),
					'align' => 'inline',
				)
			);
		}

		$fields[] = new OOUI\FieldLayout(
			new OOUI\ButtonInputWidget( array(
				'name' => $submitVar,
				'value' => $movepagebtn,
				'label' => $movepagebtn,
				'flags' => array( 'progressive', 'primary' ),
				'type' => 'submit',
			) ),
			array(
				'align' => 'top',
			)
		);

		$fieldset = new OOUI\FieldsetLayout( array(
			'label' => $this->msg( 'move-page-legend' )->text(),
			'id' => 'mw-movepage-table',
			'items' => $fields,
		) );

		$form = new OOUI\FormLayout( array(
			'method' => 'post',
			'action' => $this->getPageTitle()->getLocalURL( 'action=submit' ),
			'id' => 'movepage',
		) );
		$form->appendContent(
			$fieldset,
			new OOUI\HtmlSnippet(
				Html::hidden( 'wpOldTitle', $this->oldTitle->getPrefixedText() ) .
				Html::hidden( 'wpEditToken', $user->getEditToken() )
			)
		);

		$out->addHTML(
			new OOUI\PanelLayout( array(
				'classes' => array( 'movepage-wrapper' ),
				'expanded' => false,
				'padded' => true,
				'framed' => true,
				'content' => $form,
			) )
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
			$this->showForm( array( array( 'badtitletext' ) ) );

			return;
		}

		# Show a warning if the target file exists on a shared repo
		if ( $nt->getNamespace() == NS_FILE
			&& !( $this->moveOverShared && $user->isAllowed( 'reupload-shared' ) )
			&& !RepoGroup::singleton()->getLocalRepo()->findFile( $nt )
			&& wfFindFile( $nt )
		) {
			$this->showForm( array( array( 'file-exists-sharedrepo' ) ) );

			return;
		}

		# Delete to make way if requested
		if ( $this->deleteAndMove ) {
			$permErrors = $nt->getUserPermissionsErrors( 'delete', $user );
			if ( count( $permErrors ) ) {
				# Only show the first error
				$this->showForm( $permErrors );

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
			$page = WikiPage::factory( $nt );
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
			$this->showForm( $permStatus->getErrorsArray() );
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

		$oldLink = Linker::link(
			$ot,
			null,
			array( 'id' => 'movepage-oldlink' ),
			array( 'redirect' => 'no' )
		);
		$newLink = Linker::linkKnown(
			$nt,
			null,
			array( 'id' => 'movepage-newlink' )
		);
		$oldText = $ot->getPrefixedText();
		$newText = $nt->getPrefixedText();

		if ( $ot->exists() ) {
			//NOTE: we assume that if the old title exists, it's because it was re-created as
			// a redirect to the new title. This is not safe, but what we did before was
			// even worse: we just determined whether a redirect should have been created,
			// and reported that it was created if it should have, without any checks.
			// Also note that isRedirect() is unreliable because of bug 37209.
			$msgName = 'movepage-moved-redirect';
		} else {
			$msgName = 'movepage-moved-noredirect';
		}

		$out->addHTML( $this->msg( 'movepage-moved' )->rawParams( $oldLink,
			$newLink )->params( $oldText, $newText )->parseAsBlock() );
		$out->addWikiMsg( $msgName );

		Hooks::run( 'SpecialMovepageAfterMove', array( &$this, &$ot, &$nt ) );

		# Now we move extra pages we've been asked to move: subpages and talk
		# pages.  First, if the old page or the new page is a talk page, we
		# can't move any talk pages: cancel that.
		if ( $ot->isTalkPage() || $nt->isTalkPage() ) {
			$this->moveTalk = false;
		}

		if ( count( $ot->getUserPermissionsErrors( 'move-subpages', $user ) ) ) {
			$this->moveSubpages = false;
		}

		# Next make a list of id's.  This might be marginally less efficient
		# than a more direct method, but this is not a highly performance-cri-
		# tical code path and readable code is more important here.
		#
		# Note: this query works nicely on MySQL 5, but the optimizer in MySQL
		# 4 might get confused.  If so, consider rewriting as a UNION.
		#
		# If the target namespace doesn't allow subpages, moving with subpages
		# would mean that you couldn't move them back in one operation, which
		# is bad.
		# @todo FIXME: A specific error message should be given in this case.

		// @todo FIXME: Use Title::moveSubpages() here
		$dbr = wfGetDB( DB_MASTER );
		if ( $this->moveSubpages && (
			MWNamespace::hasSubpages( $nt->getNamespace() ) || (
				$this->moveTalk
					&& MWNamespace::hasSubpages( $nt->getTalkPage()->getNamespace() )
			)
		) ) {
			$conds = array(
				'page_title' . $dbr->buildLike( $ot->getDBkey() . '/', $dbr->anyString() )
					. ' OR page_title = ' . $dbr->addQuotes( $ot->getDBkey() )
			);
			$conds['page_namespace'] = array();
			if ( MWNamespace::hasSubpages( $nt->getNamespace() ) ) {
				$conds['page_namespace'][] = $ot->getNamespace();
			}
			if ( $this->moveTalk &&
				MWNamespace::hasSubpages( $nt->getTalkPage()->getNamespace() )
			) {
				$conds['page_namespace'][] = $ot->getTalkPage()->getNamespace();
			}
		} elseif ( $this->moveTalk ) {
			$conds = array(
				'page_namespace' => $ot->getTalkPage()->getNamespace(),
				'page_title' => $ot->getDBkey()
			);
		} else {
			# Skip the query
			$conds = null;
		}

		$extraPages = array();
		if ( !is_null( $conds ) ) {
			$extraPages = TitleArray::newFromResult(
				$dbr->select( 'page',
					array( 'page_id', 'page_namespace', 'page_title' ),
					$conds,
					__METHOD__
				)
			);
		}

		$extraOutput = array();
		$count = 1;
		foreach ( $extraPages as $oldSubpage ) {
			if ( $ot->equals( $oldSubpage ) || $nt->equals( $oldSubpage ) ) {
				# Already did this one.
				continue;
			}

			$newPageName = preg_replace(
				'#^' . preg_quote( $ot->getDBkey(), '#' ) . '#',
				StringUtils::escapeRegexReplacement( $nt->getDBkey() ), # bug 21234
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

			# Bug 14385: we need makeTitleSafe because the new page names may
			# be longer than 255 characters.
			$newSubpage = Title::makeTitleSafe( $newNs, $newPageName );
			if ( !$newSubpage ) {
				$oldLink = Linker::linkKnown( $oldSubpage );
				$extraOutput[] = $this->msg( 'movepage-page-unmoved' )->rawParams( $oldLink )
					->params( Title::makeName( $newNs, $newPageName ) )->escaped();
				continue;
			}

			# This was copy-pasted from Renameuser, bleh.
			if ( $newSubpage->exists() && !$oldSubpage->isValidMoveTarget( $newSubpage ) ) {
				$link = Linker::linkKnown( $newSubpage );
				$extraOutput[] = $this->msg( 'movepage-page-exists' )->rawParams( $link )->escaped();
			} else {
				$success = $oldSubpage->moveTo( $newSubpage, true, $this->reason, $createRedirect );

				if ( $success === true ) {
					if ( $this->fixRedirects ) {
						DoubleRedirectJob::fixRedirects( 'move', $oldSubpage, $newSubpage );
					}
					$oldLink = Linker::link(
						$oldSubpage,
						null,
						array(),
						array( 'redirect' => 'no' )
					);

					$newLink = Linker::linkKnown( $newSubpage );
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
					$oldLink = Linker::linkKnown( $oldSubpage );
					$newLink = Linker::link( $newSubpage );
					$extraOutput[] = $this->msg( 'movepage-page-unmoved' )
						->rawParams( $oldLink, $newLink )->escaped();
				}
			}
		}

		if ( $extraOutput !== array() ) {
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

	function showSubpages( $title ) {
		if ( !MWNamespace::hasSubpages( $title->getNamespace() ) ) {
			return;
		}

		$subpages = $title->getSubpages();
		$count = $subpages instanceof TitleArray ? $subpages->count() : 0;

		$out = $this->getOutput();
		$out->wrapWikiMsg( '== $1 ==', array( 'movesubpage', $count ) );

		# No subpages.
		if ( $count == 0 ) {
			$out->addWikiMsg( 'movenosubpage' );

			return;
		}

		$out->addWikiMsg( 'movesubpagetext', $this->getLanguage()->formatNum( $count ) );
		$out->addHTML( "<ul>\n" );

		foreach ( $subpages as $subpage ) {
			$link = Linker::link( $subpage );
			$out->addHTML( "<li>$link</li>\n" );
		}
		$out->addHTML( "</ul>\n" );
	}

	protected function getGroupName() {
		return 'pagetools';
	}
}
