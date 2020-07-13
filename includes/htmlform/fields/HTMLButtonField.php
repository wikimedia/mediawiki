<?php

/**
 * Adds a generic button inline to the form. Does not do anything, you must add
 * click handling code in JavaScript. Use a HTMLSubmitField if you merely
 * wish to add a submit button to a form.
 *
 * Additional recognized configuration parameters include:
 * - flags: OOUI flags for the button, see OOUI\FlaggedElement
 * - buttonlabel-message: Message to use for the button display text, instead
 *   of the value from 'default'. Overrides 'buttonlabel' and 'buttonlabel-raw'.
 * - buttonlabel: Text to display for the button display text, instead
 *   of the value from 'default'. Overrides 'buttonlabel-raw'.
 * - buttonlabel-raw: HTMLto display for the button display text, instead
 *   of the value from 'default'.
 * - formnovalidate: Set to true if clicking this button should suppress
 *   client-side form validation. Used in HTMLFormFieldCloner for add/remove
 *   buttons.
 *
 * @stable to extend
 * @since 1.22
 */
class HTMLButtonField extends HTMLFormField {
	protected $buttonType = 'button';
	protected $buttonLabel = null;

	/** @var array Flags to add to OOUI Button widget */
	protected $mFlags = [];

	protected $mFormnovalidate = false;

	/*
	 * @stable to call
	 */
	public function __construct( $info ) {
		$info['nodata'] = true;

		$this->setShowEmptyLabel( false );

		parent::__construct( $info );

		if ( isset( $info['flags'] ) ) {
			$this->mFlags = $info['flags'];
		}

		if ( isset( $info['formnovalidate'] ) ) {
			$this->mFormnovalidate = $info['formnovalidate'];
		}

		# Generate the label from a message, if possible
		if ( isset( $info['buttonlabel-message'] ) ) {
			$this->buttonLabel = $this->getMessage( $info['buttonlabel-message'] )->parse();
		} elseif ( isset( $info['buttonlabel'] ) ) {
			if ( $info['buttonlabel'] === '&#160;' || $info['buttonlabel'] === "\u{00A0}" ) {
				// Apparently some things set &nbsp directly and in an odd format
				$this->buttonLabel = "\u{00A0}";
			} else {
				$this->buttonLabel = htmlspecialchars( $info['buttonlabel'] );
			}
		} elseif ( isset( $info['buttonlabel-raw'] ) ) {
			$this->buttonLabel = $info['buttonlabel-raw'];
		}
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
		$attr = [
			'class' => 'mw-htmlform-submit ' . $this->mClass . $flags,
			'id' => $this->mID,
			'type' => $this->buttonType,
			'name' => $this->mName,
			'value' => $this->getDefault(),
			'formnovalidate' => $this->mFormnovalidate,
		] + $this->getAttributes( [ 'disabled', 'tabindex' ] );

		if ( $this->isBadIE() ) {
			return Html::element( 'input', $attr );
		} else {
			return Html::rawElement( 'button', $attr,
				$this->buttonLabel ?: htmlspecialchars( $this->getDefault() ) );
		}
	}

	/**
	 * Get the OOUI widget for this field.
	 * @stable to override
	 * @param string $value
	 * @return OOUI\ButtonInputWidget
	 */
	public function getInputOOUI( $value ) {
		return new OOUI\ButtonInputWidget( [
			'name' => $this->mName,
			'value' => $this->getDefault(),
			'label' => !$this->isBadIE() && $this->buttonLabel
				? new OOUI\HtmlSnippet( $this->buttonLabel )
				: $this->getDefault(),
			'type' => $this->buttonType,
			'classes' => [ 'mw-htmlform-submit', $this->mClass ],
			'id' => $this->mID,
			'flags' => $this->mFlags,
			'useInputTag' => $this->isBadIE(),
		] + OOUI\Element::configFromHtmlAttributes(
			$this->getAttributes( [ 'disabled', 'tabindex' ] )
		) );
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	protected function needsLabel() {
		return false;
	}

	/**
	 * Button cannot be invalid
	 * @stable to override
	 *
	 * @param string $value
	 * @param array $alldata
	 *
	 * @return bool|string|Message
	 */
	public function validate( $value, $alldata ) {
		return true;
	}

	/**
	 * IE<8 has bugs with <button>, so we'll need to avoid them.
	 * @return bool Whether the request is from a bad version of IE
	 */
	private function isBadIE() {
		$request = $this->mParent
			? $this->mParent->getRequest()
			: RequestContext::getMain()->getRequest();
		return (bool)preg_match( '/MSIE [1-7]\./i', $request->getHeader( 'User-Agent' ) );
	}
}
