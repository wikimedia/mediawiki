<?

function wfSpecialIpblocklist()
{
	global $wgUser, $wgOut, $action, $ip;

	$fields = array( "wpUnblockAddress" );
	wfCleanFormFields( $fields );
	$ipu = new IPUnblockForm();

	if ( "success" == $action ) {
		$msg = str_replace( "$1", $ip, wfMsg( "ipusuccess" ) );
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
<td>&nbsp;</td><td align=left>
<input tabindex=2 type=submit name=\"wpBlock\" value=\"{$ipus}\">
</td></tr></table>
</form>\n" );

	}

	function doSubmit()
	{
		global $wgOut, $wgUser, $wgLang;
		global $ip, $wpUnblockAddress;
		$fname = "IPUnblockForm::doSubmit";

		$sql = "DELETE FROM ipblocks WHERE ipb_address='{$wpUnblockAddress}'";
		wfQuery( $sql, $fname );

		$success = wfLocalUrl( $wgLang->specialPage( "Ipblocklist" ),
		  "action=success&ip={$wpUnblockAddress}" );
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

# Callback function
function wfAddRow( $block, $tag ) {
	global $wgOut, $wgUser, $wgLang, $ip;

	$sk = $wgUser->getSkin();
	$addr = $block->mAddress;
	$name = User::whoIs( $block->mBy );
	$ulink = $sk->makeKnownLink( $wgLang->getNsText( Namespace::getUser() ). ":{$name}", $name );
	$d = $wgLang->timeanddate( $block->mTimestamp, true );

	$line = str_replace( "$1", $d, wfMsg( "blocklistline" ) );
	$line = str_replace( "$2", $ulink, $line );
	$line = str_replace( "$3", $block->mAddress, $line );

	$wgOut->addHTML( "<li>{$line}" );
	$clink = "<a href=\"" . wfLocalUrlE( $wgLang->specialPage(
	  "Contributions" ), "target={$addr}" ) . "\">" .
	  wfMsg( "contribslink" ) . "</a>";
	$wgOut->addHTML( " ({$clink})" );

	if ( $wgUser->isSysop() ) {
		$ublink = "<a href=\"" . wfLocalUrlE( $wgLang->specialPage(
		  "Ipblocklist" ), "action=unblock&ip={$addr}" ) . "\">" .
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
