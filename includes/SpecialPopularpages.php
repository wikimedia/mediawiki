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
class PopularPagesPage extends QueryPage {

	function getName() {
		return "Popularpages";
	}

	function isExpensive() {
		# page_counter is not indexed
		return true;
	}
	function isSyndicated() { return false; }

	function getSQL() {
		$dbr =& wfGetDB( DB_SLAVE );
		$page = $dbr->tableName( 'page' );

		return
			"SELECT 'Popularpages' as type,
			        page_namespace as namespace,
			        page_title as title,
			        page_counter as value
			FROM $page
			WHERE page_namespace=0 AND page_is_redirect=0";
	}

	function formatResult( $skin, $result ) {
		global $wgLang, $wgContLang;
		$link = $skin->makeKnownLink( $result->title, $wgContLang->convert( $result->title ) );
		$nv = wfMsg( "nviews", $wgLang->formatNum( $result->value ) );
		return "{$link} ({$nv})";
	}
}

/**
 * Constructor
 */
function wfSpecialPopularpages() {
    list( $limit, $offset ) = wfCheckLimits();
    
    $ppp = new PopularPagesPage();
    
    return $ppp->doQuery( $offset, $limit );
}

?>
