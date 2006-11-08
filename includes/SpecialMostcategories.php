<?php
/**
 * @package MediaWiki
 * @subpackage SpecialPage
 *
 * @author Ævar Arnfjörð Bjarmason <avarab@gmail.com>
 * @copyright Copyright © 2005, Ævar Arnfjörð Bjarmason
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License 2.0 or later
 */

/**
 * @package MediaWiki
 * @subpackage SpecialPage
 */
class MostcategoriesPage extends QueryPage {

	function getName() { return 'Mostcategories'; }
	function isExpensive() { return true; }
	function isSyndicated() { return false; }

	function getSQL() {
		$dbr =& wfGetDB( DB_SLAVE );
		extract( $dbr->tableNames( 'categorylinks', 'page' ) );
		return
			"
			SELECT
			 	'Mostcategories' as type,
				page_namespace as namespace,
				page_title as title,
				COUNT(*) as value
			FROM $categorylinks
			LEFT JOIN $page ON cl_from = page_id
			WHERE page_namespace = " . NS_MAIN . "
			GROUP BY 1,2,3
			HAVING COUNT(*) > 1
			";
	}

	function formatResult( $result ) {
		global $wgContLang, $wgLang;

		$nt = Title::makeTitle( $result->namespace, $result->title );
		$text = $wgContLang->convert( $nt->getPrefixedText() );

		$plink = Linker::makeKnownLink( $nt->getPrefixedText(), $text );

		$nl = wfMsgExt( 'ncategories', array( 'parsemag', 'escape' ),
			$wgLang->formatNum( $result->value ) );

		$nlink = Linker::makeKnownLink( $wgContLang->specialPage( 'Categories' ),
			$nl, 'article=' . $nt->getPrefixedURL() );

		return wfSpecialList($plink, $nlink);
	}
}

/**
 * constructor
 */
function wfSpecialMostcategories() {
	list( $limit, $offset ) = wfCheckLimits();

	$wpp = new MostcategoriesPage();

	$wpp->doQuery( $offset, $limit );
}

?>
