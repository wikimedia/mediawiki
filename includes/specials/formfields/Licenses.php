<?php
/**
 * License selector for use on Special:Upload.
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
 * @copyright Copyright © 2005, Ævar Arnfjörð Bjarmason
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License 2.0 or later
 */

/**
 * A License class for use on Special:Upload
 */
class Licenses extends TemplateSelector {
	/**
	 * {@inheritdoc}
	 */
	public function __construct( $params ) {
		$params['message'] = empty( $params['licenses'] )
			? wfMessage( 'licenses' )->inContentLanguage()->plain()
			: $params['licenses'];

		parent::__construct( $params );
	}

	/**
	 * Accessor for $this->lines
	 *
	 * @return array
	 *
	 * @deprecated since 1.31 Use getLines() instead
	 */
	public function getLicenses() {
		return $this->getLines();
	}

	/**
	 * {@inheritdoc}
	 */
	public function getInputHTML( $value ) {
		// add a default "no license selected" option
		$default = $this->buildLine( '|nolicense' );
		array_unshift( $this->lines, $default );

		$html = parent::getInputHTML( $value );

		array_shift( $this->lines );
		return $html;
	}
}
