<?php
#
# SpecialShortpages extends QueryPage. It is used to return the shortest
# pages in the database.
#

require_once("QueryPage.php");

class ShortPagesPage extends QueryPage {

	function getName() {
		return "Shortpages";
	}

	function isExpensive() {
		return true;
	}

	function getSQL() {
		$dbr =& wfGetDB( DB_SLAVE );
		$cur = $dbr->tableName( 'cur' );
		
		return
			"SELECT 'Shortpages' as type,
					cur_namespace as namespace,
			        cur_title as title,
			        LENGTH(cur_text) AS value
			FROM $cur
			WHERE cur_namespace=0 AND cur_is_redirect=0";
	}
	
	function sortDescending() {
		return false;
	}

	function formatResult( $skin, $result ) {
		global $wgLang;
		$nb = wfMsg( "nbytes", $wgLang->formatNum( $result->value ) );
		$link = $skin->makeKnownLink( $result->title, "" );
		return "{$link} ({$nb})";
	}
}

function wfSpecialShortpages() {
	list( $limit, $offset ) = wfCheckLimits();

	$spp = new ShortPagesPage();

	return $spp->doQuery( $offset, $limit );
}

?>
