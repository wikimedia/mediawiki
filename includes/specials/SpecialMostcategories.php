<?php
/**
 * Implements Special:Mostcategories
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
 * A special page that list pages that have highest category count
 *
 * @ingroup SpecialPage
 */
class MostcategoriesPage extends QueryPage {

	function __construct( $name = 'Mostcategories' ) {
		parent::__construct( $name );
	}

	function isExpensive() { return true; }
	function isSyndicated() { return false; }

	function getQueryInfo() {
		return array (
			'tables' => array ( 'categorylinks', 'page' ),
			'fields' => array ( 'page_namespace AS namespace',
					'page_title AS title',
					'COUNT(*) AS value' ),
			'conds' => array ( 'page_namespace' => MWNamespace::getContentNamespaces() ),
			'options' => array ( 'HAVING' => 'COUNT(*) > 1',
				'GROUP BY' => 'page_namespace, page_title' ),
			'join_conds' => array ( 'page' => array ( 'LEFT JOIN',
					'page_id = cl_from' ) )
		);
	}

	/**
	 * @param $skin Skin
	 * @param $result
	 * @return string
	 */
	function formatResult( $skin, $result ) {
		$title = Title::makeTitleSafe( $result->namespace, $result->title );

		$count = $this->msg( 'ncategories' )->numParams( $result->value )->escaped();
		$link = Linker::link( $title );
		return $this->getLanguage()->specialList( $link, $count );
	}
}
