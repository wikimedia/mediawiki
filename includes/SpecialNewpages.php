<?php
/**
 *
 * @addtogroup SpecialPage
 */


/**
 * Start point
 */
function wfSpecialNewPages( $par, $specialPage ) {
	$page = new NewPagesPage( $specialPage );
	$page->execute( $par );
}

/**
 * implements Special:Newpages
 * @addtogroup SpecialPage
 */
class NewPagesPage extends QueryPage {

	protected $options = array();
	protected $nondefaults = array();
	protected $specialPage;

	public function __construct( $specialPage ) {
		$this->specialPage = $specialPage;
	}

	public function execute( $par ) {
		global $wgRequest, $wgLang;

		$shownavigation = !$this->specialPage->including();

		$defaults = array(
			/* bool */ 'hideliu' => false,
			/* bool */ 'hidepatrolled' => false,
			/* bool */ 'hidebots' => false,
			/* text */ 'namespace' => "0",
			/* text */ 'username' => '',
			/* int  */ 'offset' => 0,
			/* int  */ 'limit' => 50,
		);

		$options = $defaults; 

		if ( $par ) {
			$bits = preg_split( '/\s*,\s*/', trim( $par ) );
			foreach ( $bits as $bit ) {
				if ( 'shownav' == $bit )
					$shownavigation = true;
				if ( 'hideliu' === $bit )
					$options['hideliu'] = true;
				if ( 'hidepatrolled' == $bit )
					$options['hidepatrolled'] = true;
				if ( 'hidebots' == $bit )
					$options['hidebots'] = true;
				if ( is_numeric( $bit ) )
					$options['limit'] = intval( $bit );

				$m = array();
				if ( preg_match( '/^limit=(\d+)$/', $bit, $m ) )
					$options['limit'] = intval($m[1]);
				if ( preg_match( '/^offset=(\d+)$/', $bit, $m ) )
					$options['offset'] = intval($m[1]);
				if ( preg_match( '/^namespace=(.*)$/', $bit, $m ) ) {
					$ns = $wgLang->getNsIndex( $m[1] );
					if( $ns !== false ) {
						$options['namespace'] = $ns;
					}
				}
			}
		}

		// Override all values from requests, if specified
		foreach ( $defaults as $v => $t ) {
			if ( is_bool($t) ) {
				$options[$v] = $wgRequest->getBool( $v, $options[$v] );
			} elseif( is_int($t) ) {
				$options[$v] = $wgRequest->getInt( $v, $options[$v] );
			} elseif( is_string($t) ) {
				$options[$v] = $wgRequest->getText( $v, $options[$v] );
			}
		}

		$nondefaults = array();
		foreach ( $options as $v => $t ) {
			if ( $v === 'offset' ) continue; # Reset offset if parameters change
			wfAppendToArrayIfNotDefault( $v, $t, $defaults, $nondefaults );
		}

		# bind to class
		$this->options = $options;
		$this->nondefaults = $nondefaults;

		if ( !$this->doFeed( $wgRequest->getVal( 'feed' ), $options['limit'] ) ) {
			$this->doQuery( $options['offset'], $options['limit'], $shownavigation );
		}
	}

	function linkParameters() {
		$nondefaults = $this->nondefaults;
		// QueryPage seems to handle limit and offset itself
		if ( isset( $nondefaults['limit'] ) ) {
			unset($nondefaults['limit']);
		}
		return $nondefaults;
	}

	function getName() {
		return 'Newpages';
	}

	function isExpensive() {
		# Indexed on RC, and will *not* work with querycache yet.
		return false;
	}

	function makeUserWhere( $db ) {
		global $wgGroupPermissions;
		$conds = array();
		if ($this->options['hidepatrolled']) {
			$conds['rc_patrolled'] = 0;
		}
		if ($this->options['hidebots']) {
			$conds['rc_bot'] = 0;
		}
		if ($wgGroupPermissions['*']['createpage'] == true && $this->options['hideliu']) {
			$conds['rc_user'] = 0;
		} else {
			$title = Title::makeTitleSafe( NS_USER, $this->options['username'] );
			if( $title ) {
				$conds['rc_user_text'] = $title->getText();
			}
		}
		return $conds;
	}


	function getSQL() {
		global $wgUser, $wgUseNPPatrol, $wgUseRCPatrol;
		$usepatrol = ( $wgUseNPPatrol || $wgUseRCPatrol ) ? 1 : 0;
		$dbr = wfGetDB( DB_SLAVE );
		list( $recentchanges, $page ) = $dbr->tableNamesN( 'recentchanges', 'page' );

		$conds = array();
		$conds['rc_new'] = 1;
		if ( $this->options['namespace'] !== 'all' ) {
			$conds['rc_namespace'] = intval( $this->options['namespace'] );
		}
		$conds['page_is_redirect'] = 0;
		$conds += $this->makeUserWhere( $dbr );
		$condstext = $dbr->makeList( $conds, LIST_AND );

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
			WHERE rc_cur_id=page_id AND $condstext";
	}
	
	function preprocessResults( $db, $res ) {
		# Do a batch existence check on the user and talk pages
		$linkBatch = new LinkBatch();
		while( $row = $db->fetchObject( $res ) ) {
			$linkBatch->add( NS_USER, $row->user_text );
			$linkBatch->add( NS_USER_TALK, $row->user_text );
		}
		$linkBatch->execute();
		# Seek to start
		if( $db->numRows( $res ) > 0 )
			$db->dataSeek( $res, 0 );
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
		global $wgUser, $wgUseRCPatrol, $wgUseNPPatrol;
		return ( $wgUseRCPatrol || $wgUseNPPatrol )
			&& $wgUser->isAllowed( 'patrol' )
			&& !$result->patrolled;
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
		global $wgScript, $wgContLang, $wgGroupPermissions, $wgUser, $wgUseRCPatrol, $wgUseNPPatrol;
		$sk = $wgUser->getSkin();
		$align = $wgContLang->isRTL() ? 'left' : 'right';
		$self = SpecialPage::getTitleFor( $this->getName() );

		// show/hide links
		$showhide = array( wfMsgHtml( 'show' ), wfMsgHtml( 'hide' ));

		$hidelinks = array();

		if ( $wgGroupPermissions['*']['createpage'] === true ) {
			$hidelinks['hideliu'] = 'rcshowhideliu';
		}
		if ( $wgUseNPPatrol || $wgUseRCPatrol ) {
			$hidelinks['hidepatrolled'] = 'rcshowhidepatr';
		}
		$hidelinks['hidebots'] = 'rcshowhidebots';

		$links = array();
		foreach ( $hidelinks as $key => $msg ) {
			$reversed = 1-$this->options[$key];
			$link = $sk->makeKnownLinkObj( $self, $showhide[$reversed],
				wfArrayToCGI( array( $key => $reversed ), $this->nondefaults )
			);
			$links[$key] = wfMsgHtml( $msg, $link );
		}

		$hl = implode( ' | ', $links );

		// Store query values in hidden fields so that form submission doesn't lose them
		$hidden = array();
		foreach ( $this->nondefaults as $key => $value ) {
			if ( $key === 'namespace' ) continue;
			if ( $key === 'username' ) continue;
			$hidden[] = Xml::hidden( $key, $value );
		}
		$hidden = implode( "\n", $hidden );

		$form = Xml::openElement( 'form', array( 'method' => 'get', 'action' => $wgScript ) ) .
			Xml::hidden( 'title', $self->getPrefixedDBkey() ) .
			Xml::openElement( 'table' ) .
			"<tr>
				<td align=\"$align\">" .
					Xml::label( wfMsg( 'namespace' ), 'namespace' ) .
				"</td>
				<td>" .
					Xml::namespaceSelector( $this->options['namespace'], 'all' ) .
				"</td>
			</tr>
			<tr>
				<td align=\"$align\">" .
					Xml::label( wfMsg( 'newpages-username' ), 'mw-np-username' ) .
				"</td>
				<td>" .
					Xml::input( 'username', 30, $this->options['username'], array( 'id' => 'mw-np-username' ) ) .
				"</td>
			</tr>
			<tr> <td></td>
				<td>" .
					Xml::submitButton( wfMsg( 'allpagessubmit' ) ) .
				"</td>
			</tr>" .
			"<tr><td></td><td>" . $hl . "</td></tr>" .
			Xml::closeElement( 'table' ) .
			$hidden .
			Xml::closeElement( 'form' );
		return $form;
	}
	
}