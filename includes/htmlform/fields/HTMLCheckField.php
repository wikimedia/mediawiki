<?php

/**
 * A checkbox field
 *
 * @stable to extend
 */
class HTMLCheckField extends HTMLFormField {

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function getInputHTML( $value ) {
		global $wgUseMediaWikiUIEverywhere;

		if ( !empty( $this->mParams['invert'] ) ) {
			$value = !$value;
		}

		$attr = $this->getTooltipAndAccessKey();
		$attr['id'] = $this->mID;

		$attr += $this->getAttributes( [ 'disabled', 'tabindex' ] );

		if ( $this->mClass !== '' ) {
			$attr['class'] = $this->mClass;
		}

		$attrLabel = [ 'for' => $this->mID ];
		if ( isset( $attr['title'] ) ) {
			// propagate tooltip to label
			$attrLabel['title'] = $attr['title'];
		}

		$chkLabel = Xml::check( $this->mName, $value, $attr ) .
			"\u{00A0}" .
			Html::rawElement( 'label', $attrLabel, $this->mLabel );

		if ( $wgUseMediaWikiUIEverywhere || $this->mParent instanceof VFormHTMLForm ) {
			$chkLabel = Html::rawElement(
				'div',
				[ 'class' => 'mw-ui-checkbox' ],
				$chkLabel
			);
		}

		return $chkLabel;
	}

	/**
	 * Get the OOUI version of this field.
	 * @stable to override
	 * @since 1.26
	 * @param string $value
	 * @return OOUI\CheckboxInputWidget The checkbox widget.
	 */
	public function getInputOOUI( $value ) {
		if ( !empty( $this->mParams['invert'] ) ) {
			$value = !$value;
		}

		$attr = $this->getTooltipAndAccessKeyOOUI();
		$attr['id'] = $this->mID;
		$attr['name'] = $this->mName;

		$attr += OOUI\Element::configFromHtmlAttributes(
			$this->getAttributes( [ 'disabled', 'tabindex' ] )
		);

		if ( $this->mClass !== '' ) {
			$attr['classes'] = [ $this->mClass ];
		}

		$attr['selected'] = $value;
		$attr['value'] = '1'; // Nasty hack, but needed to make this work

		return new OOUI\CheckboxInputWidget( $attr );
	}

	/**
	 * For a checkbox, the label goes on the right hand side, and is
	 * added in getInputHTML(), rather than HTMLFormField::getRow()
	 *
	 * ...unless OOUI is being used, in which case we actually return
	 * the label here.
	 *
	 * @stable to override
	 * @return string
	 */
	public function getLabel() {
		if ( $this->mParent instanceof OOUIHTMLForm ) {
			return $this->mLabel;
		} elseif (
			$this->mParent instanceof HTMLForm &&
			$this->mParent->getDisplayFormat() === 'div'
		) {
			return '';
		} else {
			return "\u{00A0}";
		}
	}

	/**
	 * Get label alignment when generating field for OOUI.
	 * @stable to override
	 * @return string 'left', 'right', 'top' or 'inline'
	 */
	protected function getLabelAlignOOUI() {
		return 'inline';
	}

	/**
	 * checkboxes don't need a label.
	 * @stable to override
	 * @return bool
	 */
	protected function needsLabel() {
		return false;
	}

	/**
	 * @stable to override
	 * @param WebRequest $request
	 *
	 * @return bool
	 */
	public function loadDataFromRequest( $request ) {
		$invert = isset( $this->mParams['invert'] ) && $this->mParams['invert'];

		// Fetch the value in either one of the two following case:
		// - we have a valid submit attempt (form was just submitted)
		// - we have a value (an URL manually built by the user, or GET form with no wpFormIdentifier)
		if ( $this->isSubmitAttempt( $request ) || $request->getCheck( $this->mName ) ) {
			return $invert
				? !$request->getBool( $this->mName )
				: $request->getBool( $this->mName );
		} else {
			return (bool)$this->getDefault();
		}
	}
}
