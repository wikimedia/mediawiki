<?php

function wfSpecialIpblocklist()
{
	global $wgUser, $wgOut, $action, $ip;

	$fields = array( "wpUnblockAddress" );
	wfCleanFormFields( $fields );
	$ipu = new IPUnblockForm();

	if ( "success" == $action ) {
		$msg = wfMsg( "ipusuccess", $ip );
		$ipu->showList( $msg );
	} else if ( "submit" == $action ) {
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

	function showForm( $err )
	{
		global $wgOut, $wgUser, $wgLang;
		global $ip, $wpUnblockAddress;

		$wgOut->setPagetitle( wfMsg( "unblockip" ) );
		$wgOut->addWikiText( wfMsg( "unblockiptext" ) );

		if ( ! $wpUnblockAddress ) { $wpUnblockAddress = $ip; }
		$ipa = wfMsg( "ipaddress" );
		$ipr = wfMsg( "ipbreason" );
		$ipus = wfMsg( "ipusubmit" );
		$action = wfLocalUrlE( $wgLang->specialPage( "Ipblocklist" ),
		  "action=submit" );

		if ( "" != $err ) {
			$wgOut->setSubtitle( wfMsg( "formerror" ) );
			$wgOut->addHTML( "<p><font color='red' size='+1'>{$err}</font>\n" );
		}
		$wgOut->addHTML( "<p>
<form id=\"unblockip\" method=\"post\" action=\"{$action}\">
<table border=0><tr>
<td align=right>{$ipa}:</td>
<td align=left>
<input tabindex=1 type=text size=20 name=\"wpUnblockAddress\" value=\"{$wpUnblockAddress}\">
</td></tr><tr>
<td align=right>{$ipr}:</td>
<td align=left>
<input tabindex=1 type=text size=40 name=\"wpUnblockReason\" value=\"{$wpUnblockReason}\">
</td></tr><tr>
<td>&nbsp;</td><td align=left>
<input tabindex=2 type=submit name=\"wpBlock\" value=\"{$ipus}\">
</td></tr></table>
</form>\n" );

	}
	
	function doSubmit()
	{
		global $wgOut, $wgUser, $wgLang;
		global $wpUnblockAddress, $wpUnblockReason;

		$block = new Block();
		$wpUnblockAddress = trim( $wpUnblockAddress );

		if ( $wpUnblockAddress{0} == "#" ) {
			$block->mId = substr( $wpUnblockAddress, 1 );
		} else {
			$block->mAddress = $wpUnblockAddress;
		}
		
		# Delete block (if it exists)
		# We should probably check for errors rather than just declaring success
		$block->delete();

		# Make log entry
		$log = new LogPage( wfMsg( "blocklogpage" ), wfMsg( "blocklogtext" ) );
		$action = wfMsg( "unblocklogentry", $wpUnblockAddress );
		$log->addEntry( $action, $wpUnblockReason );

		# Report to the user
		$success = wfLocalUrl( $wgLang->specialPage( "Ipblocklist" ),
		  "action=success&ip=" . urlencode($wpUnblockAddress) );
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
	global $wgOut, $wgUser, $wgLang, $ip;

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
		$clink = "<a href=\"" . wfLocalUrlE( $wgLang->specialPage(
		  "Contributions" ), "target={$block->mAddress}" ) . "\">" .
		  wfMsg( "contribslink" ) . "</a>";
		$wgOut->addHTML( " ({$clink})" );
	}

	if ( $wgUser->isSysop() ) {
		$ublink = "<a href=\"" . wfLocalUrlE( $wgLang->specialPage(
		  "Ipblocklist" ), "action=unblock&ip=" . urlencode( $addr ) ) . "\">" .
		  wfMsg( "unblocklink" ) . "</a>";
		$wgOut->addHTML( " ({$ublink})" );
	}
	if ( "" != $block->mReason ) {
		$wgOut->addHTML( " <em>(" . wfEscapeHTML( $block->mReason ) .
		  ")</em>" );
	}
	$wgOut->addHTML( "</li>\n" );
}


?>
