<?php
/**
 *
 * @package MediaWiki
 * @subpackage SpecialPage
 */

/**
 *
 */
require_once("QueryPage.php");

/**
 * SpecialShortpages extends QueryPage. It is used to return the shortest
 * pages in the database.
 * @package MediaWiki
 * @subpackage SpecialPage
 */
class ShortPagesPage extends QueryPage {

	function getName() {
		return "Shortpages";
	}

	function isExpensive() {
		return true;
	}
	function isSyndicated() { return false; }

	function getSQL() {
		$dbr =& wfGetDB( DB_SLAVE );
		$page = $dbr->tableName( 'page' );
		$text = $dbr->tableName( 'text' );
		$name = $dbr->addQuotes( $this->getName() );
		
		# FIXME: Not only is this teh suck, it will fail
		# if we compress revisions on save as it will return
		# the compressed size.
		return
			"SELECT $name as type,
					page_namespace as namespace,
			        page_title as title,
			        LENGTH(old_text) AS value
			FROM $page, $text
			WHERE page_namespace=0 AND page_is_redirect=0
			  AND page_latest=old_id";
	}
	
	function sortDescending() {
		return false;
	}

	function formatResult( $skin, $result ) {
		global $wgLang, $wgContLang;
		$nb = wfMsg( "nbytes", $wgLang->formatNum( $result->value ) );
		$link = $skin->makeKnownLink( $result->title, $wgContLang->convert( $result->title ) );
		return "{$link} ({$nb})";
	}
}

/**
 * constructor
 */
function wfSpecialShortpages() {
	list( $limit, $offset ) = wfCheckLimits();

	$spp = new ShortPagesPage();

	return $spp->doQuery( $offset, $limit );
}

?>
