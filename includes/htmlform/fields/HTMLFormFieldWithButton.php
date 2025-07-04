<?php

namespace MediaWiki\HTMLForm\Field;

use MediaWiki\Html\Html;
use MediaWiki\HTMLForm\HTMLFormField;

/**
 * Enables HTMLFormField elements to be build with a button.
 *
 * TODO This class should be a trait
 *
 * @stable to extend
 */
class HTMLFormFieldWithButton extends HTMLFormField {
	/** @var string CSS class for the button in this field */
	protected $mButtonClass = '';

	/** @var string|int Element ID for the button in this field */
	protected $mButtonId = '';

	/** @var string Name the button in this field */
	protected $mButtonName = '';

	/** @var string Type of the button in this field (e.g. button or submit) */
	protected $mButtonType = 'submit';

	/** @var string Value for the button in this field */
	protected $mButtonValue;

	/** @var string[] Value for the button in this field */
	protected $mButtonFlags = [ 'progressive' ];

	/**
	 * @stable to call
	 * @inheritDoc
	 */
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
		if ( isset( $info['buttondefault'] ) ) {
			$this->mButtonValue = $info['buttondefault'];
		}
		if ( isset( $info['buttontype'] ) ) {
			$this->mButtonType = $info['buttontype'];
		}
		if ( isset( $info['buttonflags'] ) ) {
			$this->mButtonFlags = $info['buttonflags'];
		}
		parent::__construct( $info );
	}

	/** @inheritDoc */
	public function getInputHTML( $value ) {
		$attr = [
			'class' => 'mw-htmlform-submit ' . $this->mButtonClass,
			'id' => $this->mButtonId,
		] + $this->getAttributes( [ 'disabled', 'tabindex' ] );

		return Html::input( $this->mButtonName, $this->mButtonValue, $this->mButtonType, $attr );
	}

	/** @inheritDoc */
	public function getInputOOUI( $value ) {
		return new \OOUI\ButtonInputWidget( [
			'name' => $this->mButtonName,
			'value' => $this->mButtonValue,
			'type' => $this->mButtonType,
			'label' => $this->mButtonValue,
			'flags' => $this->mButtonFlags,
			'id' => $this->mButtonId ?: null,
		] + \OOUI\Element::configFromHtmlAttributes(
			$this->getAttributes( [ 'disabled', 'tabindex' ] )
		) );
	}

	/**
	 * Combines the passed element with a button.
	 * @param string $element Element to combine the button with.
	 * @return string
	 */
	public function getElement( $element ) {
		return $element . "\u{00A0}" . $this->getInputHTML( '' );
	}
}

/** @deprecated class alias since 1.42 */
class_alias( HTMLFormFieldWithButton::class, 'HTMLFormFieldWithButton' );
