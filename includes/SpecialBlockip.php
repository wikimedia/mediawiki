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
function wfSpecialBlockip( $par ) {
	global $wgUser, $wgOut, $wgRequest;

	if( !$wgUser->isAllowed( 'block' ) ) {
		$wgOut->permissionRequired( 'block' );
		return;
	}

	$ipb = new IPBlockForm( $par );

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

	function IPBlockForm( $par ) {
		global $wgRequest;

		$this->BlockAddress = $wgRequest->getVal( 'wpBlockAddress', $wgRequest->getVal( 'ip', $par ) );
		$this->BlockReason = $wgRequest->getText( 'wpBlockReason' );
		$this->BlockExpiry = $wgRequest->getVal( 'wpBlockExpiry', wfMsg('ipbotheroption') );
		$this->BlockOther = $wgRequest->getVal( 'wpBlockOther', '' );
		$this->BlockAnonOnly = $wgRequest->getBool( 'wpAnonOnly' );

		# Unchecked checkboxes are not included in the form data at all, so having one 
		# that is true by default is a bit tricky
		if ( $wgRequest->wasPosted() ) {
			$this->BlockCreateAccount = $wgRequest->getBool( 'wpCreateAccount', false );
		} else {
			$this->BlockCreateAccount = $wgRequest->getBool( 'wpCreateAccount', true );
		}
	}

	function showForm( $err ) {
		global $wgOut, $wgUser, $wgSysopUserBans;

		$wgOut->setPagetitle( wfMsg( 'blockip' ) );
		$wgOut->addWikiText( wfMsg( 'blockiptext' ) );

		if($wgSysopUserBans) {
			$mIpaddress = wfMsgHtml( 'ipadressorusername' );
		} else {
			$mIpaddress = wfMsgHtml( 'ipaddress' );
		}
		$mIpbexpiry = wfMsgHtml( 'ipbexpiry' );
		$mIpbother = wfMsgHtml( 'ipbother' );
		$mIpbothertime = wfMsgHtml( 'ipbotheroption' );
		$mIpbreason = wfMsgHtml( 'ipbreason' );
		$mIpbsubmit = wfMsgHtml( 'ipbsubmit' );
		$titleObj = Title::makeTitle( NS_SPECIAL, 'Blockip' );
		$action = $titleObj->escapeLocalURL( "action=submit" );

		if ( "" != $err ) {
			$wgOut->setSubtitle( wfMsgHtml( 'formerror' ) );
			$wgOut->addHTML( "<p class='error'>{$err}</p>\n" );
		}

		$scBlockAddress = htmlspecialchars( $this->BlockAddress );
		$scBlockExpiry = htmlspecialchars( $this->BlockExpiry );
		$scBlockReason = htmlspecialchars( $this->BlockReason );
		$scBlockOtherTime = htmlspecialchars( $this->BlockOther );
		$scBlockExpiryOptions = htmlspecialchars( wfMsgForContent( 'ipboptions' ) );

		$showblockoptions = $scBlockExpiryOptions != '-';
		if (!$showblockoptions)
			$mIpbother = $mIpbexpiry;

		$blockExpiryFormOptions = "<option value=\"other\">$mIpbothertime</option>";
		foreach (explode(',', $scBlockExpiryOptions) as $option) {
			if ( strpos($option, ":") === false ) $option = "$option:$option";
			list($show, $value) = explode(":", $option);
			$show = htmlspecialchars($show);
			$value = htmlspecialchars($value);
			$selected = "";
			if ($this->BlockExpiry === $value)
				$selected = ' selected="selected"';
			$blockExpiryFormOptions .= "<option value=\"$value\"$selected>$show</option>";
		}

		$token = htmlspecialchars( $wgUser->editToken() );

		$wgOut->addHTML( "
<form id=\"blockip\" method=\"post\" action=\"{$action}\">
	<table border='0'>
		<tr>
			<td align=\"right\">{$mIpaddress}:</td>
			<td align=\"left\">
				<input tabindex='1' type='text' size='40' name=\"wpBlockAddress\" value=\"{$scBlockAddress}\" />
			</td>
		</tr>
		<tr>");
		if ($showblockoptions) {
			$wgOut->addHTML("
			<td align=\"right\">{$mIpbexpiry}:</td>
			<td align=\"left\">
				<select tabindex='2' id='wpBlockExpiry' name=\"wpBlockExpiry\" onchange=\"considerChangingExpiryFocus()\">
					$blockExpiryFormOptions
				</select>
			</td>
			");
		}
		$wgOut->addHTML("
		</tr>
		<tr id='wpBlockOther'>
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
				" . wfCheckLabel( wfMsg( 'ipbanononly' ),
					'wpAnonOnly', 'wpAnonOnly', $this->BlockAnonOnly ) . "
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td align=\"left\">
				" . wfCheckLabel( wfMsg( 'ipbcreateaccount' ),
					'wpCreateAccount', 'wpCreateAccount', $this->BlockCreateAccount ) . "
			</td>
		</tr>
		<tr>
			<td style='padding-top: 1em'>&nbsp;</td>
			<td style='padding-top: 1em' align=\"left\">
				<input tabindex='4' type='submit' name=\"wpBlock\" value=\"{$mIpbsubmit}\" />
			</td>
		</tr>
	</table>
	<input type='hidden' name='wpEditToken' value=\"{$token}\" />
</form>\n" );

	}

	function doSubmit() {
		global $wgOut, $wgUser, $wgSysopUserBans, $wgSysopRangeBans;

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
					$user = User::newFromName( $this->BlockAddress );
					if( !is_null( $user ) && $user->getID() ) {
						# Use canonical name
						$this->BlockAddress = $user->getName();
					} else {
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
		if( $expirestr == 'other' )
			$expirestr = $this->BlockOther;

		if (strlen($expirestr) == 0) {
			$this->showForm( wfMsg( 'ipb_expiry_invalid' ) );
			return;
		}

		if ( $expirestr == 'infinite' || $expirestr == 'indefinite' ) {
			$expiry = Block::infinity();
		} else {
			# Convert GNU-style date, on error returns -1 for PHP <5.1 and false for PHP >=5.1
			$expiry = strtotime( $expirestr );

			if ( $expiry < 0 || $expiry === false ) {
				$this->showForm( wfMsg( 'ipb_expiry_invalid' ) );
				return;
			}

			$expiry = wfTimestamp( TS_MW, $expiry );
		}

		# Create block
		# Note: for a user block, ipb_address is only for display purposes

		$block = new Block( $this->BlockAddress, $userId, $wgUser->getID(),
			$this->BlockReason, wfTimestampNow(), 0, $expiry, $this->BlockAnonOnly, 
			$this->BlockCreateAccount );

		if (wfRunHooks('BlockIp', array(&$block, &$wgUser))) {

			if ( !$block->insert() ) {
				$this->showForm( wfMsg( 'ipb_already_blocked', 
					htmlspecialchars( $this->BlockAddress ) ) );
				return;
			}

			wfRunHooks('BlockIpComplete', array($block, $wgUser));

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
		global $wgOut;

		$wgOut->setPagetitle( wfMsg( 'blockip' ) );
		$wgOut->setSubtitle( wfMsg( 'blockipsuccesssub' ) );
		$text = wfMsg( 'blockipsuccesstext', $this->BlockAddress );
		$wgOut->addWikiText( $text );
	}
}

?>
