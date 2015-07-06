<?php
/**
 * Enables HTMLFormField elements to be build with a button.
 */
class HTMLFormFieldWithButton extends HTMLFormField {
	/** @var string $mButtonClass CSS class for the button in this field */
	protected $mButtonClass = '';

	/** @var string|integer $mButtonId Element ID for the button in this field */
	protected $mButtonId = '';

	/** @var string $mButtonName Name the button in this field */
	protected $mButtonName = '';

	/** @var string $mButtonType Type of the button in this field (e.g. button or submit) */
	protected $mButtonType = 'submit';

	/** @var string $mButtonType Value for the button in this field */
	protected $mButtonValue;

	/** @var string $mButtonType Value for the button in this field */
	protected $mButtonFlags = array( 'primary', 'progressive' );

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

	public function getInputHTML( $value ) {
		$attr = array(
			'class' => 'mw-htmlform-submit ' . $this->mButtonClass,
			'id' => $this->mButtonId,
		) + $this->getAttributes( array( 'disabled', 'tabindex' ) );

		return Html::input( $this->mButtonName, $this->mButtonValue, $this->mButtonType, $attr );
	}

	public function getInputOOUI( $value ) {
		return new OOUI\ButtonInputWidget( array(
			'label' => $this->mButtonValue,
			'type' => $this->mButtonType,
			'flags' => $this->mButtonFlags,
			'icon' => 'check',
		) );
	}

	/**
	 * Combines the passed element with a button.
	 * @param String $element Element to combine the button with.
	 * @return String
	 */
	public function getElement( $element ) {
		return $element . '&#160;' . $this->getInputHTML( '' );
	}

	/**
	 * Same as self::getElement() but for OOUI forms
	 * @param String $element Element to combine the button with.
	 * @return String
	 */
	public function getElementOOUI( $fields ) {
		# otherwise, wrap the fields into a horizontal aligned widget
		$horizontalAlignmentWidget = new OOUI\Widget( array(
			'classes' => array( 'oo-ui-horizontal-widget' ),
		) );
		$fields[] = $this->getInputOOUI( '', true );

		foreach ( $fields as $field ) {
			# Adding content after the fact does not play well with
			# infusability.  We should be using a proper Layout here.
			$horizontalAlignmentWidget->appendContent( $field );
		}

		# $horizontalAlignmentWidget is not infusable because
		# it manually added content after creation. If we embed it
		# in an infusable FieldLayout (through HTMLFormField), it will (recursively)
		# be made infusable. So protect the widget by wrapping it in a
		# FieldLayout which isn't infusable (by returning a string, instead of the field itself,
		# which will instruct HTMLFormField to wrap it into a HtmlSnippet Widget and a not
		# not infusable FieldLayout.
		return $horizontalAlignmentWidget->toString();
	}
}
