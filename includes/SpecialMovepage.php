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
	if ( 0 == $wgUser->getID() or $wgUser->isBlocked() or ($wgOnlySysopMayMove and $wgUser->isNewbie())) {
		$wgOut->errorpage( "movenologin", "movenologintext" );
		return;
	}
	# We don't move protected pages
	if ( wfReadOnly() ) {
		$wgOut->readOnlyPage();
		return;
	}

	$f = new MovePageForm();

	if ( 'success' == $action ) { $f->showSuccess(); }
	else if ( 'submit' == $action && $wgRequest->wasPosted() ) { $f->doSubmit(); }
	else { $f->showForm( '' ); }
}

/**
 *
 * @package MediaWiki
 * @subpackage SpecialPage
 */
class MovePageForm {
	var $oldTitle, $newTitle; # Text input
		
	function MovePageForm() {
		global $wgRequest;
		$this->oldTitle = $wgRequest->getText( 'wpOldTitle', $wgRequest->getVal( 'target' ) );
		$this->newTitle = $wgRequest->getText( 'wpNewTitle' );
	}
	
	function showForm( $err ) {
		global $wgOut, $wgUser, $wgLang;

		$wgOut->setPagetitle( wfMsg( 'movepage' ) );

		if ( empty( $this->oldTitle ) ) {
			$wgOut->errorpage( 'notargettitle', 'notargettext' );
			return;
		}
		
		$encOldTitle = htmlspecialchars( $this->oldTitle );
		$encNewTitle = htmlspecialchars( $this->newTitle );
		$ot = Title::newFromURL( $this->oldTitle );
		$ott = $ot->getPrefixedText();

		$wgOut->addWikiText( wfMsg( 'movepagetext' ) );
		if ( ! Namespace::isTalk( $ot->getNamespace() ) ) {
			$wgOut->addWikiText( wfMsg( 'movepagetalktext' ) );
		}

		$ma = wfMsg( 'movearticle' );
		$newt = wfMsg( 'newtitle' );
		$mpb = wfMsg( 'movepagebtn' );
		$movetalk = wfMsg( 'movetalk' );

		$titleObj = Title::makeTitle( NS_SPECIAL, 'Movepage' );
		$action = $titleObj->escapeLocalURL( 'action=submit' );

		if ( $err != '' ) {
			$wgOut->setSubtitle( wfMsg( 'formerror' ) );
			$wgOut->addHTML( '<p class="error">'.$err."</p>\n" );
		}
		$wgOut->addHTML( "
<form id=\"movepage\" method=\"post\" action=\"{$action}\">
	<table border='0'>
		<tr>
			<td align='right'>{$ma}:</td>
			<td align='left'><strong>{$ott}</strong></td>
		</tr>
		<tr>
			<td align='right'>{$newt}:</td>
			<td align='left'>
				<input type='text' size='40' name=\"wpNewTitle\" value=\"{$encNewTitle}\" />
				<input type='hidden' name=\"wpOldTitle\" value=\"{$encOldTitle}\" />
			</td>
		</tr>" );

		if ( ! Namespace::isTalk( $ot->getNamespace() ) ) {
			$wgOut->addHTML( "
		<tr>
			<td align='right'>
				<input type='checkbox' name=\"wpMovetalk\" checked='checked' value=\"1\" />
			</td>
			<td>{$movetalk}</td>
		</tr>" );
		}
		$wgOut->addHTML( "
		<tr>
			<td>&nbsp;</td>
			<td align='left'>
				<input type='submit' name=\"wpMove\" value=\"{$mpb}\" />
			</td>
		</tr>
	</table>
</form>\n" );

	}

	function doSubmit() {
		global $wgOut, $wgUser, $wgLang;
		global $wgDeferredUpdateList, $wgMessageCache;
		global  $wgUseSquid, $wgRequest;
		$fname = "MovePageForm::doSubmit";
		
		# Variables beginning with 'o' for old article 'n' for new article

		# Attempt to move the article
		
		$ot = Title::newFromText( $this->oldTitle );
		$nt = Title::newFromText( $this->newTitle );

		# don't allow moving to pages with # in
		if ( !$nt || $nt->getFragment() != '' ) {
			$this->showForm( wfMsg( "badtitletext" ) );
			return;
		}

		$error = $ot->moveTo( $nt );
		if ( $error !== true ) {
			$this->showForm( wfMsg( $error ) );
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
			$error = $ott->moveTo( $ntt );
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
		global $wgOut, $wgUser, $wgRequest;

		$wgOut->setPagetitle( wfMsg( 'movepage' ) );
		$wgOut->setSubtitle( wfMsg( 'pagemovedsub' ) );
		$oldtitle = $wgRequest->getVal('oldtitle');
		$newtitle = $wgRequest->getVal('newtitle');
		$talkmoved = $wgRequest->getVal('talkmoved');

		$text = wfMsg( 'pagemovedtext', $oldtitle, $newtitle );
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
			if ( ! Namespace::isTalk( $ot->getNamespace() ) ) {
				$wgOut->addHTML( "\n<p>" . wfMsg( 'talkpagenotmoved', wfMsg( $talkmoved ) ) . "</p>\n" );
			}
		}
	}
}
?>
