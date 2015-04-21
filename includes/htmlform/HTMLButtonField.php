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
		$useMediaWikiUIEverywhere = $this->getConfig()->get( 'UseMediaWikiUIEverywhere' );
		$attr = array(
			'class' => 'mw-htmlform-submit ' . $this->mClass,
			'id' => $this->mID,
		) + $this->getAttributes( array( 'disabled', 'tabindex' ) );

		if ( $useMediaWikiUIEverywhere ) {
			if ( $this->buttonType === 'submit' ) {
				$attr['class'] .= 'mw-ui-button mw-ui-constructive';
			} else {
				$attr['class'] .= 'mw-ui-button mw-ui-progressive';
			}
		}

		return Html::input( $this->mName, $value, $this->buttonType, $attr );
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
