<?php
/**
 *
 * @addtogroup SpecialPage
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

	if ( 'success' == $action ) {
		$f->showSuccess();
	} else if ( 'submit' == $action && $wgRequest->wasPosted()
		&& $wgUser->matchEditToken( $wgRequest->getVal( 'wpEditToken' ) ) ) {
		$f->doSubmit();
	} else {
		$f->showForm( '' );
	}
}

/**
 * HTML form for Special:Movepage
 * @addtogroup SpecialPage
 */
class MovePageForm {
	var $oldTitle, $newTitle, $reason; # Text input
	var $moveTalk, $deleteAndMove;

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

		$moveTalkChecked = $this->moveTalk ? ' checked="checked"' : '';

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

		if ( $considerTalk ) {
			$wgOut->addHTML( "
				<tr>
					<td></td>
					<td class='mw-input'>" .
						Xml::checkLabel( wfMsg( 'movetalk' ), 'wpMovetalk', 'wpMovetalk', $moveTalkChecked ) .
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
		global $wgOut, $wgUser, $wgRequest;

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

		$hookErr = null;
		if( !wfRunHooks( 'AbortMove', array( $ot, $nt, $wgUser, &$hookErr ) ) ) {
			$this->showForm( 'hookaborted', $hookErr );
			return;
		}

		$error = $ot->moveTo( $nt, true, $this->reason );
		if ( $error !== true ) {
			$this->showForm( $error );
			return;
		}

		wfRunHooks( 'SpecialMovepageAfterMove', array( &$this , &$ot , &$nt ) )	;

		# Move the talk page if relevant, if it exists, and if we've been told to
		$ott = $ot->getTalkPage();
		if( $ott->exists() ) {
			if( $this->moveTalk && !$ot->isTalkPage() && !$nt->isTalkPage() ) {
				$ntt = $nt->getTalkPage();

				# Attempt the move
				$error = $ott->moveTo( $ntt, true, $this->reason );
				if ( $error === true ) {
					$talkmoved = 1;
					wfRunHooks( 'SpecialMovepageAfterMove', array( &$this , &$ott , &$ntt ) );
				} else {
					$talkmoved = $error;
				}
			} else {
				# Stay silent on the subject of talk.
				$talkmoved = '';
			}
		} else {
			$talkmoved = 'notalkpage';
		}

		# Deal with watches
		if( $this->watch ) {
			$wgUser->addWatch( $ot );
			$wgUser->addWatch( $nt );
		} else {
			$wgUser->removeWatch( $ot );
			$wgUser->removeWatch( $nt );
		}

		# Give back result to user.
		$titleObj = SpecialPage::getTitleFor( 'Movepage' );
		$success = $titleObj->getFullURL(
		  'action=success&oldtitle=' . wfUrlencode( $ot->getPrefixedText() ) .
		  '&newtitle=' . wfUrlencode( $nt->getPrefixedText() ) .
		  '&talkmoved='.$talkmoved );

		$wgOut->redirect( $success );
	}

	function showSuccess() {
		global $wgOut, $wgRequest, $wgUser;

		$old = Title::newFromText( $wgRequest->getVal( 'oldtitle' ) );
		$new = Title::newFromText( $wgRequest->getVal( 'newtitle' ) );

		if( is_null( $old ) || is_null( $new ) ) {
			throw new ErrorPageError( 'badtitle', 'badtitletext' );
		}

		$wgOut->setPagetitle( wfMsg( 'pagemovedsub' ) );

		$talkmoved = $wgRequest->getVal( 'talkmoved' );
		$oldUrl = $old->getFullUrl( 'redirect=no' );
		$newUrl = $new->getFullUrl();
		$oldText = $old->getPrefixedText();
		$newText = $new->getPrefixedText();
		$oldLink = "<span class='plainlinks'>[$oldUrl $oldText]</span>";
		$newLink = "<span class='plainlinks'>[$newUrl $newText]</span>";

		$s = wfMsgNoTrans( 'movepage-moved', $oldLink, $newLink, $oldText, $newText );

		if ( $talkmoved == 1 ) {
			$s .= "\n\n" . wfMsgNoTrans( 'talkpagemoved' );
		} elseif( 'articleexists' == $talkmoved ) {
			$s .= "\n\n" . wfMsgNoTrans( 'talkexists' );
		} else {
			if( !$old->isTalkPage() && $talkmoved != 'notalkpage' ) {
				$s .= "\n\n" . wfMsgNoTrans( 'talkpagenotmoved', wfMsgNoTrans( $talkmoved ) );
			}
		}
		$wgOut->addWikiText( $s );
	}

	function showLogFragment( $title, &$out ) {
		$out->addHTML( Xml::element( 'h2', NULL, LogPage::logName( 'move' ) ) );
		LogEventsList::showLogExtract( $out, 'move', $title->getPrefixedText() );
	}

}
