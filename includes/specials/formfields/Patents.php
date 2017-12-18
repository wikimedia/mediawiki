<?php
/**
 * Patent selector for use on Special:Upload.
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
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License 2.0 or later
 */

/**
 * A Patent class for use on Special:Upload
 */
class Patents extends TemplateSelector {
	/**
	 * {@inheritdoc}
	 */
	public function __construct( $params ) {
		$params['message'] = empty( $params['patents'] )
			? wfMessage( 'patents' )->inContentLanguage()->plain()
			: $params['patents'];

		parent::__construct( $params );
	}

	/**
	 * {@inheritdoc}
	 */
	public function getInputHTML( $value ) {
		$options[$this->msg( 'nopatent' )->text()] = '';
		$options += $this->getOptionsArray();

		$field = new HTMLRadioField([
			'name' => 'wpPatent',
			'id' => 'wpPatent',
			'options' => $options,
		]);
		return $field->getInputHTML( $value );
	}

	/**
	 * @return array
	 */
	protected function getOptionsArray() {
		$lines = $this->getLines();
		$options = [];
		foreach ( $lines as $line ) {
			$msgObj = $this->msg( $line->text );
			$text = $msgObj->exists() ? $msgObj->text() : $line->text;

			$options[$text] = $line->template;
		}
		return $options;
	}
}
