<?php
/**
 * Creates a text input field with a button assigned to the input field.
 */
class HTMLTextFieldWithButton extends HTMLTextField {
	/** @var string $mButtonClass CSS class for the button in this field */
	protected $mButtonClass = '';

	/** @var string|integer $mButtonId Element ID for the button in this field */
	protected $mButtonId = '';

	/** @var string $mButtonName Name the button in this field */
	protected $mButtonName = '';

	/** @var string $mButtonType Type of the button in this field (e.g. button or submit) */
	protected $mButtonType = 'button';

	/** @var string $mButtonType Value for the button in this field */
	protected $mButtonValue;

	public function __construct( $info ) {
		if ( isset( $info['buttonclass'] ) ) {
			$this->mButtonClass = $info['buttonclass'];
		}
		if ( isset( $info['buttonid'] ) ) {
			$this->mButtonId = $info['buttonid'];
		}
		if ( isset( $info['buttonname'] ) ) {
			$this->mButtonName = $info['buttonname'];
		}
		if ( isset( $info['buttonvalue'] ) ) {
			$this->mButtonValue = $info['buttonvalue'];
		}
		if ( isset( $info['buttontype'] ) ) {
			$this->mButtonType = $info['buttontype'];
		}
		parent::__construct( $info );
	}

	public function getInputHTML( $value ) {
		$attr = array(
			'class' => 'mw-htmlform-submit ' . $this->mButtonClass,
			'id' => $this->mButtonId,
		) + $this->getAttributes( array( 'disabled', 'tabindex' ) );

		$input = parent::getInputHTML( $value ) . '&#160;' .
			Html::input( $this->mButtonName, $this->mButtonValue, $this->mButtonType, $attr );

		return $input;
	}
}