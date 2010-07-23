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
 * A special page listing redirects to redirecting page.
 * The software will automatically not follow double redirects, to prevent loops.
 *
 * @file
 * @ingroup SpecialPage
 */
class DoubleRedirectsPage extends PageQueryPage {

	function getName() {
		return 'DoubleRedirects';
	}

	function isExpensive( ) { return true; }
	function isSyndicated() { return false; }

	function getPageHeader( ) {
		return wfMsgExt( 'doubleredirectstext', array( 'parse' ) );
	}

	function getSQLText( &$dbr, $namespace = null, $title = null ) {

		list( $page, $redirect ) = $dbr->tableNamesN( 'page', 'redirect' );

		$limitToTitle = !( $namespace === null && $title === null );
		$sql = $limitToTitle ? "SELECT" : "SELECT 'DoubleRedirects' as type," ;
		$sql .=
			 " pa.page_namespace as namespace, pa.page_title as title," .
			 " pb.page_namespace as nsb, pb.page_title as tb," .
			 " pc.page_namespace as nsc, pc.page_title as tc" .
		   " FROM $redirect AS ra, $redirect AS rb, $page AS pa, $page AS pb, $page AS pc" .
		   " WHERE ra.rd_from=pa.page_id" .
			 " AND ra.rd_namespace=pb.page_namespace" .
			 " AND ra.rd_title=pb.page_title" .
			 " AND rb.rd_from=pb.page_id" .
			 " AND rb.rd_namespace=pc.page_namespace" .
			 " AND rb.rd_title=pc.page_title";

		if( $limitToTitle ) {
			$encTitle = $dbr->addQuotes( $title );
			$sql .= " AND pa.page_namespace=$namespace" .
					" AND pa.page_title=$encTitle";
		}

		return $sql;
	}

	function getSQL() {
		$dbr = wfGetDB( DB_SLAVE );
		return $this->getSQLText( $dbr );
	}

	function getOrder() {
		return '';
	}

	function formatResult( $skin, $result ) {
		global $wgContLang;

		$fname = 'DoubleRedirectsPage::formatResult';
		$titleA = Title::makeTitle( $result->namespace, $result->title );

		if ( $result && !isset( $result->nsb ) ) {
			$dbr = wfGetDB( DB_SLAVE );
			$sql = $this->getSQLText( $dbr, $result->namespace, $result->title );
			$res = $dbr->query( $sql, $fname );
			if ( $res ) {
				$result = $dbr->fetchObject( $res );
				$dbr->freeResult( $res );
			}
		}
		if ( !$result ) {
			return '<del>' . $skin->link( $titleA, null, array(), array( 'redirect' => 'no' ) ) . '</del>';
		}

		$titleB = Title::makeTitle( $result->nsb, $result->tb );
		$titleC = Title::makeTitle( $result->nsc, $result->tc );

		$linkA = $skin->linkKnown(
			$titleA,
			null,
			array(),
			array( 'redirect' => 'no' )
		);
		$edit = $skin->linkKnown(
			$titleA,
			wfMsgExt( 'parentheses', array( 'escape' ), wfMsg( 'editlink' ) ),
			array(),
			array(
				'redirect' => 'no',
				'action' => 'edit'
			)
		);
		$linkB = $skin->linkKnown(
			$titleB,
			null,
			array(),
			array( 'redirect' => 'no' )
		);
		$linkC = $skin->linkKnown( $titleC );
		$arr = $wgContLang->getArrow() . $wgContLang->getDirMark();

		return( "{$linkA} {$edit} {$arr} {$linkB} {$arr} {$linkC}" );
	}
}

/**
 * constructor
 */
function wfSpecialDoubleRedirects() {
	list( $limit, $offset ) = wfCheckLimits();

	$sdr = new DoubleRedirectsPage();

	return $sdr->doQuery( $offset, $limit );

}
