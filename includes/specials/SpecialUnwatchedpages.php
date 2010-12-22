<?php
/**
 * Implements Special:Unwatchedpages
 *
 * Copyright © 2005 Ævar Arnfjörð Bjarmason
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
 * @author Ævar Arnfjörð Bjarmason <avarab@gmail.com>
 */

/**
 * A special page that displays a list of pages that are not on anyones watchlist.
 *
 * @ingroup SpecialPage
 */
class UnwatchedpagesPage extends QueryPage {

	function __construct( $name = 'Unwatchedpages' ) {
		parent::__construct( $name, 'unwatchedpages' );
	}
	
	function isExpensive() { return true; }
	function isSyndicated() { return false; }

	function getQueryInfo() {
		return array (
			'tables' => array ( 'page', 'watchlist' ),
			'fields' => array ( 'page_namespace AS namespace',
					'page_title AS title',
					'page_namespace AS value' ),
			'conds' => array ( 'wl_title IS NULL',
					'page_is_redirect' => 0,
					"page_namespace != '" . NS_MEDIAWIKI .
					"'" ),
			'join_conds' => array ( 'watchlist' => array (
				'LEFT JOIN', array ( 'wl_title = page_title',
					'wl_namespace = page_namespace' ) ) )
		);
	}

	function sortDescending() { return false; }
	
	function getOrderFields() {
		return array( 'page_namespace', 'page_title' );
	}

	function formatResult( $skin, $result ) {
		global $wgContLang;

		$nt = Title::makeTitle( $result->namespace, $result->title );
		$text = $wgContLang->convert( $nt->getPrefixedText() );

		$plink = $skin->linkKnown(
			$nt,
			htmlspecialchars( $text )
		);
		$wlink = $skin->linkKnown(
			$nt,
			wfMsgHtml( 'watch' ),
			array(),
			array( 'action' => 'watch' )
		);

		return wfSpecialList( $plink, $wlink );
	}
}
