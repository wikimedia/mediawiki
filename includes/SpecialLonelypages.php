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
class LonelyPagesPage extends PageQueryPage {

	function getName() {
		return "Lonelypages";
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
		extract( $dbr->tableNames( 'cur', 'links' ) );

		return "SELECT 'Lonelypages' as type, cur_namespace AS namespace, cur_title AS title, cur_title AS value " .
			"FROM $cur LEFT JOIN $links ON cur_id=l_to ".
			"WHERE l_to IS NULL AND cur_namespace=0 AND cur_is_redirect=0";
	}
}

/**
 * Constructor
 */
function wfSpecialLonelypages() {
	list( $limit, $offset ) = wfCheckLimits();

	$lpp = new LonelyPagesPage();

	return $lpp->doQuery( $offset, $limit );
}

?>
