<?php
/**
 * Creates a text input field with a button assigned to the input field.
 */
class HTMLTextFieldWithButton extends HTMLTextField {
	/** @var HTMLFormClassWithButton $mClassWithButton */
	protected $mClassWithButton = null;

	public function __construct( $info ) {
		$this->mClassWithButton = new HTMLFormFieldWithButton( $info );
		parent::__construct( $info );
	}

	public function getInputHTML( $value ) {
		return $this->mClassWithButton->getElement( parent::getInputHTML( $value ) );
	}
}
