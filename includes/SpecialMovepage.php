<?php
/**
 * @file
 * @ingroup SpecialPage
 */

/**
 * Constructor
 */
function wfSpecialMovepage( $par = null ) {
	global $wgUser, $wgOut, $wgRequest, $action;

	# Check rights
	if ( !$wgUser->isAllowed( 'move' ) ) {
		$wgOut->showPermissionsErrorPage( array( $wgUser->isAnon() ? 'movenologintext' : 'movenotallowed' ) );
		return;
	}

	# Don't allow blocked users to move pages
	if ( $wgUser->isBlocked() ) {
		$wgOut->blockedPage();
		return;
	}

	# Check for database lock
	if ( wfReadOnly() ) {
		$wgOut->readOnlyPage();
		return;
	}

	$f = new MovePageForm( $par );

	if ( 'submit' == $action && $wgRequest->wasPosted()
		&& $wgUser->matchEditToken( $wgRequest->getVal( 'wpEditToken' ) ) ) {
		$f->doSubmit();
	} else {
		$f->showForm( '' );
	}
}

/**
 * HTML form for Special:Movepage
 * @ingroup SpecialPage
 */
class MovePageForm {
	var $oldTitle, $newTitle, $reason; # Text input
	var $moveTalk, $deleteAndMove, $moveSubpages;

	private $watch = false;

	function MovePageForm( $par ) {
		global $wgRequest;
		$target = isset($par) ? $par : $wgRequest->getVal( 'target' );
		$this->oldTitle = $wgRequest->getText( 'wpOldTitle', $target );
		$this->newTitle = $wgRequest->getText( 'wpNewTitle' );
		$this->reason = $wgRequest->getText( 'wpReason' );
		if ( $wgRequest->wasPosted() ) {
			$this->moveTalk = $wgRequest->getBool( 'wpMovetalk', false );
		} else {
			$this->moveTalk = $wgRequest->getBool( 'wpMovetalk', true );
		}
		$this->moveSubpages = $wgRequest->getBool( 'wpMovesubpages', false );
		$this->deleteAndMove = $wgRequest->getBool( 'wpDeleteAndMove' ) && $wgRequest->getBool( 'wpConfirm' );
		$this->watch = $wgRequest->getCheck( 'wpWatch' );
	}

	function showForm( $err, $hookErr = '' ) {
		global $wgOut, $wgUser;

		$ot = Title::newFromURL( $this->oldTitle );
		if( is_null( $ot ) ) {
			$wgOut->showErrorPage( 'notargettitle', 'notargettext' );
			return;
		}
		if( !$ot->exists() ) {
			$wgOut->showErrorPage( 'nopagetitle', 'nopagetext' );
			return;
		}

		$sk = $wgUser->getSkin();

		$oldTitleLink = $sk->makeLinkObj( $ot );
		$oldTitle = $ot->getPrefixedText();

		$wgOut->setPagetitle( wfMsg( 'move-page', $oldTitle ) );
		$wgOut->setSubtitle( wfMsg( 'move-page-backlink', $oldTitleLink ) );

		if( $this->newTitle == '' ) {
			# Show the current title as a default
			# when the form is first opened.
			$newTitle = $oldTitle;
		} else {
			if( $err == '' ) {
				$nt = Title::newFromURL( $this->newTitle );
				if( $nt ) {
					# If a title was supplied, probably from the move log revert
					# link, check for validity. We can then show some diagnostic
					# information and save a click.
					$newerr = $ot->isValidMoveOperation( $nt );
					if( is_string( $newerr ) ) {
						$err = $newerr;
					}
				}
			}
			$newTitle = $this->newTitle;
		}

		if ( $err == 'articleexists' && $wgUser->isAllowed( 'delete' ) ) {
			$wgOut->addWikiMsg( 'delete_and_move_text', $newTitle );
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
			$wgOut->addWikiMsg( 'movepagetext' );
			$movepagebtn = wfMsg( 'movepagebtn' );
			$submitVar = 'wpMove';
			$confirm = false;
		}

		$oldTalk = $ot->getTalkPage();
		$considerTalk = ( !$ot->isTalkPage() && $oldTalk->exists() );

		if ( $considerTalk ) {
			$wgOut->addWikiMsg( 'movepagetalktext' );
		}

		$titleObj = SpecialPage::getTitleFor( 'Movepage' );
		$token = htmlspecialchars( $wgUser->editToken() );

		if ( $err != '' ) {
			$wgOut->setSubtitle( wfMsg( 'formerror' ) );
			$errMsg = "";
			if( $err == 'hookaborted' ) {
				$errMsg = "<p><strong class=\"error\">$hookErr</strong></p>\n";
			} else if (is_array($err)) {
				$errMsg = '<p><strong class="error">' . call_user_func_array( 'wfMsgWikiHtml', $err ) . "</strong></p>\n";
			} else {
				$errMsg = '<p><strong class="error">' . wfMsgWikiHtml( $err ) . "</strong></p>\n";
			}
			$wgOut->addHTML( $errMsg );
		}

		$wgOut->addHTML(
			 Xml::openElement( 'form', array( 'method' => 'post', 'action' => $titleObj->getLocalURL( 'action=submit' ), 'id' => 'movepage' ) ) .
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
					Xml::input( 'wpNewTitle', 40, $newTitle, array( 'type' => 'text', 'id' => 'wpNewTitle' ) ) .
					Xml::hidden( 'wpOldTitle', $oldTitle ) .
				"</td>
			</tr>
			<tr>
				<td class='mw-label'>" .
					Xml::label( wfMsg( 'movereason' ), 'wpReason' ) .
				"</td>
				<td class='mw-input'>" .
					Xml::tags( 'textarea', array( 'name' => 'wpReason', 'id' => 'wpReason', 'cols' => 60, 'rows' => 2 ), htmlspecialchars( $this->reason ) ) .
				"</td>
			</tr>"
		);

		if( $considerTalk ) {
			$wgOut->addHTML( "
				<tr>
					<td></td>
					<td class='mw-input'>" .
						Xml::checkLabel( wfMsg( 'movetalk' ), 'wpMovetalk', 'wpMovetalk', $this->moveTalk ) .
					"</td>
				</tr>"
			);
		}

		if( $ot->hasSubpages() || $ot->getTalkPage()->hasSubpages() ) {
			$wgOut->addHTML( "
				<tr>
					<td></td>
					<td class=\"mw-input\">" .
				Xml::checkLabel( wfMsgHtml(
						$ot->hasSubpages()
						? 'move-subpages'
						: 'move-talk-subpages'
					),
					'wpMovesubpages', 'wpMovesubpages',
					# Don't check the box if we only have talk subpages to
					# move and we aren't moving the talk page.
					$this->moveSubpages && ($ot->hasSubpages() || $this->moveTalk)
				) .
					"</td>
				</tr>"
			);
		}

		$watchChecked = $this->watch || $wgUser->getBoolOption( 'watchmoves' ) || $ot->userIsWatching();
		$wgOut->addHTML( "
			<tr>
				<td></td>
				<td class='mw-input'>" .
					Xml::checkLabel( wfMsg( 'move-watch' ), 'wpWatch', 'watch', $watchChecked ) .
				"</td>
			</tr>
				{$confirm}
			<tr>
				<td>&nbsp;</td>
				<td class='mw-submit'>" .
					Xml::submitButton( $movepagebtn, array( 'name' => $submitVar ) ) .
				"</td>
			</tr>" .
			Xml::closeElement( 'table' ) .
			Xml::hidden( 'wpEditToken', $token ) .
			Xml::closeElement( 'fieldset' ) .
			Xml::closeElement( 'form' ) .
			"\n"
		);

		$this->showLogFragment( $ot, $wgOut );

	}

	function doSubmit() {
		global $wgOut, $wgUser, $wgRequest, $wgMaximumMovedPages, $wgLang;

		if ( $wgUser->pingLimiter( 'move' ) ) {
			$wgOut->rateLimited();
			return;
		}

		# Variables beginning with 'o' for old article 'n' for new article

		$ot = Title::newFromText( $this->oldTitle );
		$nt = Title::newFromText( $this->newTitle );

		# Delete to make way if requested
		if ( $wgUser->isAllowed( 'delete' ) && $this->deleteAndMove ) {
			$article = new Article( $nt );

			# Disallow deletions of big articles
			$bigHistory = $article->isBigDeletion();
			if( $bigHistory && !$nt->userCan( 'bigdelete' ) ) {
				global $wgLang, $wgDeleteRevisionsLimit;
				$this->showForm( array('delete-toobig', $wgLang->formatNum( $wgDeleteRevisionsLimit ) ) );
				return;
			}

			// This may output an error message and exit
			$article->doDelete( wfMsgForContent( 'delete_and_move_reason' ) );
		}

		# don't allow moving to pages with # in
		if ( !$nt || $nt->getFragment() != '' ) {
			$this->showForm( 'badtitletext' );
			return;
		}

		$error = $ot->moveTo( $nt, true, $this->reason );
		if ( $error !== true ) {
			# FIXME: moveTo() can return a string
			if(is_array($error))
				# FIXME: showForm() should handle multiple errors
				call_user_func_array(array($this, 'showForm'), $error[0]);
			else
				$this->showForm($error);
			return;
		}

		wfRunHooks( 'SpecialMovepageAfterMove', array( &$this , &$ot , &$nt ) )	;

		$wgOut->setPagetitle( wfMsg( 'pagemovedsub' ) );

		$oldUrl = $ot->getFullUrl( 'redirect=no' );
		$newUrl = $nt->getFullUrl();
		$oldText = $ot->getPrefixedText();
		$newText = $nt->getPrefixedText();
		$oldLink = "<span class='plainlinks'>[$oldUrl $oldText]</span>";
		$newLink = "<span class='plainlinks'>[$newUrl $newText]</span>";

		$wgOut->addWikiMsg( 'movepage-moved', $oldLink, $newLink, $oldText, $newText );

		# Now we move extra pages we've been asked to move: subpages and talk
		# pages.  First, if the old page or the new page is a talk page, we
		# can't move any talk pages: cancel that.
		if( $ot->isTalkPage() || $nt->isTalkPage() ) {
			$this->moveTalk = false;
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
		# is bad.  FIXME: A specific error message should be given in this
		# case.
		$dbr = wfGetDB( DB_SLAVE );
		if( $this->moveSubpages && (
			MWNamespace::hasSubpages( $nt->getNamespace() ) || (
				$this->moveTalk &&
				MWNamespace::hasSubpages( $nt->getTalkPage()->getNamespace() )
			)
		) ) {
			$conds = array(
				'page_title LIKE '.$dbr->addQuotes( $dbr->escapeLike( $ot->getDBkey() ) . '/%' )
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
				'page_title' => $ot->getDBKey()
			);
		} else {
			# Skip the query
			$conds = null;
		}

		$extrapages = array();
		if( !is_null( $conds ) ) {
			$extrapages = $dbr->select( 'page',
				array( 'page_id', 'page_namespace', 'page_title' ),
				$conds,
				__METHOD__
			);
		}

		$extraOutput = array();
		$skin = $wgUser->getSkin();
		$count = 1;
		foreach( $extrapages as $row ) {
			if( $row->page_id == $ot->getArticleId() ) {
				# Already did this one.
				continue;
			}

			$oldPage = Title::newFromRow( $row );
			$newPageName = preg_replace(
				'#^'.preg_quote( $ot->getDBKey(), '#' ).'#',
				$nt->getDBKey(),
				$oldPage->getDBKey()
			);
			# The following line is an atrocious hack.  Kill it with fire.
			$newNs = $nt->getNamespace() + ($oldPage->getNamespace() & 1);
			$newPage = Title::makeTitle( $newNs, $newPageName );

			# This was copy-pasted from Renameuser, bleh.
			if ( $newPage->exists() && !$oldPage->isValidMoveTarget( $newPage ) ) {
				$link = $skin->makeKnownLinkObj( $newPage );
				$extraOutput []= wfMsgHtml( 'movepage-page-exists', $link );
			} else {
				$success = $oldPage->moveTo( $newPage, true, $this->reason );
				if( $success === true ) {
					$oldLink = $skin->makeKnownLinkObj( $oldPage, '', 'redirect=no' );
					$newLink = $skin->makeKnownLinkObj( $newPage );
					$extraOutput []= wfMsgHtml( 'movepage-page-moved', $oldLink, $newLink );
				} else {
					$oldLink = $skin->makeKnownLinkObj( $oldPage );
					$newLink = $skin->makeLinkObj( $newPage );
					$extraOutput []= wfMsgHtml( 'movepage-page-unmoved', $oldLink, $newLink );
				}
			}

			++$count;
			if( $count >= $wgMaximumMovedPages ) {
				$extraOutput []= wfMsgHtml( 'movepage-max-pages', $wgLang->formatNum( $wgMaximumMovedPages ) );
				break;
			}
		}

		if( $extraOutput !== array() ) {
			$wgOut->addHTML( "<ul>\n<li>" . implode( "</li>\n<li>", $extraOutput ) . "</li>\n</ul>" );
		}

		# Deal with watches (we don't watch subpages)
		if( $this->watch ) {
			$wgUser->addWatch( $ot );
			$wgUser->addWatch( $nt );
		} else {
			$wgUser->removeWatch( $ot );
			$wgUser->removeWatch( $nt );
		}
	}

	function showLogFragment( $title, &$out ) {
		$out->addHTML( Xml::element( 'h2', NULL, LogPage::logName( 'move' ) ) );
		LogEventsList::showLogExtract( $out, 'move', $title->getPrefixedText() );
	}

}
