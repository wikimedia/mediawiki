<?php

require_once( "QueryPage.php" );

class AncientPagesPage extends QueryPage {

	function getName() {
		return "Ancientpages";
	}

	function isExpensive() {
		return parent::isExpensive() ;
	}

	function getSQL() {
		return
			"SELECT 'Ancientpages' as type,
					cur_namespace as namespace,
			        cur_title as title,
			        UNIX_TIMESTAMP(cur_timestamp) as value
			FROM cur USE INDEX (cur_timestamp)
			WHERE cur_namespace=0 AND cur_is_redirect=0";
	}
	
	function sortDescending() {
		return false;
	}

	function formatResult( $skin, $result ) {
		global $wgLang;

		$d = $wgLang->timeanddate( wfUnix2Timestamp( $result->value ), true );
		$link = $skin->makeKnownLink( $result->title, "" );
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
