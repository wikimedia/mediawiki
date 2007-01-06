<?php
/**
 * @package MediaWiki
 * @subpackage SpecialPage
 *
 * @author Ashar Voultoiz <hashar@altern.org>
 * @licence GNU General Public Licence 2.0 or later
 *
 */

class ListinterwikisPage extends QueryPage {

	function getName() { return( 'Listinterwikis' ); }
	function isExpensive() { return false; }
	function isSyndicated() { return false; }
	function sortDescending() { return false; }

	/**
	 * We have a little fun with title, namespace but its required by QueryPage.
	*/
	function getSQL() {
		$dbr =& wfGetDB( DB_SLAVE );
		$iw = $dbr->tableName( 'interwiki' );
		$sql = "SELECT 'Listinterwikis' AS type, iw_url AS title, 0 AS namespace, iw_prefix AS value, iw_local, iw_trans FROM $iw";
		return $sql;
	}

	function formatResult( $skin, $result ) {
		return
			$result->value
			. ' ... '
			. $skin->makeExternalLink($result->title, $result->title);
	}
}

function wfSpecialListinterwikis() {
	list( $limit, $offset ) = wfCheckLimits();
	$lip = new ListinterwikisPage();
	$lip->doQuery( $offset, $limit );
}
?>

