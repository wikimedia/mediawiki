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
class UncategorizedPagesPage extends PageQueryPage {

	function getName() {
		return "Uncategorizedpages";
	}

	function sortDescending() {
		return false;
	}

	function isExpensive() {
		return true;
	}
	function isSyndicated() { return false; }
	
	function getSQL() {
		$dbr =& wfGetDB( DB_SLAVE );
		extract( $dbr->tableNames( 'cur', 'categorylinks' ) );

		return "SELECT 'Uncategorizedpages' as type, cur_namespace AS namespace, cur_title AS title, cur_title AS value " .
			"FROM $cur LEFT JOIN $categorylinks ON cur_id=cl_from ".
			"WHERE cl_from IS NULL AND cur_namespace=0 AND cur_is_redirect=0";
	}
}

/**
 * constructor
 */
function wfSpecialUncategorizedpages() {
	list( $limit, $offset ) = wfCheckLimits();

	$lpp = new UncategorizedPagesPage();

	return $lpp->doQuery( $offset, $limit );
}

?>
