<?php

require_once("QueryPage.php");

class ShortPagesPage extends QueryPage {

	function getName() {
		return "Shortpages";
	}

	function isExpensive() {
		return true;
	}

	function getSQL() {
		return
			"SELECT 'Shortpages' as type,
					cur_namespace as namespace,
			        cur_title as title,
			        LENGTH(cur_text) AS value
			FROM cur
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

function wfSpecialShortpages()
{
	list( $limit, $offset ) = wfCheckLimits();

	$spp = new ShortPagesPage();

	return $spp->doQuery( $offset, $limit );
}

?>
