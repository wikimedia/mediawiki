<?php
/**
 * Implements Special:ipblocklist
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
 * A special page that lists existing blocks and allows users with the 'block'
 * permission to remove blocks
 *
 * @ingroup SpecialPage
 */
class IPUnblockForm extends SpecialPage {
	var $ip;
	var $hideuserblocks, $hidetempblocks, $hideaddressblocks;

	function __construct() {
		parent::__construct( 'Ipblocklist' );
	}

	/**
	 * Main execution point
	 *
	 * @param $ip part of title: Special:Ipblocklist/<ip>.
	 */
	function execute( $ip ) {
		global $wgUser, $wgOut, $wgRequest;

		$this->setHeaders();
		$this->outputHeader();

		$ip = $wgRequest->getVal( 'ip', $ip );
		$this->ip = trim( $wgRequest->getVal( 'wpUnblockAddress', $ip ) );
		$this->hideuserblocks = $wgRequest->getBool( 'hideuserblocks' );
		$this->hidetempblocks = $wgRequest->getBool( 'hidetempblocks' );
		$this->hideaddressblocks = $wgRequest->getBool( 'hideaddressblocks' );

		$action = $wgRequest->getText( 'action' );

		if( $action == 'unblock' || $action == 'submit' && $wgRequest->wasPosted() ) {
			# B/C @since 1.18: Unblock interface is now at Special:Unblock
			$title = SpecialPage::getTitleFor( 'Unblock', $this->ip );
			$wgOut->redirect( $title->getFullUrl() );
			return;
		} else {
			# Just show the block list
			$this->showList( '' );
		}
	}

	function showList( $msg ) {
		global $wgOut, $wgUser;

		if ( $msg != '' ) {
			$wgOut->setSubtitle( $msg );
		}

		// Purge expired entries on one in every 10 queries
		if ( !mt_rand( 0, 10 ) ) {
			Block::purgeExpired();
		}

		$conds = array();
		// Is user allowed to see all the blocks?
		if ( !$wgUser->isAllowed( 'hideuser' ) )
			$conds['ipb_deleted'] = 0;
		if ( $this->ip == '' ) {
			// No extra conditions
		} elseif ( substr( $this->ip, 0, 1 ) == '#' ) {
			$conds['ipb_id'] = substr( $this->ip, 1 );
		// Single IPs
		} elseif ( IP::isIPAddress( $this->ip ) && strpos( $this->ip, '/' ) === false ) {
			$iaddr = IP::toHex( $this->ip );
			if( $iaddr ) {
				# Only scan ranges which start in this /16, this improves search speed
				# Blocks should not cross a /16 boundary.
				$range = substr( $iaddr, 0, 4 );
				// Fixme -- encapsulate this sort of query-building.
				$dbr = wfGetDB( DB_SLAVE );
				$encIp = $dbr->addQuotes( IP::sanitizeIP( $this->ip ) );
				$encAddr = $dbr->addQuotes( $iaddr );
				$conds[] = "(ipb_address = $encIp) OR 
					(ipb_range_start" . $dbr->buildLike( $range, $dbr->anyString() ) . " AND
					ipb_range_start <= $encAddr
					AND ipb_range_end >= $encAddr)";
			} else {
				$conds['ipb_address'] = IP::sanitizeIP( $this->ip );
			}
			$conds['ipb_auto'] = 0;
		// IP range
		} elseif ( IP::isIPAddress( $this->ip ) ) {
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
				Html::rawElement( 'ul', null, $pager->getBody() ) .
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
		global $wgScript, $wgLang;

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
			Html::rawElement( 'form', array( 'action' => $wgScript ),
				Html::hidden( 'title', $this->getTitle()->getPrefixedDbKey() ) .
				Html::openElement( 'fieldset' ) .
				Html::element( 'legend', null, wfMsg( 'ipblocklist-legend' ) ) .
				Xml::inputLabel( wfMsg( 'ipblocklist-username' ), 'ip', 'ip', /* size */ false, $this->ip ) .
				'&#160;' .
				Xml::submitButton( wfMsg( 'ipblocklist-submit' ) ) . '<br />' .
				$hl .
				Html::closeElement( 'fieldset' )
			);
	}

	/**
	 * Makes change an option link which carries all the other options
	 *
	 * @param $title see Title
	 * @param $override Array: special query string options, will override the
	 *                  ones in $options
	 * @param $options Array: query string options
	 * @param $active Boolean: whether to display the link in bold
	 */
	function makeOptionsLink( $title, $override, $options, $active = false ) {
		global $wgUser;
		$sk = $wgUser->getSkin();
		$params = $override + $options;
		return $sk->link( $this->getTitle(), htmlspecialchars( $title ),
			( $active ? array( 'style'=>'font-weight: bold;' ) : array() ), $params, array( 'known' ) );
	}

	/**
	 * Callback function to output a block
	 *
	 * @param $block  Block
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

		$changeblocklink = '';
		$toolLinks = '';
		if ( $wgUser->isAllowed( 'block' ) ) {
			$unblocklink = $sk->link( $this->getTitle(),
					$msg['unblocklink'],
					array(),
					array( 'action' => 'unblock', 'id' => $block->mId ),
					'known' );

			# Create changeblocklink for all blocks with exception of autoblocks
			if( !$block->mAuto ) {
				$changeblocklink = wfMsgExt( 'pipe-separator', 'escapenoentities' ) .
					$sk->link( SpecialPage::getTitleFor( 'Block', $block->mAddress ),
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
		foreach ( $this->mResult as $row ) {
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
