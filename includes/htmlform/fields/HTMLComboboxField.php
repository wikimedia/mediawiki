<?php

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
	// FIXME Ewww, this shouldn't be adding any attributes not requested in $list :(
	public function getAttributes( array $list ) {
		$attribs = [
			'type' => 'text',
			'list' => $this->mName . '-datalist',
		] + parent::getAttributes( $list );

		return $attribs;
	}

	public function getInputHTML( $value ) {
		$datalist = new XmlSelect( false, $this->mName . '-datalist' );
		$datalist->setTagName( 'datalist' );
		$datalist->addOptions( $this->getOptions() );

		return parent::getInputHTML( $value ) . $datalist->getHTML();
	}

	public function getInputOOUI( $value ) {
		$disabled = false;
		$allowedParams = [ 'tabindex' ];
		$attribs = OOUI\Element::configFromHtmlAttributes(
			$this->getAttributes( $allowedParams )
		);

		if ( $this->mClass !== '' ) {
			$attribs['classes'] = [ $this->mClass ];
		}

		if ( !empty( $this->mParams['disabled'] ) ) {
			$disabled = true;
		}

		return new OOUI\ComboBoxInputWidget( [
			'name' => $this->mName,
			'id' => $this->mID,
			'options' => $this->getOptionsOOUI(),
			'value' => strval( $value ),
			'disabled' => $disabled,
		] + $attribs );
	}

	protected function shouldInfuseOOUI() {
		return true;
	}
}
