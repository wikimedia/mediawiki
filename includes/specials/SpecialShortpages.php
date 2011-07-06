<?php
/**
 * Implements Special:Shortpages
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
 * SpecialShortpages extends QueryPage. It is used to return the shortest
 * pages in the database.
 *
 * @ingroup SpecialPage
 */
class ShortPagesPage extends QueryPage {

	function __construct( $name = 'Shortpages' ) {
		parent::__construct( $name );
	}

	// inexpensive?
	/**
	 * This query is indexed as of 1.5
	 */
	function isExpensive() {
		return true;
	}

	function isSyndicated() {
		return false;
	}

	function getQueryInfo() {
		return array (
			'tables' => array ( 'page' ),
			'fields' => array ( 'page_namespace AS namespace',
					'page_title AS title',
					'page_len AS value' ),
			'conds' => array ( 'page_namespace' => MWNamespace::getContentNamespaces(),
					'page_is_redirect' => 0 ),
			'options' => array ( 'USE INDEX' => 'page_len' )
		);
	}

	function getOrderFields() {
		return array( 'page_len' );
	}

	/**
	 * @param $db DatabaseBase
	 * @param $res
	 * @return void
	 */
	function preprocessResults( $db, $res ) {
		# There's no point doing a batch check if we aren't caching results;
		# the page must exist for it to have been pulled out of the table
		if( $this->isCached() ) {
			$batch = new LinkBatch();
			foreach ( $res as $row ) {
				$batch->add( $row->namespace, $row->title );
			}
			$batch->execute();
			if ( $db->numRows( $res ) > 0 ) {
				$db->dataSeek( $res, 0 );
			}
		}
	}

	function sortDescending() {
		return false;
	}

	function formatResult( $skin, $result ) {
		global $wgLang;
		$dm = $wgLang->getDirMark();

		$title = Title::makeTitle( $result->namespace, $result->title );
		if ( !$title ) {
			return '<!-- Invalid title ' .  htmlspecialchars( "{$result->namespace}:{$result->title}" ). '-->';
		}
		$hlink = $skin->linkKnown(
			$title,
			wfMsgHtml( 'hist' ),
			array(),
			array( 'action' => 'history' )
		);
		$plink = $this->isCached()
					? $skin->link( $title )
					: $skin->linkKnown( $title );
		$size = wfMessage( 'nbytes', $wgLang->formatNum( $result->value ) )->escaped();

		return $title->exists()
				? "({$hlink}) {$dm}{$plink} {$dm}[{$size}]"
				: "<del>({$hlink}) {$dm}{$plink} {$dm}[{$size}]</del>";
	}
}
