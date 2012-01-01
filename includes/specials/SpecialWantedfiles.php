<?php
/**
 * Implements Special:Wantedfiles
 *
 * Copyright © 2008 Soxred93
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
 * @author Soxred93 <soxred93@gmail.com>
 */

/**
 * Querypage that lists the most wanted files
 *
 * @ingroup SpecialPage
 */
class WantedFilesPage extends WantedQueryPage {

	function __construct( $name = 'Wantedfiles' ) {
		parent::__construct( $name );
	}

	function getPageHeader() {
		# Specifically setting to use "Wanted Files" (NS_MAIN) as title, so as to get what
		# category would be used on main namespace pages, for those tricky wikipedia
		# admins who like to do {{#ifeq:{{NAMESPACE}}|foo|bar|....}}.
		$catMessage = wfMessage( 'broken-file-category' )
			->title( Title::newFromText( "Wanted Files", NS_MAIN ) )
			->inContentLanguage();
		
		if ( !$catMessage->isDisabled() ) {
			$category = Title::makeTitleSafe( NS_CATEGORY, $catMessage->text() );
		} else {
			$category = false;
		}

		if ( $category ) {
			return $this
				->msg( 'wantedfiletext-cat' )
				->params( $category->getFullText() )
				->parseAsBlock();
		} else {
			return $this
				->msg( 'wantedfiletext-nocat' )
				->parseAsBlock();
		}
	}

	/**
	 * KLUGE: The results may contain false positives for files
	 * that exist e.g. in a shared repo.  Setting this at least
	 * keeps them from showing up as redlinks in the output, even
	 * if it doesn't fix the real problem (bug 6220).
	 */
	function forceExistenceCheck() {
		return true;
	}

	function getQueryInfo() {
		return array (
			'tables' => array ( 'imagelinks', 'image' ),
			'fields' => array ( "'" . NS_FILE . "' AS namespace",
					'il_to AS title',
					'COUNT(*) AS value' ),
			'conds' => array ( 'img_name IS NULL' ),
			'options' => array ( 'GROUP BY' => 'il_to' ),
			'join_conds' => array ( 'image' =>
				array ( 'LEFT JOIN',
					array ( 'il_to = img_name' )
				)
			)
		);
	}
}
