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
	private $currentCategoryCounts;

	function __construct( $name = 'Wantedcategories' ) {
		parent::__construct( $name );
	}

	function getQueryInfo() {
		return [
			'tables' => [ 'categorylinks', 'page' ],
			'fields' => [
				'namespace' => NS_CATEGORY,
				'title' => 'cl_to',
				'value' => 'COUNT(*)'
			],
			'conds' => [ 'page_title IS NULL' ],
			'options' => [ 'GROUP BY' => 'cl_to' ],
			'join_conds' => [ 'page' => [ 'LEFT JOIN',
				[ 'page_title = cl_to',
					'page_namespace' => NS_CATEGORY ] ] ]
		];
	}

	function preprocessResults( $db, $res ) {
		parent::preprocessResults( $db, $res );

		$this->currentCategoryCounts = [];

		if ( !$res->numRows() || !$this->isCached() ) {
			return;
		}

		// Fetch (hopefully) up-to-date numbers of pages in each category.
		// This should be fast enough as we limit the list to a reasonable length.

		$allCategories = [];
		foreach ( $res as $row ) {
			$allCategories[] = $row->title;
		}

		$categoryRes = $db->select(
			'category',
			[ 'cat_title', 'cat_pages' ],
			[ 'cat_title' => $allCategories ],
			__METHOD__
		);
		foreach ( $categoryRes as $row ) {
			$this->currentCategoryCounts[$row->cat_title] = intval( $row->cat_pages );
		}

		// Back to start for display
		$res->seek( 0 );
	}

	/**
	 * @param Skin $skin
	 * @param object $result Result row
	 * @return string
	 */
	function formatResult( $skin, $result ) {
		global $wgContLang;

		$nt = Title::makeTitle( $result->namespace, $result->title );
		$text = htmlspecialchars( $wgContLang->convert( $nt->getText() ) );

		if ( !$this->isCached() ) {
			// We can assume the freshest data
			$plink = Linker::link(
				$nt,
				$text,
				[],
				[],
				[ 'broken' ]
			);
			$nlinks = $this->msg( 'nmembers' )->numParams( $result->value )->escaped();
		} else {
			$plink = Linker::link( $nt, $text );

			$currentValue = isset( $this->currentCategoryCounts[$result->title] )
				? $this->currentCategoryCounts[$result->title]
				: 0;
			$cachedValue = intval( $result->value ); // T76910

			// If the category has been created or emptied since the list was refreshed, strike it
			if ( $nt->isKnown() || $currentValue === 0 ) {
				$plink = "<del>$plink</del>";
			}

			// Show the current number of category entries if it changed
			if ( $currentValue !== $cachedValue ) {
				$nlinks = $this->msg( 'nmemberschanged' )
					->numParams( $cachedValue, $currentValue )->escaped();
			} else {
				$nlinks = $this->msg( 'nmembers' )->numParams( $cachedValue )->escaped();
			}
		}

		return $this->getLanguage()->specialList( $plink, $nlinks );
	}

	protected function getGroupName() {
		return 'maintenance';
	}
}
