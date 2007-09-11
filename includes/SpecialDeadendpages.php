<?php
/**
 *
 * @addtogroup SpecialPage
 */

/**
 *
 * @addtogroup SpecialPage
 */
class DeadendPagesPage extends PageQueryPage {

	function getName( ) {
		return "Deadendpages";
	}

	function getPageHeader() {
		return wfMsgExt( 'deadendpagestext', array( 'parse' ) );
	}

	function isExpensive( ) {
		return false;
	}

	function isSyndicated() { return false; }

	/**
	 * @return false
	 */
	function sortDescending() {
		return false;
	}

	/**
	 * @return string an sqlquery
	 */
	function getSQL() {
		$dbr = wfGetDB( DB_SLAVE );
		list( $page, $pagelinks ) = $dbr->tableNamesN( 'page', 'pagelinks' );
		return "SELECT 'Deadendpages' as type, page_namespace AS namespace, page_title as title, page_title AS value " .
	"FROM $page,$pagelinks " .
	"WHERE page_id = pl_from " .
	"AND page_namespace = 0 " .
	"AND page_is_redirect = 0 " .
	"AND pl_title = '' " . 
	"AND pl_namespace = 0";
	}
}

/**
 * Constructor
 */
function wfSpecialDeadendpages() {

	list( $limit, $offset ) = wfCheckLimits();

	$depp = new DeadendPagesPage();

	return $depp->doQuery( $offset, $limit );
}


