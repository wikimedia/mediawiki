<?php

/**
 * HTML form generation and submission handling, vertical-form style.
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
 * Compact stacked vertical format for forms.
 */
class OOUIHTMLForm extends HTMLForm {
	public function __construct( $descriptor, $context = null, $messagePrefix = '' ) {
		$this->getOutput()->enableOOUI();
		parent::__construct( $descriptor, $context, $messagePrefix );
	}

	/**
	 * Symbolic display format name.
	 * @var string
	 */
	protected $displayFormat = 'ooui';

	public static function loadInputFromParameters( $fieldname, $descriptor, HTMLForm $parent = null ) {
		$field = parent::loadInputFromParameters( $fieldname, $descriptor, $parent );
		$field->setShowEmptyLabel( false );
		return $field;
	}

	function getButtons() {
		$buttons = '';

		if ( $this->mShowSubmit ) {
			$attribs = array();

			if ( isset( $this->mSubmitID ) ) {
				$attribs['id'] = $this->mSubmitID;
			}

			if ( isset( $this->mSubmitName ) ) {
				$attribs['name'] = $this->mSubmitName;
			}

			if ( isset( $this->mSubmitTooltip ) ) {
				$attribs += Linker::tooltipAndAccesskeyAttribs( $this->mSubmitTooltip );
			}

			$attribs['classes'] = array(
				'mw-htmlform-submit',
				$this->mSubmitModifierClass,
			);

			$attribs['label'] = $this->getSubmitText();
			$attribs['flags'] = array( 'primary', 'constructive' );

			$buttons .= new OOUI\ButtonInputWidget( $attribs );
		}

		if ( $this->mShowReset ) {
			$buttons .= new OOUI\ButtonInputWidget( array(
				'type' => 'reset',
				'label' => $this->msg( 'htmlform-reset' )->text(),
			) );
		}

		foreach ( $this->mButtons as $button ) {
			$attrs = array();

			if ( $button['attribs'] ) {
				$attrs += $button['attribs'];
			}

			if ( isset( $button['id'] ) ) {
				$attrs['id'] = $button['id'];
			}

			$attrs['classes'] = isset( $attrs['class'] ) ? (array)$attrs['class'] : array();

			$buttons .= new OOUI\ButtonInputWidget( array(
				'type' => 'submit',
				'name' => $button['name'],
				'label' => $button['value'],
			) + $attrs );
		}

		$html = Html::rawElement( 'div',
			array( 'class' => 'mw-htmlform-submit-buttons' ), "\n$buttons" ) . "\n";

		return $html;
	}

	function getFormAttributes() {
		$attribs = parent::getFormAttributes();
		if ( !isset( $attribs['class'] ) ) {
			$attribs['class'] = '';
		}

		if ( is_string( $attribs['class'] ) ) {
			$attribs['class'] = trim( $attribs['class'] . ' mw-htmlform-ooui' );
		} else {
			$attribs['class'][] = 'mw-htmlform-ooui';
		}

		return $attribs;
	}
}
