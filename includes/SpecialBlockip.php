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

	# Can't block when the database is locked
	if( wfReadOnly() ) {
		$wgOut->readOnlyPage();
		return;
	}

	# Permission check
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
		$this->BlockAddress = strtr( $this->BlockAddress, '_', ' ' );
		$this->BlockReason = $wgRequest->getText( 'wpBlockReason' );
		$this->BlockExpiry = $wgRequest->getVal( 'wpBlockExpiry', wfMsg('ipbotheroption') );
		$this->BlockOther = $wgRequest->getVal( 'wpBlockOther', '' );

		# Unchecked checkboxes are not included in the form data at all, so having one
		# that is true by default is a bit tricky
		$byDefault = !$wgRequest->wasPosted();
		$this->BlockAnonOnly = $wgRequest->getBool( 'wpAnonOnly', $byDefault );
		$this->BlockCreateAccount = $wgRequest->getBool( 'wpCreateAccount', $byDefault );
		$this->BlockEnableAutoblock = $wgRequest->getBool( 'wpEnableAutoblock', $byDefault );
	}

	function showForm( $err ) {
		global $wgOut, $wgUser, $wgSysopUserBans;

		$wgOut->setPagetitle( wfMsg( 'blockip' ) );
		$wgOut->addWikiText( wfMsg( 'blockiptext' ) );

		if($wgSysopUserBans) {
			$mIpaddress = Xml::label( wfMsg( 'ipadressorusername' ), 'mw-bi-target' );
		} else {
			$mIpaddress = Xml::label( wfMsg( 'ipadress' ), 'mw-bi-target' );
		}
		$mIpbexpiry = Xml::label( wfMsg( 'ipbexpiry' ), 'wpBlockExpiry' );
		$mIpbother = Xml::label( wfMsg( 'ipbother' ), 'mw-bi-other' );
		$mIpbothertime = wfMsgHtml( 'ipbotheroption' );
		$mIpbreason = Xml::label( wfMsg( 'ipbreason' ), 'mw-bi-reason' );
		$titleObj = SpecialPage::getTitleFor( 'Blockip' );
		$action = $titleObj->escapeLocalURL( "action=submit" );

		if ( "" != $err ) {
			$wgOut->setSubtitle( wfMsgHtml( 'formerror' ) );
			$wgOut->addHTML( "<p class='error'>{$err}</p>\n" );
		}

		$scBlockExpiryOptions = wfMsgForContent( 'ipboptions' );

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
				" . Xml::input( 'wpBlockAddress', 40, $this->BlockAddress,
					array( 'tabindex' => '1', 'id' => 'mw-bi-target' ) ) . "
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
				" . Xml::input( 'wpBlockOther', 40, $this->BlockOther,
					array( 'tabindex' => '3', 'id' => 'mw-bi-other' ) ) . "
			</td>
		</tr>
		<tr>
			<td align=\"right\">{$mIpbreason}:</td>
			<td align=\"left\">
				" . Xml::input( 'wpBlockReason', 40, $this->BlockReason,
					array( 'tabindex' => '3', 'id' => 'mw-bi-reason' ) ) . "
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td align=\"left\">
				" . wfCheckLabel( wfMsg( 'ipbanononly' ),
					'wpAnonOnly', 'wpAnonOnly', $this->BlockAnonOnly,
					array( 'tabindex' => 4 ) ) . "
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td align=\"left\">
				" . wfCheckLabel( wfMsg( 'ipbcreateaccount' ),
					'wpCreateAccount', 'wpCreateAccount', $this->BlockCreateAccount,
					array( 'tabindex' => 5 ) ) . "
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td align=\"left\">
				" . wfCheckLabel( wfMsg( 'ipbenableautoblock' ),
						'wpEnableAutoblock', 'wpEnableAutoblock', $this->BlockEnableAutoblock,
							array( 'tabindex' => 6 ) ) . "
			</td>
		</tr>
		<tr>
			<td style='padding-top: 1em'>&nbsp;</td>
			<td style='padding-top: 1em' align=\"left\">
				" . Xml::submitButton( wfMsg( 'ipbsubmit' ),
							array( 'name' => 'wpBlock', 'tabindex' => '7' ) ) . "
			</td>
		</tr>
	</table>" .
	Xml::hidden( 'wpEditToken', $token ) .
"</form>\n" );

		$wgOut->addHtml( $this->getConvenienceLinks() );

		$user = User::newFromName( $this->BlockAddress );
		if( is_object( $user ) ) {
			$this->showLogFragment( $wgOut, $user->getUserPage() );
		} elseif( preg_match( '/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}/', $this->BlockAddress ) ) {
			$this->showLogFragment( $wgOut, Title::makeTitle( NS_USER, $this->BlockAddress ) );
		}
	}

	function doSubmit() {
		global $wgOut, $wgUser, $wgSysopUserBans, $wgSysopRangeBans;

		$userId = 0;
		$this->BlockAddress = trim( $this->BlockAddress );
		$rxIP = '\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}';

		# Check for invalid specifications
		if ( ! preg_match( "/^$rxIP$/", $this->BlockAddress ) ) {
			$matches = array();
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
						$userId = $user->getID();
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
			$this->BlockCreateAccount, $this->BlockEnableAutoblock );

		if (wfRunHooks('BlockIp', array(&$block, &$wgUser))) {

			if ( !$block->insert() ) {
				$this->showForm( wfMsg( 'ipb_already_blocked',
					htmlspecialchars( $this->BlockAddress ) ) );
				return;
			}

			wfRunHooks('BlockIpComplete', array($block, $wgUser));

			# Prepare log parameters
			$logParams = array();
			$logParams[] = $expirestr;
			$logParams[] = $this->blockLogFlags();

			# Make log entry
			$log = new LogPage( 'block' );
			$log->addEntry( 'block', Title::makeTitle( NS_USER, $this->BlockAddress ),
			  $this->BlockReason, $logParams );

			# Report to the user
			$titleObj = SpecialPage::getTitleFor( 'Blockip' );
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

	function showLogFragment( $out, $title ) {
		$out->addHtml( wfElement( 'h2', NULL, LogPage::logName( 'block' ) ) );
		$request = new FauxRequest( array( 'page' => $title->getPrefixedText(), 'type' => 'block' ) );
		$viewer = new LogViewer( new LogReader( $request ) );
		$viewer->showList( $out );
	}

	/**
	 * Return a comma-delimited list of "flags" to be passed to the log
	 * reader for this block, to provide more information in the logs
	 *
	 * @return array
	 */
	private function blockLogFlags() {
		$flags = array();
		if( $this->BlockAnonOnly )
			$flags[] = 'anononly';
		if( $this->BlockCreateAccount )
			$flags[] = 'nocreate';
		if( $this->BlockEnableAutoblock )
			$flags[] = 'autoblock';
		return implode( ',', $flags );
	}

	/**
	 * Builds unblock and block list links
	 *
	 * @return string
	 */
	private function getConvenienceLinks() {
		global $wgUser;
		$skin = $wgUser->getSkin();
		$links[] = $this->getUnblockLink( $skin );
		$links[] = $this->getBlockListLink( $skin );
		return '<p class="mw-ipb-conveniencelinks">' . implode( ' | ', $links ) . '</p>';
	}

	/**
	 * Build a convenient link to unblock the given username or IP
	 * address, if available; otherwise link to a blank unblock
	 * form
	 *
	 * @param $skin Skin to use
	 * @return string
	 */
	private function getUnblockLink( $skin ) {
		$list = SpecialPage::getTitleFor( 'Ipblocklist' );
		if( $this->BlockAddress ) {
			$addr = htmlspecialchars( strtr( $this->BlockAddress, '_', ' ' ) );
			return $skin->makeKnownLinkObj( $list, wfMsgHtml( 'ipb-unblock-addr', $addr ),
				'action=unblock&ip=' . urlencode( $this->BlockAddress ) );
		} else {
			return $skin->makeKnownLinkObj( $list, wfMsgHtml( 'ipb-unblock' ),	'action=unblock' );
		}
	}

	/**
	 * Build a convenience link to the block list
	 *
	 * @param $skin Skin to use
	 * @return string
	 */
	private function getBlockListLink( $skin ) {
		$list = SpecialPage::getTitleFor( 'Ipblocklist' );
		if( $this->BlockAddress ) {
			$addr = htmlspecialchars( strtr( $this->BlockAddress, '_', ' ' ) );
			return $skin->makeKnownLinkObj( $list, wfMsgHtml( 'ipb-blocklist-addr', $addr ),
				'ip=' . urlencode( $this->BlockAddress ) );
		} else {
			return $skin->makeKnownLinkObj( $list, wfMsgHtml( 'ipb-blocklist' ) );
		}
	}
}
?>
