<?php
/**
 * Class for generating clickable toggle links for a list of checkboxes.
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
 */

/**
 * Class for generating clickable toggle links for a list of checkboxes.
 */
class ListToggle {
	/** @var OutputPage */
	private $output;

	public function __construct( OutputPage $output ) {
		$this->output = $output;

		$output->addModules( 'mediawiki.checkboxtoggle' );
		$output->addModuleStyles( 'mediawiki.checkboxtoggle.styles' );
	}

	private function checkboxLink( $checkboxType ) {
		return Html::element(
			'a', [ 'href' => '#', 'class' => 'mw-checkbox-' . $checkboxType ],
			$this->output->msg( 'checkbox-' . $checkboxType )->text()
		);
	}

	/**
	 * @return string
	 */
	public function getHTML() {
		// Select: All, None, Invert
		$links = [];
		$links[] = $this->checkboxLink( 'all' );
		$links[] = $this->checkboxLink( 'none' );
		$links[] = $this->checkboxLink( 'invert' );

		return Html::rawElement( 'div',
			[
				'class' => 'mw-checkbox-toggle-controls'
			],
			$this->output->msg( 'checkbox-select' )
				->rawParams( $this->output->getLanguage()->commaList( $links ) )->escaped()
		);
	}
}
