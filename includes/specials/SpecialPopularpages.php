<?php
/**
 * Implements Special:PopularPages
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
 * A special page that list most viewed pages
 *
 * @ingroup SpecialPage
 */
class PopularPagesPage extends QueryPage {

	function __construct( $name = 'Popularpages' ) {
		parent::__construct( $name );
	}

	function isExpensive() {
		# page_counter is not indexed
		return true;
	}

	function isSyndicated() { return false; }

	function getQueryInfo() {
		return array (
			'tables' => array( 'page' ),
			'fields' => array( 'page_namespace AS namespace',
					'page_title AS title',
					'page_counter AS value'),
			'conds' => array( 'page_is_redirect' => 0,
					'page_namespace' => MWNamespace::getContentNamespaces() ) );
	}

	/**
	 * @param $skin Skin
	 * @param $result
	 * @return string
	 */
	function formatResult( $skin, $result ) {
		global $wgContLang;
		$title = Title::makeTitle( $result->namespace, $result->title );
		$link = Linker::linkKnown(
			$title,
			htmlspecialchars( $wgContLang->convert( $title->getPrefixedText() ) )
		);
		$nv = $this->msg( 'nviews' )->numParams( $result->value )->escaped();
		return $this->getLanguage()->specialList( $link, $nv );
	}
}
