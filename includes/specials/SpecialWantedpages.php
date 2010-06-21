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
 * implements Special:Wantedpages
 * @ingroup SpecialPage
 */
class WantedPagesPage extends WantedQueryPage {
	var $nlinks;

	function WantedPagesPage( $inc = false, $nlinks = true ) {
		$this->setListoutput( $inc );
		$this->nlinks = $nlinks;
	}

	function getName() {
		return 'Wantedpages';
	}

	function getSQL() {
		global $wgWantedPagesThreshold;
		$count = $wgWantedPagesThreshold - 1;
		$dbr = wfGetDB( DB_SLAVE );
		$pagelinks = $dbr->tableName( 'pagelinks' );
		$page      = $dbr->tableName( 'page' );
		$sql = "SELECT 'Wantedpages' AS type,
				pl_namespace AS namespace,
				pl_title AS title,
				COUNT(*) AS value
			FROM $pagelinks
			LEFT JOIN $page AS pg1
			ON pl_namespace = pg1.page_namespace AND pl_title = pg1.page_title
			LEFT JOIN $page AS pg2
			ON pl_from = pg2.page_id
			WHERE pg1.page_namespace IS NULL
			AND pl_namespace NOT IN ( " . NS_USER . ", ". NS_USER_TALK . ")
			AND pg2.page_namespace != " . NS_MEDIAWIKI . "
			GROUP BY pl_namespace, pl_title
			HAVING COUNT(*) > $count";

		wfRunHooks( 'WantedPages::getSQL', array( &$this, &$sql ) );
		return $sql;
	}
}

/**
 * constructor
 */
function wfSpecialWantedpages( $par = null, $specialPage ) {
	$inc = $specialPage->including();

	if ( $inc ) {
		@list( $limit, $nlinks ) = explode( '/', $par, 2 );
		$limit = (int)$limit;
		$nlinks = $nlinks === 'nlinks';
		$offset = 0;
	} else {
		list( $limit, $offset ) = wfCheckLimits();
		$nlinks = true;
	}

	$wpp = new WantedPagesPage( $inc, $nlinks );

	$wpp->doQuery( $offset, $limit, !$inc );
}
