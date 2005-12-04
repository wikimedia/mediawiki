<?php
/**
 *
 * @package MediaWiki
 * @subpackage SpecialPage
 */

/**
 *
 */
require_once( "LinksUpdate.php" );

/**
 * Constructor
 */
function wfSpecialMovepage( $par = null ) {
	global $wgUser, $wgOut, $wgRequest, $action, $wgOnlySysopMayMove;

	# check rights. We don't want newbies to move pages to prevents possible attack
	if ( !$wgUser->isAllowed( 'move' ) or $wgUser->isBlocked() or ($wgOnlySysopMayMove and $wgUser->isNewbie())) {
		$wgOut->errorpage( "movenologin", "movenologintext" );
		return;
	}
	# We don't move protected pages
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
 *
 * @package MediaWiki
 * @subpackage SpecialPage
 */
class MovePageForm {
	var $oldTitle, $newTitle, $reason; # Text input
	var $moveTalk, $deleteAndMove;
		
	function MovePageForm( $par ) {
		global $wgRequest;
		$target = isset($par) ? $par : $wgRequest->getVal( 'target' );
		$this->oldTitle = $wgRequest->getText( 'wpOldTitle', $target );
		$this->newTitle = $wgRequest->getText( 'wpNewTitle' );
		$this->reason = $wgRequest->getText( 'wpReason' );
		$this->moveTalk = $wgRequest->getBool( 'wpMovetalk', true );
		$this->deleteAndMove = $wgRequest->getBool( 'wpDeleteAndMove' );
	}
	
	function showForm( $err ) {
		global $wgOut, $wgUser;

		$wgOut->setPagetitle( wfMsg( 'movepage' ) );

		$ot = Title::newFromURL( $this->oldTitle );
		if( is_null( $ot ) ) {
			$wgOut->errorpage( 'notargettitle', 'notargettext' );
			return;
		}
		$oldTitle = $ot->getPrefixedText();
		
		$encOldTitle = htmlspecialchars( $oldTitle );
		if( $this->newTitle == '' ) {
			# Show the current title as a default
			# when the form is first opened.
			$encNewTitle = $encOldTitle;
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
			$encNewTitle = htmlspecialchars( $this->newTitle );
		}
		$encReason = htmlspecialchars( $this->reason );

		if ( $err == 'articleexists' && $wgUser->isAllowed( 'delete' ) ) {
			$wgOut->addWikiText( wfMsg( 'delete_and_move_text', $encNewTitle ) );
			$movepagebtn = wfMsgHtml( 'delete_and_move' );
			$submitVar = 'wpDeleteAndMove';
			$err = '';
		} else {
			$wgOut->addWikiText( wfMsg( 'movepagetext' ) );
			$movepagebtn = wfMsgHtml( 'movepagebtn' );
			$submitVar = 'wpMove';
		}

		if ( !$ot->isTalkPage() ) {
			$wgOut->addWikiText( wfMsg( 'movepagetalktext' ) );
		}

		$movearticle = wfMsgHtml( 'movearticle' );
		$newtitle = wfMsgHtml( 'newtitle' );
		$movetalk = wfMsgHtml( 'movetalk' );
		$movereason = wfMsgHtml( 'movereason' );

		$titleObj = Title::makeTitle( NS_SPECIAL, 'Movepage' );
		$action = $titleObj->escapeLocalURL( 'action=submit' );
		$token = htmlspecialchars( $wgUser->editToken() );

		if ( $err != '' ) {
			$wgOut->setSubtitle( wfMsg( 'formerror' ) );
			$wgOut->addWikiText( '<p class="error">' . wfMsg($err) . "</p>\n" );
		}

		$moveTalkChecked = $this->moveTalk ? ' checked="checked"' : '';
		
		$wgOut->addHTML( "
<form id=\"movepage\" method=\"post\" action=\"{$action}\">
	<table border='0'>
		<tr>
			<td align='right'>{$movearticle}:</td>
			<td align='left'><strong>{$oldTitle}</strong></td>
		</tr>
		<tr>
			<td align='right'><label for='wpNewTitle'>{$newtitle}:</label></td>
			<td align='left'>
				<input type='text' size='40' name='wpNewTitle' id='wpNewTitle' value=\"{$encNewTitle}\" />
				<input type='hidden' name=\"wpOldTitle\" value=\"{$encOldTitle}\" />
			</td>
		</tr>
		<tr>
			<td align='right' valign='top'><br /><label for='wpReason'>{$movereason}:</label></td>
			<td align='left' valign='top'><br />
				<textarea cols='60' rows='2' name='wpReason' id='wpReason'>{$encReason}</textarea>
			</td>
		</tr>" );

		if ( ! $ot->isTalkPage() ) {
			$wgOut->addHTML( "
		<tr>
			<td align='right'>
				<input type='checkbox' id=\"wpMovetalk\" name=\"wpMovetalk\"{$moveTalkChecked} value=\"1\" />
			</td>
			<td><label for=\"wpMovetalk\">{$movetalk}</label></td>
		</tr>" );
		}
		$wgOut->addHTML( "
		<tr>
			<td>&nbsp;</td>
			<td align='left'>
				<input type='submit' name=\"{$submitVar}\" value=\"{$movepagebtn}\" />
			</td>
		</tr>
	</table>
	<input type='hidden' name='wpEditToken' value=\"{$token}\" />
</form>\n" );

	}

	function doSubmit() {
		global $wgOut, $wgUser, $wgRequest;
		$fname = "MovePageForm::doSubmit";
		
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
			$this->showForm( $error );
			return;
		}
		
		# Move talk page if
		# (1) the checkbox says to,
		# (2) the namespaces are not themselves talk namespaces, and of course
		# (3) it exists.
		if ( ( $wgRequest->getVal('wpMovetalk') == 1 ) &&
		     !$ot->isTalkPage() &&
		     !$nt->isTalkPage() ) {
			
			$ott = $ot->getTalkPage();
			$ntt = $nt->getTalkPage();

			# Attempt the move
			$error = $ott->moveTo( $ntt, true, $this->reason );
			if ( $error === true ) {
				$talkmoved = 1;
			} else {
				$talkmoved = $error;
			}
		} else {
			# Stay silent on the subject of talk.
			$talkmoved = '';
		}
		
		# Give back result to user.
		$titleObj = Title::makeTitle( NS_SPECIAL, 'Movepage' );
		$success = $titleObj->getFullURL( 
		  'action=success&oldtitle=' . wfUrlencode( $ot->getPrefixedText() ) .
		  '&newtitle=' . wfUrlencode( $nt->getPrefixedText() ) .
		  '&talkmoved='.$talkmoved );

		$wgOut->redirect( $success );
	}

	function showSuccess() {
		global $wgOut, $wgRequest, $wgRawHtml;

		$wgOut->setPagetitle( wfMsg( 'movepage' ) );
		$wgOut->setSubtitle( wfMsg( 'pagemovedsub' ) );
		$oldtitle = $wgRequest->getVal('oldtitle');
		$newtitle = $wgRequest->getVal('newtitle');
		$talkmoved = $wgRequest->getVal('talkmoved');

		$text = wfMsg( 'pagemovedtext', $oldtitle, $newtitle );
		
		# Temporarily disable raw html wikitext option out of XSS paranoia
		$marchingantofdoom = $wgRawHtml;
		$wgRawHtml = false;
		$wgOut->addWikiText( $text );
		$wgRawHtml = $marchingantofdoom;

		if ( $talkmoved == 1 ) {
			$wgOut->addWikiText( wfMsg( 'talkpagemoved' ) );
		} elseif( 'articleexists' == $talkmoved ) {
			$wgOut->addWikiText( wfMsg( 'talkexists' ) );
		} else {
			$ot = Title::newFromURL( $oldtitle );
			if ( ! $ot->isTalkPage() ) {
				$wgOut->addWikiText( wfMsg( 'talkpagenotmoved', wfMsg( $talkmoved ) ) );
			}
		}
	}
}
?>
