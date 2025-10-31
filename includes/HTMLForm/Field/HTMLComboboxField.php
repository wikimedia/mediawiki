<?php

namespace MediaWiki\HTMLForm\Field;

use MediaWiki\Html\Html;
use MediaWiki\Xml\XmlSelect;

/**
 * A combo box field.
 *
 * You can think of it as a dropdown select with the ability to add custom options,
 * or as a text field with input suggestions (autocompletion).
 *
 * When JavaScript is not supported or enabled, it uses HTML5 `<datalist>` element.
 *
 * Besides the parameters recognized by HTMLTextField, the following are
 * recognized:
 *   options-messages - As for HTMLSelectField
 *   options - As for HTMLSelectField
 *   options-message - As for HTMLSelectField
 *
 * @stable to extend
 */
class HTMLComboboxField extends HTMLTextField {
	/** @inheritDoc */
	public function getAttributes( array $list ) {
		// FIXME Ewww, this shouldn't be adding any attributes not requested in $list :(
		$attribs = [
			'type' => 'text',
			'list' => $this->mName . '-datalist',
		] + parent::getAttributes( $list );

		return $attribs;
	}

	/** @inheritDoc */
	public function getInputHTML( $value ) {
		return parent::getInputHTML( $value ) .
			Html::rawElement( 'datalist',
				[ 'id' => $this->mName . '-datalist' ],
				XmlSelect::formatOptions( $this->getOptions() )
			);
	}

	/** @inheritDoc */
	public function getInputOOUI( $value ) {
		$disabled = false;
		$allowedParams = [ 'tabindex' ];
		$attribs = \OOUI\Element::configFromHtmlAttributes(
			$this->getAttributes( $allowedParams )
		);

		if ( $this->mClass !== '' ) {
			$attribs['classes'] = [ $this->mClass ];
		}

		if ( !empty( $this->mParams['disabled'] ) ) {
			$disabled = true;
		}

		if ( $this->mPlaceholder !== '' ) {
			$attribs['placeholder'] = $this->mPlaceholder;
		}

		return new \OOUI\ComboBoxInputWidget( [
			'name' => $this->mName,
			'id' => $this->mID,
			'options' => $this->getOptionsOOUI(),
			'value' => strval( $value ),
			'disabled' => $disabled,
		] + $attribs );
	}

	/** @inheritDoc */
	protected function shouldInfuseOOUI() {
		return true;
	}
}

/** @deprecated class alias since 1.42 */
class_alias( HTMLComboboxField::class, 'HTMLComboboxField' );
