<?php
/**
 * Constructor for Special:Blockip page
 *
 * @file
 * @ingroup SpecialPage
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
 * @ingroup SpecialPage
 */
class IPBlockForm {
	var $BlockAddress, $BlockExpiry, $BlockReason;
#	var $BlockEmail;

	function IPBlockForm( $par ) {
		global $wgRequest, $wgUser, $wgBlockAllowsUTEdit;

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
		$this->BlockEmail = $wgRequest->getBool( 'wpEmailBan', false );
		$this->BlockWatchUser = $wgRequest->getBool( 'wpWatchUser', false );
		# Re-check user's rights to hide names, very serious, defaults to 0
		$this->BlockHideName = ( $wgRequest->getBool( 'wpHideName', 0 ) && $wgUser->isAllowed( 'hideuser' ) ) ? 1 : 0;
		$this->BlockAllowUsertalk = ( $wgRequest->getBool( 'wpAllowUsertalk', $byDefault ) && $wgBlockAllowsUTEdit );
		$this->BlockReblock = $wgRequest->getBool( 'wpChangeBlock', false );
	}

	function showForm( $err ) {
		global $wgOut, $wgUser, $wgSysopUserBans;

		$wgOut->setPagetitle( wfMsg( 'blockip' ) );
		$wgOut->addWikiMsg( 'blockiptext' );

		if($wgSysopUserBans) {
			$mIpaddress = Xml::label( wfMsg( 'ipadressorusername' ), 'mw-bi-target' );
		} else {
			$mIpaddress = Xml::label( wfMsg( 'ipaddress' ), 'mw-bi-target' );
		}
		$mIpbexpiry = Xml::label( wfMsg( 'ipbexpiry' ), 'wpBlockExpiry' );
		$mIpbother = Xml::label( wfMsg( 'ipbother' ), 'mw-bi-other' );
		$mIpbreasonother = Xml::label( wfMsg( 'ipbreason' ), 'wpBlockReasonList' );
		$mIpbreason = Xml::label( wfMsg( 'ipbotherreason' ), 'mw-bi-reason' );

		$titleObj = SpecialPage::getTitleFor( 'Blockip' );
		$user = User::newFromName( $this->BlockAddress );
		
		$alreadyBlocked = false;
		if ( $err && $err[0] != 'ipb_already_blocked' ) {
			$key = array_shift($err);
			$msg = wfMsgReal($key, $err);
			$wgOut->setSubtitle( wfMsgHtml( 'formerror' ) );
			$wgOut->addHTML( Xml::tags( 'p', array( 'class' => 'error' ), $msg ) );
		} elseif ( $this->BlockAddress ) {
			$userId = 0;
			if ( is_object( $user ) )
				$userId = $user->getId();
			$currentBlock = Block::newFromDB( $this->BlockAddress, $userId );
			if ( !is_null($currentBlock) && !$currentBlock->mAuto && # The block exists and isn't an autoblock
				( $currentBlock->mRangeStart == $currentBlock->mRangeEnd || # The block isn't a rangeblock
				# or if it is, the range is what we're about to block
				( $currentBlock->mAddress == $this->BlockAddress ) ) ) {
					$wgOut->addWikiMsg( 'ipb-needreblock', $this->BlockAddress );
					$alreadyBlocked = true;
					# Set the block form settings to the existing block
					$this->BlockAnonOnly = $currentBlock->mAnonOnly;
					$this->BlockCreateAccount = $currentBlock->mCreateAccount;
					$this->BlockEnableAutoblock = $currentBlock->mEnableAutoblock;
					$this->BlockEmail = $currentBlock->mBlockEmail;
					$this->BlockHideName = $currentBlock->mHideName;
					$this->BlockAllowUsertalk = $currentBlock->mAllowUsertalk;
					if( $currentBlock->mExpiry == 'infinity' ) {
						$this->BlockOther = 'indefinite';
					} else {
						$this->BlockOther = wfTimestamp( TS_ISO_8601, $currentBlock->mExpiry );
					}
					$this->BlockReason = $currentBlock->mReason;
			}
		}

		$scBlockExpiryOptions = wfMsgForContent( 'ipboptions' );

		$showblockoptions = $scBlockExpiryOptions != '-';
		if (!$showblockoptions)
			$mIpbother = $mIpbexpiry;

		$blockExpiryFormOptions = Xml::option( wfMsg( 'ipbotheroption' ), 'other' );
		foreach (explode(',', $scBlockExpiryOptions) as $option) {
			if ( strpos($option, ":") === false ) $option = "$option:$option";
			list($show, $value) = explode(":", $option);
			$show = htmlspecialchars($show);
			$value = htmlspecialchars($value);
			$blockExpiryFormOptions .= Xml::option( $show, $value, $this->BlockExpiry === $value ? true : false ) . "\n";
		}

		$reasonDropDown = Xml::listDropDown( 'wpBlockReasonList',
			wfMsgForContent( 'ipbreason-dropdown' ),
			wfMsgForContent( 'ipbreasonotherlist' ), $this->BlockReasonList, 'wpBlockDropDown', 4 );

		global $wgStylePath, $wgStyleVersion;
		$wgOut->addHTML(
			Xml::tags( 'script', array( 'type' => 'text/javascript', 'src' => "$wgStylePath/common/block.js?$wgStyleVersion" ), '' ) .
			Xml::openElement( 'form', array( 'method' => 'post', 'action' => $titleObj->getLocalURL( "action=submit" ), 'id' => 'blockip' ) ) .
			Xml::openElement( 'fieldset' ) .
			Xml::element( 'legend', null, wfMsg( 'blockip-legend' ) ) .
			Xml::openElement( 'table', array ( 'border' => '0', 'id' => 'mw-blockip-table' ) ) .
			"<tr>
				<td class='mw-label'>
					{$mIpaddress}
				</td>
				<td class='mw-input'>" .
					Xml::input( 'wpBlockAddress', 45, $this->BlockAddress,
						array(
							'tabindex' => '1',
							'id' => 'mw-bi-target',
							'onchange' => 'updateBlockOptions()' ) ). "
				</td>
			</tr>
			<tr>"
		);
		if ( $showblockoptions ) {
			$wgOut->addHTML("
				<td class='mw-label'>
					{$mIpbexpiry}
				</td>
				<td class='mw-input'>" .
					Xml::tags( 'select',
						array(
							'id' => 'wpBlockExpiry',
							'name' => 'wpBlockExpiry',
							'onchange' => 'considerChangingExpiryFocus()',
							'tabindex' => '2' ),
						$blockExpiryFormOptions ) .
				"</td>"
			);
		}
		$wgOut->addHTML("
			</tr>
			<tr id='wpBlockOther'>
				<td class='mw-label'>
					{$mIpbother}
				</td>
				<td class='mw-input'>" .
					Xml::input( 'wpBlockOther', 45, $this->BlockOther,
						array( 'tabindex' => '3', 'id' => 'mw-bi-other' ) ) . "
				</td>
			</tr>
			<tr>
				<td class='mw-label'>
					{$mIpbreasonother}
				</td>
				<td class='mw-input'>
					{$reasonDropDown}
				</td>
			</tr>
			<tr id=\"wpBlockReason\">
				<td class='mw-label'>
					{$mIpbreason}
				</td>
				<td class='mw-input'>" .
					Xml::input( 'wpBlockReason', 45, $this->BlockReason,
						array( 'tabindex' => '5', 'id' => 'mw-bi-reason', 'maxlength'=> '200' ) ) . "
				</td>
			</tr>
			<tr id='wpAnonOnlyRow'>
				<td>&nbsp;</td>
				<td class='mw-input'>" .
				Xml::checkLabel( wfMsg( 'ipbanononly' ),
						'wpAnonOnly', 'wpAnonOnly', $this->BlockAnonOnly,
						array( 'tabindex' => '6' ) ) . "
				</td>
			</tr>
			<tr id='wpCreateAccountRow'>
				<td>&nbsp;</td>
				<td class='mw-input'>" .
					Xml::checkLabel( wfMsg( 'ipbcreateaccount' ),
						'wpCreateAccount', 'wpCreateAccount', $this->BlockCreateAccount,
						array( 'tabindex' => '7' ) ) . "
				</td>
			</tr>
			<tr id='wpEnableAutoblockRow'>
				<td>&nbsp;</td>
				<td class='mw-input'>" .
					Xml::checkLabel( wfMsg( 'ipbenableautoblock' ),
						'wpEnableAutoblock', 'wpEnableAutoblock', $this->BlockEnableAutoblock,
						array( 'tabindex' => '8' ) ) . "
				</td>
			</tr>"
		);

		global $wgSysopEmailBans, $wgBlockAllowsUTEdit;
		if ( $wgSysopEmailBans && $wgUser->isAllowed( 'blockemail' ) ) {
			$wgOut->addHTML("
				<tr id='wpEnableEmailBan'>
					<td>&nbsp;</td>
					<td class='mw-input'>" .
						Xml::checkLabel( wfMsg( 'ipbemailban' ),
							'wpEmailBan', 'wpEmailBan', $this->BlockEmail,
							array( 'tabindex' => '9' )) . "
					</td>
				</tr>"
			);
		}

		// Allow some users to hide name from block log, blocklist and listusers
		if ( $wgUser->isAllowed( 'hideuser' ) ) {
			$wgOut->addHTML("
				<tr id='wpEnableHideUser'>
					<td>&nbsp;</td>
					<td class='mw-input'>" .
						Xml::checkLabel( wfMsg( 'ipbhidename' ),
							'wpHideName', 'wpHideName', $this->BlockHideName,
							array( 'tabindex' => '10' ) ) . "
					</td>
				</tr>"
			);
		}
		
		# Watchlist their user page?
		$wgOut->addHTML("
			<tr id='wpEnableWatchUser'>
				<td>&nbsp;</td>
				<td class='mw-input'>" .
					Xml::checkLabel( wfMsg( 'ipbwatchuser' ),
						'wpWatchUser', 'wpWatchUser', $this->BlockWatchUser,
						array( 'tabindex' => '11' ) ) . "
				</td>
			</tr>"
		);
		if( $wgBlockAllowsUTEdit ){
			$wgOut->addHTML("
				<tr id='wpAllowUsertalkRow'>
					<td>&nbsp;</td>
					<td class='mw-input'>" .
						Xml::checkLabel( wfMsg( 'ipballowusertalk' ),
							'wpAllowUsertalk', 'wpAllowUsertalk', $this->BlockAllowUsertalk,
							array( 'tabindex' => '12' ) ) . "
					</td>
				</tr>"
			);
		}

		$wgOut->addHTML("
			<tr>
				<td style='padding-top: 1em'>&nbsp;</td>
				<td  class='mw-submit' style='padding-top: 1em'>" .
					Xml::submitButton( wfMsg( $alreadyBlocked ? 'ipb-change-block' : 'ipbsubmit' ),
						array( 'name' => 'wpBlock', 'tabindex' => '13', 'accesskey' => 's' ) ) . "
				</td>
			</tr>" .
			Xml::closeElement( 'table' ) .
			Xml::hidden( 'wpEditToken', $wgUser->editToken() ) .
			( $alreadyBlocked ? Xml::hidden( 'wpChangeBlock', 1 ) : "" ) .
			Xml::closeElement( 'fieldset' ) .
			Xml::closeElement( 'form' ) .
			Xml::tags( 'script', array( 'type' => 'text/javascript' ), 'updateBlockOptions()' ) . "\n"
		);

		$wgOut->addHTML( $this->getConvenienceLinks() );

		if( is_object( $user ) ) {
			$this->showLogFragment( $wgOut, $user->getUserPage() );
		} elseif( preg_match( '/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}/', $this->BlockAddress ) ) {
			$this->showLogFragment( $wgOut, Title::makeTitle( NS_USER, $this->BlockAddress ) );
		} elseif( preg_match( '/^\w{1,4}:\w{1,4}:\w{1,4}:\w{1,4}:\w{1,4}:\w{1,4}:\w{1,4}:\w{1,4}/', $this->BlockAddress ) ) {
			$this->showLogFragment( $wgOut, Title::makeTitle( NS_USER, $this->BlockAddress ) );
		}
	}

	/**
	 * Backend block code.
	 * $userID and $expiry will be filled accordingly
	 * @return array(message key, arguments) on failure, empty array on success
	 */
	function doBlock( &$userId = null, &$expiry = null ) {
		global $wgUser, $wgSysopUserBans, $wgSysopRangeBans, $wgBlockAllowsUTEdit;

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
						return array('ip_range_invalid');
					}
					$this->BlockAddress = Block::normaliseRange( $this->BlockAddress );
				} else {
					# Range block illegal
					return array('range_block_disabled');
				}
			} else if ( preg_match( "/^($rxIP6)\\/(\\d{1,3})$/", $this->BlockAddress, $matches ) ) {
		  		# IPv6
				if ( $wgSysopRangeBans ) {
					if ( !IP::isIPv6( $this->BlockAddress ) || $matches[2] < 64 || $matches[2] > 128 ) {
						return array('ip_range_invalid');
					}
					$this->BlockAddress = Block::normaliseRange( $this->BlockAddress );
				} else {
					# Range block illegal
					return array('range_block_disabled');
				}
			} else {
				# Username block
				if ( $wgSysopUserBans ) {
					$user = User::newFromName( $this->BlockAddress );
					if( !is_null( $user ) && $user->getId() ) {
						# Use canonical name
						$userId = $user->getId();
						$this->BlockAddress = $user->getName();
					} else {
						return array('nosuchusershort', htmlspecialchars( $user ? $user->getName() : $this->BlockAddress ) );
					}
				} else {
					return array('badipaddress');
				}
			}
		}

		if ( $wgUser->isBlocked() && ( $wgUser->getId() !== $userId ) ) {
			return array( 'cant-block-while-blocked' );
		}

		$reasonstr = $this->BlockReasonList;
		if ( $reasonstr != 'other' && $this->BlockReason != '' ) {
			// Entry from drop down menu + additional comment
			$reasonstr .= wfMsgForContent( 'colon-separator' ) . $this->BlockReason;
		} elseif ( $reasonstr == 'other' ) {
			$reasonstr = $this->BlockReason;
		}

		$expirestr = $this->BlockExpiry;
		if( $expirestr == 'other' )
			$expirestr = $this->BlockOther;

		if ( ( strlen( $expirestr ) == 0) || ( strlen( $expirestr ) > 50) ) {
			return array('ipb_expiry_invalid');
		}
		
		if ( false === ($expiry = Block::parseExpiryInput( $expirestr )) ) {
			// Bad expiry.
			return array('ipb_expiry_invalid');
		}
		
		if( $this->BlockHideName && $expiry != 'infinity' ) {
			// Bad expiry.
			return array('ipb_expiry_temp');
		}

		# Create block
		# Note: for a user block, ipb_address is only for display purposes
		$block = new Block( $this->BlockAddress, $userId, $wgUser->getId(),
			$reasonstr, wfTimestampNow(), 0, $expiry, $this->BlockAnonOnly,
			$this->BlockCreateAccount, $this->BlockEnableAutoblock, $this->BlockHideName,
			$this->BlockEmail, isset( $this->BlockAllowUsertalk ) ? $this->BlockAllowUsertalk : $wgBlockAllowsUTEdit
		);

		# Should this be privately logged?
		$suppressLog = (bool)$this->BlockHideName;
		if ( wfRunHooks('BlockIp', array(&$block, &$wgUser)) ) {

			if ( !$block->insert() ) {
				if ( !$this->BlockReblock ) {
					return array( 'ipb_already_blocked' );
				} else {
					# This returns direct blocks before autoblocks/rangeblocks, since we should
					# be sure the user is blocked by now it should work for our purposes
					$currentBlock = Block::newFromDB( $this->BlockAddress, $userId );
					if( $block->equals( $currentBlock ) ) {
						return array( 'ipb_already_blocked' );
					}
					$suppressLog = $suppressLog || (bool)$currentBlock->mHideName;
					$currentBlock->delete();
					$block->insert();
					$log_action = 'reblock';
				}
			} else {
				$log_action = 'block';
			}
			wfRunHooks('BlockIpComplete', array($block, $wgUser));

			if ( $this->BlockWatchUser &&
				# Only show watch link when this is no range block
				$block->mRangeStart == $block->mRangeEnd) {
				$wgUser->addWatch ( Title::makeTitle( NS_USER, $this->BlockAddress ) );
			}
			
			# Block constructor sanitizes certain block options on insert
			$this->BlockEmail = $block->mBlockEmail;
			$this->BlockEnableAutoblock = $block->mEnableAutoblock;

			# Prepare log parameters
			$logParams = array();
			$logParams[] = $expirestr;
			$logParams[] = $this->blockLogFlags();

			# Make log entry, if the name is hidden, put it in the oversight log
			$log_type = $suppressLog ? 'suppress' : 'block';
			$log = new LogPage( $log_type );
			$log->addEntry( $log_action, Title::makeTitle( NS_USER, $this->BlockAddress ),
			  $reasonstr, $logParams );

			# Report to the user
			return array();
		}
		else
			return array('hookaborted');
	}

	/**
	 * UI entry point for blocking
	 * Wraps around doBlock()
	 */
	function doSubmit()
	{
		global $wgOut;
		$retval = $this->doBlock();
		if(empty($retval)) {
			$titleObj = SpecialPage::getTitleFor( 'Blockip' );
			$wgOut->redirect( $titleObj->getFullURL( 'action=success&ip=' .
				urlencode( $this->BlockAddress ) ) );
			return;
		}
		$this->showForm( $retval );
	}

	function showSuccess() {
		global $wgOut;

		$wgOut->setPagetitle( wfMsg( 'blockip' ) );
		$wgOut->setSubtitle( wfMsg( 'blockipsuccesssub' ) );
		$text = wfMsgExt( 'blockipsuccesstext', array( 'parse' ), $this->BlockAddress );
		$wgOut->addHTML( $text );
	}

	function showLogFragment( $out, $title ) {
		global $wgUser;
		$out->addHTML( Xml::element( 'h2', NULL, LogPage::logName( 'block' ) ) );
		$count = LogEventsList::showLogExtract( $out, 'block', $title->getPrefixedText(), '', 10 );
		if($count > 10){
			$out->addHTML( $wgUser->getSkin()->link(
				SpecialPage::getTitleFor( 'Log' ),
				wfMsgHtml( 'blocklog-fulllog' ),
				array(),
				array(
					'type' => 'block',
					'page' => $title->getPrefixedText() ) ) );
		}
	}

	/**
	 * Return a comma-delimited list of "flags" to be passed to the log
	 * reader for this block, to provide more information in the logs
	 *
	 * @return array
	 */
	private function blockLogFlags() {
		global $wgBlockAllowsUTEdit;
		$flags = array();
		if( $this->BlockAnonOnly && IP::isIPAddress( $this->BlockAddress ) )
			// when blocking a user the option 'anononly' is not available/has no effect -> do not write this into log
			$flags[] = 'anononly';
		if( $this->BlockCreateAccount )
			$flags[] = 'nocreate';
		if( !$this->BlockEnableAutoblock )
			$flags[] = 'noautoblock';
		if ( $this->BlockEmail )
			$flags[] = 'noemail';
		if ( !$this->BlockAllowUsertalk && $wgBlockAllowsUTEdit )
			$flags[] = 'nousertalk';
		if ( $this->BlockHideName )
			$flags[] = 'hiddenname';
		return implode( ',', $flags );
	}

	/**
	 * Builds unblock and block list links
	 *
	 * @return string
	 */
	private function getConvenienceLinks() {
		global $wgUser, $wgLang;
		$skin = $wgUser->getSkin();
		if( $this->BlockAddress )
			$links[] = $this->getContribsLink( $skin );
		$links[] = $this->getUnblockLink( $skin );
		$links[] = $this->getBlockListLink( $skin );
		$links[] = $skin->makeLink ( 'MediaWiki:Ipbreason-dropdown', wfMsgHtml( 'ipb-edit-dropdown' ) );
		return '<p class="mw-ipb-conveniencelinks">' . $wgLang->pipeList( $links ) . '</p>';
	}
	
	/**
	 * Build a convenient link to a user or IP's contribs
	 * form
	 *
	 * @param $skin Skin to use
	 * @return string
	 */
	private function getContribsLink( $skin ) {
		$contribsPage = SpecialPage::getTitleFor( 'Contributions', $this->BlockAddress );
		return $skin->link( $contribsPage, wfMsgHtml( 'ipb-blocklist-contribs', $this->BlockAddress ) );
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
	
	/**
	* Block a list of selected users
	* @param array $users
	* @param string $reason
	* @param string $tag replaces user pages
	* @param string $talkTag replaces user talk pages
	* @returns array, list of html-safe usernames
	*/
	public static function doMassUserBlock( $users, $reason = '', $tag = '', $talkTag = '' ) {
		global $wgUser;
		$counter = $blockSize = 0;
		$safeUsers = array();
		$log = new LogPage( 'block' );
		foreach( $users as $name ) {
			# Enforce limits
			$counter++;
			$blockSize++;
			# Lets not go *too* fast
			if( $blockSize >= 20 ) {
				$blockSize = 0;
				wfWaitForSlaves( 5 );
			}
			$u = User::newFromName( $name, false );
			// If user doesn't exist, it ought to be an IP then
			if( is_null($u) || (!$u->getId() && !IP::isIPAddress( $u->getName() )) ) {
				continue;
			}
			$userTitle = $u->getUserPage();
			$userTalkTitle = $u->getTalkPage();
			$userpage = new Article( $userTitle );
			$usertalk = new Article( $userTalkTitle );
			$safeUsers[] = '[[' . $userTitle->getPrefixedText() . '|' . $userTitle->getText() . ']]';
			$expirestr = $u->getId() ? 'indefinite' : '1 week';
			$expiry = Block::parseExpiryInput( $expirestr );
			$anonOnly = IP::isIPAddress( $u->getName() ) ? 1 : 0;
			// Create the block
			$block = new Block( $u->getName(), // victim
				$u->getId(), // uid
				$wgUser->getId(), // blocker
				$reason, // comment
				wfTimestampNow(), // block time
				0, // auto ?
				$expiry, // duration
				$anonOnly, // anononly?
				1, // block account creation?
				1, // autoblocking?
				0, // suppress name?
				0 // block from sending email?
			);
			$oldblock = Block::newFromDB( $u->getName(), $u->getId() );
			if( !$oldblock ) {
				$block->insert();
				# Prepare log parameters
				$logParams = array();
				$logParams[] = $expirestr;
				if( $anonOnly ) {
					$logParams[] = 'anononly';
				}
				$logParams[] = 'nocreate';
				# Add log entry
				$log->addEntry( 'block', $userTitle, $reason, $logParams );
			}
			# Tag userpage! (check length to avoid mistakes)
			if( strlen($tag) > 2 ) {
				$userpage->doEdit( $tag, $reason, EDIT_MINOR );
			}
			if( strlen($talkTag) > 2 ) {
				$usertalk->doEdit( $talkTag, $reason, EDIT_MINOR );
			}
		}
		return $safeUsers;
	}
}
