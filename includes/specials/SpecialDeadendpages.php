<?php
/**
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
 */

/**
 * @file
 * @ingroup SpecialPage
 */
class DeadendPagesPage extends PageQueryPage {

	function getName( ) {
		return "Deadendpages";
	}

	function getPageHeader() {
		return wfMsgExt( 'deadendpagestext', array( 'parse' ) );
	}

	/**
	 * LEFT JOIN is expensive
	 *
	 * @return true
	 */
	function isExpensive( ) {
		return 1;
	}

	function isSyndicated() { return false; }

	/**
	 * @return false
	 */
	function sortDescending() {
		return false;
	}

	/**
	 * @return string an sqlquery
	 */
	function getSQL() {
		$dbr = wfGetDB( DB_SLAVE );
		list( $page, $pagelinks ) = $dbr->tableNamesN( 'page', 'pagelinks' );
		return "SELECT 'Deadendpages' as type, page_namespace AS namespace, page_title as title, page_title AS value " .
	"FROM $page LEFT JOIN $pagelinks ON page_id = pl_from " .
	"WHERE pl_from IS NULL " .
	"AND page_namespace = 0 " .
	"AND page_is_redirect = 0";
	}
}

/**
 * Constructor
 */
function wfSpecialDeadendpages() {

	list( $limit, $offset ) = wfCheckLimits();

	$depp = new DeadendPagesPage();

	return $depp->doQuery( $offset, $limit );
}
