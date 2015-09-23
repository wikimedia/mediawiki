<?php

namespace OOUI;

/**
 * A button that is an input widget. Intended to be used within a FormLayout.
 */
class ButtonInputWidget extends InputWidget {

	/* Static Properties */

	/**
	 * Disable generating `<label>` elements for buttons. One would very rarely need additional label
	 * for a button, and it's already a big clickable target, and it causes unexpected rendering.
	 */
	public static $supportsSimpleLabel = false;

	/* Properties */

	/**
	 * Whether to use `<input/>` rather than `<button/>`.
	 *
	 * @var boolean
	 */
	protected $useInputTag;

	private $labelElementMixin;

	/**
	 * @param array $config Configuration options
	 * @param string $config['type'] HTML tag `type` attribute, may be 'button', 'submit' or 'reset'
	 *   (default: 'button')
	 * @param boolean $config['useInputTag'] Whether to use `<input/>` rather than `<button/>`. Only
	 *   useful if you need IE 6 support in a form with multiple buttons. If you use this option,
	 *   icons and indicators will not be displayed, it won't be possible to have a non-plaintext
	 *   label, and it won't be possible to set a value (which will internally become identical to the
	 *   label). (default: false)
	 */
	public function __construct( array $config = array() ) {
		// Configuration initialization
		$config = array_merge( array( 'type' => 'button', 'useInputTag' => false ), $config );

		// Properties (must be set before parent constructor, which calls setValue())
		$this->useInputTag = $config['useInputTag'];

		// Parent constructor
		parent::__construct( $config );

		// Mixins
		$this->mixin( new ButtonElement( $this,
			array_merge( $config, array( 'button' => $this->input ) ) ) );
		$this->mixin( new IconElement( $this, $config ) );
		$this->mixin( new IndicatorElement( $this, $config ) );
		// HACK: We need to have access to the mixin to override the setLabel() method
		$this->mixin( $this->labelElementMixin = new LabelElement( $this, $config ) );
		$this->mixin( new TitledElement( $this,
			array_merge( $config, array( 'titled' => $this->input ) ) ) );

		// Initialization
		if ( !$config['useInputTag'] ) {
			$this->input->appendContent( $this->icon, $this->label, $this->indicator );
		}

		// HACK: This is done in LabelElement mixin, but doesn't call our overridden method because of
		// how we implement mixins. Switching to traits will fix that.
		$this->setLabel( isset( $config['label'] ) ? $config['label'] : null );

		$this->addClasses( array( 'oo-ui-buttonInputWidget' ) );
	}

	protected function getInputElement( $config ) {
		$type = in_array( $config['type'], array( 'button', 'submit', 'reset' ) ) ?
			$config['type'] :
			'button';
		$input = new Tag( $config['useInputTag'] ? 'input' : 'button' );
		$input->setAttributes( array( 'type' => $type ) );
		return $input;
	}

	/**
	 * Set label value.
	 *
	 * Overridden to support setting the 'value' of `<input/>` elements.
	 *
	 * @param string|null $label Label text
	 * @chainable
	 */
	public function setLabel( $label ) {
		$this->labelElementMixin->setLabel( $label );

		if ( $this->useInputTag ) {
			// Discard non-plaintext labels
			$label = is_string( $label ) ? $label : '';
			$this->input->setValue( $label );
		}

		return $this;
	}

	/**
	 * Set the value of the input.
	 *
	 * Overridden to disable for `<input/>` elements, which have value identical to the label.
	 *
	 * @param string $value New value
	 * @chainable
	 */
	public function setValue( $value ) {
		if ( !$this->useInputTag ) {
			parent::setValue( $value );
		}
		return $this;
	}

	public function getConfig( &$config ) {
		if ( $this->useInputTag ) {
			$config['useInputTag'] = true;
		}
		$config['type'] = $this->input->getAttribute( 'type' );
		return parent::getConfig( $config );
	}
}
