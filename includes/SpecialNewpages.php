<?php
/**
 *
 * @addtogroup SpecialPage
 */

/**
 * implements Special:Newpages
 * @addtogroup SpecialPage
 */
class NewPagesPage extends QueryPage {

	var $namespace;
	var $username = '';

	function NewPagesPage( $namespace = NS_MAIN, $username = '' ) {
		$this->namespace = $namespace;
		$this->username = $username;
	}

	function getName() {
		return 'Newpages';
	}

	function isExpensive() {
		# Indexed on RC, and will *not* work with querycache yet.
		return false;
	}

	function makeUserWhere( &$dbo ) {
		$title = Title::makeTitleSafe( NS_USER, $this->username );
		if( $title ) {
			return ' AND rc_user_text = ' . $dbo->addQuotes( $title->getText() );
		} else {
			return '';
		}
	}

	private function makeNamespaceWhere() {
		return $this->namespace !== 'all'
			? ' AND rc_namespace = ' . intval( $this->namespace )
			: '';
	}

	function getSQL() {
		global $wgUser, $wgUseRCPatrol;
		$usepatrol = ( $wgUseRCPatrol && $wgUser->isAllowed( 'patrol' ) ) ? 1 : 0;
		$dbr = wfGetDB( DB_SLAVE );
		list( $recentchanges, $page ) = $dbr->tableNamesN( 'recentchanges', 'page' );

		$nsfilter = $this->makeNamespaceWhere();
		$uwhere = $this->makeUserWhere( $dbr );

		# FIXME: text will break with compression
		return
			"SELECT 'Newpages' as type,
				rc_namespace AS namespace,
				rc_title AS title,
				rc_cur_id AS cur_id,
				rc_user AS \"user\",
				rc_user_text AS user_text,
				rc_comment as \"comment\",
				rc_timestamp AS timestamp,
				rc_timestamp AS value,
				'{$usepatrol}' as usepatrol,
				rc_patrolled AS patrolled,
				rc_id AS rcid,
				page_len as length,
				page_latest as rev_id
			FROM $recentchanges,$page
			WHERE rc_cur_id=page_id AND rc_new=1
			{$nsfilter}
			AND page_is_redirect = 0
			{$uwhere}";
	}
	
	function preprocessResults( &$dbo, &$res ) {
		# Do a batch existence check on the user and talk pages
		$linkBatch = new LinkBatch();
		while( $row = $dbo->fetchObject( $res ) ) {
			$linkBatch->addObj( Title::makeTitleSafe( NS_USER, $row->user_text ) );
			$linkBatch->addObj( Title::makeTitleSafe( NS_USER_TALK, $row->user_text ) );
		}
		$linkBatch->execute();
		# Seek to start
		if( $dbo->numRows( $res ) > 0 )
			$dbo->dataSeek( $res, 0 );
	}

	/**
	 * Format a row, providing the timestamp, links to the page/history, size, user links, and a comment
	 *
	 * @param $skin Skin to use
	 * @param $result Result row
	 * @return string
	 */
	function formatResult( $skin, $result ) {
		global $wgLang, $wgContLang;
		$dm = $wgContLang->getDirMark();

		$title = Title::makeTitleSafe( $result->namespace, $result->title );
		$time = $wgLang->timeAndDate( $result->timestamp, true );
		$plink = $skin->makeKnownLinkObj( $title, '', $this->patrollable( $result ) ? 'rcid=' . $result->rcid : '' );
		$hist = $skin->makeKnownLinkObj( $title, wfMsgHtml( 'hist' ), 'action=history' );
		$length = wfMsgExt( 'nbytes', array( 'parsemag', 'escape' ), $wgLang->formatNum( htmlspecialchars( $result->length ) ) );
		$ulink = $skin->userLink( $result->user, $result->user_text ) . ' ' . $skin->userToolLinks( $result->user, $result->user_text );
		$comment = $skin->commentBlock( $result->comment );

		return "{$time} {$dm}{$plink} ({$hist}) {$dm}[{$length}] {$dm}{$ulink} {$comment}";
	}

	/**
	 * Should a specific result row provide "patrollable" links?
	 *
	 * @param $result Result row
	 * @return bool
	 */
	function patrollable( $result ) {
		global $wgUser, $wgUseRCPatrol;
		return $wgUseRCPatrol && $wgUser->isAllowed( 'patrol' ) && !$result->patrolled;
	}

	function feedItemDesc( $row ) {
		if( isset( $row->rev_id ) ) {
			$revision = Revision::newFromId( $row->rev_id );
			if( $revision ) {
				return '<p>' . htmlspecialchars( wfMsg( 'summary' ) ) . ': ' .
					htmlspecialchars( $revision->getComment() ) . "</p>\n<hr />\n<div>" .
					nl2br( htmlspecialchars( $revision->getText() ) ) . "</div>";
			}
		}
		return parent::feedItemDesc( $row );
	}
	
	/**
	 * Show a form for filtering namespace and username
	 *
	 * @return string
	 */	
	function getPageHeader() {
		global $wgScript;
		$self = SpecialPage::getTitleFor( $this->getName() );
		$form = Xml::openElement( 'form', array( 'method' => 'get', 'action' => $wgScript ) );
		$form .= Xml::hidden( 'title', $self->getPrefixedUrl() );
		# Namespace selector
		$form .= '<table><tr><td align="right">' . Xml::label( wfMsg( 'namespace' ), 'namespace' ) . '</td>';
		$form .= '<td>' . Xml::namespaceSelector( $this->namespace, 'all' ) . '</td></tr>';
		# Username filter
		$form .= '<tr><td align="right">' . Xml::label( wfMsg( 'newpages-username' ), 'mw-np-username' ) . '</td>';
		$form .= '<td>' . Xml::input( 'username', 30, $this->username, array( 'id' => 'mw-np-username' ) ) . '</td></tr>';
		
		$form .= '<tr><td></td><td>' . Xml::submitButton( wfMsg( 'allpagessubmit' ) ) . '</td></tr></table>';
		$form .= Xml::hidden( 'offset', $this->offset ) . Xml::hidden( 'limit', $this->limit ) . '</form>';
		return $form;
	}
	
	/**
	 * Link parameters
	 *
	 * @return array
	 */
	function linkParameters() {
		return( array( 'namespace' => $this->namespace, 'username' => $this->username ) );
	}
	
}

/**
 * constructor
 */
function wfSpecialNewpages($par, $specialPage) {
	global $wgRequest, $wgContLang;

	list( $limit, $offset ) = wfCheckLimits();
	$namespace = NS_MAIN;
	$username = '';

	if ( $par ) {
		$bits = preg_split( '/\s*,\s*/', trim( $par ) );
		foreach ( $bits as $bit ) {
			if ( 'shownav' == $bit )
				$shownavigation = true;
			if ( is_numeric( $bit ) )
				$limit = $bit;

			$m = array();
			if ( preg_match( '/^limit=(\d+)$/', $bit, $m ) )
				$limit = intval($m[1]);
			if ( preg_match( '/^offset=(\d+)$/', $bit, $m ) )
				$offset = intval($m[1]);
			if ( preg_match( '/^namespace=(.*)$/', $bit, $m ) ) {
				$ns = $wgContLang->getNsIndex( $m[1] );
				if( $ns !== false ) {
					$namespace = $ns;
				}
			}
		}
	} else {
		if( $ns = $wgRequest->getText( 'namespace', NS_MAIN ) )
			$namespace = $ns;
		if( $un = $wgRequest->getText( 'username' ) )
			$username = $un;
	}
	
	if ( ! isset( $shownavigation ) )
		$shownavigation = ! $specialPage->including();

	$npp = new NewPagesPage( $namespace, $username );

	if ( ! $npp->doFeed( $wgRequest->getVal( 'feed' ), $limit ) )
		$npp->doQuery( $offset, $limit, $shownavigation );
}


