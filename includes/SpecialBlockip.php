<?php

function wfSpecialBlockip()
{
	global $wgUser, $wgOut, $action;

	if ( ! $wgUser->isSysop() ) {
		$wgOut->sysopRequired();
		return;
	}
	$fields = array( "wpBlockAddress", "wpBlockReason", "wpBlockExpiry" );
	wfCleanFormFields( $fields );
	$ipb = new IPBlockForm();

	if ( "success" == $action ) { $ipb->showSuccess(); }
	else if ( "submit" == $action ) { $ipb->doSubmit(); }
	else { $ipb->showForm( "" ); }
}

class IPBlockForm {

	function showForm( $err )
	{
		global $wgOut, $wgUser, $wgLang, $wgDefaultBlockExpiry;
		global $ip, $wpBlockAddress, $wpBlockExpiry, $wpBlockReason;

		$wgOut->setPagetitle( wfMsg( "blockip" ) );
		$wgOut->addWikiText( wfMsg( "blockiptext" ) );

		if ( ! $wpBlockAddress ) { 
			$wpBlockAddress = $ip; 
		}

		if ( is_null( $wpBlockExpiry ) || $wpBlockExpiry === "" ) {
			$wpBlockExpiry = $wgDefaultBlockExpiry;
		}

		$mIpaddress = wfMsg( "ipaddress" );
		$mIpbexpiry = wfMsg( "ipbexpiry" );
		$mIpbreason = wfMsg( "ipbreason" );
		$mIpbsubmit = wfMsg( "ipbsubmit" );
		$action = wfLocalUrlE( $wgLang->specialPage( "Blockip" ),
		  "action=submit" );

		if ( "" != $err ) {
			$wgOut->setSubtitle( wfMsg( "formerror" ) );
			$wgOut->addHTML( "<p><font color='red' size='+1'>{$err}</font>\n" );
		}

		$scBlockAddress = htmlspecialchars( $wpBlockAddress );
		$scBlockExpiry = htmlspecialchars( $wpBlockExpiry );
		$scBlockReason = htmlspecialchars( $wpBlockReason );
		
		$wgOut->addHTML( "<p>
<form id=\"blockip\" method=\"post\" action=\"{$action}\">
<table border=0><tr>
<td align=\"right\">{$mIpaddress}:</td>
<td align=\"left\">
<input tabindex=1 type=text size=20 name=\"wpBlockAddress\" value=\"{$scBlockAddress}\">
</td></tr><tr>
<td align=\"right\">{$mIpbexpiry}:</td>
<td align=\"left\">
<input tabindex=2 type=text size=20 name=\"wpBlockExpiry\" value=\"{$scBlockExpiry}\">
</td></tr><tr>
<td align=\"right\">{$mIpbreason}:</td>
<td align=\"left\">
<input tabindex=3 type=text size=40 name=\"wpBlockReason\" value=\"{$scBlockReason}\">
</td></tr><tr>
<td>&nbsp;</td><td align=\"left\">
<input tabindex=4 type=submit name=\"wpBlock\" value=\"{$mIpbsubmit}\">
</td></tr></table>
</form>\n" );

	}

	function doSubmit()
	{
		global $wgOut, $wgUser, $wgLang;
		global $ip, $wpBlockAddress, $wpBlockReason, $wpBlockExpiry;
		global $wgSysopUserBans, $wgSysopRangeBans;
		
		$userId = 0;
		$wpBlockAddress = trim( $wpBlockAddress );
		$rxIP = '\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}';

		# Check for invalid specifications
		if ( ! preg_match( "/^$rxIP$/", $wpBlockAddress ) ) {
		  	if ( preg_match( "/^($rxIP)\\/(\\d{1,2})$/", $wpBlockAddress, $matches ) ) {
				if ( $wgSysopRangeBans ) {
					if ( $matches[2] > 31 || $matches[2] < 16 ) {
						$this->showForm( wfMsg( "ip_range_invalid" ) );
						return;
					}
					$wpBlockAddress = Block::normaliseRange( $wpBlockAddress );
				} else {
					# Range block illegal
					$this->showForm( wfMsg( "range_block_disabled" ) );
					return;
				}
			} else {
				# Username block
				if ( $wgSysopUserBans ) {	
					$userId = User::idFromName( $wpBlockAddress );
					if ( $userId == 0 ) {
						$this->showForm( wfMsg( "nosuchuser", htmlspecialchars( $wpBlockAddress ) ) );
						return;
					}
				} else {
					$this->showForm( wfMsg( "badipaddress" ) );
					return;
				}
			}
		}

		if ( $wpBlockExpiry == "infinite" || $wpBlockExpiry == "indefinite" ) {
			$expiry = '';
		} else {
			# Convert GNU-style date, returns -1 on error
			$expiry = strtotime( $wpBlockExpiry );

			if ( $expiry < 0 ) {
				$this->showForm( wfMsg( "ipb_expiry_invalid" ) );
				return;
			}
			
			$expiry = wfUnix2Timestamp( $expiry );

		}

		
		if ( "" == $wpBlockReason ) {
			$this->showForm( wfMsg( "noblockreason" ) );
			return;
		}
		
		# Create block
		# Note: for a user block, ipb_address is only for display purposes

		$ban = new Block( $wpBlockAddress, $userId, $wgUser->getID(), 
			wfStrencode( $wpBlockReason ), wfTimestampNow(), 0, $expiry );
		$ban->insert();

		# Make log entry
		$log = new LogPage( wfMsg( "blocklogpage" ), wfMsg( "blocklogtext" ) );
		$action = wfMsg( "blocklogentry", $wpBlockAddress, $wpBlockExpiry );
		$log->addEntry( $action, $wpBlockReason );

		# Report to the user
		$success = wfLocalUrl( $wgLang->specialPage( "Blockip" ),
		  "action=success&ip={$wpBlockAddress}" );
		$wgOut->redirect( $success );
	}

	function showSuccess()
	{
		global $wgOut, $wgUser;
		global $ip;

		$wgOut->setPagetitle( wfMsg( "blockip" ) );
		$wgOut->setSubtitle( wfMsg( "blockipsuccesssub" ) );
		$text = wfMsg( "blockipsuccesstext", $ip );
		$wgOut->addWikiText( $text );
	}
}

?>
