<?php

/**
 * Adds a generic button inline to the form. Does not do anything, you must add
 * click handling code in JavaScript. Use a HTMLSubmitField if you merely
 * wish to add a submit button to a form.
 *
 * @since 1.22
 */
class HTMLButtonField extends HTMLFormField {
	protected $buttonType = 'button';

	public function __construct( $info ) {
		$info['nodata'] = true;
		parent::__construct( $info );
	}

	public function getInputHTML( $value ) {
		$attr = array(
			'class' => 'mw-htmlform-submit ' . $this->mClass,
			'id' => $this->mID,
		) + $this->getAttributes( array( 'disabled', 'tabindex' ) );

		return Html::input( $this->mName, $value, $this->buttonType, $attr );
	}

	/**
	 * Get the OOUI widget for this field.
	 * @param string $value
	 * @return OOUI\ButtonInputWidget
	 */
	public function getInputOOUI( $value ) {
		return new OOUI\ButtonInputWidget( array(
			'name' => $this->mName,
			'value' => $value,
			'type' => $this->buttonType,
			'classes' => array( 'mw-htmlform-submit', $this->mClass ),
			'id' => $this->mID,
		) + $this->getAttributes( array( 'disabled', 'tabindex' ), array( 'tabindex' => 'tabIndex' ) ) );
	}

	protected function needsLabel() {
		return false;
	}

	/**
	 * Button cannot be invalid
	 *
	 * @param string $value
	 * @param array $alldata
	 *
	 * @return bool
	 */
	public function validate( $value, $alldata ) {
		return true;
	}
}
