<?php
/**
 * Implements Special:DoubleRedirects
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
 * A special page listing redirects to redirecting page.
 * The software will automatically not follow double redirects, to prevent loops.
 *
 * @ingroup SpecialPage
 */
class DoubleRedirectsPage extends PageQueryPage {

	function __construct( $name = 'DoubleRedirects' ) {
		parent::__construct( $name );
	}

	function isExpensive() { return true; }
	function isSyndicated() { return false; }
	function sortDescending() { return false; }

	function getPageHeader() {
		return $this->msg( 'doubleredirectstext' )->parseAsBlock();
	}

	function reallyGetQueryInfo( $namespace = null, $title = null ) {
		$limitToTitle = !( $namespace === null && $title === null );
		$retval = array (
			'tables' => array ( 'ra' => 'redirect',
					'rb' => 'redirect', 'pa' => 'page',
					'pb' => 'page', 'pc' => 'page' ),
			'fields' => array ( 'pa.page_namespace AS namespace',
					'pa.page_title AS title',
					'pa.page_title AS value',
					'pb.page_namespace AS nsb',
					'pb.page_title AS tb',
					'pc.page_namespace AS nsc',
					'pc.page_title AS tc' ),
			'conds' => array ( 'ra.rd_from = pa.page_id',
					'pb.page_namespace = ra.rd_namespace',
					'pb.page_title = ra.rd_title',
					'rb.rd_from = pb.page_id',
					'pc.page_namespace = rb.rd_namespace',
					'pc.page_title = rb.rd_title' )
		);
		if ( $limitToTitle ) {
			$retval['conds']['pa.page_namespace'] = $namespace;
			$retval['conds']['pa.page_title'] = $title;
		}
		return $retval;
	}

	function getQueryInfo() {
		return $this->reallyGetQueryInfo();
	}

	function getOrderFields() {
		return array ( 'ra.rd_namespace', 'ra.rd_title' );
	}

	function formatResult( $skin, $result ) {
		$titleA = Title::makeTitle( $result->namespace, $result->title );

		if ( $result && !isset( $result->nsb ) ) {
			$dbr = wfGetDB( DB_SLAVE );
			$qi = $this->reallyGetQueryInfo( $result->namespace,
					$result->title );
			$res = $dbr->select($qi['tables'], $qi['fields'],
					$qi['conds'], __METHOD__ );
			if ( $res ) {
				$result = $dbr->fetchObject( $res );
			}
		}
		if ( !$result ) {
			return '<del>' . Linker::link( $titleA, null, array(), array( 'redirect' => 'no' ) ) . '</del>';
		}

		$titleB = Title::makeTitle( $result->nsb, $result->tb );
		$titleC = Title::makeTitle( $result->nsc, $result->tc );

		$linkA = Linker::linkKnown(
			$titleA,
			null,
			array(),
			array( 'redirect' => 'no' )
		);

		$edit = Linker::linkKnown(
			$titleA,
			$this->msg( 'parentheses', $this->msg( 'editlink' )->text() )->escaped(),
			array(),
			array(
				'redirect' => 'no',
				'action' => 'edit'
			)
		);

		$linkB = Linker::linkKnown(
			$titleB,
			null,
			array(),
			array( 'redirect' => 'no' )
		);

		$linkC = Linker::linkKnown( $titleC );

		$lang = $this->getLanguage();
		$arr = $lang->getArrow() . $lang->getDirMark();

		return( "{$linkA} {$edit} {$arr} {$linkB} {$arr} {$linkC}" );
	}
}
