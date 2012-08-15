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
			'fields' => array( 'namespace' => 'page_namespace',
					'title' => 'page_title',
					'value' => 'rev_timestamp' ),
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
		global $wgContLang;

		$d = $this->getLanguage()->userTimeAndDate( $result->value, $this->getUser() );
		$title = Title::makeTitle( $result->namespace, $result->title );
		$link = Linker::linkKnown(
			$title,
			htmlspecialchars( $wgContLang->convert( $title->getPrefixedText() ) )
		);
		return $this->getLanguage()->specialList( $link, htmlspecialchars( $d ) );
	}
}
