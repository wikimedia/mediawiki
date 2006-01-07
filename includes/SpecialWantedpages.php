<?php
/**
 *
 * @package MediaWiki
 * @subpackage SpecialPage
 */

/**
 *
 */
require_once 'QueryPage.php';

/**
 *
 * @package MediaWiki
 * @subpackage SpecialPage
 */
class WantedPagesPage extends QueryPage {
	var $nlinks;

	function WantedPagesPage( $inc = false, $nlinks = true ) {
		$this->setListoutput( $inc );
		$this->nlinks = $nlinks;
	}

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

	/**
	 * Fetch user page links and cache their existence
	 */
	function preprocessResults( &$db, &$res ) {
		$batch = new LinkBatch;
		while ( $row = $db->fetchObject( $res ) )
			$batch->addObj( Title::makeTitleSafe( $row->namespace, $row->title ) );
		$batch->execute();

		// Back to start for display
		if ( $db->numRows( $res ) > 0 )
			// If there are no rows we get an error seeking.
			$db->dataSeek( $res, 0 );
	}


	function formatResult( $skin, $result ) {
		global $wgContLang;

		$nt = Title::makeTitle( $result->namespace, $result->title );
		$text = $wgContLang->convert( $nt->getPrefixedText() );
		$plink = $this->isCached() ?
			$skin->makeLinkObj( $nt, $text ) :
			$skin->makeBrokenLink( $nt->getPrefixedText(), $text );

		$nl = wfMsg( 'nlinks', $result->value );
		$nlink = $skin->makeKnownLink( $wgContLang->specialPage( 'Whatlinkshere' ), $nl, 'target=' . $nt->getPrefixedURL() );

		return $this->nlinks ? "$plink ($nlink)" : $plink;
	}
}

/**
 * constructor
 */
function wfSpecialWantedpages( $par = null, $specialPage ) {
	$inc = $specialPage->including();

	if ( $inc ) {
		@list( $limit, $nlinks ) = explode( '/', $par, 2 );
		$limit = (int)$limit;
		$nlinks = $nlinks === 'nlinks';
		$offset = 0;
	} else {
		list( $limit, $offset ) = wfCheckLimits();
		$nlinks = true;
	}

	$wpp = new WantedPagesPage( $inc, $nlinks );

	$wpp->doQuery( $offset, $limit, !$inc );
}

?>
