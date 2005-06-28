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

	if ( ! $wgUser->isAllowed('block') ) {
		$wgOut->sysopRequired();
		return;
	}
	$ipb = new IPBlockForm();

	$action = $wgRequest->getVal( 'action' );
	if ( 'success' == $action ) {
		$ipb->showSuccess();
	} else if ( $wgRequest->wasPosted() && 'submit' == $action &&
		$wgUser->matchEditToken( $wgRequest->getVal( 'wpEditToken' ) ) ) {
		$ipb->doSubmit();
	} else {
		$ipb->showForm( '' );
	}
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
		$this->BlockOther = $wgRequest->getVal( 'wpBlockOther' );
	}
	
	function showForm( $err ) {
		global $wgOut, $wgUser, $wgLang, $wgBlockExpiryOptions;
		global $wgRequest, $wgSysopUserBans;

		$wgOut->setPagetitle( htmlspecialchars( wfMsg( 'blockip' ) ) );
		$wgOut->addWikiText( wfMsg( 'blockiptext' ) );

		if($wgSysopUserBans) {
			$mIpaddress = htmlspecialchars( wfMsg( 'ipadressorusername' ) );
		} else {
			$mIpaddress = htmlspecialchars( wfMsg( 'ipaddress' ) );
		}
		$mIpbexpiry = htmlspecialchars( wfMsg( 'ipbexpiry' ) );
		$mIpbother = htmlspecialchars( wfMsg( 'ipbother' ) );
		$mIpbothertime = htmlspecialchars( wfMsg( 'ipbotheroption' ) );
		$mIpbreason = htmlspecialchars( wfMsg( 'ipbreason' ) );
		$mIpbsubmit = htmlspecialchars( wfMsg( 'ipbsubmit' ) );
		$titleObj = Title::makeTitle( NS_SPECIAL, 'Blockip' );
		$action = $titleObj->escapeLocalURL( "action=submit" );

		if ( "" != $err ) {
			$wgOut->setSubtitle( wfMsg( 'formerror' ) );
			$wgOut->addHTML( "<p class='error'>{$err}</p>\n" );
		}

		$scBlockAddress = htmlspecialchars( $this->BlockAddress );
		$scBlockExpiry = htmlspecialchars( $this->BlockExpiry );
		$scBlockReason = htmlspecialchars( $this->BlockReason );
		$scBlockOtherTime = htmlspecialchars( $this->BlockOtherTime );
		$scBlockExpiryOptions = htmlspecialchars( wfMsg( 'ipboptions' ) );

		$blockExpiryFormOptions = "<option value=\"other\">$mIpbothertime</option>";
		foreach (explode(',', $scBlockExpiryOptions) as $option) {
			$selected = "";
			if ($this->BlockExpiry === $option)
				$selected = ' selected="selected"';
			$blockExpiryFormOptions .= "<option$selected>$option</option>";
		}

		$token = htmlspecialchars( $wgUser->editToken() );
		
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
				<select tabindex='2' name=\"wpBlockExpiry\">
					$blockExpiryFormOptions
				</select>
			</td>
		</tr>
		<tr>
			<td align=\"right\">{$mIpbother}:</td>
			<td align=\"left\">
				<input tabindex='3' type='text' size='40' name=\"wpBlockOther\" value=\"{$scBlockOtherTime}\" />
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
	<input type='hidden' name='wpEditToken' value=\"{$token}\" />
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
						$this->showForm( wfMsg( 'ip_range_invalid' ) );
						return;
					}
					$this->BlockAddress = Block::normaliseRange( $this->BlockAddress );
				} else {
					# Range block illegal
					$this->showForm( wfMsg( 'range_block_disabled' ) );
					return;
				}
			} else {
				# Username block
				if ( $wgSysopUserBans ) {	
					$userId = User::idFromName( $this->BlockAddress );
					if ( $userId == 0 ) {
						$this->showForm( wfMsg( 'nosuchusershort', htmlspecialchars( $this->BlockAddress ) ) );
						return;
					}
				} else {
					$this->showForm( wfMsg( 'badipaddress' ) );
					return;
				}
			}
		}

		$expirestr = $this->BlockExpiry;
		if (strlen($expirestr) == 0 || $expirestr == wfMsg('ipbotheroption'))
			$expirestr = $this->BlockOther;

		if ( $expirestr == 'infinite' || $expirestr == 'indefinite' ) {
			$expiry = '';
		} else {
			# Convert GNU-style date, returns -1 on error
			$expiry = strtotime( $expirestr );

			if ( $expiry < 0 ) {
				$this->showForm( wfMsg( 'ipb_expiry_invalid' ) );
				return;
			}
			
			$expiry = wfTimestamp( TS_MW, $expiry );

		}
		
		# Create block
		# Note: for a user block, ipb_address is only for display purposes

		$ban = new Block( $this->BlockAddress, $userId, $wgUser->getID(), 
			$this->BlockReason, wfTimestampNow(), 0, $expiry );
		
		if (wfRunHooks('BlockIp', array(&$ban, &$wgUser))) {
			
			$ban->insert();
			
			wfRunHooks('BlockIpComplete', array($ban, $wgUser));
			
			# Make log entry
			$log = new LogPage( 'block' );
			$log->addEntry( 'block', Title::makeTitle( NS_USER, $this->BlockAddress ), 
			  $this->BlockReason, $expirestr );

			# Report to the user
			$titleObj = Title::makeTitle( NS_SPECIAL, 'Blockip' );
			$wgOut->redirect( $titleObj->getFullURL( 'action=success&ip=' .
				urlencode( $this->BlockAddress ) ) );
		}
	}

	function showSuccess() {
		global $wgOut, $wgUser;

		$wgOut->setPagetitle( wfMsg( 'blockip' ) );
		$wgOut->setSubtitle( wfMsg( 'blockipsuccesssub' ) );
		$text = wfMsg( 'blockipsuccesstext', $this->BlockAddress );
		$wgOut->addWikiText( $text );
	}
}

?>
