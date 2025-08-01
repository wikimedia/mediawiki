<?php

namespace MediaWiki\HTMLForm\Field;

use MediaWiki\HTMLForm\HTMLFormField;
use MediaWiki\Xml\XmlSelect;

/**
 * A select dropdown field.  Basically a wrapper for Xmlselect class
 *
 * @stable to extend
 */
class HTMLSelectField extends HTMLFormField {

	/**
	 * @inheritDoc
	 * @stable to override
	 */
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

	/**
	 * @inheritDoc
	 * @stable to override
	 */
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

	/**
	 * @inheritDoc
	 * @stable to override
	 */
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

		return new \OOUI\DropdownInputWidget( [
			'name' => $this->mName,
			'id' => $this->mID,
			'options' => $this->getOptionsOOUI(),
			'value' => strval( $value ),
			'disabled' => $disabled,
		] + $attribs );
	}

	/** @inheritDoc */
	public function getInputCodex( $value, $hasErrors ) {
		$select = new XmlSelect( $this->mName, $this->mID, strval( $value ) );

		if ( !empty( $this->mParams['disabled'] ) ) {
			$select->setAttribute( 'disabled', 'disabled' );
		}

		$allowedParams = [ 'tabindex', 'size' ];
		$customParams = $this->getAttributes( $allowedParams );
		foreach ( $customParams as $name => $value ) {
			$select->setAttribute( $name, $value );
		}

		// TODO: Add support for error class once it's implemented in the Codex CSS-only Select.
		$selectClass = 'cdx-select';
		$selectClass .= $this->mClass !== '' ? ' ' . $this->mClass : '';
		$select->setAttribute( 'class', $selectClass );

		$select->addOptions( $this->getOptions() );

		return $select->getHTML();
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	protected function shouldInfuseOOUI() {
		return true;
	}
}

/** @deprecated class alias since 1.42 */
class_alias( HTMLSelectField::class, 'HTMLSelectField' );
