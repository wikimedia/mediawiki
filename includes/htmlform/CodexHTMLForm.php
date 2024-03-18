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

namespace MediaWiki\HTMLForm;

use MediaWiki\Html\Html;
use MediaWiki\Parser\Sanitizer;

/**
 * Codex based HTML form
 *
 * @since 1.41
 */
class CodexHTMLForm extends HTMLForm {

	protected $displayFormat = 'codex';

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

	/**
	 * @inheritDoc
	 */
	protected function formatField( HTMLFormField $field, $value ) {
		return $field->getCodex( $value );
	}

	protected function getFormAttributes() {
		$attribs = parent::getFormAttributes();
		$attribs['class'] = [ 'mw-htmlform', 'mw-htmlform-codex' ];
		return $attribs;
	}

	public function wrapForm( $html ) {
		return Html::rawElement( 'form', $this->getFormAttributes(), $html );
	}

	protected function wrapFieldSetSection( $legend, $section, $attributes, $isRoot ) {
		$attributes['class'] = 'cdx-field';
		$legendElement = Html::rawElement( 'legend', [ 'class' => [ 'cdx-label' ] ], $legend );
		return Html::rawElement( 'fieldset', $attributes, "$legendElement\n$section" ) . "\n";
	}

	/**
	 * Note that this method returns HTML, while the parent method specifies that it should return
	 * a plain string. This method is only used to get the `$legend` argument of the
	 * wrapFieldSetSection() call, so we can be reasonably sure that returning HTML here is okay.
	 *
	 * @inheritDoc
	 */
	public function getLegend( $key ) {
		$legendText = $this->msg(
			$this->mMessagePrefix ? "{$this->mMessagePrefix}-$key" : $key
		)->text();
		$legendTextMarkup = Html::element(
			'span',
			[ 'class' => [ 'cdx-label__label__text' ] ],
			$legendText
		);

		$isOptional = $this->mSections[$key]['optional'] ?? false;
		$optionalFlagMarkup = '';
		if ( $isOptional ) {
			$optionalFlagMarkup = Html::element(
				'span',
				[ 'class' => [ 'cdx-label__label__optional-flag' ] ],
				$this->msg( 'word-separator' )->text() . $this->msg( 'htmlform-optional-flag' )->text()
			);
		}

		$descriptionMarkup = '';
		if ( isset( $this->mSections[$key]['description-message'] ) ) {
			$needsParse = $this->mSections[ $key ][ 'description-message-parse' ] ?? false;
			$descriptionMessage = $this->msg( $this->mSections[ $key ][ 'description-message' ] );
			$descriptionMarkup = Html::rawElement(
				'span',
				[ 'class' => [ 'cdx-label__description' ] ],
				$needsParse ? $descriptionMessage->parse() : $descriptionMessage->escaped()
			);
		} elseif ( isset( $this->mSections[$key]['description'] ) ) {
			$descriptionMarkup = Html::element(
				'span',
				[ 'class' => [ 'cdx-label__description' ] ],
				$this->mSections[ $key ][ 'description' ]
			);
		}

		return Html::rawElement(
			'span',
			[ 'class' => [ 'cdx-label__label' ] ],
			$legendTextMarkup . $optionalFlagMarkup
		) . $descriptionMarkup;
	}

	protected function formatSection( array $fieldsHtml, $sectionName, $anyFieldHasLabel ) {
		if ( !$fieldsHtml ) {
			// Do not generate any wrappers for empty sections. Sections may be empty if they only
			// have subsections, but no fields. A legend will still be added in
			// wrapFieldSetSection().
			return '';
		}

		$html = implode( '', $fieldsHtml );

		if ( $sectionName ) {
			$attribs = [
				'id' => Sanitizer::escapeIdForAttribute( $sectionName ),
				'class' => [ 'cdx-field__control' ]
			];

			return Html::rawElement( 'div', $attribs, $html );
		}

		return $html;
	}
}

/** @deprecated since 1.42 */
class_alias( CodexHTMLForm::class, 'CodexHTMLForm' );
