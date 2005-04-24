<?php
/**
 *
 * @package MediaWiki
 * @subpackage SpecialPage
 */

/**
 *
 */
require_once('QueryPage.php');

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
		return '<p>'.wfMsg('brokenredirectstext')."</p><br />\n";
	}

	function getSQL() {
		$dbr =& wfGetDB( DB_SLAVE );
		extract( $dbr->tableNames( 'page', 'brokenlinks' ) );

		$sql = "SELECT 'BrokenRedirects' as type, page_namespace as namespace," .
			   "page_title as title, bl_to FROM $brokenlinks,$page " .
		       'WHERE page_is_redirect=1 AND bl_from=page_id ';
		return $sql;
	}

	function getOrder() {
		return '';
	}

	function formatResult( $skin, $result ) {
		global $wgContLang ;
		
		$fromObj = Title::makeTitle( $result->namespace, $result->title );
		if ( isset( $result->bl_to ) ) {
			$toObj = Title::newFromText( $result->bl_to );
		} else {
			$blinks = $fromObj->getBrokenLinksFrom();
			if ( $blinks ) {
				$toObj = $blinks[0];
			} else {
				$toObj = false;
			}
		}

		// $toObj may very easily be false if the $result list is cached
		if ( !is_object( $toObj ) || !is_object( $fromObj ) ) {
			return '';
		}

		$from = $skin->makeKnownLinkObj( $fromObj ,'', 'redirect=no' );
		$edit = $skin->makeBrokenLinkObj( $fromObj , "(".wfMsg("qbedit").")" , 'redirect=no');
		$to   = $skin->makeBrokenLinkObj( $toObj );
				
		return "$from $edit => $to";
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
