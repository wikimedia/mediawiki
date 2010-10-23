<?php
/**
 * Implements Special:Blockip
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 * @ingroup SpecialPage
 */

/**
 * A special page that allows users with 'block' right to block users from
 * editing pages and other actions
 *
 * @ingroup SpecialPage
 */
class IPBlockForm extends SpecialPage {
	var $BlockAddress, $BlockExpiry, $BlockReason;
	// The maximum number of edits a user can have and still be hidden
	const HIDEUSER_CONTRIBLIMIT = 1000;

	public function __construct() {
		parent::__construct( 'Blockip', 'block' );
	}

	public function execute( $par ) {
		global $wgUser, $wgOut, $wgRequest;

		# Can't block when the database is locked
		if( wfReadOnly() ) {
			$wgOut->readOnlyPage();
			return;
		}
		# Permission check
		if( !$this->userCanExecute( $wgUser ) ) {
			$wgOut->permissionRequired( 'block' );
			return;
		}

		$this->setup( $par );
	
		# bug 15810: blocked admins should have limited access here
		if ( $wgUser->isBlocked() ) {
			$status = IPBlockForm::checkUnblockSelf( $this->BlockAddress );
			if ( $status !== true ) {
				throw new ErrorPageError( 'badaccess', $status );
			}
		}

		$action = $wgRequest->getVal( 'action' );
		if( 'success' == $action ) {
			$this->showSuccess();
		} elseif( $wgRequest->wasPosted() && 'submit' == $action &&
			$wgUser->matchEditToken( $wgRequest->getVal( 'wpEditToken' ) ) ) {
			$this->doSubmit();
		} else {
			$this->showForm( '' );
		}
	}

	private function setup( $par ) {
		global $wgRequest, $wgUser, $wgBlockAllowsUTEdit;

		$this->BlockAddress = $wgRequest->getVal( 'wpBlockAddress', $wgRequest->getVal( 'ip', $par ) );
		$this->BlockAddress = strtr( $this->BlockAddress, '_', ' ' );
		$this->BlockReason = $wgRequest->getText( 'wpBlockReason' );
		$this->BlockReasonList = $wgRequest->getText( 'wpBlockReasonList' );
		$this->BlockExpiry = $wgRequest->getVal( 'wpBlockExpiry', wfMsg( 'ipbotheroption' ) );
		$this->BlockOther = $wgRequest->getVal( 'wpBlockOther', '' );

		# Unchecked checkboxes are not included in the form data at all, so having one
		# that is true by default is a bit tricky
		$byDefault = !$wgRequest->wasPosted();
		$this->BlockAnonOnly = $wgRequest->getBool( 'wpAnonOnly', $byDefault );
		$this->BlockCreateAccount = $wgRequest->getBool( 'wpCreateAccount', $byDefault );
		$this->BlockEnableAutoblock = $wgRequest->getBool( 'wpEnableAutoblock', $byDefault );
		$this->BlockEmail = false;
		if( self::canBlockEmail( $wgUser ) ) {
			$this->BlockEmail = $wgRequest->getBool( 'wpEmailBan', false );
		}
		$this->BlockWatchUser = $wgRequest->getBool( 'wpWatchUser', false ) && $wgUser->isLoggedIn();
		# Re-check user's rights to hide names, very serious, defaults to null
		if( $wgUser->isAllowed( 'hideuser' ) ) {
			$this->BlockHideName = $wgRequest->getBool( 'wpHideName', null );
		} else {
			$this->BlockHideName = false;
		}
		$this->BlockAllowUsertalk = ( $wgRequest->getBool( 'wpAllowUsertalk', $byDefault ) && $wgBlockAllowsUTEdit );
		$this->BlockReblock = $wgRequest->getBool( 'wpChangeBlock', false );
		
		$this->wasPosted = $wgRequest->wasPosted();
	}

	public function showForm( $err ) {
		global $wgOut, $wgUser, $wgSysopUserBans;

		$wgOut->setPageTitle( wfMsg( 'blockip-title' ) );
		$wgOut->addWikiMsg( 'blockiptext' );

		if( $wgSysopUserBans ) {
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
		$otherBlockedMsgs = array();
		if( $err && $err[0] != 'ipb_already_blocked' ) {
			$key = array_shift( $err );
			$msg = wfMsgReal( $key, $err );
			$wgOut->setSubtitle( wfMsgHtml( 'formerror' ) );
			$wgOut->addHTML( Xml::tags( 'p', array( 'class' => 'error' ), $msg ) );
		} elseif( $this->BlockAddress !== null ) {
			# Get other blocks, i.e. from GlobalBlocking or TorBlock extension
			wfRunHooks( 'OtherBlockLogLink', array( &$otherBlockedMsgs, $this->BlockAddress ) );

			$userId = is_object( $user ) ? $user->getId() : 0;
			$currentBlock = Block::newFromDB( $this->BlockAddress, $userId );
			if( !is_null( $currentBlock ) && !$currentBlock->mAuto && # The block exists and isn't an autoblock
				( $currentBlock->mRangeStart == $currentBlock->mRangeEnd || # The block isn't a rangeblock
				# or if it is, the range is what we're about to block
				( $currentBlock->mAddress == $this->BlockAddress ) )
			) {
				$alreadyBlocked = true;
				# Set the block form settings to the existing block
				if( !$this->wasPosted ) {
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
		}

		# Show other blocks from extensions, i.e. GlockBlocking and TorBlock
		if( count( $otherBlockedMsgs ) ) {
			$wgOut->addHTML(
				Html::rawElement( 'h2', array(), wfMsgExt( 'ipb-otherblocks-header', 'parseinline',  count( $otherBlockedMsgs ) ) ) . "\n"
			);
			$list = '';
			foreach( $otherBlockedMsgs as $link ) {
				$list .= Html::rawElement( 'li', array(), $link ) . "\n";
			}
			$wgOut->addHTML( Html::rawElement( 'ul', array( 'class' => 'mw-blockip-alreadyblocked' ), $list ) . "\n" );
		}

		# Username/IP is blocked already locally
		if( $alreadyBlocked ) {
			$wgOut->wrapWikiMsg( "<div class='mw-ipb-needreblock'>\n$1\n</div>", array( 'ipb-needreblock', $this->BlockAddress ) );
		}

		$scBlockExpiryOptions = wfMsgForContent( 'ipboptions' );

		$showblockoptions = $scBlockExpiryOptions != '-';
		if( !$showblockoptions ) $mIpbother = $mIpbexpiry;

		$blockExpiryFormOptions = Xml::option( wfMsg( 'ipbotheroption' ), 'other' );
		foreach( explode( ',', $scBlockExpiryOptions ) as $option ) {
			if( strpos( $option, ':' ) === false ) $option = "$option:$option";
			list( $show, $value ) = explode( ':', $option );
			$show = htmlspecialchars( $show );
			$value = htmlspecialchars( $value );
			$blockExpiryFormOptions .= Xml::option( $show, $value, $this->BlockExpiry === $value ) . "\n";
		}

		$reasonDropDown = Xml::listDropDown( 'wpBlockReasonList',
			wfMsgForContent( 'ipbreason-dropdown' ),
			wfMsgForContent( 'ipbreasonotherlist' ), $this->BlockReasonList, 'wpBlockDropDown', 4 );

		$wgOut->addModules( 'mediawiki.legacy.block' );
		$wgOut->addHTML(
			Xml::openElement( 'form', array( 'method' => 'post', 'action' => $titleObj->getLocalURL( 'action=submit' ), 'id' => 'blockip' ) ) .
			Xml::openElement( 'fieldset' ) .
			Xml::element( 'legend', null, wfMsg( 'blockip-legend' ) ) .
			Xml::openElement( 'table', array( 'border' => '0', 'id' => 'mw-blockip-table' ) ) .
			"<tr>
				<td class='mw-label'>
					{$mIpaddress}
				</td>
				<td class='mw-input'>" .
					Html::input( 'wpBlockAddress', $this->BlockAddress, 'text', array(
						'tabindex' => '1',
						'id' => 'mw-bi-target',
						'onchange' => 'updateBlockOptions()',
						'size' => '45',
						'required' => ''
					) + ( $this->BlockAddress ? array() : array( 'autofocus' ) ) ). "
				</td>
			</tr>
			<tr>"
		);
		if( $showblockoptions ) {
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
				Html::input( 'wpBlockReason', $this->BlockReason, 'text', array(
					'tabindex' => '5',
					'id' => 'mw-bi-reason',
					'maxlength' => '200',
					'size' => '45'
				) + ( $this->BlockAddress ? array( 'autofocus' ) : array() ) ) . "
				</td>
			</tr>
			<tr id='wpAnonOnlyRow'>
				<td>&#160;</td>
				<td class='mw-input'>" .
				Xml::checkLabel( wfMsg( 'ipbanononly' ),
						'wpAnonOnly', 'wpAnonOnly', $this->BlockAnonOnly,
						array( 'tabindex' => '6' ) ) . "
				</td>
			</tr>
			<tr id='wpCreateAccountRow'>
				<td>&#160;</td>
				<td class='mw-input'>" .
					Xml::checkLabel( wfMsg( 'ipbcreateaccount' ),
						'wpCreateAccount', 'wpCreateAccount', $this->BlockCreateAccount,
						array( 'tabindex' => '7' ) ) . "
				</td>
			</tr>
			<tr id='wpEnableAutoblockRow'>
				<td>&#160;</td>
				<td class='mw-input'>" .
					Xml::checkLabel( wfMsg( 'ipbenableautoblock' ),
						'wpEnableAutoblock', 'wpEnableAutoblock', $this->BlockEnableAutoblock,
						array( 'tabindex' => '8' ) ) . "
				</td>
			</tr>"
		);

		if( self::canBlockEmail( $wgUser ) ) {
			$wgOut->addHTML("
				<tr id='wpEnableEmailBan'>
					<td>&#160;</td>
					<td class='mw-input'>" .
						Xml::checkLabel( wfMsg( 'ipbemailban' ),
							'wpEmailBan', 'wpEmailBan', $this->BlockEmail,
							array( 'tabindex' => '9' ) ) . "
					</td>
				</tr>"
			);
		}

		// Allow some users to hide name from block log, blocklist and listusers
		if( $wgUser->isAllowed( 'hideuser' ) ) {
			$wgOut->addHTML("
				<tr id='wpEnableHideUser'>
					<td>&#160;</td>
					<td class='mw-input'><strong>" .
						Xml::checkLabel( wfMsg( 'ipbhidename' ),
							'wpHideName', 'wpHideName', $this->BlockHideName,
							array( 'tabindex' => '10' )
						) . "
					</strong></td>
				</tr>"
			);
		}

		# Watchlist their user page? (Only if user is logged in)
		if( $wgUser->isLoggedIn() ) {
			$wgOut->addHTML("
			<tr id='wpEnableWatchUser'>
				<td>&#160;</td>
				<td class='mw-input'>" .
					Xml::checkLabel( wfMsg( 'ipbwatchuser' ),
						'wpWatchUser', 'wpWatchUser', $this->BlockWatchUser,
						array( 'tabindex' => '11' ) ) . "
				</td>
			</tr>"
			);
		}

		# Can we explicitly disallow the use of user_talk?
		global $wgBlockAllowsUTEdit;
		if( $wgBlockAllowsUTEdit ){
			$wgOut->addHTML("
				<tr id='wpAllowUsertalkRow'>
					<td>&#160;</td>
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
				<td style='padding-top: 1em'>&#160;</td>
				<td  class='mw-submit' style='padding-top: 1em'>" .
					Xml::submitButton( wfMsg( $alreadyBlocked ? 'ipb-change-block' : 'ipbsubmit' ),
						array( 'name' => 'wpBlock', 'tabindex' => '13' )
							+ $wgUser->getSkin()->tooltipAndAccessKeyAttribs( 'blockip-block' ) ). "
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
	 * Can we do an email block?
	 * @param $user User: the sysop wanting to make a block
	 * @return Boolean
	 */
	public static function canBlockEmail( $user ) {
		global $wgEnableUserEmail, $wgSysopEmailBans;
		return ( $wgEnableUserEmail && $wgSysopEmailBans && $user->isAllowed( 'blockemail' ) );
	}
	
	/**
	 * bug 15810: blocked admins should not be able to block/unblock
	 * others, and probably shouldn't be able to unblock themselves
	 * either.
	 * @param $user User, Int or String
	 */
	public static function checkUnblockSelf( $user ) {
		global $wgUser;
		if ( is_int( $user ) ) {
			$user = User::newFromId( $user );
		} elseif ( is_string( $user ) ) {
			$user = User::newFromName( $user );
		}
		if( $user instanceof User && $user->getId() == $wgUser->getId() ) {
			# User is trying to unblock themselves
			if ( $wgUser->isAllowed( 'unblockself' ) ) {
				return true;
			} else {
				return 'ipbnounblockself';
			}
		} else {
			# User is trying to block/unblock someone else
			return 'ipbblocked';
		}
	}

	/**
	 * Backend block code.
	 * $userID and $expiry will be filled accordingly
	 * @return array(message key, arguments) on failure, empty array on success
	 */
	function doBlock( &$userId = null, &$expiry = null ) {
		global $wgUser, $wgSysopUserBans, $wgSysopRangeBans, $wgBlockAllowsUTEdit, $wgBlockCIDRLimit;

		$userId = 0;
		# Expand valid IPv6 addresses, usernames are left as is
		$this->BlockAddress = IP::sanitizeIP( $this->BlockAddress );
		# isIPv4() and IPv6() are used for final validation
		$rxIP4 = '\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}';
		$rxIP6 = '\w{1,4}:\w{1,4}:\w{1,4}:\w{1,4}:\w{1,4}:\w{1,4}:\w{1,4}:\w{1,4}';
		$rxIP = "($rxIP4|$rxIP6)";

		# Check for invalid specifications
		if( !preg_match( "/^$rxIP$/", $this->BlockAddress ) ) {
			$matches = array();
		  	if( preg_match( "/^($rxIP4)\\/(\\d{1,2})$/", $this->BlockAddress, $matches ) ) {
		  		# IPv4
				if( $wgSysopRangeBans ) {
					if( !IP::isIPv4( $this->BlockAddress ) || $matches[2] > 32 ) {
						return array( 'ip_range_invalid' );
					} elseif ( $matches[2] < $wgBlockCIDRLimit['IPv4'] ) {
						return array( 'ip_range_toolarge', $wgBlockCIDRLimit['IPv4'] );
					}
					$this->BlockAddress = Block::normaliseRange( $this->BlockAddress );
				} else {
					# Range block illegal
					return array( 'range_block_disabled' );
				}
			} elseif( preg_match( "/^($rxIP6)\\/(\\d{1,3})$/", $this->BlockAddress, $matches ) ) {
		  		# IPv6
				if( $wgSysopRangeBans ) {
					if( !IP::isIPv6( $this->BlockAddress ) || $matches[2] > 128 ) {
						return array( 'ip_range_invalid' );
					} elseif( $matches[2] < $wgBlockCIDRLimit['IPv6'] ) {
						return array( 'ip_range_toolarge', $wgBlockCIDRLimit['IPv6'] );
					}
					$this->BlockAddress = Block::normaliseRange( $this->BlockAddress );
				} else {
					# Range block illegal
					return array('range_block_disabled');
				}
			} else {
				# Username block
				if( $wgSysopUserBans ) {
					$user = User::newFromName( $this->BlockAddress );
					if( $user instanceof User && $user->getId() ) {
						# Use canonical name
						$userId = $user->getId();
						$this->BlockAddress = $user->getName();
					} else {
						return array( 'nosuchusershort', htmlspecialchars( $user ? $user->getName() : $this->BlockAddress ) );
					}
				} else {
					return array( 'badipaddress' );
				}
			}
		}

		if( $wgUser->isBlocked() && ( $wgUser->getId() !== $userId ) ) {
			return array( 'cant-block-while-blocked' );
		}

		$reasonstr = $this->BlockReasonList;
		if( $reasonstr != 'other' && $this->BlockReason != '' ) {
			// Entry from drop down menu + additional comment
			$reasonstr .= wfMsgForContent( 'colon-separator' ) . $this->BlockReason;
		} elseif( $reasonstr == 'other' ) {
			$reasonstr = $this->BlockReason;
		}

		$expirestr = $this->BlockExpiry;
		if( $expirestr == 'other' )
			$expirestr = $this->BlockOther;

		if( ( strlen( $expirestr ) == 0) || ( strlen( $expirestr ) > 50 ) ) {
			return array( 'ipb_expiry_invalid' );
		}
		
		if( false === ( $expiry = Block::parseExpiryInput( $expirestr ) ) ) {
			// Bad expiry.
			return array( 'ipb_expiry_invalid' );
		}

		if( $this->BlockHideName ) {
			// Recheck params here...
			if( !$userId || !$wgUser->isAllowed('hideuser') ) {
				$this->BlockHideName = false; // IP users should not be hidden
			} elseif( $expiry !== 'infinity' ) {
				// Bad expiry.
				return array( 'ipb_expiry_temp' );
			} elseif( User::edits( $userId ) > self::HIDEUSER_CONTRIBLIMIT ) {
				// Typically, the user should have a handful of edits.
				// Disallow hiding users with many edits for performance.
				return array( 'ipb_hide_invalid' );
			}
		}

		# Create block object
		# Note: for a user block, ipb_address is only for display purposes
		$block = new Block( $this->BlockAddress, $userId, $wgUser->getId(),
			$reasonstr, wfTimestampNow(), 0, $expiry, $this->BlockAnonOnly,
			$this->BlockCreateAccount, $this->BlockEnableAutoblock, $this->BlockHideName,
			$this->BlockEmail,
			isset( $this->BlockAllowUsertalk ) ? $this->BlockAllowUsertalk : $wgBlockAllowsUTEdit
		);

		# Should this be privately logged?
		$suppressLog = (bool)$this->BlockHideName;
		if( wfRunHooks( 'BlockIp', array( &$block, &$wgUser ) ) ) {
			# Try to insert block. Is there a conflicting block?
			if( !$block->insert() ) {
				# Show form unless the user is already aware of this...
				if( !$this->BlockReblock ) {
					return array( 'ipb_already_blocked' );
				# Otherwise, try to update the block...
				} else {
					# This returns direct blocks before autoblocks/rangeblocks, since we should
					# be sure the user is blocked by now it should work for our purposes
					$currentBlock = Block::newFromDB( $this->BlockAddress, $userId );
					if( $block->equals( $currentBlock ) ) {
						return array( 'ipb_already_blocked' );
					}
					# If the name was hidden and the blocking user cannot hide
					# names, then don't allow any block changes...
					if( $currentBlock->mHideName && !$wgUser->isAllowed( 'hideuser' ) ) {
						return array( 'cant-see-hidden-user' );
					}
					$currentBlock->delete();
					$block->insert();
					# If hiding/unhiding a name, this should go in the private logs
					$suppressLog = $suppressLog || (bool)$currentBlock->mHideName;
					$log_action = 'reblock';
					# Unset _deleted fields if requested
					if( $currentBlock->mHideName && !$this->BlockHideName ) {
						self::unsuppressUserName( $this->BlockAddress, $userId );
					}
				}
			} else {
				$log_action = 'block';
			}
			wfRunHooks( 'BlockIpComplete', array( $block, $wgUser ) );

			# Set *_deleted fields if requested
			if( $this->BlockHideName ) {
				self::suppressUserName( $this->BlockAddress, $userId );
			}

			# Only show watch link when this is no range block
			if( $this->BlockWatchUser && $block->mRangeStart == $block->mRangeEnd ) {
				$wgUser->addWatch( Title::makeTitle( NS_USER, $this->BlockAddress ) );
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
		} else {
			return array( 'hookaborted' );
		}
	}

	public static function suppressUserName( $name, $userId, $dbw = null ) {
		$op = '|'; // bitwise OR
		return self::setUsernameBitfields( $name, $userId, $op, $dbw );
	}

	public static function unsuppressUserName( $name, $userId, $dbw = null ) {
		$op = '&'; // bitwise AND
		return self::setUsernameBitfields( $name, $userId, $op, $dbw );
	}

	private static function setUsernameBitfields( $name, $userId, $op, $dbw ) {
		if( $op !== '|' && $op !== '&' ) return false; // sanity check
		if( !$dbw )
			$dbw = wfGetDB( DB_MASTER );
		$delUser = Revision::DELETED_USER | Revision::DELETED_RESTRICTED;
		$delAction = LogPage::DELETED_ACTION | Revision::DELETED_RESTRICTED;
		# Normalize user name
		$userTitle = Title::makeTitleSafe( NS_USER, $name );
		$userDbKey = $userTitle->getDBkey();
		# To suppress, we OR the current bitfields with Revision::DELETED_USER
		# to put a 1 in the username *_deleted bit. To unsuppress we AND the
		# current bitfields with the inverse of Revision::DELETED_USER. The
		# username bit is made to 0 (x & 0 = 0), while others are unchanged (x & 1 = x).
		# The same goes for the sysop-restricted *_deleted bit.
		if( $op == '&' ) {
			$delUser = "~{$delUser}";
			$delAction = "~{$delAction}";
		}
		# Hide name from live edits
		$dbw->update( 'revision', array( "rev_deleted = rev_deleted $op $delUser" ),
			array( 'rev_user' => $userId ), __METHOD__ );
		# Hide name from deleted edits
		$dbw->update( 'archive', array( "ar_deleted = ar_deleted $op $delUser" ),
			array( 'ar_user_text' => $name ), __METHOD__ );
		# Hide name from logs
		$dbw->update( 'logging', array( "log_deleted = log_deleted $op $delUser" ),
			array( 'log_user' => $userId, "log_type != 'suppress'" ), __METHOD__ );
		$dbw->update( 'logging', array( "log_deleted = log_deleted $op $delAction" ),
			array( 'log_namespace' => NS_USER, 'log_title' => $userDbKey,
				"log_type != 'suppress'" ), __METHOD__ );
		# Hide name from RC
		$dbw->update( 'recentchanges', array( "rc_deleted = rc_deleted $op $delUser" ),
			array( 'rc_user_text' => $name ), __METHOD__ );
		$dbw->update( 'recentchanges', array( "rc_deleted = rc_deleted $op $delAction" ),
			array( 'rc_namespace' => NS_USER, 'rc_title' => $userDbKey, 'rc_logid > 0' ), __METHOD__ );
		# Hide name from live images
		$dbw->update( 'oldimage', array( "oi_deleted = oi_deleted $op $delUser" ),
			array( 'oi_user_text' => $name ), __METHOD__ );
		# Hide name from deleted images
		# WMF - schema change pending
		# $dbw->update( 'filearchive', array( "fa_deleted = fa_deleted $op $delUser" ),
		#	array( 'fa_user_text' => $name ), __METHOD__ );
		# Done!
		return true;
	}

	/**
	 * UI entry point for blocking
	 * Wraps around doBlock()
	 */
	public function doSubmit() {
		global $wgOut;
		$retval = $this->doBlock();
		if( empty( $retval ) ) {
			$titleObj = SpecialPage::getTitleFor( 'Blockip' );
			$wgOut->redirect( $titleObj->getFullURL( 'action=success&ip=' .
				urlencode( $this->BlockAddress ) ) );
			return;
		}
		$this->showForm( $retval );
	}

	public function showSuccess() {
		global $wgOut;

		$wgOut->setPageTitle( wfMsg( 'blockip-title' ) );
		$wgOut->setSubtitle( wfMsg( 'blockipsuccesssub' ) );
		$text = wfMsgExt( 'blockipsuccesstext', array( 'parse' ), $this->BlockAddress );
		$wgOut->addHTML( $text );
	}

	private function showLogFragment( $out, $title ) {
		global $wgUser;

		// Used to support GENDER in 'blocklog-showlog' and 'blocklog-showsuppresslog'
		$userBlocked = $title->getText();

		LogEventsList::showLogExtract(
			$out,
			'block',
			$title->getPrefixedText(),
			'',
			array(
				'lim' => 10,
				'msgKey' => array(
					'blocklog-showlog',
					$userBlocked
				),
				'showIfEmpty' => false
			)
		);

		// Add suppression block entries if allowed
		if( $wgUser->isAllowed( 'suppressionlog' ) ) {
			LogEventsList::showLogExtract( $out, 'suppress', $title->getPrefixedText(), '',
				array(
					'lim' => 10,
					'conds' => array(
						'log_action' => array(
							'block',
							'reblock',
							'unblock'
						)
					),
					'msgKey' => array(
						'blocklog-showsuppresslog',
						$userBlocked
					),
					'showIfEmpty' => false
				)
			);
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
		if( !$this->BlockEnableAutoblock && !IP::isIPAddress( $this->BlockAddress ) )
			// Same as anononly, this is not displayed when blocking an IP address
			$flags[] = 'noautoblock';
		if( $this->BlockEmail )
			$flags[] = 'noemail';
		if( !$this->BlockAllowUsertalk && $wgBlockAllowsUTEdit )
			$flags[] = 'nousertalk';
		if( $this->BlockHideName )
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
		if ( $wgUser->isAllowed( 'editinterface' ) ) {
			$title = Title::makeTitle( NS_MEDIAWIKI, 'Ipbreason-dropdown' );
			$links[] = $skin->link(
				$title,
				wfMsgHtml( 'ipb-edit-dropdown' ),
				array(),
				array( 'action' => 'edit' )
			);
		}
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
		return $skin->link( $contribsPage, wfMsgExt( 'ipb-blocklist-contribs', 'escape', $this->BlockAddress ) );
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
		$query = array( 'action' => 'unblock' );

		if( $this->BlockAddress ) {
			$addr = strtr( $this->BlockAddress, '_', ' ' );
			$message = wfMsg( 'ipb-unblock-addr', $addr );
			$query['ip'] = $this->BlockAddress;
		} else {
			$message = wfMsg( 'ipb-unblock' );
		}
		return $skin->linkKnown(
			$list,
			htmlspecialchars( $message ),
			array(),
			$query
		);
	}

	/**
	 * Build a convenience link to the block list
	 *
	 * @param $skin Skin to use
	 * @return string
	 */
	private function getBlockListLink( $skin ) {
		$list = SpecialPage::getTitleFor( 'Ipblocklist' );
		$query = array();

		if( $this->BlockAddress ) {
			$addr = strtr( $this->BlockAddress, '_', ' ' );
			$message = wfMsg( 'ipb-blocklist-addr', $addr );
			$query['ip'] = $this->BlockAddress;
		} else {
			$message = wfMsg( 'ipb-blocklist' );
		}

		return $skin->linkKnown(
			$list,
			htmlspecialchars( $message ),
			array(),
			$query
		);
	}

	/**
	 * Block a list of selected users
	 *
	 * @param $users Array
	 * @param $reason String
	 * @param $tag String: replaces user pages
	 * @param $talkTag String: replaces user talk pages
	 * @return Array: list of html-safe usernames
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
			if( is_null( $u ) || ( !$u->getId() && !IP::isIPAddress( $u->getName() ) ) ) {
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
			if( strlen( $tag ) > 2 ) {
				$userpage->doEdit( $tag, $reason, EDIT_MINOR );
			}
			if( strlen( $talkTag ) > 2 ) {
				$usertalk->doEdit( $talkTag, $reason, EDIT_MINOR );
			}
		}
		return $safeUsers;
	}
}
