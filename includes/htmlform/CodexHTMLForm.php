<?php

/**
 * HTML form generation using Codex components.
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

use MediaWiki\Html\Html;

/**
 * Codex based HTML form
 *
 * @since 1.41
 */
class CodexHTMLForm extends HTMLForm {
	/**
	 * Symbolic display format name.
	 * Note this is `vform` rather than `div`
	 * to avoid the div.mw-label wrapper
	 * on labels.
	 *
	 * @var string
	 */
	protected $displayFormat = 'vform';

	public static function loadInputFromParameters( $fieldname, $descriptor,
		HTMLForm $parent = null
	) {
		$field = parent::loadInputFromParameters( $fieldname, $descriptor, $parent );
		$field->setShowEmptyLabel( false );
		return $field;
	}

	public function getHTML( $submitResult ) {
		$this->getOutput()->addModuleStyles( [
			'codex-styles',
		] );

		return parent::getHTML( $submitResult );
	}

	protected function getFormAttributes() {
		$attribs = parent::getFormAttributes();
		$attribs['class'] = [ 'mw-htmlform', 'mw-htmlform-codex' ];
		return $attribs;
	}

	public function wrapForm( $html ) {
		return Html::rawElement( 'form', $this->getFormAttributes(), $html );
	}
}
