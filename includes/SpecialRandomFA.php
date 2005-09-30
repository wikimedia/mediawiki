<?php
/**
 * @package MediaWiki
 * @subpackage SpecialPage
 */

/**
 * Constructor
 */
function wfSpecialRandomFA() {
	global $wgOut, $wgTitle, $wgArticle, $wgContLang;
	$fname = 'wfSpecialRandomFA';	

	$dbr =& wfGetDB( DB_SLAVE );
	$use_index = $dbr->useIndexClause( 'page_random' );
	extract( $dbr->tableNames( 'page', 'pagelinks' ) );

	$randstr = wfRandom();
	$ft = Title::newFromText( wfMsgForContent( 'featuredtemplate' ) );
	if ( is_null( $ft ) ) {
		$title = Title::newFromText( wfMsgForContent( 'mainpage' ) );
		$wgOut->redirect( $title->getFullUrl() );
		return;
	}
	$template = $dbr->addQuotes( $ft->getDBkey() );
	$sql = "
		SELECT page_title, page_random
		FROM $pagelinks
		LEFT JOIN $page $use_index ON page_id = pl_from
		WHERE pl_title = $template AND pl_namespace = " . NS_TEMPLATE . " AND page_namespace = " . NS_TALK . " AND page_random > $randstr
		ORDER BY page_random
		";
	$sql = $dbr->limitResult($sql, 1, 0);
	$res = $dbr->query( $sql, $fname );

	$title = null;
	if ( $s = $dbr->fetchObject( $res ) ) {
		$title =& Title::makeTitle( NS_MAIN, $s->page_title );
	}
	if ( is_null( $title ) ) {
		# That's not supposed to happen :)
		$title = Title::newFromText( wfMsgForContent( 'mainpage' ) );
	}
	$wgOut->reportTime(); # for logfile
	$wgOut->redirect( $title->getFullUrl() );
}

?>
