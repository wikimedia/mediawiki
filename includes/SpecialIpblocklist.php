<?php

function wfSpecialIpblocklist()
{
	global $wgUser, $wgOut, $wgRequest;
	
	$ip = $wgRequest->getVal( 'wpUnblockAddress', $wgRequest->getVal( 'ip' ) );
	$reason = $wgRequest->getText( 'wpUnblockReason' );
	$action = $wgRequest->getText( 'action' );
	
	$ipu = new IPUnblockForm( $ip, $reason );

	if ( "success" == $action ) {
		$msg = wfMsg( "ipusuccess", $ip );
		$ipu->showList( $msg );
	} else if ( "submit" == $action && $wgRequest->wasPosted() ) {
		if ( ! $wgUser->isSysop() ) {
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

class IPUnblockForm {
	var $ip, $reason;
	
	function IPUnblockForm( $ip, $reason ) {
		$this->ip = $ip;
		$this->reason = $reason;
	}
	
	function showForm( $err )
	{
		global $wgOut, $wgUser, $wgLang;

		$wgOut->setPagetitle( wfMsg( "unblockip" ) );
		$wgOut->addWikiText( wfMsg( "unblockiptext" ) );

		$ipa = wfMsg( "ipaddress" );
		$ipr = wfMsg( "ipbreason" );
		$ipus = htmlspecialchars( wfMsg( "ipusubmit" ) );
		$titleObj = Title::makeTitle( NS_SPECIAL, "Ipblocklist" );
		$action = $titleObj->escapeLocalURL( "action=submit" );

		if ( "" != $err ) {
			$wgOut->setSubtitle( wfMsg( "formerror" ) );
			$wgOut->addHTML( "<p class='error'>{$err}</p>\n" );
		}
		
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
</form>\n" );

	}
	
	function doSubmit()
	{
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
		$log = new LogPage( wfMsg( "blocklogpage" ), wfMsg( "blocklogtext" ) );
		$action = wfMsg( "unblocklogentry", $this->ip );
		$log->addEntry( $action, $this->reason );

		# Report to the user
		$titleObj = Title::makeTitle( NS_SPECIAL, "Ipblocklist" );
		$success = $titleObj->getFullURL( "action=success&ip=" . urlencode( $this->ip ) );
		$wgOut->redirect( $success );
	}

	function showList( $msg )
	{
		global $wgOut;
		
		$wgOut->setPagetitle( wfMsg( "ipblocklist" ) );
		if ( "" != $msg ) {
			$wgOut->setSubtitle( $msg );
		}
		$wgOut->addHTML( "<ul>" );
		Block::enumBlocks( "wfAddRow", 0 );
		$wgOut->addHTML( "</ul>\n" );
	}
}

# Callback function to output a block
function wfAddRow( $block, $tag ) {
	global $wgOut, $wgUser, $wgLang;

	$sk = $wgUser->getSkin();

	# Hide addresses blocked by User::spreadBlocks, for privacy
	$addr = $block->mAuto ? "#{$block->mId}" : $block->mAddress;

	$name = User::whoIs( $block->mBy );
	$ulink = $sk->makeKnownLink( $wgLang->getNsText( Namespace::getUser() ). ":{$name}", $name );
	$formattedTime = $wgLang->timeanddate( $block->mTimestamp, true );
	
	if ( $block->mExpiry === "" ) {
		$formattedExpiry = "indefinite";
	} else {
		$formattedExpiry = $wgLang->timeanddate( $block->mExpiry, true );
	}
	
	$line = wfMsg( "blocklistline", $formattedTime, $ulink, $addr, $formattedExpiry );
	
	$wgOut->addHTML( "<li>{$line}" );

	if ( !$block->mAuto ) {
		$titleObj = Title::makeTitle( NS_SPECIAL, "Contributions" );
		$clink = "<a href=\"" . $titleObj->escapeLocalURL( "target={$block->mAddress}" ) . "\">" .
		  wfMsg( "contribslink" ) . "</a>";
		$wgOut->addHTML( " ({$clink})" );
	}

	if ( $wgUser->isSysop() ) {
		$titleObj = Title::makeTitle( NS_SPECIAL, "Ipblocklist" );
		$ublink = "<a href=\"" . 
		  $titleObj->escapeLocalURL( "action=unblock&ip=" . urlencode( $addr ) ) . "\">" .
		  wfMsg( "unblocklink" ) . "</a>";
		$wgOut->addHTML( " ({$ublink})" );
	}
	if ( "" != $block->mReason ) {
		$wgOut->addHTML( " <em>(" . htmlspecialchars( $block->mReason ) .
		  ")</em>" );
	}
	$wgOut->addHTML( "</li>\n" );
}


?>
