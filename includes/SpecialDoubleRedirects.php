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
class DoubleRedirectsPage extends PageQueryPage {

	function getName() {
		return 'DoubleRedirects';
	}
	
	function isExpensive( ) { return true; }
	function isSyndicated() { return false; }

	function getPageHeader( ) {
		#FIXME : probably need to add a backlink to the maintenance page.
		return '<p>'.wfMsg("doubleredirectstext")."</p><br />\n";
	}

	function getSQL() {
		$dbr =& wfGetDB( DB_SLAVE );
		extract( $dbr->tableNames( 'page', 'pagelinks' ) );

		$sql = "SELECT 'DoubleRedirects' as type," .
		         " pa.page_namespace as namespace, pa.page_title as title," .
			     " pb.page_namespace as nsb, pb.page_title as tb," .
				 " pc.page_namespace as nsc, pc.page_title as tc" .
			   " FROM $pagelinks AS la, $pagelinks AS lb, $page AS pa, $page AS pb, $page AS pc" .
			   " WHERE pa.page_is_redirect=1 AND pb.page_is_redirect=1" .
			     " AND la.pl_from=pa.page_id" .
				 " AND la.pl_namespace=pb.page_namespace" .
				 " AND la.pl_title=pb.page_title" .
				 " AND lb.pl_from=pb.page_id" .
				 " AND lb.pl_namespace=pc.page_namespace" .
				 " AND lb.pl_title=pc.page_title";
		return $sql;
	}

	function getOrder() {
		return '';
	}
	
	function formatResult( $skin, $result ) {
		$fname = 'DoubleRedirectsPage::formatResult';
		$titleA = Title::makeTitle( $result->namespace, $result->title );

		if ( $result && !isset( $result->nsb ) ) {
			$dbr =& wfGetDB( DB_SLAVE );
			extract( $dbr->tableNames( 'page', 'pagelinks' ) );
			$encTitle = $dbr->addQuotes( $result->title );

			$sql = "SELECT pa.page_namespace as namespace, pa.page_title as title," .
					 " pb.page_namespace as nsb, pb.page_title as tb," .
					 " pc.page_namespace as nsc, pc.page_title as tc" .
				   " FROM $pagelinks AS la, $pagelinks AS lb, $page AS pa, $page AS pb, $page AS pc" .
				   " WHERE pa.page_is_redirect=1 AND pb.page_is_redirect=1" .
					 " AND la.pl_from=pa.page_id" .
					 " AND la.pl_namespace=pb.page_namespace" .
					 " AND la.pl_title=pb.page_title" .
					 " AND lb.pl_from=pb.page_id" .
					 " AND lb.pl_namespace=pc.page_namespace" .
					 " AND lb.pl_title=pc.page_title" .
					 " AND pa.page_namespace={$result->namespace}" .
					 " AND pa.page_title=$encTitle";
			$res = $dbr->query( $sql, $fname );
			if ( $res ) {
				$result = $dbr->fetchObject( $res );
			}
		}
		if ( !$result ) {
			return '';
		}
		
		$titleB = Title::makeTitle( $result->nsb, $result->tb );
		$titleC = Title::makeTitle( $result->nsc, $result->tc );

		$linkA = $skin->makeKnownLinkObj( $titleA,'', 'redirect=no' );
		$edit = $skin->makeBrokenLinkObj( $titleA, "(".wfMsg("qbedit").")" , 'redirect=no');
		$linkB = $skin->makeKnownLinkObj( $titleB, '', 'redirect=no' );
		$linkC = $skin->makeKnownLinkObj( $titleC );
		
		return "$linkA $edit &rarr; $linkB &rarr; $linkC";
	}
}

/**
 * constructor
 */
function wfSpecialDoubleRedirects() {
	list( $limit, $offset ) = wfCheckLimits();
	
	$sdr = new DoubleRedirectsPage();
	
	return $sdr->doQuery( $offset, $limit );

}
?>
