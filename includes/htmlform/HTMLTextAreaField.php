<?php

class HTMLTextAreaField extends HTMLFormField {
	const DEFAULT_COLS = 80;
	const DEFAULT_ROWS = 25;

	function getCols() {
		return isset( $this->mParams['cols'] ) ? $this->mParams['cols'] : static::DEFAULT_COLS;
	}

	function getRows() {
		return isset( $this->mParams['rows'] ) ? $this->mParams['rows'] : static::DEFAULT_ROWS;
	}

	function getSpellCheck() {
		$val = isset( $this->mParams['spellcheck'] ) ? $this->mParams['spellcheck'] : null;
		if ( is_bool( $val ) ) {
			// "spellcheck" attribute literally requires "true" or "false" to work.
			return $val === true ? 'true' : 'false';
		}
		return null;
	}

	function getInputHTML( $value ) {
		$attribs = [
				'id' => $this->mID,
				'cols' => $this->getCols(),
				'rows' => $this->getRows(),
				'spellcheck' => $this->getSpellCheck(),
			] + $this->getTooltipAndAccessKey();

		if ( $this->mClass !== '' ) {
			$attribs['class'] = $this->mClass;
		}

		$allowedParams = [
			'placeholder',
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

		$allowedParams = [
			'placeholder',
			'tabindex',
			'disabled',
			'readonly',
			'required',
			'autofocus',
		];

		$attribs += $this->getAttributes( $allowedParams, [
			'tabindex' => 'tabIndex',
			'readonly' => 'readOnly',
		] );

		return new OOUI\TextInputWidget( [
			'id' => $this->mID,
			'name' => $this->mName,
			'multiline' => true,
			'value' => $value,
			'rows' => $this->getRows(),
		] + $attribs );
	}
}
