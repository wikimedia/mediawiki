<?php
/**
 * Implements Special:Wantedcategories
 *
 * Copyright © 2005 Ævar Arnfjörð Bjarmason
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 * @ingroup SpecialPage
 */

/**
 * A querypage to list the most wanted categories - implements Special:Wantedcategories
 *
 * @ingroup SpecialPage
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
