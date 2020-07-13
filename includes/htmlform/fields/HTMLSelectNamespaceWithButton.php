<?php
/**
 * Creates a Html::namespaceSelector input field with a button assigned to the input field.
 *
 * @stable to extend
 */
class HTMLSelectNamespaceWithButton extends HTMLSelectNamespace {
	/** @var HTMLFormFieldWithButton */
	protected $mClassWithButton = null;

	/*
	 * @stable to call
	 */
	public function __construct( $info ) {
		$this->mClassWithButton = new HTMLFormFieldWithButton( $info );
		parent::__construct( $info );
	}

	public function getInputHTML( $value ) {
		return $this->mClassWithButton->getElement( parent::getInputHTML( $value ) );
	}
}
