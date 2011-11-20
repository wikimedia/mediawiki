<?php
/**
 * Implements Special:Mostlinkedcategories
 *
 * Copyright © 2005, Ævar Arnfjörð Bjarmason
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
 * A querypage to show categories ordered in descending order by the pages in them
 *
 * @ingroup SpecialPage
 */
class MostlinkedCategoriesPage extends QueryPage {

	function __construct( $name = 'Mostlinkedcategories' ) {
		parent::__construct( $name );
	}

	function isSyndicated() { return false; }

	function getQueryInfo() {
		return array (
			'tables' => array ( 'category' ),
			'fields' => array ( 'cat_title AS title',
					NS_CATEGORY . ' AS namespace',
					'cat_pages AS value' ),
			'options' => array ( 'ORDER BY' => 'cat_pages' )
		);
	}

	function sortDescending() { return true; }

	/**
	 * Fetch user page links and cache their existence
	 *
	 * @param $db DatabaseBase
	 * @param $res DatabaseResult
	 */
	function preprocessResults( $db, $res ) {
		$batch = new LinkBatch;
		foreach ( $res as $row ) {
			$batch->add( NS_CATEGORY, $row->title );
		}
		$batch->execute();

		// Back to start for display
		if ( $db->numRows( $res ) > 0 ) {
			// If there are no rows we get an error seeking.
			$db->dataSeek( $res, 0 );
		}
	}

	/**
	 * @param $skin Skin
	 * @param  $result
	 * @return string
	 */
	function formatResult( $skin, $result ) {
		global $wgContLang;

		$nt = Title::makeTitle( NS_CATEGORY, $result->title );
		$text = $wgContLang->convert( $nt->getText() );

		$plink = Linker::link( $nt, htmlspecialchars( $text ) );

		$nlinks = $this->msg( 'nmembers' )->numParams( $result->value )->escaped();
		return $this->getLang()->specialList( $plink, $nlinks );
	}
}
