<?php

include_once( "QueryPage.php" );

class AncientPagesPage extends QueryPage {

	function getName() {
		return "Ancientpages";
	}

	function isExpensive() {
		return parent::isExpensive() ;
	}

	function getSQL( $offset, $limit ) {
		return "SELECT cur_title, cur_timestamp " . 
		  "FROM cur USE INDEX (cur_timestamp) " .
		  "WHERE cur_namespace=0 AND cur_is_redirect=0 " .
		  " ORDER BY cur_timestamp LIMIT {$offset}, {$limit}";
	}

	function formatResult( $skin, $result ) {
		global $wgLang;

		$d = $wgLang->timeanddate( $result->cur_timestamp, true );
		$link = $skin->makeKnownLink( $result->cur_title, "" );
		return "{$link} ({$d})";
	}
}

function wfSpecialAncientpages()
{
	list( $limit, $offset ) = wfCheckLimits();

	$app = new AncientPagesPage();

	$app->doQuery( $offset, $limit );
}

?>
