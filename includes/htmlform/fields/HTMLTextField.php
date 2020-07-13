<?php

use OOUI\Widget;

/**
 * <input> field.
 *
 * Besides the parameters recognized by HTMLFormField, the following are
 * recognized:
 *   autocomplete - HTML autocomplete value (a boolean for on/off or a string according to
 *     https://html.spec.whatwg.org/multipage/forms.html#autofill )
 *
 * @stable to extend
 */
class HTMLTextField extends HTMLFormField {
	protected $mPlaceholder = '';

	/** @var bool HTML autocomplete attribute */
	protected $autocomplete;

	/**
	 * @stable to call
	 *
	 * @param array $params
	 *   - type: HTML textfield type
	 *   - size: field size in characters (defaults to 45)
	 *   - placeholder/placeholder-message: set HTML placeholder attribute
	 *   - spellcheck: set HTML spellcheck attribute
	 *   - persistent: upon unsuccessful requests, retain the value (defaults to true, except
	 *     for password fields)
	 */
	public function __construct( $params ) {
		if ( isset( $params['autocomplete'] ) && is_bool( $params['autocomplete'] ) ) {
			$params['autocomplete'] = $params['autocomplete'] ? 'on' : 'off';
		}

		parent::__construct( $params );

		if ( isset( $params['placeholder-message'] ) ) {
			$this->mPlaceholder = $this->getMessage( $params['placeholder-message'] )->text();
		} elseif ( isset( $params['placeholder'] ) ) {
			$this->mPlaceholder = $params['placeholder'];
		}
	}

	/**
	 * @stable to override
	 * @return int
	 */
	public function getSize() {
		return $this->mParams['size'] ?? 45;
	}

	public function getSpellCheck() {
		$val = $this->mParams['spellcheck'] ?? null;
		if ( is_bool( $val ) ) {
			// "spellcheck" attribute literally requires "true" or "false" to work.
			return $val === true ? 'true' : 'false';
		}
		return null;
	}

	public function isPersistent() {
		if ( isset( $this->mParams['persistent'] ) ) {
			return $this->mParams['persistent'];
		}
		// don't put passwords into the HTML body, they could get cached or otherwise leaked
		return !( isset( $this->mParams['type'] ) && $this->mParams['type'] === 'password' );
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function getInputHTML( $value ) {
		if ( !$this->isPersistent() ) {
			$value = '';
		}

		$attribs = [
				'id' => $this->mID,
				'name' => $this->mName,
				'size' => $this->getSize(),
				'value' => $value,
				'dir' => $this->mDir,
				'spellcheck' => $this->getSpellCheck(),
			] + $this->getTooltipAndAccessKey() + $this->getDataAttribs();

		if ( $this->mClass !== '' ) {
			$attribs['class'] = $this->mClass;
		}
		if ( $this->mPlaceholder !== '' ) {
			$attribs['placeholder'] = $this->mPlaceholder;
		}

		# @todo Enforce pattern, step, required, readonly on the server side as
		# well
		$allowedParams = [
			'type',
			'min',
			'max',
			'step',
			'title',
			'maxlength',
			'tabindex',
			'disabled',
			'required',
			'autofocus',
			'readonly',
			'autocomplete',
			// Only used in HTML mode:
			'pattern',
			'list',
			'multiple',
		];

		$attribs += $this->getAttributes( $allowedParams );

		# Extract 'type'
		$type = $this->getType( $attribs );
		return Html::input( $this->mName, $value, $type, $attribs );
	}

	protected function getType( &$attribs ) {
		$type = $attribs['type'] ?? 'text';
		unset( $attribs['type'] );

		# Implement tiny differences between some field variants
		# here, rather than creating a new class for each one which
		# is essentially just a clone of this one.
		if ( isset( $this->mParams['type'] ) ) {
			switch ( $this->mParams['type'] ) {
				case 'int':
					$type = 'number';
					$attribs['step'] = 1;
					break;
				case 'float':
					$type = 'number';
					$attribs['step'] = 'any';
					break;
				# Pass through
				case 'email':
				case 'password':
				case 'file':
				case 'url':
					$type = $this->mParams['type'];
					break;
				case 'textwithbutton':
					$type = $this->mParams['inputtype'] ?? 'text';
					break;
			}
		}

		return $type;
	}

	/**
	 * @inheritDoc
	 * @stable to override
	 */
	public function getInputOOUI( $value ) {
		if ( !$this->isPersistent() ) {
			$value = '';
		}

		$attribs = $this->getTooltipAndAccessKeyOOUI();

		if ( $this->mClass !== '' ) {
			$attribs['classes'] = [ $this->mClass ];
		}
		if ( $this->mPlaceholder !== '' ) {
			$attribs['placeholder'] = $this->mPlaceholder;
		}

		# @todo Enforce pattern, step, required, readonly on the server side as
		# well
		$allowedParams = [
			'type',
			'min',
			'max',
			'step',
			'title',
			'maxlength',
			'tabindex',
			'disabled',
			'required',
			'autofocus',
			'readonly',
			'autocomplete',
			// Only used in OOUI mode:
			'autosize',
			'flags',
			'indicator',
		];

		$attribs += OOUI\Element::configFromHtmlAttributes(
			$this->getAttributes( $allowedParams )
		);

		// FIXME T150983 downgrade autocomplete
		if ( isset( $attribs['autocomplete'] ) ) {
			if ( $attribs['autocomplete'] === 'on' ) {
				$attribs['autocomplete'] = true;
			} elseif ( $attribs['autocomplete'] === 'off' ) {
				$attribs['autocomplete'] = false;
			} else {
				unset( $attribs['autocomplete'] );
			}
		}

		$type = $this->getType( $attribs );
		if ( isset( $attribs['step'] ) && $attribs['step'] === 'any' ) {
			$attribs['step'] = null;
		}

		return $this->getInputWidget( [
			'id' => $this->mID,
			'name' => $this->mName,
			'value' => $value,
			'type' => $type,
			'dir' => $this->mDir,
		] + $attribs );
	}

	/**
	 * @stable to override
	 *
	 * @param array $params
	 *
	 * @return Widget
	 */
	protected function getInputWidget( $params ) {
		return new OOUI\TextInputWidget( $params );
	}

	/**
	 * Returns an array of data-* attributes to add to the field.
	 * @stable to override
	 *
	 * @return array
	 */
	protected function getDataAttribs() {
		return [];
	}
}
