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
	
	function isSyndicated() { return false; }

	function getSQL() {
		$db =& wfGetDB( DB_SLAVE );
		$page = $db->tableName( 'page' );
		$revision = $db->tableName( 'revision' );
		#$use_index = $db->useIndexClause( 'cur_timestamp' ); # FIXME! this is gone
		return
			"SELECT 'Ancientpages' as type,
					page_namespace as namespace,
			        page_title as title,
			        rev_timestamp as value
			FROM $page, $revision
			WHERE page_namespace=0 AND page_is_redirect=0
			  AND page_latest=rev_id";
	}
	
	function sortDescending() {
		return false;
	}

	function formatResult( $skin, $result ) {
		global $wgLang, $wgContLang;

		$d = $wgLang->timeanddate( wfTimestamp( TS_MW, $result->value ), true );
		$link = $skin->makeKnownLink( $result->title, $wgContLang->convert( $result->title) );
		return "{$link} ({$d})";
	}
}

function wfSpecialAncientpages() {
	list( $limit, $offset ) = wfCheckLimits();

	$app = new AncientPagesPage();

	$app->doQuery( $offset, $limit );
}

?>
