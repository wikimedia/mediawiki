<?php
/**
 *
 * @package MediaWiki
 * @subpackage SpecialPage
 */

/**
 *
 * @package MediaWiki
 * @subpackage SpecialPage
 */
class BrokenRedirectsPage extends PageQueryPage {
	var $targets = array();

	function getName() {
		return 'BrokenRedirects';
	}

	function isExpensive( ) { return true; }
	function isSyndicated() { return false; }

	function getPageHeader( ) {
		global $wgOut;
		return $wgOut->parse( wfMsg( 'brokenredirectstext' ) );
	}

	function getSQL() {
		$dbr =& wfGetDB( DB_SLAVE );
		list( $page, $pagelinks ) = $dbr->tableNamesN( 'page', 'pagelinks' );

		$sql = "SELECT 'BrokenRedirects'  AS type,
		                p1.page_namespace AS namespace,
		                p1.page_title     AS title,
		                pl_namespace,
		                pl_title
		           FROM $pagelinks AS pl
                   JOIN $page p1 ON (p1.page_is_redirect=1 AND pl.pl_from=p1.page_id)
		      LEFT JOIN $page AS p2 ON (pl_namespace=p2.page_namespace AND pl_title=p2.page_title )
    		                WHERE p2.page_namespace IS NULL";
		return $sql;
	}

	function getOrder() {
		return '';
	}

	function formatResult( $skin, $result ) {
		global $wgUser, $wgContLang;
		
		$fromObj = Title::makeTitle( $result->namespace, $result->title );
		if ( isset( $result->pl_title ) ) {
			$toObj = Title::makeTitle( $result->pl_namespace, $result->pl_title );
		} else {
			$blinks = $fromObj->getBrokenLinksFrom();
			if ( $blinks ) {
				$toObj = $blinks[0];
			} else {
				$toObj = false;
			}
		}

		// $toObj may very easily be false if the $result list is cached
		if ( !is_object( $toObj ) ) {
			return '<s>' . $skin->makeLinkObj( $fromObj ) . '</s>';
		}

		$from = $skin->makeKnownLinkObj( $fromObj ,'', 'redirect=no' );
		$edit = $skin->makeKnownLinkObj( $fromObj, wfMsgHtml( 'brokenredirects-edit' ), 'action=edit' );
		$to   = $skin->makeBrokenLinkObj( $toObj );
		$arr = $wgContLang->getArrow();
		
		$out = "{$from} {$edit}";
		
		if( $wgUser->isAllowed( 'delete' ) ) {
			$delete = $skin->makeKnownLinkObj( $fromObj, wfMsgHtml( 'brokenredirects-delete' ), 'action=delete' );
			$out .= " {$delete}";
		}
		
		$out .= " {$arr} {$to}";
		return $out;
	}
}

/**
 * constructor
 */
function wfSpecialBrokenRedirects() {
	list( $limit, $offset ) = wfCheckLimits();

	$sbr = new BrokenRedirectsPage();

	return $sbr->doQuery( $offset, $limit );

}
?>
