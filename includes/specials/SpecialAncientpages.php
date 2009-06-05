<?php
/**
 * @file
 * @ingroup SpecialPage
 */

/**
 * Implements Special:Ancientpages
 * @ingroup SpecialPage
 */
class AncientPagesPage extends QueryPage {

	function getName() {
		return "Ancientpages";
	}

	function isExpensive() {
		return true;
	}

	function isSyndicated() { return false; }

	function getSQL() {
		global $wgDBtype;
		$db = wfGetDB( DB_SLAVE );
		$page = $db->tableName( 'page' );
		$revision = $db->tableName( 'revision' );

		switch ($wgDBtype) {
			case 'mysql': 
				$epoch = 'UNIX_TIMESTAMP(rev_timestamp)'; 
				break;
			case 'oracle': 
				$epoch = '((trunc(rev_timestamp) - to_date(\'19700101\',\'YYYYMMDD\')) * 86400)'; 
				break;
			default:
				$epoch = 'EXTRACT(epoch FROM rev_timestamp)';
		}

		return
			"SELECT 'Ancientpages' as type,
					page_namespace as namespace,
			        page_title as title,
			        $epoch as value
			FROM $page, $revision
			WHERE page_namespace=".NS_MAIN." AND page_is_redirect=0
			  AND page_latest=rev_id";
	}

	function sortDescending() {
		return false;
	}

	function formatResult( $skin, $result ) {
		global $wgLang, $wgContLang;

		$d = $wgLang->timeanddate( wfTimestamp( TS_MW, $result->value ), true );
		$title = Title::makeTitle( $result->namespace, $result->title );
		$link = $skin->makeKnownLinkObj( $title, htmlspecialchars( $wgContLang->convert( $title->getPrefixedText() ) ) );
		return wfSpecialList($link, htmlspecialchars($d) );
	}
}

function wfSpecialAncientpages() {
	list( $limit, $offset ) = wfCheckLimits();

	$app = new AncientPagesPage();

	$app->doQuery( $offset, $limit );
}
