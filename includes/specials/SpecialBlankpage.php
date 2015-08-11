<?php
/**
 * Implements Special:Blankpage
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
 * Special page designed for basic benchmarking of
 * MediaWiki since it doesn't really do much.
 *
 * @ingroup SpecialPage
 */
class SpecialBlankpage extends UnlistedSpecialPage {
	public function __construct() {
		parent::__construct( 'Blankpage' );
	}

	public function execute( $par ) {
		$this->setHeaders();
		$this->getOutput()->enableOOUI();
		$this->getOutput()->addHTML( new MediaWiki\Widget\NamespaceInputWidget( array(
			'infusable' => true,
			'value' => 6,
			'name' => 'ns',
			'id' => 'ns-id',
			'includeAllValue' => 'foo',
		) ) );
		$this->getOutput()->addHTML( new MediaWiki\Widget\ComplexNamespaceInputWidget( array(
			'infusable' => true,
			'namespace' => array(
				'value' => 6,
				'name' => 'ns',
				'id' => 'ns-id',
				'includeAllValue' => 'foo',
			),
			'invert' => array(
				'name' => 'invert',
			),
			'invertLabel' => array(
				'label' => 'Invert!',
			),
			'associated' => array(
				'name' => 'associated',
			),
			'associatedLabel' => array(
				'label' => 'With associated!',
			),
		) ) );
	}
}
