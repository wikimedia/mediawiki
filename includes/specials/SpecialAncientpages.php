<?php
/**
 * Implements Special:Ancientpages
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
 * Implements Special:Ancientpages
 *
 * @ingroup SpecialPage
 */
class AncientPagesPage extends QueryPage {

	function __construct( $name = 'Ancientpages' ) {
		parent::__construct( $name );
	}

	function isExpensive() {
		return true;
	}

	function isSyndicated() { return false; }

	function getQueryInfo() {
		return array(
			'tables' => array( 'page', 'revision' ),
			'fields' => array( 'page_namespace AS namespace',
					'page_title AS title',
					'rev_timestamp AS value' ),
			'conds' => array( 'page_namespace' => MWNamespace::getContentNamespaces(),
					'page_is_redirect' => 0,
					'page_latest=rev_id' )
		);
	}

	function usesTimestamps() {
		return true;
	}

	function sortDescending() {
		return false;
	}

	function formatResult( $skin, $result ) {
		global $wgLang, $wgContLang;

		$d = $wgLang->timeanddate( wfTimestamp( TS_MW, $result->value ), true );
		$title = Title::makeTitle( $result->namespace, $result->title );
		$link = $skin->linkKnown(
			$title,
			htmlspecialchars( $wgContLang->convert( $title->getPrefixedText() ) )
		);
		return wfSpecialList( $link, htmlspecialchars($d) );
	}
}
