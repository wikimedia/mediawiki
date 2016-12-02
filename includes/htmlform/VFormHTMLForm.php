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
class VFormHTMLForm extends HTMLForm {
	/**
	 * Wrapper and its legend are never generated in VForm mode.
	 * @var bool
	 */
	protected $mWrapperLegend = false;

	/**
	 * Symbolic display format name.
	 * @var string
	 */
	protected $displayFormat = 'vform';

	public function isVForm() {
		wfDeprecated( __METHOD__, '1.25' );
		return true;
	}

	public static function loadInputFromParameters( $fieldname, $descriptor,
		HTMLForm $parent = null
	) {
		$field = parent::loadInputFromParameters( $fieldname, $descriptor, $parent );
		$field->setShowEmptyLabel( false );
		return $field;
	}

	public function getHTML( $submitResult ) {
		// This is required for VForm HTMLForms that use that style regardless
		// of wgUseMediaWikiUIEverywhere (since they pre-date it).
		// When wgUseMediaWikiUIEverywhere is removed, this should be consolidated
		// with the addModuleStyles in SpecialPage->setHeaders.
		$this->getOutput()->addModuleStyles( [
			'mediawiki.ui',
			'mediawiki.ui.button',
			'mediawiki.ui.input',
			'mediawiki.ui.checkbox',
		] );

		return parent::getHTML( $submitResult );
	}

	protected function getFormAttributes() {
		$attribs = parent::getFormAttributes();
		$attribs['class'] = [ 'mw-htmlform', 'mw-ui-vform', 'mw-ui-container' ];
		return $attribs;
	}

	public function wrapForm( $html ) {
		// Always discard $this->mWrapperLegend
		return Html::rawElement( 'form', $this->getFormAttributes(), $html );
	}

	public function getButtons() {
		$buttons = '';

		if ( $this->mShowSubmit ) {
			$attribs = [];

			if ( isset( $this->mSubmitID ) ) {
				$attribs['id'] = $this->mSubmitID;
			}

			if ( isset( $this->mSubmitName ) ) {
				$attribs['name'] = $this->mSubmitName;
			}

			if ( isset( $this->mSubmitTooltip ) ) {
				$attribs += Linker::tooltipAndAccesskeyAttribs( $this->mSubmitTooltip );
			}

			$attribs['class'] = [
				'mw-htmlform-submit',
				'mw-ui-button mw-ui-big mw-ui-block',
			];
			foreach ( $this->mSubmitFlags as $flag ) {
				$attribs['class'][] = 'mw-ui-' . $flag;
			}

			$buttons .= Xml::submitButton( $this->getSubmitText(), $attribs ) . "\n";
		}

		if ( $this->mShowReset ) {
			$buttons .= Html::element(
				'input',
				[
					'type' => 'reset',
					'value' => $this->msg( 'htmlform-reset' )->text(),
					'class' => 'mw-ui-button mw-ui-big mw-ui-block',
				]
			) . "\n";
		}

		if ( $this->mShowCancel ) {
			$target = $this->mCancelTarget ?: Title::newMainPage();
			if ( $target instanceof Title ) {
				$target = $target->getLocalURL();
			}
			$buttons .= Html::element(
					'a',
					[
						'class' => 'mw-ui-button mw-ui-big mw-ui-block',
						'href' => $target,
					],
					$this->msg( 'cancel' )->text()
				) . "\n";
		}

		foreach ( $this->mButtons as $button ) {
			$attrs = [
				'type' => 'submit',
				'name' => $button['name'],
				'value' => $button['value']
			];

			if ( $button['attribs'] ) {
				$attrs += $button['attribs'];
			}

			if ( isset( $button['id'] ) ) {
				$attrs['id'] = $button['id'];
			}

			$attrs['class'] = isset( $attrs['class'] ) ? (array)$attrs['class'] : [];
			$attrs['class'][] = 'mw-ui-button mw-ui-big mw-ui-block';

			$buttons .= Html::element( 'input', $attrs ) . "\n";
		}

		if ( !$buttons ) {
			return '';
		}

		return Html::rawElement( 'div',
			[ 'class' => 'mw-htmlform-submit-buttons' ], "\n$buttons" ) . "\n";
	}
}
