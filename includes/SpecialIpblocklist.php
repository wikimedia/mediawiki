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
		global $wgOut, $wgUser, $wgLang;
		global $ip;

		$wgOut->setPagetitle( wfMsg( "ipblocklist" ) );
		if ( "" != $msg ) {
			$wgOut->setSubtitle( $msg );
		}
		$sql = "SELECT ipb_timestamp,ipb_address,ipb_user,ipb_by,ipb_reason " .
		  "FROM ipblocks ORDER BY ipb_timestamp";
		$res = wfQuery( $sql, "IPUnblockForm::showList" );

		$wgOut->addHTML( "<ul>" );
		$sk = $wgUser->getSkin();
		while ( $row = wfFetchObject( $res ) ) {
			$addr = $row->ipb_address;
			$name = User::whoIs( $row->ipb_by );
			$ulink = $sk->makeKnownLink( $wgLang->getNsText( Namespace::getUser() ). ":{$name}", $name );
			$d = $wgLang->timeanddate( $row->ipb_timestamp, true );

			$line = str_replace( "$1", $d, wfMsg( "blocklistline" ) );
			$line = str_replace( "$2", $ulink, $line );
			$line = str_replace( "$3", $row->ipb_address, $line );

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
			if ( "" != $row->ipb_reason ) {
				$wgOut->addHTML( " <em>(" . wfEscapeHTML( $row->ipb_reason ) .
				  ")</em>" );
			}
			$wgOut->addHTML( "</li>\n" );
		}
		wfFreeResult( $res );
		$wgOut->addHTML( "</ul>\n" );
	}
}

?>
