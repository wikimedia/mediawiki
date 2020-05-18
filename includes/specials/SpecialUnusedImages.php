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
class SpecialUnusedImages extends ImageQueryPage {
	public function __construct( $name = 'Unusedimages' ) {
		parent::__construct( $name );
	}

	public function isExpensive() {
		return true;
	}

	protected function sortDescending() {
		return false;
	}

	public function isSyndicated() {
		return false;
	}

	public function getQueryInfo() {
		$retval = [
			'tables' => [ 'image', 'imagelinks' ],
			'fields' => [
				'namespace' => NS_FILE,
				'title' => 'img_name',
				'value' => 'img_timestamp',
			],
			'conds' => [ 'il_to IS NULL' ],
			'join_conds' => [ 'imagelinks' => [ 'LEFT JOIN', 'il_to = img_name' ] ]
		];

		if ( $this->getConfig()->get( 'CountCategorizedImagesAsUsed' ) ) {
			// Order is significant
			$retval['tables'] = [ 'image', 'page', 'categorylinks',
				'imagelinks' ];
			$retval['conds']['page_namespace'] = NS_FILE;
			$retval['conds'][] = 'cl_from IS NULL';
			$retval['conds'][] = 'img_name = page_title';
			$retval['join_conds']['categorylinks'] = [
				'LEFT JOIN', 'cl_from = page_id' ];
			$retval['join_conds']['imagelinks'] = [
				'LEFT JOIN', 'il_to = page_title' ];
		}

		return $retval;
	}

	public function usesTimestamps() {
		return true;
	}

	protected function getPageHeader() {
		if ( $this->getConfig()->get( 'CountCategorizedImagesAsUsed' ) ) {
			return $this->msg(
				'unusedimagestext-categorizedimgisused'
			)->parseAsBlock();
		}
		return $this->msg( 'unusedimagestext' )->parseAsBlock();
	}

	protected function getGroupName() {
		return 'maintenance';
	}
}
