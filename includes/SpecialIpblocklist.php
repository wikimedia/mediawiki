<?php
/**
 *
 * @addtogroup SpecialPage
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

	if( $action == 'unblock' ) {
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
		# Show unblock form
		$ipu->showForm( '' );
	} elseif( $action == 'submit' && $wgRequest->wasPosted()
		&& $wgUser->matchEditToken( $wgRequest->getVal( 'wpEditToken' ) ) ) {
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
		# Remove blocks and redirect user to success page
		$ipu->doSubmit();
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
 * @addtogroup SpecialPage
 */
class IPUnblockForm {
	var $ip, $reason, $id;

	function IPUnblockForm( $ip, $id, $reason ) {
		$this->ip = strtr( $ip, '_', ' ' );
		$this->id = $id;
		$this->reason = $reason;
	}

	function showForm( $err ) {
		global $wgOut, $wgUser, $wgSysopUserBans, $wgContLang;

		$wgOut->setPagetitle( wfMsg( 'unblockip' ) );
		$wgOut->addWikiText( wfMsg( 'unblockiptext' ) );

		$ipa = wfMsgHtml( $wgSysopUserBans ? 'ipadressorusername' : 'ipaddress' );
		$ipr = wfMsgHtml( 'ipbreason' );
		$ipus = wfMsgHtml( 'ipusubmit' );
		$titleObj = SpecialPage::getTitleFor( "Ipblocklist" );
		$action = $titleObj->getLocalURL( "action=submit" );
		$alignRight = $wgContLang->isRtl() ? 'left' : 'right';

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
				$encId = $this->id;
				$addressPart = $encName . Xml::hidden( 'id', $encId );
			}
		}
		if ( !$addressPart ) {
			$addressPart = Xml::input( 'wpUnblockAddress', 20, $this->ip, array( 'type' => 'text', 'tabindex' => '1' ) );
		}

		$wgOut->addHTML(
			Xml::openElement( 'form', array( 'method' => 'post', 'action' => $action, 'id' => 'unblockip' ) ) .
			Xml::openElement( 'table', array( 'border' => '0' ) ).
			"<tr>
				<td align='$alignRight'>
					{$ipa}
				</td>
				<td>
					{$addressPart}
				</td>
			</tr>
			<tr>
				<td align='$alignRight'>
					{$ipr}
				</td>
				<td>" .
					Xml::input( 'wpUnblockReason', 40, $this->reason, array( 'type' => 'text', 'tabindex' => '2' ) ) .
				"</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>" .
					Xml::submitButton( $ipus, array( 'name' => 'wpBlock', 'tabindex' => '3' ) ) .
				"</td>
			</tr>" .
			Xml::closeElement( 'table' ) .
			Xml::hidden( 'wpEditToken', $token ) .
			Xml::closeElement( 'form' ) . "\n"
		);

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
		global $wgOut, $wgUser;

		$wgOut->setPagetitle( wfMsg( "ipblocklist" ) );
		if ( "" != $msg ) {
			$wgOut->setSubtitle( $msg );
		}

		// Purge expired entries on one in every 10 queries
		if ( !mt_rand( 0, 10 ) ) {
			Block::purgeExpired();
		}

		$conds = array();
		$matches = array();
		// Is user allowed to see all the blocks?
		if ( !$wgUser->isAllowed( 'oversight' ) )
			$conds['ipb_deleted'] = 0;
		if ( $this->ip == '' ) {
			// No extra conditions
		} elseif ( substr( $this->ip, 0, 1 ) == '#' ) {
			$conds['ipb_id'] = substr( $this->ip, 1 );
		} elseif ( IP::toUnsigned( $this->ip ) !== false ) {
			$conds['ipb_address'] = $this->ip;
			$conds['ipb_auto'] = 0;
		} elseif( preg_match( '/^(\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3})\\/(\\d{1,2})$/', $this->ip, $matches ) ) {
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
		if ( $pager->getNumRows() ) {
			$wgOut->addHTML(
				$this->searchForm() .
				$pager->getNavigationBar() .
				Xml::tags( 'ul', null, $pager->getBody() ) .
				$pager->getNavigationBar()
			);
		} elseif ( $this->ip != '') {
			$wgOut->addHTML( $this->searchForm() );
			$wgOut->addWikiText( wfMsg( 'ipblocklist-no-results' ) );
		} else {
			$wgOut->addWikiText( wfMsg( 'ipblocklist-empty' ) );
		}
	}

	function searchForm() {
		global $wgTitle, $wgScript, $wgRequest;
		return
			Xml::tags( 'form', array( 'action' => $wgScript ),
				Xml::hidden( 'title', $wgTitle->getPrefixedDbKey() ) .
				Xml::openElement( 'fieldset' ) .
				Xml::element( 'legend', null, wfMsg( 'ipblocklist-legend' ) ) .
				Xml::inputLabel( wfMsg( 'ipblocklist-username' ), 'ip', 'ip', /* size */ false, $this->ip ) .
				'&nbsp;' .
				Xml::submitButton( wfMsg( 'ipblocklist-submit' ) ) .
				Xml::closeElement( 'fieldset' )
			);
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
			$keys = array( 'infiniteblock', 'expiringblock', 'unblocklink',
				'anononlyblock', 'createaccountblock', 'noautoblockblock', 'emailblock' );
			foreach( $keys as $key ) {
				$msg[$key] = wfMsgHtml( $key );
			}
			$msg['blocklistline'] = wfMsg( 'blocklistline' );
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
		if (!$block->mEnableAutoblock && $block->mUser ) {
			$properties[] = $msg['noautoblockblock'];
		}

		if ( $block->mBlockEmail && $block->mUser ) {
			$properties[] = $msg['emailblock'];
		}

		$properties = implode( ', ', $properties );

		$line = wfMsgReplaceArgs( $msg['blocklistline'], array( $formattedTime, $blocker, $target, $properties ) );

		$unblocklink = '';
		if ( $wgUser->isAllowed('block') ) {
			$titleObj = SpecialPage::getTitleFor( "Ipblocklist" );
			$unblocklink = ' (' . $sk->makeKnownLinkObj($titleObj, $msg['unblocklink'], 'action=unblock&id=' . urlencode( $block->mId ) ) . ')';
		}

		$comment = $sk->commentBlock( $block->mReason );

		$s = "{$line} $comment";	
		if ( $block->mHideName )
			$s = '<span class="history-deleted">' . $s . '</span>';
	
		wfProfileOut( __METHOD__ );
		return "<li>$s $unblocklink</li>\n";
	}
}

/**
 * @todo document
 * @addtogroup Pager
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


