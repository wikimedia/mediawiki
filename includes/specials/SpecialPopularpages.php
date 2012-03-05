<?php
/**
 * Implements Special:PopularPages
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
 * A special page that list most viewed pages
 *
 * @ingroup SpecialPage
 */
class PopularPagesPage extends QueryPage {

	function getName() {
		return "Popularpages";
	}

	function isExpensive() {
		# page_counter is not indexed
		return true;
	}
	function isSyndicated() { return false; }

	function getSQL() {
		$dbr = wfGetDB( DB_SLAVE );
		$page = $dbr->tableName( 'page' );

		$query =
			"SELECT 'Popularpages' as type,
			        page_namespace as namespace,
			        page_title as title,
			        page_counter as value
			FROM $page ";
		$where =
			"WHERE page_is_redirect=0 AND page_namespace";

		global $wgContentNamespaces;
		if( empty( $wgContentNamespaces ) ) {
			$where .= '='.NS_MAIN;
		} else if( count( $wgContentNamespaces ) > 1 ) {
			$where .= ' in (' . implode( ', ', $wgContentNamespaces ) . ')';
		} else {
			$where .= '='.$wgContentNamespaces[0];
		}

		return $query . $where;
	}

	function formatResult( $skin, $result ) {
		global $wgLang, $wgContLang;
		$title = Title::makeTitle( $result->namespace, $result->title );
		$link = $skin->linkKnown(
			$title,
			htmlspecialchars( $wgContLang->convert( $title->getPrefixedText() ) )
		);
		$nv = wfMsgExt(
			'nviews',
			array( 'parsemag', 'escape'),
			$wgLang->formatNum( $result->value )
		);
		return wfSpecialList($link, $nv);
	}
}

/**
 * Constructor
 */
function wfSpecialPopularpages() {
	list( $limit, $offset ) = wfCheckLimits();

	$ppp = new PopularPagesPage();

	return $ppp->doQuery( $offset, $limit );
}
