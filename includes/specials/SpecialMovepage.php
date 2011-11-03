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

	/**
	 * @var Title
	 */
	var $oldTitle, $newTitle; # Objects
	var $reason; # Text input
	var $moveTalk, $deleteAndMove, $moveSubpages, $fixRedirects, $leaveRedirect, $moveOverShared; # Checks

	private $watch = false;

	public function __construct() {
		parent::__construct( 'Movepage' );
	}

	public function execute( $par ) {
		# Check for database lock
		if ( wfReadOnly() ) {
			throw new ReadOnlyError;
		}

		$this->setHeaders();
		$this->outputHeader();

		$request = $this->getRequest();
		$target = !is_null( $par ) ? $par : $request->getVal( 'target' );

		// Yes, the use of getVal() and getText() is wanted, see bug 20365
		$oldTitleText = $request->getVal( 'wpOldTitle', $target );
		$newTitleText = $request->getText( 'wpNewTitle' );

		$this->oldTitle = Title::newFromText( $oldTitleText );
		$this->newTitle = Title::newFromText( $newTitleText );

		if( is_null( $this->oldTitle ) ) {
			throw new ErrorPageError( 'notargettitle', 'notargettext' );
		}
		if( !$this->oldTitle->exists() ) {
			throw new ErrorPageError( 'nopagetitle', 'nopagetext' );
		}

		$user = $this->getUser();

		# Check rights
		$permErrors = $this->oldTitle->getUserPermissionsErrors( 'move', $user );
		if( !empty( $permErrors ) ) {
			// Auto-block user's IP if the account was "hard" blocked
			$user->spreadAnyEditBlock();
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
			&& $user->matchEditToken( $request->getVal( 'wpEditToken' ) ) ) {
			$this->doSubmit();
		} else {
			$this->showForm( '' );
		}
	}

	/**
	 * Show the form
	 *
	 * @param $err Mixed: error message. May either be a string message name or
	 *    array message name and parameters, like the second argument to
	 *    OutputPage::wrapWikiMsg().
	 */
	function showForm( $err ) {
		global $wgContLang, $wgFixDoubleRedirects, $wgMaximumMovedPages;

		$this->getSkin()->setRelevantTitle( $this->oldTitle );

		$oldTitleLink = Linker::link( $this->oldTitle );

		$out = $this->getOutput();
		$out->setPageTitle( $this->msg( 'move-page', $this->oldTitle->getPrefixedText() ) );
		$out->addModules( 'mediawiki.special.movePage' );

		$newTitle = $this->newTitle;

		if( !$newTitle ) {
			# Show the current title as a default
			# when the form is first opened.
			$newTitle = $this->oldTitle;
		}
		else {
			if( empty($err) ) {
				# If a title was supplied, probably from the move log revert
				# link, check for validity. We can then show some diagnostic
				# information and save a click.
				$newerr = $this->oldTitle->isValidMoveOperation( $newTitle );
				if( $newerr ) {
					$err = $newerr[0];
				}
			}
		}

		$user = $this->getUser();

		if ( !empty($err) && $err[0] == 'articleexists' && $user->isAllowed( 'delete' ) ) {
			$out->addWikiMsg( 'delete_and_move_text', $newTitle->getPrefixedText() );
			$movepagebtn = wfMsg( 'delete_and_move' );
			$submitVar = 'wpDeleteAndMove';
			$confirm = "
				<tr>
					<td></td>
					<td class='mw-input'>" .
						Xml::checkLabel( wfMsg( 'delete_and_move_confirm' ), 'wpConfirm', 'wpConfirm' ) .
					"</td>
				</tr>";
			$err = '';
		} else {
			if ($this->oldTitle->getNamespace() == NS_USER && !$this->oldTitle->isSubpage() ) {
				$out->wrapWikiMsg( "<div class=\"error mw-moveuserpage-warning\">\n$1\n</div>", 'moveuserpage-warning' );
			}
			$out->addWikiMsg( $wgFixDoubleRedirects ? 'movepagetext' :
				'movepagetext-noredirectfixer' );
			$movepagebtn = wfMsg( 'movepagebtn' );
			$submitVar = 'wpMove';
			$confirm = false;
		}

		if ( !empty($err) && $err[0] == 'file-exists-sharedrepo' && $user->isAllowed( 'reupload-shared' ) ) {
			$out->addWikiMsg( 'move-over-sharedrepo', $newTitle->getPrefixedText() );
			$submitVar = 'wpMoveOverSharedFile';
			$err = '';
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
		if ( $wgFixDoubleRedirects ) {
			$hasRedirects = $dbr->selectField( 'redirect', '1',
				array(
					'rd_namespace' => $this->oldTitle->getNamespace(),
					'rd_title' => $this->oldTitle->getDBkey(),
				) , __METHOD__ );
		} else {
			$hasRedirects = false;
		}

		if ( $considerTalk ) {
			$out->addWikiMsg( 'movepagetalktext' );
		}

		$token = htmlspecialchars( $user->editToken() );

		if ( !empty($err) ) {
			$out->setSubtitle( wfMsg( 'formerror' ) );
			if( $err[0] == 'hookaborted' ) {
				$hookErr = $err[1];
				$errMsg = "<p><strong class=\"error\">$hookErr</strong></p>\n";
				$out->addHTML( $errMsg );
			} else {
				$out->wrapWikiMsg( "<p><strong class=\"error\">\n$1\n</strong></p>", $err );
			}
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
			LogEventsList::showLogExtract( $out, 'protect', $this->oldTitle, '', array( 'lim' => 1 ) );
			$out->addHTML( "</div>\n" );
		}

		$out->addHTML(
			 Xml::openElement( 'form', array( 'method' => 'post', 'action' => $this->getTitle()->getLocalURL( 'action=submit' ), 'id' => 'movepage' ) ) .
			 Xml::openElement( 'fieldset' ) .
			 Xml::element( 'legend', null, wfMsg( 'move-page-legend' ) ) .
			 Xml::openElement( 'table', array( 'border' => '0', 'id' => 'mw-movepage-table' ) ) .
			 "<tr>
				<td class='mw-label'>" .
					wfMsgHtml( 'movearticle' ) .
				"</td>
				<td class='mw-input'>
					<strong>{$oldTitleLink}</strong>
				</td>
			</tr>
			<tr>
				<td class='mw-label'>" .
					Xml::label( wfMsg( 'newtitle' ), 'wpNewTitle' ) .
				"</td>
				<td class='mw-input'>" .
					Xml::input( 'wpNewTitle', 40, $wgContLang->recodeForEdit( $newTitle->getPrefixedText() ), array( 'type' => 'text', 'id' => 'wpNewTitle' ) ) .
					Html::hidden( 'wpOldTitle', $this->oldTitle->getPrefixedText() ) .
				"</td>
			</tr>
			<tr>
				<td class='mw-label'>" .
					Xml::label( wfMsg( 'movereason' ), 'wpReason' ) .
				"</td>
				<td class='mw-input'>" .
					Html::element( 'textarea', array( 'name' => 'wpReason', 'id' => 'wpReason', 'cols' => 60, 'rows' => 2,
					'maxlength' => 200 ), $this->reason ) . // maxlength byte limit is enforce in mediawiki.special.movePage.js
				"</td>
			</tr>"
		);

		if( $considerTalk ) {
			$out->addHTML( "
				<tr>
					<td></td>
					<td class='mw-input'>" .
						Xml::checkLabel( wfMsg( 'movetalk' ), 'wpMovetalk', 'wpMovetalk', $this->moveTalk ) .
					"</td>
				</tr>"
			);
		}

		if ( $user->isAllowed( 'suppressredirect' ) ) {
			$out->addHTML( "
				<tr>
					<td></td>
					<td class='mw-input' >" .
						Xml::checkLabel( wfMsg( 'move-leave-redirect' ), 'wpLeaveRedirect',
							'wpLeaveRedirect', $this->leaveRedirect ) .
					"</td>
				</tr>"
			);
		}

		if ( $hasRedirects ) {
			$out->addHTML( "
				<tr>
					<td></td>
					<td class='mw-input' >" .
						Xml::checkLabel( wfMsg( 'fix-double-redirects' ), 'wpFixRedirects',
							'wpFixRedirects', $this->fixRedirects ) .
					"</td>
				</tr>"
			);
		}

		if( $canMoveSubpage ) {
			$out->addHTML( "
				<tr>
					<td></td>
					<td class=\"mw-input\">" .
				Xml::check(
					'wpMovesubpages',
					# Don't check the box if we only have talk subpages to
					# move and we aren't moving the talk page.
					$this->moveSubpages && ($this->oldTitle->hasSubpages() || $this->moveTalk),
					array( 'id' => 'wpMovesubpages' )
				) . '&#160;' .
				Xml::tags( 'label', array( 'for' => 'wpMovesubpages' ),
					wfMsgExt(
						( $this->oldTitle->hasSubpages()
							? 'move-subpages'
							: 'move-talk-subpages' ),
						array( 'parseinline' ),
						$this->getLang()->formatNum( $wgMaximumMovedPages ),
						# $2 to allow use of PLURAL in message.
						$wgMaximumMovedPages
					)
				) .
					"</td>
				</tr>"
			);
		}

		$watchChecked = $user->isLoggedIn() && ($this->watch || $user->getBoolOption( 'watchmoves' )
			|| $this->oldTitle->userIsWatching());
		# Don't allow watching if user is not logged in
		if( $user->isLoggedIn() ) {
			$out->addHTML( "
			<tr>
				<td></td>
				<td class='mw-input'>" .
					Xml::checkLabel( wfMsg( 'move-watch' ), 'wpWatch', 'watch', $watchChecked ) .
				"</td>
			</tr>");
		}

		$out->addHTML( "
				{$confirm}
			<tr>
				<td>&#160;</td>
				<td class='mw-submit'>" .
					Xml::submitButton( $movepagebtn, array( 'name' => $submitVar ) ) .
				"</td>
			</tr>" .
			Xml::closeElement( 'table' ) .
			Html::hidden( 'wpEditToken', $token ) .
			Xml::closeElement( 'fieldset' ) .
			Xml::closeElement( 'form' ) .
			"\n"
		);

		$this->showLogFragment( $this->oldTitle );
		$this->showSubpages( $this->oldTitle );

	}

	function doSubmit() {
		global $wgMaximumMovedPages, $wgFixDoubleRedirects, $wgDeleteRevisionsLimit;

		$user = $this->getUser();

		if ( $user->pingLimiter( 'move' ) ) {
			throw new ThrottledError;
		}

		$ot = $this->oldTitle;
		$nt = $this->newTitle;

		# Delete to make way if requested
		if ( !count( $nt->getUserPermissionsErrors( 'delete', $user ) ) && $this->deleteAndMove ) {
			$page = WikiPage::factory( $nt );

			# Disallow deletions of big articles
			$bigHistory = $page->isBigDeletion();
			if( $bigHistory && count( $nt->getUserPermissionsErrors( 'bigdelete', $user ) ) ) {
				$this->showForm( array( 'delete-toobig', $this->getLang()->formatNum( $wgDeleteRevisionsLimit ) ) );
				return;
			}

			$reason = wfMessage( 'delete_and_move_reason', $ot )->inContentLanguage()->text();

			// Delete an associated image if there is
			$file = wfLocalFile( $nt );
			if( $file->exists() ) {
				$file->delete( $reason, false );
			}

			$error = ''; // passed by ref
			if ( !$page->doDeleteArticle( $reason, false, 0, true, $error, $user ) ) {
				$this->showForm( array( 'cannotdelete', wfEscapeWikiText( $nt->getPrefixedText() ) ) );
				return;
			}

		}

		# don't allow moving to pages with # in
		if ( !$nt || $nt->getFragment() != '' ) {
			$this->showForm( 'badtitletext' );
			return;
		}

		# Show a warning if the target file exists on a shared repo
		if ( $nt->getNamespace() == NS_FILE
			&& !( $this->moveOverShared && $user->isAllowed( 'reupload-shared' ) )
			&& !RepoGroup::singleton()->getLocalRepo()->findFile( $nt )
			&& wfFindFile( $nt ) )
		{
			$this->showForm( array('file-exists-sharedrepo') );
			return;

		}

		if ( $user->isAllowed( 'suppressredirect' ) ) {
			$createRedirect = $this->leaveRedirect;
		} else {
			$createRedirect = true;
		}

		# Do the actual move.
		$error = $ot->moveTo( $nt, true, $this->reason, $createRedirect );
		if ( $error !== true ) {
			# @todo FIXME: Show all the errors in a list, not just the first one
			$this->showForm( reset( $error ) );
			return;
		}

		if ( $wgFixDoubleRedirects && $this->fixRedirects ) {
			DoubleRedirectJob::fixRedirects( 'move', $ot, $nt );
		}

		wfRunHooks( 'SpecialMovepageAfterMove', array( &$this, &$ot, &$nt ) );

		$out = $this->getOutput();
		$out->setPagetitle( wfMsg( 'pagemovedsub' ) );

		$oldLink = Linker::link(
			$ot,
			null,
			array(),
			array( 'redirect' => 'no' )
		);
		$newLink = Linker::linkKnown( $nt );	
		$oldText = $ot->getPrefixedText();
		$newText = $nt->getPrefixedText();

		$msgName = $createRedirect ? 'movepage-moved-redirect' : 'movepage-moved-noredirect';
		$out->addHTML( wfMessage( 'movepage-moved' )->rawParams( $oldLink, $newLink, $oldText, $newText )->parseAsBlock() );
		$out->addWikiMsg( $msgName );

		# Now we move extra pages we've been asked to move: subpages and talk
		# pages.  First, if the old page or the new page is a talk page, we
		# can't move any talk pages: cancel that.
		if( $ot->isTalkPage() || $nt->isTalkPage() ) {
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
		if( $this->moveSubpages && (
			MWNamespace::hasSubpages( $nt->getNamespace() ) || (
				$this->moveTalk &&
				MWNamespace::hasSubpages( $nt->getTalkPage()->getNamespace() )
			)
		) ) {
			$conds = array(
				'page_title' . $dbr->buildLike( $ot->getDBkey() . '/', $dbr->anyString() )
					.' OR page_title = ' . $dbr->addQuotes( $ot->getDBkey() )
			);
			$conds['page_namespace'] = array();
			if( MWNamespace::hasSubpages( $nt->getNamespace() ) ) {
				$conds['page_namespace'] []= $ot->getNamespace();
			}
			if( $this->moveTalk && MWNamespace::hasSubpages( $nt->getTalkPage()->getNamespace() ) ) {
				$conds['page_namespace'] []= $ot->getTalkPage()->getNamespace();
			}
		} elseif( $this->moveTalk ) {
			$conds = array(
				'page_namespace' => $ot->getTalkPage()->getNamespace(),
				'page_title' => $ot->getDBkey()
			);
		} else {
			# Skip the query
			$conds = null;
		}

		$extraPages = array();
		if( !is_null( $conds ) ) {
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
		foreach( $extraPages as $oldSubpage ) {
			if( $ot->equals( $oldSubpage ) ) {
				# Already did this one.
				continue;
			}

			$newPageName = preg_replace(
				'#^'.preg_quote( $ot->getDBkey(), '#' ).'#',
				StringUtils::escapeRegexReplacement( $nt->getDBkey() ), # bug 21234
				$oldSubpage->getDBkey()
			);
			if( $oldSubpage->isTalkPage() ) {
				$newNs = $nt->getTalkPage()->getNamespace();
			} else {
				$newNs = $nt->getSubjectPage()->getNamespace();
			}
			# Bug 14385: we need makeTitleSafe because the new page names may
			# be longer than 255 characters.
			$newSubpage = Title::makeTitleSafe( $newNs, $newPageName );
			if( !$newSubpage ) {
				$oldLink = Linker::linkKnown( $oldSubpage );
				$extraOutput []= wfMsgHtml( 'movepage-page-unmoved', $oldLink,
					htmlspecialchars(Title::makeName( $newNs, $newPageName )));
				continue;
			}

			# This was copy-pasted from Renameuser, bleh.
			if ( $newSubpage->exists() && !$oldSubpage->isValidMoveTarget( $newSubpage ) ) {
				$link = Linker::linkKnown( $newSubpage );
				$extraOutput []= wfMsgHtml( 'movepage-page-exists', $link );
			} else {
				$success = $oldSubpage->moveTo( $newSubpage, true, $this->reason, $createRedirect );
				if( $success === true ) {
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
					$extraOutput []= wfMsgHtml( 'movepage-page-moved', $oldLink, $newLink );
					++$count;
					if( $count >= $wgMaximumMovedPages ) {
						$extraOutput []= wfMsgExt( 'movepage-max-pages', array( 'parsemag', 'escape' ), $this->getLang()->formatNum( $wgMaximumMovedPages ) );
						break;
					}
				} else {
					$oldLink = Linker::linkKnown( $oldSubpage );
					$newLink = Linker::link( $newSubpage );
					$extraOutput []= wfMsgHtml( 'movepage-page-unmoved', $oldLink, $newLink );
				}
			}

		}

		if( $extraOutput !== array() ) {
			$out->addHTML( "<ul>\n<li>" . implode( "</li>\n<li>", $extraOutput ) . "</li>\n</ul>" );
		}

		# Deal with watches (we don't watch subpages)
		if( $this->watch && $user->isLoggedIn() ) {
			$user->addWatch( $ot );
			$user->addWatch( $nt );
		} else {
			$user->removeWatch( $ot );
			$user->removeWatch( $nt );
		}

		# Re-clear the file redirect cache, which may have been polluted by
		# parsing in messages above. See CR r56745.
		# @todo FIXME: Needs a more robust solution inside FileRepo.
		if( $ot->getNamespace() == NS_FILE ) {
			RepoGroup::singleton()->getLocalRepo()->invalidateImageRedirect( $ot );
		}
	}

	function showLogFragment( $title ) {
		$out = $this->getOutput();
		$out->addHTML( Xml::element( 'h2', null, LogPage::logName( 'move' ) ) );
		LogEventsList::showLogExtract( $out, 'move', $title );
	}

	function showSubpages( $title ) {
		if( !MWNamespace::hasSubpages( $title->getNamespace() ) )
			return;

		$subpages = $title->getSubpages();
		$count = $subpages instanceof TitleArray ? $subpages->count() : 0;

		$out = $this->getOutput();
		$out->wrapWikiMsg( '== $1 ==', array( 'movesubpage', $count ) );

		# No subpages.
		if ( $count == 0 ) {
			$out->addWikiMsg( 'movenosubpage' );
			return;
		}

		$out->addWikiMsg( 'movesubpagetext', $this->getLang()->formatNum( $count ) );
		$out->addHTML( "<ul>\n" );

		foreach( $subpages as $subpage ) {
			$link = Linker::link( $subpage );
			$out->addHTML( "<li>$link</li>\n" );
		}
		$out->addHTML( "</ul>\n" );
	}
}

