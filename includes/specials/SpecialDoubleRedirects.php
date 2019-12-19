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

use Wikimedia\Rdbms\IResultWrapper;
use Wikimedia\Rdbms\IDatabase;

/**
 * A special page listing redirects to redirecting page.
 * The software will automatically not follow double redirects, to prevent loops.
 *
 * @ingroup SpecialPage
 */
class DoubleRedirectsPage extends QueryPage {
	function __construct( $name = 'DoubleRedirects' ) {
		parent::__construct( $name );
	}

	public function isExpensive() {
		return true;
	}

	function isSyndicated() {
		return false;
	}

	function sortDescending() {
		return false;
	}

	function getPageHeader() {
		return $this->msg( 'doubleredirectstext' )->parseAsBlock();
	}

	function reallyGetQueryInfo( $namespace = null, $title = null ) {
		$limitToTitle = !( $namespace === null && $title === null );
		$dbr = wfGetDB( DB_REPLICA );
		$retval = [
			'tables' => [
				'ra' => 'redirect',
				'rb' => 'redirect',
				'pa' => 'page',
				'pb' => 'page'
			],
			'fields' => [
				'namespace' => 'pa.page_namespace',
				'title' => 'pa.page_title',

				'b_namespace' => 'pb.page_namespace',
				'b_title' => 'pb.page_title',

				// Select fields from redirect instead of page. Because there may
				// not actually be a page table row for this target (e.g. for interwiki redirects)
				'c_namespace' => 'rb.rd_namespace',
				'c_title' => 'rb.rd_title',
				'c_fragment' => 'rb.rd_fragment',
				'c_interwiki' => 'rb.rd_interwiki',
			],
			'conds' => [
				'ra.rd_from = pa.page_id',

				// Filter out redirects where the target goes interwiki (T42353).
				// This isn't an optimization, it is required for correct results,
				// otherwise a non-double redirect like Bar -> w:Foo will show up
				// like "Bar -> Foo -> w:Foo".

				// Need to check both NULL and "" for some reason,
				// apparently either can be stored for non-iw entries.
				'ra.rd_interwiki IS NULL OR ra.rd_interwiki = ' . $dbr->addQuotes( '' ),

				'pb.page_namespace = ra.rd_namespace',
				'pb.page_title = ra.rd_title',

				'rb.rd_from = pb.page_id',
			]
		];

		if ( $limitToTitle ) {
			$retval['conds']['pa.page_namespace'] = $namespace;
			$retval['conds']['pa.page_title'] = $title;
		}

		return $retval;
	}

	public function getQueryInfo() {
		return $this->reallyGetQueryInfo();
	}

	function getOrderFields() {
		return [ 'ra.rd_namespace', 'ra.rd_title' ];
	}

	/**
	 * @param Skin $skin
	 * @param object $result Result row
	 * @return string
	 */
	function formatResult( $skin, $result ) {
		// If no Title B or C is in the query, it means this came from
		// querycache (which only saves the 3 columns for title A).
		// That does save the bulk of the query cost, but now we need to
		// get a little more detail about each individual entry quickly
		// using the filter of reallyGetQueryInfo.
		$deep = false;
		if ( $result ) {
			if ( isset( $result->b_namespace ) ) {
				$deep = $result;
			} else {
				$dbr = wfGetDB( DB_REPLICA );
				$qi = $this->reallyGetQueryInfo(
					$result->namespace,
					$result->title
				);
				$res = $dbr->select(
					$qi['tables'],
					$qi['fields'],
					$qi['conds'],
					__METHOD__
				);

				if ( $res ) {
					$deep = $dbr->fetchObject( $res ) ?: false;
				}
			}
		}

		$titleA = Title::makeTitle( $result->namespace, $result->title );

		$linkRenderer = $this->getLinkRenderer();
		if ( !$deep ) {
			return '<del>' . $linkRenderer->makeLink( $titleA, null, [], [ 'redirect' => 'no' ] ) . '</del>';
		}

		// if the page is editable, add an edit link
		if (
			// check user permissions
			$this->getUser()->isAllowed( 'edit' ) &&
			// check, if the content model is editable through action=edit
			ContentHandler::getForTitle( $titleA )->supportsDirectEditing()
		) {
			$edit = $linkRenderer->makeKnownLink(
				$titleA,
				$this->msg( 'parentheses', $this->msg( 'editlink' )->text() )->text(),
				[],
				[ 'action' => 'edit' ]
			);
		} else {
			$edit = '';
		}

		$linkA = $linkRenderer->makeKnownLink(
			$titleA,
			null,
			[],
			[ 'redirect' => 'no' ]
		);

		$titleB = Title::makeTitle( $deep->b_namespace, $deep->b_title );
		$linkB = $linkRenderer->makeKnownLink(
			$titleB,
			null,
			[],
			[ 'redirect' => 'no' ]
		);

		$titleC = Title::makeTitle(
			$deep->c_namespace,
			$deep->c_title,
			$deep->c_fragment,
			$deep->c_interwiki
		);
		$linkC = $linkRenderer->makeKnownLink( $titleC, $titleC->getFullText() );

		$lang = $this->getLanguage();
		$arr = $lang->getArrow() . $lang->getDirMark();

		return ( "{$linkA} {$edit} {$arr} {$linkB} {$arr} {$linkC}" );
	}

	/**
	 * Cache page content model and gender distinction for performance
	 *
	 * @param IDatabase $db
	 * @param IResultWrapper $res
	 */
	function preprocessResults( $db, $res ) {
		if ( !$res->numRows() ) {
			return;
		}

		$batch = new LinkBatch;
		foreach ( $res as $row ) {
			$batch->add( $row->namespace, $row->title );
			if ( isset( $row->b_namespace ) ) {
				// lazy loaded when using cached results
				$batch->add( $row->b_namespace, $row->b_title );
			}
			if ( isset( $row->c_interwiki ) && !$row->c_interwiki ) {
				// lazy loaded when using cached result, not added when interwiki link
				$batch->add( $row->c_namespace, $row->c_title );
			}
		}
		$batch->execute();

		// Back to start for display
		$res->seek( 0 );
	}

	protected function getGroupName() {
		return 'maintenance';
	}
}
