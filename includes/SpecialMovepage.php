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
function wfSpecialMovepage() {
	global $wgUser, $wgOut, $wgRequest, $action, $wgOnlySysopMayMove;

	# check rights. We don't want newbies to move pages to prevents possible attack
	if ( $wgUser->isAnon() or $wgUser->isBlocked() or ($wgOnlySysopMayMove and $wgUser->isNewbie())) {
		$wgOut->errorpage( "movenologin", "movenologintext" );
		return;
	}
	# We don't move protected pages
	if ( wfReadOnly() ) {
		$wgOut->readOnlyPage();
		return;
	}

	$f = new MovePageForm();

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
		
	function MovePageForm() {
		global $wgRequest;
		$this->oldTitle = $wgRequest->getText( 'wpOldTitle', $wgRequest->getVal( 'target' ) );
		$this->newTitle = $wgRequest->getText( 'wpNewTitle' );
		$this->reason = $wgRequest->getText( 'wpReason' );
		$this->moveTalk = $wgRequest->getBool( 'wpMovetalk', true );
		$this->deleteAndMove = $wgRequest->getBool( 'wpDeleteAndMove' );
	}
	
	function showForm( $err ) {
		global $wgOut, $wgUser, $wgLang;

		$wgOut->setPagetitle( wfMsg( 'movepage' ) );

		if ( $this->oldTitle == '' ) {
			$wgOut->errorpage( 'notargettitle', 'notargettext' );
			return;
		}

		$ot = Title::newFromURL( $this->oldTitle );
		$oldTitle = $ot->getPrefixedText();
		
		$encOldTitle = htmlspecialchars( $this->oldTitle );
		if( $this->newTitle == '' ) {
			# Show the current title as a default
			# when the form is first opened.
			$encNewTitle = $oldTitle;
		} else {
			$nt = Title::newFromURL( $this->newTitle );
			if ( $nt ) {
				// Check if it's valid
				if ( !$nt->isValidMoveTarget( $ot ) ) {
					$err = 'articleexists';
				}
				$encNewTitle = htmlspecialchars( $this->newTitle );
			} else {
				$encNewTitle = $oldTitle;
			}
		}
		$encReason = htmlspecialchars( $this->reason );

		if ( $err == 'articleexists' && $wgUser->isAllowed( 'delete' ) ) {
			$wgOut->addWikiText( wfMsg( 'delete_and_move_text', $encNewTitle ) );
			$movepagebtn = wfMsg( 'delete_and_move' );
			$submitVar = 'wpDeleteAndMove';
			$err = '';
		} else {
			$wgOut->addWikiText( wfMsg( 'movepagetext' ) );
			$movepagebtn = wfMsg( 'movepagebtn' );
			$submitVar = 'wpMove';
		}

		if ( !$ot->isTalkPage() ) {
			$wgOut->addWikiText( wfMsg( 'movepagetalktext' ) );
		}

		$movearticle = wfMsg( 'movearticle' );
		$newtitle = wfMsg( 'newtitle' );
		$movetalk = wfMsg( 'movetalk' );
		$movereason = wfMsg( 'movereason' );

		$titleObj = Title::makeTitle( NS_SPECIAL, 'Movepage' );
		$action = $titleObj->escapeLocalURL( 'action=submit' );
		$token = htmlspecialchars( $wgUser->editToken() );

		if ( $err != '' ) {
			$wgOut->setSubtitle( wfMsg( 'formerror' ) );
			$wgOut->addHTML( '<p class="error">' . wfMsg($err) . "</p>\n" );
		}

		if ( $this->moveTalk ) {
			$moveTalkChecked = " checked='checked'";
		} else {
			$moveTalkChecked = '';
		}
		
		$wgOut->addHTML( "
<form id=\"movepage\" method=\"post\" action=\"{$action}\">
	<table border='0'>
		<tr>
			<td align='right'>{$movearticle}:</td>
			<td align='left'><strong>{$oldTitle}</strong></td>
		</tr>
		<tr>
			<td align='right'>{$newtitle}:</td>
			<td align='left'>
				<input type='text' size='40' name=\"wpNewTitle\" value=\"{$encNewTitle}\" />
				<input type='hidden' name=\"wpOldTitle\" value=\"{$encOldTitle}\" />
			</td>
		</tr>
		<tr>
			<td align='right'>{$movereason}:</td>
			<td align='left'>
				<input type='text' size=40 name=\"wpReason\" value=\"{$encReason}\" />
			</td>
		</tr>" );

		if ( ! $ot->isTalkPage() ) {
			$wgOut->addHTML( "
		<tr>
			<td align='right'>
				<input type='checkbox' name=\"wpMovetalk\"{$moveTalkChecked} value=\"1\" />
			</td>
			<td>{$movetalk}</td>
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
		global $wgOut, $wgUser, $wgLang;
		global $wgDeferredUpdateList, $wgMessageCache;
		global  $wgUseSquid, $wgRequest;
		$fname = "MovePageForm::doSubmit";
		
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

		# Update counters if the article got moved into or out of NS_MAIN namespace
		$ons = $ot->getNamespace();
		$nns = $nt->getNamespace();
		
		# moved out of article namespace?
		if ( $ons == NS_MAIN and $nns != NS_MAIN ) {
			$u = new SiteStatsUpdate( 0, 1, -1); # not viewed, edited, removing
		}
		# moved into article namespace?
		elseif ( $ons != NS_MAIN and $nns == NS_MAIN ) {
			$u = new SiteStatsUpdate( 0, 1, +1 ); # not viewed, edited, adding
		} else {
			$u = false;
		}
		if ( $u !== false ) {
			# save it for later update
			array_push( $wgDeferredUpdateList, $u );
			unset($u);
		}
		
		# Move talk page if
		# (1) the checkbox says to,
		# (2) the namespaces are not themselves talk namespaces, and of course
		# (3) it exists.
		if ( ( $wgRequest->getVal('wpMovetalk') == 1 ) &&
		     ( ! Namespace::isTalk( $ons ) ) &&
		     ( ! Namespace::isTalk( $nns ) ) ) {
			
			# get old talk page namespace
			$ons = Namespace::getTalk( $ons );
			# get new talk page namespace
			$nns = Namespace::getTalk( $nns );
			
			# make talk page title objects
			$ott = Title::makeTitle( $ons, $ot->getDBkey() );
			$ntt = Title::makeTitle( $nns, $nt->getDBkey() );

			# Attempt the move
			$error = $ott->moveTo( $ntt, true, $this->reason );
			if ( $error === true ) {
				$talkmoved = 1;
			} else {
				$talkmoved = $error;
			}
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
			$wgOut->addHTML( "\n<p>" . wfMsg( 'talkpagemoved' ) . "</p>\n" );
		} elseif( 'articleexists' == $talkmoved ) {
			$wgOut->addHTML( "\n<p><strong>" . wfMsg( 'talkexists' ) . "</strong></p>\n" );
		} else {
			$ot = Title::newFromURL( $oldtitle );
			if ( ! $ot->isTalkPage() ) {
				$wgOut->addHTML( "\n<p>" . wfMsg( 'talkpagenotmoved', wfMsg( $talkmoved ) ) . "</p>\n" );
			}
		}
	}
}
?>
