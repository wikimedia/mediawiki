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
		global $wgUseMediaWikiUI;

		$select = new XmlSelect( $this->mName, $this->mID, strval( $value ) );

		if ( !empty( $this->mParams['disabled'] ) ) {
			$select->setAttribute( 'disabled', 'disabled' );
		}
		if ( $wgUseMediaWikiUI ) {
			$select->setAttribute( 'class', 'mw-ui-input' );
		}

		$allowedParams = array( 'tabindex', 'size' );
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
}
