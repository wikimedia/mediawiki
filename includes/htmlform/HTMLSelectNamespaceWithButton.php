<?php
/**
 * Creates a Html::namespaceSelector input field with a button assigned to the input field.
 */
class HTMLSelectNamespaceWithButton extends HTMLSelectNamespace {
	/** @var HTMLFormClassWithButton $mClassWithButton */
	protected $mClassWithButton = null;

	public function __construct( $info ) {
		$this->mClassWithButton = new HTMLFormFieldWithButton( $info );
		parent::__construct( $info );
	}

	public function getInputHTML( $value ) {
		return $this->mClassWithButton->getElement( parent::getInputHTML( $value ) );
	}

	/**
	 * Get a FieldLayout (or subclass thereof) to wrap this field in when using OOUI output.
	 * @return OOUI\ActionFieldLayout
	 */
	protected function getFieldLayoutOOUI( $inputField, $classes, $infusable ) {
		$buttonWidget = $this->mClassWithButton->getInputOOUI( '' );
		// TODO Too much duplication with parent class?
		return new OOUI\ActionFieldLayout( $inputField, $buttonWidget, array(
			'classes' => $classes,
			'align' => $this->getLabelAlignOOUI(),
			'label' => $this->getLabel(),
			'help' => $this->getHelpText(),
			'infusable' => $infusable,
		) );
	}
}
