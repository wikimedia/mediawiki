<?php
/**
 * A special page to show pages ordered by the number of pages linking to them
 *
 * @package MediaWiki
 * @subpackage SpecialPage
 *
 * @author Ævar Arnfjörð Bjarmason <avarab@gmail.com>
 * @copyright Copyright © 2005, Ævar Arnfjörð Bjarmason
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License 2.0 or later
 */

/* */
require_once 'QueryPage.php';

/**
 * @package MediaWiki
 * @subpackage SpecialPage
 */
class MostlinkedPage extends QueryPage {

	function getName() { return 'Mostlinked'; }
	function isExpensive() { return true; }
	function isSyndicated() { return false; }

	/**
	 * Note: Getting page_namespace only works if $this->isCached() is false
	 */
	function getSQL() {
		$dbr =& wfGetDB( DB_SLAVE );
		extract( $dbr->tableNames( 'pagelinks', 'page' ) );
		return
			"SELECT 'Mostlinked' AS type,
				pl_namespace AS namespace,
				pl_title AS title,
				COUNT(*) AS value,

				page_namespace
			FROM $pagelinks
			LEFT JOIN $page ON pl_namespace=page_namespace AND pl_title=page_title
			GROUP BY pl_namespace,pl_title
			HAVING COUNT(*) > 1";
	}

	function formatResult( $skin, $result ) {
		global $wgContLang;

		$nt = Title::makeTitle( $result->namespace, $result->title );
		$text = $wgContLang->convert( $nt->getPrefixedText() );

		if ( $this->isCached() )
			$plink = $skin->makeKnownLink( $nt->getPrefixedText(), $text );
		else {
			$plink = is_null( $result->page_namespace )
				? $skin->makeBrokenLink( $nt->getPrefixedText(), $text )
				: $skin->makeKnownLink( $nt->getPrefixedText(), $text );
		}

		$nl = wfMsg( 'nlinks', $result->value );
		$nlink = $skin->makeKnownLink( $wgContLang->specialPage( 'Whatlinkshere' ), $nl, 'target=' . $nt->getPrefixedURL() );

		return "{$plink} ({$nlink})";
	}
}

/**
 * constructor
 */
function wfSpecialMostlinked() {
	list( $limit, $offset ) = wfCheckLimits();

	$wpp = new MostlinkedPage();

	$wpp->doQuery( $offset, $limit );
}

?>
