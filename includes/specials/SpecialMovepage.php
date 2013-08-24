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
class MovePageForm extends FormSpecialPage {

	/**
	 * Objects
	 * @var Title
	 */
	var $oldTitle, $newTitle;
	// Text input
	var $reason;
	// Checks
	var $moveTalk, $deleteAndMove, $moveSubpages, $fixRedirects, $leaveRedirect, $moveOverShared;

	private $watch = false;

	private $action, $submitbuttonname, $submitbuttonlabel, $considertalk = false, $showerrors = false;

	/**
	 * @var array|bool
	 */
	private $errors = array();

	public function __construct() {
		parent::__construct( 'Movepage' );
	}

	/**
	 * Unlisted in Special:Specialpages
	 */
	public function isListed() {
		return false;
	}

	protected function getGroupName() {
		return 'pagetools';
	}

	/**
	 * @param string $par Subpage string if one was specified
	 */
	public function execute( $par ) {
		$this->setParameter( $par );
		$this->setHeaders();

		// This will throw exceptions if there's a problem
		$this->checkExecutePermissions( $this->getUser() );
		$this->doSubmit();

		$form = $this->getForm();
		if ( !$form->show() ) {
			$this->onFailure();
		}
	}

	# Called from execute() and childrens

	/**
	 * Maybe do something interesting with the subpage parameter
	 * Called from execute()
	 * @param string $par
	 */
	protected function setParameter( $par ) {
		$this->par = $par;

		$request = $this->getRequest();
		$target = !is_null( $par ) ? $par : $request->getVal( 'target' );

		// Yes, the use of getVal() and getText() is wanted, see bug 20365

		$oldTitleText = $request->getVal( 'wpOldTitle', $target );
		$this->oldTitle = Title::newFromText( $oldTitleText );

		if ( is_null( $this->oldTitle ) ) {
			return;
		}

		$newTitleTextMain = $request->getText( 'wpNewTitleMain' );
		$newTitleTextNs = $request->getInt( 'wpNewTitleNs', $this->oldTitle->getNamespace() );
		// Backwards compatibility for forms submitting here from other sources
		// which is more common than it should be..
		$newTitleText_bc = $request->getText( 'wpNewTitle' );
		$this->newTitle = strlen( $newTitleText_bc ) > 0
			? Title::newFromText( $newTitleText_bc )
			: Title::makeTitleSafe( $newTitleTextNs, $newTitleTextMain );

		$def = !$request->wasPosted();

		$this->reason = $request->getText( 'wpReason' );
		$this->moveTalk = $request->getBool( 'wpMovetalk', $def );
		$this->fixRedirects = $request->getBool( 'wpFixRedirects', $def );
		$this->leaveRedirect = $request->getBool( 'wpLeaveRedirect', $def );
		$this->moveSubpages = $request->getBool( 'wpMovesubpages', false );
		$this->deleteAndMove = $request->getBool( 'wpDeleteAndMove' ) && $request->getBool( 'wpConfirm' );
		$this->moveOverShared = $request->getBool( 'wpMoveOverSharedFile', false );
		$this->watch = $request->getCheck( 'wpWatch' ) && $this->getUser()->isLoggedIn();

		$this->action = $request->getVal( 'action' );
	}

	/**
	 * Called from execute() to check if the given user can perform this action
	 * Failures here must throw subclasses of ErrorPageError
	 * @param User $user
	 * @throws ErrorPageError
	 * @throws PermissionsError
	 * @return bool true
	 */
	protected function checkExecutePermissions( User $user ) {
		$this->checkReadOnly();

		if ( is_null( $this->oldTitle ) ) {
			throw new ErrorPageError( 'notargettitle', 'notargettext' );
		}
		if ( !$this->oldTitle->exists() ) {
			throw new ErrorPageError( 'nopagetitle', 'nopagetext' );
		}

		$user = $this->getUser();

		# Check rights
		$permErrors = $this->oldTitle->getUserPermissionsErrors( 'move', $user );
		if ( count( $permErrors ) ) {
			// Auto-block user's IP if the account was "hard" blocked
			$user->spreadAnyEditBlock();
			throw new PermissionsError( 'move', $permErrors );
		}

		return true;
	}

	/**
	 * Move pages
	 */
	public function doSubmit() {
		global $wgMaximumMovedPages, $wgFixDoubleRedirects;

		$request = $this->getRequest();

		if ( $this->action !== 'submit' || !$request->wasPosted() ) {
			return;
		}

		$user = $this->getUser();

		if ( !$user->matchEditToken( $request->getVal( 'wpEditToken' ) ) ) {
			throw new ErrorPageError( 'sessionfailure-title', 'sessionfailure' );
		}

		if ( $user->pingLimiter( 'move' ) ) {
			throw new ThrottledError;
		}

		$ot = $this->oldTitle;
		$nt = $this->newTitle;

		# don't allow moving to pages with # in
		if ( !$nt || $nt->getFragment() != '' ) {
			$this->errors = array( array( 'badtitletext' ) );
			return;
		}

		# Show a warning if the target file exists on a shared repo
		if ( $nt->getNamespace() == NS_FILE
			&& !( $this->moveOverShared && $user->isAllowed( 'reupload-shared' ) )
			&& !RepoGroup::singleton()->getLocalRepo()->findFile( $nt )
			&& wfFindFile( $nt )
		) {
			$this->errors = array( array( 'file-exists-sharedrepo' ) );
			return;
		}

		# Delete to make way if requested
		if ( $this->deleteAndMove ) {
			$permErrors = $nt->getUserPermissionsErrors( 'delete', $user );
			if ( count( $permErrors ) ) {
				# Only show the first error
				$this->errors = $permErrors;
				return;
			}

			$reason = $this->msg( 'delete_and_move_reason', $ot )->inContentLanguage()->text();

			// Delete an associated image if there is
			if ( $nt->getNamespace() == NS_FILE ) {
				$file = wfLocalFile( $nt );
				if ( $file->exists() ) {
					$file->delete( $reason, false );
				}
			}

			$error = ''; // passed by ref
			$page = WikiPage::factory( $nt );
			$deleteStatus = $page->doDeleteArticleReal( $reason, false, 0, true, $error, $user );
			if ( !$deleteStatus->isGood() ) {
				$this->errors = $deleteStatus->getErrorsArray();
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
		$error = $ot->moveTo( $nt, true, $this->reason, $createRedirect );
		if ( $error !== true ) {
			$this->errors = $error;
			return;
		}

		if ( $wgFixDoubleRedirects && $this->fixRedirects ) {
			DoubleRedirectJob::fixRedirects( 'move', $ot, $nt );
		}

		$out = $this->getOutput();
		$out->setPageTitle( $this->msg( 'pagemovedsub' ) );

		$oldLink = Linker::link(
			$ot,
			null,
			array(),
			array( 'redirect' => 'no' )
		);
		$newLink = Linker::linkKnown( $nt );
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

		wfRunHooks( 'SpecialMovepageAfterMove', array( &$this, &$ot, &$nt ) );

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
				$this->moveTalk &&
					MWNamespace::hasSubpages( $nt->getTalkPage()->getNamespace() )
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

			if ( $oldSubpage->isTalkPage() ) {
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
					$extraOutput[] = $this->msg( 'movepage-page-moved' )->rawParams( $oldLink, $newLink )->escaped();
					++$count;

					if ( $count >= $wgMaximumMovedPages ) {
						$extraOutput[] = $this->msg( 'movepage-max-pages' )->numParams( $wgMaximumMovedPages )->escaped();
						break;
					}
				} else {
					$oldLink = Linker::linkKnown( $oldSubpage );
					$newLink = Linker::link( $newSubpage );
					$extraOutput[] = $this->msg( 'movepage-page-unmoved' )->rawParams( $oldLink, $newLink )->escaped();
				}
			}
		}

		if ( $extraOutput !== array() ) {
			$out->addHTML( "<ul>\n<li>" . implode( "</li>\n<li>", $extraOutput ) . "</li>\n</ul>" );
		}

		# Deal with watches (we don't watch subpages)
		WatchAction::doWatchOrUnwatch( $this->watch, $ot, $user );
		WatchAction::doWatchOrUnwatch( $this->watch, $nt, $user );

		$this->errors = false;
	}

	/**
	 * Get the HTMLForm to control behavior
	 * Called from execute()
	 * @return HTMLForm|null
	 */
	protected function getForm() {
		$this->fields = $this->getFormFields();

		$descriptor = array();
		// Give hooks a chance to alter the form, adding extra fields or text etc
		wfRunHooks( 'SpecialMovepageForm', array( &$descriptor, &$form ) );
		$this->fields = $this->fields + $descriptor;
		$this->fields = array_merge( $this->fields, $this->getSubmitButtonDescriptor() );

		$form = new HTMLForm( $this->fields, $this->getContext() );
		$t = $this->getTitle();
		$form->setTitle( $t );
		$form->setAction( $t->getLocalURL( 'action=submit' ) );
		$form->suppressDefaultSubmit();
		$form->setSubmitCallback( array( $this, 'onSubmit' ) );
		$form->setWrapperLegendMsg( 'move-page-legend' );

		//$headerMsg = $this->msg( 'move-page-text' );
		//if ( !$headerMsg->isDisabled() ) {
		//	$form->addHeaderText( $headerMsg->parseAsBlock() );
		//}

		// Retain query parameters (uselang etc)
		$params = array_diff_key(
			$this->getRequest()->getQueryValues(), array( 'title' => null )
		);
		$form->addHiddenField( 'redirectparams', wfArrayToCgi( $params ) );

		$form->addPreText( $this->preText() );
		$form->addPostText( $this->postText() );
		//$this->alterForm( $form );

		// Because class HTMLForm doesn't have functions for altering descripter,
		// the hook is commented out
		//// Give hooks a chance to alter the form, adding extra fields or text etc
		//wfRunHooks( "Special{$this->getName()}BeforeFormDisplay", array( &$form ) );

		return $form;
	}

	## Called from getForm()

	/**
	 * Get message prefix for HTMLForm
	 * @return string
	 */
	protected function getMessagePrefix() {
		return '';
	}

	/**
	 * Get an HTMLForm descriptor array.
	 * Called from getForm().
	 * @return array
	 */
	protected function getFormFields() {
		global $wgContLang, $wgFixDoubleRedirects, $wgMaximumMovedPages;
		$fields = array();
		$err = $this->errors;
		if ( $err === false ) {
			$err = array();
		}
		$user = $this->getUser();

		$oldTitleLink = '<strong>' . Linker::link( $this->oldTitle ) . '</strong>';
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

		$confirm = false;
		if ( count( $err ) == 1 && isset( $err[0][0] ) && $err[0][0] == 'articleexists'
			&& $newTitle->quickUserCan( 'delete', $user )
		) {
			$this->submitbuttonlabel = 'delete_and_move';
			$this->submitbuttonname = 'DeleteAndMove';
			$confirm = true;
		} else {
			$this->submitbuttonlabel = 'movepagebtn';
			$this->submitbuttonname = 'Move';
			if ( count( $err ) == 1 && isset( $err[0][0] ) && $err[0][0] == 'file-exists-sharedrepo'
				&& $user->isAllowed( 'reupload-shared' )
			) {
				$this->submitbuttonname = 'MoveOverSharedFile';
			} else {
				$this->showerrors = true;
			}
		}

		$immovableNamespaces = array();
		foreach ( array_keys( $this->getLanguage()->getNamespaces() ) as $nsId ) {
			if ( !MWNamespace::isMovable( $nsId ) ) {
				$immovableNamespaces[] = $nsId;
			}
		}

		$fields = array(
			'oldtitlelink' => array(
				'type' => 'info',
				'label-message' => 'movearticle',
				'default' => $oldTitleLink,
				'raw' => true,
			),
			'newtitle' => array(
				'type' => 'info',
				'label-message' => 'newtitle',
				'default' => Html::namespaceSelector(
						array(
							'selected' => $newTitle->getNamespace(),
							'exclude' => $immovableNamespaces,
						),
						array( 'name' => 'wpNewTitleNs', 'id' => 'wpNewTitleNs' )
					) .
					Xml::input(
						'wpNewTitleMain',
						60,
						$wgContLang->recodeForEdit( $newTitle->getText() ),
						array(
							'type' => 'text',
							'id' => 'wpNewTitleMain',
							'maxlength' => 255,
						)
					) .
					Html::hidden( 'wpOldTitle', $this->oldTitle->getPrefixedText() ),
				'raw' => true,
			),
			'Reason' => array(
				'type' => 'text',
				'label-message' => 'movereason',
				'default' => $this->reason,
				'id' => 'wpReason',
				'size' => 60,
				'maxlength' => 200,
			),
		);

		$oldTalk = $this->oldTitle->getTalkPage();
		$oldTitleSubpages = $this->oldTitle->hasSubpages();
		$oldTitleTalkSubpages = $this->oldTitle->getTalkPage()->hasSubpages();

		$canMoveSubpage = ( $oldTitleSubpages || $oldTitleTalkSubpages )
			&& !count( $this->oldTitle->getUserPermissionsErrors( 'move-subpages', $user ) );

		# We also want to be able to move assoc. subpage talk-pages even if base page
		# has no associated talk page, so || with $oldTitleTalkSubpages.
		$considerTalk = !$this->oldTitle->isTalkPage()
			&& ( $oldTalk->exists() || ( $oldTitleTalkSubpages && $canMoveSubpage ) );

		if ( $considerTalk ) {
			$fields['Movetalk'] = array(
				'type' => 'toggle',
				'label-message' => 'movetalk',
				'id' => 'wpMovetalk',
				'invert' => $this->moveTalk,
			);
			$this->considertalk = true;
		}

		$handler = ContentHandler::getForTitle( $this->oldTitle );

		if ( $user->isAllowed( 'suppressredirect' ) && $handler->supportsRedirects() ) {
			$fields['LeaveRedirect'] = array(
				'type' => 'toggle',
				'label-message' => 'move-leave-redirect',
				'id' => 'wpLeaveRedirect',
				'invert' => $this->leaveRedirect,
			);
		}

		$dbr = wfGetDB( DB_SLAVE );
		if ( $wgFixDoubleRedirects ) {
			$hasRedirects = $dbr->selectField( 'redirect', '1',
				array(
					'rd_namespace' => $this->oldTitle->getNamespace(),
					'rd_title' => $this->oldTitle->getDBkey(),
				), __METHOD__ );
		} else {
			$hasRedirects = false;
		}

		if ( $hasRedirects ) {
			$fields['FixRedirects'] = array(
				'type' => 'toggle',
				'label-message' => 'fix-double-redirects',
				'id' => 'wpFixRedirects',
				'invert' => $this->fixRedirects,
			);
		}

		if ( $canMoveSubpage ) {
			$fields['Movesubpages'] = array(
				'type' => 'toggle',
				'label' => $this->msg( (
						$this->oldTitle->hasSubpages() ? 'move-subpages' : 'move-talk-subpages'
					) )->numParams( $wgMaximumMovedPages )->params( $wgMaximumMovedPages )
					->parse(),
				'id' => 'wpMovesubpages',
				'invert' => $this->moveSubpages
					&& ( $this->oldTitle->hasSubpages() || $this->moveTalk ),
			);
		}

		# Don't allow watching if user is not logged in
		if ( $user->isLoggedIn() ) {
			$fields['Watch'] = array(
				'type' => 'toggle',
				'label-message' => 'move-watch',
				'id' => 'watch',
				'invert' => $user->isLoggedIn() && (
						$this->watch
						|| $user->getBoolOption( 'watchmoves' )
						|| $user->isWatched( $this->oldTitle )
					),
			);
		}

		if ( $confirm ) {
			$fields['Confirm'] = array(
				'type' => 'toggle',
				'label-message' => 'delete_and_move_confirm',
				'id' => 'wpConfirm',
			);
		}

		return $fields;
	}

	/**
	 * @return array
	 */
	protected function getSubmitButtonDescriptor() {
		return array(
			$this->submitbuttonname => array(
				'type' => 'info',
				'default' => Xml::submitButton( $this->msg( $this->submitbuttonlabel )->plain(), array( 'name' => "wp{$this->submitbuttonname}" ) ),
				'raw' => true,
			)
		);
	}

	/**
	 * Add pre-text to the form
	 * @return String HTML which will be sent to $form->addPreText()
	 */
	protected function preText() {
		global $wgFixDoubleRedirects;
		$out = '';

		if ( $this->submitbuttonlabel === 'delete_and_move' ) {
			$out .= $this->msg( 'delete_and_move_text', $this->newTitle->getPrefixedText() )
				->parseAsBlock();
		} else {
			if ( $this->oldTitle->getNamespace() == NS_USER && !$this->oldTitle->isSubpage() ) {
				$out .= "<div class=\"error mw-moveuserpage-warning\">\n"
					. $this->msg( 'moveuserpage-warning' )->parse()
					. "\n</div>";
			}

			$out .= $this->msg(
				$wgFixDoubleRedirects ? 'movepagetext' : 'movepagetext-noredirectfixer'
			)->parseAsBlock();

			if ( $this->submitbuttonname === 'MoveOverSharedFile' ) {
				$out .= $this->msg( 'move-over-sharedrepo', $this->newTitle->getPrefixedText() )
					->parseAsBlock();
			}
		}

		$err = array();
		if ( $this->showerrors ) {
			$err = $this->errors;
		}

		if ( count( $err ) ) {
			$out .= "<div class=\"error\">\n";
			$action_desc = $this->msg( 'action-move' )->plain();
			$out .= $this->msg( 'permissionserrorstext-withaction', count( $err ), $action_desc )
						->text();

			if ( count( $err ) == 1 ) {
				$errMsg = $err[0];
				$errMsgName = array_shift( $errMsg );

				if ( $errMsgName == 'hookaborted' ) {
					$out .= "<p>{$errMsg[0]}</p>\n";
				} else {
					$out .= $this->msg( $errMsgName, $errMsg )->parse();
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

				$out .= '<ul><li>' . implode( "</li>\n<li>", $errStr ) . "</li></ul>\n";
			}
			$out .= "</div>\n";
		}

		if ( $this->oldTitle->isProtected( 'move' ) ) {
			# Is the title semi-protected?
			if ( $this->oldTitle->isSemiProtected( 'move' ) ) {
				$noticeMsg = 'semiprotectedpagemovewarning';
			} else {
				# Then it must be protected based on static groups (regular)
				$noticeMsg = 'protectedpagemovewarning';
			}
			$out .= "<div class=\"mw-warning-with-logexcerpt\">\n";
			$out .= $this->msg( $noticeMsg )->parse();
			LogEventsList::showLogExtract(
				$out, 'protect', $this->oldTitle, '', array( 'lim' => 1 )
			);
			$out .= "</div>\n";
		}

		return $out;
	}

	/**
	 * Add post-text to the form
	 * @return string HTML which will be sent to $form->addPostText()
	 */
	protected function postText() {
		return $this->getLogFragment( $this->oldTitle )
			. $this->getSubpages( $this->oldTitle );
	}

	/**
	 * Get move loggings
	 * Called from postText()
	 *
	 * @param Title $title
	 * @return string HTML
	 */
	function getLogFragment( $title ) {
		$out = '';
		$moveLogPage = new LogPage( 'move' );
		LogEventsList::showLogExtract( $out, 'move', $title );

		return Xml::element( 'h2', null, $moveLogPage->getName()->text() ) . $out;
	}

	/**
	 * Get subpage list
	 * Called from postText()
	 *
	 * @param Title $title
	 * @return string HTML
	 */
	function getSubpages( $title ) {
		if ( !MWNamespace::hasSubpages( $title->getNamespace() ) ) {
			return '';
		}

		$subpages = $title->getSubpages();
		$count = $subpages instanceof TitleArray ? $subpages->count() : 0;

		$out = Xml::element( 'h2', null, $this->msg( 'movesubpage', $count )->text() );

		# No subpages.
		if ( $count == 0 ) {
			$out .= $this->msg( 'movenosubpage' )->escaped();
			return $out;
		}

		$out .= $this->msg( 'movesubpagetext' )->formatNum( $count )->text();

		$out .= "<ul>\n";
		foreach ( $subpages as $subpage ) {
			$link = Linker::link( $subpage );
			$out .= "<li>$link</li>\n";
		}
		$out .= "</ul>\n";

		return $out;
	}

	### callback

	/**
	 * Process the form on POST submission.
	 * Callbacked from HTMLForm::trySubmit
	 * @param array $data
	 * @return bool|array true for success, false for didn't-try, array of errors on failure
	 */
	public function onSubmit( array $data ) {
		return $this->errors === false;
	}

	# Called from execute()

	/**
	 * Called when moving is failed or the page is first viewing
	 */
	protected function onFailure() {
		$this->getSkin()->setRelevantTitle( $this->oldTitle );

		$out = $this->getOutput();
		$out->setPageTitle( $this->msg( 'move-page', $this->oldTitle->getPrefixedText() ) );

		# Enforce byte limit (not string length limit) for wpReason and wpNewTitleMain
		$out->addModules( 'mediawiki.special.movePage' );
	}
}
