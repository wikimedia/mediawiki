<?php
/**
 * Implements Special:Unusedimages
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
 * A special page that lists unused images
 *
 * @ingroup SpecialPage
 */
class UnusedimagesPage extends ImageQueryPage {
	function __construct( $name = 'Unusedimages' ) {
		parent::__construct( $name );
	}

	function isExpensive() {
		return true;
	}

	function sortDescending() {
		return false;
	}

	function isSyndicated() {
		return false;
	}

	function getQueryInfo() {
		global $wgCountCategorizedImagesAsUsed;
		$retval = array (
			'tables' => array ( 'image', 'imagelinks' ),
			'fields' => array ( 'namespace' => NS_FILE,
					'title' => 'img_name',
					'value' => 'img_timestamp',
					'img_user', 'img_user_text',
					'img_description' ),
			'conds' => array ( 'il_to IS NULL' ),
			'join_conds' => array ( 'imagelinks' => array (
					'LEFT JOIN', 'il_to = img_name' ) )
		);

		if ( $wgCountCategorizedImagesAsUsed ) {
			// Order is significant
			$retval['tables'] = array ( 'image', 'page', 'categorylinks',
					'imagelinks' );
			$retval['conds']['page_namespace'] = NS_FILE;
			$retval['conds'][] = 'cl_from IS NULL';
			$retval['conds'][] = 'img_name = page_title';
			$retval['join_conds']['categorylinks'] = array (
					'LEFT JOIN', 'cl_from = page_id' );
			$retval['join_conds']['imagelinks'] = array (
					'LEFT JOIN', 'il_to = page_title' );
		}
		return $retval;
	}

	function usesTimestamps() {
		return true;
	}

	function getPageHeader() {
		return $this->msg( 'unusedimagestext' )->parseAsBlock();
	}

}
