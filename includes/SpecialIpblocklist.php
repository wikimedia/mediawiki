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
	$id = $wgRequest->getVal( 'id' );
	$reason = $wgRequest->getText( 'wpUnblockReason' );
	$action = $wgRequest->getText( 'action' );
	$successip = $wgRequest->getVal( 'successip' );

	$ipu = new IPUnblockForm( $ip, $id, $reason );

	if ( "success" == $action ) {
		$ipu->showList( $wgOut->parse( wfMsg( 'unblocked', $successip ) ) );
	} else if ( "submit" == $action && $wgRequest->wasPosted() &&
		$wgUser->matchEditToken( $wgRequest->getVal( 'wpEditToken' ) ) ) {
		if ( ! $wgUser->isAllowed('block') ) {
			$wgOut->permissionRequired( 'block' );
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
	var $ip, $reason, $id;

	function IPUnblockForm( $ip, $id, $reason ) {
		$this->ip = $ip;
		$this->id = $id;
		$this->reason = $reason;
	}

	function showForm( $err ) {
		global $wgOut, $wgUser, $wgSysopUserBans;

		$wgOut->setPagetitle( wfMsg( 'unblockip' ) );
		$wgOut->addWikiText( wfMsg( 'unblockiptext' ) );

		$ipa = wfMsgHtml( $wgSysopUserBans ? 'ipadressorusername' : 'ipaddress' );
		$ipr = wfMsgHtml( 'ipbreason' );
		$ipus = wfMsgHtml( 'ipusubmit' );
		$titleObj = SpecialPage::getTitleFor( "Ipblocklist" );
		$action = $titleObj->escapeLocalURL( "action=submit" );

		if ( "" != $err ) {
			$wgOut->setSubtitle( wfMsg( "formerror" ) );
			$wgOut->addWikitext( "<span class='error'>{$err}</span>\n" );
		}
		$token = htmlspecialchars( $wgUser->editToken() );

		$addressPart = false;
		if ( $this->id ) {
			$block = Block::newFromID( $this->id );
			if ( $block ) {
				$encName = htmlspecialchars( $block->getRedactedName() );
				$encId = htmlspecialchars( $this->id );
				$addressPart = $encName . "<input type='hidden' name=\"id\" value=\"$encId\" />";
			}
		}
		if ( !$addressPart ) {
			$addressPart = "<input tabindex='1' type='text' size='20' " .
				"name=\"wpUnblockAddress\" value=\"" . htmlspecialchars( $this->ip ) . "\" />";
		}

		$wgOut->addHTML( "
<form id=\"unblockip\" method=\"post\" action=\"{$action}\">
	<table border='0'>
		<tr>
			<td align='right'>{$ipa}:</td>
			<td align='left'>
				{$addressPart}
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

		if ( $this->id ) {
			$block = Block::newFromID( $this->id );
			if ( $block ) {
				$this->ip = $block->getRedactedName();
			}
		} else {
			$block = new Block();
			$this->ip = trim( $this->ip );
			if ( substr( $this->ip, 0, 1 ) == "#" ) {
				$id = substr( $this->ip, 1 );
				$block = Block::newFromID( $id );
			} else {
				$block = Block::newFromDB( $this->ip );
				if ( !$block ) { 
					$block = null;
				}
			}
		}
		$success = false;
		if ( $block ) {
			# Delete block
			if ( $block->delete() ) {
				# Make log entry
				$log = new LogPage( 'block' );
				$log->addEntry( 'unblock', Title::makeTitle( NS_USER, $this->ip ), $this->reason );
				$success = true;
			}
		}

		if ( $success ) {
			# Report to the user
			$titleObj = SpecialPage::getTitleFor( "Ipblocklist" );
			$success = $titleObj->getFullURL( "action=success&successip=" . urlencode( $this->ip ) );
			$wgOut->redirect( $success );
		} else {
			if ( !$this->ip && $this->id ) {
				$this->ip = '#' . $this->id;
			}
			$this->showForm( wfMsg( 'ipb_cant_unblock', htmlspecialchars( $this->id ) ) );
		}
	}

	function showList( $msg ) {
		global $wgOut;

		$wgOut->setPagetitle( wfMsg( "ipblocklist" ) );
		if ( "" != $msg ) {
			$wgOut->setSubtitle( $msg );
		}

		// Purge expired entries on one in every 10 queries
		if ( !mt_rand( 0, 10 ) ) {
			Block::purgeExpired();
		}

		$conds = array();
		if ( $this->ip == '' ) {
			// No extra conditions
		} elseif ( substr( $this->ip, 0, 1 ) == '#' ) {
			$conds['ipb_id'] = substr( $this->ip, 1 );
		} elseif ( IP::toUnsigned( $this->ip ) !== false ) {
			$conds['ipb_address'] = $this->ip;
			$conds['ipb_auto'] = 0;
		} elseif( preg_match( "/^(\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3})\\/(\\d{1,2})$/", $this->ip, $matches ) ) {
			$conds['ipb_address'] = Block::normaliseRange( $this->ip );
			$conds['ipb_auto'] = 0;
		} else {
			$user = User::newFromName( $this->ip );
			if ( $user && ( $id = $user->getID() ) != 0 ) {
				$conds['ipb_user'] = $id;
			} else {
				// Uh...?
				$conds['ipb_address'] = $this->ip;
				$conds['ipb_auto'] = 0;
			}
		}

		$pager = new IPBlocklistPager( $this, $conds );
		$s = $pager->getNavigationBar() .
			$this->searchForm();
		if ( $pager->getNumRows() ) {
			$s .= "<ul>" . 
				$pager->getBody() .
				"</ul>";
		} else {
			$s .= '<p>' . wfMsgHTML( 'ipblocklistempty' ) . '</p>';
		}
		$s .= $pager->getNavigationBar();
		$wgOut->addHTML( $s );
	}

	function searchForm() {
		global $wgTitle, $wgScript, $wgRequest;
		return
			wfElement( 'form', array(
				'action' => $wgScript ),
				null ) .
			wfHidden( 'title', $wgTitle->getPrefixedDbKey() ) .
			wfElement( 'input', array(
				'type' => 'hidden',
				'name' => 'action',
				'value' => 'search' ) ).
			wfElement( 'input', array(
				'type' => 'hidden',
				'name' => 'limit',
				'value' => $wgRequest->getText( 'limit' ) ) ) .
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
	function formatRow( $block ) {
		global $wgUser, $wgLang;

		wfProfileIn( __METHOD__ );

		static $sk=null, $msg=null;

		if( is_null( $sk ) )
			$sk = $wgUser->getSkin();
		if( is_null( $msg ) ) {
			$msg = array();
			$keys = array( 'infiniteblock', 'expiringblock', 'contribslink', 'unblocklink', 
				'anononlyblock', 'createaccountblock', 'noautoblockblock' );
			foreach( $keys as $key ) {
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
			$target = $block->getRedactedName(); # Hide the IP addresses of auto-blocks; privacy
		} else {
			$target = $sk->makeLinkObj( Title::makeTitle( NS_USER, $block->mAddress ), $block->mAddress );
			$target .= ' (' . $sk->makeKnownLinkObj( SpecialPage::getSafeTitleFor( 'Contributions', $block->mAddress ), $msg['contribslink'] ) . ')';
		}
		
		$formattedTime = $wgLang->timeanddate( $block->mTimestamp, true );

		$properties = array();
		if ( $block->mExpiry === "" || $block->mExpiry === Block::infinity() ) {
			$properties[] = $msg['infiniteblock'];
		} else {
			$properties[] = wfMsgReplaceArgs( $msg['expiringblock'],
				array( $wgLang->timeanddate( $block->mExpiry, true ) ) );
		}
		if ( $block->mAnonOnly ) {
			$properties[] = $msg['anononlyblock'];
		}
		if ( $block->mCreateAccount ) {
			$properties[] = $msg['createaccountblock'];
		}
		if (!$block->mEnableAutoblock ) {
			$properties[] = $msg['noautoblockblock'];
		}

		$properties = implode( ', ', $properties );

		$line = wfMsgReplaceArgs( $msg['blocklistline'], array( $formattedTime, $blocker, $target, $properties ) );

		$s = "<li>{$line}";

		if ( $wgUser->isAllowed('block') ) {
			$titleObj = SpecialPage::getTitleFor( "Ipblocklist" );
			$s .= ' (' . $sk->makeKnownLinkObj($titleObj, $msg['unblocklink'], 'action=unblock&id=' . urlencode( $block->mId ) ) . ')';
		}
		$s .= $sk->commentBlock( $block->mReason );
		$s .= "</li>\n";
		wfProfileOut( __METHOD__ );
		return $s;
	}
}

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
			$name = str_replace( ' ', '_', $row->user_name );
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
		$conds[] = 'ipb_by=user_id';
		return array(
			'tables' => array( 'ipblocks', 'user' ),
			'fields' => $this->mDb->tableName( 'ipblocks' ) . '.*,user_name',
			'conds' => $conds,
		);
	}

	function getIndexField() {
		return 'ipb_timestamp';
	}
}

?>
