<?php
/**
 * Implements Special:Wantedcategories
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
 */

/**
 * A querypage to list the most wanted categories - implements Special:Wantedcategories
 *
 * @ingroup SpecialPage
 */
class WantedCategoriesPage extends WantedQueryPage {

	function __construct( $name = 'Wantedcategories' ) {
		parent::__construct( $name );
	}

	function getQueryInfo() {
		return array (
			'tables' => array ( 'categorylinks', 'page' ),
			'fields' => array ( "'" . NS_CATEGORY . "' AS namespace",
					'cl_to AS title',
					'COUNT(*) AS value' ),
			'conds' => array ( 'page_title IS NULL' ),
			'options' => array ( 'GROUP BY' => 'cl_to' ),
			'join_conds' => array ( 'page' => array ( 'LEFT JOIN',
				array ( 'page_title = cl_to',
					'page_namespace' => NS_CATEGORY ) ) )
		);
	}

	/**
	 * @param $skin Skin
	 * @param $result
	 * @return string
	 */
	function formatResult( $skin, $result ) {
		global $wgContLang;

		$nt = Title::makeTitle( $result->namespace, $result->title );
		$text = htmlspecialchars( $wgContLang->convert( $nt->getText() ) );

		$plink = $this->isCached() ?
			Linker::link( $nt, $text ) :
			Linker::link(
				$nt,
				$text,
				array(),
				array(),
				array( 'broken' )
			);

		$nlinks = $this->msg( 'nmembers' )->numParams( $result->value )->escaped();
		return $this->getLanguage()->specialList( $plink, $nlinks );
	}
}
