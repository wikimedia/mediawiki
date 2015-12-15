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
	protected $buttonLabel = null;

	/** @var array $mFlags Flags to add to OOUI Button widget */
	protected $mFlags = array();

	public function __construct( $info ) {
		$info['nodata'] = true;
		if ( isset( $info['flags'] ) ) {
			$this->mFlags = $info['flags'];
		}

		# Generate the label from a message, if possible
		if ( isset( $info['buttonlabel-message'] ) ) {
			$msgInfo = $info['buttonlabel-message'];

			if ( is_array( $msgInfo ) ) {
				$msg = array_shift( $msgInfo );
			} else {
				$msg = $msgInfo;
				$msgInfo = array();
			}

			$this->buttonLabel = $this->msg( $msg, $msgInfo )->parse();
		} elseif ( isset( $info['buttonlabel'] ) ) {
			if ( $info['buttonlabel'] === '&#160;' ) {
				// Apparently some things set &nbsp directly and in an odd format
				$this->buttonLabel = '&#160;';
			} else {
				$this->buttonLabel = htmlspecialchars( $info['buttonlabel'] );
			}
		} elseif ( isset( $info['buttonlabel-raw'] ) ) {
			$this->buttonLabel = $info['buttonlabel-raw'];
		}

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
			$flags .= ' ' . $prefix . 'button';
		}
		foreach ( $this->mFlags as $flag ) {
			$flags .= ' ' . $prefix . $flag;
		}
		$attr = array(
			'class' => 'mw-htmlform-submit ' . $this->mClass . $flags,
			'id' => $this->mID,
			'type' => $this->buttonType,
			'name' => $this->mName,
			'value' => $value,
		) + $this->getAttributes( array( 'disabled', 'tabindex' ) );

		if ( $this->isBadIE() ) {
			return Html::element( 'input', $attr );
		} else {
			return Html::rawElement( 'button', $attr, $this->buttonLabel ?: htmlspecialchars( $value ) );
		}
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
			'label' => !$this->isBadIE() && $this->buttonLabel
				? new OOUI\HtmlSnippet( $this->buttonLabel )
				: $value,
			'type' => $this->buttonType,
			'classes' => array( 'mw-htmlform-submit', $this->mClass ),
			'id' => $this->mID,
			'flags' => $this->mFlags,
			'useInputTag' => $this->isBadIE(),
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

	/**
	 * IE<8 has bugs with <button>, so we'll need to avoid them.
	 * @return bool Whether the request is from is a bad version of IE
	 */
	private function isBadIE() {
		$request = $this->mParent
			? $this->mParent->getRequest()
			: RequestContext::getMain()->getRequest();
		return preg_match( '/MSIE [1-7]\./i', $request->getHeader( 'User-Agent' ) );
	}
}
