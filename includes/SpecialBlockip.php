<?php
/**
 * Constructor for Special:Blockip page
 *
 * @addtogroup SpecialPage
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
 * Form object for the Special:Blockip page.
 *
 * @addtogroup SpecialPage
 */
class IPBlockForm {
	var $BlockAddress, $BlockExpiry, $BlockReason;

	function IPBlockForm( $par ) {
		global $wgRequest, $wgUser;

		$this->BlockAddress = $wgRequest->getVal( 'wpBlockAddress', $wgRequest->getVal( 'ip', $par ) );
		$this->BlockAddress = strtr( $this->BlockAddress, '_', ' ' );
		$this->BlockReason = $wgRequest->getText( 'wpBlockReason' );
		$this->BlockReasonList = $wgRequest->getText( 'wpBlockReasonList' );
		$this->BlockExpiry = $wgRequest->getVal( 'wpBlockExpiry', wfMsg('ipbotheroption') );
		$this->BlockOther = $wgRequest->getVal( 'wpBlockOther', '' );

		# Unchecked checkboxes are not included in the form data at all, so having one
		# that is true by default is a bit tricky
		$byDefault = !$wgRequest->wasPosted();
		$this->BlockAnonOnly = $wgRequest->getBool( 'wpAnonOnly', $byDefault );
		$this->BlockCreateAccount = $wgRequest->getBool( 'wpCreateAccount', $byDefault );
		$this->BlockEnableAutoblock = $wgRequest->getBool( 'wpEnableAutoblock', $byDefault );
		# Re-check user's rights to hide names, very serious, defaults to 0
		$this->BlockHideName = ( $wgRequest->getBool( 'wpHideName', 0 ) && $wgUser->isAllowed( 'hideuser' ) ) ? 1 : 0;
	}

	function showForm( $err ) {
		global $wgOut, $wgUser, $wgSysopUserBans, $wgContLang;

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
		$mIpbreasonother = Xml::label( wfMsg( 'ipbreason' ), 'wpBlockReasonList' );
		$mIpbreason = Xml::label( wfMsg( 'ipbotherreason' ), 'mw-bi-reason' );
		$mIpbreasonotherlist = wfMsgHtml( 'ipbreasonotherlist' );

		$titleObj = SpecialPage::getTitleFor( 'Blockip' );
		$action = $titleObj->escapeLocalURL( "action=submit" );
		$alignRight = $wgContLang->isRtl() ? 'left' : 'right';

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

		$scBlockReasonList = wfMsgForContent( 'ipbreason-dropdown' );
		$blockReasonList = '';
		if ( $scBlockReasonList != '' && $scBlockReasonList != '-' ) { 
			$blockReasonList = "<option value=\"other\">$mIpbreasonotherlist</option>";
			$optgroup = "";
			foreach ( explode( "\n", $scBlockReasonList ) as $option) {
				$value = trim( htmlspecialchars($option) );
				if ( $value == '' ) {
					continue;
				} elseif ( substr( $value, 0, 1) == '*' && substr( $value, 1, 1) != '*' ) {
					// A new group is starting ...
					$value = trim( substr( $value, 1 ) );
					$blockReasonList .= "$optgroup<optgroup label=\"$value\">";
					$optgroup = "</optgroup>";
				} elseif ( substr( $value, 0, 2) == '**' ) {
					// groupmember
					$selected = "";
					$value = trim( substr( $value, 2 ) );
					if ( $this->BlockReasonList === $value)
						$selected = ' selected="selected"';
					$blockReasonList .= "<option value=\"$value\"$selected>$value</option>";
				} else {
					// groupless block reason
					$selected = "";
					if ( $this->BlockReasonList === $value)
						$selected = ' selected="selected"';
					$blockReasonList .= "$optgroup<option value=\"$value\"$selected>$value</option>";
					$optgroup = "";
				}
			}
			$blockReasonList .= $optgroup;
		}

		$token = htmlspecialchars( $wgUser->editToken() );

		global $wgStylePath, $wgStyleVersion;
		$wgOut->addHTML( "
<script type=\"text/javascript\" src=\"$wgStylePath/common/block.js?$wgStyleVersion\">
</script>
<form id=\"blockip\" method=\"post\" action=\"{$action}\">
	<table border='0'>
		<tr>
			<td align=\"$alignRight\">{$mIpaddress}</td>
			<td>
				" . Xml::input( 'wpBlockAddress', 45, $this->BlockAddress,
					array(
						'tabindex' => '1',
						'id' => 'mw-bi-target',
						'onchange' => 'updateBlockOptions()' ) ) . "
			</td>
		</tr>
		<tr>");
		if ($showblockoptions) {
			$wgOut->addHTML("
			<td align=\"$alignRight\">{$mIpbexpiry}</td>
			<td>
				<select tabindex='2' id='wpBlockExpiry' name=\"wpBlockExpiry\" onchange=\"considerChangingExpiryFocus()\">
					$blockExpiryFormOptions
				</select>
			</td>
			");
		}
		$wgOut->addHTML("
		</tr>
		<tr id='wpBlockOther'>
			<td align=\"$alignRight\">{$mIpbother}</td>
			<td>
				" . Xml::input( 'wpBlockOther', 45, $this->BlockOther,
					array( 'tabindex' => '3', 'id' => 'mw-bi-other' ) ) . "
			</td>
		</tr>");
		if ( $blockReasonList != '' ) {
			$wgOut->addHTML("
			<tr>
				<td align=\"$alignRight\">{$mIpbreasonother}</td>
				<td>
					<select tabindex='4' id=\"wpBlockReasonList\" name=\"wpBlockReasonList\">
						$blockReasonList
						</select>
				</td>
			</tr>");
		}
		$wgOut->addHTML("
		<tr id=\"wpBlockReason\">
			<td align=\"$alignRight\">{$mIpbreason}</td>
			<td>
				" . Xml::input( 'wpBlockReason', 45, $this->BlockReason,
					array( 'tabindex' => '5', 'id' => 'mw-bi-reason',
			       		       'maxlength'=> '200' ) ) . "
			</td>
		</tr>
		<tr id='wpAnonOnlyRow'>
			<td>&nbsp;</td>
			<td>
				" . wfCheckLabel( wfMsgHtml( 'ipbanononly' ),
					'wpAnonOnly', 'wpAnonOnly', $this->BlockAnonOnly,
					array( 'tabindex' => '6' ) ) . "
			</td>
		</tr>
		<tr id='wpCreateAccountRow'>
			<td>&nbsp;</td>
			<td>
				" . wfCheckLabel( wfMsgHtml( 'ipbcreateaccount' ),
					'wpCreateAccount', 'wpCreateAccount', $this->BlockCreateAccount,
					array( 'tabindex' => '7' ) ) . "
			</td>
		</tr>
		<tr id='wpEnableAutoblockRow'>
			<td>&nbsp;</td>
			<td>
				" . wfCheckLabel( wfMsgHtml( 'ipbenableautoblock' ),
						'wpEnableAutoblock', 'wpEnableAutoblock', $this->BlockEnableAutoblock,
							array( 'tabindex' => '8' ) ) . "
			</td>
		</tr>
		");
		// Allow some users to hide name from block log, blocklist and listusers
		if ( $wgUser->isAllowed( 'hideuser' ) ) {
			$wgOut->addHTML("
			<tr>
			<td>&nbsp;</td>
				<td>
					" . wfCheckLabel( wfMsgHtml( 'ipbhidename' ),
							'wpHideName', 'wpHideName', $this->BlockHideName,
								array( 'tabindex' => '9' ) ) . "
				</td>
			</tr>
			");
		}
		$wgOut->addHTML("
		<tr>
			<td style='padding-top: 1em'>&nbsp;</td>
			<td style='padding-top: 1em'>
				" . Xml::submitButton( wfMsg( 'ipbsubmit' ),
							array( 'name' => 'wpBlock', 'tabindex' => '10' ) ) . "
			</td>
		</tr>
	</table>" .
	Xml::hidden( 'wpEditToken', $token ) .
"</form>
<script type=\"text/javascript\">updateBlockOptions()</script>
\n" );

		$wgOut->addHtml( $this->getConvenienceLinks() );

		$user = User::newFromName( $this->BlockAddress );
		if( is_object( $user ) ) {
			$this->showLogFragment( $wgOut, $user->getUserPage() );
		} elseif( preg_match( '/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}/', $this->BlockAddress ) ) {
			$this->showLogFragment( $wgOut, Title::makeTitle( NS_USER, $this->BlockAddress ) );
		} elseif( preg_match( '/^\w{1,4}:\w{1,4}:\w{1,4}:\w{1,4}:\w{1,4}:\w{1,4}:\w{1,4}:\w{1,4}/', $this->BlockAddress ) ) {
			$this->showLogFragment( $wgOut, Title::makeTitle( NS_USER, $this->BlockAddress ) );
		}
	}

	function doSubmit() {
		global $wgOut, $wgUser, $wgSysopUserBans, $wgSysopRangeBans;

		$userId = 0;
		# Expand valid IPv6 addresses, usernames are left as is
		$this->BlockAddress = IP::sanitizeIP( $this->BlockAddress );
		# isIPv4() and IPv6() are used for final validation
		$rxIP4 = '\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}';
		$rxIP6 = '\w{1,4}:\w{1,4}:\w{1,4}:\w{1,4}:\w{1,4}:\w{1,4}:\w{1,4}:\w{1,4}';
		$rxIP = "($rxIP4|$rxIP6)";
		
		# Check for invalid specifications
		if ( !preg_match( "/^$rxIP$/", $this->BlockAddress ) ) {
			$matches = array();
		  	if ( preg_match( "/^($rxIP4)\\/(\\d{1,2})$/", $this->BlockAddress, $matches ) ) {
		  		# IPv4
				if ( $wgSysopRangeBans ) {
					if ( !IP::isIPv4( $this->BlockAddress ) || $matches[2] < 16 || $matches[2] > 32 ) {
						$this->showForm( wfMsg( 'ip_range_invalid' ) );
						return;
					}
					$this->BlockAddress = Block::normaliseRange( $this->BlockAddress );
				} else {
					# Range block illegal
					$this->showForm( wfMsg( 'range_block_disabled' ) );
					return;
				}
			} else if ( preg_match( "/^($rxIP6)\\/(\\d{1,3})$/", $this->BlockAddress, $matches ) ) {
		  		# IPv6
				if ( $wgSysopRangeBans ) {
					if ( !IP::isIPv6( $this->BlockAddress ) || $matches[2] < 64 || $matches[2] > 128 ) {
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

		$reasonstr = $this->BlockReasonList;
		if ( $reasonstr != 'other' && $this->BlockReason != '') {
			// Entry from drop down menu + additional comment
			$reasonstr .= ': ' . $this->BlockReason;
		} elseif ( $reasonstr == 'other' ) {
			$reasonstr = $this->BlockReason;
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
			$reasonstr, wfTimestampNow(), 0, $expiry, $this->BlockAnonOnly,
			$this->BlockCreateAccount, $this->BlockEnableAutoblock, $this->BlockHideName);

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

			# Make log entry, if the name is hidden, put it in the oversight log
			$log_type = ($this->BlockHideName) ? 'oversight' : 'block';
			$log = new LogPage( $log_type );
			$log->addEntry( 'block', Title::makeTitle( NS_USER, $this->BlockAddress ),
			  $reasonstr, $logParams );

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
		if( $this->BlockAnonOnly && IP::isIPAddress( $this->BlockAddress ) )
					// when blocking a user the option 'anononly' is not available/has no effect -> do not write this into log
			$flags[] = 'anononly';
		if( $this->BlockCreateAccount )
			$flags[] = 'nocreate';
		if( !$this->BlockEnableAutoblock )
			$flags[] = 'noautoblock';
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
		$links[] = $skin->makeLink ( 'MediaWiki:ipbreason-dropdown', wfMsgHtml( 'ipb-edit-dropdown' ) );
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
