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
class LongPagesPage extends QueryPage {

	function getName() {
		return "Longpages";
	}

	function isExpensive() {
		return true;
	}

	function getSQL() {
		$dbr =& wfGetDB( DB_SLAVE );
		$cur = $dbr->tableName( 'cur' );

		return
			"SELECT 'Longpages' as type,
					cur_namespace as namespace,
			        cur_title as title,
			        LENGTH(cur_text) AS value
			FROM $cur
			WHERE cur_namespace=0 AND cur_is_redirect=0";
	}

	function formatResult( $skin, $result ) {
		global $wgLang;
		$nb = wfMsg( "nbytes", $wgLang->formatNum( $result->value ) );
		$link = $skin->makeKnownLink( $result->title, "" );
		return "{$link} ({$nb})";
	}
}

/**
 * constructor
 */
function wfSpecialLongpages()
{
    list( $limit, $offset ) = wfCheckLimits();

    $lpp = new LongPagesPage( );
    
    $lpp->doQuery( $offset, $limit );
}

?>
