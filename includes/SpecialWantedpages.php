<?php
/**
 *
 * @package MediaWiki
 * @subpackage SpecialPage
 */

/**
 *
 */
require_once ( 'QueryPage.php' ) ;

/**
 *
 * @package MediaWiki
 * @subpackage SpecialPage
 */
class WantedPagesPage extends QueryPage {

	function getName() {
		return 'Wantedpages';
	}

	function isExpensive() {
		return true;
	}
	function isSyndicated() { return false; }

	function getSQL() {
		$dbr =& wfGetDB( DB_SLAVE );
		$pagelinks = $dbr->tableName( 'pagelinks' );
		$page      = $dbr->tableName( 'page' );
		return
			"SELECT 'Wantedpages' AS type,
			        pl_namespace AS namespace,
			        pl_title AS title,
			        COUNT(*) AS value
			 FROM $pagelinks
			 LEFT JOIN $page
			 ON pl_namespace=page_namespace AND pl_title=page_title
			 WHERE page_namespace IS NULL
			 GROUP BY pl_namespace,pl_title
			 HAVING COUNT(*) > 1";
	}

	function formatResult( $skin, $result ) {
		global $wgContLang;

		$nt = Title::makeTitle( $result->namespace, $result->title );
		$text = $wgContLang->convert( $nt->getPrefixedText() );
		$plink = $skin->makeBrokenLink( $nt->getPrefixedText(), $text );
		
		$nl = wfMsg( "nlinks", $result->value );
		$nlink = $skin->makeKnownLink( $wgContLang->specialPage( "Whatlinkshere" ), $nl,
		  "target=" . $nt->getPrefixedURL() );

		return "{$plink} ({$nlink})";
	}
}

/**
 * constructor
 */
function wfSpecialWantedpages() {
	list( $limit, $offset ) = wfCheckLimits();

	$wpp = new WantedPagesPage();

	$wpp->doQuery( $offset, $limit );
}

?>
