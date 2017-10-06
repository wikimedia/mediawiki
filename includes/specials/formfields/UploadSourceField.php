<?php
/**
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
 * A form field that contains a radio box in the label
 */
class UploadSourceField extends HTMLTextField {

	/**
	 * @param array $cellAttributes
	 * @return string
	 */
	function getLabelHtml( $cellAttributes = [] ) {
		$id = $this->mParams['id'];
		$label = Html::rawElement( 'label', [ 'for' => $id ], $this->mLabel );

		if ( !empty( $this->mParams['radio'] ) ) {
			if ( isset( $this->mParams['radio-id'] ) ) {
				$radioId = $this->mParams['radio-id'];
			} else {
				// Old way. For the benefit of extensions that do not define
				// the 'radio-id' key.
				$radioId = 'wpSourceType' . $this->mParams['upload-type'];
			}

			$attribs = [
				'name' => 'wpSourceType',
				'type' => 'radio',
				'id' => $radioId,
				'value' => $this->mParams['upload-type'],
			];

			if ( !empty( $this->mParams['checked'] ) ) {
				$attribs['checked'] = 'checked';
			}

			$label .= Html::element( 'input', $attribs );
		}

		return Html::rawElement( 'td', [ 'class' => 'mw-label' ] + $cellAttributes, $label );
	}

	/**
	 * @return int
	 */
	function getSize() {
		return $this->mParams['size'] ?? 60;
	}
}
