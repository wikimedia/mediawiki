<?php
/**
 * @file
 * @ingroup SpecialPage
 */

/**
 * A querypage to list the most wanted categories - implements Special:Wantedcategories
 *
 * @ingroup SpecialPage
 *
 * @author Ævar Arnfjörð Bjarmason <avarab@gmail.com>
 * @copyright Copyright © 2005, Ævar Arnfjörð Bjarmason
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License 2.0 or later
 */
class WantedCategoriesPage extends WantedQueryPage {

	function getName() {
		return 'Wantedcategories';
	}

	function getSQL() {
		$dbr = wfGetDB( DB_SLAVE );
		list( $categorylinks, $page ) = $dbr->tableNamesN( 'categorylinks', 'page' );
		$name = $dbr->addQuotes( $this->getName() );
		return
			"
			SELECT
				$name as type,
				" . NS_CATEGORY . " as namespace,
				cl_to as title,
				COUNT(*) as value
			FROM $categorylinks
			LEFT JOIN $page ON cl_to = page_title AND page_namespace = ". NS_CATEGORY ."
			WHERE page_title IS NULL
			GROUP BY cl_to
			";
	}

	function formatResult( $skin, $result ) {
		global $wgLang, $wgContLang;

		$nt = Title::makeTitle( $result->namespace, $result->title );
		$text = htmlspecialchars( $wgContLang->convert( $nt->getText() ) );

		$plink = $this->isCached() ?
			$skin->link( $nt, $text ) :
			$skin->link(
				$nt,
				$text,
				array(),
				array(),
				array( 'broken' )
			);

		$nlinks = wfMsgExt( 'nmembers', array( 'parsemag', 'escape'),
			$wgLang->formatNum( $result->value ) );
		return wfSpecialList($plink, $nlinks);
	}
}

/**
 * constructor
 */
function wfSpecialWantedCategories() {
	list( $limit, $offset ) = wfCheckLimits();

	$wpp = new WantedCategoriesPage();

	$wpp->doQuery( $offset, $limit );
}
