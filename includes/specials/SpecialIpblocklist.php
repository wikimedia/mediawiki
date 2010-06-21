<?php
/**
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
 */

/**
 * @file
 * @ingroup SpecialPage
 */

/**
 * @param $ip part of title: Special:Ipblocklist/<ip>.
 * @todo document
 */
function wfSpecialIpblocklist( $ip = '' ) {
	global $wgUser, $wgOut, $wgRequest;
	$ip = $wgRequest->getVal( 'ip', $ip );
	$ip = trim( $wgRequest->getVal( 'wpUnblockAddress', $ip ) );
	$id = $wgRequest->getVal( 'id' );
	$reason = $wgRequest->getText( 'wpUnblockReason' );
	$action = $wgRequest->getText( 'action' );
	$successip = $wgRequest->getVal( 'successip' );

	$ipu = new IPUnblockForm( $ip, $id, $reason );

	if( $action == 'unblock' || $action == 'submit' && $wgRequest->wasPosted() ) {
		# Check permissions
		if( !$wgUser->isAllowed( 'block' ) ) {
			$wgOut->permissionRequired( 'block' );
			return;
		}
		# Check for database lock
		if( wfReadOnly() ) {
			$wgOut->readOnlyPage();
			return;
		}
	
		# bug 15810: blocked admins should have limited access here
		if ( $wgUser->isBlocked() ) {
			if ( $id ) {
				# This doesn't pick up on autoblocks, but admins
				# should have the ipblock-exempt permission anyway
				$block = Block::newFromID( $id );
				$user = User::newFromName( $block->mAddress );
			} else {
				$user = User::newFromName( $ip );
			}
			$status = IPBlockForm::checkUnblockSelf( $user );
			if ( $status !== true ) {
				throw new ErrorPageError( 'badaccess', $status );
			}
		}
		
		if( $action == 'unblock' ){
			# Show unblock form
			$ipu->showForm( '' );
		} elseif( $action == 'submit' 
			&& $wgRequest->wasPosted()
			&& $wgUser->matchEditToken( $wgRequest->getVal( 'wpEditToken' ) ) ) 
		{
			# Remove blocks and redirect user to success page
			$ipu->doSubmit();
		}
		
	} elseif( $action == 'success' ) {
		# Inform the user of a successful unblock
		# (No need to check permissions or locks here,
		# if something was done, then it's too late!)
		if ( substr( $successip, 0, 1) == '#' ) {
			// A block ID was unblocked
			$ipu->showList( $wgOut->parse( wfMsg( 'unblocked-id', $successip ) ) );
		} else {
			// A username/IP was unblocked
			$ipu->showList( $wgOut->parse( wfMsg( 'unblocked', $successip ) ) );
		}
	} else {
		# Just show the block list
		$ipu->showList( '' );
	}

}

/**
 * implements Special:ipblocklist GUI
 * @ingroup SpecialPage
 */
class IPUnblockForm {
	var $ip, $reason, $id;

	function IPUnblockForm( $ip, $id, $reason ) {
		global $wgRequest;
		$this->ip = strtr( $ip, '_', ' ' );
		$this->id = $id;
		$this->reason = $reason;
		$this->hideuserblocks = $wgRequest->getBool( 'hideuserblocks' );
		$this->hidetempblocks = $wgRequest->getBool( 'hidetempblocks' );
		$this->hideaddressblocks = $wgRequest->getBool( 'hideaddressblocks' );
	}

	/**
	 * Generates the unblock form
	 * @param $err string: error message
	 * @return $out string: HTML form
	 */
	function showForm( $err ) {
		global $wgOut, $wgUser, $wgSysopUserBans;

		$wgOut->setPagetitle( wfMsg( 'unblockip' ) );
		$wgOut->addWikiMsg( 'unblockiptext' );

		$titleObj = SpecialPage::getTitleFor( "Ipblocklist" );
		$action = $titleObj->getLocalURL( "action=submit" );

		if ( $err != "" ) {
			$wgOut->setSubtitle( wfMsg( "formerror" ) );
			$wgOut->addWikiText( Xml::tags( 'span', array( 'class' => 'error' ), $err ) . "\n" );
		}

		$addressPart = false;
		if ( $this->id ) {
			$block = Block::newFromID( $this->id );
			if ( $block ) {
				$encName = htmlspecialchars( $block->getRedactedName() );
				$encId = $this->id;
				$addressPart = $encName . Xml::hidden( 'id', $encId );
				$ipa = wfMsgHtml( $wgSysopUserBans ? 'ipadressorusername' : 'ipaddress' );
			}
		}
		if ( !$addressPart ) {
			$addressPart = Xml::input( 'wpUnblockAddress', 40, $this->ip, array( 'type' => 'text', 'tabindex' => '1' ) );
			$ipa = Xml::label( wfMsg( $wgSysopUserBans ? 'ipadressorusername' : 'ipaddress' ), 'wpUnblockAddress' );
		}

		$wgOut->addHTML(
			Xml::openElement( 'form', array( 'method' => 'post', 'action' => $action, 'id' => 'unblockip' ) ) .
			Xml::openElement( 'fieldset' ) .
			Xml::element( 'legend', null, wfMsg( 'ipb-unblock' ) ) .
			Xml::openElement( 'table', array( 'id' => 'mw-unblock-table' ) ).
			"<tr>
				<td class='mw-label'>
					{$ipa}
				</td>
				<td class='mw-input'>
					{$addressPart}
				</td>
			</tr>
			<tr>
				<td class='mw-label'>" .
					Xml::label( wfMsg( 'ipbreason' ), 'wpUnblockReason' ) .
				"</td>
				<td class='mw-input'>" .
					Xml::input( 'wpUnblockReason', 40, $this->reason, array( 'type' => 'text', 'tabindex' => '2' ) ) .
				"</td>
			</tr>
			<tr>
				<td>&#160;</td>
				<td class='mw-submit'>" .
					Xml::submitButton( wfMsg( 'ipusubmit' ), array( 'name' => 'wpBlock', 'tabindex' => '3' ) ) .
				"</td>
			</tr>" .
			Xml::closeElement( 'table' ) .
			Xml::closeElement( 'fieldset' ) .
			Xml::hidden( 'wpEditToken', $wgUser->editToken() ) .
			Xml::closeElement( 'form' ) . "\n"
		);

	}

	const UNBLOCK_SUCCESS = 0; // Success
	const UNBLOCK_NO_SUCH_ID = 1; // No such block ID
	const UNBLOCK_USER_NOT_BLOCKED = 2; // IP wasn't blocked
	const UNBLOCK_BLOCKED_AS_RANGE = 3; // IP is part of a range block
	const UNBLOCK_UNKNOWNERR = 4; // Unknown error

	/**
	 * Backend code for unblocking. doSubmit() wraps around this.
	 * $range is only used when UNBLOCK_BLOCKED_AS_RANGE is returned, in which
	 * case it contains the range $ip is part of.
	 * @return array array(message key, parameters) on failure, empty array on success
	 */

	static function doUnblock(&$id, &$ip, &$reason, &$range = null, $blocker=null) {
		if ( $id ) {
			$block = Block::newFromID( $id );
			if ( !$block ) {
				return array('ipb_cant_unblock', htmlspecialchars($id));
			}
			$ip = $block->getRedactedName();
		} else {
			$block = new Block();
			$ip = trim( $ip );
			if ( substr( $ip, 0, 1 ) == "#" ) {
				$id = substr( $ip, 1 );
				$block = Block::newFromID( $id );
				if( !$block ) {
					return array('ipb_cant_unblock', htmlspecialchars($id));
				}
				$ip = $block->getRedactedName();
			} else {
				$block = Block::newFromDB( $ip );
				if ( !$block ) {
					return array('ipb_cant_unblock', htmlspecialchars($id));
				}
				if( $block->mRangeStart != $block->mRangeEnd && !strstr( $ip, "/" ) ) {
					/* If the specified IP is a single address, and the block is
					 * a range block, don't unblock the range. */
					 $range = $block->mAddress;
					 return array('ipb_blocked_as_range', $ip, $range);
				}
			}
		}
		// Yes, this is really necessary
		$id = $block->mId;
		
		# If the name was hidden and the blocking user cannot hide
		# names, then don't allow any block removals...
		if( $blocker && $block->mHideName && !$blocker->isAllowed('hideuser') ) {
			return array('ipb_cant_unblock', htmlspecialchars($id));
		}

		# Delete block
		if ( !$block->delete() ) {
			return array('ipb_cant_unblock', htmlspecialchars($id));
		}
		
		# Unset _deleted fields as needed
		if( $block->mHideName ) {
			IPBlockForm::unsuppressUserName( $block->mAddress, $block->mUser );
		}

		# Make log entry
		$log = new LogPage( 'block' );
		$log->addEntry( 'unblock', Title::makeTitle( NS_USER, $ip ), $reason );
		return array();
	}

	function doSubmit() {
		global $wgOut, $wgUser;
		$retval = self::doUnblock($this->id, $this->ip, $this->reason, $range, $wgUser);
		if( !empty($retval) ) {
			$key = array_shift($retval);
			$this->showForm(wfMsgReal($key, $retval));
			return;
		}
		# Report to the user
		$titleObj = SpecialPage::getTitleFor( "Ipblocklist" );
		$success = $titleObj->getFullURL( "action=success&successip=" . urlencode( $this->ip ) );
		$wgOut->redirect( $success );
	}

	function showList( $msg ) {
		global $wgOut, $wgUser;

		$wgOut->setPagetitle( wfMsg( "ipblocklist" ) );
		if ( $msg != "" ) {
			$wgOut->setSubtitle( $msg );
		}

		// Purge expired entries on one in every 10 queries
		if ( !mt_rand( 0, 10 ) ) {
			Block::purgeExpired();
		}

		$conds = array();
		$matches = array();
		// Is user allowed to see all the blocks?
		if ( !$wgUser->isAllowed( 'hideuser' ) )
			$conds['ipb_deleted'] = 0;
		if ( $this->ip == '' ) {
			// No extra conditions
		} elseif ( substr( $this->ip, 0, 1 ) == '#' ) {
			$conds['ipb_id'] = substr( $this->ip, 1 );
		// Single IPs
		} elseif ( IP::isIPAddress($this->ip) && strpos($this->ip,'/') === false ) {
			if( $iaddr = IP::toHex($this->ip) ) {
				# Only scan ranges which start in this /16, this improves search speed
				# Blocks should not cross a /16 boundary.
				$range = substr( $iaddr, 0, 4 );
				// Fixme -- encapsulate this sort of query-building.
				$dbr = wfGetDB( DB_SLAVE );
				$encIp = $dbr->addQuotes( IP::sanitizeIP($this->ip) );
				$encAddr = $dbr->addQuotes( $iaddr );
				$conds[] = "(ipb_address = $encIp) OR 
					(ipb_range_start" . $dbr->buildLike( $range, $dbr->anyString() ) . " AND
					ipb_range_start <= $encAddr
					AND ipb_range_end >= $encAddr)";
			} else {
				$conds['ipb_address'] = IP::sanitizeIP($this->ip);
			}
			$conds['ipb_auto'] = 0;
		// IP range
		} elseif ( IP::isIPAddress($this->ip) ) {
			$conds['ipb_address'] = Block::normaliseRange( $this->ip );
			$conds['ipb_auto'] = 0;
		} else {
			$user = User::newFromName( $this->ip );
			if ( $user && ( $id = $user->getId() ) != 0 ) {
				$conds['ipb_user'] = $id;
			} else {
				// Uh...?
				$conds['ipb_address'] = $this->ip;
				$conds['ipb_auto'] = 0;
			}
		}
		// Apply filters
		if( $this->hideuserblocks ) {
			$conds['ipb_user'] = 0;
		}
		if( $this->hidetempblocks ) {
			$conds['ipb_expiry'] = 'infinity';
		}
		if( $this->hideaddressblocks ) {
			$conds[] = "ipb_user != 0 OR ipb_range_end > ipb_range_start";
		}

		// Search form
		$wgOut->addHTML( $this->searchForm() );

		// Check for other blocks, i.e. global/tor blocks
		$otherBlockLink = array();
		wfRunHooks( 'OtherBlockLogLink', array( &$otherBlockLink, $this->ip ) );

		// Show additional header for the local block only when other blocks exists.
		// Not necessary in a standard installation without such extensions enabled
		if( count( $otherBlockLink ) ) {
			$wgOut->addHTML(
				Html::rawElement( 'h2', array(), wfMsg( 'ipblocklist-localblock' ) ) . "\n"
			);
		}
		$pager = new IPBlocklistPager( $this, $conds );
		if ( $pager->getNumRows() ) {
			$wgOut->addHTML(
				$pager->getNavigationBar() .
				Xml::tags( 'ul', null, $pager->getBody() ) .
				$pager->getNavigationBar()
			);
		} elseif ( $this->ip != '') {
			$wgOut->addWikiMsg( 'ipblocklist-no-results' );
		} else {
			$wgOut->addWikiMsg( 'ipblocklist-empty' );
		}

		if( count( $otherBlockLink ) ) {
			$wgOut->addHTML(
				Html::rawElement( 'h2', array(), wfMsgExt( 'ipblocklist-otherblocks', 'parseinline', count( $otherBlockLink ) ) ) . "\n"
			);
			$list = '';
			foreach( $otherBlockLink as $link ) {
				$list .= Html::rawElement( 'li', array(), $link ) . "\n";
			}
			$wgOut->addHTML( Html::rawElement( 'ul', array( 'class' => 'mw-ipblocklist-otherblocks' ), $list ) . "\n" );
		}

	}

	function searchForm() {
		global $wgScript, $wgRequest, $wgLang;

		$showhide = array( wfMsg( 'show' ), wfMsg( 'hide' ) );
		$nondefaults = array();
		if( $this->hideuserblocks ) {
			$nondefaults['hideuserblocks'] = $this->hideuserblocks;
		}
		if( $this->hidetempblocks ) {
			$nondefaults['hidetempblocks'] = $this->hidetempblocks;
		}
		if( $this->hideaddressblocks ) {
			$nondefaults['hideaddressblocks'] = $this->hideaddressblocks;
		}
		$ubLink = $this->makeOptionsLink( $showhide[1-$this->hideuserblocks],
			array( 'hideuserblocks' => 1-$this->hideuserblocks ), $nondefaults);
		$tbLink = $this->makeOptionsLink( $showhide[1-$this->hidetempblocks],
			array( 'hidetempblocks' => 1-$this->hidetempblocks ), $nondefaults);
		$sipbLink = $this->makeOptionsLink( $showhide[1-$this->hideaddressblocks],
			array( 'hideaddressblocks' => 1-$this->hideaddressblocks ), $nondefaults);

		$links = array();
		$links[] = wfMsgHtml( 'ipblocklist-sh-userblocks', $ubLink );
		$links[] = wfMsgHtml( 'ipblocklist-sh-tempblocks', $tbLink );
		$links[] = wfMsgHtml( 'ipblocklist-sh-addressblocks', $sipbLink );
		$hl = $wgLang->pipeList( $links );

		return
			Xml::tags( 'form', array( 'action' => $wgScript ),
				Xml::hidden( 'title', SpecialPage::getTitleFor( 'Ipblocklist' )->getPrefixedDbKey() ) .
				Xml::openElement( 'fieldset' ) .
				Xml::element( 'legend', null, wfMsg( 'ipblocklist-legend' ) ) .
				Xml::inputLabel( wfMsg( 'ipblocklist-username' ), 'ip', 'ip', /* size */ false, $this->ip ) .
				'&#160;' .
				Xml::submitButton( wfMsg( 'ipblocklist-submit' ) ) . '<br />' .
				$hl .
				Xml::closeElement( 'fieldset' )
			);
	}

	/**
	 * Makes change an option link which carries all the other options
	 * @param $title see Title
	 * @param $override
	 * @param $options
	 */
	function makeOptionsLink( $title, $override, $options, $active = false ) {
		global $wgUser;
		$sk = $wgUser->getSkin();
		$params = $override + $options;
		$ipblocklist = SpecialPage::getTitleFor( 'Ipblocklist' );
		return $sk->link( $ipblocklist, htmlspecialchars( $title ),
			( $active ? array( 'style'=>'font-weight: bold;' ) : array() ), $params, array( 'known' ) );
	}

	/**
	 * Callback function to output a block
	 */
	function formatRow( $block ) {
		global $wgUser, $wgLang, $wgBlockAllowsUTEdit;

		wfProfileIn( __METHOD__ );

		static $sk=null, $msg=null;

		if( is_null( $sk ) )
			$sk = $wgUser->getSkin();
		if( is_null( $msg ) ) {
			$msg = array();
			$keys = array( 'infiniteblock', 'expiringblock', 'unblocklink', 'change-blocklink',
				'anononlyblock', 'createaccountblock', 'noautoblockblock', 'emailblock', 'blocklist-nousertalk', 'blocklistline' );
			foreach( $keys as $key ) {
				$msg[$key] = wfMsgHtml( $key );
			}
		}

		# Prepare links to the blocker's user and talk pages
		$blocker_id = $block->getBy();
		$blocker_name = $block->getByName();
		$blocker = $sk->userLink( $blocker_id, $blocker_name );
		$blocker .= $sk->userToolLinks( $blocker_id, $blocker_name );

		# Prepare links to the block target's user and contribs. pages (as applicable, don't do it for autoblocks)
		if( $block->mAuto ) {
			$target = $block->getRedactedName(); # Hide the IP addresses of auto-blocks; privacy
		} else {
			$target = $sk->userLink( $block->mUser, $block->mAddress )
				. $sk->userToolLinks( $block->mUser, $block->mAddress, false, Linker::TOOL_LINKS_NOBLOCK );
		}

		$formattedTime = htmlspecialchars( $wgLang->timeanddate( $block->mTimestamp, true ) );

		$properties = array();
		$properties[] = Block::formatExpiry( $block->mExpiry );
		if ( $block->mAnonOnly ) {
			$properties[] = $msg['anononlyblock'];
		}
		if ( $block->mCreateAccount ) {
			$properties[] = $msg['createaccountblock'];
		}
		if (!$block->mEnableAutoblock && $block->mUser ) {
			$properties[] = $msg['noautoblockblock'];
		}

		if ( $block->mBlockEmail && $block->mUser ) {
			$properties[] = $msg['emailblock'];
		}
		
		if ( !$block->mAllowUsertalk && $wgBlockAllowsUTEdit ) {
			$properties[] = $msg['blocklist-nousertalk'];
		}

		$properties = $wgLang->commaList( $properties );

		$line = wfMsgReplaceArgs( $msg['blocklistline'], array( $formattedTime, $blocker, $target, $properties ) );

		$unblocklink = '';
		$changeblocklink = '';
		$toolLinks = '';
		if ( $wgUser->isAllowed( 'block' ) ) {
			$unblocklink = $sk->link( SpecialPage::getTitleFor( 'Ipblocklist' ),
					$msg['unblocklink'],
					array(),
					array( 'action' => 'unblock', 'id' => $block->mId ),
					'known' );

			# Create changeblocklink for all blocks with exception of autoblocks
			if( !$block->mAuto ) {
				$changeblocklink = wfMsgExt( 'pipe-separator', 'escapenoentities' ) .
					$sk->link( SpecialPage::getTitleFor( 'Blockip', $block->mAddress ), 
						$msg['change-blocklink'],
						array(), array(), 'known' );
			}
			$toolLinks = "($unblocklink$changeblocklink)";
		}

		$comment = $sk->commentBlock( htmlspecialchars($block->mReason) );

		$s = "{$line} $comment";
		if ( $block->mHideName )
			$s = '<span class="history-deleted">' . $s . '</span>';

		wfProfileOut( __METHOD__ );
		return "<li>$s $toolLinks</li>\n";
	}
}

/**
 * @todo document
 * @ingroup Pager
 */
class IPBlocklistPager extends ReverseChronologicalPager {
	public $mForm, $mConds;

	function __construct( $form, $conds = array() ) {
		$this->mForm = $form;
		$this->mConds = $conds;
		parent::__construct();
	}

	function getStartBody() {
		wfProfileIn( __METHOD__ );
		# Do a link batch query
		$this->mResult->seek( 0 );
		$lb = new LinkBatch;

		/*
		while ( $row = $this->mResult->fetchObject() ) {
			$lb->addObj( Title::makeTitleSafe( NS_USER, $row->user_name ) );
			$lb->addObj( Title::makeTitleSafe( NS_USER_TALK, $row->user_name ) );
			$lb->addObj( Title::makeTitleSafe( NS_USER, $row->ipb_address ) );
			$lb->addObj( Title::makeTitleSafe( NS_USER_TALK, $row->ipb_address ) );
		}*/
		# Faster way
		# Usernames and titles are in fact related by a simple substitution of space -> underscore
		# The last few lines of Title::secureAndSplit() tell the story.
		while ( $row = $this->mResult->fetchObject() ) {
			$name = str_replace( ' ', '_', $row->ipb_by_text );
			$lb->add( NS_USER, $name );
			$lb->add( NS_USER_TALK, $name );
			$name = str_replace( ' ', '_', $row->ipb_address );
			$lb->add( NS_USER, $name );
			$lb->add( NS_USER_TALK, $name );
		}
		$lb->execute();
		wfProfileOut( __METHOD__ );
		return '';
	}

	function formatRow( $row ) {
		$block = new Block;
		$block->initFromRow( $row );
		return $this->mForm->formatRow( $block );
	}

	function getQueryInfo() {
		$conds = $this->mConds;
		$conds[] = 'ipb_expiry>' . $this->mDb->addQuotes( $this->mDb->timestamp() );
		return array(
			'tables' => 'ipblocks',
			'fields' => '*',
			'conds' => $conds,
		);
	}

	function getIndexField() {
		return 'ipb_timestamp';
	}
}
