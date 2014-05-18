<?php

/**
 * A select dropdown field.  Basically a wrapper for Xmlselect class
 */
class HTMLSelectField extends HTMLFormField {
	function validate( $value, $alldata ) {
		$p = parent::validate( $value, $alldata );

		if ( $p !== true ) {
			return $p;
		}

		$validOptions = HTMLFormField::flattenOptions( $this->getOptions() );

		if ( in_array( strval( $value ), $validOptions, true ) ) {
			return true;
		} else {
			return $this->msg( 'htmlform-select-badoption' )->parse();
		}
	}

	function getInputHTML( $value ) {
		$select = new XmlSelect( $this->mName, $this->mID, strval( $value ) );

		if ( !empty( $this->mParams['disabled'] ) ) {
			$select->setAttribute( 'disabled', 'disabled' );
		}

		if ( isset( $this->mParams['tabindex'] ) ) {
			$select->setAttribute( 'tabindex', $this->mParams['tabindex'] );
		}

		if ( $this->mClass !== '' ) {
			$select->setAttribute( 'class', $this->mClass );
		}

		$select->addOptions( $this->getOptions() );

		return $select->getHTML();
	}
}
