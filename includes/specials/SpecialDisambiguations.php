<?php
/**
 * @file
 * @ingroup SpecialPage
 */

/**
 * @ingroup SpecialPage
 */
class DisambiguationsPage extends PageQueryPage {

	function getName() {
		return 'Disambiguations';
	}

	function isExpensive( ) { return true; }
	function isSyndicated() { return false; }


	function getPageHeader( ) {
		return wfMsgExt( 'disambiguations-text', array( 'parse' ) );
	}

	function getSQL() {
		$dbr = wfGetDB( DB_SLAVE );

		$linkBatch = new LinkBatch;
		foreach( wfGetDisambiguationTemplates() as $tl )
			$linkBatch->addObj( $tl );

		$set = $linkBatch->constructSet( 'lb.tl', $dbr );
		if( $set === false ) {
			# We must always return a valid sql query, but this way DB will always quicly return an empty result
			$set = 'FALSE';
			wfDebug("Mediawiki:disambiguationspage message does not link to any templates!\n");
		}

		list( $page, $pagelinks, $templatelinks) = $dbr->tableNamesN( 'page', 'pagelinks', 'templatelinks' );

		$sql = "SELECT 'Disambiguations' AS \"type\", pb.page_namespace AS namespace,"
			." pb.page_title AS title, la.pl_from AS value"
			." FROM {$templatelinks} AS lb, {$page} AS pb, {$pagelinks} AS la, {$page} AS pa"
			." WHERE $set"  # disambiguation template(s)
			.' AND pa.page_id = la.pl_from'
			.' AND pa.page_namespace = ' . NS_MAIN  # Limit to just articles in the main namespace
			.' AND pb.page_id = lb.tl_from'
			.' AND pb.page_namespace = la.pl_namespace'
			.' AND pb.page_title = la.pl_title'
			.' ORDER BY lb.tl_namespace, lb.tl_title';

		return $sql;
	}

	function getOrder() {
		return '';
	}

	function formatResult( $skin, $result ) {
		global $wgContLang;
		$title = Title::newFromID( $result->value );
		$dp = Title::makeTitle( $result->namespace, $result->title );

		$from = $skin->link( $title );
		$edit = $skin->link( $title, "(".wfMsgHtml("qbedit").")", array(), array( 'redirect' => 'no', 'action' => 'edit' ) );
		$arr  = $wgContLang->getArrow();
		$to   = $skin->link( $dp );

		return "$from $edit $arr $to";
	}
}

/**
 * Constructor
 */
function wfSpecialDisambiguations() {
	list( $limit, $offset ) = wfCheckLimits();

	$sd = new DisambiguationsPage();

	return $sd->doQuery( $offset, $limit );
}
