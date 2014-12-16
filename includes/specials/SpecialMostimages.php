<?php
/**
 * Implements Special:Mostimages
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
 * A special page that lists most used images
 *
 * @ingroup SpecialPage
 */
class MostimagesPage extends ImageQueryPage {
	function __construct( $name = 'Mostimages' ) {
		parent::__construct( $name );
	}

	function isExpensive() {
		return true;
	}

	function isSyndicated() {
		return false;
	}

	function getQueryInfo() {
		return array(
			'tables' => array( 'imagelinks' ),
			'fields' => array(
				'namespace' => NS_FILE,
				'title' => 'il_to',
				'value' => 'COUNT(*)'
			),
			'options' => array(
				'GROUP BY' => 'il_to',
				'HAVING' => 'COUNT(*) > 1'
			)
		);
	}

	function getCellHtml( $row ) {
		return $this->msg( 'nimagelinks' )->numParams( $row->value )->escaped() . '<br />';
	}

	protected function getGroupName() {
		return 'highuse';
	}
}
