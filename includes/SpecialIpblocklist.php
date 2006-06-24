<?php
/**
 *
 * @package MediaWiki
 * @subpackage SpecialPage
 */

/**
 * @todo document
 */
function wfSpecialIpblocklist() {
	global $wgUser, $wgOut, $wgRequest;

	$ip = $wgRequest->getVal( 'wpUnblockAddress', $wgRequest->getVal( 'ip' ) );
	$reason = $wgRequest->getText( 'wpUnblockReason' );
	$action = $wgRequest->getText( 'action' );

	$ipu = new IPUnblockForm( $ip, $reason );

	if ( "success" == $action ) {
		$ipu->showList( wfMsgWikiHtml( 'unblocked', htmlspecialchars( $ip ) ) );
	} else if ( "submit" == $action && $wgRequest->wasPosted() &&
		$wgUser->matchEditToken( $wgRequest->getVal( 'wpEditToken' ) ) ) {
		if ( ! $wgUser->isAllowed('block') ) {
			$wgOut->sysopRequired();
			return;
		}
		$ipu->doSubmit();
	} else if ( "unblock" == $action ) {
		$ipu->showForm( "" );
	} else {
		$ipu->showList( "" );
	}
}

/**
 *
 * @package MediaWiki
 * @subpackage SpecialPage
 */
class IPUnblockForm {
	var $ip, $reason;

	function IPUnblockForm( $ip, $reason ) {
		$this->ip = $ip;
		$this->reason = $reason;
	}

	function showForm( $err ) {
		global $wgOut, $wgUser, $wgSysopUserBans;

		$wgOut->setPagetitle( wfMsg( 'unblockip' ) );
		$wgOut->addWikiText( wfMsg( 'unblockiptext' ) );

		$ipa = wfMsgHtml( $wgSysopUserBans ? 'ipadressorusername' : 'ipaddress' );
		$ipr = wfMsgHtml( 'ipbreason' );
		$ipus = wfMsgHtml( 'ipusubmit' );
		$titleObj = Title::makeTitle( NS_SPECIAL, "Ipblocklist" );
		$action = $titleObj->escapeLocalURL( "action=submit" );

		if ( "" != $err ) {
			$wgOut->setSubtitle( wfMsg( "formerror" ) );
			$wgOut->addWikitext( "<span class='error'>{$err}</span>\n" );
		}
		$token = htmlspecialchars( $wgUser->editToken() );

		$wgOut->addHTML( "
<form id=\"unblockip\" method=\"post\" action=\"{$action}\">
	<table border='0'>
		<tr>
			<td align='right'>{$ipa}:</td>
			<td align='left'>
				<input tabindex='1' type='text' size='20' name=\"wpUnblockAddress\" value=\"" . htmlspecialchars( $this->ip ) . "\" />
			</td>
		</tr>
		<tr>
			<td align='right'>{$ipr}:</td>
			<td align='left'>
				<input tabindex='1' type='text' size='40' name=\"wpUnblockReason\" value=\"" . htmlspecialchars( $this->reason ) . "\" />
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td align='left'>
				<input tabindex='2' type='submit' name=\"wpBlock\" value=\"{$ipus}\" />
			</td>
		</tr>
	</table>
	<input type='hidden' name='wpEditToken' value=\"{$token}\" />
</form>\n" );

	}

	function doSubmit() {
		global $wgOut;

		$block = new Block();
		$this->ip = trim( $this->ip );

		if ( $this->ip{0} == "#" ) {
			$block->mId = substr( $this->ip, 1 );
		} else {
			$block->mAddress = $this->ip;
		}

		# Delete block (if it exists)
		# We should probably check for errors rather than just declaring success
		$block->delete();

		# Make log entry
		$log = new LogPage( 'block' );
		$log->addEntry( 'unblock', Title::makeTitle( NS_USER, $this->ip ), $this->reason );

		# Report to the user
		$titleObj = Title::makeTitle( NS_SPECIAL, "Ipblocklist" );
		$success = $titleObj->getFullURL( "action=success&ip=" . urlencode( $this->ip ) );
		$wgOut->redirect( $success );
	}

	function showList( $msg ) {
		global $wgOut;

		$wgOut->setPagetitle( wfMsg( "ipblocklist" ) );
		if ( "" != $msg ) {
			$wgOut->setSubtitle( $msg );
		}
		global $wgRequest;
		list( $this->limit, $this->offset ) = $wgRequest->getLimitOffset();
		$this->counter = 0;

		$paging = '<p>' . wfViewPrevNext( $this->offset, $this->limit,
			Title::makeTitle( NS_SPECIAL, 'Ipblocklist' ),
			'ip=' . urlencode( $this->ip ) ) . "</p>\n";
		$wgOut->addHTML( $paging );

		$search = $this->searchForm();
		$wgOut->addHTML( $search );
		
		$wgOut->addHTML( "<ul>" );
		if( !Block::enumBlocks( array( &$this, "addRow" ), 0 ) ) {
			// FIXME hack to solve #bug 1487
			$wgOut->addHTML( '<li>'.wfMsgHtml( 'ipblocklistempty' ).'</li>' );
		}
		$wgOut->addHTML( "</ul>\n" );
		$wgOut->addHTML( $paging );
	}

	function searchForm() {
		global $wgTitle;
		return
			wfElement( 'form', array(
				'action' => $wgTitle->getLocalUrl() ),
				null ) .
			wfElement( 'input', array(
				'type' => 'hidden',
				'name' => 'action',
				'value' => 'search' ) ).
			wfElement( 'input', array(
				'type' => 'hidden',
				'name' => 'limit',
				'value' => $this->limit ) ).
			wfElement( 'input', array(
				'name' => 'ip',
				'value' => $this->ip ) ) .
			wfElement( 'input', array(
				'type' => 'submit',
				'value' => wfMsg( 'search' ) ) ) .
			'</form>';
	}

	/**
	 * Callback function to output a block
	 */
	function addRow( $block, $tag ) {
		global $wgOut, $wgUser, $wgLang;

		if( $this->ip != '' ) {
			if( $block->mAuto ) {
				if( stristr( $block->mId, $this->ip ) == false ) {
					return;
				}
			} else {
				if( stristr( $block->mAddress, $this->ip ) == false ) {
					return;
				}
			}
		}

		// Loading blocks is fast; displaying them is slow.
		// Quick hack for paging.
		$this->counter++;
		if( $this->counter <= $this->offset ) {
			return;
		}
		if( $this->counter - $this->offset > $this->limit ) {
			return;
		}

		$fname = 'IPUnblockForm-addRow';
		wfProfileIn( $fname );

		static $sk=null, $msg=null;

		if( is_null( $sk ) )
			$sk = $wgUser->getSkin();
		if( is_null( $msg ) ) {
			$msg = array();
			foreach( array( 'infiniteblock', 'expiringblock', 'contribslink', 'unblocklink' ) as $key ) {
				$msg[$key] = wfMsgHtml( $key );
			}
			$msg['blocklistline'] = wfMsg( 'blocklistline' );
			$msg['contribslink'] = wfMsg( 'contribslink' );
		}


		# Prepare links to the blocker's user and talk pages
		$blocker_name = $block->getByName();
		$blocker = $sk->MakeLinkObj( Title::makeTitle( NS_USER, $blocker_name ), $blocker_name );
		$blocker .= ' (' . $sk->makeLinkObj( Title::makeTitle( NS_USER_TALK, $blocker_name ), $wgLang->getNsText( NS_TALK ) ) . ')';

		# Prepare links to the block target's user and contribs. pages (as applicable, don't do it for autoblocks)
		if( $block->mAuto ) {
			$target = '#' . $block->mId; # Hide the IP addresses of auto-blocks; privacy
		} else {
			$target = $sk->makeLinkObj( Title::makeTitle( NS_USER, $block->mAddress ), $block->mAddress );
			$target .= ' (' . $sk->makeKnownLinkObj( Title::makeTitle( NS_SPECIAL, 'Contributions' ), $msg['contribslink'], 'target=' . urlencode( $block->mAddress ) ) . ')';
		}
		
		# Prep the address for the unblock link, masking autoblocks as before
		$addr = $block->mAuto ? '#' . $block->mId : $block->mAddress;
		
		$formattedTime = $wgLang->timeanddate( $block->mTimestamp, true );

		if ( $block->mExpiry === "" ) {
			$formattedExpiry = $msg['infiniteblock'];
		} else {
			$formattedExpiry = wfMsgReplaceArgs( $msg['expiringblock'],
				array( $wgLang->timeanddate( $block->mExpiry, true ) ) );
		}

		$line = wfMsgReplaceArgs( $msg['blocklistline'], array( $formattedTime, $blocker, $target, $formattedExpiry ) );

		$wgOut->addHTML( "<li>{$line}" );

		if ( $wgUser->isAllowed('block') ) {
			$titleObj = Title::makeTitle( NS_SPECIAL, "Ipblocklist" );
			$wgOut->addHTML( ' (' . $sk->makeKnownLinkObj($titleObj, $msg['unblocklink'], 'action=unblock&ip=' . urlencode( $addr ) ) . ')' );
		}
		$wgOut->addHTML( $sk->commentBlock( $block->mReason ) );
		$wgOut->addHTML( "</li>\n" );
		wfProfileOut( $fname );
	}
}

?>
