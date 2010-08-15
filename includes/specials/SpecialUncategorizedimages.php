<?php
/**
 * Implements Special:Uncategorizedimages
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
 * @author Rob Church <robchur@gmail.com>
 */

/**
 * Special page lists images which haven't been categorised
 *
 * @ingroup SpecialPage
 */
class UncategorizedImagesPage extends ImageQueryPage {

	function getName() {
		return 'Uncategorizedimages';
	}

	function sortDescending() {
		return false;
	}

	function isExpensive() {
		return true;
	}

	function isSyndicated() {
		return false;
	}

	function getSQL() {
		$dbr = wfGetDB( DB_SLAVE );
		list( $page, $categorylinks ) = $dbr->tableNamesN( 'page', 'categorylinks' );
		$ns = NS_FILE;

		return "SELECT 'Uncategorizedimages' AS type, page_namespace AS namespace,
				page_title AS title, page_title AS value
				FROM {$page} LEFT JOIN {$categorylinks} ON page_id = cl_from
				WHERE cl_from IS NULL AND page_namespace = {$ns} AND page_is_redirect = 0";
	}

}

function wfSpecialUncategorizedimages() {
	$uip = new UncategorizedImagesPage();
	list( $limit, $offset ) = wfCheckLimits();
	return $uip->doQuery( $offset, $limit );
}
