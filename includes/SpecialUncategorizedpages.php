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
	var $requestedNamespace = NS_MAIN;
	
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
		extract( $dbr->tableNames( 'page', 'categorylinks' ) );

		return "SELECT 'Uncategorizedpages' as type, page_namespace AS namespace, page_title AS title, page_title AS value " .
			"FROM $page LEFT JOIN $categorylinks ON page_id=cl_from ".
			"WHERE cl_from IS NULL AND page_namespace=$this->requestedNamespace AND page_is_redirect=0";
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
