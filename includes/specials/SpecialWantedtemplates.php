<?php
/**
 * @file
 * @ingroup SpecialPage
 */

/**
 * A querypage to list the most wanted templates - implements Special:Wantedtemplates
 * based on SpecialWantedcategories.php by Ævar Arnfjörð Bjarmason <avarab@gmail.com>
 * makeWlhLink() taken from SpecialMostlinkedtemplates by Rob Church <robchur@gmail.com>
 *
 * @ingroup SpecialPage
 *
 * @author Danny B.
 * @copyright Copyright © 2008, Danny B.
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License 2.0 or later
 */
class WantedTemplatesPage extends WantedQueryPage {

	function getName() {
		return 'Wantedtemplates';
	}

	function getSQL() {
		$dbr = wfGetDB( DB_SLAVE );
		list( $templatelinks, $page ) = $dbr->tableNamesN( 'templatelinks', 'page' );
		$name = $dbr->addQuotes( $this->getName() );
		return
			"
			  SELECT $name as type, 
			         tl_namespace as namespace,
			         tl_title as title,
			         COUNT(*) as value
			    FROM $templatelinks LEFT JOIN
			         $page ON tl_title = page_title AND tl_namespace = page_namespace
			   WHERE page_title IS NULL AND tl_namespace = ". NS_TEMPLATE ."
			GROUP BY tl_namespace, tl_title
			";
	}
}

/**
 * constructor
 */
function wfSpecialWantedTemplates() {
	list( $limit, $offset ) = wfCheckLimits();

	$wpp = new WantedTemplatesPage();

	$wpp->doQuery( $offset, $limit );
}
