<?php

/**
 * Special page allowing users to view their own contributions
 * and those of others.
 *
 * @package MediaWiki
 * @subpackage Special pages
 */
class ContributionsPage extends QueryPage {
	var $user = null;
	var $namespace = null;
	var $newbies = false;
	var $botmode = false;

	/**
	 * Constructor.
	 * @param $username username to list contribs for (or "newbies" for extra magic)
	 */
	function __construct( $username ) {
		// This is an ugly hack.  I don't know who came up with it.
		if ( $username == 'newbies' && $this->newbiesModeEnabled() )
			$this->newbies = true;
		else
			$this->user = User::newFromName( $username, false );
	}

	/**
	 * @return string Name of this special page.
	 */
	function getName() {
		return 'Contributions';
	}

	/**
	 * Not expensive, won't work with the query cache anyway.
	 */
	function isExpensive() { return false; }

	/**
	 * Should we?
	 */
	function isSyndicated() { return false; }

	/**
	 * Special handling of "newbies" username.  May be disabled in subclasses.
	 */
	function newbiesModeEnabled() { return true; }

	/**
	 * @return array Extra URL params for self-links.
	 */
	function linkParameters() {
		$params['target'] = ( $this->newbies ? "newbies" : $this->user->getName() );

		if ( isset($this->namespace) )
			$params['namespace'] = $this->namespace;

		if ( $this->botmode )
			$params['bot'] = 1;

		return $params;
	}

	/**
	 * Build the list of links to be shown in the subtitle.
	 * @return string Link list for "contribsub" UI message.
	 */
	function getTargetUserLinks() {
		global $wgSysopUserBans, $wgLang, $wgUser;

		$skin = $wgUser->getSkin();

		$username = $this->user->getName();
		$userpage = $this->user->getUserPage();
		$userlink = $skin->makeLinkObj( $userpage, $username );

		// talk page link
		$tools[] = $skin->makeLinkObj( $userpage->getTalkPage(), $wgLang->getNsText( NS_TALK ) );

		// block or block log link
		$id = $this->user->getId();
		if ( ( $id != 0 && $wgSysopUserBans ) || ( $id == 0 && User::isIP( $username ) ) ) {
			if( $wgUser->isAllowed( 'block' ) )
				$tools[] = $skin->makeKnownLinkObj( SpecialPage::getTitleFor( 'Blockip', $username ),
								  wfMsgHtml( 'blocklink' ) );
			else
				$tools[] = $skin->makeKnownLinkObj( SpecialPage::getTitleFor( 'Log' ),
								  htmlspecialchars( LogPage::logName( 'block' ) ),
								  'type=block&page=' . $userpage->getPrefixedUrl() );
		}

		// other logs link
		$tools[] = $skin->makeKnownLinkObj( SpecialPage::getTitleFor( 'Log' ),
						  wfMsgHtml( 'log' ),
						  'user=' . $userpage->getPartialUrl() );

		return $userlink . ' (' . implode( ' | ', $tools ) . ')';
	}

	/**
	 * Generate "For User (...)" message in subtitle.  Calls
	 * getTargetUserLinks() for most of the work.
	 * @return string 
	 */
	function getSubtitleForTarget() {
		if ( $this->newbies )
			return wfMsgHtml( 'sp-contributions-newbies-sub' );
		else
			return wfMsgHtml( 'contribsub', $this->getTargetUserLinks() );
	}

	/**
	 * If the user has deleted contributions and we are allowed to
	 * view them, generate a link to Special:DeletedContributions.
	 * @return string 
	 */
	function getDeletedContributionsLink() {
		global $wgUser;

		if( $this->newbies || !$wgUser->isAllowed( 'deletedhistory' ) )
			return '';

		$dbr = wfGetDB( DB_SLAVE );
		$n = $dbr->selectField( 'archive', 'count(*)', array( 'ar_user_text' => $this->user->getName() ), __METHOD__ );

		if ( $n == 0 )
			return '';

		$msg = wfMsg( ( $wgUser->isAllowed( 'delete' ) ? 'thisisdeleted' : 'viewdeleted' ),
			      $wgUser->getSkin()->makeKnownLinkObj(
				      SpecialPage::getTitleFor( 'DeletedContributions', $this->user->getName() ),
				      wfMsgExt( 'restorelink', array( 'parsemag', 'escape' ), $n ) ) );

		return "<p>$msg</p>";
	}

	/**
	 * Construct and output the page subtitle.
	 */
	function outputSubtitle() {
		global $wgOut;
		$subtitle = $this->getSubtitleForTarget();
		// $subtitle .= $this->getDeletedContributionsLink();  NOT YET...
		$wgOut->setSubtitle( $subtitle );
	}

	/**
	 * Construct the namespace selector form.
	 * @return string 
	 */
	function getNamespaceForm() {
		$title = $this->getTitle();

		$ns = $this->namespace;
		if ( !isset($ns) )
			$ns = '';

		$form = Xml::openElement( 'form', array( 'method' => 'post', 'action' => $title->getLocalUrl() ) );
		$form .= wfMsgHtml( 'namespace' ) . ' ';
		$form .= Xml::namespaceSelector( $ns, '' ) . ' ';
		$form .= Xml::submitButton( wfMsg( 'allpagessubmit' ) );
		$form .= Xml::hidden( 'offset', $this->offset );
		$form .= Xml::hidden( 'limit',  $this->limit );
		$form .= Xml::hidden( 'target', ( $this->newbies ? "newbies" : $this->user->getName() ) );
		if ( $this->botmode )
			$form .= Xml::hidden( 'bot', 1 );
		$form .= '</form>';

		return '<p>' . $form . '</p>';
	}

	/**
	 * Build the page header.  Also calls outputSubtitle().
	 * @return string 
	 */
	function getPageHeader() {
		$this->outputSubtitle();
		return $this->getNamespaceForm();
	}

	/**
	 * Construct the WHERE clause of the SQL SELECT statement for
	 * this query.
	 * @return string
	 */
	function makeSQLCond( $dbr ) {
		$cond = ' page_id = rev_page';
		if ( $this->newbies ) {
			$max = $dbr->selectField( 'user', 'max(user_id)', false, 'make_sql' );
			$cond .= ' AND rev_user > ' . (int)($max - $max / 100);
		} else {
			$cond .= ' AND rev_user_text = ' . $dbr->addQuotes( $this->user->getName() );
		}
		if ( isset($this->namespace) )
			$cond .= ' AND page_namespace = ' . (int)$this->namespace;
		return $cond;
	}

	/**
	 * Construct the SQL SELECT statement for this query.
	 * @return string
	 */
	function getSQL() {
		$dbr = wfGetDB( DB_SLAVE );

		list( $page, $revision ) = $dbr->tableNamesN( 'page', 'revision' );

		return "SELECT 'Contributions' as type,
				page_namespace AS namespace,
				page_title     AS title,
				rev_timestamp  AS value,
				rev_user       AS userid,
				rev_user_text  AS username,
				rev_minor_edit AS is_minor,
				page_latest    AS cur_id,
				rev_id         AS rev_id,
				rev_comment    AS comment,
				rev_deleted    AS deleted
			FROM $page, $revision
			WHERE " . $this->makeSQLCond( $dbr );
	}

	/**
	 * If in newbies mode, do a batch existence check for any user
	 * and user talk pages that will be shown in the list.
	 */
	function preprocessResults( $dbr, $res ) {
		if ( !$this->newbies )
			return;

		// Do a batch existence check for user and talk pages
		$linkBatch = new LinkBatch();
		while( $row = $dbr->fetchObject( $res ) ) {
			$linkBatch->add( NS_USER, $row->username );
			$linkBatch->add( NS_USER_TALK, $row->username );
		}
		$linkBatch->execute();

		// Seek to start
		if( $dbr->numRows( $res ) > 0 )
			$dbr->dataSeek( $res, 0 );
	}

	/**
	 * Format a row, providing the timestamp, links to the
	 * page/diff/history and a comment
	 *
	 * @param $skin Skin to use
	 * @param $row Result row
	 * @return string
	 */
	function formatResult( $skin, $row ) {
		global $wgLang, $wgContLang, $wgUser;

		$dm = $wgContLang->getDirMark();

		/*
		 * Cache UI messages in a static array so we don't
		 * have to regenerate them for each row.
		 */
		static $messages;
		if( !isset( $messages ) ) {
			foreach( explode( ' ', 'uctop diff newarticle rollbacklink diff hist minoreditletter' ) as $msg )
				$messages[$msg] = wfMsgExt( $msg, array( 'escape') );
		}

		$page = Title::makeTitle( $row->namespace, $row->title );

		/*
		 * HACK: We need a revision object, so we make a very
		 * heavily stripped-down one.  All we really need are
		 * the comment, the title and the deletion bitmask.
		 */
		$rev = new Revision( array(
			'comment'   => $row->comment,
			'deleted'   => $row->deleted,
			'user_text' => $row->username,
			'user'      => $row->userid,
		) );
		$rev->setTitle( $page );

		$ts = wfTimestamp( TS_MW, $row->value );
		$time = $wgLang->timeAndDate( $ts, true );
		$hist = $skin->makeKnownLinkObj( $page, $messages['hist'], 'action=history' );

		if ( $rev->userCan( Revision::DELETED_TEXT ) )
			$diff = $skin->makeKnownLinkObj( $page, $messages['diff'], 'diff=prev&oldid=' . $row->rev_id );
		else
			$diff = $messages['diff'];

		if( $row->is_minor )
			$mflag = '<span class="minor">' . $messages['minoreditletter'] . '</span> ';
		else
			$mflag = '';

		$link    = $skin->makeKnownLinkObj( $page );
		$comment = $skin->revComment( $rev );

		if ( $this->newbies ) {
			$user = ' . . ' . $skin->userLink( $row->userid, $row->username )
				. $skin->userToolLinks( $row->userid, $row->username );
		} else {
			$user = '';
		}

		$notes = '';

		if( $row->rev_id == $row->cur_id ) {
			$notes .= ' <strong>' . $messages['uctop'] . '</strong>';

			if( $wgUser->isAllowed( 'rollback' ) )
				$notes .= ' ' . $skin->generateRollback( $rev );
		}
		
		if( $rev->isDeleted( Revision::DELETED_TEXT ) ) {
			$time = '<span class="history-deleted">' . $time . '</span>';
			$notes .= ' ' . wfMsgHtml( 'deletedrev' );
		}
		
		return "{$time} ({$hist}) ({$diff}) {$mflag} {$dm}{$link}{$user} {$comment}{$notes}";
	}

	/**
	 * Called to actually output the page.  Override to do a basic
	 * input validity check before proceeding.
	 *
	 * @param $offset database query offset
	 * @param $limit database query limit
	 * @param $shownavigation show navigation like "next 200"?
	 */
	function doQuery( $limit, $offset, $shownavigation = true ) {

		// this needs to be checked before doing anything
		if( !$this->user && !$this->newbies ) {
			global $wgOut;
			$wgOut->showErrorPage( 'notargettitle', 'notargettext' );
			return;
		}

		return parent::doQuery( $limit, $offset, $shownavigation );
	}
}

/**
 * Show the special page.
 */
function wfSpecialContributions( $par = null ) {
	global $wgRequest, $wgUser;

	$username = ( isset($par) ? $par : $wgRequest->getVal( 'target' ) );

	$page = new ContributionsPage( $username );

	// hook for Contributionseditcount extension
	if ( $page->user && $page->user->isLoggedIn() )
		wfRunHooks( 'SpecialContributionsBeforeMainOutput', $page->user->getId() );
		
	$page->namespace = $wgRequest->getIntOrNull( 'namespace' );
	$page->botmode   = ( $wgUser->isAllowed( 'rollback' ) && $wgRequest->getBool( 'bot' ) );
	
	list( $limit, $offset ) = wfCheckLimits();
	return $page->doQuery( $offset, $limit );
}

?>
