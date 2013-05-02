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

	protected function needsLabel() {
		return false;
	}

	/**
	 * Button cannot be invalid
	 *
	 * @param $value String
	 * @param $alldata Array
	 *
	 * @return Bool
	 */
	public function validate( $value, $alldata ) {
		return true;
	}
}
