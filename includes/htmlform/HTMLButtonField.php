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

	/** @var array $mFlags Flags to add to OOUI Button widget */
	protected $mFlags = array();

	public function __construct( $info ) {
		$info['nodata'] = true;
		if ( isset( $info['flags'] ) )
			$this->mFlags = $info['flags'];
		parent::__construct( $info );
	}

	public function getInputHTML( $value ) {
		$flags = '';
		$prefix = 'mw-htmlform-';
		if ( $this->mParent instanceof VFormHTMLForm ||
			$this->mParent->getConfig()->get( 'UseMediaWikiUIEverywhere' )
		) {
			$prefix = 'mw-ui-';
			// add mw-ui-button separately, so the descriptor doesn't need to set it
			$flags .= ' ' . $prefix.'button';
		}
		foreach ( $this->mFlags as $flag ) {
			$flags .= ' ' . $prefix . $flag;
		}
		$attr = array(
			'class' => 'mw-htmlform-submit ' . $this->mClass . $flags,
			'id' => $this->mID,
		) + $this->getAttributes( array( 'disabled', 'tabindex' ) );

		return Html::input( $this->mName, $value, $this->buttonType, $attr );
	}

	/**
	 * Get the OOUI widget for this field.
	 * @param string $value
	 * @return OOUI\\ButtonInputWidget
	 */
	public function getInputOOUI( $value ) {
		return new OOUI\ButtonInputWidget( array(
			'name' => $this->mName,
			'value' => $value,
			'label' => $value,
			'type' => $this->buttonType,
			'classes' => array( 'mw-htmlform-submit', $this->mClass ),
			'id' => $this->mID,
			'flags' => $this->mFlags,
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
