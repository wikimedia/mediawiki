<?php
/**
 * Constructor for Special:Blockip page
 *
 * @package MediaWiki
 * @subpackage SpecialPage
 */

/**
 * Constructor
 */
function wfSpecialBlockip() {
	global $wgUser, $wgOut, $wgRequest;

	if ( ! $wgUser->isSysop() ) {
		$wgOut->sysopRequired();
		return;
	}
	$ipb = new IPBlockForm();

	$action = $wgRequest->getVal( 'action' );
	if ( "success" == $action ) { $ipb->showSuccess(); }
	else if ( $wgRequest->wasPosted() && "submit" == $action ) { $ipb->doSubmit(); }
	else { $ipb->showForm( "" ); }
}

/**
 * Form object
 *
 * @package MediaWiki
 * @subpackage SpecialPage
 */
class IPBlockForm {
	var $BlockAddress, $BlockExpiry, $BlockReason;

	function IPBlockForm() {
		global $wgRequest;
		$this->BlockAddress = $wgRequest->getVal( 'wpBlockAddress', $wgRequest->getVal( 'ip' ) );
		$this->BlockReason = $wgRequest->getText( 'wpBlockReason' );
		$this->BlockExpiry = $wgRequest->getVal( 'wpBlockExpiry' );
	}
	
	function showForm( $err )
	{
		global $wgOut, $wgUser, $wgLang, $wgDefaultBlockExpiry;
		global $wgRequest;

		$wgOut->setPagetitle( htmlspecialchars( wfMsg( "blockip" ) ) );
		$wgOut->addWikiText( htmlspecialchars( wfMsg( "blockiptext" ) ) );

		if ( is_null( $this->BlockExpiry ) || $this->BlockExpiry === "" ) {
			$this->BlockExpiry = $wgDefaultBlockExpiry;
		}

		$mIpaddress = htmlspecialchars( wfMsg( "ipaddress" ) );
		$mIpbexpiry = htmlspecialchars( wfMsg( "ipbexpiry" ) );
		$mIpbreason = htmlspecialchars( wfMsg( "ipbreason" ) );
		$mIpbsubmit = htmlspecialchars( wfMsg( "ipbsubmit" ) );
		$titleObj = Title::makeTitle( NS_SPECIAL, "Blockip" );
		$action = $titleObj->escapeLocalURL( "action=submit" );

		if ( "" != $err ) {
			$wgOut->setSubtitle( htmlspecialchars( wfMsg( "formerror" ) ) );
			$wgOut->addHTML( "<p class='error'>{$err}</p>\n" );
		}

		$scBlockAddress = htmlspecialchars( $this->BlockAddress );
		$scBlockExpiry = htmlspecialchars( $this->BlockExpiry );
		$scBlockReason = htmlspecialchars( $this->BlockReason );
		
		$wgOut->addHTML( "
<form id=\"blockip\" method=\"post\" action=\"{$action}\">
	<table border='0'>
		<tr>
			<td align=\"right\">{$mIpaddress}:</td>
			<td align=\"left\">
				<input tabindex='1' type='text' size='20' name=\"wpBlockAddress\" value=\"{$scBlockAddress}\" />
			</td>
		</tr>
		<tr>
			<td align=\"right\">{$mIpbexpiry}:</td>
			<td align=\"left\">
				<input tabindex='2' type='text' size='20' name=\"wpBlockExpiry\" value=\"{$scBlockExpiry}\" />
			</td>
		</tr>
		<tr>
			<td align=\"right\">{$mIpbreason}:</td>
			<td align=\"left\">
				<input tabindex='3' type='text' size='40' name=\"wpBlockReason\" value=\"{$scBlockReason}\" />
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td align=\"left\">
				<input tabindex='4' type='submit' name=\"wpBlock\" value=\"{$mIpbsubmit}\" />
			</td>
		</tr>
	</table>
</form>\n" );

	}

	function doSubmit() {
		global $wgOut, $wgUser, $wgLang;
		global $wgSysopUserBans, $wgSysopRangeBans;
		
		$userId = 0;
		$this->BlockAddress = trim( $this->BlockAddress );
		$rxIP = '\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}';

		# Check for invalid specifications
		if ( ! preg_match( "/^$rxIP$/", $this->BlockAddress ) ) {
		  	if ( preg_match( "/^($rxIP)\\/(\\d{1,2})$/", $this->BlockAddress, $matches ) ) {
				if ( $wgSysopRangeBans ) {
					if ( $matches[2] > 31 || $matches[2] < 16 ) {
						$this->showForm( wfMsg( "ip_range_invalid" ) );
						return;
					}
					$this->BlockAddress = Block::normaliseRange( $this->BlockAddress );
				} else {
					# Range block illegal
					$this->showForm( wfMsg( "range_block_disabled" ) );
					return;
				}
			} else {
				# Username block
				if ( $wgSysopUserBans ) {	
					$userId = User::idFromName( $this->BlockAddress );
					if ( $userId == 0 ) {
						$this->showForm( wfMsg( "nosuchuser", htmlspecialchars( $this->BlockAddress ) ) );
						return;
					}
				} else {
					$this->showForm( wfMsg( "badipaddress" ) );
					return;
				}
			}
		}

		if ( $this->BlockExpiry == "infinite" || $this->BlockExpiry == "indefinite" ) {
			$expiry = '';
		} else {
			# Convert GNU-style date, returns -1 on error
			$expiry = strtotime( $this->BlockExpiry );

			if ( $expiry < 0 ) {
				$this->showForm( wfMsg( "ipb_expiry_invalid" ) );
				return;
			}
			
			$expiry = wfUnix2Timestamp( $expiry );

		}

		
		if ( "" == $this->BlockReason ) {
			$this->showForm( wfMsg( "noblockreason" ) );
			return;
		}
		
		# Create block
		# Note: for a user block, ipb_address is only for display purposes

		$ban = new Block( $this->BlockAddress, $userId, $wgUser->getID(), 
			wfStrencode( $this->BlockReason ), wfTimestampNow(), 0, $expiry );
		$ban->insert();

		# Make log entry
		$log = new LogPage( 'block' );
		$log->addEntry( 'block', Title::makeTitle( NS_USER, $this->BlockAddress ), $this->BlockReason );

		# Report to the user
		$titleObj = Title::makeTitle( NS_SPECIAL, "Blockip" );
		$wgOut->redirect( $titleObj->getFullURL( "action=success&ip={$this->BlockAddress}" ) );
	}

	function showSuccess() {
		global $wgOut, $wgUser;

		$wgOut->setPagetitle( wfMsg( "blockip" ) );
		$wgOut->setSubtitle( wfMsg( "blockipsuccesssub" ) );
		$text = wfMsg( "blockipsuccesstext", $this->BlockAddress );
		$wgOut->addWikiText( $text );
	}
}

?>
