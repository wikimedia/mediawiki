<?php

namespace OOUI;

/**
 * Input widget with a text field.
 */
class TextInputWidget extends InputWidget {

	/* Properties */

	/**
	 * Input field type.
	 *
	 * @var string
	 */
	protected $type = null;

	/**
	 * Prevent changes.
	 *
	 * @var boolean
	 */
	protected $readOnly = false;

	/**
	 * Allow multiple lines of text.
	 *
	 * @var boolean
	 */
	protected $multiline = false;

	/**
	 * @param array $config Configuration options
	 * @param string $config['type'] HTML tag `type` attribute: 'text', 'password', 'search', 'email'
	 *   or 'url'. Ignored if `multiline` is true. (default: 'text')
	 *
	 *   Some values of `type` result in additional behaviors:
	 *   - `search`: implies `icon: 'search'` and `indicator: 'clear'`; when clicked, the indicator
	 *     empties the text field
	 * @param string $config['placeholder'] Placeholder text
	 * @param boolean $config['autofocus'] Ask the browser to focus this widget, using the 'autofocus'
	 *   HTML attribute (default: false)
	 * @param boolean $config['readOnly'] Prevent changes (default: false)
	 * @param number $config['maxLength'] Maximum allowed number of characters to input
	 * @param boolean $config['multiline'] Allow multiple lines of text (default: false)
	 * @param int $config['rows'] If multiline, number of visible lines in textarea
	 * @param boolean $config['required'] Mark the field as required.
	 *   Implies `indicator: 'required'`. (default: false)
	 * @param boolean $config['autocomplete'] If the field should support autocomplete
	 *   or not (default: true)
	 */
	public function __construct( array $config = array() ) {
		// Config initialization
		$config = array_merge( array(
			'type' => 'text',
			'readOnly' => false,
			'autofocus' => false,
			'required' => false,
			'autocomplete' => true,
		), $config );
		if ( $config['type'] === 'search' ) {
			if ( !array_key_exists( 'icon', $config ) ) {
				$config['icon'] = 'search';
			}
		}
		if ( $config['required'] ) {
			if ( !array_key_exists( 'indicator', $config ) ) {
				$config['indicator'] = 'required';
			}
		}

		// Parent constructor
		parent::__construct( $config );

		// Properties
		$this->type = $this->getSaneType( $config );
		$this->multiline = isset( $config['multiline'] ) ? (bool)$config['multiline'] : false;

		// Mixins
		$this->mixin( new IconElement( $this, $config ) );
		$this->mixin( new IndicatorElement( $this, $config ) );

		// Initialization
		$this
			->addClasses( array( 'oo-ui-textInputWidget', 'oo-ui-textInputWidget-type-' . $this->type ) )
			->appendContent( $this->icon, $this->indicator );
		$this->setReadOnly( $config['readOnly'] );
		if ( isset( $config['placeholder'] ) ) {
			$this->input->setAttributes( array( 'placeholder' => $config['placeholder'] ) );
		}
		if ( isset( $config['maxLength'] ) ) {
			$this->input->setAttributes( array( 'maxlength' => $config['maxLength'] ) );
		}
		if ( $config['autofocus'] ) {
			$this->input->setAttributes( array( 'autofocus' => 'autofocus' ) );
		}
		if ( $config['required'] ) {
			$this->input->setAttributes( array( 'required' => 'required', 'aria-required' => 'true' ) );
		}
		if ( !$config['autocomplete'] ) {
			$this->input->setAttributes( array( 'autocomplete' => 'off' ) );
		}
		if ( $this->multiline && isset( $config['rows'] ) ) {
			$this->input->setAttributes( array( 'rows' => $config['rows'] ) );
		}
	}

	/**
	 * Check if the widget is read-only.
	 *
	 * @return boolean
	 */
	public function isReadOnly() {
		return $this->readOnly;
	}

	/**
	 * Set the read-only state of the widget. This should probably change the widget's appearance and
	 * prevent it from being used.
	 *
	 * @param boolean $state Make input read-only
	 * @chainable
	 */
	public function setReadOnly( $state ) {
		$this->readOnly = (bool)$state;
		if ( $this->readOnly ) {
			$this->input->setAttributes( array( 'readonly' => 'readonly' ) );
		} else {
			$this->input->removeAttributes( array( 'readonly' ) );
		}
		return $this;
	}

	protected function getInputElement( $config ) {
		if ( isset( $config['multiline'] ) && $config['multiline'] ) {
			return new Tag( 'textarea' );
		} else {
			$input = new Tag( 'input' );
			$input->setAttributes( array( 'type' => $this->getSaneType( $config ) ) );
			return $input;
		}
	}

	private function getSaneType( $config ) {
		if ( isset( $config['multiline'] ) && $config['multiline'] ) {
			return 'multiline';
		} else {
			$type = in_array( $config['type'], array( 'text', 'password', 'search', 'email', 'url' ) ) ?
				$config['type'] :
				'text';
			return $type;
		}
	}

	/**
	 * Check if input supports multiple lines.
	 *
	 * @return boolean
	 */
	public function isMultiline() {
		return (bool)$this->multiline;
	}

	public function getConfig( &$config ) {
		if ( $this->isMultiline() ) {
			$config['multiline'] = true;
			$rows = $this->input->getAttribute( 'rows' );
			if ( $rows !== null ) {
				$config['rows'] = $rows;
			}
		} else {
			$type = $this->input->getAttribute( 'type' );
			if ( $type !== 'text' ) {
				$config['type'] = $type;
			}
		}
		if ( $this->isReadOnly() ) {
			$config['readOnly'] = true;
		}
		$placeholder = $this->input->getAttribute( 'placeholder' );
		if ( $placeholder !== null ) {
			$config['placeholder'] = $placeholder;
		}
		$maxlength = $this->input->getAttribute( 'maxlength' );
		if ( $maxlength !== null ) {
			$config['maxLength'] = $maxlength;
		}
		$autofocus = $this->input->getAttribute( 'autofocus' );
		if ( $autofocus !== null ) {
			$config['autofocus'] = true;
		}
		$required = $this->input->getAttribute( 'required' );
		$ariarequired = $this->input->getAttribute( 'aria-required' );
		if ( ( $required !== null ) || ( $ariarequired !== null ) ) {
			$config['required'] = true;
		}
		$autocomplete = $this->input->getAttribute( 'autocomplete' );
		if ( $autocomplete !== null ) {
			$config['autocomplete'] = false;
		}
		return parent::getConfig( $config );
	}
}
