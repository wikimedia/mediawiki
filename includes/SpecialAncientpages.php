<?php
/**
 *
 * @package MediaWiki
 * @subpackage SpecialPage
 */

/**
 *
 */
require_once( 'QueryPage.php' );

/**
 *
 * @package MediaWiki
 * @subpackage SpecialPage
 */
class AncientPagesPage extends QueryPage {

	function getName() {
		return "Ancientpages";
	}

	function isExpensive() {
		return parent::isExpensive() ;
	}

	function getSQL() {
		$db =& wfGetDB( DB_SLAVE );
		$cur = $db->tableName( 'cur' );
		$use_index = $db->useIndexClause( 'cur_timestamp' );
		return
			"SELECT 'Ancientpages' as type,
					cur_namespace as namespace,
			        cur_title as title,
			        cur_timestamp as value
			FROM $cur $use_index
			WHERE cur_namespace=0 AND cur_is_redirect=0";
	}
	
	function sortDescending() {
		return false;
	}

	function formatResult( $skin, $result ) {
		global $wgLang;

		$d = $wgLang->timeanddate( wfTimestamp( TS_MW, $result->value ), true );
		$link = $skin->makeKnownLink( $result->title, "" );
		return "{$link} ({$d})";
	}
}

function wfSpecialAncientpages() {
	list( $limit, $offset ) = wfCheckLimits();

	$app = new AncientPagesPage();

	$app->doQuery( $offset, $limit );
}

?>
