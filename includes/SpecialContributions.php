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
	var $botmode = false;

	/**
	 * Constructor.
	 * @param $username username to list contribs for (or "newbies" for extra magic)
	 */
	function __construct( $username='' ) {
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
	 * Get target user name.  May be overridden in subclasses.
	 * @return string username
	 */
	function getUsername() {
		return $this->user->getName();
	}

	/**
	 * @return array Extra URL params for self-links.
	 */
	function linkParameters() {
		$params['target'] = $this->getUsername();

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

		$username = $this->getUsername();
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
		return wfMsgHtml( 'contribsub', $this->getTargetUserLinks() );
	}

	/**
	 * If the user has deleted contributions and we are allowed to
	 * view them, generate a link to Special:DeletedContributions.
	 * @return string 
	 */
	function getDeletedContributionsLink() {
		global $wgUser;

		if( !$wgUser->isAllowed( 'deletedhistory' ) )
			return '';

		$dbr = wfGetDB( DB_SLAVE );
		$n = $dbr->selectField( 'archive', 'count(*)', array( 'ar_user_text' => $this->getUsername() ), __METHOD__ );

		if ( $n == 0 )
			return '';

		$msg = wfMsg( ( $wgUser->isAllowed( 'delete' ) ? 'thisisdeleted' : 'viewdeleted' ),
			      $wgUser->getSkin()->makeKnownLinkObj(
				      SpecialPage::getTitleFor( 'DeletedContributions', $this->getUsername() ),
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
		$form .= Xml::hidden( 'target', $this->getUsername() );
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
		$cond .= ' AND rev_user_text = ' . $dbr->addQuotes( $this->getUsername() );

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

		// XXX: the username and userid fields aren't used for much here,
		// but some subclasses rely on them more than we do.

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
	 * Get user links for output row, for subclasses that may want
	 * such functionality.
	 *
	 * @param $skin Skin to use
	 * @param $row Result row
	 * @return string
	 */
	function getRowUserLinks( $skin, $row ) { return ''; }

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

		$user = $this->getRowUserLinks( $skin, $row );  // for subclasses

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
}

/**
 * Show the special page.
 */
function wfSpecialContributions( $par = null ) {
	global $wgRequest, $wgUser, $wgOut;

	$username = ( isset($par) ? $par : $wgRequest->getVal( 'target' ) );

	// compatibility hack
	if ( $username == 'newbies' ) {
		$wgOut->redirect( SpecialPage::getTitleFor( 'NewbieContributions' )->getFullURL() );
		return;
	}

	$page = new ContributionsPage( $username );

	if( !$page->user ) {
		$wgOut->showErrorPage( 'notargettitle', 'notargettext' );
		return;
	}

	// hook for Contributionseditcount extension
	if ( $page->user && $page->user->isLoggedIn() )
		wfRunHooks( 'SpecialContributionsBeforeMainOutput', $page->user->getId() );
		
	$page->namespace = $wgRequest->getIntOrNull( 'namespace' );
	$page->botmode   = ( $wgUser->isAllowed( 'rollback' ) && $wgRequest->getBool( 'bot' ) );
	
	list( $limit, $offset ) = wfCheckLimits();
	return $page->doQuery( $offset, $limit );
}

?>
