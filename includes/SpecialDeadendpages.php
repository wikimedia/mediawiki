<?php
/**
 *
 * @package MediaWiki
 * @subpackage SpecialPage
 */

/**
 *
 */
require_once( "QueryPage.php" );

/**
 *
 * @package MediaWiki
 * @subpackage SpecialPage
 */
class DeadendPagesPage extends PageQueryPage {

	function getName( ) {
		return "Deadendpages";
	}

	/**
	 * LEFT JOIN is expensive
	 *
	 * @return true
	 */
	function isExpensive( ) {
		return 1;
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
		$dbr =& wfGetDB( DB_SLAVE );
		extract( $dbr->tableNames( 'cur', 'links' ) );
		return "SELECT 'Deadendpages' as type, cur_namespace AS namespace, cur_title as title, cur_title AS value " . 
	"FROM $cur LEFT JOIN $links ON cur_id = l_from " .
	"WHERE l_from IS NULL " .
	"AND cur_namespace = 0 " .
	"AND cur_is_redirect = 0";
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

?>
