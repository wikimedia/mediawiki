<?php

/**
 * Special page allowing users to view the contributions of new users.
 *
 * @package MediaWiki
 * @subpackage Special pages
 */
class NewbieContributionsPage extends ContributionsPage {

	/**
	 * Constructor.  No need for username.
	 */
	function __construct() { }

	/**
	 * @return string Name of this special page.
	 */
	function getName() {
		return 'NewbieContributions';
	}

	/**
	 * No target user here.
	 */
	function getUsername() {
		return '';
	}

	/**
	 * No target user => no subtitle.
	 */
	function getSubtitleForTarget() {
		return '';
	}

	/**
	 * No target user => no deleted contribs link.
	 */
	function getDeletedContributionsLink() {
		return '';
	}

	/**
	 * Construct the WHERE clause of the SQL SELECT statement for
	 * this query.
	 * @return string
	 */
	function makeSQLCond( $dbr ) {
		$cond = ' page_id = rev_page';

		$max = $dbr->selectField( 'user', 'max(user_id)', false, 'make_sql' );
		$cond .= ' AND rev_user > ' . (int)($max - $max / 100);

		if ( isset($this->namespace) )
			$cond .= ' AND page_namespace = ' . (int)$this->namespace;

		return $cond;
	}

	/**
	 * Do a batch existence check for any user and user talk pages
	 * that will be shown in the list.
	 */
	function preprocessResults( $dbr, $res ) {
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
	 * Get user links for output row.
	 *
	 * @param $skin Skin to use
	 * @param $row Result row
	 * @return string User links
	 */
	function getRowUserLinks( $skin, $row ) {
		$user = ' . . ' . $skin->userLink( $row->userid, $row->username )
				. $skin->userToolLinks( $row->userid, $row->username );
		return $user;
	}
}

/**
 * Show the special page.
 */
function wfSpecialNewbieContributions( $par = null ) {
	global $wgRequest, $wgUser, $wgOut;

	$page = new NewbieContributionsPage();

	$page->namespace = $wgRequest->getIntOrNull( 'namespace' );
	$page->botmode   = ( $wgUser->isAllowed( 'rollback' ) && $wgRequest->getBool( 'bot' ) );
	
	list( $limit, $offset ) = wfCheckLimits();
	return $page->doQuery( $offset, $limit );
}

?>
