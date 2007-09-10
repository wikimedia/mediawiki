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

	function getSQLText( &$dbr, $namespace = null, $title = null ) {
		
		extract( $dbr->tableNames( 'page', 'pagelinks' ) );

		$limitToTitle = !( $namespace === null && $title === null );
		$sql = $limitToTitle ? "SELECT" : "SELECT 'DoubleRedirects' as type," ;
		$sql .=
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

		if( $limitToTitle ) {
			$encTitle = $dbr->addQuotes( $title );
			$sql .= " AND pa.page_namespace=$namespace" .
					" AND pa.page_title=$encTitle";
		}

		return $sql;
	}
	
	function getSQL() {
		$dbr =& wfGetDB( DB_SLAVE );
		return $this->getSQLText( $dbr );
	}

	function getOrder() {
		return '';
	}

	function formatResult( $skin, $result ) {
		global $wgContLang;
	
		$fname = 'DoubleRedirectsPage::formatResult';
		$titleA = Title::makeTitle( $result->namespace, $result->title );

		if ( $result && !isset( $result->nsb ) ) {
			$dbr =& wfGetDB( DB_SLAVE );
			$sql = $this->getSQLText( $dbr, $result->namespace, $result->title );
			$res = $dbr->query( $sql, $fname );
			if ( $res ) {
				$result = $dbr->fetchObject( $res );
				$dbr->freeResult( $res );
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
		$arr = $wgContLang->getArrow() . $wgContLang->getDirMark();

		return( "{$linkA} {$edit} {$arr} {$linkB} {$arr} {$linkC}" );
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
