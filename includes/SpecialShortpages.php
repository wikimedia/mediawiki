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

	/**
	 * This query is indexed as of 1.5
	 */
	function isExpensive() {
		return true;
	}

	function isSyndicated() {
		return false;
	}

	function getSQL() {
		$dbr =& wfGetDB( DB_SLAVE );
		$page = $dbr->tableName( 'page' );
		$name = $dbr->addQuotes( $this->getName() );

		return
			"SELECT $name as type,
				page_namespace as namespace,
			        page_title as title,
			        page_len AS value
			FROM $page FORCE INDEX (page_len)
			WHERE page_namespace=".NS_MAIN." AND page_is_redirect=0";
	}

	function sortDescending() {
		return false;
	}

	function formatResult( $skin, $result ) {
		global $wgLang, $wgContLang;
		$nb = htmlspecialchars( wfMsg( 'nbytes', $wgLang->formatNum( $result->value ) ) );
		$title = Title::makeTitle( $result->namespace, $result->title );
		$link = $skin->makeKnownLinkObj( $title, htmlspecialchars( $wgContLang->convert( $title->getPrefixedText() ) ) );
		$histlink = $skin->makeKnownLinkObj( $title, wfMsgHtml('hist'), 'action=history' );
		return "({$histlink}) $link ({$nb})";
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
