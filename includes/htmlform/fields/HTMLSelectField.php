<?php

/**
 * A select dropdown field.  Basically a wrapper for Xmlselect class
 */
class HTMLSelectField extends HTMLFormField {
	public function validate( $value, $alldata ) {
		$p = parent::validate( $value, $alldata );

		if ( $p !== true ) {
			return $p;
		}

		$validOptions = HTMLFormField::flattenOptions( $this->getOptions() );

		if ( in_array( strval( $value ), $validOptions, true ) ) {
			return true;
		} else {
			return $this->msg( 'htmlform-select-badoption' );
		}
	}

	public function getInputHTML( $value ) {
		$select = new XmlSelect( $this->mName, $this->mID, strval( $value ) );

		if ( !empty( $this->mParams['disabled'] ) ) {
			$select->setAttribute( 'disabled', 'disabled' );
		}

		$allowedParams = [ 'tabindex', 'size' ];
		$customParams = $this->getAttributes( $allowedParams );
		foreach ( $customParams as $name => $value ) {
			$select->setAttribute( $name, $value );
		}

		if ( $this->mClass !== '' ) {
			$select->setAttribute( 'class', $this->mClass );
		}

		$select->addOptions( $this->getOptions() );

		return $select->getHTML();
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

		return new OOUI\DropdownInputWidget( [
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
