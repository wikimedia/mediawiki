<?php
/**
 *
 * @package MediaWiki
 * @subpackage SpecialPage
 */

/**
 * @todo document
 */
function wfSpecialIpblocklist() {
	global $wgUser, $wgOut, $wgRequest;
	
	$ip = $wgRequest->getVal( 'wpUnblockAddress', $wgRequest->getVal( 'ip' ) );
	$reason = $wgRequest->getText( 'wpUnblockReason' );
	$action = $wgRequest->getText( 'action' );
	
	$ipu = new IPUnblockForm( $ip, $reason );

	if ( "success" == $action ) {
		$msg = wfMsg( "ipusuccess", htmlspecialchars( $ip ) );
		$ipu->showList( $msg );
	} else if ( "submit" == $action && $wgRequest->wasPosted() &&
		$wgUser->matchEditToken( $wgRequest->getVal( 'wpEditToken' ) ) ) {
		if ( ! $wgUser->isAllowed('block') ) {
			$wgOut->sysopRequired();
			return;
		}
		$ipu->doSubmit();
	} else if ( "unblock" == $action ) {
		$ipu->showForm( "" );
	} else {
		$ipu->showList( "" );
	}
}

/**
 * 
 * @package MediaWiki
 * @subpackage SpecialPage
 */
class IPUnblockForm {
	var $ip, $reason;
	
	function IPUnblockForm( $ip, $reason ) {
		$this->ip = $ip;
		$this->reason = $reason;
	}
	
	function showForm( $err )
	{
		global $wgOut, $wgUser, $wgLang, $wgSysopUserBans;

		$wgOut->setPagetitle( wfMsg( "unblockip" ) );
		$wgOut->addWikiText( wfMsg( "unblockiptext" ) );

		$ipa = wfMsgHtml( $wgSysopUserBans ? 'ipadressorusername' : 'ipaddress' );
		$ipr = wfMsgHtml( 'ipbreason' );
		$ipus = wfMsgHtml( 'ipusubmit' );
		$titleObj = Title::makeTitle( NS_SPECIAL, "Ipblocklist" );
		$action = $titleObj->escapeLocalURL( "action=submit" );

		if ( "" != $err ) {
			$wgOut->setSubtitle( wfMsg( "formerror" ) );
			$wgOut->addWikitext( "<span class='error'>{$err}</span>\n" );
		}
		$token = htmlspecialchars( $wgUser->editToken() );
		
		$wgOut->addHTML( "
<form id=\"unblockip\" method=\"post\" action=\"{$action}\">
	<table border='0'>
		<tr>
			<td align='right'>{$ipa}:</td>
			<td align='left'>
				<input tabindex='1' type='text' size='20' name=\"wpUnblockAddress\" value=\"" . htmlspecialchars( $this->ip ) . "\" />
			</td>
		</tr>
		<tr>
			<td align='right'>{$ipr}:</td>
			<td align='left'>
				<input tabindex='1' type='text' size='40' name=\"wpUnblockReason\" value=\"" . htmlspecialchars( $this->reason ) . "\" />
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td align='left'>
				<input tabindex='2' type='submit' name=\"wpBlock\" value=\"{$ipus}\" />
			</td>
		</tr>
	</table>
	<input type='hidden' name='wpEditToken' value=\"{$token}\" />
</form>\n" );

	}
	
	function doSubmit() {
		global $wgOut, $wgUser, $wgLang;

		$block = new Block();
		$this->ip = trim( $this->ip );

		if ( $this->ip{0} == "#" ) {
			$block->mId = substr( $this->ip, 1 );
		} else {
			$block->mAddress = $this->ip;
		}

		# Delete block (if it exists)
		# We should probably check for errors rather than just declaring success
		$block->delete();

		# Make log entry
		$log = new LogPage( 'block' );
		$log->addEntry( 'unblock', Title::makeTitle( NS_USER, $this->ip ), $this->reason );

		# Report to the user
		$titleObj = Title::makeTitle( NS_SPECIAL, "Ipblocklist" );
		$success = $titleObj->getFullURL( "action=success&ip=" . urlencode( $this->ip ) );
		$wgOut->redirect( $success );
	}

	function showList( $msg ) {
		global $wgOut;
		
		$wgOut->setPagetitle( wfMsg( "ipblocklist" ) );
		if ( "" != $msg ) {
			$wgOut->setSubtitle( $msg );
		}
		$wgOut->addHTML( "<ul>" );
		// FIXME hack to solve #bug 1487
		if(!Block::enumBlocks( "wfAddRow", 0 ))
			$wgOut->addHTML( '<li>'.wfMsg( 'ipblocklistempty' ).'</li>' );
		$wgOut->addHTML( "</ul>\n" );
	}
}

/**
 * Callback function to output a block
 */
function wfAddRow( $block, $tag ) {
	global $wgOut, $wgUser, $wgLang, $wgContLang;

	$sk = $wgUser->getSkin();

	# Hide addresses blocked by User::spreadBlocks, for privacy
	$addr = $block->mAuto ? "#{$block->mId}" : $block->mAddress;

	$name = User::whoIs( $block->mBy );
	$ulink = $sk->makeKnownLinkObj( Title::makeTitle( NS_USER, $name ), $name );
	$formattedTime = $wgLang->timeanddate( $block->mTimestamp, true );
	
	if ( $block->mExpiry === "" ) {
		$formattedExpiry = wfMsgHtml('infiniteblock');
	} else {
		$formattedExpiry = wfMsgHtml('expiringblock', $wgLang->timeanddate( $block->mExpiry, true ) );
	}
	
	$line = wfMsg( "blocklistline", $formattedTime, $ulink, $addr, $formattedExpiry );
	
	$wgOut->addHTML( "<li>{$line}" );

	if ( !$block->mAuto ) {
		$titleObj = Title::makeTitle( NS_SPECIAL, "Contributions" );
		$wgOut->addHTML( ' (' . $sk->makeKnownLinkObj($titleObj, wfMsgHtml( 'contribslink' ), "target={$block->mAddress}") . ')' );
	}

	if ( $wgUser->isAllowed('block') ) {
		$titleObj = Title::makeTitle( NS_SPECIAL, "Ipblocklist" );
		$wgOut->addHTML( ' (' . $sk->makeKnownLinkObj($titleObj, wfMsgHtml( 'unblocklink' ), 'action=unblock&ip=' . urlencode( $addr ) ) . ')' );
	}
	$wgOut->addHTML( $sk->commentBlock( $block->mReason ) );
	$wgOut->addHTML( "</li>\n" );
}


?>
