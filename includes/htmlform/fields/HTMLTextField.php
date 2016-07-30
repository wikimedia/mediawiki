<?php

class HTMLTextField extends HTMLFormField {
	protected $mPlaceholder = '';

	/**
	 * @param array $params
	 *   - type: HTML textfield type
	 *   - size: field size in characters (defaults to 45)
	 *   - placeholder/placeholder-message: set HTML placeholder attribute
	 *   - spellcheck: set HTML spellcheck attribute
	 *   - persistent: upon unsuccessful requests, retain the value (defaults to true, except
	 *     for password fields)
	 */
	public function __construct( $params ) {
		parent::__construct( $params );

		if ( isset( $params['placeholder-message'] ) ) {
			$this->mPlaceholder = $this->getMessage( $params['placeholder-message'] )->parse();
		} elseif ( isset( $params['placeholder'] ) ) {
			$this->mPlaceholder = $params['placeholder'];
		}
	}

	function getSize() {
		return isset( $this->mParams['size'] ) ? $this->mParams['size'] : 45;
	}

	function getSpellCheck() {
		$val = isset( $this->mParams['spellcheck'] ) ? $this->mParams['spellcheck'] : null;
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

	function getInputHTML( $value ) {
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
			'pattern',
			'title',
			'step',
			'list',
			'maxlength',
			'tabindex',
			'disabled',
			'required',
			'autofocus',
			'multiple',
			'readonly'
		];

		$attribs += $this->getAttributes( $allowedParams );

		# Extract 'type'
		$type = $this->getType( $attribs );
		return Html::input( $this->mName, $value, $type, $attribs );
	}

	protected function getType( &$attribs ) {
		$type = isset( $attribs['type'] ) ? $attribs['type'] : 'text';
		unset( $attribs['type'] );

		# Implement tiny differences between some field variants
		# here, rather than creating a new class for each one which
		# is essentially just a clone of this one.
		if ( isset( $this->mParams['type'] ) ) {
			switch ( $this->mParams['type'] ) {
				case 'int':
					$type = 'number';
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
			}
		}

		return $type;
	}

	function getInputOOUI( $value ) {
		if ( !$this->isPersistent() ) {
			$value = '';
		}

		$attribs = $this->getTooltipAndAccessKey();

		if ( $this->mClass !== '' ) {
			$attribs['classes'] = [ $this->mClass ];
		}
		if ( $this->mPlaceholder !== '' ) {
			$attribs['placeholder'] = $this->mPlaceholder;
		}

		# @todo Enforce pattern, step, required, readonly on the server side as
		# well
		$allowedParams = [
			'autofocus',
			'autosize',
			'disabled',
			'flags',
			'indicator',
			'maxlength',
			'readonly',
			'required',
			'tabindex',
			'type',
		];

		$attribs += OOUI\Element::configFromHtmlAttributes(
			$this->getAttributes( $allowedParams )
		);

		$type = $this->getType( $attribs );

		return $this->getInputWidget( [
			'id' => $this->mID,
			'name' => $this->mName,
			'value' => $value,
			'type' => $type,
		] + $attribs );
	}

	protected function getInputWidget( $params ) {
		return new OOUI\TextInputWidget( $params );
	}

	/**
	 * Returns an array of data-* attributes to add to the field.
	 *
	 * @return array
	 */
	protected function getDataAttribs() {
		return [];
	}
}
