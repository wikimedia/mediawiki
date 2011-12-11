<?php
/**
 * Implements Special:Listredirects
 *
 * Copyright © 2006 Rob Church
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
 * Special:Listredirects - Lists all the redirects on the wiki.
 * @ingroup SpecialPage
 */
class ListredirectsPage extends QueryPage {

	function __construct( $name = 'Listredirects' ) {
		parent::__construct( $name );
	}

	function isExpensive() { return true; }
	function isSyndicated() { return false; }
	function sortDescending() { return false; }

	function getQueryInfo() {
		return array(
			'tables' => array( 'p1' => 'page', 'redirect', 'p2' => 'page' ),
			'fields' => array( 'p1.page_namespace AS namespace',
					'p1.page_title AS title',
					'p1.page_title AS value',
					'rd_namespace',
					'rd_title',
					'rd_fragment',
					'rd_interwiki',
					'p2.page_id AS redirid' ),
			'conds' => array( 'p1.page_is_redirect' => 1 ),
			'join_conds' => array( 'redirect' => array(
					'LEFT JOIN', 'rd_from=p1.page_id' ),
				'p2' => array( 'LEFT JOIN', array(
					'p2.page_namespace=rd_namespace',
					'p2.page_title=rd_title' ) ) )
		);
	}

	function getOrderFields() {
		return array ( 'p1.page_namespace', 'p1.page_title' );
	}

	/**
	 * Cache page existence for performance
	 *
	 * @param $db DatabaseBase
	 * @param $res ResultWrapper
	 */
	function preprocessResults( $db, $res ) {
		$batch = new LinkBatch;
		foreach ( $res as $row ) {
			$batch->add( $row->namespace, $row->title );
			$batch->addObj( $this->getRedirectTarget( $row ) );
		}
		$batch->execute();

		// Back to start for display
		if ( $db->numRows( $res ) > 0 ) {
			// If there are no rows we get an error seeking.
			$db->dataSeek( $res, 0 );
		}
	}

	protected function getRedirectTarget( $row ) {
		if ( isset( $row->rd_title ) ) {
			return Title::makeTitle( $row->rd_namespace,
				$row->rd_title, $row->rd_fragment,
				$row->rd_interwiki
			);
		} else {
			$title = Title::makeTitle( $row->namespace, $row->title );
			$article = WikiPage::factory( $title );
			return $article->getRedirectTarget();
		}
	}

	function formatResult( $skin, $result ) {
		# Make a link to the redirect itself
		$rd_title = Title::makeTitle( $result->namespace, $result->title );
		$rd_link = Linker::link(
			$rd_title,
			null,
			array(),
			array( 'redirect' => 'no' )
		);

		# Find out where the redirect leads
		$target = $this->getRedirectTarget( $result );
		if( $target ) {
			# Make a link to the destination page
			$lang = $this->getLanguage();
			$arr = $lang->getArrow() . $lang->getDirMark();
			$targetLink = Linker::link( $target );
			return "$rd_link $arr $targetLink";
		} else {
			return "<del>$rd_link</del>";
		}
	}
}
