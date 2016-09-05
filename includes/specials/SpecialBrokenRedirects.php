<?php
/**
 * Implements Special:Brokenredirects
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
 * A special page listing redirects to non existent page. Those should be
 * fixed to point to an existing page.
 *
 * @ingroup SpecialPage
 */
class BrokenRedirectsPage extends QueryPage {
	function __construct( $name = 'BrokenRedirects' ) {
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
		return $this->msg( 'brokenredirectstext' )->parseAsBlock();
	}

	public function getQueryInfo() {
		$dbr = wfGetDB( DB_REPLICA );

		return [
			'tables' => [
				'redirect',
				'p1' => 'page',
				'p2' => 'page',
			],
			'fields' => [
				'namespace' => 'p1.page_namespace',
				'title' => 'p1.page_title',
				'value' => 'p1.page_title',
				'rd_namespace',
				'rd_title',
			],
			'conds' => [
				// Exclude pages that don't exist locally as wiki pages,
				// but aren't "broken" either.
				// Special pages and interwiki links
				'rd_namespace >= 0',
				'rd_interwiki IS NULL OR rd_interwiki = ' . $dbr->addQuotes( '' ),
				'p2.page_namespace IS NULL',
			],
			'join_conds' => [
				'p1' => [ 'JOIN', [
					'rd_from=p1.page_id',
				] ],
				'p2' => [ 'LEFT JOIN', [
					'rd_namespace=p2.page_namespace',
					'rd_title=p2.page_title'
				] ],
			],
		];
	}

	/**
	 * @return array
	 */
	function getOrderFields() {
		return [ 'rd_namespace', 'rd_title', 'rd_from' ];
	}

	/**
	 * @param Skin $skin
	 * @param object $result Result row
	 * @return string
	 */
	function formatResult( $skin, $result ) {
		$fromObj = Title::makeTitle( $result->namespace, $result->title );
		if ( isset( $result->rd_title ) ) {
			$toObj = Title::makeTitle( $result->rd_namespace, $result->rd_title );
		} else {
			$blinks = $fromObj->getBrokenLinksFrom(); # TODO: check for redirect, not for links
			if ( $blinks ) {
				$toObj = $blinks[0];
			} else {
				$toObj = false;
			}
		}

		// $toObj may very easily be false if the $result list is cached
		if ( !is_object( $toObj ) ) {
			return '<del>' . Linker::link( $fromObj ) . '</del>';
		}

		$from = Linker::linkKnown(
			$fromObj,
			null,
			[],
			[ 'redirect' => 'no' ]
		);
		$links = [];
		// if the page is editable, add an edit link
		if (
			// check user permissions
			$this->getUser()->isAllowed( 'edit' ) &&
			// check, if the content model is editable through action=edit
			ContentHandler::getForTitle( $fromObj )->supportsDirectEditing()
		) {
			$links[] = Linker::linkKnown(
				$fromObj,
				$this->msg( 'brokenredirects-edit' )->escaped(),
				[],
				[ 'action' => 'edit' ]
			);
		}
		$to = Linker::link(
			$toObj,
			null,
			[],
			[],
			[ 'broken' ]
		);
		$arr = $this->getLanguage()->getArrow();

		$out = $from . $this->msg( 'word-separator' )->escaped();

		if ( $this->getUser()->isAllowed( 'delete' ) ) {
			$links[] = Linker::linkKnown(
				$fromObj,
				$this->msg( 'brokenredirects-delete' )->escaped(),
				[],
				[ 'action' => 'delete' ]
			);
		}

		if ( $links ) {
			$out .= $this->msg( 'parentheses' )->rawParams( $this->getLanguage()
				->pipeList( $links ) )->escaped();
		}
		$out .= " {$arr} {$to}";

		return $out;
	}

	/**
	 * Cache page content model for performance
	 *
	 * @param IDatabase $db
	 * @param ResultWrapper $res
	 */
	function preprocessResults( $db, $res ) {
		if ( !$res->numRows() ) {
			return;
		}

		$batch = new LinkBatch;
		foreach ( $res as $row ) {
			$batch->add( $row->namespace, $row->title );
		}
		$batch->execute();

		// Back to start for display
		$res->seek( 0 );
	}

	protected function getGroupName() {
		return 'maintenance';
	}
}
