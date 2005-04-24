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
		extract( $dbr->tableNames( 'cur', 'links' ) );

		$sql = "SELECT 'DoubleRedirects' as type," .
		         " ca.cur_namespace as namespace, ca.cur_title as title," .
			     " cb.cur_namespace as nsb, cb.cur_title as tb," .
				 " cc.cur_namespace as nsc, cc.cur_title as tc" .
			   " FROM $links AS la, $links AS lb, $cur AS ca, $cur AS cb, $cur AS cc" .
			   " WHERE ca.cur_is_redirect=1 AND cb.cur_is_redirect=1" .
			     " AND la.l_from=ca.cur_id" .
				 " AND la.l_to=cb.cur_id" .
				 " AND lb.l_from=cb.cur_id" .
				 " AND lb.l_to=cc.cur_id";
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
			extract( $dbr->tableNames( 'cur', 'links' ) );
			$encTitle = $dbr->addQuotes( $result->title );

			$sql = "SELECT ca.cur_namespace as namespace, ca.cur_title as title," .
					 " cb.cur_namespace as nsb, cb.cur_title as tb," .
					 " cc.cur_namespace as nsc, cc.cur_title as tc" .
				   " FROM $links AS la, $links AS lb, $cur AS ca, $cur AS cb, $cur AS cc" .
				   " WHERE ca.cur_is_redirect=1 AND cb.cur_is_redirect=1" .
					 " AND la.l_from=ca.cur_id" .
					 " AND la.l_to=cb.cur_id" .
					 " AND lb.l_from=cb.cur_id" .
					 " AND lb.l_to=cc.cur_id" .
					 " AND ca.cur_namespace={$result->namespace}" .
					 " AND ca.cur_title=$encTitle";
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
