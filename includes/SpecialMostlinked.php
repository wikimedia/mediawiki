<?php
/**
 *
 * @package MediaWiki
 * @subpackage SpecialPage
 */

require_once ( 'QueryPage.php' ) ;

/**
 *
 * @package MediaWiki
 * @subpackage SpecialPage
 */
class MostlinkedPage extends QueryPage {

	function getName() {
		return 'Mostlinked';
	}

	function isExpensive() {
		return true;
	}
	function isSyndicated() { return false; }

	function getSQL() {
		$dbr =& wfGetDB( DB_SLAVE );
		extract( $dbr->tableNames( 'pagelinks', 'page' ) );
		return
			"SELECT 'Mostlinked' AS type,
				pl_namespace AS namespace,
				pl_title AS title,
				COUNT(*) AS value,
				page_namespace
			FROM $pagelinks
			LEFT JOIN $page ON pl_namespace=page_namespace AND pl_title=page_title
			GROUP BY pl_namespace,pl_title
			HAVING COUNT(*) > 1";
	}

	function formatResult( $skin, $result ) {
		global $wgContLang;

		$nt = Title::makeTitle( $result->namespace, $result->title );
		$text = $wgContLang->convert( $nt->getPrefixedText() );
		if ( is_null( $result->page_namespace ) )
			$plink = $skin->makeBrokenLink( $nt->getPrefixedText(), $text );
		else
			$plink = $skin->makeKnownLink( $nt->getPrefixedText(), $text );
		
		$nl = wfMsg( "nlinks", $result->value );
		$nlink = $skin->makeKnownLink( $wgContLang->specialPage( "Whatlinkshere" ), $nl, "target=" . $nt->getPrefixedURL() );

		return "{$plink} ({$nlink})";
	}
}

/**
 * constructor
 */
function wfSpecialMostlinked() {
	list( $limit, $offset ) = wfCheckLimits();

	$wpp = new MostlinkedPage();

	$wpp->doQuery( $offset, $limit );
}

?>
