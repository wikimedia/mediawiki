<?php

/**
 * Special page for listing the articles with the fewest revisions.
 *
 * @package MediaWiki
 * @addtogroup SpecialPage
 * @author Martin Drashkov
 */
class FewestrevisionsPage extends QueryPage {

	function getName() {
		return 'Fewestrevisions';
	}

	function isExpensive() {
		return true;
	}

	function isSyndicated() {
		return false;
	}

	function getSql() {
		$dbr = wfGetDB( DB_SLAVE );
		list( $revision, $page ) = $dbr->tableNamesN( 'revision', 'page' );

		return "SELECT 'Fewestrevisions' as type,
				page_namespace as namespace,
				page_title as title,
				COUNT(*) as value
			FROM $revision
			JOIN $page ON page_id = rev_page
			WHERE page_namespace = " . NS_MAIN . "
			GROUP BY 1,2,3
			HAVING COUNT(*) > 1";
	}

	function sortDescending() {
		return false;
	}

	function formatResult( $skin, $result ) {
		global $wgLang, $wgContLang;

		$nt = Title::makeTitleSafe( $result->namespace, $result->title );
		$text = $wgContLang->convert( $nt->getPrefixedText() );

		$plink = $skin->makeKnownLinkObj( $nt, $text );

		$nl = wfMsgExt( 'nrevisions', array( 'parsemag', 'escape'),
			$wgLang->formatNum( $result->value ) );
		$nlink = $skin->makeKnownLinkObj( $nt, $nl, 'action=history' );

		return wfSpecialList( $plink, $nlink );
	}
}

function wfSpecialFewestrevisions() {
	list( $limit, $offset ) = wfCheckLimits();
	$frp = new FewestrevisionsPage();
	$frp->doQuery( $offset, $limit );
}


