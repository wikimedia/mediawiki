<?php

namespace MediaWiki\HTMLForm\Field;

use MediaWiki\Html\Html;
use MediaWiki\HTMLForm\HTMLForm;
use MediaWiki\HTMLForm\HTMLFormField;
use MediaWiki\HTMLForm\OOUIHTMLForm;
use MediaWiki\HTMLForm\VFormHTMLForm;
use MediaWiki\Request\WebRequest;

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

		$isVForm = $this->mParent instanceof VFormHTMLForm;

		$chkDivider = "\u{00A0}";
		$chkLabel = Html::check( $this->mName, $value, $attr ) .
			$chkDivider .
			Html::rawElement( 'label', $attrLabel, $this->mLabel );

		if ( $isVForm ) {
			$chkLabelClass = 'mw-ui-checkbox';
			$chkLabel = Html::rawElement(
				'div',
				[ 'class' => $chkLabelClass ],
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
	 * @return \OOUI\CheckboxInputWidget The checkbox widget.
	 */
	public function getInputOOUI( $value ) {
		if ( !empty( $this->mParams['invert'] ) ) {
			$value = !$value;
		}

		$attr = $this->getTooltipAndAccessKeyOOUI();
		$attr['id'] = $this->mID;
		$attr['name'] = $this->mName;

		$attr += \OOUI\Element::configFromHtmlAttributes(
			$this->getAttributes( [ 'disabled', 'tabindex' ] )
		);

		if ( $this->mClass !== '' ) {
			$attr['classes'] = [ $this->mClass ];
		}

		$attr['selected'] = $value;
		$attr['value'] = '1'; // Nasty hack, but needed to make this work

		return new \OOUI\CheckboxInputWidget( $attr );
	}

	/** @inheritDoc */
	public function getInputCodex( $value, $hasErrors ) {
		if ( !empty( $this->mParams['invert'] ) ) {
			$value = !$value;
		}

		// Attributes for the <input> element.
		$attribs = $this->getTooltipAndAccessKey();
		$attribs['id'] = $this->mID;
		$attribs += $this->getAttributes( [ 'disabled', 'tabindex' ] );

		// The Xml class doesn't support an array of classes, so we have to provide a string.
		$inputClass = $this->mClass ?? '';
		$attribs['class'] = $inputClass . ' cdx-checkbox__input';

		// Attributes for the <label> element.
		$labelAttribs = [ 'for' => $this->mID ];
		$labelAttribs['class'] = [ 'cdx-checkbox__label' ];

		// Attributes for the wrapper <div>.
		$wrapperAttribs = [ 'class' => [ 'cdx-checkbox' ] ];
		if ( $hasErrors ) {
			$wrapperAttribs['class'][] = 'cdx-checkbox--status-error';
		}
		if ( isset( $attribs['title'] ) ) {
			// Propagate tooltip to the entire component (including the label).
			$wrapperAttribs['title'] = $attribs['title'];
		}

		// Construct the component.
		$checkIcon = "<span class=\"cdx-checkbox__icon\">\u{00A0}</span>";
		$innerContent = Html::check( $this->mName, $value, $attribs ) .
			$checkIcon .
			Html::rawElement( 'label', $labelAttribs, $this->mLabel );
		return Html::rawElement(
			'div',
			$wrapperAttribs,
			$innerContent
		);
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
			return $this->mLabel ?? '';
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
	 * @return bool
	 */
	public function getDefault() {
		return (bool)$this->mDefault;
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
			return $this->getDefault();
		}
	}
}

/** @deprecated class alias since 1.42 */
class_alias( HTMLCheckField::class, 'HTMLCheckField' );
