<?php

class HTMLTextAreaField extends HTMLFormField {
	const DEFAULT_COLS = 80;
	const DEFAULT_ROWS = 25;

	protected $mPlaceholder = '';

	/**
	 * @param array $params
	 *   - cols, rows: textarea size
	 *   - placeholder/placeholder-message: set HTML placeholder attribute
	 *   - spellcheck: set HTML spellcheck attribute
	 */
	public function __construct( $params ) {
		parent::__construct( $params );

		if ( isset( $params['placeholder-message'] ) ) {
			$this->mPlaceholder = $this->getMessage( $params['placeholder-message'] )->parse();
		} elseif ( isset( $params['placeholder'] ) ) {
			$this->mPlaceholder = $params['placeholder'];
		}
	}

	public function getCols() {
		return isset( $this->mParams['cols'] ) ? $this->mParams['cols'] : static::DEFAULT_COLS;
	}

	public function getRows() {
		return isset( $this->mParams['rows'] ) ? $this->mParams['rows'] : static::DEFAULT_ROWS;
	}

	public function getSpellCheck() {
		$val = isset( $this->mParams['spellcheck'] ) ? $this->mParams['spellcheck'] : null;
		if ( is_bool( $val ) ) {
			// "spellcheck" attribute literally requires "true" or "false" to work.
			return $val === true ? 'true' : 'false';
		}
		return null;
	}

	public function getInputHTML( $value ) {
		$attribs = [
				'id' => $this->mID,
				'cols' => $this->getCols(),
				'rows' => $this->getRows(),
				'spellcheck' => $this->getSpellCheck(),
			] + $this->getTooltipAndAccessKey();

		if ( $this->mClass !== '' ) {
			$attribs['class'] = $this->mClass;
		}
		if ( $this->mPlaceholder !== '' ) {
			$attribs['placeholder'] = $this->mPlaceholder;
		}

		$allowedParams = [
			'tabindex',
			'disabled',
			'readonly',
			'required',
			'autofocus'
		];

		$attribs += $this->getAttributes( $allowedParams );
		return Html::textarea( $this->mName, $value, $attribs );
	}

	function getInputOOUI( $value ) {
		if ( isset( $this->mParams['cols'] ) ) {
			throw new Exception( "OOUIHTMLForm does not support the 'cols' parameter for textareas" );
		}

		$attribs = $this->getTooltipAndAccessKey();

		if ( $this->mClass !== '' ) {
			$attribs['classes'] = [ $this->mClass ];
		}
		if ( $this->mPlaceholder !== '' ) {
			$attribs['placeholder'] = $this->mPlaceholder;
		}

		$allowedParams = [
			'tabindex',
			'disabled',
			'readonly',
			'required',
			'autofocus',
		];

		$attribs += OOUI\Element::configFromHtmlAttributes(
			$this->getAttributes( $allowedParams )
		);

		return new OOUI\TextInputWidget( [
			'id' => $this->mID,
			'name' => $this->mName,
			'multiline' => true,
			'value' => $value,
			'rows' => $this->getRows(),
		] + $attribs );
	}
}
